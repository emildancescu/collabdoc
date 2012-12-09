<?php

session_start();

$userEmail = mysql_real_escape_string($_POST['email']);
$docId = mysql_real_escape_string($_POST['docID']);

// if any user input is missing or invalid, show an error message
if ($userEmail == "" || $docId == "")
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	
	if ($email == "")
		$response['errors']['email'] = "You must provide an e-mail address with which to share the document";
	
	if ($docId == "")
		$response['errors']['docID'] = "Internal error: Missing document ID";
	
	echo json_encode($response);
	exit();
}

// retrieve the ID associated with the specified e-mail
$query = "SELECT id FROM users WHERE email='" . $email . "'";
$result = mysql_query($query);

// if the specified e-mail is not an registered user, show an error message
if (mysql_num_rows($result) == 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['error'];
}

// check if the email address already has rights on the specified document
$query = "SELECT * FROM sp_rights WHERE fk_='" . $email . "' AND pass='" . $pass . "'";
$result = mysql_query($query);

?>