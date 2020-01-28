<?php

function dbConnect()
{
	$DB_DSN  = 'mysql:dbname=camagru;host=127.0.0.1';
	$DB_USER = 'root';
	$DB_PASSWORD = 'CTSJkVNyKqaSqM23';

	try 
	{
	    $DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 	    return $DB;
	} 
	catch (Exception $e)
	{
	    die('Error : ' . $e->getMessage());
	}
}	

?>