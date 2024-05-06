<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');

use setasign\Fpdi\Fpdi;

require_once('fpdi/src/autoload.php');
require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');



$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare($link, "select * from dbcsessions");
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


    if ($row['bmlunch'] != null) {
        $filename = 'dbcschedule.pdf';
        $mystart = 127.87;
        $mysectioninc = 13.135;
        $mystart2 = 147.045;
        $mystart3 = 218.416;
    } else {
        $filename = 'dbcschedulefew.pdf';
        $mystart = 129.848;
        $mysectioninc = 14.942;
        $mystart2 = 150.78;
        $mystart3 = 216.583;
    }

    $pdf->AddPage();
    $pdf->setSourceFile($filename);
    $tplIdx = $pdf->importPage(1);
    $pdf->useTemplate($tplIdx);


    $start = 52;
    $inc = 6;
    $ind = 37;

    $pdf->SetFont('overpassb', '', 16);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $row['fname'], 0, 0, 'C');


    $pdf->SetFont('overpass', '', 11);

    $start = 78.989;
    for ($i = 1; $i <= 6; $i++) {
        $wkindent = 61.167;

        if ($row['wkpresenter'] != 'No') {
            if ($row['wkpresenter'] == $row['wk' . $i]) {
                $pdf->SetFont('overpassb', '', 11);
                $pdf->SetXY($wkindent, $start);
                $wkindent = 82.632;
                $pdf->Cell(0, 0, '[Presenter]');
            }
        }
        $pdf->SetFont('overpass', '', 11);
        $text =  $row['wk' . $i];
        $pdf->SetXY($wkindent, $start);
        $pdf->Cell(0, 0, $text);
        $start += $inc;
    }


    $start = $mystart;
    for ($i = 1; $i <= 2; $i++) {
        $wkindent = 57;
        if ($row['panelist' . $i] != 'No') {
            if ($row['panelist' . $i] == $row['pn' . $i]) {
                $pdf->SetFont('overpassb', '', 11);
                $pdf->SetXY($wkindent, $start);
                $wkindent = 78.562;
                $pdf->Cell(0, 0, '[Presenter]');
            }
        }
        $pdf->SetFont('overpass', '', 11);
        $text =  $row['pn' . $i];
        $pdf->SetXY($wkindent, $start);
        $pdf->Cell(0, 0, $text);
        $start += $inc;
    }



    $ind = 56;
    $pdf->SetFont('palanquinb', '', 12);
    $sectioninc = $mysectioninc;

    $start = $mystart2;

    $ind = 42;

    $day = 'Friday';
    $time = '3:00 pm';
    if ($row['fellowship'] == 'Yes') {
        $title = 'FELLOWSHIP LEADERS MEETING';
    } else {
        $title = 'TEAM BUILDING';
    }
    $pdf->SetFont('palanquin', '', 10);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $day);
    $pdf->SetXY(25, $start);
    $pdf->Cell(0, 0, $time);
    $pdf->SetFont('palanquinb', '', 12);
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $title);



    if ($row['bmlunch'] != null) {
        $start = $start + $sectioninc;
        $time = '12:15 pm';
        $title = 'LUNCH WITH BOARD MEMBER (' . $row['bmlunch'] . ')';
        $day = $row['bmday'];
        $pdf->SetFont('palanquin', '', 10);
        $pdf->SetXY(10, $start);
        $pdf->Cell(0, 0, $day);
        $pdf->SetXY(25, $start);
        $pdf->Cell(0, 0, $time);
        $pdf->SetFont('palanquinb', '', 12);
        $pdf->SetXY($ind, $start);
        $pdf->Cell(0, 0, $title);
    }





    $start = $start + $sectioninc;
    $time = '3:15 pm';
    $day = 'Saturday';
    if ($row['mscholar'] == 'Yes') {
        $title = 'MEDICAL SCHOLAR MEET & GREET';
    } else {
        $title = 'TEAM BUILDING';
    }
    $pdf->SetFont('palanquin', '', 10);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $day);
    $pdf->SetXY(25, $start);
    $pdf->Cell(0, 0, $time);
    $pdf->SetFont('palanquinb', '', 12);
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $title);



    $start = $start + $sectioninc;
    $day = 'Saturday';
    if ($row['speedpitch'] == 'Yes') {
        $title = 'SPEED PITCH COMPETITION';
        $time = '4:30 pm';
    } else if ($row['fmdoctor1'] == 'Yes') {
        $title = 'WOMEN IN WHITE COATS #1';
        $time = '4:45 pm';
    } else {
        if ($row['wmileadershippanelist'] == 'Yes') {
            $title = '[Presenter] WMI LEADERSHIP 101';
        } else {
            $title = 'WMI LEADERSHIP 101';
        }
        $time = '4:45 pm';
    }
    $pdf->SetFont('palanquin', '', 10);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $day);
    $pdf->SetXY(25, $start);
    $pdf->Cell(0, 0, $time);
    $pdf->SetFont('palanquinb', '', 12);
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $title);


    $start = $start + $sectioninc;
    $time = '7:00 pm';
    $day = 'Monday';
    if ($row['fmdoctor2'] == 'Yes') {
        $title = 'WOMEN IN WHITE COATS #2';
    } else {
        $title = 'DINNER-FREE SEATING';
    }
    $pdf->SetFont('palanquin', '', 10);
    $pdf->SetXY(10, $start);
    $pdf->Cell(0, 0, $day);
    $pdf->SetXY(25, $start);
    $pdf->Cell(0, 0, $time);
    $pdf->SetFont('palanquinb', '', 12);
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $title);

    $pdf->SetFont('overpass', '', 11);

    $start = $mystart3;
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $row['cssite']);


    $start = 237.5;
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $row['day2act']);
    $start = $start + $inc;
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $row['day3act']);
    $start = $start + $inc;
    $pdf->SetXY($ind, $start);
    $pdf->Cell(0, 0, $row['day4act']);

    $docname = '2023_DBC_' . $row['fname'] . '_Personal Conference Schedule.pdf';

    //$pdf->Output('F', 'schedules/' . $docname);
    //$zip->addFile($docname, $docname);
    $cnt = $cnt + 1;
}
$pdf->Output('I', $docname);


//$zip->close();

//header("Content-type: application/zip");
//header("Content-Disposition: attachment; filename=$zipFileName");
//header("Content-length: " . filesize($zipFileName));
//readfile($zipFileName);
//unlink($zipFileName);
