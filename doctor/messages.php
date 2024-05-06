<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}

if ( isset( $_GET[ 'del' ] ) ) {
    mysqli_query( $link, "delete from id17137158_pmsproject.doctormessages where id = '" . $_GET[ 'id' ] . "'" );
}
if ( isset( $_GET[ 'con' ] ) ) {
    $isread = 1;
    mysqli_query( $link, "update id17137158_pmsproject.doctormessages set IsRead='$isread' where id = '" . $_GET[ 'id' ] . "'" );
}
if ( isset( $_GET[ 'unr' ] ) ) {
    $isread = 0;
    mysqli_query( $link, "update id17137158_pmsproject.doctormessages set IsRead='$isread' where id = '" . $_GET[ 'id' ] . "'" );
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Messages</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
<script>
	function changeview(val) {
		$.ajax({
			type: "POST",
			url: "onchange.php",
			data:'msgtype='+val,
			success: function(data){
				$("#mymessages").html(data);
			}
		});
	}
</script>
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <div class="row card-body">
        <div class="col-lg-6">
            <h3 id "welcome-header">Manage | Messages</h3>
            <p>List of All Messages</p>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col">
                    <label>View</label>
                    <select name="msgtype" class="form-control" onChange="changeview(this.value);">
                        <option value="unread">Unread Messages</option>
                        <option value="read">Read Messages</option>
                        <option value="all">All Messages</option>
                    </select>
                </div>
                <div class="col">
                    <label>.</label>
                    <div class="text-right"><a style="color:white" href="create-message.php">
                        <button type="button" class="btn btn-success" name="update">Create New Message</button>
                        </a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered  table-hover table-bordered" id="sample-table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th style="width:30%">Message</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="mymessages">
                <?php
                $sql = mysqli_stmt_init( $link );
                $sql = mysqli_prepare( $link, "select * from doctormessages where docID = " . $_SESSION[ 'id' ] . " and IsRead = 0 order by id desc" );

                mysqli_stmt_execute( $sql );
                $result = mysqli_stmt_get_result( $sql );
                $cnt = 1;
                if ( !mysqli_num_rows( $result ) ) {
                    ?>
                <tr>
                    <td colspan="7"><div class="text-center"><img src="../images/no-data.png" style="text-align: center"></div></td>
                </tr>
                <tr>
                    <td colspan="7"><div class="text-center">
                            <h5>No Messages Yet</h5>
                        </div></td>
                </tr>
                <?php
                } else {

                    while ( $row = mysqli_fetch_array( $result ) ) {
                        if ( $row[ 'adminID' ] != 0 ) {
                            $category = "admin";
                            $color = "blue";
                            $sql2 = mysqli_prepare( $link, "select admins.id as sid, admins.name as name, admins.email as email, admins.contactno as contact from admins where id = " . $row[ 'adminID' ] );
                            mysqli_stmt_execute( $sql2 );
                            $result2 = mysqli_stmt_get_result( $sql2 );
                            $row2 = mysqli_fetch_array( $result2 );


                        } else {
                            $category = "patient";
                            $color = "orange";
                            $sql2 = mysqli_prepare( $link, "select patients.id as sid, patients.name as name, patients.email as email, patients.patientcontno as contact from patients where id = " . $row[ 'patientID' ] );
                            mysqli_stmt_execute( $sql2 );
                            $result2 = mysqli_stmt_get_result( $sql2 );
                            $row2 = mysqli_fetch_array( $result2 );
                        }


                        ?>
                <tr>
                    <td><?php echo $cnt;?>.</td>
                    <td><?php echo date('d-m-Y h:ia', strtotime($row['date']));?></td>
                    <td><?php echo $row2['name'];?></td>
                    <td style="color: <?php echo $color?>"><?php echo ucfirst($category);?></td>
                    <td><?php echo $row['message'];?></td>
                    <?php
                    if ( $row[ 'IsRead' ] == 1 ) {
                        $status = 'Read';
                        $color = 'Green';
                    } else {
                        $status = 'Unread';
                        $color = 'Red';
                    }
                    ?>
                    <?php
                    if ( $status == 'Read' ) {
                        ?>
                    <td><div><a href="msg-details.php?id=<?php echo $row['id']?>&sid=<?php echo $row2['sid']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-book-open bx-tada-hover bx-sm"></i></a><a href="reply.php?pmessage=<?php echo $row['message']?>&sid=<?php echo $row2['sid']?>&pemail=<?php echo $row2['email']?>&pname=<?php echo $row2['name']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Reply"><i class="bx bx-reply bx-tada-hover bx-sm"></i></a><a href="messages.php?id=<?php echo $row['id']?>&unr=update" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Unread"><i class="bx bx-envelope-open bx-tada-hover bx-sm"></i></a><a href="messages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this message?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover bx-sm"></i></a></div></td>
                    <?php
                    } else {
                        ?>
                    <td><div><a href="msg-details.php?id=<?php echo $row['id']?>&sid=<?php echo $row2['sid']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-book-open bx-tada-hover bx-sm"></i></a><a href="reply.php?pmessage=<?php echo $row['message']?>&sid=<?php echo $row2['sid']?>&pemail=<?php echo $row2['email']?>&pname=<?php echo $row2['name']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Reply"><i class="bx bx-reply bx-tada-hover bx-sm"></i></a><a href="messages.php?id=<?php echo $row['id']?>&con=update" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Read"><i class="bx bxs-envelope bx-tada-hover bx-sm"></i></a><a href="messages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this message?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover bx-sm"></i></a></div></td>
                    <?php
                    }
                    ?>
                    <td style="color:<?php echo $color;?>;"><?php
                    echo $status;
                    ?></td>
                </tr>
                <?php
                $cnt = $cnt + 1;
                }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>