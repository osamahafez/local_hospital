<?php
	session_start();
	$pageTitle = $_SESSION['doc_name'];
	include 'init.php';
	include $tmpl . 'navbar.php';

	$do = isset($_GET['do']) ? $_GET['do'] : 'View' ;

	$stmt = $conn->prepare("SELECT * FROM doctors WHERE doc_id=?");
	$stmt->execute(array($_SESSION['doc_id']));
	$result = $stmt->fetch();

	if($do == 'View') {
		
		?>

		<div class="profile-image">
			<img class="img-responsive img-circle" src="Admin\Uploads\doc_pics\<?php echo $result['doc_pic']; ?>">
			<h3 class="text-center">Dr/<?php echo $result['doc_name']; ?></h3>
		</div>
		
		<div class="information block">
			<div class="container">
				<div class="panel panel-red">
					<div class="panel-heading panel-heading-red">My Information</div>
					<div class="panel-body panel-style">
						<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>Username:</span> <?php echo $result['doc_username']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-envelope-o fa-fw"></i>
								<span>Name:</span> <?php echo $result['doc_name']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-user fa-fw"></i>
								<span>Gender:</span> <?php echo $result['doc_gender']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Email:</span> <?php echo $result['doc_email']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Phone:</span> <?php echo $result['doc_phone']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Address:</span> <?php echo $result['doc_address']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Specilization:</span> <?php echo $result['doc_field']; ?> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Bio:</span> <?php echo $result['doc_bio']; ?> 
							</li>
							<hr>
					
						</ul>
						<a href="doctor_profile.php?do=Edit" class="btn btn-default pull-right">Edit</a>
					</div>
				</div>
			</div>
		</div>

		<?php	
	}

	elseif($do == 'Edit') {
		
		?>

		<div class="profile-image">
			<img class="img-responsive img-circle" src="Admin\Uploads\doc_pics\<?php echo $result['doc_pic']; ?>">
			<h3 class="text-center">Dr/<?php echo $result['doc_name']; ?></h3>
		</div>
		
		<div class="information block">
			<div class="container">
				<div class="panel panel-red">
					<div class="panel-heading panel-heading-red">Edit Information</div>
					<div class="panel-body panel-style">
						<form action="doctor_profile.php?do=Update" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $result['doc_id'] ?>">
						<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>Username:</span> 
								<input class="form-control" type="text" name="user" value="<?php echo $result['doc_username']; ?>" requried="requried" />   
							</li>
							<hr>
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>New Password:</span> 
								<input class="form-control" type="password" name="new_pass" /> 
								<br />
								<span>Confirm Password:</span> 
								<input class="form-control" type="password" name="conf_pass" />  
							</li>
							<hr>
							<li>
								<i class="fa fa-envelope-o fa-fw"></i>
								<span>Name:</span> 
								<input class="form-control" type="text" name="name" value="<?php echo $result['doc_name']; ?>" requried="requried" />
							</li>
							<hr>
							<li>
								<i class="fa fa-user fa-fw"></i>
								<span>Gender:</span> <br>
								<input type="radio" name="gender" value="M" <?php if($result['doc_gender'] == 'M') echo "checked"; ?> /> Male
								<input type="radio" name="gender" value="F" <?php if($result['doc_gender'] == 'F') echo "checked"; ?> /> Female
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Email:</span> 
								<input class="form-control" type="email" name="email" value="<?php echo $result['doc_email']; ?>" requried="requried" />
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Phone:</span> 
								<input class="form-control" type="text" name="phone" value="<?php echo $result['doc_phone']; ?>" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Address:</span> 
								<input class="form-control" type="text" name="address" value="<?php echo $result['doc_address']; ?>" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Specilization:</span> 
								<input class="form-control" type="text" name="field" value="<?php echo $result['doc_field']; ?>" placeholder="Separate each field with a comma (,)" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Bio:</span> <br /> 
								<textarea class="form-control" name="bio"><?php echo $result['doc_bio']; ?> </textarea>
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Upload New Picture:</span> 
								<input class="form-control" type="file" name="pic" /> 
							</li>
							<hr>
					
						</ul>
						<input class="btn btn-default pull-right" type="submit" value="Save" />
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php

	}

	elseif ($do == 'Update') {
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				echo "<div class='container text-center'>";

				$id 		= $_POST['id'];
				$gender 	= $_POST['gender'];
				$user 		= filter_var($_POST['user'], FILTER_SANITIZE_STRING);
				$new_pass 	= filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING);
				$conf_pass 	= filter_var($_POST['conf_pass'], FILTER_SANITIZE_STRING);
				$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
				$phone 		= filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
				$address 	= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
				$field 		= filter_var($_POST['field'], FILTER_SANITIZE_STRING);
				$bio 		= filter_var($_POST['bio'], FILTER_SANITIZE_STRING);
				$email 		= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$hashpass 	= '';
				$new_pic 	= '';

				if(empty($new_pass)) {
					$stmt = $conn->prepare("SELECT doc_password FROM doctors WHERE doc_id=?");
					$stmt->execute(array($id));
					$myfetch = $stmt->fetch();
					$hashpass = $myfetch['doc_password'];
				}
				else {
					$hashpass = sha1($new_pass);
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
						rename("Admin\Uploads\doc_pics\\" . $old_pic, "Uploads\doc_pics\\" . $new_pic);
					}
				}
				else {
					$stmt4=$conn->prepare("SELECT doc_pic FROM doctors WHERE doc_id=?");
					$stmt4->execute(array($id));
					$myfetch4=$stmt4->fetch();
					$current_pic = $myfetch4['doc_pic'];
					unlink("Admin\Uploads\doc_pics\\" . $current_pic);
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
				if($new_pass !== $conf_pass)
					$formErrors[] = "The two passwords don't match";
				if(!empty($pic_name) && !in_array($extension2, $pic_extensions))
					$formErrors[] = "Picture extenstion is not allowed, choose => (jpeg, jpg, png).";


				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
				}
				else {

					if(!empty($pic_name)) {
						$new_pic = $user . "_" . $pic_name;
						move_uploaded_file($pic_tmp, "Admin\Uploads\doc_pics\\" . $new_pic);
					}

					$stmt2 = $conn->prepare("UPDATE doctors SET doc_username=?, doc_password=?, doc_name=?, doc_gender=?, doc_phone=?, doc_address=?, doc_email=?, doc_field=?, doc_bio=?, doc_pic=? WHERE doc_id=?");
					$stmt2->execute(array($user, $hashpass, $name, $gender, $phone, $address, $email, $field, $bio, $new_pic, $id));

					if($stmt2->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Edit Completed Succesfully</h3></div>";
						header('REFRESH:2;URL=doctor_profile.php');
					}
					else {
						echo "<div class='alert alert-danger'><h3>No Changes Happened</h3></div>";
						header('REFRESH:2;URL=doctor_profile.php');
					}

				}

				echo "</div>";

		}


		?>

		<div class="profile-image">
			<img class="img-responsive img-circle" src="Admin\Uploads\doc_pics\<?php echo $result['doc_pic']; ?>">
			<h3 class="text-center">Dr/<?php echo $result['doc_name']; ?></h3>
		</div>
		
		<div class="information block">
			<div class="container">
				<div class="panel panel-red">
					<div class="panel-heading panel-heading-red">Edit Information</div>
					<div class="panel-body panel-style">
						<form action="doctor_profile.php?do=Update" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo $result['doc_id'] ?>">
						<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>Username:</span> 
								<input class="form-control" type="text" name="user" value="<?php echo $result['doc_username']; ?>"  />   
							</li>
							<hr>
							<li>
								<i class="fa fa-unlock-alt fa-fw"></i>
								<span>New Password:</span> 
								<input class="form-control" type="password" name="new_pass" /> 
								<br />
								<span>Confirm Password:</span> 
								<input class="form-control" type="password" name="conf_pass" />  
							</li>
							<hr>
							<li>
								<i class="fa fa-envelope-o fa-fw"></i>
								<span>Name:</span> 
								<input class="form-control" type="text" name="name" value="<?php echo $result['doc_name']; ?>" " />
							</li>
							<hr>
							<li>
								<i class="fa fa-user fa-fw"></i>
								<span>Gender:</span> <br>
								<input type="radio" name="gender" value="M" <?php if($result['doc_gender'] == 'M') echo "checked"; ?> /> Male
								<input type="radio" name="gender" value="F" <?php if($result['doc_gender'] == 'F') echo "checked"; ?> /> Female
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Email:</span> 
								<input class="form-control" type="email" name="email" value="<?php echo $result['doc_email']; ?>" requried="requried" />
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Phone:</span> 
								<input class="form-control" type="text" name="phone" value="<?php echo $result['doc_phone']; ?>" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Address:</span> 
								<input class="form-control" type="text" name="address" value="<?php echo $result['doc_address']; ?>" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Specilization:</span> 
								<input class="form-control" type="text" name="field" value="<?php echo $result['doc_field']; ?>" placeholder="Separate each field with a comma (,)" requried="requried" /> 
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Bio:</span> <br /> 
								<textarea class="form-control" name="bio"><?php echo $result['doc_bio']; ?> </textarea>
							</li>
							<hr>
							<li>
								<i class="fa fa-calendar fa-fw"></i>
								<span>Upload New Picture:</span> 
								<input class="form-control" type="file" name="pic" /> 
							</li>
							<hr>
					
						</ul>
						<input class="btn btn-default pull-right" type="submit" value="Save" />
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php


	}



	include $tmpl . 'footer.php'; ?>


	
