<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Your Personal Database</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="css/fontello.css">
	</head>
	<body>
		<header>
			<div class="container">
			<a href="index.php"><h1>Your Personal Database</h1></a>
				<nav class="menu-div">
					<a href="#" id="menu-icon" onclick="menuToggle();"></a>
					<ul class="menu" id="menu">
				<?php
					if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
				?>
						<a href="index.php"><li>Home</li></a>
						<a href="view-public-records.php"><li>View Public Records</li></a>
						<a href="reset-password.php"><li>Reset Password</li></a>
						<a href="logout.php"><li>Log Out</li></a>
					
				<?php
					} else {
				?>
						<a href="view-public-records.php"><li>View Public Records</li></a>
						<a href="register.php"><li>Create Free Account</li></a>
						<a href="login.php"><li>Login</li></a>
				<?php
					}
				?>
					</ul>
				</nav>
			</div>
		</header>
		<div class="container body">