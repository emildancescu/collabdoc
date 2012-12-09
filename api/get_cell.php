<?php
session_start();

require('dbinfo.php');

if (!$_SESSION['user']) exit();

$userid = mysql_real_escape_string($_SESSION['user']['id']);
$docid = mysql_real_escape_string($_POST['docid']);
$initial = mysql_real_escape_string($_POST['initial']);

if ($initial == 'NO') {
	$timestamp = mysql_real_escape_string($_POST['timestamp']);
}

$response = array();

$query = "select * from spreadsheets where (fk_userID ='$userid'
         or id=(select fk_sheetID from sp_rights where fk_userID='$userid')) and id='$docid'";
$result = mysql_query($query);		 

 if (mysql_num_rows($result) == 0) {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You don't have rights to get this";
	echo json_encode($response);
	exit();
}

$result = mysql_query($query);
$cells = array();

if ($initial == 'NO') {
	$query = "Select * from sp_data where data>'$timestamp' and fk_sheetID='$docid' order by id DESC group by cell_id";
} else {
	$query = "";
}
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result))
{
	$temp = array();
	$temp['cell_id'] = $row['cell_id'];
	$temp['content'] = $row['content'];
	$temp['data'] = $row['data'];
	$cells[] = $temp;
}

$response['status'] = 'OK';
$respine['cells'] = $cells;

echo json_encode($response);

?>