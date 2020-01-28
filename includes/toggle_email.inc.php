<?php
	session_start();
	require_once("function.inc.php");
	if (!check_connection())
	{
		header("Location: ../index.php");
	}
	else
	{
		if ($_GET['bool'] == "false")
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("UPDATE users SET email_bool = 0 WHERE username = ?");
			$stmt->execute([$_SESSION['user']]);
		}
		else
		{
			$DB = dbConnect();
			$stmt = $DB->prepare("UPDATE users SET email_bool = 1 WHERE username = ?");
			$stmt->execute([$_SESSION['user']]);

		}
	}
?>