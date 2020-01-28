<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">	
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Camagru</title>
	</head>
	<body>
		<header>
			<?php 
				if (isset($_SESSION['user']) && $_SESSION['user'] !== "")
				{
					?>
							<a href="index.php">Index</a>
							<a class="create" href="capture.php">Take Photo</a>
							<a href="parameters.php">Parameters</a>
							<a href="./includes/logout.inc.php">Logout</a>
					<?php			
				}
				else
				{
					?>
						<div class="link">
							<a href="index.php">Index</a>
						</div>
						<div class="link">
							<form method="post" action="./includes/login.inc.php">
									<input type="text" autocomplete="username" name="email" placeholder="Email/Username" required="">
									<input type="password" autocomplete="current-password" name="passwd" placeholder="Password" required="">
									<input type="submit" name="login" value="Login">
							</form>
						</div>
						<div class="link">
							<a class="create" href="create_account.php">Create account</a>
						</div>
						<div class="link">
							<a href="forgot_password.php">Forgot your password ?</a>
						</div>
					<?php
				}
			?>
		</header>
		<div id="main">
