<?php
// Initialize the session
session_start();

include( '../config.php' );

// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}



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

$password_err = $npassword_err = $cpassword_err = "";
$password = $npassword = $cpassword = "";

if(isset($_POST['submit'])){
	if ( empty( trim( $_POST[ "password" ] ) ) ) {
		$password_err = "Please Enter Current Password.";
	} else {
		$password = trim( $_POST[ "password" ] );
	}

	if ( empty( trim( $_POST[ "npassword" ] ) ) ) {
		$npassword_err = "Please Enter The New Password.";
	} elseif ( strlen( trim( $_POST[ "npassword" ] ) ) < 6 ) {
        $npassword_err = "Password must have atleast 6 characters.";
    } else {
		$npassword = trim( $_POST[ "npassword" ] );
	}
	
	if ( empty( trim( $_POST[ "cpassword" ] ) ) ) {
		$cpassword_err = "Please Confirm The New Password.";
	} elseif ( empty( $npassword_err ) && ( $npassword !== trim( $_POST[ "cpassword" ] ) ) ) {
		$cpassword_err = "Password did not match.";
	}else {
		$cpassword = trim( $_POST[ "cpassword" ] );
	}
	
	if ( empty( $password_err ) && empty( $npassword_err ) && empty( $cpassword_err ) ){
		$sql = mysqli_query( $link, "SELECT password FROM id17137158_pmsproject.doctors WHERE id = '".$_SESSION['id']."'");
		$row = mysqli_fetch_array( $sql );
		$hashed_password = $row['password'];
		if ( password_verify( $password, $hashed_password ) ){
			$sql = mysqli_prepare( $link, "UPDATE id17137158_pmsproject.doctors set password = ? WHERE id = '".$_SESSION['id']."'");
			$param_password = password_hash( $npassword, PASSWORD_DEFAULT );
			mysqli_stmt_bind_param( $sql, "s", $param_password );
			
			if ( mysqli_stmt_execute( $sql ) ) {
                echo "<script>alert('Password Changed Successfully');</script>";
                // Redirect to login page
                echo "<script>window.location.href ='index.php'</script>";
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
				echo "<script>window.location.href ='dashboard.php'</script>";
            }
		}else{
			echo "<script>alert('Wrong Password');</script>";
		}
	}	
}



?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Change Password</title>
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
        <h3 id "welcome-header">Change Password</h3>
    
		<p style="color:red;"><?php echo $_SESSION['msg'];?>
			<?php $_SESSION['msg']="";?>
		</p>
		<p style="color:green;"><?php echo $_SESSION['msgscs'];?>
			<?php $_SESSION['msgscs']="";?>
		</p>
	</div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="row">
            <div class="form-group col-12">
                <label>Enter Current Password</label>
                <input id="password" type="password" name="password" class="togglepass form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Current Password" >
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
			</div>
            <div class="form-group col-sm-12 col-lg-6">
                <label>Enter New Password</label>
                <input id="npassword" type="password" name="npassword" class="togglepass form-control <?php echo (!empty($npassword_err)) ? 'is-invalid' : ''; ?>" placeholder="New Password" >
                <span class="invalid-feedback"><?php echo $npassword_err; ?></span>
			</div>
            <div class="form-group col-sm-12 col-lg-6">
                <label>Confirm Password</label>
                <input id="cpassword" type="password" name="cpassword" class="togglepass form-control <?php echo (!empty($cpassword_err)) ? 'is-invalid' : ''; ?>" placeholder="Confirm Password">
                <span class="invalid-feedback"><?php echo $cpassword_err; ?></span>
			</div>
            <div class="form-group col-12">
                <input class="" type="checkbox" onclick="togglepassvisible()">
                <label class="">Show Passwords</label>
            </div>
            
        </div>
		<div class="form-group text-right">
			<a style="color:white" href="dashboard.php">
				<button type="button" class="btn btn-primary" name="update">Cancel</button>
			</a>
			<input type="submit" class="btn btn-success" name="submit" value="Change Password">
		</div>
    </form>

</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>