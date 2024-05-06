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
$sid = $_SESSION[ 'id' ];


if ( isset( $_POST[ 'submit' ] ) ) {
    $remail = $_POST[ 'email' ];
    $category = $_POST[ 'category' ];
    $sql = mysqli_stmt_init( $link );
    $sql = mysqli_prepare( $link, "select id from id17137158_pmsproject." . $category . " where email = '" . $remail . "'" );
    mysqli_stmt_execute( $sql );
    $result = mysqli_stmt_get_result( $sql );
    $row = mysqli_fetch_array( $result );
    $rid = $row[ 'id' ];
    $message = "Message from " . $_SESSION[ 'username' ] . ": " . $_POST[ 'title' ] . ": " . $_POST[ 'message' ];
    mysqli_stmt_init( $link );
    if ( $category == "patients" ) {
        $query = mysqli_prepare( $link, "insert into id17137158_pmsproject.patientmessages (patientID,message,docID) values (?,?,?)" );
    } else {
        $query = mysqli_prepare( $link, "insert into id17137158_pmsproject.adminmessages (adminID,message,docID) values (?,?,?)" );
    }
    mysqli_stmt_bind_param( $query, "isi", $rid, $message, $sid );
    if ( mysqli_stmt_execute( $query ) ) {
        echo "<script>alert('Message Sent Successfully.');</script>";
        echo "<script>window.location.href ='messages.php'</script>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Patient | New Message</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
<script>
	function checkemailAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
			url: "check-availability.php",
			data:'emailid='+$("#email").val()+'&cat='+$("#category").val(),
			type: "POST",
			success:function(data){
				$("#email-availability-status2").html(data);
				$("#loaderIcon").hide();
			},
			error:function (){
				
			}
		});
	}
</script>
</head>

<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
<div class="card-body">
    <h3 id="welcome-header">Create New Message</h3>
</div>
<div class="">
    <h5 style="color: green; font-size:18px; ">
        <?php if($msg) { echo htmlentities($msg);}?>
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <tbody>
            <form name="query" method="post">
                <tr>
                    <th style="width: 30%">Select Category</th>
                    <td><select id="category" name="category" class="form-control" onChange="checkemailAvailability()">
                            <option value="patients">Patient</option>
                            <option value="admins">Admin</option>
                        </select></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><input type="email" id="email" name="email" class="form-control"  onBlur="checkemailAvailability()" placeholder="Enter Email" value=""></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php ?>
                        <span id="email-availability-status2"></span></td>
                </tr>
                <tr>
                    <th>Title</th>
                    <td><?php ?>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Title" value=""></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><textarea id="message" name="message" class="form-control" placeholder="Type your message here..." required></textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><div class="text-right"><a style="color:white" href="messages.php">
                            <button type="button" class="btn btn-success" name="cancel">Cancel</button>
                            </a>
                            <button type="submit" name="submit" id="submit" class="btn btn-success" name="send">
                            Send Message
                            </button>
                        </div></td>
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