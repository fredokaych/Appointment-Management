<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');

use setasign\Fpdi\Fpdi;

require_once('fpdi/src/autoload.php');
require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');
//require('writetag/WriteTag.php');

$pdf = new FPDI('P','mm','Letter');
$pdf->SetMargins(10, 10, 10);
$pdf->SetTextColor(54,52,57);//WMI Jagger colour
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');

//$pdf->SetStyle("wmigreen","overpassb","N",10,"74,125,58");

$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from gswl");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);


if (!mysqli_num_rows($result)) {
    $nodata = 'NO DATA';
    $pdf->SetFont('Times', 'B', 30);
    $pdf->SetXY(100, 90);
    $pdf->Cell(0, 5, $nodata);
} else {
    while ($row = mysqli_fetch_array($result)) {

        $pdf->AddPage();
        $filename = 'gswl.pdf';
        $pdf->setSourceFile($filename);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);
        

        $start = 42;
        $inc = 4.466;

        $pdf->SetFont('overpassb', '', 10);
        $pdf -> SetTextColor(74,125,58);
        $name = $row['fname'].' '.$row['lname'];
        
        $pdf->SetXY(32.896, $start);
        $pdf->Cell(0, 0, $name);

        $pdf->SetFont('overpass', '', 10);
        $pdf -> SetTextColor(0,0,0);
        $text = 'The Wells Mountain Initiative is very proud to formally recognize your achievement for completing your courses at '.$row['school'].'. It was with confidence in your ability to rise above all obstacles to achieve this success that you were selected for the WMI Scholar\'s Program. We entrusted you to deepen your commitment to your educational studies and engage as a leader within your community by participating in and creating service opportunities. We are impressed with your achievements! ';



        $start = 47.31;
        $pdf->SetXY(24.508, $start);
        $pdf->MultiCell(165, 4.466, $text);


    }
}

$pdf->Output('i', '2023_Graduate Scholar Welcome Letter.pdf');

















