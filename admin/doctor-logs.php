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



?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Doctor Logs</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
	
<script>
	function changeview(val) {
		$.ajax({
			type: "POST",
			url: "ponchange.php",
			data:'table='+val,
			success: function(data){
				$("#mytabledata").html(data);
			}
		});
	}
</script> 
	
</head>
	
	
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
	<div class="row">
		<div class="col-lg-6">
			<h3 id "welcome-header">Manage | Login Sessions</h3>
			<p>View User Login details</p>
		</div>

		<div class="col-lg-6">
			<h4>View</h4>
			<select name="msgtype" class="form-control"  onChange="changeview(this.value);">

				<option value="doctorslog">Doctor Logs</option>
				<option value="patientslog">Patient Logs </option>
				<option value="adminslog">Admin Logs</option>

			</select>

		</div>
	</div>
	
	
	
	<?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>	
	
	
	<div class="table-responsive">
		<table class="table table-bordered  table-hover" id="sample-table-1">
			<thead>
				<tr>
					<th>No</th>
					<th>User id</th>
					<th>Username</th>
					<th>User IP</th>
					<th>Login time</th>
					<th>Logout Time </th>
					<th>Status </th>
				</tr>
			</thead>
			<tbody id="mytabledata">
				<?php
				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select * from id17137158_pmsproject.doctorslog order by id desc" );
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				while ( $row = mysqli_fetch_array( $result ) ) {
					if($row['status']==1){
							$status = "Success";
							$color = "green";
						}else{
							$status = "Failed";
							$color="red";
						}
					?>
				<tr>
					<td><?php echo $cnt;?>.</td>
					<td><?php echo $row['uid'];?></td>
					<td><?php echo $row['username'];?></td>
					<td><?php echo $row['userip'];?></td>
					<td><?php echo date('d-m-Y h:ia', strtotime($row['loginTime']))?></td>
					<td><?php echo $row['logout']? date('d-m-Y h:ia', strtotime($row['logout'])):''?></td>
					<td style="color:<?php echo $color?>">
						<?php 
							echo $status;						
						?>
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