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
    $docspecialization = $_POST[ 'Doctorspecialization' ];
    $docname = $_POST[ 'docname' ];
    $docaddress = $_POST[ 'clinicaddress' ];
    $docfees = $_POST[ 'docfees' ];
    $doccontactno = $_POST[ 'doccontact' ];
    $docemail = $_POST[ 'docemail' ];
    //$password = md5( $_POST[ 'npass' ] );
	$password = password_hash($_POST[ 'npass' ], PASSWORD_DEFAULT);
    $sql = mysqli_prepare( $link, "insert into id17137158_pmsproject.doctors(specilization,name,address,docFees,contactno,email,password) values(?,?,?,?,?,?,?)" );
    mysqli_stmt_bind_param($sql, "sssdiss", $docspecialization, $docname, $docaddress, $docfees, $doccontactno, $docemail, $password);
	
	if ( mysqli_stmt_execute($sql) ) {
        echo "<script>alert('Doctor info added Successfully');</script>";
        echo "<script>window.location.href ='doctors.php'</script>";

    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Add Doctor</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
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
<script>
	function checkemailAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
			url: "check-availability.php",
			data:'emailid='+$("#docemail").val(),
			type: "POST",
			success:function(data){
				$("#email-availability-status").html(data);
				$("#loaderIcon").hide();
			},
			error:function (){}
		});
	}
</script>
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
<h3 id "welcome-header">Adding Doctor</h3>
<p>Fill all fields then save</p>
<div class="panel-body">
    <form role="form" name="adddoc" method="post" onSubmit="return valid();">
        <div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="DoctorSpecialization">Doctor Specialization</label>
                    <select name="Doctorspecialization" class="form-control" required="true">
                        <option value="">Select Specialization</option>
                        <?php
                        $ret = mysqli_query( $link, "select * from doctorspecilization" );
                        while ( $row = mysqli_fetch_array( $ret ) ) {
                            ?>
                        <option value="<?php echo htmlentities($row['specilization']);?>"><?php echo htmlentities($row['specilization']);?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="doctorname">Doctor Name</label>
                    <input type="text" name="docname" class="form-control"  placeholder="Enter Doctor Name" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Doctor Clinic Address</label>
                    <textarea name="clinicaddress" class="form-control"  placeholder="Enter Doctor Clinic Address" required="true"></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Consultancy Fees</label>
                    <input type="text" name="docfees" class="form-control"  placeholder="Enter Doctor Consultancy Fees" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Contact no</label>
                    <input type="text" name="doccontact" class="form-control"  placeholder="Enter Doctor Contact no" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Email</label>
                    <input type="email" id="docemail" name="docemail" class="form-control"  placeholder="Enter Doctor Email id" required="true" onBlur="checkemailAvailability()">
                    <span id="email-availability-status"></span></div>
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