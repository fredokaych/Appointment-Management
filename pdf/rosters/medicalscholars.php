<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select fname from dbcsessions where mscholar = 'Yes'");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);


//$zip = new ZipArchive();
//$zipFileName = 'example.zip';
//$zip->open($zipFileName, ZipArchive::CREATE);


$pdf = new FPDI('P', 'mm', 'A4');


$pdf->SetMargins(200, 10, 10);
$pdf->SetTextColor(22, 55, 28); //WMI Jagger colour
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');
$pdf->SetFont('overpass', '', 10);

$pdf->AddPage();
$filename = 'medicalscholars.pdf';
$pdf->setSourceFile($filename);
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$cnt = 1;
$inc = 5.644;
$start = 71;
$start2 = 71;
$start3 = 71;
$ind = 21.5;

while ($row = mysqli_fetch_array($result)) {

    if ($cnt > 37 && $cnt<=74) {
        $pdf->SetXY(77, $start2);
        $pdf->Cell(0, 0, $cnt . '.');
        $pdf->SetXY(77 + 7, $start2);
        $pdf->Cell(0, 0, $row['fname']);
        $start2 += $inc;
    } else if ($cnt > 74) {
        $pdf->SetXY(137, $start3);
        $pdf->Cell(0, 0, $cnt . '.');
        $pdf->SetXY(137 + 7, $start3);
        $pdf->Cell(0, 0, $row['fname']);
        $start3 += $inc;
    } else {
        $pdf->SetXY($ind, $start);
        $pdf->Cell(0, 0, $cnt . '.');
        $pdf->SetXY($ind + 7, $start);
        $pdf->Cell(0, 0, $row['fname']);
        $start += $inc;
    }


    $cnt++;


    $docname = '2023_DBC_Medical Scholars.pdf';
    //$pdf->Output('F', 'rosters/'.$docname);
}
$pdf->Output('I', $docname);
