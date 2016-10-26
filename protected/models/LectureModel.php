<?php
class LectureModel extends Model{
    public function addLecture(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO lectures(title, content, date, id_lesson) VALUES(:title, :content, :date, :id_lesson)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        return $this->getLectureIdByTitle($data['title']);
    }

    public function getLectureIdByTitle($title){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lecture FROM lectures WHERE title = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->execute();
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lecture['id_lecture'];
    }
    
    public function isLectureCreated($id_lecture){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lecture FROM lectures WHERE id_lecture = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lecture, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->errorInfo()[1]){
                throw new StatementExecutionException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($lecture['id_lecture']);
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function getLectureByLessonId($id_lesson){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM lectures WHERE id_lesson = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->errorInfo()[1]){
                throw new StatementExecutionException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lecture;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteLecture($id_lecture){
        try{
            if ($this->isLectureCreated($id_lecture)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM lectures WHERE id_lecture = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_lecture, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->errorInfo()[1]){
                    throw new StatementExecutionException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;  
            }
            else{
                throw new LectureNotFoundException("Lecture with id: {$id_lecture} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function updateLecture(array $data){
        try{
            if ($this->isLectureCreated($data['id_lecture'])){
                if ($this->getLectureIdByTitle($data['title'])){
                    $link = PDOConnection::getInstance()->getConnection();
                    $sql = "UPDATE lectures SET title = :title, content = :content WHERE id_lecture = ?";
                    $stmt = $link->prepare($sql);
                    $stmt->execute(array(':title' => $data['title'], ':content' => $data['content'], ':id_lecture' => $data['id_lecture']));
                    if ($stmt->errorInfo()[1]){
                        throw new StatementExecutionException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                    }
                    return true;
                }
                else{
                    throw new LectureAlreadyExistsException("Lecture with title {$data['title']} already exists.");
                }
            }
            else{
                throw new LectureNotFoundException("Lecture with id: {$data['id_lecture']} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
}