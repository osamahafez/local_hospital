<?php

	$pageTitle = 'The Local Hospital';
	include 'init.php';

?>
	
	<div class="background-image">

	<a href="Login_v18/doctor_login.php" class="btn btn-primary pull-right doc-login">
		Login for Doctors
	</a>

	<div class="container index-container">
		<div class="my-panel">
			<h2 class="text-center">The Local Hospital</h2>
			<div class="panel panel-primary main-panel">
				<div class="panel-heading text-center p-head">
					Submit Appointment
				</div>
				<div class="panel-body p-body">
					
					<form class="text-center" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

						<!--START NAME FIELD-->
						<div class="form-group">
							<label>Patient Name</label>
							<div>
								<input class="form-control text-center" type="text" name="name" required="required" autocomplete="off" />
							</div>
						</div>
						<!--END NAME FIELD-->

						<!--START GENDER FIELD-->
						<div class="form-group">
							<label>Gender</label>
							<div>
								<input type="radio" name="gender" value="M" checked /> Male
								<input type="radio" name="gender" value="F" /> Female																
							</div>
						</div>
						<!--END GENDER FIELD-->

						<!--START EMAIL FIELD-->
						<div class="form-group">
							<label>Email</label>
							<div>
								<input class="form-control text-center" type="email" name="email" required="required" />
							</div>
						</div>
						<!--END EMAIL FIELD-->

						<!--START RESERVATION DATE FIELD-->
						<div class="form-group">
							<label>Reserve The Appointment</label>
							<div>
								<input class="form-control text-center" type="date" name="date" required="required" />
							</div>
						</div>
						<!--END RESERVATION DATE FIELD-->

						<!--START DOCTOR FIELD-->
						<div class="form-group">
							<label>Doctor</label>
							<div>
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
							<label>Condition</label>
							<div>
								<textarea class="form-control" name="msg" placeholder="Describe Your condition (Optional)"></textarea>
							</div>
						</div>
						<!--END MSG FIELD-->

						<!--START SUBMIT FIELD-->
						<div class="form-group">
							<div>
								<input class="btn btn-primary submit-custom" type="submit" value="Submit" />
							</div>
						</div>
						<!--END SUBMIT FIELD-->
					
					</form>

				</div>	
			</div>
	
			

<?php
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			$name 	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			$doctor = filter_var($_POST['doctor'], FILTER_SANITIZE_NUMBER_INT);
			$msg 	= filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
			$gender = $_POST['gender'];
			$date 	= $_POST['date'];

			$formErrors = array();

			if(empty($name))
				$formErrors[] = "Please Enter Patient's Name.";
			if(empty($email))
				$formErrors[] = "Please Enter Patient's Email.";
			if(empty($date))
				$formErrors[] = "Please Select Appointment Date.";
			if(empty($doctor))
				$formErrors[] = "Please Choose a Doctor.";
			if($gender != 'M' && $gender != 'F')
				$formErrors[] = "Please Choose Your Gender";
			

			if(!empty($formErrors)) {

				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger text-center'>" . $error . "</div>";
				}
			}
			else {
				$stmt2 = $conn->prepare("INSERT INTO appointments(app_patient, app_gender, app_email, app_msg, app_date, app_resDate, app_doctorID) 
										VALUES(?, ?, ?, ?, ?, NOW(), ?)");
				$stmt2->execute(array($name, $gender, $email, $msg, $date, $doctor));

				if($stmt2->rowCount() == 1) {
					echo "<div class='alert alert-success text-center'>Form Submitted.</div>";
				}
				else {
					echo "<div class='alert alert-danger text-center'>Error, Please Try Again.</div>";
				}
			}
			echo "</div></div></div>";
		}

	include $tmpl . 'footer.php'; 

?>