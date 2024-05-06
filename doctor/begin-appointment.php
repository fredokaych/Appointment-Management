<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}

$appid = $_GET[ 'id' ];
$ret1 = mysqli_query( $link, "select * from id17137158_pmsproject.appointment where id = '$appid'" );
while($row1 = mysqli_fetch_array($ret1)){
	$uid = $row1['userId'];
}

if(isset($_POST['submit'])){
	$bp=$_POST['bp'];
	$weight=$_POST['weight'];
	$bs=$_POST['bs'];
	$bt=$_POST['bt'];
	$mp=$_POST['mp'];
	$remarks=$_POST['remarks'];
	
	$sql=mysqli_query($link, "insert into id17137158_pmsproject.medhist(PatientID, BloodPressure, BloodSugar, Weight, Temperature, MedicalPres, remarks ) VALUES ('$uid', '$bp', '$bs', '$weight', '$bt', '$mp', '$remarks')");
	if($sql){
		$msg="Update Success";
		$sql=mysqli_query($link, "Update id17137158_pmsproject.appointment set completed = 1 where id='$appid'");
		echo "<script>window.location.href ='appointments.php'</script>";
	}
	
	
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Appointment Data</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <h3 id "welcome-header">Appointment | In Session</h3>
    <p>Patient Session Data</p>
    <p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p>
    <?php
    $ret = mysqli_query( $link, "select * from id17137158_pmsproject.patients where id = '$uid'" );
    $cnt = 1;
    while ( $row = mysqli_fetch_array( $ret ) ) {
        ?>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <tr align="center">
                <th colspan="6">Patient Details</th>
            </tr>
            <tr>
                <th>Name</th>
                <td><?php  echo $row['name'];?></td>
                <th>Email</th>
                <td><?php  echo $row['email'];?></td>
				<th>Mobile Number</th>
                <td><?php  echo $row['PatientContno'];?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php  echo $row['PatientGender'];?></td>
                <th>Age</th>
                <td><?php  echo $row['PatientAge'];?></td>
                <th>Registration</th>
                <td><?php  echo date('d-m-Y h:ia', strtotime($row['CreationDate']));?></td>
            </tr>
            
            <tr>
                <th>Medical History(if any)</th>
                <td><?php  echo $row['PatientMedhis'];?></td>
            </tr>
            <?php }?>
        </table>
    </div>
	
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-2">
            <tr align="center">
                <th colspan="5">Session Data and remarks</th>
            </tr>
            <tr>
                
                <th>Blood Pressure</th>
                <th>Weight</th>
                <th>Blood Sugar</th>
                <th>Body Temprature</th>
                <th>Medical Prescription</th>
            </tr>
            <form name="query" method="post">
				<tr>

					<td><input type="text" name="bp" class="form-control"  placeholder="BP"></td>
					<td><input type="text" name="weight" class="form-control"  placeholder="Weight"></td>
					<td><input type="text" name="bs" class="form-control"  placeholder="Blood Sugar"></td>
					<td><input type="text" name="bt" class="form-control"  placeholder="Temp."></td>
					<td><input type="text" name="mp" class="form-control"  placeholder="Prescription"></td>

				</tr>
				<tr>
					<th>Remarks</th>
					<td colspan="4"><textarea name="remarks" class="form-control" placeholder="Write a remark regarding this session"required></textarea></td>
				</tr>
				<tr>
					
					<td colspan="5">
						<div class="text-right">
							<a style="color:white" href="appointments.php"><button type="button" class="btn btn-primary" name="update">Cancel</button></a>
							<button type="submit" name="submit" id="submit" class="btn btn-success">Save & Complete Appointment</button>
						</div>
					</td>
				</tr>
			</form>
        </table>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>