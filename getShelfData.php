<?php

//File that gets specific shelf data in database and returns to profile.js
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

	if($_POST['purpose']=="aShelfClick"){

		$status = $_POST['status'];

		$stmt = $conn->prepare("SELECT * FROM $tablename WHERE Status = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("s",$status);
		$stmt->execute();
		$result = $stmt->get_result();

		$bookData = array();

		if($result->num_rows>0){
			while($row = $result->fetch_assoc()){

				$r = array('VolumeId'=>$row["VolumeId"],'Title'=>$row["Title"],'Author'=>$row["Author"],'ImgLink'=>$row["ImgLink"],'Liked'=>$row["Liked"]);
				array_push($bookData,$r);	
			}
		}	
		echo json_encode($bookData);
		$stmt->close();
	}

	else if($_POST['purpose']=="adShelfClick"){

		$columnName = $_POST['columnName'];
		$status = $_POST['status'];

		$stmt = $conn->prepare("SELECT * FROM $tablename WHERE $columnName = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("s",$status);
		$stmt->execute();
		$result = $stmt->get_result();

		$bookData = array();

		if($result->num_rows>0){
			while($row = $result->fetch_assoc()){

				$r = array('VolumeId'=>$row["VolumeId"],'Title'=>$row["Title"],'Author'=>$row["Author"],'ImgLink'=>$row["ImgLink"],'Liked'=>$row["Liked"]);
				array_push($bookData,$r);	
			}
		}	
		echo json_encode($bookData);	
		$stmt->close();	
	}
		
}

?>