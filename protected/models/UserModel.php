<?php
include $_SERVER['DOCUMENT_ROOT']."/assets/settings.php";

class UserModel{
    public function addUser(array $data){
        try{
            if($this->isRegistered($data['email'])){
                header('HTTP/1.1 403 Forbidden', true, 403);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"403\",
                           \"source\": { \"pointer\": \"/protected/models/UserModel/addUser\" },
                           \"title\":  \"Collision\",
                           \"detail\": \"User {$data['login']}:{$data['password']} already exists\"
                        }
                    ]
                }";
                exit();
            }
            $connection = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO User(name, password, email, register_date, role)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->execute($data);
            if(!empty($stmt->errorInfo()[1])){
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"500\",
                           \"source\": { \"pointer\": \"/protected/models/UserModel/addUser\" },
                           \"title\":  \"Internal error\",
                           \"detail\": \"Error ".$stmt->errorInfo()[0].": ".$stmt->errorInfo()[2]."\"
                        }
                    ]
                }";
                exit();
            }
            return true;
        }catch(PDOException $e){
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            echo "{
                    \"errors\": [
                        {
                           \"status\": \"500\",
                           \"source\": { \"pointer\": \"/protected/models/UserModel/addUser\" },
                           \"title\":  \"Internal error\",
                           \"detail\": \"".$e->getMessage()."\"
                        }
                    ]
                }";
            exit();
        }
    }
    
    public function isRegistered($email){
        try {
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_u FROM User WHERE email = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"500\",
                           \"source\": { \"pointer\": \"/protected/models/UserModel/isRegistered\" },
                           \"title\":  \"Internal error\",
                           \"detail\": \"Error ".$stmt->errorInfo()[0].": ".$stmt->errorInfo()[2]."\"
                        }
                    ]
                }";
                exit();
            }
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($user['id_u']);
        } catch (PDOException $e){
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            echo "{
                    \"errors\": [
                        {
                           \"status\": \"500\",
                           \"source\": { \"pointer\": \"/protected/models/UserModel/addUser\" },
                           \"title\":  \"Internal error\",
                           \"detail\": \"".$e->getMessage()."\"
                        }
                    ]
                }";
            exit();
        }
    }
}