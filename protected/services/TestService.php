<?php
class TestService{
    private $testModel;
    private $questionService;

    public function __construct(TestModel $testModel, QuestionService $questionService)
    {
        $this->testModel = $testModel;
        $this->questionService = $questionService;
    }

    /**
     * Forms testInfo and adds it into tests table in DB using testModel.
     * Redirects data with question and answers to it into questionService.
     * @param array $testContent - each element consists of question, points and 4 answers to question.
     * @param $id_lesson - which lesson this test related with.
     */
    public function addTest(array $testContent, $id_lesson){
        $testMark = 0.0;
        foreach ($testContent as $question){
            $testMark += (float)$question['points'];
        }
        $testInfo = [
            'mark' => $testMark,
            'date' => time(),
            'id_lesson' => $id_lesson
        ];
        $id_test = $this->testModel->addTest($testInfo);
        if (!empty($id_test)) {
            foreach ($testContent as $question){
                $this->questionService->addQuestion($question, $id_test);
            }
        }
    }

    public function getTest($id_lesson){
        $test = $this->testModel->getTestByLessonId($id_lesson);
        if (!empty($test)){
            $questionsList = $this->questionService->getQuestionsList($test['id_test']);
            $test['questions'] = $questionsList;
            return $test;
        }
        else
            throw new EntityNotFoundException("Test by id_lesson: {$id_lesson} was not found.");
    }

    public function deleteTest($id_test){
        if ($this->testModel->isTestCreated($id_test)) {
            $this->testModel->deleteTest($id_test);
        }
        else{
            throw new EntityNotFoundException("Test with id: {$id_test} does not exist.");
        }
    }

    public function updateTest(array $data){
        if ($this->testModel->isTestCreated($data['id_test'])) {
            $this->testModel->updateTest($data);
        }
        else
            throw new EntityNotFoundException("Test with id: {$data['id_test']} does not exist.");
    }
}