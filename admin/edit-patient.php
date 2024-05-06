<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$msg="";
$did=intval($_GET['id']);// get patient id
if(isset($_POST['submit'])){
	$pname=cleandata($_POST['pname']);
	$pcontact=cleandata($_POST['pcontact']);
	$pemail=cleandata($_POST['pemail']);
	$pgender=cleandata($_POST['pgender']);
	$paddress=cleandata($_POST['paddress']);
	$page=cleandata($_POST['page']);
	$pmedhis=cleandata($_POST['pmedhis']);
	
	
	$npass = cleandata($_POST['npass']);
	
	
	if(!empty($npass)){
		$npass_err = "";
		if (strlen(trim($npass)) < 6 ){
			$npass_err = "Password must be at least 6 characters long";
		}else{
			$hashpass = password_hash( $npass, PASSWORD_DEFAULT );
			$sql=mysqli_prepare($link,"Update id17137158_pmsproject.patients set name=?,PatientContno=?,email=?,PatientAdd=?,PatientGender=?,PatientAge=?,PatientMedhis=?,password=? where id='$did'");
			mysqli_stmt_bind_param($sql, "sisssiss", $pname, $pcontact, $pemail, $paddress, $pgender, $page, $pmedhis,$hashpass);
			if(mysqli_stmt_execute($sql)){
				//$msg="Patient Details updated Successfully";
				echo "<script>alert('Patient Details and Password Updated Successful');</script>";
				echo "<script>window.location.href ='patients.php'</script>";
			}
		}
	}else{
		$sql=mysqli_prepare($link,"Update id17137158_pmsproject.patients set name=?,PatientContno=?,email=?,PatientAdd=?,PatientGender=?,PatientAge=?,PatientMedhis=? where id='$did'");
		mysqli_stmt_bind_param($sql, "sisssis", $pname, $pcontact, $pemail, $paddress, $pgender, $page, $pmedhis);
		if(mysqli_stmt_execute($sql)){
			//$msg="Patient Details updated Successfully";
			echo "<script>alert('Patient Details updated Successfully');</script>";
			echo "<script>window.location.href ='patients.php'</script>";
		}
	}
	
	
	
	
	
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Edit Patient</title>
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
	<h4>Editing Patient</h4>
	<h5 style="color: green; font-size:18px; "><?php if($msg) { echo htmlentities($msg);}?> </h5>
	
	<?php $sql=mysqli_query($link,"select * from id17137158_pmsproject.patients where id='$did'");
	$data=mysqli_fetch_array($sql)
	
	?>
	<h4><?php echo htmlentities($data['name']);?>'s Profile</h4>
	<p><b>Date Registered: </b><?php echo date('d-m-Y h:ia', strtotime($data['CreationDate']));?></p>
	<?php if($data['UpdationDate']){?>
	<p><b>Last Update: </b><?php echo date('d-m-Y h:ia', strtotime($data['UpdationDate']));?></p>
	<?php } ?>

    <form role="form" name="adddoc" method="post" onSubmit="return valid();">
        <div>
			<input autocomplete="false" name="hidden" type="text" style="display: none">
			<input autocomplete="false" name="hidden" type="password" style="display: none">
            <div class="row">
                
                <div class="form-group col-md-6">
                    <label for="doctorname">Patient Name</label>
                    <input type="text" name="pname" class="form-control"  placeholder="Name" value="<?php echo htmlentities($data['name']);?>" required="true">
                </div>
				<div class="form-group col-md-6">
                    <label for="fess">Patients Email</label>
                    <input type="email" name="pemail" class="form-control"  placeholder="Email" value="<?php echo htmlentities($data['email']);?>">
                </div>
				<div class="form-group col-md-6">
                    <label for="fess">Patient Contact no</label>
                    <input type="text" name="pcontact" class="form-control"  placeholder="Contact no" value="<?php echo htmlentities($data['PatientContno']);?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Patients Age</label>
                    <input name="page" class="form-control"  placeholder="Age" value ="<?php echo htmlentities($data['PatientAge']);?>">
                </div>
                
                
                <div class="form-group col-md-6">
                    <label for="fess">Medical History</label>
                    <textarea id="pmedhis" name="pmedhis" class="form-control"  placeholder="Enter some medical history"><?php echo htmlentities($data['PatientMedhis']);?></textarea>
				</div>
				<div class="form-group col-md-6">
                    <label for="fess">Patient Address</label>
                    <textarea id="paddress" name="paddress" class="form-control"  placeholder="Patients Adress"><?php echo htmlentities($data['PatientAdd']);?></textarea>
				</div>
				<div class="form-group col-md-6">				
					<label for="fess">Patient Gender:</label>
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
				<div class="form-group col-md-6">
                    <label for="npass">Update Password</label>
                    <input autocomplete="off" type="password" id="npass" name="npass" class="togglepass form-control <?php echo (!empty($npass_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter new password, otherwise leave blank"/>
					<span class="invalid-feedback"><?php echo $npass_err; ?></span>
                    <input type="checkbox" onclick="togglepassvisible()">Show Password
				</div>
				
				
                
                
            </div>
            <div class="text-right">
				<a style="color:white" href="patients.php"><button type="button" class="btn btn-primary" name="update">Back</button></a>
                <button type="submit" name="submit" id="submit" class="btn btn-success">Save & Close</button>
            </div>
        </div>
    </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
</body>
</html>