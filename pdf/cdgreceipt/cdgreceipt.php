<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from cdgreceipt");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);




$cnt = 1;
$pdf = new FPDI('P', 'mm', 'Letter');
while ($row = mysqli_fetch_array($result)) {

    $pdf->AddPage();
    
    $filename = 'cdgreceipt.pdf';
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);


    $pdf->SetFont('Times', 'B', 11);
    $pdf->SetMargins(20, 20, 28.5);

    $start = 34.7;
    $pdf->SetXY(167, $start);
    
    $pdf->Cell(0, 0, str_pad($row['no'], 3, '0',STR_PAD_LEFT));

    $start = 47;
    $pdf->SetXY(108, $start);
    $pdf->Cell(0, 0, $row['recipient']);

    $pdf->SetFont('Times', '', 11);
    $start = 69;
    $pdf->SetXY(147, $start);
    $pdf->Cell(0, 0, $row['amount'], 0, 0, 'R');

    $pdf->SetFont('Times', 'B', 11);
    $start = 93.5;
    $pdf->SetXY(147, $start);
    $pdf->Cell(0, 0, $row['amount'], 0, 0, 'R');

    $start = 67;
    $pdf->SetXY(31, $start);
    $pdf->MultiCell(90, 5, $row['description'], 0, 'L');

    $start = 106;
    $pdf->SetXY(37, $start);
    $pdf->Cell(0, 0, $row['date']);

    $start = 106;
    $pdf->SetXY(84, $start);
    $pdf->Cell(0, 0, $row['recipient']);


    $docname = 'cdgreceipt' . $cnt . '.pdf';

    //$pdf->Output('F', $docname);
    $cnt = $cnt + 1;
}
$pdf->Output('I', $docname);

