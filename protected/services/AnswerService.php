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
        if (!empty($answersList)){
            return $answersList;
        }
        else
            throw new EntityNotFoundException("Answers list by id_question: {$id_question} were not found.");
    }

    public function deleteAnswer($id_answer){
        $answerModel = new AnswerModel();
        if ($answerModel->isAnswerCreated($id_answer)) {
            $answerModel->deleteAnswer($id_answer);
        }
        else{
            throw new EntityNotFoundException("Answer with id: {$id_answer} does not exist.");
        }
    }

    public function updateAnswer(array $data){
        $answerModel = new AnswerModel();
        if ($this->isAnswerCreated($data['id_answer'])) {
            $answerModel->updateAnswer($data);
        }
        else{
            throw new EntityNotFoundException("Answer with id: {$data['id_answer']} does not exist.");
        }
    }
}