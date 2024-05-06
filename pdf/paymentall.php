<?php
session_start();
date_default_timezone_set( 'Africa/Nairobi' );
include( '../config.php' );

$sql = mysqli_stmt_init($link);
$sql = mysqli_prepare( $link, "select * from payhist order by id desc");
mysqli_stmt_execute($sql);	
$result = mysqli_stmt_get_result($sql);	

use setasign\Fpdi\Fpdi;
require_once( 'fpdi/src/autoload.php' );

require( 'fpdf/fpdf.php' );
require( 'fpdi/src/Fpdi.php' );


$pdf = new FPDI( 'L' );
$pdf->AddPage();
$filename = 'paymentall.pdf';

$pdf->setSourceFile( $filename );
$tplIdx = $pdf->importPage( 1 );
$pdf->useTemplate( $tplIdx );

$pdf->SetFont( 'Times', 'B', 12 );
$start = 31;
$inc = 10;
$ind = 200;

$start = $start + $inc;
$pdf->SetXY( $ind, $start );
$pdf->Cell( 0, 5, date( 'd-m-Y h:ia' ) );			

$cnt = 1;
$start = 67;
$inc = 7.22;
$ind = 15;

if ( !mysqli_num_rows( $result ) ) {
	$nodata = 'NO DATA YET';
	$pdf->SetFont( 'Times', 'B', 30 );
	$pdf->SetXY(100, 90);
	$pdf->Cell(0, 5, $nodata);
	

} else {
    while ( $row = mysqli_fetch_array( $result ) ) {

		
		
		$sql2 = mysqli_prepare( $link, "select patients.name as pname, doctors.name as dname, doctors.specilization as specialty from patients join doctors on patients.id = '".$row['userId']."' and doctors.id = '".$row['docId']."'");
		mysqli_stmt_execute($sql2);	
		$result2 = mysqli_stmt_get_result($sql2);
		$row2 = mysqli_fetch_array( $result2 );

		
		$refNo = $row['refNo'];
		$docname = $row2['dname'];
		$specialty = $row2['specialty'];
		$amount = $row['amount'];
		$paydate = $row['paydate'];
		$transactionNo = $row['transactionNo'];
		$payNo = $row['payNo'];
		$payerId = $row['userId'];
		
		
		
		
        $start = $start+$inc;
		$pdf->SetXY($ind, $start);
		$pdf->Cell(0, 5, $cnt);
		
		$pdf->SetXY(25, $start);
		$pdf->Cell(0, 5, $row2[ 'pname' ]);
		
		$pdf->SetXY(75, $start);
		$pdf->Cell(0, 5, $payNo);
		
		$pdf->SetXY(110, $start);
		$pdf->Cell(0, 5, $amount);
		
		$pdf->SetXY(130, $start);
		$pdf->Cell(0, 5, $docname);
		
		$pdf->SetXY(175, $start);
		$pdf->Cell(0, 5, date('d-m-Y h:ia', strtotime($paydate)));
		
		$pdf->SetXY(220, $start);
		$pdf->Cell(0, 5, $transactionNo);
		
		$pdf->SetXY(252, $start);
		$pdf->Cell(0, 5, $refNo);
		

		
        $cnt = $cnt + 1;


    }
}

$pdf->Output('I', 'AMSPayments.pdf');
