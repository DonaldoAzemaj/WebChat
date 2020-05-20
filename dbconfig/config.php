<?php

//$con= mysqli_connect("localhost","root","") or die ("Uneable to reach");
//mysqli_select_db($con,'marketdb');


class DB{

    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;

    public function connect(){
        $this->servername="127.0.0.1";
        $this->username="root";
        $this->password="";
        $this->dbname="marketdb";
        $this->charset="utf8mb4";

        try{

        $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
        $pdo = new PDO($dsn,$this->username,$this->password);
        return $pdo;

        }catch(PDOExepction $e){

            return "Connection faild: ". $e->getMessage();

        }


    

    }


   



}


?>