<?php
class AnswerService{
    private static $instance = null;
    private $answerModel;

    protected function __construct(AnswerModel $answerModel)
    {
        $this->answerModel = $answerModel;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(AnswerModel::getInstance());
        }
        return self::$instance;
    }

    public function addAnswer($answerData){
        $this->answerModel->addAnswer($answerData);
    }

    public function getAnswersList($id_question){
        $answersList = $this->answerModel->getAnswersListByQuestionId($id_question);
        if (!empty($answersList)){
            return $answersList;
        }
        else
            throw new EntityNotFoundException("Answers list by id_question: {$id_question} were not found.");
    }

    public function deleteAnswer($id_answer){
        if ($this->answerModel->isAnswerCreated($id_answer)) {
            $this->answerModel->deleteAnswer($id_answer);
        }
        else{
            throw new EntityNotFoundException("Answer with id: {$id_answer} does not exist.");
        }
    }

    public function updateAnswer(array $data){
        if ($this->answerModel->isAnswerCreated($data['id_answer'])) {
            $this->answerModel->updateAnswer($data);
        }
        else{
            throw new EntityNotFoundException("Answer with id: {$data['id_answer']} does not exist.");
        }
    }
}