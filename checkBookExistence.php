<?php

//File that checks if a book exists already in the database

if(!isset($_SESSION)){ 
    session_start(); 
}

include_once("connect.php");

$username = $_SESSION["username"];
$tablename = $username."books";

if($_SERVER['REQUEST_METHOD']=="POST"){

	$sql = "USE Books;";
	$conn->query($sql);

	$volumeId = $_POST["volumeId"];

	$stmt = $conn->prepare("SELECT * FROM $tablename WHERE VolumeId = ?;");
	if(!$stmt){
		echo "Error preparing statement ".htmlspecialchars($conn->error);
	}
	$stmt->bind_param("s",$volumeId);
	$stmt->execute();
	$result = $stmt->get_result();	
	$stmt->close();

	if($result->num_rows>0){
		echo ("Book exists!");
	}	
	else{
		echo ("Book does not exists!");
	}

}	

?>