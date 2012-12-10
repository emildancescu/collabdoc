<?
$dbuser="collab";
$dbpass="collab@upb";
$dbhost="localhost";
$dbname="collabdoc";
mysql_connect($dbhost, $dbuser, $dbpass) or die ('Nu ma pot conecta la baza de date!');
mysql_select_db($dbname) or die ('Nu pot selecta baza de date!');;
//mysql_query("SET NAMES 'utf8'");
?>