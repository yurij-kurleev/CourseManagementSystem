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
    if (!empty($link->errorInfo()[1])) {
        print_r($link->errorInfo());
    }
} catch (PDOException $e) {
    echo $e->getCode() . ": " . $e->getMessage();
    exit();
}

//lessons
$sql = "CREATE TABLE IF NOT EXISTS lessons
(id_lesson INT(11) NOT NULL AUTO_INCREMENT,
title VARCHAR(100) NOT NULL,
date INT(14) NOT NULL,
id_course INT(11) NOT NULL,
UNIQUE (title),
PRIMARY KEY (id_lesson),
FOREIGN KEY (id_course) REFERENCES courses(id_course)
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
date INT(14) NOT NULL,
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

//tests
$sql = "CREATE TABLE IF NOT EXISTS tests
(id_test INT(11) NOT NULL AUTO_INCREMENT,
mark DECIMAL(5,2) DEFAULT 0.0,
date INT(14) NOT NULL,
id_lesson INT(11) NOT NULL,
PRIMARY KEY (id_test),
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

//questions
$sql = "CREATE TABLE IF NOT EXISTS questions
(id_question INT(11) NOT NULL AUTO_INCREMENT,
question VARCHAR(350) NOT NULL,
mark DECIMAL(5,2) DEFAULT 0.0,
date INT(14) NOT NULL,
id_lesson INT(11) NOT NULL,
PRIMARY KEY (id_question),
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

//answers. Field is_correct point correct(1) or incorrect(0) the answer is.
$sql = "CREATE TABLE IF NOT EXISTS answers
(id_answer INT(11) NOT NULL AUTO_INCREMENT,
answer VARCHAR(350) NOT NULL,
date INT(14) NOT NULL,
is_correct INT(5) NOT NULL,
id_question INT(11) NOT NULL,
PRIMARY KEY (id_answer),
FOREIGN KEY (id_question) REFERENCES questions(id_question)
ON DELETE CASCADE
ON UPDATE CASCADE)";
try{
    $link->exec($sql);
    if (!empty($link->errorInfo()[1])) {
        print_r($link->errorInfo());
    }
} catch (PDOException $e) {
    echo $e->getCode() . ": " . $e->getMessage();
    exit();
}

echo "Completed";