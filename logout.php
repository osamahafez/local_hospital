<?php 

	session_start();

	session_unset();

	session_destroy();

	header('Location:Login_v18\doctor_login.php'); 

	exit();