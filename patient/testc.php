<?php
// Initialize the session
ob_start();
session_start();

include( '../config.php' );
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}

require( '../pay/configmp.php' );
require( '../pay/callback.php' );
$base = ( isset( $_SERVER[ "HTTPS" ] ) ? "https" : "http" ) . "://" . ( isset( $_SERVER[ "SERVER_NAME" ] ) ? $_SERVER[ "SERVER_NAME" ] : '' );
$defaults = array(
    "env" => "sandbox",
    "type" => 5,
    "shortcode" => "174379",
    "headoffice" => "174379",
    "key" => "nTHCyxwlMYQH6DTyAItUqQwXWtlRmKDs",
    "secret" => "6YbOIl0SeFH9zJsA",
    "username" => "apitest",
    "password" => "Safaricom992!",
    "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
    "validation_url" => $base . "/pay/validate.php",
    "confirmation_url" => $base . "/pay/confirm.php",
    "callback_url" => "https://bbitnew.000webhostapp.com/pay/callback.php",
    //"callback_url" => $base . "/pay/callback.php",
    "timeout_url" => $base . "/pay/timeout.php",
    "results_url" => $base . "/pay/results.php",
);

$configuration = new Config( $defaults );
$myObj = new TransactionCallbacks();

$trans = array( 'PHI8QTW67T', 'PHI8QTO75R', 'PHI8QTW34P', 'PHI8QTK88Y', 'PHI8QT034T', 'PHI8QTU23O' );
$trans2 = array_rand( $trans );

function funky( $callMeBack ) {
    if(is_array($callMeBack)){
        call_user_func($callMeBack);
    }elseif(is_callable($callMeBack)){
        $callMeBack();
    }

    $callbackData = $configuration->getDataFromCallback();
	print_r($callbackData);

}

funky( [ $myObj, "processSTKPushRequestCallback" ] );
?>

<!DOCTYPE html>
<html>
<head>
<title>Migori | Confirmation Payment</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
<link rel="stylesheet" href="../tools/date/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../tools/date/datepersonal.css">
</head>
<body id="body-pd">
<?php include('sidebar.php')?>
<div class=" bg-light container-fluid"><?php print_r($trans);?></div>
<script src="../tools/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../tools/sidebar.js"></script>
<script src="../tools/tableToCards.js"></script>
<script src="../tools/date/js/bootstrap-datepicker.min.js"></script>
<!--<script src="../tools/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>-->

</body>
</html>