<?php
// Initialize the session
//session_start();
// Check if the user is logged in, if not then redirect him to login page
if ( !isset( $_SESSION[ "loggedin" ] ) || $_SESSION[ "loggedin" ] !== true ) {
    header( "location: ../index.php" );
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Migori|Dashboard</title>	
	<link rel="stylesheet" type="text/css" href="../tools/boxicons/css/boxicons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
	<link rel="stylesheet" type="text/css" href="../tools/mytweaks.css">
	<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">

</head>
	
<header id="header" class="page-header header container-fluid">
    <div class="header_toggle" > <i class='bx bx-menu  bx-sm' id="header-toggle"></i> </div>
    <div class="dropdown">
		<button onclick="myFunction()" class="dropbtn">MENU</button>
		<div id="myDropdown" class="dropdown-content">
			<a href="profile.php">My Profile</a>
			<a href="change-password.php">Change Password</a>
			<a href="../logout.php">Sign Out</a>
		</div>
	</div>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav"> 
			<!--<a> </a>-->
            <div> 
				
                <div class="nav_list"> 
					<a  class="nav_link">
						
					</a>
					<a href="dashboard.php" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i> 
						<span class="nav_name">Dashboard</span> 
					</a> 
					<a href="doctors.php" class="nav_link"> <i class='bx bx-user nav_icon'></i> 
						<span class="nav_name">Doctors</span> 
					</a> 					 
					<a href="patients.php" class="nav_link"> <i class='bx bx-confused nav_icon'></i> 
						<span class="nav_name">Patients</span> 
					</a> 
					<a href="appointments.php" class="nav_link"> <i class='bx bx-time nav_icon'></i> 
						<span class="nav_name">Appointments</span> 
					</a>
					<a href="payhist.php" class="nav_link"> <i class='bx bx-money nav_icon'></i> 
						<span class="nav_name">Payments</span> 
					</a>
					<a href="specialties.php" class="nav_link"> <i class='bx bx-scan nav_icon'></i> 
						<span class="nav_name">Specialties</span> 
					</a>
					<a href="doctor-logs.php" class="nav_link"> <i class='bx bx-library nav_icon'></i> 
						<span class="nav_name">Session Logs</span> 
					</a>
					<!--<a href="#" class="nav_link"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> 
						<span class="nav_name">Stats</span> 
					</a> -->
					<?php 
					$sql = mysqli_query( $link, "select id from id17137158_pmsproject.messages where IsRead = 0" );
					$num_rows = mysqli_num_rows($sql);
					?>
					<a href="pmessages.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i> 
						<span class="nav_name">Messages
						<?php 
						if($num_rows==0){

						}else{
							echo '<span class="badge">'.$num_rows.'</span>';
						}

						?>
						</span> 
					</a>
					<?php 
					$sql = mysqli_query( $link, "select id from id17137158_pmsproject.adminmessages where IsRead = 0" );
					$num_rows = mysqli_num_rows($sql);
					?>
					<a href="messages.php" class="nav_link"> <i class='bx bx-message-square nav_icon'></i> 
						<span class="nav_name">Internal Messages
						<?php 
						if($num_rows==0){

						}else{
							echo '<span class="badge">'.$num_rows.'</span>';
						}

						?>
						</span> 
					</a>
				</div>
            </div>
            <a href="../logout.php" class="nav_link"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a> 
		</nav>
    </div>
</header>

</html>