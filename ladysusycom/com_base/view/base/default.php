<?php
/**
 * miAgenda
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019, LadySusy
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package		miAgenda
 * @author		LadySusy Dev
 * @copyright	Copyright (c) 2019, LadySusy (http://www.ladysusy.org/)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
 
$dia1 = array();
$dia2 = array();
$dia1 = $this->listaTareas['dia1'];
$dia2 = $this->listaTareas['dia2'];
$fechaPDF = $this->dia1['ano'].'-'.$this->dia1['mesInt'].'-'.$this->dia1['diaInt'];
$sesion = LSPrincipal::getSession();
$idUser = $sesion->get('id');
?>

<div class="row imgAgenda">
    <div class="col-md-6">
        <div class="row show-grid">
            <div class="col-md-12">
                <div>
                    <div style="float:left;width: 50%;"><h2><?php echo $this->dia1['diaStr'].' '.$this->dia1['diaInt']; ?></h2></div>
                    <div style="float:left;width: 50%; height: 0"><p class="text-right"><img src="imagenes/LogoAgenda.png"></div>    
                </div>
                <div style="clear:both"></div>
                <h4><?php echo $this->dia1['mesStr'].' '.$this->dia1['ano']; ?></h4>
                <form action="#" id="frmtareas1" method="post" name="frmtareas">
                    <div class="cajaForm">
                        <input  type="text" id="nombre1" class="cajatext" name="nombre1" placeholder="Ingrese una tarea"/>
                        <input type="hidden" id="fecha" name="fecha" value="<?php echo $this->dia1['diaInt'].'-'.$this->dia1['mesInt'].'-'.$this->dia1['ano']; ?>">
                        <img src="imagenes/cargando.gif" id="LoadingImage1" style="display:none" />
                    </div>
                </form>
                <div class="cajaAgen" id="result">
                    <ul id="pagina1">
                    <?php
                    if (!empty($dia1)) {
                        while ($lista1 = $dia1->fetch_object()) { 
                            if ($lista1->estado == 'T') {
                                $tachado = true;
                            } else {
                                $tachado = false;
                            } ?>
                        <li id="item_<?php echo $lista1->id;?>">
                            <div class="del_wrapper"><a href="#" class="del_button" id="del-<?php echo $lista1->id; ?>">
                                <img src="imagenes/del.png" border="0" /></a>
                            </div>
                        <?php echo $lista1->nombre; ?>
                        </li>
                        <?php 
                        } 
                    } ?>
                    </ul>
                </div>
            </div>
            </div>
            <div class="row text-right">
                <div class="col-md-6 offset-md-6">
                    <h6 class="text-primary"><?php echo $this->MensajePensamiento; ?></h6><small><span class="text-dark"><?php echo $this->athorPensamiento; ?></span></small>
                </div>
            </div>
    </div>
    <div class="col-md-6">
        <div class="row show-grid">
            <div class="col-md-12">
                <div>
                    <div style="float:left;width: 100%;n"><h2><?php echo $this->dia2['diaStr'].' '.$this->dia2['diaInt']; ?></h2></div>  
                </div>
                <div style="clear:both"></div>
                <h4><?php echo $this->dia2['mesStr'].' '.$this->dia2['ano']; ?></h4>
                <form action="#" id="frmtareas2" method="post" name="frmtareas">
                    <div class="cajaForm">
                        <input type="text" id="nombre2" class="cajatext" size="10" name="nombre2" placeholder="Ingrese una tarea"/>
                        <input type="hidden" id="fecha" name="fecha" value="<? echo $this->dia2['diaInt'].'-'.$this->dia2['mesInt'].'-'.$this->dia2['ano']; ?>">
                        <img src="imagenes/cargando.gif" id="LoadingImage2" style="display:none" />
                    </div>
                </form>
                <div class="cajaAgen" id="result">
                    <ul id="pagina2">
                    <?php
                    if (!empty($dia2)) {
                        while ($lista2 = $dia2->fetch_object()) { 
                            if ($lista2->estado == 'T') {
                                $tachado = true;
                            } else {
                                $tachado = false;
                            } ?>
                        <li id="item_<?php echo $lista2->id;?>">
                            <div class="del_wrapper"><a href="#" class="del_button" id="del-<?php echo $lista2->id; ?>">
                                <img src="imagenes/del.png" border="0" /></a>
                            </div>
                            <?php echo $lista2->nombre; ?>
                        </li>
                        <?php 
                        } 
                    } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>


