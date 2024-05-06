<?php
include( '../config.php' );


$docid = 0;
if ( !empty( $_POST[ "specilizationid" ] ) ) {

    $sql = mysqli_query( $link, "select name,id from id17137158_pmsproject.doctors where specilization='" . $_POST[ 'specilizationid' ] . "'" );
    ?>
<option selected="selected">Select Doctor </option>
<?php
while ( $row = mysqli_fetch_array( $sql ) ) {
    ?>
<option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['name']); ?></option>
<?php
}
}


if ( !empty( $_POST[ "doctor" ] ) ) {
    $docid = $_POST[ "doctor" ];

    $sql = mysqli_query( $link, "select docFees from id17137158_pmsproject.doctors where id='" . $docid . "'" );
    while ( $row = mysqli_fetch_array( $sql ) ) {
        ?>
<option value="<?php echo htmlentities($row['docFees']); ?>"><?php echo htmlentities($row['docFees']); ?></option>
<?php
}


}


if ( !empty( $_POST[ "datepicker" ] ) ) {
    $docid = $_POST[ "id" ];
    $sql = mysqli_query( $link, "select * from id17137158_pmsproject.schedules where docID = '" . $docid . "' and date = '" . date( 'Y-m-d', strtotime( $_POST[ "datepicker" ] ) ) . "'" );
    $times = array( "8:30", "9:00", "9:30", "10:30", "11:00", "11:30", "12:00", "2:30", "3:00", "3:30" );
    $times24 = array( "08:30", "09:00", "09:30", "10:30", "11:00", "11:30", "12:00", "14:30", "15:00", "15:30" );
	
	$curtime = date( 'd-m-Y H:i' );
	$appdate = date('d-m-Y', strtotime($_POST['datepicker']));
	$dtnow = new DateTime($curtime);
	

    if ( !mysqli_num_rows( $sql ) ) {
        for ( $i = 1; $i <= 10; $i++ ) {
			
			$strtime1 = date('H:i', strtotime($times24[$i-1]));
			$strtime = date('d-m-Y H:i', strtotime($appdate.' '.$strtime1));
			$dtapp = new DateTime($strtime);
			
			
			
			
            if ( $dtnow >= $dtapp ) {
                $status = "disabled";
            } else {
                $status = "enabled";
            }
            ?>
			<div class = "fill col border border-success" style="text-align: center">
				<input id="<?php echo htmlentities('radio_'.$i); ?>" class="radio isHidden" name="radio_a" type="radio" value = "<?php echo htmlentities($times[$i-1]); ?>"  <?php echo htmlentities($status); ?> required>
				<label for="<?php echo htmlentities('radio_'.$i); ?>" class="label column"><?php echo htmlentities($times[$i-1]); ?></label>
			</div>
			<?php
}
} else {
    while ( $row = mysqli_fetch_array( $sql ) ) {

        for ( $i = 1; $i <= 10; $i++ ) {
            if ( $row[ 'mo' . $i ] == 1 || $row[ 'mo' . $i ] == 2 ) {
                $status = "disabled";
            } else {
                $status = "enabled";
                
				$strtime1 = date('H:i', strtotime($times24[$i-1]));
				$strtime = date('d-m-Y H:i', strtotime($appdate.' '.$strtime1));
				$dtapp = new DateTime($strtime);

				if ( $dtnow >= $dtapp ) {
					$status = "disabled";
				} else {
					$status = "enabled";
				}
            }


            ?>
			<div class = "fill col border border-success" style="text-align: center">
				<input id="<?php echo htmlentities('radio_'.$i); ?>" class="radio isHidden" name="radio_a" type="radio" value = "<?php echo htmlentities($times[$i-1]); ?>" <?php echo htmlentities($status); ?> required>
				<label for="<?php echo htmlentities('radio_'.$i); ?>" class="label column"><?php echo htmlentities($times[$i-1]); ?></label>
			</div>
			<?php
}
}
}


}


?>
