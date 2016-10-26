<?php
class QuestionService{
    public function addQuestion(array $questionContent, $id_test){
        $questionModel = new QuestionModel();
        $questionData = [
            'question' => $questionContent['question'],
            'points' => $questionContent['points'],
            'date' => time(),
            'id_test' => $id_test
        ];
        $id_question = $questionModel->addQuestion($questionData);
        if (!empty($id_question)) {
            $answerService = new AnswerService();
            $answerService->addAnswer($questionContent['correct_answer'], $id_question, 1);
            foreach ($questionContent['incorrect_answers'] as $incorrectAnswer) {
                $answerService->addAnswer($incorrectAnswer, $id_question);
            }
        }
    }

    public function getQuestionsList($id_test){
        $questionModel = new QuestionModel();
        $questionsList = $questionModel->getQuestionsListByTestId($id_test);
        if (!empty($questionsList)){
            return $questionsList;
        }
        else
            throw new EntityNotFoundException("Questions list by id_test: {$id_test} was not found.");
    }

    public function deleteQuestion($id_question){
        $questionModel = new QuestionModel();
        if ($questionModel->isQuestionCreated($id_question)){
            $questionModel->deleteQuestion($id_question);
        }
        else{
            throw new EntityNotFoundException("Question with id: {$id_question} does not exist.");
        }
    }

    public function updateQuestion(array $data){
        $questionModel = new QuestionModel();
        if ($this->isQuestionCreated($data['id_question'])){
            $questionModel->updateQuestion($data);
        }
        else{
            throw new EntityNotFoundException("Question with id: {$data['id_question']} does not exist.");
        }
    }
}