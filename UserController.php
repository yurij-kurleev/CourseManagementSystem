<?php
class UserController{
    public function registerUserAction(){
        $userModel = new UserModel();
        $data = [
            'name' => strip_tags(trim($_POST['name'])),
            'email' => strip_tags(trim($_POST['email'])),
            'password' => hash("sha256", strip_tags(trim($_POST['password']))),
            'role' => strip_tags(trim($_POST['role'])),
            'register_date' => time()
        ];
        foreach ($data as $key=>$value){
            if(empty($value)){
                header('HTTP/1.1 400 Bad Request', true, 400);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"400\",
                           \"source\": { \"pointer\": \"/protected/controllers/UserController/registerLecturerAction\" },
                           \"title\":  \"Missing params\",
                           \"detail\": \"Missing param: `$key` !\"
                        }
                    ]
                }";
                exit();
            }
        }
        if($userModel->addUser($data)){
            mail($data['email'], "Course Management System",
            "Congratulations".$data['name']."! You've been successfully registered. Have a nice day.");
            header('HTTP/1.1 201 Created', true, 201);
        }
    }
}