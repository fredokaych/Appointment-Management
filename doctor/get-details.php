<?php
include( '../config.php' );


$docid = 0;

if ( !empty( $_POST[ "datepicker" ] ) ) {
	$docid = $_POST[ "id" ];
    $sql = mysqli_query( $link, "select * from id17137158_pmsproject.schedules where docID = '".$docid."' and date = '".date('Y-m-d', strtotime($_POST[ "datepicker" ]))."'" );
	$times = array("8:30", "9:00", "9:30", "10:30", "11:00", "11:30", "12:00", "2:30", "3:00", "3:30");
	
	
	
	if(!mysqli_num_rows( $sql )){
		for ($i=1; $i <= 10; $i++ ){
			?>
				<div class = "fill col border border-success" style="text-align: center">
					<label for="<?php echo htmlentities('check_'.$i); ?>" class="label"><?php echo htmlentities($times[$i-1]); ?></label>
					<input id="<?php echo htmlentities('check_'.$i); ?>" class="form-check-input myclass" name="check_a[]" type="checkbox" value = "<?php echo htmlentities($times[$i-1]); ?>" checked>						
				</div>
			<?php
		}
	}else{
		while ($row = mysqli_fetch_array($sql)){
			for ($i=1; $i <= 10; $i++ ){ 
				if($row['mo'.$i] == 1){
					$status="checked";
					$abled="disabled";
				}elseif($row['mo'.$i] == 2){
					$status="";
					$abled="";
				}else{
					$status="checked";
					$abled="";
				}
				?>

					<div class = "fill col border border-success" style="text-align: center">
						
						<label for="<?php echo htmlentities('check_'.$i); ?>" class="label"><?php echo htmlentities($times[$i-1]); ?></label>
						
						<input id="<?php echo htmlentities('check_'.$i); ?>" class="form-check-input myclass" name="check_a[]" type="checkbox" value = "<?php echo htmlentities($times[$i-1]); ?>" <?php echo htmlentities($status); ?> <?php echo htmlentities($abled); ?> >						
					</div>

					<?php /*?><div class = "fill col border border-success form-check" style="text-align: center">
						<input id="<?php echo htmlentities('radio_'.$i); ?>" class="radio isHidden" name="radio_a" type="radio" value = "<?php echo htmlentities($times[$i-1]); ?>" <?php echo htmlentities($status); ?> required>
						<label for="<?php echo htmlentities('radio_'.$i); ?>" class="label column"><?php echo htmlentities($times[$i-1]); ?></label>
					</div><?php */?>
				<?php	
			} 
		}
	}
	

}


?>
