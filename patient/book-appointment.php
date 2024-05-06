<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
  header( "location: ../index.php" );
  exit;
}
$_SESSION[ 'msg' ] = "";

if ( isset( $_POST[ 'submit' ] ) ) {
  $specilization = cleandata($_POST[ 'Doctorspecialization' ]);
  $doctorid = cleandata($_POST[ 'doctor' ]);
  $userid = cleandata($_SESSION[ 'id' ]);
  $fees = cleandata($_POST[ 'fees' ]);
  $appdate = cleandata($_POST[ 'datepicker' ]);
  $time = cleandata($_POST[ 'radio_a' ]);
  $userstatus = 1;
  $docstatus = 1;
  $gddate = date( 'Y-m-d', strtotime($appdate));
  $times = array( "8:30", "9:00", "9:30", "10:30", "11:00", "11:30", "12:00", "2:30", "3:00", "3:30" );
  $key = array_search( $time, $times ) + 1;


  $sql = mysqli_query( $link, "select * from id17137158_pmsproject.schedules where docID = '" . $doctorid . "' and date = '" . $gddate . "'" );
  if ( !mysqli_num_rows( $sql ) ) {
    $query = mysqli_query( $link, "insert into id17137158_pmsproject.schedules(docID,date,mo" . $key . ") values('$doctorid','$gddate','1')" );
    if ( $query ) {};

  } else {
    $sql = mysqli_query( $link, "Update id17137158_pmsproject.schedules set mo" . $key . " = 1 where docID = '$doctorid' and date = '$gddate'" );

    if ( $sql ) {};
  }


  $query = mysqli_query( $link, "insert into id17137158_pmsproject.appointment(doctorSpecialization,doctorId,userId,consultancyFees,appointmentDate,appointmentTime,userStatus,doctorStatus) values('$specilization','$doctorid','$userid','$fees','$gddate','$time','$userstatus','$docstatus')" );
  if ( $query ) {
    $last_id = mysqli_insert_id( $link );
	  

    $_SESSION['starttime'.$last_id] = time();

	  	  
    echo "<script>alert('Appointment Booked, Proceeding to payment');</script>";
  }

  if ( !empty( $_POST[ 'descriptionp' ] ) ) {
    $message = "[Appointment Message]: " . $_POST[ 'descriptionp' ];
    mysqli_query( $link, "insert into id17137158_pmsproject.doctormessages(docID,patientID,message) values('$doctorid','$userid','$message')" );
  }


  $ret = mysqli_query( $link, "select PatientContno from id17137158_pmsproject.patients where id = " . $userid );
  while ( $row = mysqli_fetch_array( $ret ) ) {
    echo '<script>	window.location.href = "payment.php?phn=' . $row[ 'PatientContno' ] . '&amt=' . $fees . '&last_id=' . $last_id . '"			</script>';
  };

}


?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | New Appointment</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="../tools/date/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../tools/date/datepersonal.css">
<!--<link rel="stylesheet" href="../tools/bootstrap-timepicker/bootstrap-timepicker.min.css">--> 
<script>
	function getdoctor(val) {
		$.ajax({
			type: "POST",
			url: "get-details.php",
			data:'specilizationid='+val,
			success: function(data){
				$("#doctor").html(data);
			}
		});
	}
</script> 
<script>
	function getfee(val, dat) {
		$.ajax({
			type: "POST",
			url: "get-details.php",
			data:'doctor='+val+'&date='+dat,
			success: function(data){
				$("#fees").html(data);
			}
		});
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();
		today = yyyy + '/' + mm + '/' + dd;
		gettimes(today, val)
	}
</script> 
<script>

	function gettimes(val, doc) {
		
		$.ajax({
			type: "POST",
			url: "get-details.php",
			data:'datepicker='+val+'&id='+doc,
			success: function(data){
				$("#myslots").html(data);
			}
		});
	}
</script>
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
  <div class="card-body">
    <h3 id "welcome-header">Create New Appointment</h3>
    <p>Fill all fields then proceed to payment</p>
  </div>
  <p style="color:red;"><?php echo $_SESSION['msg'];?>
    <?php $_SESSION['msg']="";?>
  </p>
	

  <form role="form" name="adddoc" method="post">
    <div>
      <div class="row">
        <div class="form-group col-md-4">
          <label for="DoctorSpecialization">Doctor Specialty</label>
          <select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required>
            <option value="">Select Specialty</option>
            <?php
            $ret = mysqli_query( $link, "select * from id17137158_pmsproject.doctorspecilization" );
            while ( $row = mysqli_fetch_array( $ret ) ) {
              ?>
            <option value="<?php echo htmlentities($row['specilization']);?>"><?php echo htmlentities($row['specilization']);?></option>
            <?php };?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="doctor">Doctors</label>
          <select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value, datepicker.value);" required>
            <option value="">Select Doctor</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="consultancyfees">Consultancy Fees</label>
          <select name="fees" class="form-control" id="fees"  readonly>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="AppointmentDate">Date</label>
          <input class="form-control datepicker" id="datepicker" name="datepicker" onChange="gettimes(datepicker.value, doctor.value);" value="<?php echo date('m/d/Y'); ?>" required>
        </div>
        <div class="form-group col-md-8">
          <label for="AppointmentTime">Select an available time slot</label>
          <div class="row" id="myslots">
            <!--<div class = "fill col border border-success">
                            <input id="radio_1" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_1" class="label column">8:30</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_2" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_2" class="label column">9:00</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_3" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_3" class="label column">9:30</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_4" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_4" class="label column">10:30</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_5" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_5" class="label column">11:00</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_6" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_6" class="label column">11:30</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_7" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_7" class="label column">12:00</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_8" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_8" class="label column">2:30</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_9" class="radio isHidden" name="radio_a" type="radio">
                            <label for="radio_9" class="label column">3:00</label>
                        </div>
                        <div class = "fill col border border-success">
                            <input id="radio_10" class="radio isHidden" name="radio_a" type="radio" disabled>
                            <label for="radio_10" class="label column">3:30</label>
                        </div>-->
          </div>
        </div>
        <div class="form-group col">
          <label for="descriptionp">Short Appointment Description (If Any)</label>
          <!--<input class="form-control" name="apptime" id="descriptionp" required>-->
          <textarea class="form-control" name="descriptionp" id="descriptionp"></textarea>
        </div>
      </div>
      <div class="text-right"><a style="color:white" href="appointments.php">
        <button type="button" class="btn btn-primary" name="update">Cancel</button>
        </a>
        <input type="reset" class="btn btn-secondary" value="Reset">
        <button type="submit" name="submit" id="submit" class="btn btn-success">Confirm & Proceed to Checkout</button>
      </div>
    </div>
  </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
<script src="../tools/date/js/bootstrap-datepicker.min.js"></script>
<!--<script src="../tools/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>-->

<script>
	$('.datepicker').datepicker({
		dateFormat: 'dd-mm-yyyy',
		startDate: '1d',
		endDate: '+30d',
		todayBtn: "linked",
		calendarWeeks: false,
		autoclose: true,
		todayHighlight: true
	});
	
</script>

</body>
</html>