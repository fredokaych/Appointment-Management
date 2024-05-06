<?php
// Initialize the session
//session_start();
// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: ../index.php");
	exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>PMS|Dashboard</title>
	<link rel="stylesheet" type="text/css" href="../tools/boxicons/css/boxicons.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
	<link rel="stylesheet" type="text/css" href="../tools/mytweaks.css">
	<link rel="stylesheet" type="text/css" href="../tools/sidebar.css">
</head>

<header id="header" class="page-header header container-fluid">
	<div class="header_toggle"> <i class='bx bx-menu  bx-sm' id="header-toggle"></i> </div>
	<!--<div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div>-->
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
					<a class="nav_link">

					</a>
					<a href="dashboard.php" class="nav_link"> <i class='bx bx-grid-alt nav_icon'></i>
						<span class="nav_name">Dashboard</span>
					</a>
					<a href="profile.php" class="nav_link"> <i class='bx bx-user nav_icon'></i>
						<span class="nav_name">Profile</span>
					</a>

					<?php
					$sql = mysqli_query($link, "select id from id17137158_pmsproject.appointment where doctorId = " . $_SESSION['id'] . " and approvStatus = 0");
					$num_rows = mysqli_num_rows($sql);
					?>

					<a href="appointments.php" class="nav_link"> <i class='bx bx-time nav_icon'></i>
						<span class="nav_name">Appointments
							<?php
							if ($num_rows == 0) {
							} else {
								echo '<span class="badge"  style="background-color: orange">' . $num_rows . '</span>';
							}
							?>
						</span>
					</a>
					<a href="schedule.php" class="nav_link"> <i class='bx bx-layer nav_icon'></i>
						<span class="nav_name">Schedule</span>
					</a>
					<a href="payhist.php" class="nav_link"> <i class='bx bx-money nav_icon'></i>
						<span class="nav_name">My Payments</span>
					</a>
					<?php
					$sql = mysqli_query($link, "select id from id17137158_pmsproject.doctormessages where docID = " . $_SESSION['id'] . " and IsRead = 0");
					$num_rows = mysqli_num_rows($sql);
					?>
					<a href="messages.php" class="nav_link"> <i class='bx bx-message-square-detail nav_icon'></i>
						<span class="nav_name">Messages
							<?php
							if ($num_rows == 0) {
							} else {
								echo '<span class="badge">' . $num_rows . '</span>';
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