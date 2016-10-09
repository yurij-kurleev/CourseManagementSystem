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
            header('HTTP/1.1 201 Created', true, 201);
        }
    }

    public function meAction(){
        $userModel = new UserModel();
        $data = [
            'email' => strip_tags(trim($_POST['email'])),
            'password' => hash("sha256", strip_tags(trim($_POST['password'])))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                header('HTTP/1.1 400 Bad Request', true, 400);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"400\",
                           \"source\": { \"pointer\": \"/protected/controllers/UserController/meAction\" },
                           \"title\":  \"Missing params\",
                           \"detail\": \"Missing param: `$key` !\"
                        }
                    ]
                }";
                exit();
            }
        }
        $userInfo = $userModel->getUserByEmailPassword($data);
        if (empty($userInfo)){
            header("HTTP/1.1 401 Unauthorized", true, 401);
            echo "{
                    \"errors\": [
                        {
                           \"status\": \"401\",
                           \"source\": { \"pointer\": \"/protected/controllers/UserController/meAction\" },
                           \"title\":  \"User unauthorized\",
                           \"detail\": \"No such user or password is not correct!\"
                        }
                    ]
                }";
            exit();
        }
        else{
            header("HTTP/1.1 200 OK", true, 200);
            FrontController::getInstance()->setBody(json_encode($userInfo));
        }
    }
}