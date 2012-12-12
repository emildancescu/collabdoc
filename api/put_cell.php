<?php
session_start();

require('dbinfo.php');

if (!$_SESSION['user']) exit();

$userid = mysql_real_escape_string($_SESSION['user']['id']);
$docid = mysql_real_escape_string($_POST['docid']);
$cell_id = mysql_real_escape_string($_POST['cell_id']);
$content = mysql_real_escape_string($_POST['content']);

$response = array();

$query = "select * from spreadsheets where (fk_userID ='$userid'
         or id IN (select fk_sheetID from sp_rights where fk_userID='$userid')) and id='$docid'";
$result = mysql_query($query);		 

 if (mysql_num_rows($result) == 0) {
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['docname'] = "You don't have rights to edit this";
	echo json_encode($response);
	exit();
}
$result = mysql_query($query);
$files = array();

$query = "INSERT INTO sp_data (fk_sheetID, fk_userID, cell_id, content) VALUES ('$docid ', '$userid', '$cell_id', '$content')";
$result = mysql_query($query);

$response['status'] = 'OK';

echo json_encode($response);

?>