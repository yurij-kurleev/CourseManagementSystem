<?php
class UserService{
    public function registerUser(array $data){
        $userModel = new UserModel();
        if ($userModel->addUser($data)) {
            mail($data['email'], "Course Management System",
                "Congratulations " . $data['name'] . "! You've been successfully registered. Have a nice day.");
            return true;
        }
    }

    public function authUser(array $data){
        $userModel = new UserModel();
        $userInfo = $userModel->getUserByEmailPassword($data);
        if (empty($userInfo) || is_null($userInfo)){
            throw new AuthorizationException("No such user or password is not correct!");
        }
        else
            return $userInfo;
    }
}