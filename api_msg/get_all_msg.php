<?php
header("Content-type: text\javascript");
require_once "../dbconfig/config.php";
session_start();

if(!isset($_SESSION['login_id'])){
   exit;
}

$from_login_id = $_SESSION['login_id'];
$to_login_id = $_POST['to_login_id'];


$queryS = "SELECT * FROM msg_data

 WHERE
 (from_login_id=:from_login_id AND to_login_id=:to_login_id)
 OR (from_login_id=:to_login_id AND to_login_id=:from_login_id) ORDER BY post_date ASC";

$db=new DB;
$pdo = $db->connect();
$stmt = $pdo->prepare($queryS);
$stmt->bindParam(":from_login_id",$from_login_id);
$stmt->bindParam(":to_login_id",$to_login_id);
if($stmt->execute()){

   $msgsObj = $stmt->fetchAll();
   $jsonMsg = array();
   
   $i=0;
   foreach($msgsObj as $msg){
   $jsonMsg[$i]['from_login_id']=$msg['from_login_id'];
   $jsonMsg[$i]['from_username']=$msg['to_login_id'];
   $jsonMsg[$i]['message']=$msg['message'];
   $dt=$msg['post_date'];
   $jsonMsg[$i]['date']=date("j F, h:i A", strtotime($dt));
   $i++;
   }


   if($i!=0){
      $date = $jsonMsg[$i-1]["date"];
      $queryI = "UPDATE  last_event SET last_msg='$date' WHERE login_data_id=$from_login_id";
   }else{

      $queryS = "SELECT * FROM last_event WHERE login_data_id=$from_login_id";
      $stmt = $pdo->prepare($queryS);
      $stmt->execute();
    
      if(count($stmt->fetchAll())==1){
         
         $queryI = "UPDATE  last_event SET last_msg=CURRENT_TIMESTAMP() WHERE login_data_id=$from_login_id";
      }else{       
         
         $queryI = "INSERT INTO last_event (login_data_id,last_msg) VALUES ($from_login_id,CURRENT_TIMESTAMP())";
      }


   }
 

   $stmt = $pdo->prepare($queryI);

   if($stmt->execute()){
      echo json_encode($jsonMsg);

   }else{
      echo json_encode($date);
   }

   
  
  



}else{
   echo json_encode("error");
}






