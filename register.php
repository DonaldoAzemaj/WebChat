<?php 
require 'dbconfig/config.php';

?>

<!DOCTYPE html>
<html style="height:100%; margin:0;">

<head>
    <title>WebChat</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
     
    
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
  </script>
    

    <script> 
    $(function(){
      $("#footer").load("footer.html"); 
    });
    </script> 

</head>

 <body >


 <?php

if(isset($_POST['submit_btn']))
{

 function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
 }

 $email = test_input($_POST['email']);
 $password = $_POST['password'];
 $cpassword = $_POST['cpassword'];
 $username = $_POST["username"];
 
 $vkey = md5(time(). $email . randomString());


 if(empty($email)){
   $error_form = "Please enter email.";
  
 }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  $error_form = "Please enter a vaild email.";
 }elseif(empty($password) or empty($cpassword)){
   $error_form = "Please enter password.";
 }else{

 if($password===$cpassword){
   $password =   password_hash($password, PASSWORD_DEFAULT);
  
   $db = new DB;
   $pdo=$db->connect();

   $queryS = "SELECT email FROM login_data WHERE email=:email";
   $stmt = $pdo->prepare($queryS);

   $stmt->bindParam(':email',$email);

   if($stmt->execute()){
  
     
     if(empty($stmt->fetchAll())){

      $queryI = "INSERT INTO login_data (email, password,username) VALUES (:email, :password, :username)";
      $stmt = $pdo->prepare($queryI);

      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':username', $username);

     if($stmt->execute()){

       $queryI = "INSERT INTO login_data_recover (email, bcrypt_key) VALUES (:email, :bcrypt_key)";
       $stmt = $pdo->prepare($queryI);

       $bcrypt_key = password_hash($vkey, PASSWORD_DEFAULT);

       $stmt->bindParam(':email', $email);
       $stmt->bindParam(':bcrypt_key', $bcrypt_key);

       if($stmt->execute()){

      $to = $email;
      $subject = "Verification Email";
      $message = "Click the link to verefied your account. <a href = 'http://".$_SERVER['HTTP_HOST']."/webchat/verify.php?key=$bcrypt_key&email=$email';'>Click Here</a>";
      $headers = "From: bestmomentteam@outlook.com \r\n";
      $headers .= "MIME-Version: 1.0"."\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8"."\r\r"; 
      mail($to, $subject, $message, $headers);

      $msg_register="You are register succesfully, check your email to verify account.";

      }else{

        $error_form = "Problem with email verefication";

      }
       
     }else{
       $error_form = "Error while registering, please try again.";
     }


      
     }else{
       $error_form = "This email alrady exist, try another email.";
     }
     


   }else{
     $error_form = "Error while registering, please try again.";

   }
  
 }else{
   
   $error_form = "Password dosn't match, try again.";
   
}  }  }



function randomString() {
  $length = rand(10,20);
  $characters = password_hash(rand(20,40),PASSWORD_DEFAULT);
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

?>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="home.php">WebChat</a>
    </div>

    <ul class="nav navbar-nav navbar-right">
      <li  class="active"><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li ><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    </ul>
  </div>
</nav>





<div class="container">

<h1 class ="text-center text-primary">Sing up</h1>


<form class="loginFormDesign" action="register.php" method="post">

<div class="form-group">
  <label  for="email">Username</label>
  <input class="form-control" type="text" name="username" placeholder="Username">
</div>


<div class="form-group">
  <label  for="email">Email</label>
  <input class="form-control" type="text" name="email" placeholder="Email">
</div>

<div class="form-group">
  <label for="password">Password</label>
  <input class="form-control" type="password" name="password" placeholder="Password">
</div>

<div class="form-group">
  <label  for="password">Confirm Password</label>
  <input class="form-control" type="password" name="cpassword" placeholder="Confirm Password">
</div>  

<div class="form-group">
  <p class="text-danger"><?php if(isset($error_form)){echo $error_form;} ?></p>
</div>

<div class="form-group">
<p class="text-success"><?php if(isset($msg_register))echo $msg_register; ?></p>
</div>

  <div class="form-group">
  <input type="submit" name="submit_btn" id="submit_btn" value="Register" class="btn btn-default">
  </div>
</form>


</div>


</body>

</html>