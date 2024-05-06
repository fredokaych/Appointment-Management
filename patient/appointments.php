<?php
// Initialize the session
session_start();

include( '../config.php' );

// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


require( '../other-functions.php' );
$myconfig = new myConfigs();

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

$ret = mysqli_query( $link, "select PatientContno from id17137158_pmsproject.patients where id = " . $_SESSION[ 'id' ] );
while ( $row = mysqli_fetch_array( $ret ) ) {
    $phone = $row[ 'PatientContno' ];
}

//remove 30 minute old unpaid appointments
if($ret = mysqli_query( $link, "select id from id17137158_pmsproject.appointment where userId = " . $_SESSION[ 'id' ]." and payStatus = 0" )){
	
	$numrows = mysqli_num_rows( $ret );
    $tbd = array();
	while ( $row = mysqli_fetch_array( $ret ) ) {
		$now = time();
		$then = time();
		if(isset($_SESSION['starttime'.$row['id']])){
			$then = $_SESSION['starttime'.$row['id']];
		}else{
			$_SESSION['starttime'.$row['id']] = time();
		}
		$timeSince = $now - $_SESSION['starttime'.$row['id']];
		if($timeSince>1800){
			$tbd[] = $row['id'];
		}
	}
	for ( $i = 0; $i < count($tbd); $i++ ) {
		$myconfig->removeschedule( $tbd[$i] );
		$sqlstr2 = "DELETE from id17137158_pmsproject.appointment where id = '" . $tbd[ $i ] . "'";
		$sql = mysqli_query( $link, $sqlstr2 );
	}
	header("Refresh:1801");
}









if ( isset( $_GET[ 'can' ] ) ) {
    $myconfig->removeschedule( $_GET[ 'id' ] );
    mysqli_query( $link, "update id17137158_pmsproject.appointment set completed = 3, approvStatus = 1, userStatus='0' where id = '" . $_GET[ 'id' ] . "'" );
	
	$sql = mysqli_query($link, "select hacc from patients where id = ".$_SESSION['id']);
	$hacc = mysqli_fetch_array($sql)['hacc'];
	$fees = $_GET['fees'];
	$newhacc = $hacc+($fees*90/100);
	mysqli_query($link, "update id17137158_pmsproject.patients set hacc = '$newhacc' where id = '".$_SESSION['id']."'" );
	
	
	
    $_SESSION[ 'msg' ] = "Appointment cancelled, 90% of the fee refunded";
}
if ( isset( $_GET[ 'del' ] ) ) {
    $myconfig->removeschedule( $_GET[ 'id' ] );
    mysqli_query( $link, "delete from id17137158_pmsproject.appointment where id = '" . $_GET[ 'id' ] . "'" );
    $_SESSION[ 'msg' ] = "Appointment Deleted";

}


