<?php 
require_once "dbconfig/config.php";

session_start();


if(isset($_SESSION['email'])){
  
  header("location:chat.php");
  
  }

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

 <body>

 <?php



 
 if(isset($_POST['submit'])){
   $email = $_POST['email'];
   $password = $_POST['password'];
   
  

   $db = new DB;
   $pdo = $db->connect();
   $queryS="SELECT login_id, verified,username, password FROM login_data WHERE email=:email LIMIT 1";
   $stmt = $pdo->prepare($queryS);

   $stmt->bindParam(':email', $email);
   

   if($stmt->execute()){
   // $result = $stmt->fetchAll();
   

    if(($result = $stmt->fetch())!== null){
      //$result=$result[0];
      $loginId = $result['login_id'];
      $hashPass = $result['password'];
      $verified = $result['verified'];
      $username = $result['username'];

    
    if(password_verify($password, $hashPass)){

      if($verified==0){
     
      $client_msg="You have to verify email before login";

      }else{
        session_regenerate_id(true);

        //setcookie("token","affsesDFG564Gbn&6uJkI4zp",time()+60,"/");

        $_SESSION['active_time']=time();
        $_SESSION['email']=$email;
        $_SESSION['login_id'] = $loginId;
        $_SESSION['username']= $username;
        
        header("location:chat.php");

      }

    }else{
      $client_msg ="Password is incorrect, try again";
    }
   
    }else{
      $client_msg ="Email is incorrect, try again";
    }



   }else{
    $client_msg ="Error ocure while loading, Try again";
   }




 }
 
 

 
 ?>








<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="home.php">WebChat</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <li ><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li  class="active"><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    </ul>
  </div>
</nav>




<div class="container">

<h1 class="text-center text-primary">Login Form</h1>

<form class="loginFormDesign" action="login.php" method="post">

<div class="form-group">
<label for="email">Email</label>
<input class="form-control" type="email" name="email" placeholder="Email">
</div>

<div class="form-group">
<label for="password">Password</label>
<input class="form-control" type="password" name="password" placeholder="Password">
</div>
<div class="form-group">
<p class="text-danger"><?php if(isset($client_msg))echo $client_msg; ?></p>
</div>
<div class="form-group">
<input type="submit" id="submit_btn" name="submit" value="Login" class="btn btn-default">
</div>

</form>

<center><a href="sendemailrecover.php">Forgot password?</a></center>

</div>

</body>

</html>