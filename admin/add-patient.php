<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if ( isset( $_POST[ 'submit' ] ) ) {
    $docname = $_POST[ 'pname' ];
    $docaddress = $_POST[ 'paddress' ];
    $doccontactno = $_POST[ 'pcontact' ];
    $docemail = $_POST[ 'pemail' ];
	$pgender = $_POST[ 'flexRadioDefault' ];
	$pdob = $_POST[ 'pdob' ];
    //$password = md5( $_POST[ 'npass' ] );
	$password = password_hash($_POST[ 'npass' ], PASSWORD_DEFAULT);
    $sql = mysqli_prepare( $link, "insert into id17137158_pmsproject.patients(name,PatientAdd,PatientContno,email,PatientGender,PatientAge,password) values(?,?,?,?,?,?,?)" );
    mysqli_stmt_bind_param($sql, "sssssis", $docname, $docaddress, $doccontactno, $docemail, $pgender, $pdob, $password);
	
	
	if ( mysqli_stmt_execute($sql)) {
        echo "<script>alert('Patient info added Successfully');</script>";
        echo "<script>window.location.href ='patients.php'</script>";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Add Patient</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
<script type="text/javascript">
	function valid(){
		if(document.adddoc.npass.value!= document.adddoc.cfpass.value){
			alert("Password and Confirm Password Field do not match  !!");
			document.adddoc.cfpass.focus();
			return false;
		}
		return true;
	}
</script> 

</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
<h3 id "welcome-header">Adding Patient</h3>
<p>Fill all fields then save</p>
<div class="panel-body">
    <form role="form" name="adddoc" method="post" onSubmit="return valid();">
        <div>
            <div class="row">
                
                <div class="form-group col-md-6">
                    <label for="doctorname">Patient Name</label>
                    <input type="text" name="pname" class="form-control"  placeholder="Name" required="true">
                </div>
                
                
                <div class="form-group col-md-6">
                    <label for="fess">Phone No.:</label>
                    <input type="text" name="pcontact" class="form-control"  placeholder="Phone" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Patient Email:</label>
                    <input type="email" id="pemail" name="pemail" class="form-control"  placeholder="Email">
				</div>
				<div class="form-group col-md-6">
                    <label for="fess">Patients Age:</label>
                    <input type="text" id="pdob" name="pdob" class="form-control"  placeholder="Age">
				</div>
				
				
				<div class="form-group col-md-6">				
					<label for="fess">Patient Gender:</label>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="Male" checked>
					  <label class="form-check-label" for="flexRadioDefault1">
						Male
					  </label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="Female" >
					  <label class="form-check-label" for="flexRadioDefault2">
						Female
					  </label>
					</div>
				</div>
				
				<div class="form-group col-md-6">
                    <label for="address">Patient Address:</label>
                    <textarea name="paddress" class="form-control"  placeholder="Address"></textarea>
                </div>

				
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="npass" class="form-control"  placeholder="New Password" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword2">Confirm Password</label>
                    <input type="password" name="cfpass" class="form-control"  placeholder="Confirm Password" required="required">
                </div>
            </div>
            <div class="text-right">
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