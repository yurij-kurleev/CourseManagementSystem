<?php
class TestService{
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
        $testModel = new TestModel();
        $id_test = $testModel->addTest($testInfo);
        if (!empty($id_test)) {
            foreach ($testContent as $question){
                $questionService = new QuestionService();
                $questionService->addQuestion($question, $id_test);
            }
        }
    }

    public function getTest($id_lesson){
        $testModel = new TestModel();
        $test = $testModel->getTestByLessonId($id_lesson);
        if (!empty($test)){
            $questionService = new QuestionService();
            $questionsList = $questionService->getQuestionsList($test['id_test']);
            $test['questions'] = $questionsList;
            return $test;
        }
        else
            throw new EntityNotFoundException("Test by id_lesson: {$id_lesson} was not found.");
    }

    public function deleteTest($id_test){
        $testModel = new TestModel();
        if ($this->isTestCreated($id_test)){
            $testModel->deleteTest($id_test);
        }
        else{
            throw new EntityNotFoundException("Test with id: {$id_test} does not exist.");
        }
    }

    public function updateTest(array $data){
        $testModel = new TestModel();
        if ($this->isTestCreated($data['id_test'])){
            $testModel->updateTest($data);
        }
        else
            throw new EntityNotFoundException("Test with id: {$data['id_test']} does not exist.");
    }
}