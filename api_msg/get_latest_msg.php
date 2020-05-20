<?php
header("Content-type: text\javascript");
require_once "../dbconfig/config.php";
session_start();
if(!isset($_SESSION['email'])){
    exit;
}

$to_login_id = $_POST["to_login_id"];
$from_login_id = $_SESSION["login_id"];

$db = new DB;
$pdo = $db->connect();
$queryS = "SELECT * FROM last_event AS last
INNER JOIN msg_data AS msg
ON last.login_data_id = msg.to_login_id
WHERE (msg.from_login_id=$to_login_id AND msg.to_login_id=$from_login_id)
AND (msg.post_date>last.last_msg)";

$stmt = $pdo->prepare($queryS);
$stmt->execute();

$msgsObj = $stmt->fetchAll();
$jsonMsg = array();

$i=0;
foreach($msgsObj as $msg){
$jsonMsg[$i]['from_login_id']=$msg['from_login_id'];
$jsonMsg[$i]['message']=$msg['message'];
$dt=$msg['post_date'];
$jsonMsg[$i]['date']=date("j F, h:i A", strtotime($dt));
$i++;
}


if($i!=0){

    $queryU = "UPDATE  last_event SET last_msg=CURRENT_TIMESTAMP() WHERE login_data_id=$from_login_id";
    $stmt = $pdo->prepare($queryU);
    $stmt->execute();

}



echo json_encode($jsonMsg);


