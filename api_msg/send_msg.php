<?php

header("Content-type: text\javascript");
require_once "../dbconfig/config.php";
session_start();
if(!isset($_SESSION['email'])){
    exit;
}

$from_login_id = $_SESSION["login_id"];
$to_login_id = $_POST["to_login_id"];
$message = $_POST["message"];

$db = new DB;
$pdo = $db->connect();
$queryI = "INSERT INTO msg_data (from_login_id, to_login_id, message) VALUES (:from_login_id, :to_login_id, :message)";
$stmt = $pdo->prepare($queryI);

$stmt->bindParam(":from_login_id",$from_login_id);
$stmt->bindParam(":to_login_id",$to_login_id);
$stmt->bindParam(":message",$message);



if($stmt->execute()){

   // $queryU = "UPDATE  last_event SET last_msg=CURRENT_TIMESTAMP() WHERE login_data_id=$from_login_id";
   // $stmt = $pdo->prepare($queryU);
   //$stmt->execute();

    echo json_encode( array("status"=>"good"));

}else{

    echo json_encode( array("status"=>"bad"));

}
