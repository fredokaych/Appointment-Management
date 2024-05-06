<?php
class myConfigs {
    public function removeschedule( $id ) {
        $link = mysqli_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME );

        $ret = mysqli_query( $link, "select appointmentTime, appointmentDate, doctorId from id17137158_pmsproject.appointment where id = " . $id );
        if ( mysqli_num_rows( $ret ) > 0 ) {
            $row = mysqli_fetch_array( $ret );
            $time = $row[ 'appointmentTime' ];
            $doctorid = $row[ 'doctorId' ];
            $gddate = $row[ 'appointmentDate' ];

            $times = array( "8:30", "9:00", "9:30", "10:30", "11:00", "11:30", "12:00", "2:30", "3:00", "3:30" );
            $key = array_search( $time, $times ) + 1;

            $sql = mysqli_query( $link, "Update id17137158_pmsproject.schedules set mo" . $key . " = 0 where docID = '$doctorid' and date = '$gddate'" );
        }

    }
}
?>