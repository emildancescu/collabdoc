<?php

session_start();

require('dbinfo.php');

$email = mysql_real_escape_string($_POST['email']);
$pass = mysql_real_escape_string($_POST['pass']);

// if any user input is missing or invalid, show an error message
if ($email == "" || $pass == "")
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	
	if ($email == "")
		$response['errors']['email'] = "You must provide an e-mail address";
	
	if ($pass == "")
		$response['errors']['pass'] = "You must provide a password";
	
	echo json_encode($response);
	exit();
}

// check if the email address is registered and check if the user gave the correct password
$query = "SELECT * FROM users WHERE email='" . $email . "' AND pass='" . $pass . "'";
$result = mysql_query($query);

// if there are no users registered with that e-mail or the password is invalid, show an error message
if (mysql_num_rows($result) == 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['email'] = "Invalid e-mail or password";
	
	echo json_encode($response);
	exit();
}

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