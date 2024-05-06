<?php

session_start();
date_default_timezone_set('Africa/Nairobi');
include( '../config.php' );





if ( isset( $_GET[ 'refNo' ] ) ) {
    $refNo = $_GET[ 'refNo' ];
} else {
    $refNo = "";
}
if ( isset( $_GET[ 'payerId' ] ) ) {
    $payerId = $_GET[ 'payerId' ];
} else {
    $payerId = "";
}
if ( isset( $_GET[ 'docname' ] ) ) {
    $docname = $_GET[ 'docname' ];
} else {
    $docname = "";
}if ( isset( $_GET[ 'specialty' ] ) ) {
    $specialty = $_GET[ 'specialty' ];
} else {
    $specialty = "";
}if ( isset( $_GET[ 'amount' ] ) ) {
    $amount = "Kshs. ".$_GET[ 'amount' ];
} else {
    $amount = "";
}if ( isset( $_GET[ 'paydate' ] ) ) {
    $paydate = $_GET[ 'paydate' ];
} else {
    $paydate = "";
}if ( isset( $_GET[ 'transactionNo' ] ) ) {
    $transactionNo = $_GET[ 'transactionNo' ];
} else {
    $transactionNo = "";
}if ( isset( $_GET[ 'payNo' ] ) ) {
    $payNo = formatphone($_GET[ 'payNo' ]);
} else {
    $payNo = "";
}


function formatphone($phone){
	$phone = ( substr( $phone, 0, 1 ) == "+" ) ? str_replace( "+", "", $phone ) : $phone;
    $phone = ( substr( $phone, 0, 1 ) == "0" ) ? preg_replace( "/^0/", "254", $phone ) : $phone;
    $phone = ( substr( $phone, 0, 1 ) == "7" ) ? "254{$phone}" : $phone;
	return "+".$phone;
}

$sql = mysqli_query( $link, "select name, email, PatientContno  from id17137158_pmsproject.patients where id = '".$payerId."'");
$row = mysqli_fetch_array( $sql );
$myname = $row['name'];
$myemail = $row['email'];
$mycontact = formatphone($row['PatientContno']);

$sql = mysqli_query( $link, "select docId  from id17137158_pmsproject.payhist where refNo = '".$refNo."'");
$row = mysqli_fetch_array( $sql );
$docId = $row['docId'];
$sql = mysqli_query( $link, "select name, email, contactno  from id17137158_pmsproject.doctors where id = '".$docId."'");
$row = mysqli_fetch_array( $sql );
$dname = $row['name'];
$demail = $row['email'];
$dcontact = formatphone($row['contactno']);

$sql = mysqli_query( $link, "select appointmentDate, appointmentTime  from id17137158_pmsproject.appointment where id = '".ltrim($refNo, 'MIGORI_AMS')."'");
$row = mysqli_fetch_array( $sql );
$adate = $row['appointmentDate'];
$atime = $row['appointmentTime'];



use setasign\Fpdi\Fpdi;
require_once('fpdi/src/autoload.php');

require('fpdf/fpdf.php');
require('fpdi/src/Fpdi.php');


$pdf = new FPDI();
$pdf->AddPage();

$filename = 'tmh.pdf';
$pdf->setSourceFile($filename); 
$tplIdx = $pdf->importPage(1); 
$pdf->useTemplate($tplIdx);

$pdf->SetFont('Times','B',12);
$start = 63.8;
$inc = 10;
$ind = 140;
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $refNo);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, date('d-m-Y h:ia'));

$pdf->SetFont('Times','',12);


$start = 102.5;
$inc = 7.1;
$ind = 110;
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $myname);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $myemail);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $mycontact);


$start = 147.2;
$inc = 7.1;
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $amount);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $refNo);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $transactionNo);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $payNo);
$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, date('d-m-Y h:ia', strtotime($paydate)));


$start = 206.2;
$inc = 7.1;

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $dname);

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $demail);

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $dcontact);

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $specialty);

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, date('d-m-Y', strtotime($adate)));

$start = $start+$inc;
$pdf->SetXY($ind, $start);
$pdf->Cell(0, 5, $atime);




$pdf->Output('i', 'Migori_Receipt_'.$refNo.'.pdf');






















?>