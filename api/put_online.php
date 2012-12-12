<?php

include('global.inc.php');

function randomColor() {
	$rand = array('a', 'b', 'c', 'd', 'e', 'f');
    return '#'.$rand[rand(0,5)].$rand[rand(0,5)].$rand[rand(0,5)].$rand[rand(0,5)].$rand[rand(0,5)].$rand[rand(0,5)];
}

$docid = $_GET['docid'];
$cellid = $_GET['cellid'];
$locked = $_GET['locked'];

$query = "SELECT id FROM online WHERE fk_userID=$userid AND fk_sheetID=$docid ORDER BY id DESC LIMIT 1";
$result = mysql_query($query);

$id = '';

if ($result && mysql_num_rows($result) > 0) {
	$id = mysql_result($result, 0);
}

if ($id != '') {
	
	$query = "UPDATE online SET cell_id='$cellid', locked='$locked' WHERE id=$id";
	$result = mysql_query($query);
	
} else {

	$color = randomColor();
	
	$query = "INSERT INTO online (fk_userID, fk_sheetID, color, cell_id, locked) VALUES ('$userid', '$docid', '$color', '$cellid', '$locked')";
	$result = mysql_query($query);
	
}





?>