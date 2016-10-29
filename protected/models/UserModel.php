<?php
include $_SERVER['DOCUMENT_ROOT']."/assets/settings.php";

class UserModel extends Model{
    private static $instance = null;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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

    public function getUserById($id)
    {
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_u, name, password, email, register_date, role FROM users 
                WHERE id_u = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function subscribeOnCourse(array $data)
    {
        $connection = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO subscriptions(id_u, id_course, date)
                VALUES(:id_u, :id_course, :date)";
        $stmt = $connection->prepare($sql);
        $stmt->execute($data);
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    public function unsubscribeFromCourse(array $data)
    {
        $connection = PDOConnection::getInstance()->getConnection();
        $sql = "DELETE FROM subscriptions WHERE id_u = :id_u AND id_course = ?";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array('id_u' => $data['id_u']));
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    public function isSubscribed(array $data)
    {
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_sub, id_u, id_course, date FROM subscriptions 
                WHERE id_u = :id_u AND id_course = :id_course";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        UserModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $subscription = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($subscription);
    }
}