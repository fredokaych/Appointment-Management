<?php
session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');

use setasign\Fpdi\Fpdi;

require_once('fpdi/src/autoload.php');
require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');

$pdf = new FPDI('P','mm','Letter');
$pdf->AddFont('overpass', '', 'overpass.php');
$pdf->AddFont('overpassb', '', 'overpassb.php');
$pdf->AddFont('palanquin', '', 'palanquin.php');
$pdf->AddFont('palanquinb', '', 'palanquinb.php');

$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbcscholarnametags");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);

if (!mysqli_num_rows($result)) {
    $nodata = 'NO DATA';
    $pdf->SetFont('Times', 'B', 30);
    $pdf->SetXY(100, 90);
    $pdf->Cell(0, 5, $nodata);
} else {
    $numRows = mysqli_num_rows($result);
    $pages = ceil($numRows / 8);

    for ($i = 0; $i < $numRows; $i += 8) {
        $pdf->AddPage();
        $filename = 'scholarnametags.pdf';
        $pdf->setSourceFile($filename);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx);

        mysqli_data_seek($result, $i);
        $start = 44.964;
        $start3 = $start + 9;
        $start2 = 64.795;
        $inc = 63.5;
        $pdf->SetMargins(203.2, 10, 114.3);
        for ($j = 0; $j < 4; $j++) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                break;
            }
            $fontsize = $row['font'];

            $pdf->SetFont('palanquinb', '', $fontsize);
            $pdf->SetXY(12.7, $start);
            $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');

            $pdf->SetFont('palanquin', '', 18);
            $pdf->SetXY(12.7, $start3);
            $pdf->Cell(0, 0, $row['lname'], 0, 0, 'C');
            $pdf->SetFont('palanquinb', '', 18);
            $pdf->SetXY(12.7, $start2);
            $pdf->Cell(0, 0, $row['nation'], 0, 0, 'R');

            $start = $start + $inc;
            $start2 = $start2 + $inc;
            $start3 = $start3 + $inc;
        }

        mysqli_data_seek($result, $i + $j);
        $start = 44.964;
        $start3 = $start + 9;
        $start2 = 64.795;
        $inc = 63.5;
        $pdf->SetMargins(101.6, 10, 12.7);
        for ($j = 0; $j < 4; $j++) {
            $row = mysqli_fetch_assoc($result);
            if (!$row) {
                break;
            }
            $fontsize = $row['font'];

            $pdf->SetFont('palanquinb', '', $fontsize);
            $pdf->SetXY(114.3, $start);
            $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');

            $pdf->SetFont('palanquin', '', 18);
            $pdf->SetXY(114.3, $start3);
            $pdf->Cell(0, 0, $row['lname'], 0, 0, 'C');
            $pdf->SetFont('palanquinb', '', 18);
            $pdf->SetXY(114.3, $start2);
            $pdf->Cell(0, 0, $row['nation'], 0, 0, 'R');

            $start = $start + $inc;
            $start2 = $start2 + $inc;
            $start3 = $start3 + $inc;
        }
    }
}
$pdf->Output('', 'AMSAppointments.pdf');
