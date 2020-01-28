<?php
	session_start();
	require_once("function.inc.php");

	if (isset($_POST['change']) && !empty($_POST['new_password']) && !empty($_SESSION['token']))
	{
		if (!preg_match('/^^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,49}$/', $_POST['new_password']))
		{
			header("Location: change_password.inc.php?log_error=Password need at least 1 number, 1 uppercase, 1 lowercase and at least 8 char&token=" . $_SESSION['token']);
		}
		else
		{
			$hash_pass = hash('whirlpool', $_POST['new_password']);
			$DB = dbConnect();
			$stmt = $DB->prepare("SELECT username FROM pending_users WHERE token = ?");
			$stmt->execute([$_SESSION['token']]);
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			$DB = dbConnect();
			$stmt = $DB->prepare("UPDATE users SET password = ? WHERE email = ?");
			$stmt->execute(array($hash_pass, $result['username']));


			$DB = dbConnect();
			$stmt = $DB->prepare("DELETE FROM pending_users WHERE username = ?");
			$stmt->execute([$result['username']]);
			header("Location: ../index.php?log_error=Password Updated");
		}
	}
	else
	{
		header("Location: ../index.php");
	}