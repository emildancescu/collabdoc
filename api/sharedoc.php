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
	$response['errors']['email'] = "There is no user registered with that e-mail address";
	
	echo json_encode($response);
	exit();
}

// we managed to retrieve user information for the given e-mail, extract the user's ID
while ($row = mysql_fetch_assoc($result))
{
	// store the account data in the SESSION global var
	$userId = $row['id'];
}

// check if the email address already has rights on the specified document
$query = "SELECT * FROM sp_rights WHERE fk_userID=" . $userId . " AND fk_sheetID=" . $docId;
$result = mysql_query($query);

// if the user already has rights on this document, show an error message
if (mysql_num_rows($result) > 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['email'] = "The document is already shared with this user";
	
	echo json_encode($response);
	exit();
}

// if we've gotten this far, insert a new right for the given user on the given document
$query = "INSERT INTO sp_rights (fk_userID, fk_sheetID) VALUES (" . $userId . ", " . $docId . ")";
$result = mysql_query($query);

// notify of success via json
$response = array();
$response['status'] = "ok";

echo json_encode($response);

?>