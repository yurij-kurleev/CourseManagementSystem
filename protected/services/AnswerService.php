<?php
class AnswerService{
    public function addAnswer(array $data, $is_correct = 0){
        $answerModel = new AnswerModel();
        if ($answerModel->addAnswer($data, $is_correct)){
            return true;
        }
    }

    public function getAnswersList($id_question){
        $answerModel = new AnswerModel();
        $answersList = $answerModel->getAnswersListByQuestionId($id_question);
        if (!empty($answersList) && !is_null($answersList)){
            return $answersList;
        }
    }

    public function deleteAnswer($id_answer){
        $answerModel = new AnswerModel();
        if ($answerModel->deleteAnswer($id_answer)){
            return true;
        }
    }

    public function updateAnswer(array $data){
        $answerModel = new AnswerModel();
        if ($answerModel->updateAnswer($data)){
            return true;
        }
    }
}