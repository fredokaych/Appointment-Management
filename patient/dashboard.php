<?php
// Initialize the session
//session_start();
// Check if the user is logged in, if not then redirect him to login page
include('../config.php');
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}




$sql = mysqli_query( $link, "select name from id17137158_pmsproject.patients where id = ".$_SESSION['id'] );
$name = mysqli_fetch_array($sql)['name'];
$welcome = greet().split_name($name)[0].",";
			

?>

<!DOCTYPE html>
<html>
<head>
<title>User | Dashboard</title>
<link rel="stylesheet" type="text/css" href="../tools/mytweaks.css">

</head>
<body id="body-pd">
	
<?php include('sidebar.php')?>
	
<div class=" bg-light container-fluid">
    <div class="card-body row">
        <div class="col-sm-12 col-lg-6">
            <h5 style="color:darkgreen"><?php echo ucfirst($welcome);?></h5>
            <h3>Dashboard</h3>
        </div>
        <div class="col-sm-12 col-lg-6 kibeleft">
            <h5 style="color: darkgreen"><?php echo date('l d-M-Y ');?></h5>
        </div>
    </div>
	
    <div class="card">
        <div class="row" style="margin: 10px 5px">
            <div class="col-sm-4" style="margin: 10px 0">
                <div class="card border-secondary">
                    <div class="card-body text-center">
						<i class="bx bx-user bx-md"></i>
						<h4 class="card-title"><a href="view-profile.php">My Profile</a></h4>
						<?php ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" style="margin: 10px 0">
                <div class="card border-secondary">
                    <div class="card-body text-center">
						<i class="bx bx-time bx-md"></i> 
						<h4 class="card-title"><a href="appointments.php">Appointments</a></h4>
						<?php ?>
                    </div>
                </div>
            </div>
			
			<div class="col-sm-4" style="margin: 10px 0">
				<div class="card border-secondary">
					<div class="card-body text-center">
						<i class="bx bx-money bx-md"></i> 
						<h4 class="card-title"><a href="payhist.php">Payments</a></h4>
						<?php ?>
					</div>
				</div>
			</div>
			<div class="col-sm-4" style="margin: 10px 0">
				<div class="card border-secondary">
					<div class="card-body text-center">
						<i class="bx bx-library bx-md"></i> 
						<h4 class="card-title"><a href="medical-history.php">Visit History</a></h4>
						<?php ?>
					</div>
				</div>
			</div>
			<?php 
			$sql = mysqli_query( $link, "select id from id17137158_pmsproject.patientmessages where patientID = ".$_SESSION['id']." and IsRead = 0" );
			$num_rows = mysqli_num_rows($sql);
			?>
			
			<div class="col-sm-4" style="margin: 10px 0">
				<div class="card border-secondary">
					<div class="card-body text-center">
						<i class="bx bx-message-square-detail bx-md"></i> 
						<h4 class="card-title">
							<a href="messages.php">My Messages
								<?php 
								if($num_rows==0){

								}else{
									echo '<span class="badge">'.$num_rows.'</span>';
								}

								?>
							</a>
						</h4>
						<?php ?>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
</body>
</html>