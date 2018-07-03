<?php

//File that saves book data in database
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

	if($_POST["purpose"]=="aClickAdd"){

		$volumeId = $_POST['volumeId'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$imgLink = $_POST['imgLink'];
		$status = $_POST['status'];
		$likeStatus = "no";

		$stmt = $conn->prepare("SELECT * FROM $tablename WHERE VolumeId = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("s",$volumeId);
		$stmt->execute();
		$result = $stmt->get_result();	
		$stmt->close();

		if($result->num_rows>0){
			while($row = $result->fetch_assoc()){

				$stmt = $conn->prepare("UPDATE $tablename SET Status=? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$status,$volumeId);
				$stmt->execute();
				$stmt->close();

				$activity = $username." has added ".$title." to his ".$status." collection.";
				$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$activity,$volumeId);
				$stmt->execute();
				$stmt->close();					

			}
		}	

		else{

			$activity = $username." has added ".$title." to his ".$status." collection.";
			$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
			if(!$stmt){
				echo "Error preparing statement ".htmlspecialchars($conn->error);
			}
			$stmt->bind_param("ss",$activity,$volumeId);
			$stmt->execute();
			$stmt->close();	

			$stmt = $conn->prepare("INSERT INTO $tablename(VolumeId,Title,Author,ImgLink,Liked,Status) "."VALUES (?,?,?,?,?,?);");
			if(!$stmt){
				echo "Error preparing statement ".htmlspecialchars($conn->error);
			}
			$stmt->bind_param("ssssss",$volumeId,$title,$author,$imgLink,$likeStatus,$status);
			$stmt->execute();
			$stmt->close();

		}		

	}

	else if($_POST["purpose"]=="adClickAdd"){

		$volumeId = $_POST['volumeId'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$imgLink = $_POST['imgLink'];
		$column = $_POST['columnName'];

		$stmt = $conn->prepare("SELECT * FROM $tablename WHERE VolumeId = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("s",$volumeId);
		$stmt->execute();
		$result = $stmt->get_result();	
		$stmt->close();

		if($result->num_rows>0){
			while($row = $result->fetch_assoc()){

				$activity = $username." has added ".$title." to his ".$column." collection.";

				if($row[$column]=="yes"){
					$status = "no";
					$activity = $username." has removed ".$title." from his ".$column." collection.";
				}

				else{
					$status = "yes";
					$activity = $username." has added ".$title." to his ".$column." collection.";
				}

				$stmt = $conn->prepare("UPDATE $tablename SET $column = ? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$status,$volumeId);
				$stmt->execute();		
				$stmt->close();	

				$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$activity,$volumeId);
				$stmt->execute();
				$stmt->close();			

			}
		}


		else{

				$likeStatus = "no";

				$stmt = $conn->prepare("INSERT INTO $tablename(VolumeId,Title,Author,ImgLink,Liked,Status) "."VALUES (?,?,?,?,?,?);");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ssssss",$volumeId,$title,$author,$imgLink,$likeStatus,$status);
				$stmt->execute();
				$stmt->get_result();

				$stmt->close();

				$activity = $username." has added ".$title." to his ".$column." collection. ";
				$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$activity,$volumeId);
				$stmt->execute();
				$stmt->close();

		}		

	}


	else if($_POST["purpose"]=="addShelf"){

		$shelfName = $_POST["shelfName"];

		$stmt = $conn->prepare("ALTER TABLE $tablename ADD $shelfName VARCHAR(500);");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->execute();
		$stmt->close();

		$shelfName.="%";

		$stmt = $conn->prepare("UPDATE user SET Shelves=concat(Shelves,?) WHERE username = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("ss",$shelfName,$username);
		$stmt->execute();
		$stmt->close();

	}

	else if($_POST["purpose"]=="likeUpdate"){

		$volumeId = $_POST['volumeId'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$imgLink = $_POST['imgLink'];
		$likeStatus = $_POST['likeStatus'];
		$column = $_POST['columnName'];

		$stmt = $conn->prepare("SELECT * FROM $tablename WHERE VolumeId = ?;");
		if(!$stmt){
			echo "Error preparing statement ".htmlspecialchars($conn->error);
		}
		$stmt->bind_param("s",$volumeId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();	

		if($result->num_rows>0){
			while($row = $result->fetch_assoc()){

				if($row[$column]=="yes"){
					$likeStatus = "no";
				}

				else{
					$likeStatus = "yes";
					$activity = $username." liked ".$title.".";
					$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
					if(!$stmt){
						echo "Error preparing statement ".htmlspecialchars($conn->error);
					}
					$stmt->bind_param("ss",$activity,$volumeId);
					$stmt->execute();
					$stmt->close();	
				}

				$stmt = $conn->prepare("UPDATE $tablename SET $column = ? WHERE VolumeId = ?;");
				if(!$stmt){
					echo "Error preparing statement ".htmlspecialchars($conn->error);
				}
				$stmt->bind_param("ss",$likeStatus,$volumeId);
				$stmt->execute();		
				$stmt->close();		

			}
		}

		else{

			$stmt = $conn->prepare("INSERT INTO $tablename(VolumeId,Title,Author,ImgLink,Liked) "."VALUES (?,?,?,?,?);");
			$stmt->bind_param("sssss",$volumeId,$title,$author,$imgLink,$likeStatus);
			$stmt->execute();
			$stmt->get_result();
			$stmt->close();

			$activity = $username." liked ".$title.".";
			$stmt = $conn->prepare("UPDATE $tablename SET Activity=? WHERE VolumeId = ?;");
			if(!$stmt){
				echo "Error preparing statement ".htmlspecialchars($conn->error);
			}
			$stmt->bind_param("ss",$activity,$volumeId);
			$stmt->execute();
			$stmt->close();

		}		

	}
		
}

?>