?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Appointments</title>
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
        <h3 id "welcome-header">My Appointments</h3>
		<?php 
		$sql = mysqli_query($link, "select id from appointment where paystatus = 0 and userId = '".$_SESSION['id']."'");
		if ( !mysqli_num_rows( $sql ) ) {
    		$hasunpaid = '';
  		} else {
			$hasunpaid = '(Unpaid appointments will be automatically deleted after 30 minutes or immediately on logout)';
		}
	
	
	
		
		?>
        <p>View or Cancel Appointments <i style="color: orange"><?php echo $hasunpaid;?></i></p>
		
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
            <h4>Current Appointments</h4>
        </div>
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Ref.</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Fee</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select doctors.name as docname, appointment.*  from appointment join doctors on doctors.id = appointment.doctorId where appointment.userId='".$_SESSION['id']."' and appointment.completed = 0 order by id desc");
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
                if ( !mysqli_num_rows( $result ) ) {
                    ?>
                <tr>
                    <td colspan="9"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
                </tr>
                <tr>
                    <td colspan="9"><div class="text-center">
                            <h5>No New Appointments</h5>
                        </div></td>
                </tr>
                <?php
                } else {
					while ( $row = mysqli_fetch_array( $result ) ) {

						$status = $pstatus = $color = "";
						if($row['payStatus']==1){
							$pstatus = "Paid";
							if(($row['userStatus']==1) && ($row['doctorStatus']==1) && ($row['approvStatus']==1)){
								$status = "Active";
								$color = "green";
							}
							if(($row['userStatus']==1) && ($row['doctorStatus']==1) && ($row['approvStatus']==0)){
								$status = "Pending";
								$color = "Orange";
							}
							if(($row['userStatus']==0) && ($row['doctorStatus']==1)){
								$status =  "You Cancelled";
								$color = "Red";
								$pstatus = "90% Refund";
							}
							if(($row['userStatus']==1) && ($row['doctorStatus']==0)){
								$status =  "Cancelled";
								$color = "Red";
								$pstatus = "Refunded";
							}
						}else{
							$status =  "Not Paid";
							$pstatus = "Pay Now";
							$color = "Red";
						};
						?>
					<tr>
						<td><?php echo $cnt;?>.</td>
						<td><?php echo "MIGORI_".$row['id'];?></td>
						<td><?php echo $row['docname'];?></td>
						<td><?php echo $row['doctorSpecialization'];?></td>
						<td><?php echo $row['consultancyFees'];?></td>
						<td><?php echo date('d-m-Y', strtotime($row['appointmentDate']));?> / <?php echo $row['appointmentTime'];?></td>
						<td style="color:<?php echo $color;?>;"><?php echo $status ?></td>
						<td style="color:<?php echo $color;?>;"><?php echo $pstatus; ?></td>
						<td><?php
						if($row['payStatus']==0){

						?>
							<a href="payment.php?last_id=<?php echo $row['id'];?>&phn=<?php echo $phone;?>&amt=<?php echo $row['consultancyFees'];?> " class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Pay Now"><i class="bx bx-money bx-sm bx-tada-hover"></i></a>
							
							<a href="appointments.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to cancel and delete?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-sm bx-tada-hover"></i></a>
							<?php
						}else{
						?>
							<?php /*?><a href="appointments.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Reschedule"><i class="bx bx-time bx-sm bx-tada-hover"></i></a><?php */?>
							<a href="appointments.php?id=<?php echo $row['id']?>&fees=<?php echo $row['consultancyFees']?>&can=update" onClick="return confirm('Are you sure you want to cancel?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Cancel"><i class="bx bx-comment-x bx-sm bx-tada-hover"></i></a>
							<?php	
						};

						?></td>
					</tr>
					<?php
					$cnt = $cnt + 1;
					}
				}
				?>
            </tbody>
        </table>
    </div>
    <h1></h1>
    <div class="table-responsive">
        <div class="text-center">
            <h4>Previous & Complete Appointments</h4>
        </div>
        <table class="table table-bordered  table-hover" id="sample-table-2">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Doctor Name</th>
                    <th>Specialization</th>
                    <th>Fee</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select doctors.name as docname, appointment.*  from appointment join doctors on doctors.id = appointment.doctorId where appointment.userId='".$_SESSION['id']."' and appointment.completed != 0");
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
                if ( !mysqli_num_rows( $result ) ) {
                    ?>
                <tr>
                    <td colspan="7"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
                </tr>
                <tr>
                    <td colspan="7"><div class="text-center">
                            <h5>No Data Yet</h5>
                        </div></td>
                </tr>
                <?php
                } else {
					while ( $row = mysqli_fetch_array( $result ) ) {
						$status = $pstatus = $color = "";

						$pstatus = "Incomplete";

						if($row['completed']==3){
							$status =  "You Cancelled";
							$color = "Red";
							$pstatus = "90% Refund";
						}elseif($row['completed']==2){
							$status =  "Doctor Cancelled";
							$color = "Crimson";
							$pstatus = "Refunded";

						}elseif($row['completed']==5){
							$status =  "Admin Cancelled";
							$color = "Crimson";
							$pstatus = "Refunded";
						}elseif($row['completed']==4){
							$status =  "Missed";
							$color = "Aqua";
							$pstatus = "Null";
						}else{
							$status =  "Completed";
							$color = "green";
							$pstatus = "Done";
						}

						?>
					<tr>
						<td><?php echo $cnt;?>.</td>
						<td><?php echo $row['docname'];?></td>
						<td><?php echo $row['doctorSpecialization'];?></td>
						<td><?php echo $row['consultancyFees'];?></td>
						<td><?php echo $row['appointmentDate'];?>/<?php echo $row['appointmentTime'];?></td>
						<td style="color:<?php echo $color;?>;"><?php echo $status ?></td>
						<td style="color:<?php echo $color;?>;"><?php echo $pstatus; ?></td>
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