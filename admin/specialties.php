<?php
// Initialize the session
session_start();
date_default_timezone_set('Africa/Nairobi');
include('../config.php');
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if(isset($_GET['del'])){
	mysqli_query($link,"delete from id17137158_pmsproject.doctorspecilization where ID = '".$_GET['id']."'");
	$_SESSION['msg']="Data deleted !!";
}
if(isset($_GET['edit'])){
	//mysqli_query($link,"delete from id17137158_pmsproject.doctorspecilization where ID = '".$_GET['id']."'");
	$_SESSION['msg']="Edited Successfully";
}

if( !empty($_POST['editspecialty'] )){
	
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Doctor Specialties</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" type="text/css" href="../tools/boxicons/css/boxicons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
	
	<div class="card-body">
		<h3 id "welcome-header">Manage | Specialties</h3>
    	<p>Edit, Add or Delete Specialties</p>
	</div>
    	
	<?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>	
	
	<div class="text-right">
		<a class="btn btn-primary btn-success" href="edit-specialty.php" role="button">Add New Specialty</a>
	</div>

	
	<div class="table-responsive">
		<table class="table table-bordered  table-hover" id="sample-table-1">
			<thead>
				<tr>
					<th class="center">No</th>
					<th>Specialty</th>
					<th>Created On </th>
					<th>Last Update </th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select * from id17137158_pmsproject.doctorspecilization order by id desc" );
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				while ( $row = mysqli_fetch_array( $result ) ) {
					?>
				<tr>
					<td class="center"><?php echo $cnt;?>.</td>
					<td class="hidden-xs"><?php echo $row['specilization'];?></td>
					<td><?php echo date('d-m-Y h:ia', strtotime($row['creationDate']));?></td>
					<td><?php echo date('d-m-Y h:ia', strtotime($row['updationDate']));?></td>
					<td >
						<div>
							
							<a href="edit-specialty.php?id=<?php echo $row['id'];?>" data-toggle='tooltip' class="btn btn-transparent btn-xs" data-placement="top" title="Edit"><i class="bx bx-pencil bx-sm bx-tada-hover"></i></a>
							<a href="specialties.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-sm bx-tada-hover"></i></a>
						</div>

					</td>
				</tr>
				<?php
				$cnt = $cnt + 1;
				}
				?>
			</tbody>
		</table>
		
	</div>
</div>
	
<div id="specialty-modal" class="modal fade bd-example-modal-lg">
	<div class="modal-dialog modal-md modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"> <span>x</span> </button>
			</div>
			<div class="modal-body ">
				<h5>Edit Specialty</h5>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class = "needs-validation">
					<div class="form-group">
						<input id="specialty" type="text" name="specialty" class="form-control" placeholder="" required>
					</div>

					<div class="form-group text-right">
						<input type="submit" class="btn btn-success" name="editspecialty" value="Send Message">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>