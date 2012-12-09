<?php
session_start();

require('dbinfo.php');

if (!$_SESSION['user']) exit();

$userid = mysql_real_escape_string($_SESSION['user']['id']);
$docname = mysql_real_escape_string($_POST['docname']);

$response = array();

if ($docname == "") {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You must provide a name";
	echo json_encode($response);
	exit();
}

//Check if user already has a spreadsheet with that name
$query = "Select id from spreadsheets where fk_userID = '$userid' and docname = '$docname'";
$result = mysql_query($query);
if (mysql_num_rows($result) > 0) {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You already have a spreadsheet with that name";
	echo json_encode($response);
	exit();
}

$query = "INSERT INTO spreadsheets (fk_userID, docname) VALUES ('$userid', '$docname')";
$result = mysql_query($query);

$response['status'] = 'OK';

echo json_encode($response);

?>