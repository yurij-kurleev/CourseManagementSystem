<?php
include $_SERVER['DOCUMENT_ROOT']."/assets/settings.php";

class UserModel{
    public function addUser(array $data){
        try{
            if($this->isRegistered($data['email'])){
                throw new UserExistsException("User {$data['email']}:{$data['password']} already exists");
            }
            $connection = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO users(name, password, email, register_date, role)
                    VALUES(:name, :password, :email, :register_date, :role)";
            $stmt = $connection->prepare($sql);
            $stmt->execute($data);
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            return true;
        } catch(PDOException $e){
            throw $e;
        }
    }
    
    public function isRegistered($email){
        try {
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_u FROM users WHERE email = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($user['id_u']);
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getUserByEmailPassword(array $data){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_u, name, password, email, register_date, role FROM users 
                    WHERE email = :email AND password = :password";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if (!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }catch(PDOException $e){
            throw $e;
        }
    }

    public function deleteUser($id_user){
        
    }
}