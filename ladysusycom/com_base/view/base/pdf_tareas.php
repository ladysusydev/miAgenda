<?php
define('_LS', 1);
define('DS', DIRECTORY_SEPARATOR);

// Extraer la ruta principal
define('LS_BASE', strstr(dirname(__FILE__), DS."ladysusycom", true));

require_once LS_BASE.'/includes/defines.php';
require_once LS_BASE.'/includes/entorno.php';
include_once(dirname(__FILE__).'/pdf_reportes_sql.php');

include_once(LS_BASE.'/lib/cms/pdf/fpdf.php');
include_once(LS_BASE.'/lib/cms/pdf/fpdi.php');
include_once(LS_BASE.'/lib/cms/pdf/fpdf2.php');

// Una instancia a la clase de Reportes
$PDFReportes = new PDFReportes();
$fecha = $_GET['fechaPDF'];
$id = $_GET['id'];
$datosTareas = $PDFReportes->reporteTareas($fecha, $id);
$datosUsuario = $PDFReportes->getUsuario($id);
$resultUser = $datosUsuario->fetch_object();

// Valones iniciales
$x = 22;
$y = 78;
$wFila = 0;
$cFila = 6.2;

// Instanciamos el FPDI
$pdf = new FPDI();

// Agregamos una pagina
$pdf->AddPage();

// Obtenemos el archivo PDF
$pdf->setSourceFile(LS_BASE.'/lib/cms/formatos/FT.pdf');

// Importamos la pagina 1
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, null, null, 0, 0, true);
$pdf->SetFont('Arial','',11);
$pdf->SetTextColor(0, 0, 0);

// Datos iniciales
$pdf->SetXY(58.5, 46.5);
$pdf->MultiCell(175,5,'Actividades realizadas');
$pdf->SetXY(58.5, 51.2);
$pdf->MultiCell(175,5,$resultUser->nombre);
$pdf->SetXY(58.5, 56.2);

// Fecha de las actividades
$fechaTemp = explode("-", $fecha);
$fecha = $fechaTemp[2]."-".$fechaTemp[1]."-".$fechaTemp[0];
$pdf->MultiCell(175,5,$fecha);
$pdf->SetXY($x, $y+$wFila);
$i = 1;

while ($datos = $datosTareas->fetch_object()) {
    $pdf->SetX($x);
    $pdf->MultiCell(175,5,$i.". ".$datos->nombre);
    $pdf->Ln(2);
    $i++;
}

// Generando PDF
$pdf->Output();
