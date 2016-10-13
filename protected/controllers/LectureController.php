<?php
class LectureController{
    public function addLectureAction(){
        $lectureModel = new LectureModel();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'content' => strip_tags(trim($_POST['content'])),
            'id_lesson' => strip_tags(trim($_POST['id_lesson']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                header("HTTP/1.1 400 Bad Request", true, 400);
                echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/LectureController/addLectureAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: `$key` !\" 
                    ]
                ";
                exit();
            }
        }
        if ($lectureModel->addLecture($data)){
            header("HTTP/1.1 201 Created", true, 201);
        }
    }
}