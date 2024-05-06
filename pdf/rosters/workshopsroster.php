<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbcworkshoptopics order by session ASC");
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

    for ($session = 1; $session <= 6; $session++) {
        $sql2 = mysqli_stmt_init($link);
        $sql2 = mysqli_prepare($link, "select fname, lname from dbcsessions where wk" . $session . " = '" . $row['topic'] . "'");
        mysqli_stmt_execute($sql2);
        $result2 = mysqli_stmt_get_result($sql2);

        if (!mysqli_num_rows($result2)) {

        } else {
            $pdf->AddPage();
            $filename = 'workshopsroster.pdf';
            $pdf->setSourceFile($filename);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);

            $start = 61;
            $inc = 6;
            $ind = 37;

            $pdf->SetFont('palanquinb', '', 14);
            $pdf->SetXY(10, $start);
            $pdf->Cell(0, 0, 'Session ' . $session, 0, 0, 'C');

            $start += $inc;
            $pdf->SetXY(10, $start);
            $pdf->Cell(0, 0, $row['topic'], 0, 0, 'C');

            $start += $inc;
            $pdf->SetXY(10, $start);
            $pdf->SetFont('palanquin', '', 14);
            $pdf->Cell(0, 0, 'Presenter: ' . $row['presenter'], 0, 0, 'C');

            $cnt = 1;
            $start = 90.445;
            $inc = 7.408;
            $ind = 32.741;
            $pdf->SetFont('overpass', '', 11);

            while ($row2 = mysqli_fetch_array($result2)) {
                $pdf->SetXY($ind, $start);
                $pdf->Cell(0, 0, $cnt . '.');
                $pdf->SetXY($ind + 13, $start);
                $pdf->Cell(0, 0, $row2['fname'] . ' ' . $row2['lname']);
                $start += $inc;
                $cnt++;
            }

            $docname = '2023_DBC_Workshop: ' . $row['topic'];
            //$pdf->Output('F', 'rosters/'.$docname);
        }
    }
}
$pdf->Output('I', $docname);
