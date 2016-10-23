<?php
class AnswerModel{
    public function addAnswer(array $data, $is_correct){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO answers(answer, date, is_correct, id_question) VALUES (:answer, :date, :is_correct, :id_question)";
            $stmt = $link->prepare($sql);
            $stmt->execute(array(':answer' => $data['answer'], ':date' => $data['date'], ':is_correct' => $is_correct,
                ':id_question' => $data['id_question']));
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            return true;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isAnswerCreated($id_answer){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_answer FROM answers WHERE id_answer = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_answer, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $answer = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($answer['id_answer']);
        }catch (PDOException $e){

        }
    }

    public function getAnswerListByQuestionId($id_question){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT * FROM answers WHERE  id_question = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
            $stmt->execute();
            if(!empty($stmt->errorInfo()[1])){
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $answersList = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $answersList;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteAnswer($id_answer){
        try{
            if ($this->isAnswerCreated($id_answer)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM answers WHERE id_answer = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $id_answer, PDO::PARAM_INT);
                $stmt->execute();
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new AnswerNotFoundException("Answer with id: {$id_answer} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function updateAnswer(array $data){
        try{
            if ($this->isAnswerCreated($data['id_answer'])){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "UPDATE answers SET answer = :answer WHERE id_answer = :id_answer";
                $stmt = $link->prepare($sql);
                $stmt->execute(array(':answer' => $data['answer'], ':id_answer' => $data['id_answer']));
                if(!empty($stmt->errorInfo()[1])){
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new AnswerNotFoundException("Answer with id: {$data['id_answer']} does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
}