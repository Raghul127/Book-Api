<?php

if(!isset($_SESSION)){ 
    session_start(); 
}

if(!isset($_SESSION["username"])){
	header('Location: login.php');
	exit();
}

include_once("connect.php");
$username = $_SESSION["username"];

$sql = "USE Books;";
$conn->query($sql);

$tableName = $username."books";

$sql = "CREATE TABLE IF NOT EXISTS $tableName(
		id INT(100) NOT NULL AUTO_INCREMENT,
		VolumeId VARCHAR(500) NOT NULL,
		Title VARCHAR(500) NOT NULL,
		Author VARCHAR(500) NOT NULL, 
		ImgLink VARCHAR(500),
		Activity VARCHAR(500),
		Expires DATETIME,
		Liked VARCHAR(500),
		Status VARCHAR(500),
		Favourites VARCHAR(500),
		PRIMARY KEY (id,VolumeId)
		)";
$result = $conn->query($sql);

if (!$result) {
	trigger_error('Invalid query: ' . $conn->error);
}	

?>