<?php
class QuestionModel{
    public function addQuestion(array $data){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO questions(question, points, date, id_test) VALUES (:question, :points, :date, :id_test)";
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

    public function isQuestionCreated($id_question){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_question FROM questions WHERE id_question = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $question = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($question['id_question']);
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isQuestionExistsByTestId($id_test){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_question FROM questions WHERE id_test = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $question = $stmt->fetch(PDO::FETCH_ASSOC);
            return $question['id_question'];
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function getQuestionsListByTestId($id_test){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM questions WHERE  id_test = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $questionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $questionsList;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteQuestion($id_question){
        try{
            if ($this->isQuestionCreated($id_question)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM questions WHERE id_question = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
                $stmt->execute();
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new QuestionNotFoundException("Question with id: {$id_question} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function updateQuestion(array $data){
        try{
            if ($this->isQuestionCreated($data['id_question'])){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "UPDATE questions SET question = :question, points = :points WHERE id_question = :id_question";
                $stmt = $link->prepare($sql);
                $stmt->execute(array(':question' => $data['question'], 'points' => $data['points'], ':id_question' => $data['id_question']));
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new QuestionNotFoundException("Question with id: {$data['id_question']} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
}