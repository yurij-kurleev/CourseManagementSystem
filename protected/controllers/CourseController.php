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

    public function getCourseAction(){
        $courseModel = new CourseModel();
        $id_course = strip_tags(trim($_POST['id_course']));
        if (empty($id_course)){
            header("HTTP/1.1 400 Bad Request", true, 400);
            echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/addCourseAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: id_course !\" 
                    ]
                ";
            exit();
        }
        $course = $courseModel->getCourseById($id_course);
        if(empty($course)){
            header("HTTP/1.1 404 Not found", true, 404);
            echo "
                    \"errors\": [
                        \"status\": \"404\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/getCourseAction\"},
                        \"title\": \"Not found\",
                        \"detail\": \" Course with id_course: " . $id_course . " was not found.\"
                    ]
                ";
            exit();
        }
        else{
            FrontController::getInstance()->setBody(json_encode($course));
        }
    }
    
    public function getCoursesListAction(){
        $courseModel = new CourseModel();
        $id_lecturer = strip_tags(trim($_POST['id_u']));
        if (empty($id_lecturer)){
            header("HTTP/1.1 400 Bad Request", true, 400);
            echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/addCourseAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: id_lecturer !\" 
                    ]
                ";
            exit();
        }
        $coursesList = $courseModel->getCoursesListByLecturerId($id_lecturer);
        if (empty($coursesList)){
            header("HTTP/1.1 404 Not found", true, 404);
            echo "
                    \"errors\": [
                        \"status\": \"404\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/getCoursesListAction\"},
                        \"title\": \"Not found\",
                        \"detail\": \" Courses list was not found by id_lecturer: " . $id_lecturer . ".\"
                    ]
                ";
            exit();
        }
        else{
            FrontController::getInstance()->setBody(json_encode($coursesList));
        }
    }

    public function deleteCourseAction(){
        $courseModel = new CourseModel();
        $id_course = strip_tags(trim($_POST['id_course']));
        if (empty($id_course)){
            header("HTTP/1.1 400 Bad Request", true, 400);
            echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/deleteCourseAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: id_course !\" 
                    ]
                ";
            exit();
        }
        if ($courseModel->deleteCourse($id_course)){
            header("HTTP/1.1 200 OK");
        }
        else{
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseController/deleteCourseAction\"},
                            \"title\": \"Internal error\",
                            \"description\": \" Deleting course failed.\" 
                        ]
                    ";
            exit();
        }
    }
    
    public function updateCourseAction(){
        $courseModel = new CourseModel();
        $data = [
            'id_course' => strip_tags(trim($_POST['id_course'])),
            'title' => strip_tags(trim($_POST['title'])),
            'description' => strip_tags(trim($_POST['description']))
        ];
        foreach ($data as $key => $value){
            if (empty($key)){
                header("HTTP/1.1 400 Bad Request", true, 400);
                echo "
                    \"errors\": [
                        \"status\": \"400\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseController/updateCourseAction\"},
                        \"title\": \"Missing params\",
                        \"description\": \" Missing param: `$key` !\" 
                    ]
                ";
                exit();
            }
        }
        if ($courseModel->updateCourse($data)){
            header("HTTP/1.1 200 OK");
        }
        else{
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseController/updateCourseAction\"},
                            \"title\": \"Internal error\",
                            \"description\": \" Updating course failed.\" 
                        ]
                    ";
            exit();
        }
    }
}