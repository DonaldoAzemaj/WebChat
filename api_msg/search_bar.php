<?php 
header("Content-type: text\javascript");
require_once "../dbconfig/config.php";
session_start();
if(!isset($_SESSION['email'])){
    exit;
}

$email = $_SESSION["email"];
$key = $_POST["keyPres"];

$db = new DB;
$pdo = $db->connect();
$queryS = "SELECT username,login_id  FROM login_data WHERE username LIKE concat(:key,'%') and email!=:email";
$stmt = $pdo->prepare($queryS);
$stmt->bindParam(':key', $key);
$stmt->bindParam(':email', $email);

$stmt->execute();

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