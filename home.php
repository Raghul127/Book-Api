<?php

session_start();
include_once("connect.php");
include_once("createDataTable.php");

if(!isset($_SESSION["username"])){
	header('Location: login.php');
	exit();
}

if(isset($_SESSION["viewUser"])){
	unset($_SESSION["viewUser"]);
}

$_SESSION['message']="";

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>HomePage</title>
	<link rel="icon" type="image/png" href="assets/favicon.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
	<style type="text/css">
		
		html,body{
			margin: 0;
			padding: 0;
			font-family: Georgia, Helvetica, 'sans-serif';
		}

		.sticky {
			z-index: 1;
			position: fixed;
			top: 0;
			width: 100%
		}

		.sticky + .content {
 			padding-top: 60px;
		}

		a:hover{
			cursor: pointer;
		}

		.topnav{
			overflow: hidden;
			background-color: #e9e9e9;
			box-shadow: 0 1px 2px 0 rgba(0,0,0,.45);
		}

		.topnav a{
			float: left;
			display: block;
			color: black;
			text-align: center;
			padding: 15px 36px;
			text-decoration: none;
			font-size: 1.5em;
		}

		.title{
			float: none;
			margin-left: 10vw;
			text-align: center;
			font-family: 'Trebuchet MS';
			letter-spacing: 0.4em;
			font-size: 1.5em;
			padding: 6px;
			margin-right: 3vw;
		}

		.title:hover{
			cursor: pointer;
		}

		.topnav a.active{
			background-color: #c9c9c9;
		}

		.topnav a.options:hover{
			background-color: #645045;
			color: white;
		}

		.topnav .search-container{
			margin-top: 1vh;
			float: right;
			margin-right: 10vw;
		}

		.topnav select{
			min-height: 35px;
			font-size: 1.3em;
			padding: 2px;
		}

		.topnav input[type=text]{
			padding: 6px;
			font-size: 1.3em;
			min-width: 20vw;
			border: none;
			min-height: 20px;
		}

		.topnav .search-container button{
			padding: 6px 10px;
			background: #ddd;
			font-size: 1.3em;
			border: none;
			cursor: pointer;
			min-height: 20px;
		}

		.topnav .search-container button:hover{
			background: #ccc;
		}

		li{
			list-style-type: none;
		}

		.container{
			width: 65%;
			height: auto;
		}

		.card{
			padding: 0px;
			height: auto;
		}

		.liClass{
			margin-top: 20px;
			margin-bottom: 20px;
		 	overflow: auto; 
		}

		.imgDivClass{
			margin-left: 40%;
			padding: 30px;
			padding-bottom: 10px;
		}

		.titleClass{
			text-align: center;
			margin-top: 2%;
			color: #333;
			font-family: "Garamond","Helvetica Neue","Helvetica","Arial","sans-serif";
			font-weight: 900;
			font-size: 1.7em;
		}

		.byAuthClass{
			text-align: center;
		}

		.dropDivClass{
			margin-left: 74%;
			margin-top: 3%;
			font-size: 20px;
		}

		.dropdown-menu{
			overflow: scroll;
		}

		.scrollable-menu{
		    height: auto;
		    max-height: 200px;
		    overflow-x: hidden;
		}

		.no-books{
			padding: 10px;
			font-size: 1.5em;
			margin-top: 10vh;
			text-align: center;
			max-width: 30vw;
			margin-left: 35vw; 
		}

		.btn-brown{
			background: #645045;
			color: white;
		}

		#selectId{
			box-shadow: none;
		}

		.fa-thumbs-up{
			margin-top: 2%;
			margin-left: 10%;
			margin-bottom: 2%;
			cursor: pointer;
    		user-select: none;
    		color: #a2b9bc;
		}

		.liked{
			color : #645045;
		}

		#searchSuggestionsRegion{
			position: fixed;
			z-index: 1;
		}

		#searchSuggestionsRegion .container{
			width: 400px;
			height: auto;
			overflow: auto;
			padding: 5px;
		}

		.searchSuggestionsThumbnail{
			width: 50px;
			height: 60px;
		}

		.searchSuggestionsTitle{
			font-size: 15px;
		}

		.userBoxClass{
			padding: 20px;
			width: 40%;
			margin-left: 26%;
			margin-bottom: 25px;
		}

		.userBtn{
			float: right;
			margin: 5px;
		}

		.userNameDisp{
			font-size: 25px;
		}

		@media screen and (max-width: 600px) {
			.topnav .search-container {
		    	float: none;
		  	}

		  	.topnav a, .topnav input[type=text], .topnav .search-container button {
			    float: none;
			    display: block;
			    text-align: left;
			    width: 100%;
			    margin: 0;
			    padding: 14px;
		  	}

		  	.topnav input[type=text] {
		    	border: 1px solid #ccc;  
		  	}
		}

	</style>
</head>
<body>
	<div id="navbar" class="topnav">
		<a class="title" onclick="home()">Your Library</a>
		<a class="active options" href="#home" onclick="home()">Home</a>
	  	<a class="options" onclick="profile()">Profile</a>
	  	<span class="search-container">
	      	<input id="searchValue" type="text" placeholder="Search" name="search" onfocus="searchSuggestions(this.value);" onkeyup="searchSuggestions(this.value);">
	      	<select id="selectId">
	      		<option>Title</option>
	      		<option>Author</option>
	      		<option>Publisher</option>
	      		<option>ISBN</option>
	    		<option>Subject</option>
	      	</select>
	      	<button id="searchButtonId" onclick="search()"><i class="fa fa-search"></i></button>	
	  	</span>
	</div>	
	<div id="searchSuggestionsRegion" class="search-container"></div>
	<div id="activityRegion" style="margin-top: 20vh;"></div>
<script type="text/javascript">

	document.getElementById("searchSuggestionsRegion").style.left = document.getElementById("searchValue").offsetLeft-20+"px";

	window.onscroll = function() {myFunction()};

	var navbar = document.getElementById("navbar");

	var sticky = navbar.offsetTop;

	function myFunction() {
		if (window.pageYOffset >= sticky) {
		    navbar.classList.add("sticky")
		} 
		else{
		    navbar.classList.remove("sticky");
		 }
	}

</script>	
<script src="functions.js"></script>
<script src="home.js"></script>
</body>
</html>