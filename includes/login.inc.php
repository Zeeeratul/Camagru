<?php
	session_start();
	require_once("./function.inc.php");


	if (!isset($_POST['login']) || empty($_POST['email']) || empty($_POST['passwd']))
	{
		header("Location: ../index.php");
	}
	else
	{
		$hash_pass = hash('whirlpool', $_POST['passwd']);
		$array_return = checking_login($_POST['email'], $hash_pass);
		if ($array_return['validation'] === "Log success")
		{
			$_SESSION['user'] = $array_return['username'];
			$_SESSION['id'] = $array_return['id'];
			$_SESSION['email'] = $array_return['email'];
		}
		else
		{
			$_SESSION['user'] = "";
			$_SESSION['id'] = "";
			$_SESSION['email'] = "";		
		}
		header("Location: ../index.php?log_error=" . $array_return['validation']); 
	}
?>