<?php
class TestService{
    private static $instance = null;
    private $testModel;
    private $questionService;

    protected function __construct(TestModel $testModel, QuestionService $questionService)
    {
        $this->testModel = $testModel;
        $this->questionService = $questionService;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(TestModel::getInstance(), QuestionService::getInstance());
        }
        return self::$instance;
    }

    /**
     * Forms testInfo and adds it into tests table in DB using testModel.
     * Redirects data with question and answers to it into questionService.
     * @param array $testContent - each element consists of question, points and 4 answers to question.
     */
    public function addTest(array $testContent){
        foreach ($testContent as $question) {

        }
        $testMark = 0.0;
        for($i = 0; $i < count($testContent) - 1; $i++){
            if (!empty($testContent[$i]['points'])) {
                $testMark += $testContent[$i]['points'];
            }
        }
        $testInfo = [
            'mark' => $testMark,
            'date' => time(),
            'id_lesson' => $testContent['id_lesson']
        ];
        unset($testContent['id_lesson']);
        $id_test = $this->testModel->addTest($testInfo);
        if (!empty($id_test)) {
            foreach ($testContent as $question){
                $question['id_test'] = $id_test;
                $this->questionService->addQuestion($question);
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

    public function checkTestsEmptyness(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->checkTestsEmptyness($value);
            }
            if (empty($value)) {
                //throw new TestException("Empty ".$key."field");
            }
        }
    }
}