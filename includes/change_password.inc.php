<?php
	session_start();
	require_once("./function.inc.php");

	if (!isset($_GET['token']))
	{
		header("Location: ../index.php");
	}
	else
	{
		$header = check_token($_GET['token']);
		if (!isset($header))
		{
			header("Location: ../forgot_password.php");
		}
		else
		{
			if (isset($_GET['log_error']))
			{
				echo "<p class='error'>" . $_GET['log_error'] . "</p>";
			}
			$_SESSION['token'] = $_GET['token'];
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<title></title>
			</head>
			<body>
				<form method="post" action="modif_password.inc.php">
				<input type="password" name="new_password" required="" placeholder="Your new password">
				<input type="submit" name="change" value="change">
			</form>
			</body>
			</html>


			<?php
		}
	}