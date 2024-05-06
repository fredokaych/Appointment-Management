<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
  header( "location: ../index.php" );
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Medical History</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
  <div class="card-body">
    <h3 id "welcome-header">Medical Profile and Visit History</h3>
    <p>Details and  History</p>
  </div>
  <p style="color:red;"><?php echo $_SESSION['msg'];?>
    <?php $_SESSION['msg']="";?>
  </p>
  <?php
  $vid = $_SESSION[ 'id' ];
  $ret = mysqli_query( $link, "select * from id17137158_pmsproject.patients where ID='$vid'" );
  $cnt = 1;
  ?>
  <div class="text-right"><a class="btn btn-primary btn-success" href="book-appointment.php" role="button">Book Appointment</a></div>
  <div class="table-responsive">
    <?php

    $ret = mysqli_query( $link, "select * from id17137158_pmsproject.medhist  where PatientID='$vid' order by id desc" );


    ?>
    <table class="table table-bordered  table-hover" id="sample-table-2">
      <tr align="center">
        <th colspan="8" >Visit History</th>
      </tr>
      <tr>
        <th>No</th>
        <th>Blood Pressure</th>
        <th>Weight</th>
        <th>Blood Sugar</th>
        <th>Body Temprature</th>
        <th>Medical Prescription</th>
        <th>Visit Date</th>
      </tr>
      <?php
      while ( $row = mysqli_fetch_array( $ret ) ) {
        ?>
      <tr>
        <td><?php echo $cnt;?>.</td>
        <td><?php  echo $row['BloodPressure'];?></td>
        <td><?php  echo $row['Weight'];?></td>
        <td><?php  echo $row['BloodSugar'];?></td>
        <td><?php  echo $row['Temperature'];?></td>
        <td><?php  echo $row['MedicalPres'];?></td>
        <td><?php  echo date('d-m-Y h:ia', strtotime($row['CreationDate']));?></td>
      </tr>
      <?php $cnt=$cnt+1;} ?>
    </table>
  </div>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>