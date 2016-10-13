<?php
class LectureModel{
    public function addLecture(array $data){
        try{
            if ($this->isLectureCreated($data['title'])){
                header("HTTP/1.1 403 Forbidden", true, 403);
                echo "
                    \"errors\": [
                        \"status\": \"403\",
                        \"source\": { \"pointer\" : \"/protected/models/LectureModel/addLecture\"},
                        \"title\": \"Collision\",
                        \"description\": \" Lecture {$data['title']} already exists \" 
                    ]
                ";
                exit();
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO lectures(title, content, id_lesson) VALUES(:title, :content, :id_lesson)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if(!empty($stmt->errorInfo()[1])){
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                echo "{
                    \"errors\": [
                        {
                           \"status\": \"500\",
                           \"source\": { \"pointer\": \"/protected/models/LectureModel/addLecture\" },
                           \"title\":  \"Internal error\",
                           \"detail\": \"Error ".$stmt->errorInfo()[0].": ".$stmt->errorInfo()[2]."\"
                        }
                    ]
                }";
                exit();
            }
            return true;
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/LectureModel/addLecture\"},
                        \"title\": \"Internal error\",
                        \"description\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }

    public function isLectureCreated($title){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lecture FROM lectures WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->errorInfo()[1]) {
                header("HTTP/1.1 500 Internal Server Error", true, 500);
                echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/LectureModel/isLectureCreated\"},
                        \"title\": \"Internal error\",
                        \"description\": \" Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2] . "\" 
                    ]
                ";
                exit();
            }
            $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($lecture['id_lecture']);
        }catch (PDOException $e){
            header("HTTP/1.1 500 Internal Server Error", true, 500);
            echo "
                    \"errors\": [
                        \"status\": \"500\",
                        \"source\": { \"pointer\" : \"/protected/models/LectureModel/isLectureCreated\"},
                        \"title\": \"Internal error\",
                        \"description\": \"" . $e->getMessage() . "\"
                    ]
                ";
            exit();
        }
    }
}