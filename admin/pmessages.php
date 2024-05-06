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
	mysqli_query($link,"delete from id17137158_pmsproject.messages where id = '".$_GET['id']."'");
}
if(isset($_GET['con'])){
	mysqli_query($link,"update id17137158_pmsproject.messages set IsRead=0 where id = '".$_GET['id']."'");
}
if(isset($_GET['conu'])){
	mysqli_query($link,"update id17137158_pmsproject.messages set IsRead=1 where id = '".$_GET['id']."'");
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Unread Messages</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
	
	
	
<script>
	function changeview(val) {
		$.ajax({
			type: "POST",
			url: "ponchange.php",
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

	<div class="row">
		<div class="col-lg-6">
			<h3 id "welcome-header">Manage | Messages</h3>
			<p>List of All Messages</p>
		</div>
		
		<div class="col-lg-6">
			<h4>View</h4>
			<select name="msgtype" class="form-control"   onChange="changeview(this.value);">
				<option value="unread">Unread Messages</option>
				<option value="read">Read Messages</option>
				<option value="all">All Messages</option>
			</select>

		</div>
	</div>	
	
	
	<div class="table-responsive">
		<table class="table table-bordered  table-hover table-bordered" id="sample-table-1">
			<thead>
				<tr>
					<th>No</th>
					<th>Name</th>
					<th style="width:15%">Phone No. </th>
					<th style="width:40%">Message </th>
					<th>Action</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody id="mymessages">
				<?php
				$sql = mysqli_stmt_init($link);
				$sql = mysqli_prepare( $link, "select * from id17137158_pmsproject.messages where IsRead = 0 order by id desc" );
				mysqli_stmt_execute($sql);	
				$result = mysqli_stmt_get_result($sql);				
				$cnt = 1;
				if( ! mysqli_num_rows($result) ) {
					?>
					
					<tr>
						<td colspan="7">
							<div class="text-center">
								<img src="../images/no-data.png" style="text-align: center">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="7">
							<div class="text-center">
								<h5>No Messages Yet</h5>
							</div>
						</td>
					</tr>
					<?php
				}else{
					while ( $row = mysqli_fetch_array( $result ) ) {
						if($row['IsRead']==1){
							$status='Read';
							$color='Green';
							$bolden='';
						}else{
							$status='Unread';
							$color='Red';
							$bolden='Bold';
						}
					?>
					<tr style = "font-weight: <?php echo $bolden;?>">
						<td><?php echo $cnt;?>.</td>
						<td><?php echo $row['fullname'];?></td>
						<td><?php echo $row['contactno'];?></td>
						<td><?php echo $row['message'];?></td>
						<?php
						
						if($status=='Read'){
							?>
							<td>
								<div>
									<a href="pmsg-details.php?id=<?php echo $row['id']?>" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-comment-edit bx-tada-hover bx-sm"></i></a>
									
									<a href="pmessages.php?id=<?php echo $row['id']?>&con=confirm" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Unread"><i class="bx bx-envelope-open bx-tada-hover bx-sm"></i></a>
									
									<a href="pmessages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover  bx-sm"></i></a>
									
								</div>
							</td>
							<?php
						}else{
							?>
							<td>
								<div>
									<a href="pmsg-details.php?id=<?php echo $row['id']?>" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-comment-edit bx-tada-hover bx-sm"></i></a>
									
									<a href="pmessages.php?id=<?php echo $row['id']?>&conu=confirm" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Read"><i class="bx bxs-envelope bx-tada-hover bx-sm"></i></a>
									
									<a href="pmessages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover  bx-sm"></i></a>
								</div>
							</td>
							<?php
						}
						?>
						<td style="color:<?php echo $color;?>;">
							<?php 
							echo $status;
							?>
						</td>
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