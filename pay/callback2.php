<?php

include( '../config.php' );

try {

    $callbackJSONData = file_get_contents( 'php://input' );
    $callbackData = json_decode( $callbackJSONData );

    $resultCode = $callbackData->Body->stkCallback->ResultCode;

    if ( $resultCode == 0 ) {
        $resultDesc = $callbackData->Body->stkCallback->ResultDesc;
        $merchantRequestID = $callbackData->Body->stkCallback->MerchantRequestID;
        $checkoutRequestID = $callbackData->Body->stkCallback->CheckoutRequestID;

        $amount = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 0 ]->Value;
        $mpesaReceiptNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 1 ]->Value;
        $balance = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 2 ]->Value;
        $transactionDate = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 3 ]->Value;
        $phoneNumber = $callbackData->Body->stkCallback->CallbackMetadata->Item[ 4 ]->Value;
        
		
		
		$gddate = date('Y-m-d H:i:s', strtotime($transactionDate));

        $sqlstr1 = "UPDATE id17137158_pmsproject.payhist SET amount = '$amount', payNo = '$phoneNumber', transactionNo = '$mpesaReceiptNumber', paydate = '$gddate', status = '1', MerchantRequestID = '$merchantRequestID', CheckoutRequestID = '$checkoutRequestID', ResultCode = '$resultCode', ResultDesc = '$resultDesc' WHERE CheckoutRequestID = '$checkoutRequestID'";
        $sql = mysqli_query( $link, $sqlstr1 );

        $sql = mysqli_query( $link, "select refNo, userId from id17137158_pmsproject.payhist where CheckoutRequestID = '$checkoutRequestID'" );
        $refNo = mysqli_fetch_array( $sql )[ 'refNo' ];
		$userId = mysqli_fetch_array($sql)['userId'];
        $num = ltrim( $refNo, 'MIGORI_AMS' );

		
		$sqlstr = "UPDATE id17137158_pmsproject.patients SET hacc = '0' WHERE id = '$userId'";
        $sql = mysqli_query( $link, $sqlstr );
		
        $sqlstr = "UPDATE id17137158_pmsproject.appointment SET payStatus = '1' WHERE id = '$num'";
        $sql = mysqli_query( $link, $sqlstr );
    }




    $filePath = "callbackmessages.txt";
    //error log
    $errorLog = "callbackerrors.txt";
    //Parse payload to json
    $jdata = json_decode( $callbackJSONData, true );
    //perform business operations on $jdata here
    //open text file for logging messages by appending
    $file = fopen( $filePath, "a" );
    //log incoming request
    fwrite( $file, $callbackJSONData );
    fwrite( $file, "\r\n" );
    //log response and close file
    fwrite( $file, $sqlstr1 );
    fclose( $file );
} catch ( Exception $ex ) {
    //append exception to errorLog
    $logErr = fopen( $errorLog, "a" );
    fwrite( $logErr, $ex->getMessage() );
    fwrite( $logErr, "\r\n" );
    fclose( $logErr );
}


?>