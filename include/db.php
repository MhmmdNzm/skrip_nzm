<?php
$host = 'localhost';
$db = 'saw_security';
$user = 'root';
$pass = '';


$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
die('Connect Error: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8');