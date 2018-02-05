<?php
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$dbName = 'dbthesys';

	//creating database connection
	//$conn = new mysqli($servername, $username, $password, $dbName);
	$conn = new mysqli($servername, $username, $password, $dbName);

	if(!$conn)
	{
		die("Connection Failed" . mysqli_connect_error());
	}

?>