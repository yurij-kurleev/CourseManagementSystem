<?php
session_start();
function __autoload($class_name){
    //class directories
    $directories = array(
        $_SERVER['DOCUMENT_ROOT'].'/protected/controllers/',
        $_SERVER['DOCUMENT_ROOT'].'/protected/models/',
        $_SERVER['DOCUMENT_ROOT'].'/protected/library/'
    );

    foreach($directories as $directory){
        if(file_exists($directory.$class_name . '.php')) {
            require_once($directory . $class_name . '.php');
            return;
        }
    }
}

try{
    $front = FrontController::getInstance();
    $front->route();
    echo $front->getBody();
} catch (Exception $e){
    echo $e->getMessage();
}