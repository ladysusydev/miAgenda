<?php
class FPDF2 extends FPDI
{
        function GetCellMargin()        {       return $this->cMargin;  }
        function GetLeftMargin()        {       return $this->lMargin;  }
        function GetRightMargin()       {       return $this->rMargin;  }
        function GetTopMargin()         {       return $this->tMargin;  }
        function GetBottomMargin()      {       return $this->bMargin;  }
        function GetPaperHeight()       {       return $this->h;        }
        function GetPaperWidth()        {       return $this->w;        }
        function GetPrintableHeight()   {       return $this->h-$this->GetTopMargin()-$this->GetBottomMargin(); }
        function GetPrintableWidth()    {       return $this->w-$this->GetLeftMargin()-$this->GetRightMargin(); }
        function GetFontHeight()        {       return $this->FontSizePt/$this->k;      }
        function GetAvailableHeight()   {       return $this->GetPaperHeight()-$this->GetY()-$this->GetBottomMargin();  }

        // ------------------------------------- Split Text in rows ------------------------------------
        function SplitInRows($txt,$w)
        {       $realwidth=$w-2*$this->cMargin;
                $row=NULL;
                $paragraph=explode("\n",$txt);
                $N=NULL; $i=0; $spaceLength=$this->GetStringWidth(' ');
                foreach($paragraph as $p)
                {       $rowLength=0; $pos1=$pos2=0;
                        $word=explode(' ',$p);
                        foreach($word as $wrd)
                        {       $wordLength=strlen($wrd);
                                $l=$this->GetStringWidth($wrd);
                                $rowLength+=$l;
                                if($rowLength>$realwidth)
                                {       $row[]=trim(substr($p,$pos1,$pos2-$pos1));
                                        $rowLength=$l+$spaceLength;
                                        $pos1=$pos2;
                                        $pos2+=$wordLength+1;
                                }
                                else
                                {       $rowLength+=$spaceLength;
                                        $pos2+=$wordLength+1;
                                }
                        }
                        $row[]= substr($p,$pos1);
                }
                return $row;
        }
        
         // ---------------------------------------------------------------------------------------------

