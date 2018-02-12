<?php
	include 'dbconnect.php';
	session_start();
	$userID = $_SESSION['user_id'];

	$_SESSION['user_id'] = $userID;

  	$uploadCode = $_SESSION['upload_code'];

	$deleteRequest = "DELETE FROM tblRequest WHERE USER_ID = '$userID' AND UPLOAD_CODE = '$uploadCode'";
	$delRequestRes = $conn->query($deleteRequest);

	session_unset($uploadCode);

	$_SESSION['user_id'] = $userID;

	header('location: users_requests.php');

?>