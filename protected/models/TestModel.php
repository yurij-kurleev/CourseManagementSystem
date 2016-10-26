<?php
class TestModel extends Model{
    public function addTest(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO tests(mark, date, id_lesson) VALUES (:mark, :date, :id_lesson)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
        return $this->getTestIdByLessonId($data['id_lesson']);
    }

    public function isTestCreated($id_test){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_test FROM tests WHERE id_test = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
            $stmt->execute();
            TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
            $test = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($test['id_test']);
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function getTestIdByLessonId($id_lesson){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_test FROM tests WHERE id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
        $stmt->execute();
        TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $test = $stmt->fetch(PDO::FETCH_ASSOC);
        return $test['id_test'];
    }

    public function getTestByLessonId($id_lesson){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM tests WHERE  id_lesson = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
            $stmt->execute();
            TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
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
                TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
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
                TestModel::checkErrorArrayEmptiness($stmt->errorInfo());
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