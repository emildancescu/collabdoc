<?php

session_start();

require('dbinfo.php');

$email = mysql_real_escape_string($_POST['email']);
$pass = mysql_real_escape_string($_POST['pass']);
$pass_conf = mysql_real_escape_string($_POST['pass_conf']);
$name = mysql_real_escape_string($_POST['name']);

// if any user input is missing or invalid, show an error message
if ($email == "" || $pass == "" || $pass_conf == "" || $name == "" || $pass_conf != $pass)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	
	if ($email == "")
		$response['errors']['email'] = "You must provide an e-mail address";
	
	if ($pass == "")
		$response['errors']['pass'] = "You must provide a password";
		
	if ($pass_conf == "" && $pass != "")
		$response['errors']['pass_conf'] = "You must provide a password confirmation";	
	
	if ($pass_conf != $pass && $pass != "" && $pass_conf != "")
		$response['errors']['pass_conf'] = "The password confirmation does not match the initial password";
		
	if ($name == "")
		$response['errors']['name'] = "Your full name is required to register an account";
		
	echo json_encode($response);
	exit();
}


// check if the email address is already registered
$query = "SELECT * FROM users WHERE email='" . $email . "'";
$result = mysql_query($query);

// if there's already an user registered with that email
if (mysql_num_rows($result) > 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['email'] = "That e-mail address is already registered";
	
	echo json_encode($response);
	exit();
}

// hash the password for use below
$hashedPass = md5($pass);

// insert the data for the new account in the database
$query = "INSERT INTO users (email, pass, fullname) VALUES ('" . $email . "', '" . $hashedPass . "', '" . $name . "')";
$result = mysql_query($query);

// retrieve data for the account (need to do this because we don't have the user id)
$query = "SELECT * FROM users WHERE email='" . $email . "'";
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result))
{
	// store the account data in the SESSION global var
	$user_data = array();
	$user_data['email'] = $row['email'];
	$user_data['id'] = $row['id'];
	$user_data['fullname'] = $row['fullname'];
}

$_SESSION['user'] = $user_data;

// notify of success via json
$response = array();
$response['status'] = "ok";

echo json_encode($response);

?>