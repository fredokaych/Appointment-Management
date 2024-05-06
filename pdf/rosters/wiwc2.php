<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select fname from dbcsessions where fmdoctor2 = 'Yes'");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);


//$zip = new ZipArchive();
//$zipFileName = 'example.zip';
//$zip->open($zipFileName, ZipArchive::CREATE);

$cnt = 1;
$pdf = new FPDI('P', 'mm', 'A4');

$cnt = 1;
$inc = 10;
$pdf->SetMargins(200, 10, 10);
$pdf->SetTextColor(22, 55, 28); //WMI Jagger colour
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');


$pdf->AddPage();
$filename = 'wiwc2.pdf';
$pdf->setSourceFile($filename);
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$start = 73.765;
$ind = 26;
while ($row = mysqli_fetch_array($result)) {


    $pdf->SetFont('overpass', '', 12);
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $cnt . '.');
    $pdf->SetXY($ind + 15, $start);
    $pdf->Cell(0, 0, $row['fname']);
    $start += $inc;
    $cnt++;


    $docname = '2023_DBC_Women In White Coats #2 Roster.pdf';
    //$pdf->Output('F', 'rosters/'.$docname);
}
$pdf->Output('I', $docname);
