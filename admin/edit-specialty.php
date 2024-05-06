<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$msg="";
if(isset($_GET['id'])){
	$did = intval($_GET['id']);
}else{
	$did = 0;
}


if(isset($_POST['submit'])){
	$specialty=$_POST['specialty'];
	if($did==0){
		$sql=mysqli_query($link, "Insert into id17137158_pmsproject.doctorspecilization set specilization = '$specialty' ");
		if($sql){
			$msg="Speciality Added Successfully";
			echo "<script>window.location.href ='specialties.php'</script>";
		}
	}else{
		$sql=mysqli_query($link, "Update id17137158_pmsproject.doctorspecilization set specilization = '$specialty' where id='$did'");
		if($sql){
			$msg="Speciality updated Successfully";
			echo "<script>window.location.href ='specialties.php'</script>";
		}
	}
	
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Edit Specialty</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">

</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid">
<h3 id "welcome-header">Adding & Editing  Specialty</h3>
<p>Edit or enter field then save</p>
<div class="">
	<h5 style="color: green; font-size:18px; "><?php if($msg) { echo htmlentities($msg);}?> </h5>
	
	
	<?php 
	//$did == 0 ? 
	
	$sql=mysqli_query($link,"select * from id17137158_pmsproject.doctorspecilization where id='$did'");
	$data=mysqli_fetch_array($sql)
	?>
	
    <form role="form" name="adddoc" method="post" onSubmit="return valid();">
        <div>
            <div class="row">
                
                <div class="form-group col-md-12">
                    <label for="specialty">Specialty</label>
                    <input type="text" name="specialty" id="specialty" class="form-control"  placeholder="Enter New Specialty" value="<?php echo $data==null? "":htmlentities($data['specilization']);?>" required>
                </div>
		  	</div>
            <div class="text-right">
				<a style="color:white" href="specialties.php"><button type="button" class="btn btn-primary" name="update">Cancel</button></a>
                <button type="submit" name="submit" id="submit" class="btn btn-success">Save & Close</button>
            </div>
			
		</div>
    </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
</body>
</html>