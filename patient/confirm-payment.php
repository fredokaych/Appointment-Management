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
if ( isset( $_GET[ 'ophone' ] ) ) {
    $ophone = $_GET[ 'ophone' ];
} else {
    $ophone = "";
}

if ( isset( $_GET[ 'error' ] ) ) {
    $errmsg = $_GET[ 'error' ];
} else {
    $errmsg = "";
}

if ( isset( $_GET[ 'docid' ] ) ) {
    $docid = $_GET[ 'docid' ];
} else {
    $docid = "";
}

function is_connected() {
    $connected = @fsockopen( "www.example.com", 80 ); //website, port  (try 80 or 443)
    if ( $connected ) {
        $is_conn = true; //action when connected
        fclose( $connected );
    } else {
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
}

require( '../pay/configmp.php' );
$base = ( isset( $_SERVER[ "HTTPS" ] ) ? "https" : "http" ) . "://" . ( isset( $_SERVER[ "SERVER_NAME" ] ) ? $_SERVER[ "SERVER_NAME" ] : '' );
$defaults = array(
    "env" => "sandbox",
    "type" => 4,
    "shortcode" => 174379,
    "headoffice" => 174379,
    "key" => "nTHCyxwlMYQH6DTyAItUqQwXWtlRmKDs",
    "secret" => "6YbOIl0SeFH9zJsA",
    "username" => "apitest",
    "password" => "Safaricom992!",
    "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
    "validation_url" => $base . "/pay/validate.php",
    "confirmation_url" => $base . "/pay/confirm.php",
    "callback_url" => "https://bbitnew.000webhostapp.com/pay/callback2.php",
    "timeout_url" => $base . "/pay/timeout.php",
    "results_url" => $base . "/pay/results.php",
);

$configuration = new Config( $defaults );

if ( isset( $_POST[ 'submit' ] ) ) {
    $endpoint2 = ( $configuration->getConfig()->env == "live" ) ?
        "https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query" :
        "https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query";
    $timestamp = date( "YmdHis" );
    $password = base64_encode( $configuration->getConfig()->shortcode . $configuration->getConfig()->passkey . $timestamp );
    $credentials = base64_encode( $configuration->getConfig()->key . $configuration->getConfig()->secret . $timestamp );
    $access_token = $configuration->token();
    $curl_post_data = array(
        'BusinessShortCode' => $configuration->getConfig()->headoffice,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'CheckoutRequestID' => $_SESSION[ 'CheckoutRequestID' ]
    );
    $data_string = json_encode( $curl_post_data );
    $curl = curl_init( $endpoint2 );
    curl_setopt( $curl, CURLOPT_POST, 1 );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt(
        $curl,
        CURLOPT_HTTPHEADER,
        array(
            "Content-Type:application/json",
            "Authorization:Bearer " . $access_token,
        )
    );
    $response = curl_exec( $curl );
    $result = json_decode( $response, true );


    if ( isset( $result[ 'errorCode' ] ) ) {
        if ( $result[ 'errorCode' ] == '400.002.02' ) {
            $errors = "Error: Invalid Phone Number. Please Enter Valid Safaricom Number";
            echo "<script>alert('Error: Invalid Phone Number. Please Enter Valid Safaricom Number');</script>";
            header( "Location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
        } elseif ( $result[ 'errorCode' ] == '500.001.1001' ) {
            echo "<script>alert('Transaction in Progress. Please Wait...');</script>";
        } else {
            $errors = "Error: " . $result[ 'errorMessage' ];
            echo "<script>alert('Error: " . $result[ 'errorMessage' ] . "');</script>";
            header( "Location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
        }
    } else {


        if ( $result[ 'ResultCode' ] == 0 ) {
            $success = "Success";
            header( "Location: payhist.php?success=" . $success );
        } elseif ( ( $result[ 'ResultCode' ] == 1032 ) || ( $result[ 'ResultCode' ] == 1031 ) ) {
            $errors = "Error: Transaction Cancelled by User";
            header( "Location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
            //exit();
        } elseif($result[ 'ResultCode' ] == 2001 ){
			$errors = "Error: Invalid Credentials. Ensure you input the correct M-PESA PIN";
            header( "Location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
		}else {
			$errors = "Error: " . $result[ 'ResultDesc' ];
            header( "Location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
            //exit();
        }
    }
}


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
    <h3 id "welcome-header">Confirm Payment</h3>
    <p>Kindly input MPESA Pin for the notification sent to your phone and press OK.</p>
    <p style="color:red;"><?php echo $_SESSION['msg'];?>
        <?php $_SESSION['msg']="";?>
    </p>
    <form role="form" name="adddoc" method="post">
        <div id = "mypaydets" class="">
            <div class="row">
                <div class="form-group col-12">
                    <label>Amount</label>
                    <input class="form-control" id="amount" name="amount" value="<?php echo $amount ?>" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Paying Phone</label>
                    <input class="form-control" id="phone" name="phone" value="<?php echo $phone ?>" required readonly>
                </div>
                <div class="form-group col-12">
                    <label>Transaction ID</label>
                    <input class="form-control" id="last_id" name="last_id" value="<?php echo "MIGORI_AMS".$last_id ?>" required readonly>
                </div>
            </div>
            <div class="text-center"><a style="color:white" href="payment.php?last_id=<?php echo $last_id?>&amt=<?php echo $amount?>&phn=<?php echo $phone?>&ophone=<?php echo $ophone?>&docid=<?php echo $docid?>">
                <button type="button" class="btn btn-secondary">Back</button>
                </a>
                <button type="submit" name="submit" id="submit" class="btn btn-success">Complete Payment</button>
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

</body>
</html>