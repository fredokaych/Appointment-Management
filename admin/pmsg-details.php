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


if ( isset( $_POST[ 'update' ] ) ) {
    $qid = intval( $_GET[ 'id' ] );
    $adminremark = $_POST[ 'adminremark' ];
    $isread = 1;
    mysqli_stmt_init( $link );
    $query = mysqli_prepare( $link, "update id17137158_pmsproject.messages set  AdminRemark=?, IsRead=? where id=?" );
    mysqli_stmt_bind_param( $query, "sii", $adminremark, $isread, $qid );

    if ( mysqli_stmt_execute( $query ) ) {
        echo "<script>alert('Remark Successful.');</script>";
        echo "<script>window.location.href ='messages.php'</script>";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Message Details</title>
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
<p>Status</p>
<div class="">
    <h5 style="color: green; font-size:18px; ">
        <?php if($msg) { echo htmlentities($msg);}?>
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover" id="sample-table-1">
            <tbody>
                <?php
                $qid = intval( $_GET[ 'id' ] );
				mysqli_query($link,"update id17137158_pmsproject.messages set IsRead = 1 where id = ".$qid);
				
                $sql = mysqli_query( $link, "select * from id17137158_pmsproject.messages where id = '$qid'" );
                $cnt = 1;
                while ( $row = mysqli_fetch_array( $sql ) ) {
                    ?>
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $row['fullname'];?></td>
                </tr>
                <tr>
                    <th>Email Id</th>
                    <td><?php echo $row['email'];?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo $row['contactno'];?></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?php echo $row['message'];?></td>
                </tr>
                <?php if($row['AdminRemark']==""){?>
				<form name="query" method="post">
					<tr>
						<th>Admin Remark</th>
						<td><textarea name="adminremark" class="form-control" required="true"></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><div class="text-right">
								<button type="submit" class="btn btn-success" name="update">Update Remark</button>
							</div></td>
					</tr>
				</form>
            <?php } else {?>
					<tr>
						<th>Admin Remark</th>
						<td><?php echo $row['AdminRemark'];?></td>
					</tr>
					<tr>
						<th>Last Updation Date</th>
						<td><?php echo date('d-m-Y h:ia', strtotime($row['LastupdationDate']));?></td>
					</tr>
            <?php
            }
            }
            ?>
            </tbody>
        </table>
        <div class="text-right"><a style="color:white" href="pmessages.php">
            <button type="button" class="btn btn-success" name="update">Back to Messages</button>
            </a>
        </div>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
</body>
</html>