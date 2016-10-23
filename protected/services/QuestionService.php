<?php
class QuestionService{
    public function addQuestion(array $data){
        $questionModel = new QuestionModel();
        $questionData = [
            'question' => $data['question'],
            'points' => $data['points'],
            'date' => $data['date'],
            'id_test' => $data['id_test']
        ];
        if ($questionModel->addQuestion($questionData)){
            $id_question = $questionModel->isQuestionExistsByTestId($data['id_test']);
            if (!empty($id_question)){
                $answerService = new AnswerService();
                $correctAnswerData = [
                    'answer' => $data['correct_answer'],
                    'date' => time(),
                    'id_question' => $id_question
                ];
                $answerService->addAnswer($correctAnswerData, 1);

                $incorrectAnswers = $data['incorrect_answers'];
                foreach ($incorrectAnswers as $incorrectAnswer){
                    $incorrectAnswerData = [
                        'answer' => $incorrectAnswer,
                        'date' => time(),
                        'id_question' => $id_question
                    ];
                    $answerService->addAnswer($incorrectAnswerData);
                }
                return true;
            }
            else{
                return false;
            }
        }
        return false;
    }

    public function getQuestionsList($id_test){
        $questionModel = new QuestionModel();
        $questionsList = $questionModel->getQuestionsListByTestId($id_test);
        if (!empty($questionsList) && !is_null($questionsList)){
            return $questionsList;
        }
    }

    public function deleteQuestion($id_question){
        $questionModel = new QuestionModel();
        if ($questionModel->deleteQuestion($id_question)){
            return true;
        }
    }

    public function updateQuestion(array $data){
        $questionModel = new QuestionModel();
        if ($questionModel->updateQuestion($data)){
            return true;
        }
    }
}