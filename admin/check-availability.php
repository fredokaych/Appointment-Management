<?php
require_once( "../config.php" );
if ( !empty( $_POST[ "emailid" ] ) ) {
    $email = $_POST[ "emailid" ];

    if($result = mysqli_prepare( $link, "SELECT email FROM doctors WHERE email=?" )){
		mysqli_stmt_bind_param( $result, "s", $email );
		if ( mysqli_stmt_execute( $result ) ){
			mysqli_stmt_store_result( $result );
			if ( mysqli_stmt_num_rows( $result ) > 0 ){
				echo "<span style='color:red'> Email already exists .</span>";
				echo "<script>$('#submit').prop('disabled',true);</script>";
			}else{
				echo "<span style='color:green'> Email available for Registration .</span>";
				echo "<script>$('#submit').prop('disabled',false);</script>";
			}
		}
	}else{
		echo "<script>$('#submit').prop('disabled',true);</script>";
	}
	
  
}




if ( !empty( $_POST[ "email" ] ) ) {
    $email = $_POST[ "email" ];
	$cat = $_POST["cat"];
    if($result = mysqli_prepare( $link, "SELECT email, name FROM id17137158_pmsproject." . $cat . " WHERE email=?" )){
		
		mysqli_stmt_bind_param( $result, "s", $email );
		if ( mysqli_stmt_execute( $result ) ){
			$myres = mysqli_stmt_get_result( $result );
			$row = mysqli_fetch_array( $myres );
			if ( mysqli_num_rows( $myres ) > 0 ){
				echo "<span style='color:green'>".$row['name']."</span>";
				echo "<script>$('#submit').prop('disabled',false);</script>";
				
			}else{
				echo "<span style='color:red'>Email doesnt exist in " . $cat . ". Kindly countercheck or try changing category</span>";
				echo "<script>$('#submit').prop('disabled',true);</script>";
			}
		}
	}else{
		echo "<script>$('#submit').prop('disabled',true);</script>";
	}
	
  
}


?>
