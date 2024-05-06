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


$msg = "";


if ( isset( $_GET[ 'last_id' ] ) ) {
    $last_id = $_GET[ 'last_id' ];
} else {
    $last_id = "";
}
if ( isset( $_GET[ 'amt' ] ) ) {
    $amount = $_GET[ 'amt' ];
} else {
    $amount = "";
}
if ( isset( $_GET[ 'phn' ] ) ) {
    $phone = $_GET[ 'phn' ];
} else {
    $phone = "";
}

if ( isset( $_GET[ 'error' ] ) ) {
    $errmsg = $_GET[ 'error' ];
} else {
    $errmsg = "";
}








require( '../pay/configmp.php' );

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
    "callback_url" => $base . "/pay/reconcile.php",
    "timeout_url" => $base . "/pay/timeout.php",
    "results_url" => $base . "/pay/results.php",
);

$configuration = new Config( $defaults );




if ( isset( $_POST[ 'submit' ] ) ) {
	

    $phone = $_POST[ 'phone' ];
    $amount = 1;
    $reference = "ACCOUNT";
    $description = "Transaction Description";
    $remark = "Remark";
    $callback = null;

    $phone = ( substr( $phone, 0, 1 ) == "+" ) ? str_replace( "+", "", $phone ) : $phone;
    $phone = ( substr( $phone, 0, 1 ) == "0" ) ? preg_replace( "/^0/", "254", $phone ) : $phone;
    $phone = ( substr( $phone, 0, 1 ) == "7" ) ? "254{$phone}" : $phone;

    $timestamp = date( "YmdHis" );
    $password = base64_encode( $configuration->getConfig()->shortcode . $configuration->getConfig()->passkey . $timestamp );

    $endpoint = ( $configuration->getConfig()->env == "live" ) ?
        "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" :
        "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";

    $curl_post_data = array(
        "BusinessShortCode" => $configuration->getConfig()->headoffice,
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => ( $configuration->getConfig()->type == 4 ) ? "CustomerPayBillOnline" : "CustomerBuyGoodsOnline",
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => $configuration->getConfig()->shortcode,
        "PhoneNumber" => $phone,
        "CallBackURL" => $configuration->getConfig()->callback_url,
        "AccountReference" => $reference,
        "TransactionDesc" => $description,
        "Remark" => $remark,
    );

    $response = $configuration->remote_post( $endpoint, $curl_post_data );
    $result = json_decode( $response, true );


    if ($result[ 'ResponseCode' ] == 0 ) {
        $_SESSION[ 'MerchantRequestID' ] = $result[ 'MerchantRequestID' ];
        $_SESSION[ 'CheckoutRequestID' ] = $result[ 'CheckoutRequestID' ];
        $_SESSION[ 'Amount' ] = $amount;
		$errors = "---";
        header( "location: confirm-payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id);
    } elseif ( $result[ 'errorCode' ] && $result[ 'errorCode' ] == '500.001.1001' ) {
        $errors = "Error! A transaction is already in progress for the current phone number";
        header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
    } elseif ( $result[ 'errorCode' ] && $result[ 'errorCode' ] == '400.002.02' ) {
        $errors = "Error! Invalid Request";
        header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
    } else {
        $errors = "Error! Unable to make MPESA STK Push request. If the problem persists, please contact our site administrator!";
        header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
    }
}


?>

<!DOCTYPE html>
<html>
<head>
<title>MIGORI | Booking Payment</title>
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
    <h3 id "welcome-header">Pay For Appointment</h3>
    <p>Kindly confirm all fields then click Pay Now.</p>
    <p style="color:red;"><?php echo $_SESSION['msg'];?>
        <?php $_SESSION['msg']="";?>
    </p>
    <form role="form" name="adddoc" method="post">
        <div id = "mypaydets">
            <div class="row">
				<div class="form-group col-md-6">
                    <label for="AppointmentDate">Phone</label>
                    <input class="form-control" id="phone" name="phone" value="<?php echo $phone ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="AppointmentDate">Amount</label>
                    <input class="form-control" id="amount" name="amount" value="<?php echo $amount ?>" required readonly>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="AppointmentDate">Transaction ID</label>
                    <input class="form-control" id="last_id" name="last_id" value="<?php echo "2022AMS".$last_id ?>" required readonly>
                </div>
            </div>
            <div class="text-right"> <a style="color:white" href="appointments.php">
                <button type="button" class="btn btn-success" name="update">Back</button>
                </a>
                <button type="submit" name="submit" id="submit" class="btn btn-success">Pay Now</button>
            </div>
        </div>
    </form>
    <p style="color:red;"><?php echo $errmsg;?>
        <?php $errmsg="";?>
    </p>
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
		startDate: '-3d',
		endDate: '+30d',
		todayBtn: "linked",
		calendarWeeks: false,
		autoclose: true,
		todayHighlight: true
	});
	
</script>
</body>
</html>