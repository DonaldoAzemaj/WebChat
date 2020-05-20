<?php

require_once "dbconfig/config.php";

if(isset($_GET['email'])){
    $email = $_GET['email'];
}

if(isset($_GET['key'])){
    $key = $_GET['key'];
}

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $bcrypt_key = $_POST['key'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if($password===$cpassword and !empty($password)){

    $db = new DB;
    $pdo = $db->connect();
    $queryS = "SELECT email FROM login_data_recover WHERE email=:email AND bcrypt_key=:bcrypt_key LIMIT 1";
    $stmt = $pdo->prepare($queryS);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':bcrypt_key',$bcrypt_key);

    if($stmt->execute()){

        if(count($stmt->fetchAll())==1){

            $password =   password_hash($password, PASSWORD_DEFAULT);

            $queryS = "UPDATE login_data SET password=:password WHERE email=:email";
            $stmt = $pdo->prepare($queryS);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':password',$password);

            if($stmt->execute()){
                $succes_msg = "Password update succesfully. <a href='login.php'>Login</a>";

                $queryS = "DELETE FROM login_data_recover WHERE email=:email";
                $stmt = $pdo->prepare($queryS);
                $stmt->bindParam(':email',$email);
                
                $stmt->execute();



            }


        }else{
            header("location: login.php");
        }


    }

   }else{
    $client_msg = "Password dosn't match, try again";
   }


}else{

    if(isset($_GET['email'])){
        $email = $_GET['email'];
    }else{
        header("location: login.php");
    }
    
    if(isset($_GET['key'])){
        $key = $_GET['key'];
    }else{
        header("location: login.php");
    }



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

<script>
 document.getElementById("circle").onclick = function() { 
  
  document.getElementById("circle").style.display = "none"; 

}
</script>

<div class="loginFormOuter">
<div class="loginForm"  >

<h1 style="text-align:center;">Reset Password</h1>

<form class="loginFormDesign" 
action="recoverpsw.php?key=<?php if(isset($key)) echo $key; ?>&email=<?php if(isset($email)) echo $email; ?>" method="post">

<input type="hidden"  name="email" value=<?php if(isset($email)) echo $email;?>>
<input type="hidden"  name="key" value=<?php if(isset($key))  echo $key;?>>

<label class="inputLabel" for="password">Password</label><br>
<input class="inputvalue" type="password" name="password" placeholder="Password"><br>
<label class="inputLabel" for="cpassword">Confirm Password</label><br>
<input class="inputvalue" type="password" name="cpassword" placeholder="Confirm Password"><br>
<p id="error_msg"><?php if(isset($client_msg))echo $client_msg; ?></p>
<p id="error_msg"><?php if(isset($succes_msg))echo $succes_msg; ?></p>
<input type="submit" id="submit_btn" name="submit" value="Update"  <?php if(isset($succes_msg)){echo "disabled" ; } ?> >


</form>


</div>
</div>



<div id="footer" style="margin-top:auto;" >
</div>

</body>

</html>