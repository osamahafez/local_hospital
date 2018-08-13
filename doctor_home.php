<?php

	session_start();

	if(isset($_SESSION['doc_user'])) {
		$pageTitle = 'HomePage';
		include 'init.php';
		include $tmpl . 'navbar.php';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$date = filter_var($_POST['app_date'], FILTER_SANITIZE_STRING);

			$stmt2= $conn->prepare("SELECT * FROM appointments WHERE app_doctorID=? AND app_date=?");
			$stmt2->execute(array($_SESSION['doc_id'], $date));
			$apps = $stmt2->fetchAll();
			$records = $stmt2->rowCount();

			if($records > 0) {

				?>

				<div class="container">
						<h2 class="text-center">Appointments</h2>
						<div class="table-responsive">
							<table class="main-table table table-bordered text-center">
								<tr>
									<td>ID</td>
									<td>Patient Name</td>
									<td>Email</td>
									<td>Reservation Date</td>
									<td>Appointment Date</td>
									<td class="condition">Condition</td>
								</tr>

								<?php

									foreach ($apps as $app) { ?>
										<tr>
											<td><?php echo $app['app_id'] ?></td>
											<td><?php echo $app['app_patient'] ?></td>
											<td><?php echo $app['app_email'] ?></td>
											<td><?php echo $app['app_resDate'] ?></td>
											<td><?php echo $app['app_date'] ?></td>
											<td class="condition"><?php echo $app['app_msg'] ?></td>
										</tr>
										<?php	
									} 
								?>

							</table>
						</div>
				</div>

				<?php
				include $tmpl . 'footer.php';
			}
			else {
				echo "<div class='alert alert-danger text-center'>No Records Found.</div>";
				include $tmpl . 'footer.php';
			}
		}

		else {
			$stmt = $conn->prepare("SELECT * FROM appointments WHERE app_doctorID = ?");
			$stmt->execute(array($_SESSION['doc_id']));
			$apps = $stmt->fetchAll();

			?>

			<div class="container">
					<h2 class="text-center">Appointments</h2>
					<div class="table-responsive">
						<table class="main-table table table-bordered text-center">
							<tr>
								<td>ID</td>
								<td>Patient Name</td>
								<td>Email</td>
								<td>Reservation Date</td>
								<td>Appointment Date</td>
								<td class="condition">Condition</td>
							</tr>

							<?php

								foreach ($apps as $app) { ?>
									<tr>
										<td><?php echo $app['app_id'] ?></td>
										<td><?php echo $app['app_patient'] ?></td>
										<td><?php echo $app['app_email'] ?></td>
										<td><?php echo $app['app_resDate'] ?></td>
										<td><?php echo $app['app_date'] ?></td>
										<td class="condition"><?php echo $app['app_msg'] ?></td>
									</tr>
									<?php	
								} 
							?>

						</table>
					</div>
				</div>
			
				<?php

				include $tmpl . 'footer.php';
		}
	
	}
	else {
		header('Location:Login_v18\doctor_login.php');
		exit();
	}