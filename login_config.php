<?php
require('dbconnect.php');
session_start();

if(isset($_POST['login'])){

	$username = $_POST['userID'];
	$password = MD5($_POST['password']);

	$queryUsername = "SELECT * FROM tblUsers WHERE user_id = '$username' AND status LIKE('ACTIVE')";

	$resultUsername = mysqli_query($conn, $queryUsername);

   
	$row = mysqli_fetch_assoc($resultUsername);
	$userID = $row['user_id'];
	$userType = $row['user_type'];
	$queryPassword = "SELECT * FROM tblPasswords WHERE password = '$password' AND user_id = '$userID'";
	$resultPassword = mysqli_query($conn, $queryPassword);
	if((mysqli_num_rows($resultUsername) > 0) && (mysqli_num_rows($resultPassword) > 0) )
	{
		if($userType == 'ADMINISTRATOR')
		{
			header("location: admin_dashboard.php");
			$_SESSION['user_id'] = $userID;
		}

		elseif($userType == 'FACULTY')
		{
			header("location: users_dashboard.php");
			$_SESSION['user_id'] = $userID;
		}
		elseif($userType == 'VISITOR')
		{
			header("location: users_dashboard.php");
			$_SESSION['user_id'] = $userID;
		}
		elseif($userType == 'STUDENT')
		{
			header('location: student_dashboard.php');
			$_SESSION['user_id'] = $userID;
		}

	}


else{
	?>
		<script type="text/javascript">
			alert('Username or Password not found.');

		</script>
	<?php
	echo"<script>location.assign('index.html')</script>";
	}
}
?>