<?php 
	
	session_start();
	
	if(isset($_SESSION['user_admin'])) {

		$pageTitle = 'Messeges';
		include 'init.php';
		include $tmpl . 'admin_navbar.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'View';

		if($do == 'View') {

			?>

				<div class="container container-table">
					<h2 class="text-center">Messages</h2>
					<div class="table-responsive">
						<table class="main-table table table-bordered text-center">
							<tr>
								<td>Doctor Username</td>
								<td>Message</td>
								<td>Options</td>
							</tr>

							<?php
								$stmt = $conn->prepare("SELECT contacts.*, doctors.* FROM contacts
														INNER JOIN doctors ON doctors.doc_id=contacts.contact_doc  
														ORDER BY contacts.contact_id DESC");
								$stmt->execute();
								$results = $stmt->fetchAll();

								foreach ($results as $oneResult) { ?>
									<tr>
										<td><?php echo $oneResult['doc_username'] ?></td>
										<td><?php echo $oneResult['contact_msg'] ?></td>
										<td>
											<a href="contacts.php?do=Delete&contid=<?php echo $oneResult['contact_id'] ?>" class="btn btn-danger btn-xs confirm"> Delete</a>
										</td>
									</tr>
									<?php	
								} 

							?>
				
						</table>
					</div>
				</div>


			<?php
		
		}

		elseif($do == 'Delete') {
			
			echo "<div class='container text-center'>";

			$contid = (isset($_GET['contid']) && is_numeric($_GET['contid'])) ? intval($_GET['contid']) : 0;

			$stmt1= $conn->prepare("SELECT * FROM contacts WHERE contact_id=$contid");
			$stmt1->execute();

			if($stmt1->rowCount() == 1) {
				$stmt2 = $conn->prepare("DELETE FROM contacts WHERE contact_id=$contid");
				$stmt2->execute();

				if($stmt2->rowCount() == 1) {
					echo "<div class='alert alert-success'><h3>Message Deleted Succesfully</h3></div>";
					header('REFRESH:2;URL=contacts.php?do=View');
				}
				else {
					echo "div class='alert alert-danger'>Something went wrong, please try again</div>";
					header('REFRESH:2;URL=contacts.php?do=View');
				}
			}
			else {
				echo "div class='alert alert-danger'>Message not found</div>";
			}
			echo "</div>";

		}

		include $tmpl . 'footer.php';
	}

