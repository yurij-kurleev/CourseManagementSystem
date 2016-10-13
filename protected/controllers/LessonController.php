<?php

class LessonController
{
    public function addLessonAction()
    {
        $lessonModel = new LessonModel();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'description' => strip_tags(trim($_POST['description'])),
            'id_c' => strip_tags(trim($_POST['id_c'])),
            'date' => time()
        ];
        foreach ($data as $key => $value) {
            if (empty($key)) {
                header("HTTP/1.1 400 Bad Request", true, 400);
                echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/LessonController/addLessonAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: `$key` !\" 
                    ]
                ";
                exit();
            }
        }
        /*
        if ($lessonModel->addLesson($data)){
            header("HTTP/1.1 201 Created", true, 201);
        }
        */
    }
}