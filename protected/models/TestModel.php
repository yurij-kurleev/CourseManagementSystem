<?php
class TestModel{
    public function addTest(array $data){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO tests(mark, date, id_lesson) VALUES (:mark, :date, :id_lesson)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            return true;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isTestCreated($id_test){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_test FROM tests WHERE id_test = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $test = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($test['id_test']);
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function isTestExistByLessonId($id_lesson){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_test FROM tests WHERE id_lesson = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $test = $stmt->fetch(PDO::FETCH_ASSOC);
            return $test['id_test'];
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function getTestByLessonId($id_lesson){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM tests WHERE  id_lesson = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $test = $stmt->fetch(PDO::FETCH_ASSOC);
            return $test;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteTest($id_test){
        try{
            if ($this->isTestCreated($id_test)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM tests WHERE id_test = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
                $stmt->execute();
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new TestNotFoundException("Test with id: {$id_test} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function updateTest(array $data){
        try{
            if ($this->isTestCreated($data['id_test'])){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "UPDATE tests SET mark = :mark WHERE id_test = :id_test";
                $stmt = $link->prepare($sql);
                $stmt->execute(array(':mark' => $data['mark'], ':id_test' => $data['id_test']));
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new TestNotFoundException("Test with id: {$data['id_test']} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
}