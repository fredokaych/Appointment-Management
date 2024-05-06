<?php
// Initialize the session
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}


$id = $_SESSION[ 'id' ];
if ( isset( $_POST[ 'submit' ] ) ) {
    $docspecialization = $_POST[ 'Doctorspecialization' ];
    $docname = $_POST[ 'docname' ];
    $docaddress = $_POST[ 'clinicaddress' ];
    $docfees = $_POST[ 'docfees' ];
    $doccontactno = $_POST[ 'doccontact' ];
    $docemail = $_POST[ 'docemail' ];
    $sql = mysqli_query( $link, "Update id17137158_pmsproject.doctors set specilization='$docspecialization', name='$docname', address='$docaddress', docFees=$docfees, contactno=$doccontactno where id=$id" );
    if ( $sql ) {
        echo "<script>alert('Details updated Successfully');</script>";
    } else {
        echo "<script>alert('Details error');</script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor | Edit Profile</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class="bg-light bgl container-fluid">
    <div class="card-body">
        <h3 id "welcome-header">My Profile</h3>
        <p>View your profile, edit where neccesary</p>
    </div>
    <?php
    $sql = mysqli_query( $link, "select * from id17137158_pmsproject.doctors where id='$id'" );
    $data = mysqli_fetch_array( $sql )

    ?>
    <form role="form" name="adddoc" method="post">
        <div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="DoctorSpecialization">Doctor Specialization</label>
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
                    <label for="doctorname">Doctor Name</label>
                    <input type="text" name="docname" class="form-control"  placeholder="Enter Doctor Name" value="<?php echo htmlentities($data['name']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Consultancy Fees</label>
                    <input type="text" name="docfees" class="form-control"  placeholder="Enter Doctor Consultancy Fees" value="<?php echo htmlentities($data['docFees']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Contact no</label>
                    <input type="text" name="doccontact" class="form-control"  placeholder="Enter Doctor Contact no" value="<?php echo htmlentities($data['contactno']);?>" required="true">
                </div>
                <div class="form-group col-md-6">
                    <label for="fess">Doctor Email</label>
                    <input type="email" id="docemail" name="docemail" class="form-control"  placeholder="Enter Doctor Email id"  value="<?php echo htmlentities($data['email']);?>" required="true" >
                    <span id="email-availability-status"></span></div>
                <div class="form-group col-md-6">
                    <label for="address">Doctor Clinic Address</label>
                    <textarea name="clinicaddress" class="form-control"  placeholder="Enter Doctor Clinic Address" required="true"><?php echo htmlentities($data['address']);?></textarea>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" name="submit" id="submit" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </form>
</div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
</body>
</html>