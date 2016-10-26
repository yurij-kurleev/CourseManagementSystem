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
            $questionService = new QuestionService();
            foreach ($testContent as $question){
                $questionService->addQuestion($question, $id_test);
            }
        }
    }

    public function getTest($id_lesson){
        $testModel = new TestModel();
        $test = $testModel->getTestByLessonId($id_lesson);
        if (!empty($test) && !is_null($test)){
            return $test;
        }
    }

    public function deleteTest($id_test){
        $testModel = new TestModel();
        if ($testModel->deleteTest($id_test)){
            return true;
        }
    }

    public function updateTest(array $data){
        $testModel = new TestModel();
        if ($testModel->updateTest($data)){
            return true;
        }
    }
}