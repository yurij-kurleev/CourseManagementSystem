<?php
class TestService{
    public function addTest(array $data){
        $testMark = 0.0;
        for($i = 0; $i < count($data['questions']); $i++){
            $testMark += (float)$data['questions'][$i]['points'];
        }
        $testData = [
            'mark' => $testMark,
            'date' => $data['date'],
            'id_lesson' => $data['id_lesson']
        ];
        $testModel = new TestModel();
        if ($testModel->addTest($testData)){
            $id_test = $testModel->isTestExistByLessonId($data['id_lesson']);
            if (!empty($id_test)){
                $questionService = new QuestionService();
                for($i = 0; $i < count($data['questions']); $i++){
                    $data['questions'][$i]['date'] = time();
                    $data['questions'][$i]['id_test'] = $id_test;
                    $questionService->addQuestion($data['questions'][$i]);
                }
                return true;
            }
        }
        else{
            return false;
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