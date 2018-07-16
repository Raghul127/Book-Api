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
	
	<title>Profile</title>
	<link rel="icon" type="image/png" href="assets/favicon.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

		a:hover{
			cursor: pointer;
		}

		.sidenav{
		    height: 90%;
		    min-width: 13vw;
		    z-index: 1;
		    position: fixed;
		    top: 8%;
		    left: 0;
		    background-color: lightblue;
		    overflow-x: hidden;
		    padding-top: 1.97vh;   
		    box-shadow: 0 1px 2px 0 rgba(0,0,0,.45); 
		}

		.labelsOPT{
			padding: 6px 8px 6px 16px;
		    color: #818181;
		    display: block;
			margin-top: 16%;
			font-family: 'Comic Sans MS';
			font-size: 1.4em;
			font-weight: bold;
			color: darkred;
			padding-bottom: 15px;
		}

		.labelLinks{
			font-family: 'Comic Sans MS';
		}

		.sidenav a {
		    padding: 6px 8px 6px 16px;
		    text-decoration: none;
		    font-size: 1.4em;
		    color: #818181;
		    display: block;
		}

		.sidenav a.sidenavlinks:hover{
		    background-color: blue;
			color: white;
		}

		.main {
			margin-top: 50px;
		    margin-left: 240px;
		    padding: 0px 10px;
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

		.active{
		    background-color: #2980B9;
		}

		.topnav{
			overflow: hidden;
			background-color: lightblue;
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
			background-color: #2980B9;
		}

		.topnav a.options:hover{
			background-color: blue;
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

		.modal{
		    display: none;
		    position: fixed;
		    z-index: 1;
		    padding-top: 27vh;
		    left: 0;
		    top: 0;
		    width: 100%;
		    height: 100%;
		    overflow: auto;/* Enable scroll if needed */
		    background-color: rgb(0,0,0); /* Fallback color */
		    background-color: rgba(0,0,0,0.4);
		}

		.modal-content{
			overflow: auto;
		    position: relative;
		    background-color: #fefefe;
		    margin: auto;
		    padding: 0;
		    border-radius: 10px;
		    width: 38%;
		    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
		    -webkit-animation-name: animatetop;
		    -webkit-animation-duration: 0.4s;
		    animation-name: animatetop;
		    animation-duration: 0.4s
		}

		@-webkit-keyframes animatetop {
		    from{top:-300px; opacity:0} 
		    to {top:0; opacity:1}
		}

		@keyframes animatetop {
		    from {top:-300px; opacity:0}
		    to {top:0; opacity:1}
		}

		.close{
		    color: white;
		    float: right;
		    font-size: 28px;
		    font-weight: bold;
		}

		.close:hover,.close:focus{
		    color: #000;
		    text-decoration: none;
		    cursor: pointer;
		}

		.modal-header{
			border-radius: 10px;
		    padding: 2px 16px;
		    background-color: lightblue;
		    color: black;
		}

		.modal-body{
			padding: 2px 16px;
		}

		.inputClass{
			width: auto;
			height: auto;
		}

		#shelfInputId{
			min-width: 100%;
			min-height: 40px;
			max-width: 100%;
			margin-top: 20px;
			margin-bottom: 10px;
			border-radius: 3px;
			font-family: "Comic Sans MS";
			font-size: 1.6em;
		}

		#submitInputId{
			margin-top: 25px;
			margin-bottom: 10px;
			margin-left: 48%;
			border-radius: 3px;
			font-family: "Sofia";
			font-size: 1.2em;
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

		.activityText{
			margin-left: 6%;
			margin-bottom: -1%;
			font-style: italic; 
			font-weight: 700;
			font-size: 1.3em;
		}

		.shareButton{
			margin-left: 70%;
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

		.switch{
		  position: relative;
		  display: inline-block;
		  width: 60px;
		  height: 34px;
		  margin-left: 30%;
		  margin-top: 5px;
		}

		.switch input{
			display:none;
		}

		.slider{
		  position: absolute;
		  cursor: pointer;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: #ccc;
		  -webkit-transition: .4s;
		  transition: .4s;
		}

		.slider:before {
		  position: absolute;
		  content: "";
		  height: 26px;
		  width: 26px;
		  left: 4px;
		  bottom: 4px;
		  background-color: white;
		  -webkit-transition: .4s;
		  transition: .4s;
		}

		input:checked + .slider {
		  background-color: #645045;
		}

		input:focus + .slider {
		  box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		  -webkit-transform: translateX(26px);
		  -ms-transform: translateX(26px);
		  transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
		  border-radius: 34px;
		}

		.slider.round:before {
		  border-radius: 50%;
		}

		.sliderDiv{
			margin-left: 60vw;
			width: 180px;
		}

		.sliderNameDiv{
			text-align: center;
			font-size: 20px;
			padding: 5px;
		}

		#activitySelect{
			margin-bottom: 10vh;
			margin-left: 1vw;
			font-size: 1.3em;
			height: 40px;
			min-width: 80px; 
		}

		.userActivityClass{
			text-align: center;
			padding: 10px;
			font-size: 20px;
			width: 60%;
			overflow: auto;
			margin-bottom: 30px;
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
		<a id="title" class="title" onclick="home()">My Library</a>
		<a class="options" href="#home" onclick="home()">Home</a>
	  	<a class="active options" onclick="profile()">My Profile</a>
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
	<div class="sidenav" id="sidenav">
		<a class="sidenavlinks active" onclick="activityClick();">Activity</a>
		<a id="booksLiked" class="sidenavlinks" onclick="shelfClick(this);">Books Liked</a>
		<a id="wantToRead" class="sidenavlinks" onclick="shelfClick(this);">Want To Read</a>
		<a id="currentlyReading" class="sidenavlinks" onclick="shelfClick(this);">Currently Reading</a>
		<a id="finishedReading" class="sidenavlinks" onclick="shelfClick(this);">Finished Reading</a>
		<a class="sidenavlinks" onclick="logout();">Logout</a>
		<div class="sidenavlinks labelsOPT"><bold>Bookshelves:</bold></div>
		<a class="sidenavlinks" onclick="openNewShelfModal();">Add New Shelf</a>
		<a class="sidenavlinks" onclick="shelfClick(this);">Favourites</a>
	</div>
	<div class="modal" id="modalId"> 
		<div class="modal-content">
			<div class="modal-header">
				<h2 style="text-align: center; font-size: 1.8em; margin-left: 34%;">Add a Book shelf</h2>
				<span class="close" id="modalCloseId">&times;</span>
			</div>
			<div class="modal-body">		
					<div><input id="shelfInputId" class="inputClass" type="text" name="title" placeholder="Bookshelf Name"/></div>	
					<div><input id="submitInputId" class="inputClass" type="submit" name="appAdd" value="Save" onclick="addShelf();"></div>			
			</div>
		</div>
	</div>
	<div id="searchSuggestionsRegion" class="search-container"></div>
	<div id="activityRegion" class="main" style="margin-top: 20vh;">
		
	</div>

<script type="text/javascript">

	document.getElementById("sidenav").style.top = document.getElementById("navbar").offsetHeight+"px";
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

	var modal = document.getElementById('modalId');
	var close = document.getElementById("modalCloseId");

	close.onclick = function() {
	    modal.style.display = "none";
	  
		document.getElementById("shelfInputId").value = "";
		document.getElementById("shelfInputId").placeholder = "Bookshelf name";
		
	}

	window.onclick = function(event) {
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	}	

</script>
<script src="functions.js"></script>
<script src="profile.js"></script>
</body>
</html>
