<?php
require $_SERVER['DOCUMENT_ROOT']."/assets/settings.php";

class PDOConnection{
    private static $instance = null;
    private $connection = null;
    
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function __construct(){}

    public function getConnection(){
        if(is_null($this->connection)){
            $this->connection = new PDO(DSN, DB_USER, DB_PASSWORD);
        }
        return $this->connection;
    }
}