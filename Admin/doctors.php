<?php 
	
	session_start();
	
	if(isset($_SESSION['user_admin'])) {

		$pageTitle = 'Manage Doctors';
		include 'init.php';
		include $tmpl . 'admin_navbar.php';


		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage') {
			?>

			<div class="container container-table">
				<h2 class="text-center">Doctors</h2>
				<div class="table-responsive">
					<table class="main-table table table-bordered text-center">
						<tr>
							<td>ID</td>
							<td>Name</td>
							<td>Username</td>
							<td>Date</td>
							<td>Field</td>
							<td>Options</td>
						</tr>

						<?php
							$stmt = $conn->prepare("SELECT * FROM doctors ORDER BY doc_id DESC");
							$stmt->execute();
							$results = $stmt->fetchAll();

							foreach ($results as $oneResult) { ?>
								<tr>
									<td><?php echo $oneResult['doc_id'] ?></td>
									<td><?php echo $oneResult['doc_name'] ?></td>
									<td><?php echo $oneResult['doc_username'] ?></td>
									<td><?php echo $oneResult['doc_date'] ?></td>
									<td><?php echo $oneResult['doc_field'] ?></td>
									<td>
										<a href="doctors.php?do=Edit&userid=<?php echo $oneResult['doc_id'] ?>" class="btn btn-success btn-xs">Edit</a>
										<a href="doctors.php?do=Delete&userid=<?php echo $oneResult['doc_id'] ?>" class="btn btn-danger btn-xs confirm">Delete</a>
									</td>
								</tr>
								<?php	
							} 

						?>
						
					</table>
				</div>
				<a href="doctors.php?do=Add" class="btn btn-primary">Add Doctor</a>
			</div>


			<?php

		}

		elseif($do == 'Add') {
			?>
			<div class="container add-form">
				<h3>Add Doctor:</h3>
				<form class="form-horizontal" action="doctors.php?do=Insert" method="POST" enctype="multipart/form-data">

					<!--START USERNAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="user" autocomplete="off" required="required" />
						</div>
					</div>
					<!--END USERNAME FIELD-->

					<!--START PASSWORD FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="pass" value="<?php echo randomPassword(10) ?>" required="required" />
							<span>(Auto Generated Password)</span>
						</div>
					</div>
					<!--END PASSWORD FIELD-->

					<!--START FULL NAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" required="required" />
						</div>
					</div>
					<!--END FULL NAME FIELD-->

					<!--START GENDER FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Gender</label>
						<div class="col-sm-10 col-md-4 radio-input">
							<input type="radio" name="gender" value="M" checked /> Male
							<input type="radio" name="gender" value="F"  /> Female
						</div>
					</div>
					<!--END GENDER FIELD-->

					<!--START PHONE FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Phone</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="phone" required="required" />
						</div>
					</div>
					<!--END PHONE FIELD-->

					<!--START ADDRESS FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Address</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="address" required="required" />
						</div>
					</div>
					<!--END ADDRESS FIELD-->

					<!--START EMAIL FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="email" name="email" required="required" />
						</div>
					</div>
					<!--END EMAIL FIELD-->

					<!--START DOCTOR'S FIELD FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Specialization Field</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="field" required="required" placeholder="Separate each field by a comma e.g.(Eyes, Heart) " />
						</div>
					</div>
					<!--END DOCTOR'S FIELD FIELD-->

					<!--START BIO FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Bio</label>
						<div class="col-sm-10 col-md-4">
							<textarea class="form-control" name="bio"></textarea>
						</div>
					</div>
					<!--END BIO FIELD-->

					<!--START PIC FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Upload Picture</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="file" name="pic" required="required" />
						</div>
					</div>
					<!--END PIC FIELD-->

					<!--START SUBMIT FIELD-->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input class="btn btn-primary" type="submit" value="Add" />
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

				// information came from the add form
				$user 	= filter_var($_POST['user'], FILTER_SANITIZE_STRING);
				$pass 	= filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
				$name 	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);	
				$phone 	= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
				$address= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
				$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$field 	= filter_var($_POST['field'], FILTER_SANITIZE_STRING);
				$bio 	= filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
				$gender	= $_POST['gender'];

				// information of the uploaded picture
				$pic_name = $_FILES['pic']['name'];
				$pic_type = $_FILES['pic']['type'];
				$pic_size = $_FILES['pic']['size'];
				$pic_tmp  = $_FILES['pic']['tmp_name'];

				$pic_extensions = array("jpeg", "jpg", "png"); // allowed extensions
				$pic_array = explode('.', $pic_name);
				$extension = strtolower(end($pic_array)); // the picture extension

				/*Important Note: when a picture is uploaded it its name is changed to 
				this form (doctorUsername_originalPictureName.jpg) or .png or .jpeg */

				$formErrors = array();

				if(empty($user))
					$formErrors[] = "Username field is empty.";
				if(empty($pass))
					$formErrors[] = "Password field is empty.";
				if(empty($name))
					$formErrors[] = "Full Name field is empty.";
				if(empty($phone))
					$formErrors[] = "Phone field is empty.";
				if(empty($address))
					$formErrors[] = "Address field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";
				if(empty($field))
					$formErrors[] = "Specialization field is empty.";
				if(empty($pic_name))
					$formErrors[] = "You haven't uploaded a picture.";
				if(!empty($pic_name) && !in_array($extension, $pic_extensions))
					$formErrors[] = "Picture extension is now allowed, choose => (jpg, png, jpeg).";
				if($pic_size > 40194304)  
					$formErrors[] = "Picture's maximum size can't be larger than 4MB";
			
	

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
				}
				else {

					// move the uploaded picture to "Uploads\doc_pic\"
					$doc_pic = $user . "_" . $pic_name;
					move_uploaded_file($pic_tmp, "Uploads\doc_pics\\" . $doc_pic);

					$stmt = $conn->prepare("INSERT INTO doctors(doc_username, doc_password, doc_name, doc_gender, doc_phone, doc_address, doc_email, doc_field, doc_bio, doc_pic, doc_date) 
											VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
					$stmt->execute(array($user, sha1($pass), $name, $gender, $phone, $address, $email, $field, $bio, $doc_pic));

					if($stmt->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Doctor Added Succesfully</h3></div>";
						header('REFRESH:2;URL=doctors.php?do=Manage');
					}
					else {
						echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
						header('REFRESH:2;URL=doctors.php?do=Manage');
					}

				}

				echo "</div>";
			}
			else {
				echo "You can't view this page directly";
			}

		}

		elseif($do == 'Edit') {

			$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

			$stmt= $conn->prepare("SELECT * FROM doctors WHERE doc_id=$userid");
			$stmt->execute();

			if($stmt->rowCount() == 1) {
				$row = $stmt->fetch();
				?>

				<div class="container edit-form">
					<div class="panel panel-primary">
						<div class="panel-heading text-center">Edit Doctor Information</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									<form class="form-horizontal" action="doctors.php?do=Update" method="POST" enctype="multipart/form-data">

										<!--START HIDDEN ID FIELD-->
										<input class="form-control" type="hidden" name="id" value="<?php echo $row['doc_id'] ?>" />
										<!--END HIDDEN ID FIELD-->

										<!--START USERNAME FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Username</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="user" value="<?php echo $row['doc_username'] ?>" autocomplete="off" required="required" />
											</div>
										</div>
										<!--END USERNAME FIELD-->

										<!--START PASSWORD FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Password</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="pass" placeholder="Enter new password or leave blank" />
											</div>
										</div>
										<!--END PASSWORD FIELD-->

										<!--START FULL NAME FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Full Name</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="name" value="<?php echo $row['doc_name'] ?>" required="required" />
											</div>
										</div>
										<!--END FULL NAME FIELD-->

										<!--START GENDER FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Gender</label>
											<div class="col-sm-10 col-md-8 radio-input">
												<input type="radio" name="gender" value="M" <?php if($row['doc_gender'] == 'M') echo "checked" ?> /> Male
												<input type="radio" name="gender" value="F" <?php if($row['doc_gender'] == 'F') echo "checked" ?> /> Female
											</div>
										</div>
										<!--END GENDER FIELD-->

										<!--START PHONE FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Phone</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="phone" value="<?php echo $row['doc_phone'] ?>" required="required" />
											</div>
										</div>
										<!--END PHONE FIELD-->

										<!--START ADDRESS FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Address</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="address" value="<?php echo $row['doc_address'] ?>" required="required" />
											</div>
										</div>
										<!--END ADDRESS FIELD-->

										<!--START EMAIL FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Email</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="email" name="email" value="<?php echo $row['doc_email'] ?>" required="required" />
											</div>
										</div>
										<!--END EMAIL FIELD-->

										<!--START DOCTOR'S FIELD FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Specialization Field</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="text" name="field" value="<?php echo $row['doc_field'] ?>" required="required" />
											</div>
										</div>
										<!--END DOCTOR'S FIELD FIELD-->

										<!--START BIO FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Bio</label>
											<div class="col-sm-10 col-md-8">
												<textarea class="form-control" name="bio"><?php echo $row['doc_bio'] ?></textarea>
											</div>
										</div>
										<!--END BIO FIELD-->

										<!--START PIC FIELD-->
										<div class="form-group">
											<label class="col-sm-2 col-md-4 control-label">Upload Picture</label>
											<div class="col-sm-10 col-md-8">
												<input class="form-control" type="file" name="pic" value="<?php echo $row['doc_pic'] ?>" />
											</div>
										</div>
										<!--END PIC FIELD-->

										<!--START SUBMIT FIELD-->
										<div class="form-group">
											<div class="col-sm-offset-4 col-sm-10">
												<input class="btn btn-primary" type="submit" value="Save" />
											</div>
										</div>
										<!--END SUBMIT FIELD-->
					
									</form>
								</div>
								<div class="col-md-6">
									<div class="thumbnail live-preview">
										<img class="img-responsive" src="Uploads\doc_pics\<?php echo $row['doc_pic'] ?>" />
									</div>
								</div>
							</div>
						</div>
					</div>		
				</div>

				<?php
			}
			else {
				echo "No Such ID";
			} 

		}

		elseif($do == 'Update') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				echo "<div class='container text-center'>";

				$id 	= $_POST['id'];
				$gender = $_POST['gender'];
				$user 	= filter_var($_POST['user'], FILTER_SANITIZE_STRING);
				$pass 	= filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
				$name 	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
				$phone 	= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
				$address= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
				$field 	= filter_var($_POST['field'], FILTER_SANITIZE_STRING);
				$bio 	= filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
				$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$hashpass = '';
				$new_pic = '';

				if(empty($pass)) {
					$stmt = $conn->prepare("SELECT doc_password FROM doctors WHERE doc_id=?");
					$stmt->execute(array($id));
					$myfetch = $stmt->fetch();
					$hashpass = $myfetch['doc_password'];
				}
				else {
					$hashpass = sha1($pass);
				}

				$pic_name = $_FILES['pic']['name'];
				$pic_size = $_FILES['pic']['size'];
				$pic_tmp  = $_FILES['pic']['tmp_name'];
				$pic_type = $_FILES['pic']['type']; 

				$pic_extensions = array("jpeg", "jpg", "png");
				$extension1 = explode('.', $pic_name);
				$extension2 = strtolower(end($extension1));

				if(empty($pic_name)) {
					$stmt3=$conn->prepare("SELECT doc_username, doc_pic FROM doctors WHERE doc_id=?");
					$stmt3->execute(array($id));
					$myfetch3=$stmt3->fetch();
					$new_pic = $myfetch3['doc_pic'];

					if($myfetch3['doc_username'] != $user) {

						$old_pic = $new_pic;	
						$new_pic = str_replace($myfetch3['doc_username'], $user, $new_pic);
						rename("Uploads\doc_pics\\" . $old_pic, "Uploads\doc_pics\\" . $new_pic);
					}
				}
				else {
					$stmt4=$conn->prepare("SELECT doc_pic FROM doctors WHERE doc_id=?");
					$stmt4->execute(array($id));
					$myfetch4=$stmt4->fetch();
					$current_pic = $myfetch4['doc_pic'];
					unlink("Uploads\doc_pics\\" . $current_pic);
					$new_pic = $pic_name; 
				}	

				$formErrors = array();

				if(empty($user))
					$formErrors[] = "Username field is empty.";
				if(empty($name))
					$formErrors[] = "Full Name field is empty.";
				if(empty($phone))
					$formErrors[] = "phone field is empty.";
				if(empty($address))
					$formErrors[] = "address field is empty.";
				if(empty($field))
					$formErrors[] = "Specialization field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";
				if(!empty($pic_name) && !in_array($extension2, $pic_extensions))
					$formErrors[] = "Picture extenstion is not allowed, choose => (jpeg, jpg, png).";

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
					header('REFRESH:5;URL=doctors.php?do=Manage');
				}
				else {

					if(!empty($pic_name)) {
						$new_pic = $user . "_" . $pic_name;
						move_uploaded_file($pic_tmp, "Uploads\doc_pics\\" . $new_pic);
					}

					$stmt2 = $conn->prepare("UPDATE doctors SET doc_username=?, doc_password=?, doc_name=?, doc_gender=?, doc_phone=?, doc_address=?, doc_email=?, doc_field=?, doc_bio=?, doc_pic=? WHERE doc_id=?");
					$stmt2->execute(array($user, $hashpass, $name, $gender, $phone, $address, $email, $field, $bio, $new_pic, $id));

					if($stmt2->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Edit Completed Succesfully</h3></div>";
						header('REFRESH:2;URL=doctors.php?do=Manage');
					}
					else {
						echo "<div class='alert alert-danger'><h3>No Changes Happened</h3></div>";
						header('REFRESH:2;URL=doctors.php?do=Manage');
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

			$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

			$stmt1= $conn->prepare("SELECT * FROM doctors WHERE doc_id=$userid");
			$stmt1->execute();
			$Thefetch = $stmt1->fetch();
			$pic_name = $Thefetch['doc_pic'];

			if($stmt1->rowCount() == 1) {
				$stmt2 = $conn->prepare("DELETE FROM doctors WHERE doc_id=$userid");
				$stmt2->execute();
				unlink("Uploads\doc_pics\\" . $pic_name); // delete doctor pic from uploads folder

				if($stmt2->rowCount() == 1) {
					echo "<div class='alert alert-success'><h3>Doctor Deleted Succesfully</h3></div>";
					header('REFRESH:2;URL=doctors.php?do=Manage');
				}
				else {
					echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
					header('REFRESH:2;URL=doctors.php?do=Manage');
				}
			}
			else {
				echo "div class='alert alert-danger'>Doctor not found</div>";
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

	