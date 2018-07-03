<?php

//File that gets all books data in database
if(!isset($_SESSION)){ 
    session_start(); 
} 

if(!isset($_SESSION["username"])){
	header('Location: login.php');
	exit();
}

include_once("connect.php");
$_SESSION['message']="";

$username = $_SESSION["username"];
$tablename = $username."books";

if($_SERVER['REQUEST_METHOD']=="POST"){

	$sql = "USE Books;";
	$conn->query($sql);

	$stmt = $conn->prepare("SELECT * FROM $tablename");
	if(!$stmt){
		echo "Error preparing statement ".htmlspecialchars($conn->error);
	}
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$bookData = array();

	if($result->num_rows>0){
		while($row = $result->fetch_assoc()){

			$r = array('VolumeId'=>$row["VolumeId"],'Title'=>$row["Title"],'Author'=>$row["Author"],'ImgLink'=>$row["ImgLink"],'Activity'=>$row["Activity"],'Liked'=>$row["Liked"],'Status'=>$row["Status"]);
			array_push($bookData,$r);	
		}
	}	
	echo json_encode($bookData);
}

?>