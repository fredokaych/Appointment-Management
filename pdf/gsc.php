<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');

use setasign\Fpdi\Fpdi;

require_once('fpdi/src/autoload.php');
require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');

$pdf = new FPDI();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTextColor(54, 52, 57); //WMI Jagger colour
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');

//$pdf->SetStyle("wmigreen","overpassb","N",10,"74,125,58");

$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from gswl");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);



while ($row = mysqli_fetch_array($result)) {

    $pdf->AddPage();
    $filename = 'gsc.pdf';
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);


    $start = 151.085;


    $pdf->SetFont('palanquinb', '', 35);
    $pdf->SetTextColor(74, 125, 58);
    $text = $row['fname'] . ' ' . $row['lname'];

    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $text, 0, 0, 'C');
}

$pdf->Output('D', '2023_Graduate Scholar Certificate.pdf');
