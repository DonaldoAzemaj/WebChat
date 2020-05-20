<?php
require_once "dbconfig/config.php";

$message = "";

if(isset($_GET['key']) AND isset($_GET['email'])){

    $bcrypt_key = $_GET['key'];
    $email = $_GET['email'];

    $db = new DB;
    $pdo = $db->connect();
    
    $queryS = "SELECT bcrypt_key FROM login_data_recover WHERE email=:email AND bcrypt_key=:bcrypt_key LIMIT 1";
    $stmt = $pdo->prepare($queryS);
    
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':bcrypt_key',$bcrypt_key);
    
    if($stmt->execute()){
    
    if(count($stmt->FetchAll())===1){
    
        $queryI = "UPDATE login_data SET verified=1 WHERE email=:email AND verified=0";
        $stmt = $pdo->prepare($queryI);

        $stmt->bindParam(':email',$email);

       if( $stmt->execute()){

        $queryD = "DELETE FROM login_data_recover WHERE email=:email";
        $stmt = $pdo->prepare($queryD);

        $stmt->bindParam(':email',$email);

        $stmt->execute();

        $message = "You are successfully verified! <br> <br> <a href='login.php'>Login</a>";
       }else{
        $message = "you are not verefyed";
       }      

    
    }else{
        $message = "You credential are wrong or your account is verefied!";
     
        http_response_code(401);
    }
    
    
    
    }



}else{

    $message = "you credential are missing ";
    http_response_code(401);
}



?>

<!DOCTYPE html>
<html>
  <head>
    <title>Title of the document</title>
    <style>
      section { 
        display: flex; 
        width: 100%; 
        height: 97vh; 
        margin: auto; 
        border-radius: 10px; 
        border: 3px dashed #1c87c9; 
      } 
      p { 
        margin: auto; /* Important */ 
        text-align: center;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-weight: bolder;
        color: rgb(0, 133, 88); 
        font-size: 25px;
      }
    </style>
  </head>
  <body>
    <section>
      <p> <?php echo $message; ?></p>
    </section>
  </body>
</html>