<?php

	function getTitle() {

		global $pageTitle;

		if (isset($pageTitle)) 
			echo $pageTitle;

		else 
			echo "No Title";
	}
	

	function randomPassword($pass_length=8) {

    $characters = '!@#$%&^*abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $charLengths = strlen($characters) - 1;

    for ($i = 0; $i < $pass_length; $i++) {
        $n = rand(0, $charLengths); // choose one character from $characters 
        $pass[] = $characters[$n]; // put the chosen character in pass array
    }

    return implode($pass); //turn the array into a string
}