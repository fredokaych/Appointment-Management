<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

date_default_timezone_set('Africa/Nairobi');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'id17137158_pmsfred');
define('DB_PASSWORD', '11aaAA..00124578');
define('DB_NAME', 'id17137158_pmsproject');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

function cleandata( $data ) {
	$data = trim( $data );
	$data = stripslashes( $data );
	$data = htmlspecialchars( $data );
	return $data;
}


function split_name($name){
	$name = trim($name);
	$last_name = (strpos($name, ' ')===false)?'' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
	$first_name = trim(preg_replace('#'.preg_quote($last_name,'#').'#','',$name));
	return array($first_name, $last_name);
}
function greet(){
	$string = "Howdy ";
	$time=date('H');
	$timezone = date('e');
	if($time<"12"){
		$string='Good Morning ';
	}else{
		if($time>="12"&&$time<"17"){
			$string='Good Afternoon ';
		}else{
			if($time>="17"&&$time<"19"){
				$string='Good Evening ';
			}else{
				if($time>="19"){
					$string='Good Night ';
				}
			}
		}
	}
	return($string);
}


// Check connection
if($link === false){
    die("ERROR: Could not connect." . mysqli_connect_error());
}



?>