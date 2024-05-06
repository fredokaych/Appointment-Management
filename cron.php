<?php


include( 'config.php' );
require( 'other-functions.php' );
$myconfig = new myConfigs();

$sqlstr = "select * from id17137158_pmsproject.appointment where payStatus = 0";
$sql = mysqli_query( $link, $sqlstr);

while ( $row = mysqli_fetch_array( $sql ) ) {
    $posttime = new DateTime( date( 'Y-m-d H:i', strtotime( $row[ 'postingDate' ] ) ) );
    $curtime = new DateTime( date( 'Y-m-d H:i' ) );

    $interval = date_diff( $posttime, $curtime )->format( '%i' );
    if ( $interval >= 5 ) {
        $myconfig->removeschedule( $row[ 'id' ] );
		$sqlstr2 = "DELETE from id17137158_pmsproject.appointment where id = '" . $row[ 'id' ] . "'";
        $sql = mysqli_query( $link, $sqlstr2);
    }
	
	$filePath = "cronjobs.txt";
   
    
    $file = fopen( $filePath, "a" );
    //log incoming request
    fwrite( $file, $interval.'=>'.$sqlstr.'=>'.$sqlstr2 );
    fwrite( $file, "\r\n" );
    
    fclose( $file );

}


?>
