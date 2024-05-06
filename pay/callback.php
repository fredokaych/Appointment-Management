<?php

//
//
//	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//    $callbackJSONData = file_get_contents( 'php://input' );
//    $callbackData = json_decode( $callbackJSONData );
//    $resultCode = $callbackData->Body->stkCallback->ResultCode;
//    $resultDesc = $callbackData->Body->stkCallback->ResultDesc;
//    $merchantRequestID = $callbackData->Body->stkCallback->MerchantRequestID;
//    $checkoutRequestID = $callbackData->Body->stkCallback->CheckoutRequestID;
//
//    $amount = $callbackData->stkCallback->Body->CallbackMetadata->Item[ 0 ]->Value;
//    $mpesaReceiptNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 1 ]->Value;
//    $balance = $callbackData->stkCallback->Body->CallbackMetadata->Item[ 2 ]->Value;
//    $b2CUtilityAccountAvailableFunds = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 3 ]->Value;
//    $transactionDate = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 4 ]->Value;
//    $phoneNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 5 ]->Value;
//
//    $result = [
//      "resultDesc" => $resultDesc,
//      "resultCode" => $resultCode,
//      "merchantRequestID" => $merchantRequestID,
//      "checkoutRequestID" => $checkoutRequestID,
//      "amount" => $amount,
//      "mpesaReceiptNumber" => $mpesaReceiptNumber,
//      "balance" => $balance,
//      "b2CUtilityAccountAvailableFunds" => $b2CUtilityAccountAvailableFunds,
//      "transactionDate" => $transactionDate,
//      "phoneNumber" => $phoneNumber
//    ];
//
//
//	$amount=$result['amount'];
//	$ophone="099999";
//	$phone=$result['phoneNumber'];
//	$refno='refno';
//	$trans=$result['mpesaReceiptNumber'];
//	$uid=11;
//	$docid=5;
//
//
//
//	$sql = mysqli_query( $link, "INSERT INTO payhist(amount, userNo, payNo, refNo, transactionNo, userId, docId) VALUES ('$amount','$ophone','$phone','$refno','$trans','$uid','$docid')" );


class TransactionCallbacks {

    public static function processSTKPushRequestCallback() {
        $callbackJSONData = file_get_contents( 'php://input' );
        $callbackData = json_decode( $callbackJSONData );
        $resultCode = $callbackData->Body->stkCallback->ResultCode;
        $resultDesc = $callbackData->Body->stkCallback->ResultDesc;
        $merchantRequestID = $callbackData->Body->stkCallback->MerchantRequestID;
        $checkoutRequestID = $callbackData->Body->stkCallback->CheckoutRequestID;

        $amount = $callbackData->stkCallback->Body->CallbackMetadata->Item[ 0 ]->Value;
        $mpesaReceiptNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 1 ]->Value;
        $balance = $callbackData->stkCallback->Body->CallbackMetadata->Item[ 2 ]->Value;
        $b2CUtilityAccountAvailableFunds = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 3 ]->Value;
        $transactionDate = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 4 ]->Value;
        $phoneNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 5 ]->Value;

        $result = [
            "resultDesc" => $resultDesc,
            "resultCode" => $resultCode,
            "merchantRequestID" => $merchantRequestID,
            "checkoutRequestID" => $checkoutRequestID,
            "amount" => $amount,
            "mpesaReceiptNumber" => $mpesaReceiptNumber,
            "balance" => $balance,
            "b2CUtilityAccountAvailableFunds" => $b2CUtilityAccountAvailableFunds,
            "transactionDate" => $transactionDate,
            "phoneNumber" => $phoneNumber
        ];

        return json_encode( $result );		
		
//			$amount=99;
//			$ophone="099999";
//			$phone='899';
//			$refno='refno';
//			$trans='99';
//			$uid=11;
//			$docid=5;
//		
//			$sql = mysqli_query( $link, "INSERT INTO payhist(amount, userNo, payNo, refNo, transactionNo, userId, docId) VALUES ('$amount','$ophone','$phone','$refno','$trans','$uid','$docid')" );

	
    }

    public static function processSTKPushQueryRequestCallback() {
        $callbackJSONData = file_get_contents( 'php://input' );
        $callbackData = json_decode( $callbackJSONData );
        $responseCode = $callbackData->ResponseCode;
        $responseDescription = $callbackData->ResponseDescription;
        $merchantRequestID = $callbackData->MerchantRequestID;
        $checkoutRequestID = $callbackData->CheckoutRequestID;
        $resultCode = $callbackData->ResultCode;
        $resultDesc = $callbackData->ResultDesc;

        $result = [
            "resultCode" => $resultCode,
            "responseDescription" => $responseDescription,
            "responseCode" => $responseCode,
            "merchantRequestID" => $merchantRequestID,
            "checkoutRequestID" => $checkoutRequestID,
            "resultDesc" => $resultDesc
        ];

        return json_encode( $result );
    }
	
	public static function testcallbackfunction() {
        $responseCode = "Myresponse code";
        $responseDescription = "Myresponse code";
        $merchantRequestID = "Myresponse code";
        $checkoutRequestID = "Myresponse code";
        $resultCode = "Myresponse code";
        $resultDesc = "Myresponse code";

        $result = [
            "resultCode" => $resultCode,
            "responseDescription" => $responseDescription,
            "responseCode" => $responseCode,
            "merchantRequestID" => $merchantRequestID,
            "checkoutRequestID" => $checkoutRequestID,
            "resultDesc" => $resultDesc
        ];
		//print_r($result);
        return json_encode( $result );
    }
	
	public function getDataFromCallback(){
        $callbackJSONData=file_get_contents('php://input');
        return $callbackJSONData;
    }

}


//function funky( $callMeBack ) {
//    if ( is_callable( $callMeBack ) ) {
//        $callMeBack();
//    }
//}
//funky( "TransactionCallbacks::processSTKPushRequestCallback" );



