<?php
	session_start();
	require ("connect.php");
	
	$fname = mysql_real_escape_string($_POST['fname']);
	$lname = mysql_real_escape_string($_POST['lname']);
	$email = mysql_real_escape_string($_POST['email']);
	$phone = mysql_real_escape_string($_POST['phone']);
	$password = $_POST['password'];
	
	// Checking to see if the email is already in the database
	$result = mysql_query("SELECT * FROM users WHERE email = '".$email."'");
	$num = mysql_num_rows($result);
	
	if ($num != 0) {
		header('Location: ../register.php?errorString=User already exists with this email!');
	} else {
		$sql = "INSERT INTO users (fname, lname, email, phone, password) VALUES ('$fname', '$lname', '$email', '$phone', MD5('$password')) ";
		$result = mysql_query($sql);
		header('Location: ../index.php?errorString=<h3 class="text-info">Register Complete. Please Login..</h3>');
	}

?>