<?php
session_start();

require_once "config.php";

if(!empty($_POST['submitmessage'])){
		
	$aname=$_POST['fullname'];
	$acontact=$_POST['phnnum'];
	$aemail=$_POST['cemail'];
	$amsg=$_POST['cmessage'];

	$sql = mysqli_prepare( $link, "insert into id17137158_pmsproject.messages(fullname,email,contactno,message) values(?,?,?,?)" );
	mysqli_stmt_bind_param($sql, "ssis", $aname, $aemail, $acontact, $amsg);
	if(mysqli_stmt_execute($sql)){
		$_SESSION[ 'msgscs' ]="Message Sent Successfully";
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

    <header class="page-header header container-fluid">
        <div class="overlay">
		
		
		<p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p>
		<p style="color:green;"><?php echo $_SESSION['msgscs'];?><?php $_SESSION['msgscs']="";?></p>
		</div>
		
		
		
		
		
		
         
        </div>
    </header>
	
<?php
include('modals.php');
?>
	<!--
    <div class="background">
        <div class="container team">
            <div class="row">
                <div class="card col-lg-4 col-md-4 col-sm-6 text-center">
                    <img class="card-img-top rounded-circle" src="images/team1.jpg" alt="Card image">
                    <div class="card-body">
                        <h4 class="card-title">Jane Doe</h4>
                        <p class="card-text">Job Description</p>
                    </div>
                </div>
                <div class="card col-lg-4 col-md-4 col-sm-6 text-center">
                    <img class="card-img-top rounded-circle" src="images/team2.png" alt="Card image">
                    <div class="card-body">
                        <h4 class="card-title">Jane Doe</h4>
                        <p class="card-text">Job Description</p>
                    </div>
                </div>
                <div class="card col-lg-4 col-md-4 col-sm-6 text-center">
                    <img class="card-img-top rounded-circle" src="images/team3.png" alt="Card image">
                    <div class="card-body">
                        <h4 class="card-title">Jane Doe</h4>
                        <p class="card-text">Job Description</p>
                    </div>
                </div>
                <div class="card col-lg-4 col-md-4 col-sm-6 text-center">
                    <img class="card-img-top rounded-circle" src="images/team1.jpg" alt="Card image">
                    <div class="card-body">
                        <h4 class="card-title">Jane Doe</h4>
                        <p class="card-text">Job Description</p>
                    </div>
                </div>
                <div class="card col-lg-4 col-md-4 col-sm-6 text-center">
                    <img class="card-img-top rounded-circle" src="images/team2.png" alt="Card image">
                    <div class="card-body">
                        <h4 class="card-title">Jane Doe</h4>
                        <p class="card-text">Job Description</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

-->

    <script src="tools/jquery-3.6.0.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="tools/main.js"></script>
</body>
</html>