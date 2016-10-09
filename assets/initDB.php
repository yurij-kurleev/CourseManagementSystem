<?php
include_once "settings.php";
include_once $_SERVER['DOCUMENT_ROOT']."/protected/library/PDOConnection.php";

//Connect
try{
    $link = PDOConnection::getInstance()->getConnection();
}catch (PDOException $e){
    echo $e->getCode().": ".$e->getMessage();
    exit();
}

//users
$sql = "CREATE TABLE IF NOT EXISTS users
(id_u INT(11) NOT NULL AUTO_INCREMENT,
 name VARCHAR(75) NOT NULL,
 password VARCHAR(200) NOT NULL,
 email VARCHAR(50) NOT NULL,
 register_date INT(14) NOT NULL,
 role VARCHAR(20) NOT NULL,
 PRIMARY KEY (id_u),
 UNIQUE (email))";
try{
    $link->exec($sql);
    if (!empty($link->errorInfo()[1])) {
        print_r($link->errorInfo());
    }
}catch (PDOException $e){
    echo $e->getCode().": ".$e->getMessage();
    exit();
}

echo "Completed";