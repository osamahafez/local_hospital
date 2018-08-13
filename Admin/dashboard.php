<?php 

	session_start();

	if(isset($_SESSION['user_admin'])) { // if admin is signed in
		
		$pageTitle = 'Dashboard';	
		include 'init.php';
		include $tmpl . 'admin_navbar.php';

		// Record all the available doctors, appointments and messages
		$stmt1 = $conn->prepare("SELECT * FROM doctors");
		$stmt1->execute();
		$doctors = $stmt1->rowCount();

		$stmt2 = $conn->prepare("SELECT * FROM appointments");
		$stmt2->execute();
		$appointments = $stmt2->rowCount();

		$stmt3 = $conn->prepare("SELECT * FROM contacts");
		$stmt3->execute();
		$contacts = $stmt3->rowCount();

		?>

		<div class="container">
			<div class="row text-center upper-dashboard">

				<div class="col-md-4">
					<a href="doctors.php">
					<div class="dashboard-left">
						<span class="glyphicon glyphicon-user"></span>
						<h3><?php echo $doctors ?> Doctors</h3>
					</div></a>
					<a href="doctors.php?do=Add" class="btn btn-default btn-block">Add Doctor</a>
				</div>

				<div class="col-md-4 ">
					<a href="contacts.php">
					<div class="dashboard-mid">
						<span class="glyphicon glyphicon-envelope"></span>
						<h3><?php echo $contacts ?> Messeages</h3>
					</div></a>
					<a href="contacts.php" class="btn btn-default btn-block">View Messages</a>
				</div>

				<div class="col-md-4 ">
					<a href="appointments.php">
					<div class="dashboard-right">
						<span class="glyphicon glyphicon-comment"></span>
						<h3><?php echo $appointments ?> Appointments</h3>
					</div></a>
					<a href="appointments.php?do=Add" class="btn btn-default btn-block">Add Appointment</a>
				</div>

			</div>

		</div>

		<?php
		include $tmpl . 'footer.php';
	}

	else { // if admin is not signed in
		echo "You Can't view this page directly";
	}