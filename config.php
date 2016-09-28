<?php
$currency = '&#xe3f; '; //Currency Character or code

$db_username = '';
$db_password = '';
$db_name = '';
$db_host = '';
					
//connect to MySql						
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);						
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
?>