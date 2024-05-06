<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../../config.php');

use setasign\Fpdi\Fpdi;

require_once('../fpdi/src/autoload.php');
require('../fpdf/fpdf.php');
require('../fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbccertificates where type ='attendance'");
mysqli_stmt_execute($sql);
$result = mysqli_stmt_get_result($sql);

$docname = 'None';
$pdf = new FPDI('L', 'mm', 'A4');
while ($row = mysqli_fetch_array($result)) {


    //$pdf->SetMargins(10, 10, 10);
    //$pdf->SetTextColor(54, 52, 57); //WMI Jagger colour

    $pdf->AddFont('overpass', '', 'overpass.php');
    $pdf->AddFont('overpassb', '', 'overpassb.php');
    $pdf->AddFont('palanquin', '', 'palanquin.php');
    $pdf->AddFont('palanquinb', '', 'palanquinb.php');

    $pdf->AddPage();
    $extratext = false;
    $text = "";
    $pdf->SetTextColor(9, 164, 168); //WMI Persian Green colour
    if ($row['type'] == 'workshop') {
        $start = 108.903;
        $filename = 'workshoppresenter.pdf';
    } else if ($row['type'] == 'panelist') {
        $start = 108.903;
        $filename = 'panelist.pdf';
    } else if ($row['type'] == 'planning') {
        $start = 108.903;
        $filename = 'planning.pdf';
        $extratext = true;
        $text = $row['specification'];
    } else if ($row['type'] == 'speedpitch') {
        $start = 87;
        $filename = 'speedpitch.pdf';
    } else if ($row['type'] == 'attendance') {
        $start = 118.468;
        $filename = 'attendance.pdf';
    } 
    else if ($row['type'] == 'board') {
        $start = 108.304;
        $filename = 'board.pdf';
    } else if ($row['type'] == 'volunteer') {
        $start = 108.304;
        $filename = 'volunteer.pdf';
    }



    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);


    $pdf->SetFont('palanquinb', '', 36);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $row['fname'] . ' ' . $row['lname'], 0, 0, 'C');

    if ($extratext) {
        $pdf->SetTextColor(23, 55, 28); //WMI Hunter Green colour
        $pdf->SetFont('overpassb', '', 20);
        $pdf->SetXY(58, 123.502);
        $text = 'For outstanding work and contributions as a member of the ' . $text . ' Planning Committee';
        $pdf->MultiCell(181.299, 10.16, $text, 0, 'C');
    }


    $docname = $row['fname'] . ' ' . $row['lname'].'_Speed Pitch Certificate.pdf';
    //$pdf->Output('F', 'speedpitch/'.$docname);
}
$pdf->Output('I', $docname);
