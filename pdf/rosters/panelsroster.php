<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select DISTINCT topic from dbcpaneltopics");
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

    for ($session = 1; $session <= 2; $session++) {
        $sql2 = mysqli_stmt_init($link);
        $sql2 = mysqli_prepare($link, "select fname from dbcsessions where pn" . $session . " = '" . $row['topic'] . "'");
        mysqli_stmt_execute($sql2);
        $result2 = mysqli_stmt_get_result($sql2);

        if (!mysqli_num_rows($result2)) {
        } else {
            $pdf->AddPage();
            $filename = 'panelsroster.pdf';
            $pdf->setSourceFile($filename);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx);

            $start = 49;
            $inc = 5;
            $ind = 37;

            $pdf->SetFont('palanquinb', '', 14);
            $pdf->SetXY(30, $start);
            $pdf->Cell(0, 0, 'Panel ' . $session . ': ' . $row['topic']);
            $pdf->SetFont('palanquinb', '', 10);
            $start = 56;
            $start2 = 56;
            $pdf->SetXY(30, $start);
            $pdf->Cell(0, 0, 'Panelists:');

            $pdf->SetFont('overpass', '', 10);


            $sql3 = mysqli_stmt_init($link);
            $sql3 = mysqli_prepare($link, "select presenter".$session." from dbcpaneltopics where topic = '" . $row['topic'] . "'");
            mysqli_stmt_execute($sql3);
            $result3 = mysqli_stmt_get_result($sql3);
            if (!mysqli_num_rows($result3)) {
            } else {
                while ($row3 = mysqli_fetch_array($result3)) {
                    $pdf->SetXY(55, $start);
                    $pdf->Cell(0, 0, $row3['presenter'.$session]);
                    $start += $inc;
                }
            }



            $start = 96.5;
            $start2 = 96.5;


            $cnt = 1;
            $inc = 7.408;
            $ind = 32.741;

            $pdf->SetFont('overpass', '', 11);

            while ($row2 = mysqli_fetch_array($result2)) {
                if ($cnt > 24) {
                    $pdf->SetXY(112, $start2);
                    $pdf->Cell(0, 0, $cnt . '.');
                    $pdf->SetXY(112 + 13, $start2);
                    $pdf->Cell(0, 0, $row2['fname']);
                    $start2 += $inc;
                } else {


                    $pdf->SetXY($ind, $start);
                    $pdf->Cell(0, 0, $cnt . '.');
                    $pdf->SetXY($ind + 13, $start);
                    $pdf->Cell(0, 0, $row2['fname']);
                    $start += $inc;
                }
                $cnt++;
            }

            $docname = '2023_DBC_Panel: ' . $row['topic'];
            //$pdf->Output('F', 'rosters/'.$docname);
        }
    }
}
$pdf->Output('I', $docname);
