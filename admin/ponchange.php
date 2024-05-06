<?php
include( '../config.php' );
date_default_timezone_set('Africa/Nairobi');
session_start();

if ( !empty( $_POST[ "table" ] ) ){

	$sql = mysqli_stmt_init($link);
	$sql = mysqli_prepare( $link, "select * from id17137158_pmsproject.".$_POST['table']." order by id desc" );
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
					<h5>No Data Yet</h5>
				</div>
			</td>
		</tr>
		<?php
	}else{
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
			<td><?php echo date('d-m-Y h:ia', strtotime($row['loginTime']));?></td>
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
	}


	
}

if(!empty($_POST['msgtype'])){
	if($_POST['msgtype']=='unread'){
		$sqlstr = "select * from id17137158_pmsproject.messages where IsRead = 0 order by id desc";
	}elseif($_POST['msgtype']=='read'){
		$sqlstr = "select * from id17137158_pmsproject.messages where IsRead = 1 order by id desc";
	}else{
		$sqlstr = "select * from id17137158_pmsproject.messages order by id desc";
	}
	
	
	$sql = mysqli_stmt_init($link);
	$sql = mysqli_prepare( $link, $sqlstr);
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

						<a href="pmessages.php?id=<?php echo $row['id']?>&con=confirm" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Unread"><i class="bx bx-comment-x bx-tada-hover bx-sm"></i></a>

						<a href="pmessages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover  bx-sm"></i></a>

					</div>
				</td>
				<?php
			}else{
				?>
				<td>
					<div>
						<a href="pmsg-details.php?id=<?php echo $row['id']?>" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-comment-edit bx-tada-hover bx-sm"></i></a>

						<a href="pmessages.php?id=<?php echo $row['id']?>&conu=confirm" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Read"><i class="bx bx-comment-check bx-tada-hover bx-sm"></i></a>

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


}

?>
