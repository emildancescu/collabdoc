<?php
session_start();

require('dbinfo.php');

// check if the user is logged in and stop the request if he is not
if ($_SESSION['user'] == null)
{
	$response = array();
	$response['status'] = 'error';
	$response['errors'] = array();
	$response['errors']['email'] = "You must be logged in!";
	
	echo json_encode($response);
	exit();
}

$userid = $_SESSION['user']['id'];

//auto escape GET and POST data
function array_map_recursive($callback, $array) {
	$r = array();
	if (is_array($array)) {
		foreach($array as $key => $value) {
			$r[$key] = is_scalar($value) ? $callback($value) : array_map_recursive($callback, $value);
		}
	}
	return $r;
}

if (!get_magic_quotes_gpc()) {
	$_GET = array_map_recursive('mysql_real_escape_string', $_GET); 
	$_POST = array_map_recursive('mysql_real_escape_string', $_POST); 
} else {  
	$_GET = array_map_recursive('stripslashes', $_GET); 
	$_POST = array_map_recursive('stripslashes', $_POST); 

	$_GET = array_map_recursive('mysql_real_escape_string', $_GET); 
	$_POST = array_map_recursive('mysql_real_escape_string', $_POST); 
}

