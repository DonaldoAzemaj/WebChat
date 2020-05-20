<?php 
header("Content-type: text\javascript");
require_once "../dbconfig/config.php";
session_start();
if(!isset($_SESSION['email'])){
    exit;
}


$email = $_SESSION["email"];

$queryS = "SELECT username,login_id FROM login_data WHERE email !='".$email."'";

$db = new DB;
$pdo = $db->connect();
$stmt = $pdo->prepare($queryS);

if($stmt->execute()){

    $objs = $stmt->fetchAll();
    $data= array();
    $count = count($objs);

    for($i=0;$i<$count;$i++){
        $obj = $objs[$i];
        $data[$i]['username'] = $obj["username"];
        $data[$i]['login_id'] = $obj["login_id"];
    }

    echo json_encode($data);
    


}else{
    echo "error";
}


