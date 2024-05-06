<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbcfoldedpaper");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);




$cnt = 1;
$pdf = new FPDI('L', 'mm', 'A4');
while ($row = mysqli_fetch_array($result)) {
    

    //$pdf->SetTextColor(54, 52, 57); //WMI Jagger colour

    $pdf->AddFont('overpass', '', 'overpass.php');
    $pdf->AddFont('overpassb', '', 'overpassb.php');
    $pdf->AddFont('palanquin', '', 'palanquin.php');
    $pdf->AddFont('palanquinb', '', 'palanquinb.php');

    $pdf->AddPage();
    $pdf->SetTextColor(23, 55, 28); //WMI Hunter Green colour

    $start = 134.845;
    


    $filename = 'foldedpaper3.pdf';
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage($cnt);
    $pdf->useTemplate($tplIdx);


    $pdf->SetFont('overpassb', '', 55);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');
  

    $docname = 'foldedpaper' . $cnt . '.pdf';

    //$pdf->Output('F', $docname);
    $cnt = $cnt + 1;
}
$pdf->Output('I', $docname);

