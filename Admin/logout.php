<?php 

	session_start();

	session_unset(); // clear session

	session_destroy(); // end session

	header('Location:index.php'); // go back to the login page

	exit();