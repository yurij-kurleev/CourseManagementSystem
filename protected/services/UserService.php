<?php
class UserService{
    public function registerUser(array $data){
        $userModel = new UserModel();
        if($userModel->isRegistered($data['email'])){
            throw new UserExistsException("User {$data['email']}:{$data['password']} already exists");
        }
        $userModel->addUser($data);
            mail($data['email'], "Course Management System",
                "Congratulations, " . $data['name'] . "! You've been successfully registered in Course Management System.
                 Have a nice day :)");
    }

    public function authUser(array $data){
        $userModel = new UserModel();
        $userInfo = $userModel->getUserByEmailPassword($data);
        if (empty($userInfo)){
            throw new AuthorizationException("No such user or password is not correct!");
        }
        else
            return $userInfo;
    }
}