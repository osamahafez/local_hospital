<?php 
	
	session_start();
	
	if(isset($_SESSION['user_admin'])) {

		$pageTitle = 'Appointments';
		include 'init.php';
		include $tmpl . 'admin_navbar.php';

		// Similar to admins page
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage') {
			?>

			<div class="container container-table">
				<h2 class="text-center">Appointments</h2>
				<div class="table-responsive">
					<table class="main-table table table-bordered text-center">
						<tr>
							<td>ID</td>
							<td>Patient Name</td>
							<td>Reservation Date</td>
							<td>Appointment Date</td>
							<td>Doctor</td>
							<td>Options</td>
						</tr>

						<?php
							$stmt = $conn->prepare("SELECT appointments.*, doctors.doc_name FROM appointments
													INNER JOIN doctors ON doctors.doc_id=appointments.app_doctorID  
													ORDER BY appointments.app_id DESC");
							$stmt->execute();
							$results = $stmt->fetchAll();

							foreach ($results as $oneResult) { ?>
								<tr>
									<td><?php echo $oneResult['app_id'] ?></td>
									<td><?php echo $oneResult['app_patient'] ?></td>
									<td><?php echo $oneResult['app_resDate'] ?></td>
									<td><?php echo $oneResult['app_date'] ?></td>
									<td><?php echo $oneResult['doc_name'] ?></td>
									<td>
										<a href="appointments.php?do=Edit&appid=<?php echo $oneResult['app_id'] ?>" class="btn btn-success btn-xs"> Edit</a>
										<a href="appointments.php?do=Delete&appid=<?php echo $oneResult['app_id'] ?>" class="btn btn-danger btn-xs confirm"> Delete</a>
									</td>
								</tr>
								<?php	
							} 

						?>
			
					</table>
				</div>
				<a href="appointments.php?do=Add" class="btn btn-primary">New Appointment</a>
			</div>


			<?php

		}

		elseif($do == 'Add') {
			?>
			<div class="container add-form">
				<h3>Add Appointment:</h3>
				<form class="form-horizontal" action="appointments.php?do=Insert" method="POST">

					<!--START NAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Patient Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" autocomplete="off" required="required" />
						</div>
					</div>
					<!--END NAME FIELD-->

					<!--START GENDER FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Gender</label>
						<div class="col-sm-10 col-md-4">
							<input type="radio" name="gender" value="M" checked /> Male
							<input type="radio" name="gender" value="F" /> Female																
						</div>
					</div>
					<!--END GENDER FIELD-->

					<!--START EMAIL FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="email" name="email" required="required" />
						</div>
					</div>
					<!--END EMAIL FIELD-->

					<!--START APPOINTMENT DATE FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Appointment Date</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="date" name="date" required="required" />
						</div>
					</div>
					<!--END APPOINTMENT DATE FIELD-->

					<!--START DOCTOR FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Doctor</label>
						<div class="col-sm-10 col-md-4">
							<select class="form-control" name="doctor" required="required">
								<option value=""></option>
								<?php

								$stmt1 = $conn->prepare("SELECT doc_id, doc_name, doc_field FROM doctors");
								$stmt1->execute();
								$doctors = $stmt1->fetchAll();

								foreach($doctors as $doctor) {
									echo "<option value='" . $doctor['doc_id'] . "'>" . $doctor['doc_name'] . " - " . $doctor['doc_field'] . "</option>";
								}

								?>
							</select>
						</div>
					</div>
					<!--END DOCTOR FIELD-->

					<!--START MSG FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Condition</label>
						<div class="col-sm-10 col-md-4">
							<textarea class="form-control" name="msg" placeholder="Describe Your condition (Optional)"></textarea>
						</div>
					</div>
					<!--END MSG FIELD-->

					<!--START SUBMIT FIELD-->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input class="btn btn-primary" type="submit" value="Submit" />
						</div>
					</div>
					<!--END SUBMIT FIELD-->
					
				</form>
			</div>
			<?php

		}

		elseif($do == 'Insert') {
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<div class='container text-center'>";

				$name = $_POST['name'];
				$gender = $_POST['gender'];
				$email = $_POST['email'];
				$date = $_POST['date'];
				$doctor = $_POST['doctor'];
				$msg = $_POST['msg'];


				$formErrors = array();

				if(empty($name))
					$formErrors[] = "Name field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";
				if(empty($date))
					$formErrors[] = "Date field is empty.";
				if(empty($doctor))
					$formErrors[] = "You didn't choose the doctor.";

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
				}
				else {
					$stmt = $conn->prepare("INSERT INTO appointments(app_patient, app_gender, app_email, app_msg, app_resDate, app_date, app_doctorID) 
											VALUES(?, ?, ?, ?, NOW(), ?, ?)");
					$stmt->execute(array($name, $gender, $email, $msg, $date, $doctor));

					if($stmt->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Appointment Added Succesfully</h3></div>";
						header('REFRESH:2;URL=appointments.php?do=Manage');
					}
					else {
						echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
						header('REFRESH:2;URL=appointments.php?do=Manage');
					}

				}

				echo "</div>";
			}
			else {
				echo "You can't view this page directly";
			}

		}

		elseif($do == 'Edit') {

			$appid = (isset($_GET['appid']) && is_numeric($_GET['appid'])) ? intval($_GET['appid']) : 0;

			$stmt1= $conn->prepare("SELECT * FROM appointments WHERE app_id=$appid");
			$stmt1->execute();

			if($stmt1->rowCount() == 1) {
				$row = $stmt1->fetch();
				?>

				<div class="container edit-form">
				<h3>Edit Appointment:</h3>
				<form class="form-horizontal" action="appointments.php?do=Update" method="POST">

					<!--START HIDDEN ID FIELD-->
					<input class="form-control" type="hidden" name="id" value="<?php echo $row['app_id']?>" />
					<!--END HIDDEN ID FIELD-->

					<!--START NAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Patient Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" value="<?php echo $row['app_patient']?>" autocomplete="off" required="required" />
						</div>
					</div>
					<!--END NAME FIELD-->

					<!--START GENDER FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Gender</label>
						<div class="col-sm-10 col-md-4">
							<input type="radio" name="gender" value="M" <?php if($row['app_gender'] == 'M') echo "checked"; ?> /> Male
							<input type="radio" name="gender" value="F" <?php if($row['app_gender'] == 'F') echo "checked"; ?> /> Female																
						</div>
					</div>
					<!--END GENDER FIELD-->

					<!--START EMAIL FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="email" name="email" value="<?php echo $row['app_email']?>" required="required" />
						</div>
					</div>
					<!--END EMAIL FIELD-->

					<!--START RESERVATION DATE FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Appointment Date</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="date" name="date" value="<?php echo $row['app_date']?>" required="required" />
						</div>
					</div>
					<!--END RESERVATION DATE FIELD-->

					<!--START DOCTOR FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Doctor</label>
						<div class="col-sm-10 col-md-4">
							<select class="form-control" name="doctor" required="required">
								<option value=""></option>
								<?php

								$stmt2 = $conn->prepare("SELECT doc_id, doc_name, doc_field FROM doctors");
								$stmt2->execute();
								$doctors = $stmt2->fetchAll();

								foreach($doctors as $doctor) {
									?>
									<option value='<?php echo $doctor['doc_id'] ?>' <?php if($row['app_doctorID'] == $doctor['doc_id']) echo "selected"; ?>> <?php echo $doctor['doc_name'] ?> - <?php echo $doctor['doc_field'] ?></option>
									<?php
								}

								?>
							</select>
						</div>
					</div>
					<!--END DOCTOR FIELD-->

					<!--START MSG FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Condition</label>
						<div class="col-sm-10 col-md-4">
							<textarea class="form-control" name="msg" placeholder="Describe Your condition (Optional)"><?php echo $row['app_msg']?></textarea>
						</div>
					</div>
					<!--END MSG FIELD-->

					<!--START SUBMIT FIELD-->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input class="btn btn-primary" type="submit" value="Submit" />
						</div>
					</div>
					<!--END SUBMIT FIELD-->

				<?php
			}
			else {
				echo "No Such ID";
			} 

		}

		elseif($do == 'Update') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				echo "<div class='container text-center'>";

				$id = $_POST['id'];
				$name = $_POST['name'];
				$gender = $_POST['gender'];
				$email = $_POST['email'];
				$date = $_POST['date'];
				$doctor = $_POST['doctor'];
				$msg = $_POST['msg'];


				$formErrors = array();

				if(empty($name))
					$formErrors[] = "Name field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";
				if(empty($date))
					$formErrors[] = "Date field is empty.";
				if(empty($doctor))
					$formErrors[] = "You didn't choose the doctor.";

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
					header('REFRESH:5;URL=appointments.php?do=Manage');
				}
				else {
					$stmt2 = $conn->prepare("UPDATE appointments SET app_patient=?, app_gender=?, app_email=?, app_msg=?, app_date=?, app_doctorID=? WHERE app_id=?");
					$stmt2->execute(array($name, $gender, $email, $msg, $date, $doctor, $id));

					if($stmt2->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Edit Completed Succesfully</h3></div>";
						header('REFRESH:2;URL=appointments.php?do=Manage');
					}
					else {
						echo "<div class='alert alert-danger'><h3>No Changes Happened</h3></div>";
						header('REFRESH:2;URL=appointments.php?do=Manage');
					}

				}

				echo "</div>";

			}
			else {
				echo "You Can't view this page directly";
			}

		}

		elseif($do == 'Delete') {
			
			echo "<div class='container text-center'>";

			$appid = (isset($_GET['appid']) && is_numeric($_GET['appid'])) ? intval($_GET['appid']) : 0;

			$stmt1= $conn->prepare("SELECT * FROM appointments WHERE app_id=$appid");
			$stmt1->execute();

			if($stmt1->rowCount() == 1) {
				$stmt2 = $conn->prepare("DELETE FROM appointments WHERE app_id=$appid");
				$stmt2->execute();

				if($stmt2->rowCount() == 1) {
					echo "<div class='alert alert-success'><h3>Appointment Deleted Succesfully</h3></div>";
					header('REFRESH:2;URL=appointments.php?do=Manage');
				}
				else {
					echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
					header('REFRESH:2;URL=appointments.php?do=Manage');
				}
			}
			else {
				echo "div class='alert alert-danger'>Appointment not found</div>";
			}
			echo "</div>";

		}

		else {
			echo "Wrong Request.";
		}

		include $tmpl . 'footer.php';
	}
	else {
		echo "You can't view this page directly";
	}

	