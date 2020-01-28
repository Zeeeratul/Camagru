<?php
	require_once("function.inc.php");
	if (!isset($_POST['forgot']))
	{
		header("Location: ../forgot_password.php");
	}
	else
	{
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			header("Location: ../forgot_password.php?log_error=Wrong email format");
		}
		else
		{
			if (already_use_email($_POST['email']))
			{		
				$token = generate_unique_url($_POST['email']);
				send_email($_POST['email'], "Change your password", "Click on this link http://celestindelahaye.ddns.net/camagru/includes/change_password.inc.php?token=" . $token);
				header("Location: ../index.php?log_error=Email send, please check your email");
			}
			else
			{
				header("Location: ../index.php");
			}
		}
	}