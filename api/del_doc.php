<?php

session_start();

require('dbinfo.php');

// check if the user is logged in and stop the request if he is not
if ($_SESSION['user'] == null)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['email'] = "You must be logged in to share this document";
	
	echo json_encode($response);
	exit();
}

$docId = mysql_real_escape_string($_GET['docID']);
$ownerId = 0;

// check to see if the specified document exists
$query = "SELECT * FROM spreadsheets WHERE id=" . $docId;
$result = mysql_query($query);

if (mysql_num_rows($result) == 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docID'] = "Internal error: There is no document with that docID";
	
	echo json_encode($response);
	exit();
}

// check to see if the user has rights to delete the document
$hasRights = 0;
while ($row = mysql_fetch_assoc($result))
{
	$ownerId = $row['fk_userID'];

	// if the user is not the owner of the document
	if ($_SESSION['user']['id'] != $row['fk_userID'])
	{
		// check the sp_rights table to see if the user was previously given rights on the document
		$rightsQuery = "SELECT * FROM sp_rights WHERE fk_userID=" . $_SESSION['user']['id'] . " AND fk_sheetID=" . $docId;
		$rightsResult = mysql_query($rightsQuery);
		
		// if we found that the document is shared with the user
		if (mysql_num_rows($rightsResult) > 0)
		{
			$hasRights = 1;
		}
	}
	// if the user is the owner of the document
	else
	{
		$hasRights = 1;
	}
}

// if the user doesn't have the rights to delete the document, show an error message
if ($hasRights == 0)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docID'] = "You do not have sufficient rights to delete this document";
	
	echo json_encode($response);
	exit();
}

// remove the document
$query = "DELETE FROM spreadsheets WHERE id=" . $docId;
$result = mysql_query($query);

// remove all shared rights on the document
$query = "DELETE FROM sp_rights WHERE fk_sheetID=" . $docId;
$result = mysql_query($query);

// remove all data related to the document
$query = "DELETE FROM sp_data WHERE fk_sheetID=" . $docId;
$result = mysql_query($query);

// notify of success via json
$response = array();
$response['status'] = "ok";

echo json_encode($response);

?>