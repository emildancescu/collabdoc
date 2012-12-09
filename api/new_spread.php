<?php

require('dbinfo.php');

if (!$_SESSION['user']) exit();

$userid = mysql_real_escape_string($_SESSION['user']['name']);
$spreadsheet_name = mysql_real_escape_string($_POST['name']);
$response = array();

if ($spreadsheet_name == "") {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['speardsheet_name'] = "You must provide a name";
}

//Check if user already has a spreadsheet with that name
$query = "Select id from SPREADSHEETS where fk_userid = " + $userid + "and spreadname is '" + $spreadsheet_name + "'";
$result = mysql_query($query);
if (mysql_num_rows($result)) {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['speardsheet_name'] = "You already have a spreadsheet with that name";
}


$query = "INSERT INTO SPREADSHEETS ('fk_userid', 'spreadname') VALUES('" + $spreadsheet_name + " , " + $userid + "')";
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
	$id = $row[id];
}

$response['status'] = 'OK';
$response['id'] = $id;
$response['spreadsheet_name'] = $spreadsheet_name;


echo json_encode($response);

?>