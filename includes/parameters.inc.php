<?php
	session_start();
	require_once("./function.inc.php");

	if (!check_connection())
	{
		header("Location: index.php");
	}
	else
	{
		if (isset($_POST['changepasswd']))
		{
			if (empty($_POST['old_passwd']) || empty($_POST['new_passwd1']) || empty($_POST['new_passwd2']))
			{
				header("../index.php?log_error=Please fill form");
			}
			else
			{
				if ($_POST['new_passwd1'] !== $_POST['new_passwd2'])
				{
					header("Location: ../parameters.php?log_error=Passwords need to match");
				}
				else
				{
					$hash_pass_old = hash('whirlpool', $_POST['old_passwd']);
					$hash_pass_new = hash('whirlpool', $_POST['new_passwd1']);
					$array = checking_login($_SESSION['email'], $hash_pass_old);
					if ($array['validation'] == "Log success")
					{
						if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{7,49}$/', $_POST['new_passwd1']))
						{
							header("Location: ../parameters.php?log_error=Password need at least 1 number, 1 uppercase, 1 lowercase and at least 8 char");
						}
						else
						{
							$DB = dbConnect();
							$stmt = $DB->prepare("UPDATE users SET password = ? WHERE username = ?");
							$stmt->execute(array($hash_pass_new, $_SESSION['user']));
							header("Location: ../parameters.php?log_error=Password modified");
						}
					}
					else
					{
						header("Location: ../parameters.php?log_error=Wrong password");
					}
				}
			}
		}
		else if (isset($_POST['changeemail']))
		{
			if (empty($_POST['old_email']) || empty($_POST['new_email1']) || empty($_POST['new_email2']))
			{
				header("../index.php?log_error=Please fill form");
			}
			else
			{
				if ($_SESSION['email'] == $_POST['old_email'])
				{
					if ($_POST['new_email1'] !== $_POST['new_email2'])
					{
						header("Location: ../parameters.php?log_error=Emails need to match");
					}
					else
					{
						if (!filter_var($_POST['new_email1'], FILTER_VALIDATE_EMAIL))
						{
							header("Location: ../parameters.php?log_error=Wrong email format");
						}
						else
						{
							if (already_use_email($_POST['new_email1']))
							{
								header("Location: ../parameters.php?log_error=Email is taken");
							}
							else
							{
								$DB = dbConnect();
								$stmt = $DB->prepare("UPDATE users SET email = ? WHERE email = ?");
								$stmt->execute(array($_POST['new_email1'], $_SESSION['email']));
								$_SESSION['email'] = $_POST['new_email1'];
								header("Location: ../parameters.php?log_error=Email modified");
							}
						}
					}
	
				}
				else
				{
					header("Location: ../parameters.php?log_error=Wrong email");
				}
			}
		}
		else if (isset($_POST['changeusername']))
		{
			if (empty($_POST['old_username']) || empty($_POST['new_username1']) || empty($_POST['new_username2']))
			{
				header("../index.php?log_error=Please fill form");
			}
			else
			{
				if ($_POST['new_username1'] !== $_POST['new_username2'])
				{
					header("Location: ../parameters.php?log_error=Username need to match");
				}
				else
				{				
					if ($_SESSION['user'] == $_POST['old_username'])
					{
						if (strlen($_POST['new_username1']) < 6)
						{
							header("Location: ../parameters.php?log_error=Username need to be at least 6 char");
						}
						else
						{	
							if (already_use_username($_POST['new_username1']))
							{
								header("Location: ../parameters.php?log_error=Username is taken");
							}	
							else
							{		
								$DB = dbConnect();
								$stmt = $DB->prepare("UPDATE users SET username = ? WHERE email = ?");
								$stmt->execute(array($_POST['new_username1'], $_SESSION['email']));
								$_SESSION['user'] = $_POST['new_username1'];
								header("Location: ../parameters.php?log_error=Username modified");
							}
						}
					}				
					else
					{	
						header("Location: ../parameters.php?log_error=Wrong username");
					}
				}
			}
		}
		else
		{
			header("Location: ../index.php");
		}
	}