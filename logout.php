<?php
// Initialize the session
session_start();

include( 'config.php' );

if ( $_SESSION[ "persona" ] == 'patient' ) {
    require( 'other-functions.php' );
    $myconfig = new myConfigs();

    $sqlstr = "select id, postingDate from id17137158_pmsproject.appointment where payStatus = 0 and userId = '" . $_SESSION[ 'id' ] . "'";
    
    if ( $sql = mysqli_query( $link, $sqlstr ) ) {
		$numrows = mysqli_num_rows( $sql );
        $tbd = array();
        while ( $row = mysqli_fetch_array( $sql ) ) {
            $myconfig->removeschedule( $row[ 'id' ] );
            $tbd[] = $row[ 'id' ];
        }
        for ( $i = 0; $i < $numrows; $i++ ) {
            $sqlstr2 = "DELETE from id17137158_pmsproject.appointment where id = '" . $tbd[ $i ] . "'";
            $sql = mysqli_query( $link, $sqlstr2 );
        }
    }
}

$ldate = date( 'd-m-Y h:i:s A', time() );
mysqli_query( $link, "UPDATE " . $_SESSION[ 'persona' ] . "slog  SET logout = '$ldate' WHERE uid = '" . $_SESSION[ 'id' ] . "' ORDER BY id DESC LIMIT 1" );

$_SESSION = array();

// Destroy the session.
session_destroy();
// Redirect to login page
echo "<script>window.location.href ='index.php'</script>";
//exit();

?>
