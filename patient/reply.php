<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$msg = "";
 // get message id
$pid=$_SESSION['id'];// get patient id
$sid=$_GET['sid'];// get doctor id
$dname=$_GET['pname'];// get doctor name
$demail=$_GET['pemail'];// get doctor email
$dmessage=$_GET['pmessage'];// get doctor ,essage
$category=$_GET['category'];

if(isset($_POST['update'])){
	$reply="Reply from ".$_SESSION['username'].": ".$_POST['message'];
	mysqli_stmt_init($link);
	if($category=="doctor"){
		$query=mysqli_prepare($link,"insert into id17137158_pmsproject.doctormessages (docID,message,patientID) values (?,?,?)");
	}else{
		$query=mysqli_prepare($link,"insert into id17137158_pmsproject.adminmessages (adminID,message,patientID) values (?,?,?)");
	}
	mysqli_stmt_bind_param($query,"isi",$sid, $reply, $pid);
	if(mysqli_stmt_execute($query)){
		echo "<script>alert('Reply Sent Successfully.');</script>";
		echo "<script>window.location.href ='messages.php'</script>";
	}
}





?>

<!DOCTYPE html>
<html>
<head>
<title>Patient | Reply Message</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
	
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
<h3 id "welcome-header">Message Details</h3>
<div class="">
    <h5 style="color: green; font-size:18px; ">
        <?php if($msg) { echo htmlentities($msg);}?>
    </h5>

        <div class="table-responsive">
            <table class="table table-bordered  table-hover" id="sample-table-1">
				<tbody>

					<tr>
						<th>Full Name</th>
						<td><?php echo $dname;?></td>
					</tr>

					<tr>
						<th>Email</th>
						<td><?php echo $demail;?></td>
					</tr>
					<tr>
						<th>Message</th>
						<td><?php echo $dmessage;?></td>
					</tr>

						
					<form name="query" method="post">
						<tr>
							<th>Reply</th>
							<td><textarea name="message" class="form-control" placeholder="Type your reply here..." required></textarea></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<div class="text-right">
									<a style="color:white" href="messages.php">
										<button type="button" class="btn btn-success" name="cancel"> Cancel </button>
									</a>
									<button type="submit" class="btn btn-success" name="update">Send Message</button>
								</div>
							</td>
						</tr>
					</form>												
				</tbody>
            </table>
        </div>
   
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
</body>
</html>