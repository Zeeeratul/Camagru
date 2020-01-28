<?php

	try {
	    $dbh = new PDO("mysql:host=127.0.0.1", "root", "CTSJkVNyKqaSqM23");
	    var_dump($dbh);
	    $dbh->exec("CREATE DATABASE camagru;")
	    or die(print_r($dbh->errorInfo(), true));
	} catch (PDOException $e) {
	    die("DB ERROR: ". $e->getMessage());
	}

	require_once("database.php");
	$sql = file_get_contents("camagru.sql");
	$sql_array = explode(";", $sql);
	foreach ($sql_array as $val) 
	{
		$DB = dbConnect();
		$stmt = $DB->query($val);
	}
	header("Location: ../index.php?log_error=Database create");
?>