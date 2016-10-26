<?php
class TestController{
    public function addTestAction(){
        $testService = new TestService();
        $data = [
            'mark' => strip_tags(trim($_POST['mark'])),
            'date' => time(),
            'id_lesson' => strip_tags(trim($_POST['id_lesson']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($testService->addTest($data)){
                http_response_code(201);
            }
        }catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function getTestAction(){
        $testService = new TestService();
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_lesson");
        }
        try{
            $test = $testService->getTest($id_lesson);
            FrontController::getInstance()->setBody(json_encode($test));
        }catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function deleteTestAction(){
        $testService = new TestService();
        $id_test = strip_tags(trim($_POST['id_test']));
        if (empty($id_test)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_test");
        }
        try{
            if ($testService->deleteTest($id_test)){
                http_response_code(200);
            }
        }catch (TestNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function updateTestAction(){
        $testService = new TestService();
        $data = [
            'id_test' => strip_tags(trim($_POST['id_test'])),
            'mark' => strip_tags(trim($_POST['mark']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($testService->updateTest($data)){
                http_response_code(200);
            }
        }catch (TestNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
}