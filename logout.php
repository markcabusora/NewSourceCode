<?php
	//database connection
	require('dbconnect.php');

	//start of session
	session_start();

	//end of session
	session_destroy();

	//The page will be redirected to login page
	//when the session is ended.
	header('location:index.html');
?>