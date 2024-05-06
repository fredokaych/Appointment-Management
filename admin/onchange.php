<?php
include( '../config.php' );
date_default_timezone_set('Africa/Nairobi');
session_start();



if(!empty($_POST['msgtype'])){

	$id = $_SESSION['id'];
	if($_POST['msgtype']=='unread'){
		$sqlstr = "select * from id17137158_pmsproject.adminmessages where IsRead = 0 and adminID = ".$id." order by id desc";
	}elseif($_POST['msgtype']=='read'){
		$sqlstr = "select * from id17137158_pmsproject.adminmessages where IsRead = 1 and adminID = ".$id." order by id desc";
	}else{
		$sqlstr = "select * from id17137158_pmsproject.adminmessages where adminID = ".$id." order by id desc";
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
			if($row['docID']!=0){
				$category = "doctor";
				$color = "blue";
				$sql2 = mysqli_prepare( $link, "select doctors.id as sid, doctors.name as name, doctors.email as email, doctors.contactno as contact from doctors where id = ".$row['docID'] );
				mysqli_stmt_execute($sql2);	
				$result2 = mysqli_stmt_get_result($sql2);
				$row2 = mysqli_fetch_array( $result2 );


			}else{
				$category="patient";
				$color = "orange";
				$sql2 = mysqli_prepare( $link, "select patients.id as sid, patients.name as name, patients.email as email, patients.patientcontno as contact from patients where id = ".$row['patientID'] );
				mysqli_stmt_execute($sql2);	
				$result2 = mysqli_stmt_get_result($sql2);
				$row2 = mysqli_fetch_array( $result2 );
			}

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
				<td><?php echo date('d-m-Y h:ia', strtotime($row['date']));?></td>
				<td><?php echo $row2['name'];?></td>
				<td style="color: <?php echo $color?>"><?php echo ucfirst($category);?></td>
				<td><?php echo $row['message'];?></td>

				<?php
		
				?>

				<?php
				if($status=='Read'){
					?>
					<td>
						<div>
							<a href="msg-details.php?id=<?php echo $row['id']?>&sid=<?php echo $row2['sid']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-book-open bx-tada-hover bx-sm"></i></a>

							<a href="reply.php?pmessage=<?php echo $row['message']?>&sid=<?php echo $row2['sid']?>&pemail=<?php echo $row2['email']?>&pname=<?php echo $row2['name']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Reply"><i class="bx bx-reply bx-tada-hover bx-sm"></i></a>
							<a href="messages.php?id=<?php echo $row['id']?>&unr=update" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Unread"><i class="bx bx-envelope-open bx-tada-hover bx-sm"></i></a>
							<a href="messages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this message?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover bx-sm"></i></a>

						</div>
					</td>
					<?php
				}else{
					?>
					<td>
						<div>
							<a href="msg-details.php?id=<?php echo $row['id']?>&sid=<?php echo $row2['sid']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="View"><i class="bx bx-book-open bx-tada-hover bx-sm"></i></a>

							<a href="reply.php?pmessage=<?php echo $row['message']?>&sid=<?php echo $row2['sid']?>&pemail=<?php echo $row2['email']?>&pname=<?php echo $row2['name']?>&category=<?php echo $category?>&rep=insert" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Reply"><i class="bx bx-reply bx-tada-hover bx-sm"></i></a>
							<a href="messages.php?id=<?php echo $row['id']?>&con=update" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Mark As Read"><i class="bx bxs-envelope bx-tada-hover bx-sm"></i></a>
							<a href="messages.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete this message?')" class="btn btn-transparent" data-togle="tooltip" data-placement="top" title="Delete"><i class="bx bx-trash bx-tada-hover bx-sm"></i></a>
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
