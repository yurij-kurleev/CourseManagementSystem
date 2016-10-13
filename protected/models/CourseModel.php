<?php
include_once "UserModel.php";

class CourseModel{
    public function addCourse(array $data){
        try {
            if ($this->isCourseCreated($data['title'])){
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/addCourse\"},
                        \"title\": \"Collision\",
                        \"description\": \"Course {$data['title']} already exists.\" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO courses(title, description, id_auth) VALUES(:title, :description, :id_auth)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/addCourse\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            return true;
        } catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/addCourse\"},
                        \"title\": \"Internal error\",
                        \"description\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }

    public function isCourseCreated($title){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_course FROM courses WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/isCourseCreated\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($course['id_course']);
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/isCourseCreated\"},
                        \"title\": \"Internal error\",
                        \"detail\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }

    /**/

    public function getCourseByTitle($title){
        try{
            if ($this->isCourseCreated($title)){
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/getCourseByTitle\"},
                        \"title\": \"No record\",
                        \"description\": \"Course with title: " . $title . "does not exist.\" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM courses WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/getCourseByTitle\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            return $course;
        } catch(PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                \"errors\": [
                    \"status\": \"500\",
                    \"source\": { \"pointer\": \"/protected/models/CourseModel/getCourseByTitle\"},
                    \"title\": \"Internal error\",
                    \"details\": \"" . $e->getMessage() . "\"
                ]
            ";
            exit();
        }
    }

    public function getCoursesListByLecturerEmail($email_lecturer){
        $userModel = new UserModel();
        try{
            if (!$userModel->isRegistered($email_lecturer)){
                header("HTTP/1.1 404 Not Found", true, 404);
                echo "
                        \"errors\": [
                            \"status\": \"404\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/getCoursesListByLecturerEmail\"},
                            \"title\": \"Not found\",
                            \"description\": \"Lecturer with email: " . $email_lecturer . " was not found.\" 
                        ]
                    ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_course, title, description FROM courses WHERE id_auth = (SELECT id_u FROM users WHERE email = ?)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $email_lecturer, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/getCoursesListByLecturerEmail\"},
                            \"title\": \"Internal error\",
                            \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                        ]
                    ";
                exit();
            }
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courses;
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/getCoursesListByLecturerEmail\"},
                            \"title\": \"Internal error\",
                            \"description\": \"" . $e->getMessage() . "\" 
                        ]
                    ";
            exit();
        }
    }

    public function deleteCourse($title){
        try{
            if ($this->isCourseCreated($title)){
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/deleteCourse\"},
                        \"title\": \"No record\",
                        \"description\": \"Course with title: " . $title . "does not exist.\" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "DELETE FROM courses WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/deleteCourse\"},
                            \"title\": \"Internal error\",
                            \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                        ]
                    ";
                exit();
            }
            return true;
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/deleteCourse\"},
                            \"title\": \"Internal error\",
                            \"description\": \"" . $e->getMessage() . "\" 
                        ]
                    ";
            exit();
        }
    }
    
    public function updateCourse(array $data){
        try{
            if ($this->isCourseCreated($data['title'])){
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/updateCourse\"},
                        \"title\": \"No record\",
                        \"description\": \"Course with title: " . $data['title'] . "does not exist.\" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "UPDATE courses SET title = :title, description = :description WHERE id_course = :id_course";
            $stmt = $link->prepare($sql);
            $stmt->execute(array(':title' => $data['title'], 'description' => $data['description'], 'id_course' => $data['id_course']));
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/CourseModel/updateCourse\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            return true;
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                        \"errors\": [
                            \"status\": \"500\",
                            \"source\": { \"pointer\" : \"/protected/models/CourseModel/updateCourse\"},
                            \"title\": \"Internal error\",
                            \"description\": \"" . $e->getMessage() . "\" 
                        ]
                    ";
            exit();
        }
    }
}