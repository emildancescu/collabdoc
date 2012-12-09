<?php
session_start();

require('dbinfo.php');

if (!$_SESSION['user']) exit();
$userid = mysql_real_escape_string($_SESSION['user']['id']);

$response = array();
$response['status'] = 'OK';

$query = "select * from spreadsheets where fk_userID ='$userid'
         or id=(select fk_sheetID from sp_rights where fk_userID='$userid')
		 ORDER BY data ASC";
$result = mysql_query($query);
$files = array();

while ($row = mysql_fetch_assoc($result))
{
	$temp = array();
	$temp['id'] = $row['id'];
	$temp['docname'] = $row['docname'];
	$temp['data'] = $row['data'];
	if ($row['fk_userID'] == $userid)
		$temp['owner'] = 'YES';
	else
		$temp['owner'] = 'NO';
	$files[] = $temp;
}

$response['files'] = $files;

echo json_encode($response);

?>