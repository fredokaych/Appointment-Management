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

$hacc = 0;
if($msql = mysqli_query($link, "select hacc from id17137158_pmsproject.patients where id = " . $_SESSION['id'] )){
	$hacc = mysqli_fetch_array( $msql )['hacc'];
}

if ( isset( $_GET[ 'last_id' ] ) ) {
    $last_id = $_GET[ 'last_id' ];

    $ret = mysqli_query( $link, "select doctorId from id17137158_pmsproject.appointment where id = " . $last_id );
    $row = mysqli_fetch_array( $ret );
    $docid = $row[ 'doctorId' ];

} else {
    $last_id = "";
    $docid = "";
}
if ( isset( $_GET[ 'amt' ] ) ) {
    $amount = $_GET[ 'amt' ];
} else {
    $amount = "";
}
if ( isset( $_GET[ 'phn' ] ) ) {
    $phone = $_GET[ 'phn' ];
    $ophone = $_GET[ 'phn' ];
} else {
    $phone = "";
    $ophone = "";
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

if ( isset( $_POST[ 'submit' ] ) ) {
	$phone = cleandata($_POST[ 'phone' ]);
	$amount = $_POST[ 'amount' ];
	$reference = $_POST[ 'last_id' ];

	if($hacc>$amount){
		
		
		$mrid = 'Local_Payment';
		$crid = 'From_Holding_Account';
		
		
		$errors = "";
		$trans = "HOLDING_".$last_id;
		$uid = $_SESSION[ 'id' ];
		
		$sql = mysqli_prepare( $link, "select id from payhist where refNo = '" . $reference . "'" );
		mysqli_stmt_execute( $sql );
		$result = mysqli_stmt_get_result( $sql );
		if ( !mysqli_num_rows( $result ) ) {
			$sql = mysqli_query( $link, "INSERT INTO payhist(amount, userNo, payNo, status, refNo, transactionNo, userId, docId, MerchantRequestID, CheckoutRequestID) VALUES ('$amount','$ophone','$phone','1','$reference','$trans','$uid','$docid','$mrid','$crid')" );
		} else {
			$sql = mysqli_query( $link, "UPDATE payhist SET amount='$amount', userNo='$ophone', status='1', payNo='$phone', refNo='$reference', transactionNo='$trans', userId='$uid', docId='$docid', MerchantRequestID='$mrid', CheckoutRequestID='$crid' WHERE refNo = '$reference'" );
		}
		
		$newbal = $hacc-$amount;
		$sqlstr = "UPDATE id17137158_pmsproject.patients SET hacc = '$newbal' WHERE id = '$uid'";
        $sql = mysqli_query( $link, $sqlstr );
		
        $sqlstr = "UPDATE id17137158_pmsproject.appointment SET payStatus = '1' WHERE id = '$last_id'";
        $sql = mysqli_query( $link, $sqlstr );
		
		
		$success = "Successfull Payment From Holding Account";
		header( "Location: payhist.php?success=" . $success );
		
		
		
		exit();

		
	}else{
		$amount = $amount - $hacc;
		if ( $_SERVER[ 'REMOTE_ADDR' ] == '127.0.0.1' ) {
			echo "<script>alert('Error: You cannot pay on a local server');</script>";
		} else {

			$description = "Payment for appointment booking ref no. " . $reference;
			$remark = "Remarks: Initiated";
			$callback = null;

			$phone = ( substr( $phone, 0, 1 ) == "+" ) ? str_replace( "+", "", $phone ) : $phone;
			$phone = ( substr( $phone, 0, 1 ) == "0" ) ? preg_replace( "/^0/", "254", $phone ) : $phone;
			$phone = ( substr( $phone, 0, 1 ) == "7" ) ? "254{$phone}" : $phone;

			$ophone = ( substr( $ophone, 0, 1 ) == "+" ) ? str_replace( "+", "", $ophone ) : $ophone;
			$ophone = ( substr( $ophone, 0, 1 ) == "0" ) ? preg_replace( "/^0/", "254", $ophone ) : $ophone;
			$ophone = ( substr( $ophone, 0, 1 ) == "7" ) ? "254{$ophone}" : $ophone;

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


				if ( $result[ 'ResponseCode' ] == 0 ) {
					$mrid = $result[ 'MerchantRequestID' ];
					$crid = $result[ 'CheckoutRequestID' ];
					$_SESSION[ 'CheckoutRequestID' ] = $crid;
					$errors = "";
					$trans = "New";
					$uid = $_SESSION[ 'id' ];

					$sql = mysqli_prepare( $link, "select id from payhist where refNo = '" . $reference . "'" );
					mysqli_stmt_execute( $sql );
					$result = mysqli_stmt_get_result( $sql );
					if ( !mysqli_num_rows( $result ) ) {
						$sql = mysqli_query( $link, "INSERT INTO payhist(amount, userNo, payNo, refNo, transactionNo, userId, docId, MerchantRequestID, CheckoutRequestID) VALUES ('$amount','$ophone','$phone','$reference','$trans','$uid','$docid','$mrid','$crid')" );
					} else {
						$sql = mysqli_query( $link, "UPDATE payhist SET amount='$amount', userNo='$ophone', payNo='$phone', refNo='$reference', transactionNo='$trans', userId='$uid', docId='$docid', MerchantRequestID='$mrid', CheckoutRequestID='$crid' WHERE refNo = '$reference'" );
					}

					header( "location: confirm-payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id . "&docid=" . $docid . "&ophone=" . $ophone );
					exit();


				} elseif ( $result[ 'errorCode' ] && $result[ 'errorCode' ] == '500.001.1001' ) {
					$errors = "Error! A transaction is already in progress for the current phone number";
					header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
				} elseif ( $result[ 'errorCode' ] && $result[ 'errorCode' ] == '400.002.02' ) {
					$errors = "Error! Invalid Phone Number. Please Enter Valid Safaricom Number";
					header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
				} else {
					$errors = "Error! Unable to make MPESA STK Push request. If the problem persists, please contact our site administrator!";
					header( "location: payment.php?error=" . $errors . "&amt=" . $amount . "&phn=" . $phone . "&last_id=" . $last_id );
				}
			}
		}		

		
		
		
		
		
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
    <div class="card-body">
        <h3 id "welcome-header">Pay For Appointment</h3>
        <p>Kindly confirm all fields then click Pay Now.</p>
    </div>
    <p style="color:red;"><?php echo $_SESSION['msg'];?>
        <?php $_SESSION['msg']="";?>
    </p>
    <form role="form" name="adddoc" method="post">
        <div id = "mypaydets">
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Paying Phone</label>
                    <input class="form-control" id="phone" name="phone" value="<?php echo $phone ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Amount</label>
                    <input class="form-control" id="amount" name="amount" value="<?php echo $amount ?>" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label>Reference No.</label>
                    <input class="form-control" id="last_id" name="last_id" value="<?php echo "MIGORI_AMS".$last_id ?>" required readonly>
                </div>
                <div class="form-group col-md-6">
                    <label>Users Phone</label>
                    <input class="form-control" id="userphone" name="userphone" value="<?php echo $ophone ?>" required readonly>
                </div>
            </div>
            <div class="text-right"><a style="color:white" href="appointments.php">
                <button type="button" class="btn btn-secondary" name="update">Back</button>
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