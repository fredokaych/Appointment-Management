<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$msg = "";
$did = intval( $_GET[ 'id' ] ); // get doctor id
if ( isset( $_POST[ 'submit' ] ) ) {
    $docspecialization = cleandata( $_POST[ 'Doctorspecialization' ] );
    $docname = cleandata( $_POST[ 'docname' ] );
    $docaddress = cleandata( $_POST[ 'clinicaddress' ] );
    $docfees = cleandata( $_POST[ 'docfees' ] );
    $doccontactno = cleandata( $_POST[ 'doccontact' ] );
    $docemail = cleandata( $_POST[ 'docemail' ] );
    $npass = cleandata( $_POST[ 'npass' ] );


    if ( !empty( $npass ) ) {
        $npass_err = "";
        if ( strlen( trim( $npass ) ) < 6 ) {
            $npass_err = "Password must be at least 6 characters long";
        } else {
            $hashpass = password_hash( $npass, PASSWORD_DEFAULT );
            $sql = mysqli_query( $link, "Update id17137158_pmsproject.doctors set specilization='$docspecialization',name='$docname',address='$docaddress',docFees='$docfees',contactno='$doccontactno',email='$docemail',password='$hashpass' where id='$did'" );
            echo "<script>alert('Doctor Details and Password Update Successful');</script>";
            echo "<script>window.location.href ='doctors.php'</script>";
        }
    } else {
        $sql = mysqli_query( $link, "Update id17137158_pmsproject.doctors set specilization='$docspecialization',name='$docname',address='$docaddress',docFees='$docfees',contactno='$doccontactno',email='$docemail' where id='$did'" );
        echo "<script>alert('Doctor Details Updated Successfully');</script>";
        echo "<script>window.location.href ='doctors.php'</script>";
    }

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Edit Doctor</title>
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
    <h3 id "welcome-header">Editing Doctor</h3>
    <p>Edit all fields then save</p>
    <h5 style="color: green; font-size:18px; ">
        <?php if($msg) { echo htmlentities($msg);}?>
    </h5>
    <?php
    $sql = mysqli_query( $link, "select * from id17137158_pmsproject.doctors where id='$did'" );
    $data = mysqli_fetch_array( $sql )

    ?>
    <h4><?php echo htmlentities($data['name']);?>'s Profile</h4>
    <p><b>Date Registered:</b><?php echo date('d-m-Y h:ia', strtotime($data['creationDate']));?></p>
    <?php if($data['updationDate']){?>
    <p><b>Last Update:</b><?php echo date('d-m-Y h:ia', strtotime($data['updationDate']));?></p>
    <?php } ?>
    <form autocomplete="off" role="form" name="adddoc" method="post" onSubmit="return valid();">
        <div>
            <input autocomplete="false" name="hidden" type="text" style="display: none">
            <input autocomplete="false" name="hidden" type="password" style="display: none">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="DoctorSpecialization">Doctor Specialty</label>
                    <select name="Doctorspecialization" class="form-control" required="true">
                        <option value="<?php echo htmlentities($data['specilization']);?>"><?php echo htmlentities($data['specilization']);?></option>
                        <?php
                        $ret = mysqli_query( $link, "select * from id17137158_pmsproject.doctorspecilization" );
                        while ( $row = mysqli_fetch_array( $ret ) ) {
                            ?>
                        <option value="<?php echo htmlentities($row['specilization']);?>"><?php echo htmlentities($row['specilization']);?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="docfees">Doctor Consultancy Fees</label>
                    <input type="text" name="docfees" class="form-control"  placeholder="Enter Doctor Consultancy Fees" value="<?php echo htmlentities($data['docFees']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="docname">Doctor Name</label>
                    <input type="text" name="docname" class="form-control"  placeholder="Enter Doctor Name" value="<?php echo htmlentities($data['name']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="doccontact">Doctor Contact no</label>
                    <input type="text" name="doccontact" class="form-control"  placeholder="Enter Doctor Contact no" value="<?php echo htmlentities($data['contactno']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="docemail">Doctor Email</label>
                    <input type="email" id="docemail" name="docemail" class="form-control"  placeholder="Enter Doctor Email id"  value="<?php echo htmlentities($data['email']);?>" required="true" >
                    <span id="email-availability-status"></span></div>
                <div class="form-group col-md-6">
                    <label for="clinicaddress">Doctor Clinic Address</label>
                    <textarea name="clinicaddress" class="form-control"  placeholder="Enter Doctor Clinic Address" required="true"><?php echo htmlentities($data['address']);?></textarea>
                </div>
                <div class="form-group col-md-6">
                    <label for="npass">Update Password</label>
                    <input autocomplete="off" type="password" id="npass" name="npass" class="togglepass form-control <?php echo (!empty($npass_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter new password, otherwise leave blank"/>
                    <span class="invalid-feedback"><?php echo $npass_err; ?></span>
                    <input type="checkbox" onclick="togglepassvisible()">
                    Show Password</div>
            </div>
            <div class="text-right"><a style="color:white" href="doctors.php">
                <button type="button" class="btn btn-primary" name="update">Cancel</button>
                </a>
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