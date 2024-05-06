<?php
// Initialize the session
session_start();

include( '../config.php' );

// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
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
        <p>View Payments for my ppointments</p>
    </div>

    <div class="table-responsive">
        
        <div class="text-center">
            <h4>Current Payments</h4>
        </div>
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Reference No.</th>
                    <th>Patient Name</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Transaction No.</th>
                    <th>Payment No.</th>
					<th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php
				$sql = mysqli_query( $link, "select name, specilization  from id17137158_pmsproject.doctors where id = '".$_SESSION['id']."'");
				$row = mysqli_fetch_array( $sql );
				$docname = $row['name'];
				$specialty = $row['specilization'];

				$sql = mysqli_stmt_init($link);
				
				$sql = mysqli_prepare( $link, "select patients.id as payerId, patients.name as pname, patients.email as email, payhist.*  from payhist join patients on patients.id = payhist.userId where payhist.docId='".$_SESSION['id']."' order by id desc");
				
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
						$amount = $row['amount'];
						$paydate = $row['paydate'];
						$transactionNo = $row['transactionNo'];
						$payNo = $row['payNo'];
						$payerId = $row['payerId'];
					?>
					<tr>
						<td><?php echo $cnt;?>.</td>
						<td><?php echo $refNo;?></td>
						<td><?php echo $row['pname'];?></td>
						<td><?php echo $row['email'];?></td>
						<td><?php echo $amount;?></td>
						<td><?php echo date('d-m-Y h:ia', strtotime($paydate));?></td>
						<td><?php echo $transactionNo;?></td>
						<td><?php echo $payNo;?></td>
						<td>
						
						<a href="../pdf/pdf.php?refNo=<?php echo $refNo;?>&docname=<?php echo $docname;?>&specialty=<?php echo $specialty;?>&amount=<?php echo $amount;?>&paydate=<?php echo $paydate;?>&transactionNo=<?php echo $transactionNo;?>&payNo=<?php echo $payNo;?>&payerId=<?php echo $payerId;?>" target ="_blank" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Receipt"><i class="bx bx-receipt bx-sm  solid bx-tada-hover"></i></a>
						
						</td>
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