<?php

	session_start();
	$pageTitle = 'Contact Admin';
	include 'init.php';
	include $tmpl . 'navbar.php';

	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

		$stmt = $conn->prepare("INSERT INTO contacts(contact_msg, contact_doc) 
								VALUES(?, ?)");
		$stmt->execute(array($msg, $_SESSION['doc_id']));

		if($stmt->rowCount() == 1) {
			$successMsg = "Message Delivered Successfully.";
		}
		else {
			$faildMsg = "Something went wrong please try again.";
		}
	}

	?>

		
	<form class="contact-form text-center" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">

		<h3>Contact Admin</h3>
		<textarea class="form-control" name="msg"></textarea>
		<input class="btn text-center" type="submit" value="Submit">
			
	</form>
	
	<?php

	echo "<div class='custom-alert'>";
		if(isset($successMsg)) {
			echo "<div class='alert alert-success text-center custom-alert-succ'>" . $successMsg . "</div>";
		}
		if(isset($faildMsg)) {
			echo "<div class='alert alert-danger text-center custom-alert-dang'>" . $faildMsg . "</div>";
		}
	echo "</div>";
	
	include $tmpl . 'footer.php';
