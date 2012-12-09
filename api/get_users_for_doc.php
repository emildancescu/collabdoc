<?php

session_start();

require('dbinfo.php');

// check if the user is logged in and stop the request if he is not
if ($_SESSION['user'] == null)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docID'] = "You must be logged in to perform this action";
	
	echo json_encode($response);
	exit();
}

$docId = mysql_real_escape_string($_POST['docID']);

// if any user input is missing or invalid, show an error message
if ($docId == "")
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	
	if ($docId == "")
		$response['errors']['docID'] = "Internal error: Missing document ID";
	
	echo json_encode($response);
	exit();
}

// check if the specified document exists
$query = "SELECT * FROM spreadsheets WHERE id=" . $docId;
$result = mysql_query($query);

if (mysql_num_rows($result) == 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docID'] = "Internal error: There is no document with that document ID";
	
	echo json_encode($response);
	exit();
}

$ownerData = array();

// retrieve the owner of the document
while ($row = mysql_fetch_assoc($result))
{
	$ownerId = $row['fk_userID'];
	
	// retrieve information about the owner
	$userQuery = "SELECT * FROM users WHERE id=" . $ownerId;
	$userResult = mysql_query($userQuery);
	
	// parse information about that user
	while ($userRow = mysql_fetch_assoc($userResult))
	{
		$ownerData['email']	= $userRow['email'];
		$ownerData['fullname'] = $userRow['fullname'];
	}
}

// prepare the initial part of the response
$response = array();
$response['owner'] = $ownerData;

$response['users'] = array();

// retrieve all of the users that have access to this document and place them inside the response array
$query = "SELECT * FROM sp_rights WHERE fk_sheetID=" . $docId;
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result))
{
	$newUser = array();
	
	// retrieve user information for that user
	$userQuery = "SELECT * FROM users WHERE id=" . $row['fk_userID'];
	$userResult = mysql_query($userQuery);
	
	// parse information about that user
	while ($userRow = mysql_fetch_assoc($userResult))
	{
		$newUser['email'] = $userRow['email'];
		$newUser['fullname'] = $userRow['fullname'];
	}
	
	// add the new information to the response structure
	array_push($response['users'], $newUser);
}

echo json_encode($response);

?>