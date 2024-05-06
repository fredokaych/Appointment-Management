<?php

session_start();
date_default_timezone_set( 'Africa/Nairobi' );
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
}
if ( isset( $_GET[ 'specialty' ] ) ) {
    $specialty = $_GET[ 'specialty' ];
} else {
    $specialty = "";
}
if ( isset( $_GET[ 'amount' ] ) ) {
    $amount = "Kshs. " . $_GET[ 'amount' ];
} else {
    $amount = "";
}
if ( isset( $_GET[ 'paydate' ] ) ) {
    $paydate = $_GET[ 'paydate' ];
} else {
    $paydate = "";
}
if ( isset( $_GET[ 'transactionNo' ] ) ) {
    $transactionNo = $_GET[ 'transactionNo' ];
} else {
    $transactionNo = "";
}
if ( isset( $_GET[ 'payNo' ] ) ) {
    $payNo = formatphone( $_GET[ 'payNo' ] );
} else {
    $payNo = "";
}


use setasign\Fpdi\Fpdi;
require_once( 'fpdi/src/autoload.php' );

require( 'fpdf/fpdf.php' );
require( 'fpdi/src/Fpdi.php' );


$pdf = new FPDI( 'L' );
$pdf->AddPage();
$filename = 'appointmentall.pdf';

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


$sql = mysqli_stmt_init( $link );
$sql = mysqli_prepare( $link, "select doctors.name as docname, patients.id as pid, patients.hacc as hacc, patients.name as pname, appointment.*  from appointment join doctors on doctors.id=appointment.doctorId join patients on patients.id=appointment.userId where appointment.completed = 0 order by appointment.id desc" );
mysqli_stmt_execute( $sql );
$result = mysqli_stmt_get_result( $sql );
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

        $start = $start+$inc;
		$pdf->SetXY($ind, $start);
		$pdf->Cell(0, 5, $cnt);
		$pdf->SetXY(25, $start);
		$pdf->Cell(0, 5, $row[ 'docname' ]);
		$pdf->SetXY(80, $start);
		$pdf->Cell(0, 5, $row[ 'pname' ]);
		$pdf->SetXY(135, $start);
		$pdf->Cell(0, 5, $row[ 'consultancyFees' ]);
		$pdf->SetXY(155, $start);
		$pdf->Cell(0, 5, date( 'd-m-Y', strtotime( $row[ 'appointmentDate' ] ) )." / ".$row['appointmentTime']);
		$pdf->SetXY(200, $start);
		$pdf->Cell(0, 5, date( 'd-m-Y h:ia', strtotime( $row[ 'postingDate' ] ) ));

        if ( $row[ 'payStatus' ] == 0 ) {
            $mystr = "Not Paid";
            $color = "red";
        } else {
            if ( ( $row[ 'userStatus' ] == 1 ) && ( $row[ 'doctorStatus' ] == 1 ) ) {
                if ( $row[ 'approvStatus' ] == 1 ) {
                    $mystr = "Active";
                    $color = "green";
                } else {
                    $mystr = "Pending";
                    $color = "orange";
                }
            };
            if ( ( $row[ 'userStatus' ] == 0 ) && ( $row[ 'doctorStatus' ] == 1 ) ) {
                $mystr = "Cancelled";
                $color = "red";
            };
            if ( ( $row[ 'userStatus' ] == 1 ) && ( $row[ 'doctorStatus' ] == 0 ) ) {
                $mystr = "Cancelled";
                $color = "red";
            };
        }

		$pdf->SetXY(250, $start);
		$pdf->Cell(0, 5, $mystr);
		
        $cnt = $cnt + 1;
    }
}

$pdf->Output('', 'AMSAppointments.pdf');

?>
