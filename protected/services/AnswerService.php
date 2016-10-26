<?php
class AnswerService{
    public function addAnswer($answer, $id_question, $is_correct = 0){
        $answerData = [
            'answer' => $answer,
            'date' => time(),
            'is_correct' => $is_correct,
            'id_question' => $id_question
        ];
        $answerModel = new AnswerModel();
        $answerModel->addAnswer($answerData);
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