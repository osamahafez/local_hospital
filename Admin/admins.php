<?php 
	
	session_start();
	
	if(isset($_SESSION['user_admin'])) { // if admin is signed in

		$pageTitle = 'Admins';
		include 'init.php';
		include $tmpl . 'admin_navbar.php';

		//Information from the "GET" request
		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage'; 

		// Table that view important info about the admins
		if($do == 'Manage') {
			?>

			<div class="container container-table">
				<h2 class="text-center">Admins</h2>
				<div class="table-responsive">
					<table class="main-table table table-bordered text-center">
						<tr>
							<td>ID</td>
							<td>Username</td>
							<td>Full Name</td>
							<td>Email</td>
							<td>Date</td>
							<td>Options</td>
						</tr>

						<?php
							$stmt = $conn->prepare("SELECT * FROM admins ORDER BY ID DESC");
							$stmt->execute();
							$results = $stmt->fetchAll();

							foreach ($results as $oneResult) { ?>
								<tr>
									<td><?php echo $oneResult['ID'] ?></td>
									<td><?php echo $oneResult['Username'] ?></td>
									<td><?php echo $oneResult['FullName'] ?></td>
									<td><?php echo $oneResult['Email'] ?></td>
									<td><?php echo $oneResult['RegDate'] ?></td>
									<td>
										<a href="admins.php?do=Edit&userid=<?php echo $oneResult['ID'] ?>" class="btn btn-success btn-xs"><i class="fa fa-close"></i> Edit</a>
										<a href="admins.php?do=Delete&userid=<?php echo $oneResult['ID'] ?>" class="btn btn-danger btn-xs confirm"><i class="fa fa-close"></i> Delete</a>
									</td>
								</tr>
								<?php	
							} 

						?>
			
					</table>
				</div>
				<a href="admins.php?do=Add" class="btn btn-primary">New Admin</a>
			</div>


			<?php

		}

		// form to add new admin
		elseif($do == 'Add') {
			?>
			<div class="container add-form">
				<h3>Add Admin:</h3>
				<form class="form-horizontal" action="admins.php?do=Insert" method="POST">

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
							<input class="form-control" type="text" name="pass" value="<?php echo randomPassword() ?>" required="required" />
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

					<!--START EMAIL FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="email" name="email" required="required" />
						</div>
					</div>
					<!--END EMAIL FIELD-->

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

		// insert the information that came from the add form in database
		elseif($do == 'Insert') {
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<div class='container text-center'>";

				// filtering any input to be pure string without any harmful tags
				$user 	= filter_var($_POST['user'], FILTER_SANITIZE_STRING);
				$pass 	= filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
				$name 	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
				$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

				$formErrors = array();

				if(empty($user))
					$formErrors[] = "Username field is empty.";
				if(empty($pass))
					$formErrors[] = "Password field is empty.";
				if(empty($name))
					$formErrors[] = "Full Name field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
				}
				else {
					$stmt = $conn->prepare("INSERT INTO admins(Username, Password, FullName, Email, RegDate) 
											VALUES(?, ?, ?, ?, NOW())");
					$stmt->execute(array($user, sha1($pass), $name, $email));

					if($stmt->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Admin Added Succesfully</h3></div>";
						header('REFRESH:2;URL=admins.php?do=Manage'); // go the manage table after 2 seconds
					}
					else {
						echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
						header('REFRESH:2;URL=admins.php?do=Manage'); // go the manage table after 2 seconds
					}

				}

				echo "</div>";
			}
			else {
				echo "You can't view this page directly";
			}

		}

		// form to edit admin info
		elseif($do == 'Edit') {

			// $userid came from GET request from the edit button in the Manage section and it contains admin ID 
			$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

			$stmt= $conn->prepare("SELECT * FROM admins WHERE ID=$userid");
			$stmt->execute();

			if($stmt->rowCount() == 1) {
				$row = $stmt->fetch();
				?>

				<div class="container edit-form">
				<h3>Edit Admin:</h3>
				<form class="form-horizontal" action="admins.php?do=Update" method="POST">

					<!--START HIDDEN ID FIELD-->
						<input class="form-control" type="hidden" name="id" value="<?php echo $row['ID']?>" />
					<!--END HIDDEN ID FIELD-->

					<!--START USERNAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Username</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="user" value="<?php echo $row['Username']?>" autocomplete="off" required="required" />
						</div>
					</div>
					<!--END USERNAME FIELD-->

					<!--START PASSWORD FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Password</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="pass" placeholder="Enter new password or leave it blank" />
						</div>
					</div>
					<!--END PASSWORD FIELD-->

					<!--START FULL NAME FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="text" name="name" value="<?php echo $row['FullName']?>" required="required" />
						</div>
					</div>
					<!--END FULL NAME FIELD-->

					<!--START EMAIL FIELD-->
					<div class="form-group">
						<label class="col-sm-2 control-label">Email</label>
						<div class="col-sm-10 col-md-4">
							<input class="form-control" type="email" name="email" value="<?php echo $row['Email']?>" required="required" />
						</div>
					</div>
					<!--END EMAIL FIELD-->

					<!--START SUBMIT FIELD-->
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input class="btn btn-primary" type="submit" value="Save" />
						</div>
					</div>
					<!--END SUBMIT FIELD-->
					
				</form>
			</div>

				<?php
			}
			else {
				echo "No Such ID";
			} 

		}

		// Update information that came from the edit form and put in database
		elseif($do == 'Update') {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				
				echo "<div class='container text-center'>";

				$id 	= $_POST['id'];
				$user 	= filter_var($_POST['user'], FILTER_SANITIZE_STRING);
				$pass 	= filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
				$name 	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
				$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$hashpass = '';

				if(empty($pass)) {
					$stmt1=$conn->prepare("SELECT Password FROM admins WHERE ID=$id");
					$stmt1->execute();
					$myfetch=$stmt1->fetch();
					$hashpass = $myfetch['Password'];
				}
				else {
					$hashpass = sha1($pass);
				}


				$formErrors = array();

				if(empty($user))
					$formErrors[] = "Username field is empty.";
				if(empty($name))
					$formErrors[] = "Full Name field is empty.";
				if(empty($email))
					$formErrors[] = "Email field is empty.";

				if(!empty($formErrors)) {
					foreach($formErrors as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}
					header('REFRESH:5;URL=admins.php?do=Manage');
				}
				else {
					$stmt2 = $conn->prepare("UPDATE admins SET Username=?, Password=?, FullName=?, Email=? WHERE ID=?");
					$stmt2->execute(array($user, $hashpass, $name, $email, $id));

					if($stmt2->rowCount() == 1) {
						echo "<div class='alert alert-success'><h3>Edit Completed Succesfully</h3></div>";
						header('REFRESH:2;URL=admins.php?do=Manage');
					}
					else {
						echo "<div class='alert alert-danger'><h3>No Changes Happened</h3></div>";
						header('REFRESH:2;URL=admins.php?do=Manage');
					}

				}

				echo "</div>";

			}
			else {
				echo "You Can't view this page directly";
			}

		}

		// Delete a certain admin record
		elseif($do == 'Delete') {
			
			echo "<div class='container text-center'>";

			$userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

			$stmt1= $conn->prepare("SELECT * FROM admins WHERE ID=$userid");
			$stmt1->execute();

			if($stmt1->rowCount() == 1) {
				$stmt2 = $conn->prepare("DELETE FROM admins WHERE ID=$userid");
				$stmt2->execute();

				if($stmt2->rowCount() == 1) {
					echo "<div class='alert alert-success'><h3>Admin Deleted Succesfully</h3></div>";
					header('REFRESH:2;URL=admins.php?do=Manage');
				}
				else {
					echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
					header('REFRESH:2;URL=admins.php?do=Manage');
				}
			}
			else {
				echo "div class='alert alert-danger'>Admin not found</div>";
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

	