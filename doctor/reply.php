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
$did=$_SESSION['id'];// get doctor id
$sid=$_GET['sid'];// get patient id
$pname=$_GET['pname'];// get patient name
$pemail=$_GET['pemail'];// get patient email
$pmessage=$_GET['pmessage'];
$category=$_GET['category'];// get patient message


if(isset($_POST['update'])){
	$reply="Reply from ".$_SESSION['username'].": ".$_POST['message'];
	mysqli_stmt_init($link);
	if($category=="admin"){
		$query=mysqli_prepare($link,"insert into id17137158_pmsproject.adminmessages (docID,message,adminID) values (?,?,?)");

	}else{
		$query=mysqli_prepare($link,"insert into id17137158_pmsproject.patientmessages (docID,message,patientID) values (?,?,?)");

	}
	
	
	mysqli_stmt_bind_param($query,"isi",$did, $reply, $sid);
	
	if(mysqli_stmt_execute($query)){
		echo "<script>alert('Reply Sent Successfully.');</script>";
		echo "<script>window.location.href ='messages.php'</script>";
	}
}





?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Reply Message</title>
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
						<td><?php echo $pname;?></td>
					</tr>

					<tr>
						<th>Email Id</th>
						<td><?php echo $pemail;?></td>
					</tr>
					<tr>
						<th>Message</th>
						<td><?php echo $pmessage;?></td>
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
										<button type="button" class="btn btn-success" name="update"> Cancel </button>
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