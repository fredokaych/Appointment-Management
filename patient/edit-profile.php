<?php
// Initialize the session
ob_start();
session_start();

include('../config.php');
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$id=$_SESSION['id'];
if(isset($_POST['submit'])){
	$pname=$_POST['pname'];
	$pcontact=$_POST['pcontact'];
	$pemail=$_POST['pemail'];
	$pgender=$_POST['pgender'];
	$paddress=$_POST['paddress'];
	$page=$_POST['page'];
	$pmedhis=$_POST['pmedhis'];
	$sql=mysqli_prepare($link,"Update id17137158_pmsproject.patients set name=?,PatientContno=?,email=?,PatientAdd=?,PatientGender=?,PatientAge=?,PatientMedhis=? where id='$id'");
	mysqli_stmt_bind_param($sql, "sisssis", $pname, $pcontact, $pemail, $paddress, $pgender, $page, $pmedhis);
	
	if(mysqli_stmt_execute($sql)){
		$msg="Patient Details updated Successfully";
        header( "location: view-profile.php" );
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Edit Profile</title>
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
    <h3 id "welcome-header">My Profile</h3>
		<p>View your profile, edit where neccesary</p></div>


	
	<?php 
	$sql=mysqli_query($link,"select * from id17137158_pmsproject.patients where id='$id'");
	$data=mysqli_fetch_array($sql)
	
	?>
	
	
    <form role="form" name="adddoc" method="post">
        <div>
            <div class="row">
                
                <div class="form-group col-md-6">
                    <label for="doctorname">Name</label>
                    <input type="text" name="pname" class="form-control"  placeholder="Name" value="<?php echo htmlentities($data['name']);?>" required="true">
                </div>
				<div class="form-group col-md-6">
                    <label for="fess">Email</label>
                    <input type="email" name="pemail" class="form-control"  placeholder="Email" value="<?php echo htmlentities($data['email']);?>">
                </div>
				<div class="form-group col-md-6">
                    <label for="fess">Contact no</label>
                    <input type="text" name="pcontact" class="form-control"  placeholder="Contact no" value="<?php echo htmlentities($data['PatientContno']);?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Age</label>
                    <input name="page" class="form-control"  placeholder="Age" value ="<?php echo htmlentities($data['PatientAge']);?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Medical History</label>
                    <textarea id="pmedhis" name="pmedhis" class="form-control"  placeholder="Enter some medical history"><?php echo htmlentities($data['PatientMedhis']);?></textarea>
				</div>
				<div class="form-group col-md-6">
                    <label for="fess">Address</label>
                    <textarea id="paddress" name="paddress" class="form-control"  placeholder="Patients Adress"><?php echo htmlentities($data['PatientAdd']);?></textarea>
				</div>
				<div class="form-group col-md-6">				
					<label for="fess">Gender:</label>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="pgender" id="pgender" value="Male" <?php echo htmlentities($data['PatientGender'])==='Male' ? 'checked="checked"':''?>>
					  <label class="form-check-label" for="flexRadioDefault1">
						Male
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="pgender" id="pgender" value="Female" <?php echo htmlentities($data['PatientGender'])==='Female' ? 'checked="checked"':''?>>
					  <label class="form-check-label" for="flexRadioDefault2">
						Female
					  </label>
					</div>
				</div>
				
				
                
                
            </div>
            <div class="text-right">
				<a style="color:white" href="view-profile.php"><button type="button" class="btn btn-primary" name="update">Back</button></a>
                <button type="submit" name="submit" id="submit" class="btn btn-success">Save & Close</button>
            </div>
        </div>
    </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>