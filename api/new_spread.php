<?php

require('dbinfo.php');
/*
if (!$_SESSION['user']) exit();

$userid = mysql_real_escape_string($_SESSION['user']['id']);
$docname = mysql_real_escape_string($_POST['docname']);
*/
$response = array();

$userid = $_GET['userid'];
$docname = $_GET['docname'];

if ($spreadsheet_name == "") {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You must provide a name";
	echo json_encode($response);
	exit();
}

//Check if user already has a spreadsheet with that name
$query = "Select id from SPREADSHEETS where fk_userid = " + $userid + "and spreadname is '" + $docname + "'";
$result = mysql_query($query);
if (mysql_num_rows($result)) {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You already have a spreadsheet with that name";
	echo json_encode($response);
	exit();
}

$query = "INSERT INTO SPREADSHEETS ('fk_userid', 'spreadname') VALUES('" + $docname + " , " + $userid + "')";
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
	$id = $row[id];
}

$response['status'] = 'OK';
$response['id'] = $id;
$response['docname'] = $docname;

echo json_encode($response);

?>