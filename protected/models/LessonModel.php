<?php

class LessonModel
{
    public function addLesson(array $data)
    {
        try {
            if ($this->isLessonCreated($data['title'])) {
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/controllers/LessonModel/addLesson\"},
                        \"title\": \"Collision\",
                        \"description\": \"Lesson {$data['title']} already exists.\" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO lessons(title, description, id_c, date) 
                    VALUES(:title, :description, :id_c, :date)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/controllers/LessonModel/addLesson\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            return true;
        } catch (PDOException $e) {
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseModel/addCourse\"},
                        \"title\": \"Internal error\",
                        \"description\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }

    public function isLessonCreated($title)
    {
        try {
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lesson FROM lessons WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseModel/isLessonCreated\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($course['id_lesson']);
        } catch (PDOException $e) {
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/controllers/CourseModel/isCourseCreated\"},
                        \"title\": \"Internal error\",
                        \"detail\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }
}