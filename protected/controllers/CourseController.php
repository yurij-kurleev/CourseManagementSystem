<?php
class CourseController{
    public function addCourseAction(){
        $courseModel = new CourseModel();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'description' => strip_tags(trim($_POST['description'])),
            'id_auth' => strip_tags(trim($_POST['id_auth']))
        ];
        foreach ($data as $key => $value){
            if (empty($key)){
                header("HTTP/1.1 400 Bad Request", true, 400);
                echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/addCourseAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: `$key` !\" 
                    ]
                ";
                exit();
            }
        }
        if ($courseModel->addCourse($data)){
            header("HTTP/1.1 201 Created", true, 201);
        }
    }
}