<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select DISTINCT cssite from dbcsessions order by cssite asc");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);


//$zip = new ZipArchive();
//$zipFileName = 'example.zip';
//$zip->open($zipFileName, ZipArchive::CREATE);

$cnt = 1;
$pdf = new FPDI('P', 'mm', 'A4');


while ($row = mysqli_fetch_array($result)) {
    $pdf->SetMargins(200, 10, 10);
    $pdf->SetTextColor(22, 55, 28); //WMI Jagger colour
    $pdf->AddFont('overpass', '', 'overpass.php');
    $pdf->AddFont('overpassb', '', 'overpassb.php');
    $pdf->AddFont('palanquin', '', 'palanquin.php');
    $pdf->AddFont('palanquinb', '', 'palanquinb.php');


    $pdf->AddPage();
    $filename = 'csroster.pdf';
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);

    $start = 61;

    $pdf->SetFont('palanquinb', '', 14);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $row['cssite'], 0, 0, 'C');

    $sql2 = mysqli_stmt_init($link);
    $sql2 = mysqli_prepare($link, 'select fname from dbcsessions where cssite = "' . $row['cssite'] . '"');
    mysqli_stmt_execute($sql2);
    $result2 = mysqli_stmt_get_result($sql2);

    $start = 91.648;
    $inc = 8.819;
    $ind = 40;

    if (!mysqli_num_rows($result2)) {
    } else {
        $cnt = 1;
        while ($row2 = mysqli_fetch_array($result2)) {
            $pdf->SetFont('overpass', '', 12);
            $pdf->SetXY($ind, $start);
            $pdf->Cell(0, 0, $cnt.'.');
            $pdf->SetXY($ind+15, $start);
            $pdf->Cell(0, 0, $row2['fname']);
            $start += $inc;
            $cnt++;
        }
    }
    $docname = '2023_DBC_CS Site: ' . $row['cssite'];
    //$pdf->Output('F', 'rosters/'.$docname);
}
$pdf->Output('I', $docname);
