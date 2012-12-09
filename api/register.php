<?php

require('dbinfo.php');

$email = mysql_real_escape_string($_POST['email']);
$pass = mysql_real_escape_string($_POST['pass']);
$pass_conf = mysql_real_escape_string($_POST['pass_conf']);
$name = mysql_real_escape_string($_POST['name']);


$user_data['email']
$user_data['id']
$user_data['name']

$_SESSION['user'] = $user_data;

$query = "";
$result = mysql_query($query);

mysql_num_rows



while ($row = mysql_fetch_assoc($result)) {

	

}

$response = array();

$response['status'] = 'error';
$response['errors']['email'] = 'Adresa exista deja in baza de date';

echo json_encode($response);




// check that all strings are valid
if ($email == "" || $pass == "" || $pass_conf == "" || $name == "")
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	
	if ($email == "")
		$response['errors']['email'] = "You must provide an username";
	
	if ($pass == "")
		$response['errors']['pass'] = "You must provide a password";
		
	if ($pass_conf == "")
		$response['errors']['pass'] = "You must provide a password confirmation";
		
	if ($name == "")
		$response['errors']['name'] = "Your full name is required to register an account";
}
else
{
// check if the email address is already registered
$query = "SELECT * FROM users WHERE email=" + $email;
	//$result = 
	}

?>