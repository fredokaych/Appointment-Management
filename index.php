<?php
// Initialize the session
session_start();
date_default_timezone_set('Africa/Nairobi');
// Check if the user is already logged in, if yes then redirect him to welcome page
if ( isset( $_SESSION[ "loggedin" ] ) && $_SESSION[ "loggedin" ] === true ) {
    header( "location: " . $_SESSION[ "persona" ] );
    exit;
}


require_once "config.php";

// Define variables and initialize with empty values
$persona = $username = $password = "";
$username_err = $password_err = $login_err = "";
$_SESSION[ 'msg' ] = "";
$_SESSION[ 'msgscs' ] = "";

// Processing form data when form is submitted
if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {
	if( !empty($_POST['submitlogin'] )) {
		$persona = trim( $_POST[ "hiddencontainer" ] );
		if ( empty( trim( $_POST[ "username" ] ) ) ) {
			$username_err = "Please enter username.";
		} else {
			$username = trim( $_POST[ "username" ] );
		}

		if ( empty( trim( $_POST[ "password" ] ) ) ) {
			$password_err = "Please enter your password.";
		} else {
			$password = trim( $_POST[ "password" ] );
		}


		// Validate credentials
		if ( empty( $username_err ) && empty( $password_err ) ) {
			$sql = "SELECT id, email, password FROM id17137158_pmsproject." . $persona . "s WHERE email = ?";
			if ( $stmt = mysqli_prepare( $link, $sql ) ) {
				mysqli_stmt_bind_param( $stmt, "s", $param_username );
				$param_username = $username;
				if ( mysqli_stmt_execute( $stmt ) ) {
					mysqli_stmt_store_result( $stmt );
					if ( mysqli_stmt_num_rows( $stmt ) == 1 ) {
						mysqli_stmt_bind_result( $stmt, $id, $username, $hashed_password );
						if ( mysqli_stmt_fetch( $stmt ) ) {
							if ( password_verify( $password, $hashed_password ) ) {
								$_SESSION[ "loggedin" ] = true;
								$_SESSION[ "id" ] = $id;
								$_SESSION[ "username" ] = $username;
								$_SESSION[ "persona" ] = $persona;
								$uip=$_SERVER['REMOTE_ADDR'];
								$status=1;
								$log=mysqli_query($link,"insert into ".$persona."slog(uid,username,userip,status) values('".$id."','".$username."','$uip','$status')");
								header( "location:" . $persona );
								exit;
							} else {
								$login_err = "Invalid username or password.";
								$_SESSION[ 'msg' ] = $login_err;
								$uip=$_SERVER['REMOTE_ADDR'];
								$status=0;
								$log=mysqli_query($link,"insert into ".$persona."slog(username,userip,status) values('".$username."','$uip','$status')");
							}
						}
					} else {
						$login_err = "Invalid username or password.";
						$_SESSION[ 'msg' ] = $login_err;
					}
				} else {
					echo "Oops! Something went wrong. Please try again later.";
					$_SESSION[ 'msg' ] = "Oops! Something went wrong. Please try again later.";
				}
				mysqli_stmt_close( $stmt );
			}
		}
		mysqli_close( $link );

	}elseif(!empty($_POST['submitmessage'])){
		
		$aname=$_POST['fullname'];
		$acontact=$_POST['phnnum'];
		$aemail=$_POST['cemail'];
		$amsg=$_POST['cmessage'];
		
		$sql = mysqli_prepare( $link, "insert into id17137158_pmsproject.messages(fullname,email,contactno,message) values(?,?,?,?)" );
    	mysqli_stmt_bind_param($sql, "ssis", $aname, $aemail, $acontact, $amsg);
		if(mysqli_stmt_execute($sql)){
			$_SESSION[ 'msgscs' ]="Message Sent Successfully";
		}
		
	}else{
		
	}
}
?>

<html>
<head>
<title>Patient Management System</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="tools/main.css">
</head>

<body>
<nav class="navbar navbar-expand-md"> <a class="navbar-brand" href="#">MIGORI</a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation"> <span class="navbar-toggler-icon"></span> </button>
    <div class="collapse navbar-collapse" id="main-navigation">
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" href="index.php">Home</a> </li>
            <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle='modal' data-target='#contact-modal'>Contact</a> </li>
        </ul>
    </div>
</nav>
<header class="height-100 page-header header container-fluid">
    <div class="overlay">
        <div class="description">
            <h1>Welcome to</h1>
            <h2>MIGORI LEVEL IV HOSPITAL</h2>
			<h1 style="color: lime">Appointment Management System</h1>
            <p>Please Sign In or create account</p>
            <button id = "patient" data-id = "patient" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Patient</button>
            <button id = "doctor" data-id = "doctor" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Doctor</button>
            <button id = "admin" data-id = "admin" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Admin</button>
            <p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p>
			<p style="color:green;"><?php echo $_SESSION['msgscs'];?><?php $_SESSION['msgscs']="";?></p>
        </div>
    </div>
</header>



<?php
include('modals.php');
?>

<script src="tools/jquery-3.6.0.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="tools/main.js"></script>
</body>
</html>
