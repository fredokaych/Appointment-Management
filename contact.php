<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if ( isset( $_SESSION[ "loggedin" ] ) && $_SESSION[ "loggedin" ] === true ) {
    header( "location: " . $_SESSION[ "persona" ] );
    exit;
}


// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$persona = $username = $password = "";
$username_err = $password_err = $login_err = "";
$_SESSION[ 'msg' ] = "";

// Processing form data when form is submitted
if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {
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
                            header( "location:" . $persona );
                            exit;
                        } else {
                            $login_err = "Invalid username or password.";
                            $_SESSION[ 'msg' ] = $login_err;
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
<nav class="navbar navbar-expand-md"> <a class="navbar-brand" href="#">PMS</a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation"> <span class="navbar-toggler-icon"></span> </button>
    <div class="collapse navbar-collapse" id="main-navigation">
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" href="index.php">Home</a> </li>
            <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
            <li class="nav-item"> <a class="nav-link" data-toggle='modal' data-target='#contact-modal'>Contact</a> </li>
        </ul>
    </div>
</nav>
<header class="page-header header container-fluid">
    <div class="overlay">
        <div class="description">
            <h1>Welcome to</h1>
            <h2>ST. contact PMS</h2>
            <p>Please Sign In or create account</p>
            <button id = "patient" data-id = "patient" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Patient</button>
            <button id = "doctor" data-id = "doctor" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Doctor</button>
            <button id = "admin" data-id = "admin" class="btn btn-outline-secondary btn-lg" data-toggle="modal" data-target="#demo-modal">Admin</button>
            <p style="color:red;"><?php echo $_SESSION['msg'];?>
                <?php $_SESSION['msg']="";?>
            </p>
        </div>
    </div>
</header>

<!--another modal-->
<div id="demo-modal" class="modal fade bd-example-modal-lg">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"> <span>x</span> </button>
            </div>
            <div class="modal-body ">
                <h3 id = "mylbl">Sign In</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "needs-validation">
                    <?php /*?>
<input type="text" class="form-control" placeholder="Username" name="username">
<br>
<input type="password" id="password" class="form-control" placeholder="Password" name="password"><br>
<input type="submit" name="login" id="loginbtn" class="btn" value="Enter"><?php */?>
                    <div class="form-group">
                        <input type="hidden" name="hiddencontainer" id = "hiddencontainer" value="Doctor" >
                        <label>Username</label>
                        <input id="uname" type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                        <span class="invalid-feedback"><?php echo $username_err; ?></span> </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span> </div>
                    <div class="form-group text-right">
                        <input type="submit" class="btn btn-primary" value="Login">
                    </div>
                    <div class="form-group text-centre">
                        <?php
						
							?>
                        <p id="mycreate">Don't have an account? <a href='#' data-toggle='modal' data-target='#contact-modal'>Sign up now</a></p>
                        <?php

						?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="contact-modal" class="modal fade bd-example-modal-lg">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="close"> <span>x</span> </button>
            </div>
            <div class="modal-body ">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "needs-validation">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input id="fullname" type="text" name="fullname" class="form-control" value="" required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Phone No.</label>
                        <input id="phnnum" type="text" name="phnnum" class="form-control" value="" required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Enail</label>
                        <input id="cemail" type="email" name="cemail" class="form-control" value="" required>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="cmessage" id="cmessage" class="form-control" required></textarea>
                        <span class="invalid-feedback"></span> </div>
                    <div class="form-group text-right">
                        <input type="submit" class="btn btn-primary" value="Send Message">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="tools/jquery-3.6.0.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="tools/main.js"></script>
</body>
</html>
