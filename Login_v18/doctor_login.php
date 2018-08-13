<?php
	
	session_start();

	if(isset($_SESSION['doc_user'])) {
		header('Location:../doctor_home.php');
		exit();
	}

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		include '../connect.php';

		$user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
		$pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);

		$stmt = $conn->prepare("SELECT * FROM doctors WHERE doc_username=? AND doc_password=?");
		$stmt->execute(array($user, sha1($pass)));

		if($stmt->rowCount() == 1) {
			$row = $stmt->fetch();
			$_SESSION['doc_user'] = $user;
			$_SESSION['doc_id']   = $row['doc_id'];
			$_SESSION['doc_name'] = $row['doc_name'];
			header('Location:../doctor_home.php');
			exit();
		}
		else {
			global $errorMsg;
			$errorMsg = "Either Username or Password doesn't exsists";
		}


	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V18</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="my-link"><a href="../index.php">Return to Homepage</a></div>
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
					<span class="login100-form-title p-b-43">
						Doctor's Login
					</span>
					
					
					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="user">
						<span class="focus-input100"></span>
						<span class="label-input100">Username</span>
					</div>
					
					
					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="pass">
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>


					<div class="container-login100-form-btn">
						<input class="login100-form-btn" type="submit" value="Login"  />
					</div>

					<div class="error-msg">
						 <?php if(isset($errorMsg)) echo "<span style='color:red'>Error</span>: " . $errorMsg; ?>
					</div>
					
				</form>

				<div class="login100-more" style="background-image: url('images/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>
	

	
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>