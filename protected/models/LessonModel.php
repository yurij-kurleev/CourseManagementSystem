<?php
class LessonModel{
    public function addLesson(array $data){
        try{
            if ($this->isLessonWithTitleExists($data['title'])){
                throw new LessonAlreadyExistsException("Lesson {$data['title']} already exists.");
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO lessons(title, date, id_course) VALUES (:title, :date, :id_course)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if (!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            return true;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isLessonWithTitleExists($title){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lesson FROM lessons WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lesson['id_lesson'];
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isLessonCreated($id_lesson){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lesson FROM lessons WHERE id_lesson = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($lesson['id_lesson']);
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function getLessonsListByCourseId($id_course){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_lesson, title, date FROM lessons WHERE id_course = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_course, PDO::PARAM_INT);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $lessonsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $lessonsList;
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function getLessonById($id_lesson){
        try{
            if ($this->isLessonCreated($id_lesson)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "SELECT * FROM lessons WHERE id_lesson = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
                $stmt->execute();
                if (!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
                return $lesson;
            }
            else{
                throw new LessonNotFoundException("Lesson with id: {$id_lesson} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteLesson($id_lesson){
        try{
            if ($this->isLessonCreated($id_lesson)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM lessons WHERE id_lesson = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
                $stmt->execute();
                if (!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error ". $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new LessonNotFoundException("Lesson with id: " . $id_lesson . "does not exists.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function updateLesson(array $data){
        try{
            if ($this->isLessonCreated($data['id_lesson'])) {
                if (!$this->isLessonWithTitleExists($data['title'])) {
                    $link = PDOConnection::getInstance()->getConnection();
                    $sql = "UPDATE lessons SET title = :title WHERE id_lesson = :id_lesson";
                    $stmt = $link->prepare($sql);
                    $stmt->execute(array(':title' => $data['title'], ':id_lesson' => $data['id_lesson']));
                    if (!empty($stmt->errorInfo()[1])) {
                        throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                    }
                    return true;
                }
                else{
                    throw new LessonAlreadyExistsException("Lesson with title: {$data['title']} already exists.");
                }
            }
            else{
                throw new LessonNotFoundException("Lesson with id: {$data['id_lesson']} does not exist");
            }
        }catch (PDOException $e){ 
            throw $e;
        }
    }
}