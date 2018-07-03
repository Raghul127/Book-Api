<?php

session_start();
include_once('connect.php');
include_once("createDb.php");
$_SESSION['message']="";

$tablename = "user";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $sql = "USE Books;";
    $conn->query($sql);

    $_SESSION['message']="";
    $allow=1;

    $url ='https://www.google.com/recaptcha/api/siteverify';
    $privateKey = "6LfehmEUAAAAAGd8h7kHg_8T5_fMAvQfqqHTis0I";

    $response = file_get_contents($url."?secret=".$privateKey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $data = json_decode($response);


    $username = $conn->real_escape_string($_POST['username']);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['message'] = 'Please enter a valid E-Mail address!';
        $allow=0;
    }

    if(!preg_match("/^[a-zA-Z0-9_.-]*$/",$_POST['username'])) {
        $_SESSION['message'] = 'Your username can only contain letters, numbers, underscore, dash, point, no other special characters are allowed!';
        $allow=0;
    }

    $sql = "SELECT * FROM $tablename;";
    $result = $conn->query($sql);

    if($result->num_rows>0){
        while($row = $result->fetch_assoc()){

            if($row["username"]==$username){
                $_SESSION['message'] = 'Username already exists!';
                $allow=0;
            }

            if($row["email"]==$email){
                $_SESSION['message'] = 'E-Mail already exists!';
                $allow=0;
            }
        }
    }   

    if(!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}$/",$_POST['password'])) {
        $_SESSION['message'] = 'Your password should contain minimum four characters, at least one letter and one number!';
        $allow=0;
    }


    if($allow==1){

        //if two passwords are equal to each other
        if($_POST["password"]==$_POST["confirmpassword"]){

            $password = md5($_POST['password']); //md5 hash password for security

            //set session variables
            $_SESSION['username'] = $username; 
            $_SESSION['email'] = $email;

            include("createDataTable.php");

            $shelves = "Favourites%";
            $activityVisibility = "public";
            $usersActivity = "";
            $following = "";
            $followers = "";

            //insert user data into database
            $stmt = $conn->prepare("INSERT INTO $tablename (username,email,password,Shelves,ActivityVisibility,UsersActivity) "."VALUES (?,?,?,?,?,?)");
            if(!$stmt){
                echo "Error preparing statement ".htmlspecialchars($conn->error);
            }
            $stmt->bind_param("ssssss",$username,$email,$password,$shelves,$activityVisibility,$usersActivity);
            if($stmt->execute() === true){    
                $_SESSION['message'] = "Registration succesful! Added $username to the database!";
                header("location: home.php");  
            }

            else{
                $_SESSION['message'] = 'User could not be added to the database!';
            }
            
            $stmt->close();
            $conn->close();   

        }

        else{
            $_SESSION['message'] = 'Two passwords do not match!';
        }

    }    

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Sign Up </title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
    <script src='https://www.google.com/recaptcha/api.js'></script>
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

        #loginb{
            border-radius: 3px;
            letter-spacing: 0.3em;
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
    <h2>Register</h2>
  </div>
    
  <form method="post" action="register.php">
    <div id="errMsg"><?= $_SESSION['message'] ?><span id="errMsg1"></span></div>
    <div class="input-group">
      <label>Username</label>
      <input type="text" name="username"  onkeyup="usernameAvailabilty(this.value);" onblur="usernameFocusOut();" required />
    </div>
    <div class="input-group">
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password">
    </div>
    <div class="input-group">
      <label>Confirm password</label>
      <input type="password" name="confirmpassword">
      <br>
    </div>
            <div class="reCaptchaClass"><div class="g-recaptcha" data-sitekey="6LfehmEUAAAAAHBm_412a_fY7WwW9f5kjcLdYvH5"></div></div></div>
       
    <div class="input-group">
      <button type="submit" class="btn" name="register">Register</button>
    </div>
  </form>

  <div class="options">
        <span class="text">Already have an account?  </span>
        <span><button id="loginb" onclick="login()">Login</button></span>
    </div>
<br><br>

<script>

    function login(){
        window.location = "login.php";
    }

</script>
<script src="register.js"></script>       
</body>
</html>