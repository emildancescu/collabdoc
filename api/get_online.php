<?php

include('global.inc.php');

$docid = $_GET['docid'];
$timestamp = $_GET['timestamp'];

$query = "	SELECT cell_id, color, fullname 
			FROM online 
			LEFT JOIN users ON users.id=online.fk_userID
			WHERE fk_userID<>$userid AND fk_sheetID=$docid AND TIMESTAMPDIFF(MINUTE,time,NOW()) < 15";
$result = mysql_query($query);

//if (!$result) exit();

$response['cells'] = array();

while ($row = mysql_fetch_assoc($result)) {
	$response['cells'][] = $row;
}


//get cells
$query = "	SELECT cell_id, content, data 
			FROM sp_data 
			WHERE id IN (	SELECT MAX(id) 
							FROM sp_data 
							WHERE fk_sheetID=$docid AND data>'$timestamp' 
							GROUP BY cell_id	)
			ORDER BY data DESC	";
			
$result = mysql_query($query);

$response['data'] = array();

while ($row = mysql_fetch_assoc($result)) {
	$response['data'][] = $row;
}


echo json_encode($response);

?>