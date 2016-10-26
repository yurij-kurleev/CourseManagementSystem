<?php
include $_SERVER['DOCUMENT_ROOT']."/assets/settings.php";

class UserModel extends Model{
    public function addUser(array $data){
        $connection = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO users(name, password, email, register_date, role)
                VALUES(:name, :password, :email, :register_date, :role)";
        $stmt = $connection->prepare($sql);
        $stmt->execute($data);
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }
    
    public function isRegistered($email){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_u FROM users WHERE email = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($user['id_u']);
    }

    public function getUserByEmailPassword(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_u, name, password, email, register_date, role FROM users 
                WHERE email = :email AND password = :password";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}