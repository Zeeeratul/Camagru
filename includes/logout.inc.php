<?php
	session_start();

	$_SESSION['user'] = "";
	$_SESSION['id'] = "";
	$_SESSION['email'] = "";

	session_destroy();
	header("Location: ../index.php");
?>