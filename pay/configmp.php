<?php

class Config {
    /* $config Configuration options */
    public static $config;

    /* Setup global configuration for classes */
    public function __construct( $configs ) {
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
			"callback_url" => "https://bbitnew.000webhostapp.com/pay/callback2.php",
			"timeout_url" => $base . "/pay/timeout.php",
			"results_url" => $base . "/pay/results.php",
        );

        if ( !empty( $configs ) && ( !isset( $configs[ "headoffice" ] ) || empty( $configs[ "headoffice" ] ) ) ) {
            $defaults[ "headoffice" ] = $configs[ "shortcode" ];
        }

        foreach ( $defaults as $key => $value ) {
            if ( isset( $configs[ $key ] ) ) {
                $defaults[ $key ] = $configs[ $key ];
            } else {
                $defaults[ $key ] = $value;
            }
        }

        self::$config = ( object )$configs;
    }

    public static function getConfig() {
        return self::$config;
    }

    /** Perform a GET request to the M-PESA Daraja API */
    public static function remote_get( $endpoint, $credentials = null ) {
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $endpoint );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Authorization: Basic " . $credentials ) );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

        return curl_exec( $curl );
		
		
		
		
		
		
		
    }

    /* Perform a POST request to the M-PESA Daraja API */
    public static function remote_post( $endpoint, $data = array() ) {
        $token = self::token();
        $curl = curl_init();
        $data_string = json_encode( $data );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_URL, $endpoint );
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type:application/json",
                "Authorization:Bearer " . $token,
            )
        );
        return curl_exec( $curl );
    }
	
	
	    /* Perform a query check for transaction at Daraja API */
    public static function process_query($endpoint, $data = array() ) {
        $token = self::token();
		
		$data_string = json_encode( $data );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Content-Type:application/json",
                "Authorization:Bearer " . $token,
            )
        );
        return curl_exec( $curl );
    }
	
	

    /* Fetch Token To Authenticate Requests */
    public static function token() {
        $endpoint = ( self::$config->env == "live" ) ?
            "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" :
            "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";

        $credentials = base64_encode( self::$config->key . ":" . self::$config->secret );
        $response = self::remote_get( $endpoint, $credentials );
        $result = json_decode( $response );

        return isset( $result->access_token ) ? $result->access_token : "";
    }

    /* Get Status of a Transaction */
    public static function status(
        $transaction,
        $command = "TransactionStatusQuery",
        $remarks = "Transaction Status Query",
        $occasion = "Transaction Status Query",
        $callback = null
    ) {
        $env = self::$config->env;
        $plaintext = self::$config->password;
        $publicKey = file_get_contents( __DIR__ . "/certs/{$env}/cert.cer" );

        openssl_public_encrypt( $plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING );
        $password = base64_encode( $encrypted );

        $endpoint = ( $env == "live" ) ?
            "https://api.safaricom.co.kelipwa/transactionstatus/v1/query" :
            "https://sandbox.safaricom.co.kelipwa/transactionstatus/v1/query";

        $curl_post_data = array(
            "Initiator" => self::$config->username,
            "SecurityCredential" => $password,
            "CommandID" => $command,
            "TransactionID" => $transaction,
            "PartyA" => self::$config->shortcode,
            "IdentifierType" => self::$config->type,
            "ResultURL" => self::$config->results_url,
            "QueueTimeOutURL" => self::$config->timeout_url,
            "Remarks" => $remarks,
            "Occasion" => $occasion,
        );
        $response = self::remote_post( $endpoint, $curl_post_data );
        $result = json_decode( $response, true );

        return is_null( $callback ) ?
            $result :
            \call_user_func_array( $callback, array( $result ) );
    }

    /* Reverse a Transaction */
    public static function reverse(
        $transaction,
        $amount,
        $receiver,
        $receiver_type = 3,
        $remarks = "Transaction Reversal",
        $occasion = "Transaction Reversal",
        $callback = null
    ) {
        $env = self::$config->env;
        $plaintext = self::$config->password;
        $publicKey = file_get_contents( __DIR__ . "/certs/{$env}/cert.cer" );

        openssl_public_encrypt( $plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING );
        $password = base64_encode( $encrypted );

        $endpoint = ( $env == "live" ) ?
            "https://api.safaricom.co.kelipwa/reversal/v1/request" :
            "https://sandbox.safaricom.co.kelipwa/reversal/v1/request";

        $curl_post_data = array(
            "CommandID" => "TransactionReversal",
            "Initiator" => self::$config->business,
            "SecurityCredential" => $password,
            "TransactionID" => $transaction,
            "Amount" => $amount,
            "ReceiverParty" => $receiver,
            "RecieverIdentifierType" => $receiver_type,
            "ResultURL" => self::$config->results_url,
            "QueueTimeOutURL" => self::$config->timeout_url,
            "Remarks" => $remarks,
            "Occasion" => $occasion,
        );

        $response = self::remote_post( $endpoint, $curl_post_data );
        $result = json_decode( $response, true );

        return is_null( $callback ) ?
            $result :
            \call_user_func_array( $callback, array( $result ) );
    }

    /* Check Account Balance */
    public static function balance(
        $command,
        $remarks = "Balance Query",
        $callback = null
    ) {
        $env = self::$config->env;
        $plaintext = self::$config->password;
        $publicKey = file_get_contents( __DIR__ . "/certs/{$env}/cert.cer" );

        openssl_public_encrypt( $plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING );
        $password = base64_encode( $encrypted );

        $endpoint = ( $env == "live" ) ?
            "https://api.safaricom.co.kelipwa/accountbalance/v1/query" :
            "https://sandbox.safaricom.co.kelipwa/accountbalance/v1/query";

        $curl_post_data = array(
            "CommandID" => $command,
            "Initiator" => self::$config->username,
            "SecurityCredential" => $password,
            "PartyA" => self::$config->shortcode,
            "IdentifierType" => self::$config->type,
            "Remarks" => $remarks,
            "QueueTimeOutURL" => self::$config->timeout_url,
            "ResultURL" => self::$config->results_url,
        );

        $response = self::remote_post( $endpoint, $curl_post_data );
        $result = json_decode( $response, true );

        return is_null( $callback ) ?
            $result :
            \call_user_func_array( $callback, array( $result ) );
    }

    /* Validate Transaction Data */
    public static function validate( $callback = null ) {
        $data = json_decode( file_get_contents( "php://input" ), true );

        if ( is_null( $callback ) ) {
            return array(
                "ResultCode" => 0,
                "ResultDesc" => "Success",
            );
        } else {
            return call_user_func_array( $callback, array( $data ) ) ?
                array(
                    "ResultCode" => 0,
                    "ResultDesc" => "Success",
                ) :
                array(
                    "ResultCode" => 1,
                    "ResultDesc" => "Failed",
                );
        }
    }

    /* Confirm Transaction Data */
    public static function confirm( $callback = null ) {
        $data = json_decode( file_get_contents( "php://input" ), true );

        if ( is_null( $callback ) ) {
            return array(
                "ResultCode" => 0,
                "ResultDesc" => "Success",
            );
        } else {
            return call_user_func_array( $callback, array( $data ) ) ?
                array(
                    "ResultCode" => 0,
                    "ResultDesc" => "Success",
                ) :
                array(
                    "ResultCode" => 1,
                    "ResultDesc" => "Failed",
                );
        }
    }

    /* Reconcile Transaction Using Instant Payment Notification from M-PESA */
    public static function reconcile( callable $callback = null ) {
        $response = json_decode( file_get_contents( "php://input" ), true );

        if ( is_null( $callback ) ) {
            return array(
                "ResultCode" => 0,
                "ResultDesc" => "Service request successful",
            );
        } else {
            return call_user_func_array( $callback, array( $response ) ) ?
                array(
                    "ResultCode" => 0,
                    "ResultDesc" => "Service request successful",
                ) :
                array(
                    "ResultCode" => 1,
                    "ResultDesc" => "Service request failed",
                );
        }
    }

    /* Process Results of an API Request */
    public static function results( callable $callback = null ) {
        $response = json_decode( file_get_contents( "php://input" ), true );

        if ( is_null( $callback ) ) {
            return array(
                "ResultCode" => 0,
                "ResultDesc" => "Service request successful",
            );
        } else {
            return call_user_func_array( $callback, array( $response ) ) ?
                array(
                    "ResultCode" => 0,
                    "ResultDesc" => "Service request successful",
                ) :
                array(
                    "ResultCode" => 1,
                    "ResultDesc" => "Service request failed",
                );
        }
    }

    /* Process Transaction Timeout */
    public static function timeout( callable $callback = null ) {
        $response = json_decode( file_get_contents( "php://input" ), true );

        if ( is_null( $callback ) ) {
            return array(
                "ResultCode" => 0,
                "ResultDesc" => "Service request successful",
            );
        } else {
            return call_user_func_array( $callback, array( $response ) ) ?
                array(
                    "ResultCode" => 0,
                    "ResultDesc" => "Service request successful",
                ) :
                array(
                    "ResultCode" => 1,
                    "ResultDesc" => "Service request failed",
                );
        }
    }
	
	public function getDataFromCallback(){
        $callbackJSONData=file_get_contents('php://input');
        return $callbackJSONData;
    }
	
	    /**
     *Use this function to confirm all transactions in callback routes
     */
    public function finishTransaction($status = true){
        if ($status === true) {
            $resultArray=[
                "ResultDesc"=>"Confirmation Service request accepted successfully",
                "ResultCode"=>"0"
            ];
        } else {
            $resultArray=[
                "ResultDesc"=>"Confirmation Service not accepted",
                "ResultCode"=>"1"
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($resultArray);
    }
	
	
}