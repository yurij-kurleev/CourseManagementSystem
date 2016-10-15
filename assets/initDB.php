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

//User
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
    print_r($link->errorInfo());
}catch (PDOException $e){
    echo $e->getCode().": ".$e->getMessage();
    exit();
}

//courses
$sql = "CREATE TABLE IF NOT EXISTS courses
(id_course INT(11) NOT NULL AUTO_INCREMENT,
title VARCHAR(100) NOT NULL,
description TEXT NOT NULL,
date INT(11) NOT NULL,
id_auth INT(11) NOT NULL,
UNIQUE (title),
PRIMARY KEY (id_course),
FOREIGN KEY (id_auth) REFERENCES users(id_u)
ON DELETE CASCADE
ON UPDATE CASCADE)";
try{
    $link->exec($sql);
    print_r($link->errorInfo());
}catch (PDOException $e){
    echo $e->getCode().": ".$e->getMessage();
    exit();
}

//lessons + добавить id_test
$sql = "CREATE TABLE IF NOT EXISTS lessons
(id_lesson INT(11) NOT NULL AUTO_INCREMENT,
title VARCHAR(100) NOT NULL,
description TEXT NOT NULL,
date INT(14) NOT NULL,
id_c INT(11) NOT NULL,
UNIQUE (title),
PRIMARY KEY (id_lesson),
FOREIGN KEY (id_c) REFERENCES courses(id_course)
ON DELETE CASCADE
ON UPDATE CASCADE)";
try{
    $link->exec($sql);
    print_r($link->errorInfo());
}catch (PDOException $e){
    echo $e->getCode(). ": " . $e->getMessage();
    exit();
}

//lectures
$sql = "CREATE TABLE IF NOT EXISTS lectures
(id_lecture INT(11) NOT NULL AUTO_INCREMENT,
title VARCHAR(100) NOT NULL,
content TEXT NOT NULL,
id_lesson INT(11) NOT NULL,
UNIQUE (title),
PRIMARY KEY (id_lecture),
FOREIGN KEY (id_lesson) REFERENCES lessons(id_lesson)
ON DELETE CASCADE
ON UPDATE CASCADE)";
try{
    $link->exec($sql);
    print_r($link->errorInfo());
}catch (PDOException $e){
    echo $e->getCode(). ": " . $e->getMessage();
    exit();
}

echo "Completed";