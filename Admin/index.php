<?php
	
	session_start();
	$pageTitle='Admin-Login';
	include 'init.php';

	// If Admin is already login in send him to dashboard page
	if(isset($_SESSION['user_admin'])) {
		header('Location:dashboard.php');
	} 

	// Checking if the login in admin exists in database and if he is then record the session the go to dashboard
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$user_admin = $_POST['user'];
		$pass_admin = $_POST['pass'];

		$stmt = $conn->prepare("SELECT * FROM admins WHERE Username=? AND Password=? LIMIT 1");
		$stmt->execute(array($user_admin, sha1($pass_admin)));
		$check = $stmt->rowCount();
		$fetch = $stmt->fetch();

		if($check == 1) {
			$_SESSION['user_admin'] = $user_admin;
			$_SESSION['id_admin'] = $fetch['ID'];
			header('Location:dashboard.php');
			exit();
		}
	}

?>

<!--this button creates database and tables needed in this project-->
<div class="db-button"><a href="database_creation.php" class="btn btn-primary btn-block">Create Database</a></div>

<div class="frontend-button pull-right"><a href="../index.php" class="btn btn-warning">Front-End Section</a></div>

<form class="admin-login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
	<input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
	<input class="btn btn-info btn-block" type="submit" value="Login">
</form>
	
	
	

<?php	include $tmpl . 'footer.php'; ?>