<?php
class QuestionService{
    private static $instance = null;
    private $questionModel;
    private $answerService;

    protected function __construct(QuestionModel $questionModel, AnswerService $answerService)
    {
        $this->questionModel = $questionModel;
        $this->answerService = $answerService;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(QuestionModel::getInstance(), AnswerService::getInstance());
        }
        return self::$instance;
    }

    public function addQuestion(array $questionContent, $id_test){
        $questionData = [
            'question' => $questionContent['question'],
            'points' => $questionContent['points'],
            'date' => time(),
            'id_test' => $id_test
        ];
        $id_question = $this->questionModel->addQuestion($questionData);
        if (!empty($id_question)) {
            $this->answerService->addAnswer($questionContent['correct_answer'], $id_question, 1);
            foreach ($questionContent['incorrect_answers'] as $incorrectAnswer) {
                $this->answerService->addAnswer($incorrectAnswer, $id_question);
            }
        }
    }

    public function getQuestionsList($id_test){
        $questionsList = $this->questionModel->getQuestionsListByTestId($id_test);
        if (!empty($questionsList)){
            foreach ($questionsList as $questionListItem){
                $answersList = $this->answerService->getAnswersList($questionListItem['id_question']);
                $questionListItem['answers'] = $answersList;
            }
            return $questionsList;
        }
        else
            throw new EntityNotFoundException("Questions list by id_test: {$id_test} was not found.");
    }

    public function deleteQuestion($id_question){
        if ($this->questionModel->isQuestionCreated($id_question)) {
            $this->questionModel->deleteQuestion($id_question);
        }
        else{
            throw new EntityNotFoundException("Question with id: {$id_question} does not exist.");
        }
    }

    public function updateQuestion(array $data){
        if ($this->questionModel->isQuestionCreated($data['id_question'])) {
            $this->questionModel->updateQuestion($data);
        }
        else{
            throw new EntityNotFoundException("Question with id: {$data['id_question']} does not exist.");
        }
    }
}