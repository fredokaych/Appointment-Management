<?php
// Initialize the session
session_start();
ob_start();
include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}






if(isset($_POST['submit'])){
	$adname = cleandata($_POST['adname']);
	$ademail = cleandata($_POST['ademail']);
	$adcontact = cleandata($_POST['adcontact']);
	
	
	$sql = mysqli_prepare($link, "update admins set email = ?, name = ?, contactno = ? where id = ".$_SESSION['id']);
	mysqli_stmt_bind_param($sql, 'ssi', $ademail, $adname, $adcontact);
	if(mysqli_stmt_execute($sql)){
		echo ('<script>alert("Success")</script>');
		header('Location: dashboard.php');
	}else{
		echo "<script>alert('Error: Try Again')</script>";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Profile</title>
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
        <h3 id "welcome-header">Admin Profile</h3>
        <p>Quick Details</p>
    </div>
    <?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>
    <?php
    $vid = $_SESSION[ 'id' ];
    $ret = mysqli_query( $link, "select * from id17137158_pmsproject.admins where id='$vid'" );
    $cnt = 1;
    while ( $row = mysqli_fetch_array( $ret ) ) {
        ?>
    <form role="form" name="adddoc" method="post">
        <div class="table-responsive">
            <table class="table table-bordered  table-hover" id="sample-table-1">
                <tr align="center">
                    <th colspan="4">Admin Details</th>
                </tr>
                <tr>
                    <th scope>Full Name</th>
                    <td><input type="text" name="adname" class="form-control"  value="<?php echo htmlentities($row["name"]);?>" required></td>
                </tr>
                <tr>
                    <th scope>Email Address</th>
                    <td><input type="text" name="ademail" class="form-control"  value="<?php echo htmlentities($row["email"]);?>" required></td>
                </tr>
                <tr>
                    <th scope>Mobile Number</th>
                    <td><input type="text" name="adcontact" class="form-control"  value="<?php echo htmlentities($row["contactno"]);?>" required></td>
                </tr>
                <?php }?>
            </table>
        </div>
        <div class="text-right">
			<a style="color:white" href="dashboard.php"><button type="button" class="btn btn-primary" name="update">Cancel</button></a>
			<button type="submit" name="submit" id="submit" class="btn btn-success">Save Changes</button>
		</div>
    </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>