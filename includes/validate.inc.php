<?php
	session_start();
	require_once("./function.inc.php");

	if (!isset($_GET['token']))
	{
		header("Location: ../index.php");
	}
	else
	{
		$header = validate_email($_GET['token']);
		header("Location: ../index.php?log_error=" . $header);
	}