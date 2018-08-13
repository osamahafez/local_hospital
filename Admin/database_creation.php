<?php

	$pageTitle='Create Database';
	$css = "Layouts/css/";
	$jquery = "Layouts/jquery/";
	include 'Includes/Functions/functions.php';
	include 'Includes/Templates/header.php';

	echo "<div class='container text-center db-creation'>";

	$servername = "localhost";
	$username = "root";
	$password = "";

	// Create local_hospital Database
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql1 = "CREATE DATABASE local_hospital CHARACTER SET utf8 COLLATE utf8_general_ci";
	    
	    $connect->exec($sql1);
	    echo "<div class='alert alert-success'> \"local_hospital\" Database Created Successfully</div>";
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create admins Table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql2 = "CREATE TABLE local_hospital.admins( 
		    										ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,  
		    										Username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE, 
		    										Password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		    										FullName VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		    										Email VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		    										RegDate DATE NOT NULL    
	    																	)	    										 
	    			ENGINE = InnoDB 
	    			CHARSET=utf8 COLLATE utf8_general_ci";
	     
	    $connect->exec($sql2);
	    echo "<div class='alert alert-success'> \"admins\" Table Created Successfully</div>";    
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create doctors Table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql3 = "CREATE TABLE local_hospital.doctors(
	    											doc_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	    											doc_username VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE,
	    											doc_password VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_gender ENUM('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_phone VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_address VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_email VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_field VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_bio TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_pic VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	    											doc_date DATE NOT NULL
																			)
					ENGINE = InnoDB
					CHARSET = utf8 COLLATE utf8_general_ci";

	    $connect->exec($sql3);
	    echo "<div class='alert alert-success'> \"doctors\" Table Created Successfully</div>";
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create appointments Table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql4 = "CREATE TABLE local_hospital.appointments(
														app_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
														app_patient VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
														app_gender ENUM('M','F') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
														app_email VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
														app_msg TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
														app_resDate DATE NOT NULL COMMENT 'Reservation Date',
														app_date DATE NOT NULL COMMENT 'Appoinment Date',
														app_doctorID INT NOT NULL
																					)
					ENGINE  = InnoDB
					CHARSET = utf8 COLLATE utf8_general_ci";

		$connect->exec($sql4);
	    echo "<div class='alert alert-success'> \"appointments\" Table Created Successfully</div>";
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create contacts Table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql5 = "CREATE TABLE local_hospital.contacts(
														contact_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
														contact_msg TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
														contact_doc INT NOT NULL									
																					)
					ENGINE = InnoDB
					CHARSET = utf8 COLLATE utf8_general_ci";

		$connect->exec($sql5);
	    echo "<div class='alert alert-success'> \"contacts\" Table Created Successfully</div>";
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create connection between appointments table and doctors table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql6 = "ALTER TABLE local_hospital.appointments ADD CONSTRAINT app_1 
					FOREIGN KEY (app_doctorID) REFERENCES local_hospital.doctors(doc_id) 
					ON DELETE CASCADE 
					ON UPDATE CASCADE";

		$connect->exec($sql6);
	    echo "<div class='alert alert-success'> \"appointments\" Foreign Key Added Successfully</div>";
	    
	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Create connection between contacts table and doctors table
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql7 = "ALTER TABLE local_hospital.contacts ADD CONSTRAINT cont_1
					FOREIGN KEY(contact_doc) REFERENCES local_hospital.doctors(doc_id)
					ON DELETE CASCADE 
					ON UPDATE CASCADE";
	   	

	   	$connect->exec($sql7);
	    echo "<div class='alert alert-success'> \"contacts\" Foreign Key Added Successfully</div>";

	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}

	// Insert a row in admins database username=admin and password=admin
	try {
	    $connect = new PDO("mysql:host=$servername", $username, $password);
	    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql8 = "INSERT INTO local_hospital.admins(Username, Password, FullName, Email, RegDate)
					VALUES('admin', sha1('admin'), 'admin', 'admin@admin.com', NOW())";
	    
	    $connect->exec($sql8);
	    echo "<div class='alert alert-success'><strong>Username:</strong> admin, <strong>Password:</strong> admin</div>";

	}
	catch(PDOException $e) {
	    echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
	}


	$connect = null; // end connection

	echo "<a class='btn btn-default' href='index.php'>Finish</a>";

	echo "</div>";
	include 'Includes/Templates/footer.php';