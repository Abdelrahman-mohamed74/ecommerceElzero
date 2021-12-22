<?php

$dsn  = "mysql:host=localhost;dbname=shopelzero";
$user = 'root';
$pass = '';

try {
	$conn = new PDO($dsn , $user , $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	

} catch (PDOException $e) {
	echo $e->getMessage();
}