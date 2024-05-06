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
$mid = $_GET['id'];
$sid = $_GET['sid'];
$category =  $_GET[ 'category' ];


$sname="";// get patient name
$semail="";//// get patient email
$smessage="";//




?>

<!DOCTYPE html>
<html>
<head>
<title>Patient | Message Details</title>
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
                <?php
                
				mysqli_query($link,"update id17137158_pmsproject.patientmessages set IsRead=1 where id = ".$mid);
				if($category=="admin"){
					$sql = mysqli_query( $link, "select admins.id as sid, admins.name as fullname, admins.email as email, admins.contactno as contactno, patientmessages.* from id17137158_pmsproject.patientmessages join admins on admins.id = patientmessages.adminID and patientmessages.id='$mid'" );
				}else{
					$sql = mysqli_query( $link, "select doctors.id as sid, doctors.name as fullname, doctors.email as email, doctors.contactno as contactno, patientmessages.* from id17137158_pmsproject.patientmessages join doctors on doctors.id = patientmessages.docID and patientmessages.id='$mid'" );
				}
                $cnt = 1;
                while ( $row = mysqli_fetch_array( $sql ) ) {
					$sname=$row['fullname']; 
					$semail=$row['email'];
					$smessage=$row['message'];
                    ?>
					<tr>
						<th style="width:15%">Category</th>
						<td><?php echo ucfirst($category)." Message";?></td>
					</tr>
					<tr>
						<th>Sender Name</th>
						<td><?php echo $sname;?></td>
					</tr>
					<tr>
						<th>Sender Email</th>
						<td><?php echo $semail;?></td>
					</tr>
					<tr>
						<th>Phone. No</th>
						<td><?php echo $row['contactno'];?></td>
					</tr>
					<tr>
						<th>Message</th>
						<td><?php echo $smessage;?></td>
					</tr>

					<tr>
						<th>Date Sent</th>
						<td><?php echo date('d-m-Y h:ia', strtotime($row['date']));?></td>
					</tr>
					<?php
				}
				?>
            </tbody>
        </table>
		
		
		
        <div class="text-right">
			<a style="color:white" href="messages.php"><button type="button" class="btn btn-primary" name="update">Back to Messages</button></a>
			<a style="color:white" href="reply.php?pmessage=<?php echo $smessage?>&sid=<?php echo $sid?>&pemail=<?php echo $semail?>&pname=<?php echo $sname?>&category=<?php echo $category?>&rep=insert"><button type="button" class="btn btn-success" name="update">Reply this Messages</button></a>
        </div>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
</body>
</html>