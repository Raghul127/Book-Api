<?php

session_start();
include_once('connect.php');
$_SESSION['message']="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username = trim($_POST['username']);
    $username = stripslashes($username);
    $username = htmlspecialchars($username);
    $username = $conn->real_escape_string($username);

    $password = md5($_POST['password']);

    include_once("createDb.php");

    $stmt = $conn->prepare("SELECT * FROM user WHERE username= ?;");
    if(!$stmt){
        echo "Error preparing statement ".htmlspecialchars($conn->error);
    }
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){

            if($row["password"]==$password){
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $row["email"];
                include("createDataTable.php");
                header("location: home.php");  
            }
            else{
                $_SESSION['message'] = "Sorry, Wrong Password!";
            }
        }
    }   
    else{
        $_SESSION['message'] = "Username doesn't exist!";
    }

    $stmt->close();
        
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
    <style type="text/css">
        
* {
  margin: 0px;
  padding: 0px;
  box-sizing: border-box;
}
body {
  font-size: 120%;
  background: #F8F8FF;
  background-image: url("bg.jpg");
              background-repeat: no-repeat;
    background-size: cover;
}
        .title{
            text-align: center;
            font-family: 'Sofia';
            letter-spacing: 0.4em;
            font-size: 4em;
            padding: 5px;
            color: white;
        }
        .header {
  width: 30%;
  margin: 50px auto 0px;
  color: white;
  background: #5F9EA0;
  text-align: center;
  border: 1px solid #B0C4DE;
  border-bottom: none;
  border-radius: 10px 10px 0px 0px;
  padding: 20px;
}
.header:after {
  content: "";
  display: table;
  clear: both;
}
form {
  width: 700px;
  margin: 0px auto;
  padding: 20px;
  border: 1px solid #B0C4DE;
  background: white;
  border-radius: 0px 0px 10px 10px;
}
.input-group {
  margin: 10px 0px 10px 0px;
}
.input-group label {
  display: block;
  text-align: left;
  margin: 3px;
}
.input-group input {
  height: 30px;
  width: 93%;
  padding: 5px 10px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid gray;
}

        #errMsg{
            overflow: auto;
            background: red;
            margin-top: 1px;
            letter-spacing: 0.1em;
            font-size: 1.1em;
            margin-left: 17.2vw;
            width: 14vw;
            padding: 3px;
            padding-left: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
  
        .options{
            margin-top: 5vh;
        }

        .text{
            color: white;
            font-size: 1.2em;
            margin-left: 40vw;
        }

        #signupb{
            border-radius: 3px;
            font-size: 1em;
            min-width: 7vw;
        }
        input {
  margin: 0;
  border: none;
  border-radius: 0;
  width: 75%;
  padding: 10px;
  float: left;
  font-size: 16px;
}
.btn {
  padding: 10px;
  font-size: 15px;
  color: white;
  background: #5F9EA0;
  border: none;
  border-radius: 5px;
}


    </style>
</head>
<body>
    <a onclick="#"><h1 class="title">My Library</h1></a>
<div class="header">
    <h2>Login</h2>
  </div>
     
  <form method="post" action="login.php">
     <div id="errMsg"><?= $_SESSION['message'] ?></div>
    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" >
    </div>
    <br>
    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password">
    </div>
    <br>
    <div class="input-group">
        <button type="submit" class="btn" name="login">Login</button>
    </div>
  </form>
      <div class="options">
        <span class="text">New User? Register here!</span>
        <span><button id="signupb" onclick="register()">Sign Up</button></span>
    </div>



<script>

    function register(){
        window.location = "register.php";
    }

</script>   
</body>
</html>