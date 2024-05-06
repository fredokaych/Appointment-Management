<?php
// Initialize the session
session_start();
date_default_timezone_set('Africa/Nairobi');
include( '../config.php' );

// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if ( isset( $_GET[ 'error' ] ) ) {
    $_SESSION[ 'msg' ] = $_GET[ 'error' ];
} else {
    $_SESSION[ 'msg' ] = "";
}
if ( isset( $_GET[ 'success' ] ) ) {
    $_SESSION[ 'msgscs' ] = $_GET[ 'success' ];
} else {
    $_SESSION[ 'msgscs' ] = "";
}


?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Payments</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <div class="card-body">
        <h3 id "welcome-header">My Payments</h3>
        <p>View Payments for Appointments</p>
    	<p style="color:red;"><?php echo $_SESSION['msg'];?>
			<?php $_SESSION['msg']="";?>
		</p>
		<p style="color:green;"><?php echo $_SESSION['msgscs'];?>
			<?php $_SESSION['msgscs']="";?>
		</p>
	</div>

    <div class="table-responsive">
        <div class="text-right"><a class="btn btn-primary btn-success" href="book-appointment.php" role="button">Book New Appointment</a></div>
        <div class="text-center">
            <h4>Current Payments</h4>
        </div>
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Reference No.</th>
                    <th>Doctor Name</th>
                    <th>Fee</th>
                    <th>Payment Date</th>
                    <th>Transaction No.</th>
                    <th>Payment No.</th>
					<th>Receipt</th>
					<th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select doctors.name as docname, doctors.specilization as specialty, payhist.*  from payhist join doctors on doctors.id = payhist.docId where payhist.userId='".$_SESSION['id']."' and payhist.transactionNo != 'New' order by id desc");
				
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				if ( !mysqli_num_rows( $result ) ) {
					?>
					<tr>
						<td colspan="9"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
					</tr>
					<tr>
						<td colspan="9"><div class="text-center"><h5>No Payments Yet</h5></div></td>
					</tr>
					<?php
				} else {
					while ( $row = mysqli_fetch_array( $result ) ) {
						$refNo = $row['refNo'];
						$docname = $row['docname'];
						$pstatus = $row['status'];
						$amount = $row['amount'];
						$paydate = $row['paydate'];
						$specialty = $row['specialty'];
						$transactionNo = $row['transactionNo'];
						$payNo = $row['payNo'];
						
						if($pstatus==0){
							$pcolor = "orange";
							$pdisplay = "Pending";
						}elseif($pstatus==1){
							$pcolor = "green";
							$pdisplay = "Complete";
						}else{
							$pcolor = "red";
							$pdisplay = "Incomplete";
						}
					?>
					<tr>
						<td><?php echo $cnt;?>.</td>
						<td><?php echo $refNo;?></td>
						<td><?php echo $docname;?></td>
						<td><?php echo $amount;?></td>
						<td><?php echo date('d-m-Y h:ia', strtotime($paydate));?></td>
						<td><?php echo $transactionNo;?></td>
						<td><?php echo $payNo;?></td>
						<td>
						
						<a href="../pdf/pdf.php?refNo=<?php echo $refNo;?>&docname=<?php echo $docname;?>&specialty=<?php echo $specialty;?>&amount=<?php echo $amount;?>&paydate=<?php echo $paydate;?>&transactionNo=<?php echo $transactionNo;?>&payNo=<?php echo $payNo;?>&payerId=<?php echo $_SESSION['id'];?>" target ="_blank" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Receipt"><i class="bx bx-receipt bx-sm  solid bx-tada-hover"></i></a>
						
						</td>
						<td style="color:<?php echo $pcolor;?>;"><?php echo $pdisplay;?></td>
					</tr>
					<?php
					$cnt = $cnt + 1;
					}
				}
				?>
            </tbody>
        </table>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>