        // -------------------------------------- Special Cell -----------------------------------------
/*      Parameters:
        - w: width
        - h: height
        - txt: text to write in cell
        - border: border drawing.
        - halign: horizontal align (L: Left, C: Center, R: Right, J: Justified)
        - valign: vertical align (T: Top, M: Medium; B: Botom, J: Justified)
        - fill: Filling flag
        - overflow: Flag that indicates whether the text should be cropped or not
        - rowh: row height
        - brk: break behavior. Indicates the alignment of the following cell respect to this cell
        - start: If the cell includes a list, indicates the initial item
        - listItem: Character to display as a list item, when the lis is not numeric or alphabetic
        - itemwidth: width of the list item. If not specified is calculated automatically.
*/
        function MultiCell2($w,$h,$txt='',$border=0,$halign='J',$valign='T',$fill=0,$overflow=1,$rowh=0,$brk='L',$start=0,$listItem=NULL,$itemWidth=0)
        {       $x=$this->GetX(); $y=$this->GetY(); $width=$w;
                $this->Cell($w,$h,NULL,$border,0,NULL,$fill);
                if(!$rowh) $rowh=$this->GetFontHeight();
                if(is_array($txt)) $tx=$txt; else $tx=array($txt);

                // -------------- Get width of list items ---------------
                if($start||$listItem||$itemWidth)
                {       $NP=count($tx);
                        $s=NULL;
                        if($start) $s=$this->GetStringWidth($start+$NP);
                        $minItemWidth=$s+$this->GetStringWidth($listItem.' ')+2*$this->GetCellMargin();
                        $itemWidth=max($itemWidth,$minItemWidth);
                        $width=$w-$itemWidth+$this->GetCellMargin();
                }
                // --------------------------------------------------------

                $block=NULL; $i=0; $pNumLines=$bNumLines=NULL;
                foreach ($tx as $b)
                {       $paragraph=explode("\n",$b);
                        $j=0;
                        foreach($paragraph as $p)
                        {       $block[$i][$j]=$this->SplitInRows($p,$width);
                                $pNumLines[$i][$j]=count($block[$i][$j]);
                                $j++;
                        }
                        $bNumLines[$i]=array_sum($pNumLines[$i]);
                        $i++;
                }
                if(is_array($bNumLines)) $nLines=array_sum($bNumLines); else $nLines=0;
				$brk=strtolower($brk);
                switch($brk)
                {       case 'p':       $line=$pNumLines; break;
                        case 'b':       $line=$bNumLines; break;
                        default:        $line=array_pad(array(1),$nLines,1);
                }

                $realHeight=$h-2*$this->GetCellMargin();

                // --------- Separate fitting text from not fitting text ---------
                if(!$overflow)
                {       $maxFittingLines=floor($realHeight/$rowh);
                        $fittingLines=0;
                        foreach($line as $ln)
                        {       if($fittingLines+$ln>$maxFittingLines) break;
                                $fittingLines+=$ln;
                        }
                        $n=0; $notFittingText=$fittingText=NULL;
                        if(!is_array($block)) $block=array();
                        foreach($block as $i=>$b)
                        {       foreach($b as $j=>$p)
                                {       foreach ($p as $l)
                                        {       $n++;
                                                if($n>$fittingLines) $notFittingText[$i][$j][]=$l;
                                                else $fittingText[$i][$j][]=$l;
                                        }
                                        if(is_array($fittingText[$i][$j])) $fittingText[$i][$j]=implode(' ',$fittingText[$i][$j]);
                                        if(is_array($notFittingText[$i][$j])) $notFittingText[$i][$j]=implode(' ',$notFittingText[$i][$j]);
                                }
                                if(is_array($fittingText[$i])) $fittingText[$i]=implode("\n",$fittingText[$i]);
                                if(is_array($notFittingText[$i])) $notFittingText[$i]=implode("\n",$notFittingText[$i]);
                        }
                }
                else
                {       $fittingText=$tx; $fittingLines=$nLines;
                }
                // --------------------------------------------------------

                $textHeight=$rowh*$fittingLines;

                // ---------- Set position for vertical alignment ---------
                switch($valign)
                {       case 'M':       $vpos=($h-$textHeight)/2; break;
                        case 'B':       $vpos=$h-$textHeight-$this->GetCellMargin(); break;
                        case 'J':       $rowh=$h/count($line);
                        default:        $vpos=$this->GetCellMargin();
                }
                $y=$this->GetY();
                $this->SetXY($x,$y+$vpos);
                // --------------------------------------------------------
                
                // ------------------ Printing ----------------------------
                $i=$start;
                if(!is_array($fittingText)) $fittingText=array();
                foreach($fittingText as $p)
                {       if($itemWidth)
                        {       if($start) $item=$i++.$listItem;
                                else $item=$listItem;
                                $this->Cell($itemWidth,$rowh,$item,0,0,'R');
                                $this->SetX($this->GetX()-$this->GetCellMargin());
                        }
                        $this->MultiCell($width,$rowh,$p,0,$halign);
                        $this->SetX($x);
                }
                // --------------------------------------------------------

                // ----------------- Set position -------------------------
                $this->SetXY($x+$w,$y);
                $this->lasth=$h;
                // --------------------------------------------------------

                // ---------------- Return not fitting text ---------------
               // if(!is_array($txt)&&is_array(isset($notFittingText))) $notFittingText=implode(NULL,$notFittingText);
               //return $notFittingText;
                // --------------------------------------------------------
        }
        // ---------------------------------------------------------------------------------------------

        function MinCellHeight($txt='',$w)
        { return $this->GetFontHeight()*count($this->SplitInRows($txt,$w))+2*$this->GetCellMargin();
        }
}


