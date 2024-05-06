<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


if ( isset( $_POST[ 'submit' ] ) ) {
    $userid = $_SESSION[ 'id' ];
    $appdate = $_POST[ 'datepicker' ];
    $gddate = date( 'Y-m-d', strtotime( $_POST[ "datepicker" ] ) );
    $times = array( "8:30", "9:00", "9:30", "10:30", "11:00", "11:30", "12:00", "2:30", "3:00", "3:30" );
    $vals = array( "0", "0", "0", "0", "0", "0", "0", "0", "0", "0" );


    $sql = mysqli_query( $link, "select * from id17137158_pmsproject.schedules where docID = '" . $userid . "' and date = '" . $gddate . "'" );


    if ( !mysqli_num_rows( $sql ) ) {
        $query = mysqli_query( $link, "insert into id17137158_pmsproject.schedules(docID,date) values('$userid','$gddate')" );
    } else {
        $row = mysqli_fetch_array( $sql );
        for ( $i = 1; $i <= 10; $i++ ) {
            $vals[ $i - 1 ] = $row[ 'mo' . $i ];
        }
    }


    if ( isset( $_POST[ 'check_a' ] ) ) {
        for ( $i = 1; $i <= 10; $i++ ) {
            if ( $vals[ $i - 1 ] != '1' ) {
                $vals[ $i - 1 ] = '2';
            }
        }
        foreach ( $_POST[ 'check_a' ] as $selected ) {
            $key = array_search( $selected, $times );
            $vals[ $key ] = "0";
        }
    } else {
        for ( $i = 1; $i <= 10; $i++ ) {
            if ( $vals[ $i - 1 ] != '1' ) {
                $vals[ $i - 1 ] = '2';
            }
        }
    }

    for ( $i = 1; $i <= 10; $i++ ) {
        if ( $vals[ $i - 1 ] == '2' ) {
            mysqli_query( $link, "update id17137158_pmsproject.schedules set mo" . $i . " = 2 where docID = '" . $userid . "' and date = '" . $gddate . "'" );
        } elseif ( $vals[ $i - 1 ] == '1' ) {
            mysqli_query( $link, "update id17137158_pmsproject.schedules set mo" . $i . " = 1 where docID = '" . $userid . "' and date = '" . $gddate . "'" );
        } else {
            mysqli_query( $link, "update id17137158_pmsproject.schedules set mo" . $i . " = 0 where docID = '" . $userid . "' and date = '" . $gddate . "'" );
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Schedule</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="../tools/date/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../tools/date/datepersonal.css">
<link rel="stylesheet" href="">
<script>
	function gettimes(val, doc) {
		$.ajax({
			type: "POST",
			url: "get-details.php",
			data:'datepicker='+val+'&id='+doc,
			success: function(data){
				$("#mytimes").html(data);
			}
		});
	}
</script>
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
    <div class="card-body">
        <h3 id "welcome-header">My Schedule</h3>
        <p>Turn on and off available time slots for booking appointments</p>
    </div>
    <?php /*?><p style="color:red;"><?php echo $_SESSION['msg'];?><?php $_SESSION['msg']="";?></p><?php */?>
    <div class="card-body">
        <form role="form" name="myform" method="post">
            <div class="row">
                <div class="form-group  col-md-4 ">
                    <label for="date">Date</label>
                    <input id="datepicker" name="datepicker" class="form-control datepicker"  data-provide=""  value="<?php echo date('m/d/Y'); ?>" onChange="gettimes(this.value, <?php echo htmlentities($_SESSION['id']) ?>);">
                </div>
                <div class="form-group col-md-8">
                    <label for="mytimes">Time Slots</label>
                    <div class="row" name="mytimes" id="mytimes" ></div>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" name="submit" id="submit" class="btn btn-success">Save Schedule</button>
            </div>
        </form>
    </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
<script src="../tools/date/js/bootstrap-datepicker.min.js"></script>
<script>
	$('.datepicker').datepicker({
		startDate: '-0d',
		endDate: '+30d',
		todayBtn: "linked",
		calendarWeeks: false,
		autoclose: true,
		todayHighlight: true
	});
	gettimes(datepicker.value, <?php echo htmlentities($_SESSION['id']) ?>);
	
	
//	$("form").submit(function () {
//
//		var this_master = $(this);
//
//		this_master.find('input[type="checkbox"]').each( function () {
//			var checkbox_this = $(this);
//
//
//			if( checkbox_this.is(":checked") == true ) {
//				checkbox_this.attr('value','1');
//			} else {
//				checkbox_this.prop('checked',true);
//				//DONT' ITS JUST CHECK THE CHECKBOX TO SUBMIT FORM DATA    
//				checkbox_this.attr('value','0');
//			}
//		})
//	})
	
</script>

</body>
</html>