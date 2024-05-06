<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');

use setasign\Fpdi\Fpdi;

require_once('fpdi/src/autoload.php');
require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');

$pdf = new FPDI('P', 'mm', 'Letter');
//$pdf->SetMargins(10, 10, 10);
//$pdf->SetTextColor(54,52,57);//WMI Jagger colour
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');

$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbcworkshopnametags order by id asc");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);

//$numRows = mysqli_fetch_array($result);
$numRows = mysqli_num_rows($result);
$pages = ceil($numRows / 8);

for ($i = 0; $i < $numRows; $i += 8) {
    $pdf->AddPage();
    $filename = 'workshopnametags.pdf';
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);

    mysqli_data_seek($result, $i);
    $start = 44.298;
    $start2 = 50.852;
    $start3 = 65.014;
    $inc = 63.5;
    $pdf->SetMargins(203.2, 10, 114.3);
    for ($j = 0; $j < 4; $j++) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            break;
        }

        $pdf->SetFont('palanquinb', '', 20);
        $pdf->SetXY(12.7, $start);
        $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');

        $pdf->SetFont('palanquin', '', 14);
        $pdf->SetXY(20, $start2-1.7);
        $pdf->MultiCell(74.3, 4.566, $row['position'], 0, 'C');

        $pdf->SetFont('palanquinb', '', 15);
        $pdf->SetXY(20, $start3);
        $pdf->Cell(0, 0, $row['nation'], 0, 0, 'R');

        $start = $start + $inc;
        $start2 = $start2 + $inc;
        $start3 = $start3 + $inc;
    }

    mysqli_data_seek($result, $i + $j);
    $start = 44.298;
    $start2 = 50.852;
    $start3 = 65.014;
    $inc = 63.5;
    $pdf->SetMargins(101.6, 10, 12.7);
    for ($j = 0; $j < 4; $j++) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            break;
        }

        $pdf->SetFont('palanquinb', '', 20);
        $pdf->SetXY(114.3, $start);
        $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');

        $pdf->SetFont('palanquin', '', 14);
        $pdf->SetXY(121.6, $start2-1.7);
        $pdf->MultiCell(74.3, 4.566, $row['position'], 0, 'C');
        //$pdf->SetXY(117, $start2);
        //$pdf->Cell(0, 0, $row['position'], 0, 0, 'C');

        $pdf->SetFont('palanquinb', '', 15);
        $pdf->SetXY(121.6, $start3);
        $pdf->Cell(0, 0, $row['nation'], 0, 0, 'R');

        $start = $start + $inc;
        $start2 = $start2 + $inc;
        $start3 = $start3 + $inc;
    }
}


$pdf->Output('', 'AMSAppointments.pdf');
