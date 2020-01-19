<?php

$user = 'alumne';
$password = 'alualualu';
$db = 'echat';
$host = 'localhost';
$port = 8889;


$link = mysqli_connect($host, $user, $password, $db, $port);

if (mysqli_connect_error()) {
	
	die ("Database Connection Error");
	
}

?>