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
$fullname = $phnno = $username = $password = $confirm_password = "";
$fullname_err = $phnno_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ( $_SERVER[ "REQUEST_METHOD" ] == "POST" ) {

	
    function test_input( $data ) {
        $data = trim( $data );
        $data = stripslashes( $data );
        $data = htmlspecialchars( $data );
        return $data;
    }
	
	
	
	
	

    if ( empty( trim( $_POST[ "fullname" ] ) ) ) {
        $fullname_err = "Please enter your name.";
    } elseif ( !preg_match( '/^[a-zA-Z0-9_ ]+$/', trim( $_POST[ "fullname" ] ) ) ) {
        $fullname_err = "Name can only contain letters, numbers, and underscores.";
    } else {
        $fullname = trim( $_POST[ "fullname" ] );
    }
	if ( empty( trim( $_POST[ "phnno" ] ) ) ) {
        $phnno_err = "Please enter your name.";
    } elseif ( !preg_match( '/^[0-9+]+$/', trim( $_POST[ "phnno" ] ) ) ) {
        $phnno_err = "Please enter a valid phone number.";
    } else {
        $phnno = trim( $_POST[ "phnno" ] );
    }


    if ( empty( trim( $_POST[ "email" ] ) ) ) {
        $username_err = "Please enter an Email.";
    } elseif ( !preg_match( '/^[a-zA-Z0-9_@.-]+$/', trim( $_POST[ "email" ] ) ) ) {
        $username_err = "Email can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT id FROM patients WHERE email = ?";
        if ( $stmt = mysqli_prepare( $link, $sql ) ) {
            mysqli_stmt_bind_param( $stmt, "s", $param_username );
            $param_username = trim( $_POST[ "email" ] );
            if ( mysqli_stmt_execute( $stmt ) ) {
                mysqli_stmt_store_result( $stmt );
                if ( mysqli_stmt_num_rows( $stmt ) > 0 ) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim( $_POST[ "email" ] );
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close( $stmt );
        }
    }

    // Validate password
    if ( empty( trim( $_POST[ "password" ] ) ) ) {
        $password_err = "Please enter a password.";
    } elseif ( strlen( trim( $_POST[ "password" ] ) ) < 6 ) {
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim( $_POST[ "password" ] );
    }

    // Validate confirm password
    if ( empty( trim( $_POST[ "confirm_password" ] ) ) ) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim( $_POST[ "confirm_password" ] );
        if ( empty( $password_err ) && ( $password != $confirm_password ) ) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if ( empty( $fullname_err ) && empty( $phnno_err ) && empty( $username_err ) && empty( $password_err ) && empty( $confirm_password_err ) ) {

        // Prepare an insert statement
        $sql = "INSERT INTO id17137158_pmsproject.patients (name, PatientContno, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init( $link );

        if ( $stmt = mysqli_prepare( $link, $sql ) ) {
            mysqli_stmt_bind_param( $stmt, "siss", $fullname, $phnno, $username, $param_password );
            $param_password = password_hash( $password, PASSWORD_DEFAULT ); // Creates a password hash
            if ( mysqli_stmt_execute( $stmt ) ) {
                echo "<script>alert('Registration Successful. Please Log In.');</script>";
                // Redirect to login page
                echo "<script>window.location.href ='index.php'</script>";
            } else {
                echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            }
            mysqli_stmt_close( $stmt );
        }
    }

    // Close connection
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
<nav class="navbar navbar-expand-md"> <a class="navbar-brand" href="index.php">MIGORI</a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#main-navigation"> <span class="navbar-toggler-icon"></span> </button>
    <div class="collapse navbar-collapse" id="main-navigation">
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" href="index.php">Home</a> </li>
            <li class="nav-item"> <a class="nav-link" href="about.php">About</a> </li>
            
        </ul>
    </div>
</nav>
<header class="height-100 page-header header container-fluid">
    <div class="overlay">
        <div class="description">
            <h2 id="punguza">MIGORI LEVEL IV HOSPITAL</h2><h2 id="punguza">Sign Up Sheet</h2>
                <p>Please fill this form to create an account.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <input type="text" name="fullname" placeholder="Full Name" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>" required>
                        <span class="invalid-feedback"><?php echo $fullname_err; ?></span> </div>
                    <div class="form-group">
                        <input type="text" name="phnno" placeholder="Phone No." class="form-control <?php echo (!empty($phnno_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phnno; ?>" required>
                        <span class="invalid-feedback"><?php echo $phnno_err; ?></span> </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                        <span class="invalid-feedback"><?php echo $username_err; ?></span> </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password"class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" required>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span> </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" placeholder="Confirm Password"class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" required>
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span> </div>
                    <div class="form-group">
                        <input type="reset" class="btn btn-secondary" value="Reset">
                        <input type="submit" class="btn btn-success" value="Create Account">
                        
                    </div>
                    <p>Already have an account? <a href="index.php">Login here</a></p>
                </form>
        </div>
    </div>
</header>
<script src="tools/jquery-3.6.0.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="tools/main.js"></script>
</body>
</html>
