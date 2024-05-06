<?php
// Initialize the session
session_start();

include('../config.php');
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if(isset($_GET['del'])){
	mysqli_query($link,"delete from doctors where id = '".$_GET['id']."'");
	$_SESSION['msg']="Data deleted !!";
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Reports</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <h3 id "welcome-header">Reports</h3>
    <p>Generate required reports</p>
	<div class="text-centre">
		<!-- <a class="btn btn-primary btn-success" href="../pdf/appointmentall.php" target="_blank" role="button">Appointment Report</a>
		<a class="btn btn-primary btn-success" href="../pdf/paymentall.php" target="_blank" role="button">Payment Report</a> -->
		<a class="btn btn-primary btn-success" href="../pdf/dbcschedule.php" target="_blank" role="button">DBC Schedule</a>
		<a class="btn btn-primary btn-success" href="../pdf/scholarnametags.php" target="_blank" role="button">DBC  Scholar Nametags</a>
		<a class="btn btn-primary btn-success" href="../pdf/workshopnametags.php" target="_blank" role="button">DBC  Workshop Presenter Nametags</a>
		<a class="btn btn-primary btn-success" href="../pdf/certificates/certificates.php" target="_blank" role="button">Certificates</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/workshopsroster.php" target="_blank" role="button">Workshops Roster</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/panelsroster.php" target="_blank" role="button">Panels Roster</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/csroster.php" target="_blank" role="button">CS Roster</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/wiwc1.php" target="_blank" role="button">WIWC1</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/wiwc2.php" target="_blank" role="button">WIWC2</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/speedpitchers.php" target="_blank" role="button">Speed Pitchers</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/speedcatchers.php" target="_blank" role="button">Speed Catchers</a>
		<a class="btn btn-primary btn-success" href="../pdf/rosters/medicalscholars.php" target="_blank" role="button">Medical Scholars</a>
		<a class="btn btn-primary btn-success" href="../pdf/foldedpaper/foldedpaper.php" target="_blank" role="button">Folded Papers</a>
		<a class="btn btn-primary btn-success" href="../pdf/cdgreceipt/cdgreceipt.php" target="_blank" role="button">CDG Receipts</a>

		

		<!-- <a class="btn btn-primary btn-success" href="../pdf/gsc.php" target="_blank" role="button">GSCertificate</a>
		<a class="btn btn-primary btn-success" href="../pdf/gswl.php" target="_blank" role="button">GS Welcome Letter</a> -->
		
	</div>
	
	<?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>	
	
	
	<div class="table-responsive">
		
	</div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>