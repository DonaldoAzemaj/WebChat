<?php 
require_once "dbconfig/config.php";

if(isset($_POST['submit'])){
$email = $_POST['email'];

$db = new DB;
$pdo = $db->connect();
$queryS = "SELECT verified FROM login_data WHERE email=:email ";
$stmt = $pdo->prepare($queryS);

$stmt->bindParam(':email', $email);
if($stmt->execute()){

   

if(count($result = $stmt->fetchAll())==1){
 
    $result= $result[0]['verified'];


    if($result == 1){

    $queryI = "DELETE FROM login_data_recover WHERE email=:email";
    $stmt = $pdo->prepare($queryI);
    
    $stmt->bindParam(':email',$email);
   
    if($stmt->execute()){
    
    $vkey = md5(time(). $email . randomString());
    $bcrypt_key =   password_hash($vkey, PASSWORD_DEFAULT);
    
    $queryI = "INSERT INTO login_data_recover (email, bcrypt_key) VALUES (:email, :bcrypt_key)";
    $stmt = $pdo->prepare($queryI);
    
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':bcrypt_key',$bcrypt_key);
    
    if($stmt->execute()){
    
        $to = $email;
          $subject = "Update Your Password";
          $message = "Click the link to update your account's password. <a href = 'http://localhost/superpmarket/recoverpsw.php?key=$bcrypt_key&email=$email';'>Click Here</a>";
          $headers = "From: bestmomentteam@outlook.com \r\n";
          $headers .= "MIME-Version: 1.0"."\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8"."\r\r"; 
          mail($to, $subject, $message, $headers);
          $client_msg ="We email you link to reset password, check your email";
    
    }else{
        $client_msg ="Error, try again later. Can't process recovery";
    }
    
   }else{
    $client_msg ="Error, try again later.";
   }

}else{
    $client_msg ="This email is not register to our site.";
}


}else{
    $client_msg ="You have to verifiy account";
}

}else{
    $client_msg ="Error, try again later. Can't find account";
}




}


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


<!DOCTYPE html>
<html style="height:100%; margin:0;">

<head>
    <title>Supermaaket</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

 <body class="body" style="height:100%;  margin:0; display:flex; flex-direction:column; min-height:100vh; ">


<div id="header"></div>


<div class="loginFormOuter">
<div class="loginForm">

<h1 style="text-align:center;">Reset Password</h1>

<form class="loginFormDesign" action="sendemailrecover.php" method="post">
<label class="inputLabel" for="email">Email</label><br>
<input class="inputvalue" type="email" name="email" placeholder="Your email"><br>
<p id="error_msg"><?php if(isset($client_msg))echo $client_msg; ?></p>
<input type="submit" id="submit_btn"  <?php if(isset($_POST['submit'])){echo "disabled" ; } ?> name="submit" value="Send">

</form>


</div>
</div>



<div id="footer" style="margin-top:auto;" >
</div>

</body>

</html>