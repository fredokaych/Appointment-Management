<?php
// Initialize the session
session_start();

include('../config.php');
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if(isset($_GET['del'])){
	mysqli_query($link,"delete from patients where ID = '".$_GET['id']."'");
	$_SESSION['msg']="Data deleted !!";
}


?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Manage Patients</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <h3 id "welcome-header">Manage | Patients</h3>
    <p>Edit, add or delete Patients</p>	
	<?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>	
	
	<div class="text-right">
		<a class="btn btn-primary btn-success" href="add-patient.php" role="button">Add Patient</a>
	</div>
	<div class="table-responsive">
		<table class="table table-bordered  table-hover" id="sample-table-1">
			<thead>
				<tr>
					<th class="center">No</th>
					<th>Patient Name</th>
					<th>Patient Contact</th>
					<th>Patient Gender </th>
					<th>Patient Email </th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select * from id17137158_pmsproject.patients order by id desc" );
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				while ( $row = mysqli_fetch_array( $result ) ) {
					?>
				<tr>
					<td class="center"><?php echo $cnt;?>.</td>
					<td class="hidden-xs"><?php echo $row['name'];?></td>
					<td><?php echo $row['PatientContno'];?></td>
					<td><?php echo $row['PatientGender'];?></td>
					<td><?php echo $row['email'];?></td>
					<td >
						<div>
							<a href="view-patients.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-show bx-sm bx-tada-hover"></i></a>
							<a href="edit-patient.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" data-togle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil bx-sm bx-tada-hover"></i></a>
							<a href="patients.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')"class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Remove"><i class="bx bx-trash bx-sm bx-tada-hover"></i></a>
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
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>