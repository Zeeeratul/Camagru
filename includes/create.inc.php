<?php
	require_once("./function.inc.php");

	if (!isset($_POST['create']) || empty($_POST['email']) || empty($_POST['username']) || empty($_POST['passwd']) || empty($_POST['passwd2']))
	{
		header("Location: ../create_account.php");
	}
	else
	{
		$error = valid_format_create($_POST['passwd'], $_POST['passwd2'], $_POST['email'], $_POST['username']);
		if ($error !== TRUE)
		{
			header("Location: ../create_account.php?log_error=" . $error);
		}
		else
		{
			if (already_use_email($_POST['email']))
			{
				header("Location: ../create_account.php?log_error=Email is taken");
			}
			else if (already_use_username($_POST['username']))
			{
				header("Location: ../create_account.php?log_error=Username is taken");
			}
			else
			{
				$hash_pass = hash('whirlpool', $_POST['passwd']);
				create_account($hash_pass, $_POST['email'], $_POST['username']);
				$token = generate_unique_url($_POST['username']);
				send_email($_POST['email'], "Validate your account", "Click on this link http://celestindelahaye.ddns.net/camagru/includes/validate.inc.php?token=" . $token);
				header("Location: ../index.php?log_error=Account created!");
			}
		}
	}