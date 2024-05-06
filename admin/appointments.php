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


require( '../other-functions.php' );
$myconfig = new myConfigs();

if ( isset( $_GET[ 'can' ] ) ) {
    $myconfig->removeschedule( $_GET[ 'id' ] );
    mysqli_query($link, "update id17137158_pmsproject.appointment set doctorStatus = 0, completed = 5, approvStatus = 1 where id = '" . $_GET[ 'id' ] . "'" );
	$hacc = $_GET['hacc'];
	$fees = $_GET['fees'];
	$newhacc = $hacc+$fees;
	mysqli_query($link, "update id17137158_pmsproject.patients set hacc = '$newhacc' where id = '".$_GET['pid']."'" );
    $_SESSION[ 'msg' ] = "Appointment Cancelled and Refunded";
}
if ( isset( $_GET[ 'appr' ] ) ) {
    mysqli_query( $link, "update id17137158_pmsproject.appointment set approvStatus = 1, doctorStatus = 1 where ID = '" . $_GET[ 'id' ] . "'" );
}
if ( isset( $_GET[ 'del' ] ) ) {
    $myconfig->removeschedule( $_GET[ 'id' ] );
    mysqli_query( $link, "delete from id17137158_pmsproject.appointment where ID = '" . $_GET[ 'id' ] . "'" );
    $_SESSION[ 'msg' ] = "Appointment Deleted";
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Appointments</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <h3 id "welcome-header">Manage | Appointments</h3>
    <p>List of Appointments</p>
    <?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>
    <?php /*?><div class="text-right"><a class="btn btn-primary btn-success" href="#" role="button">Create Appointment</a></div>><?php */?>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <?php /*?><th>Specialization</th><?php */?>
                    <th>Fee</th>
                    <th>Date / Time</th>
                    <th>Created On</th>
                    <th>Current Status</th>
                    <th >Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select doctors.name as docname, patients.id as pid, patients.hacc as hacc, patients.name as pname, appointment.*  from appointment join doctors on doctors.id=appointment.doctorId join patients on patients.id=appointment.userId where appointment.completed = 0 order by appointment.id desc" );
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				if( ! mysqli_num_rows($result) ) {
					?>
					<tr>
						<td colspan="8"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
					</tr>
					<tr>
						<td colspan="8"><div class="text-center"><h5>No Data Yet</h5></div></td>
					</tr>
					<?php
				}else{
					while ( $row = mysqli_fetch_array( $result ) ) {
						?>
					<tr>
						<td class="center"><?php echo $cnt;?>.</td>
						<td class="hidden-xs"><?php echo $row['docname'];?></td>
						<td><?php echo $row['pname'];?></td>
						<?php /*?><td><?php echo $row['doctorSpecialization'];?></td><?php */?>
						<td><?php echo $row['consultancyFees'];?></td>
						<td><?php echo date('d-m-Y', strtotime($row['appointmentDate']))?>/<?php echo $row['appointmentTime'];?></td>
						<td><?php echo date('d-m-Y h:ia', strtotime($row['postingDate']))?></td>
						<?php
							$mystr=$color="";
							if($row['payStatus']==0){
								$mystr= "Not Paid";
								$color="red";
							}else{
								if(($row['userStatus']==1) && ($row['doctorStatus']==1)){
									if($row['approvStatus']==1){
										$mystr= "Active";
										$color="green";
									}else{
										$mystr= "Pending";
										$color="orange";
									}

								};
								if(($row['userStatus']==0) && ($row['doctorStatus']==1)){
									$mystr= "Cancelled";
									$color="red";
								};
								if(($row['userStatus']==1) && ($row['doctorStatus']==0)){
									$mystr= "Cancelled";
									$color="red";
								};
							}
						?>
						<td style="color:<?php echo $color;?>;"><?php echo $mystr;?></td>
						<td><div>
								<?php
								if($row['payStatus']==0){
									//echo "Awaiting Payment";?>
								<a href="appointments.php?id=<?php echo $row['id']?>&del=del" onClick="return confirm('Are you sure you want to Delete?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-sm bx-tada-hover"></i></a>
								<?php
								}else{
									if($mystr== "Cancelled"){
										?>
								<a href="appointments.php?id=<?php echo $row['id']?>&del=del" onClick="return confirm('Are you sure you want to Delete?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-sm bx-tada-hover"></i></a>
								<?php
									}else{
										?>
								<a href="appointments.php?id=<?php echo $row['id'];?>&appr=update" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Approve"><i class="bx bx-comment-check bx-sm bx-tada-hover"></i></a>
								<?php /*?><a href="appointments.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Reschedule"><i class="bx bx-time bx-sm bx-tada-hover"></i></a><?php */?>
								<a href="appointments.php?id=<?php echo $row['id']?>&hacc=<?php echo $row['hacc']?>&pid=<?php echo $row['pid']?>&fees=<?php echo $row['consultancyFees']?>&can=update" onClick="return confirm('Are you sure you want to cancel?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Cancel"><i class="bx bx-comment-x bx-sm bx-tada-hover"></i></a>
								<?php
									}
									?>
								<?php
								};
								?>
							</div></td>
					</tr>
					<?php
					$cnt = $cnt + 1;
					}
				}
				?>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-2">
            <thead>
                <tr align="center">
                    <th colspan = "8">Completed Appointments</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <th>Fee</th>
                    <th>Date / Time</th>
                    <th>Created On</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = mysqli_stmt_init( $link );
                $sql = mysqli_prepare( $link, "select doctors.name as docname, patients.name as pname, appointment.*  from appointment join doctors on doctors.id=appointment.doctorId join patients on patients.id=appointment.userId where appointment.completed != 0 order by appointment.id desc" );
                mysqli_stmt_execute( $sql );
                $result = mysqli_stmt_get_result( $sql );
                $cnt = 1;
                if ( !mysqli_num_rows( $result ) ) {
                    ?>
					<tr>
						<td colspan="8"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
					</tr>
					<tr>
						<td colspan="8"><div class="text-center">
								<h5>No Data Yet</h5>
							</div></td>
					</tr>
					<?php
                } else {
                    while ( $row = mysqli_fetch_array( $result ) ) {
						
						$status = $pstatus = $color = "";

						if($row['completed']==3){
							$status =  "Patient Cancelled";
							$color = "Red";
							$pstatus = "90% Refund";
						}elseif($row['completed']==2){
							$status =  "Doctor Cancelled";
							$color = "Crimson";
							$pstatus = "Refunded";

						}elseif($row['completed']==4){
							$status =  "Missed";
							$color = "Aqua";
							$pstatus = "Null";
						}elseif($row['completed']==5){
							$status =  "Admin Cancelled";
							$color = "Crimson";
							$pstatus = "Refunded";
						}else{
							$status =  "Completed";
							$color = "green";
							$pstatus = "Done";
						}
						
                        ?>
						<tr>
							<td><?php echo $cnt;?>.</td>
							<td><?php echo $row['docname'];?></td>
							<td><?php echo $row['pname'];?></td>
							<td><?php echo $row['consultancyFees'];?></td>
							<td><?php echo $row['appointmentDate'];?>/<?php echo $row['appointmentTime'];?></td>
							<td><?php echo $row['postingDate'];?></td>
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