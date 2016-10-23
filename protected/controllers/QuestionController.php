<?php
class QuestionController{
    public function addQuestionAction(){
        $questionService = new QuestionService();
        $data = [
            'question' => strip_tags(trim($_POST['question'])),
            'points' => strip_tags(trim($_POST['points'])),
            'date' => time(),
            'id_test' => strip_tags(trim($_POST['id_test']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($questionService->addQuestion($data)){
                http_response_code(201);
            }
        }catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function getQuestionsListAction(){
        $questionService = new QuestionService();
        $id_test = strip_tags(trim($_POST['id_test']));
        if (empty($id_test)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_test");
        }
        try{
            $questionsList = $questionService->getQuestionsList($id_test);
            FrontController::getInstance()->setBody(json_encode($questionsList));
        }catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function deleteQuestionAction(){
        $questionService = new QuestionService();
        $id_question = strip_tags(trim($_POST['id_question']));
        if (empty($id_question)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_test");
        }
        try{
            if ($questionService->deleteQuestion($id_question)){
                http_response_code(200);
            }
        }catch (QuestionNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function updateQuestionAction(){
        $questionService = new QuestionService();
        $data = [
            'id_question' => strip_tags(trim($_POST['id_question'])),
            'question' => strip_tags(trim($_POST['question'])),
            'points' => strip_tags(trim($_POST['points']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($questionService->updateQuestion($data)){
                http_response_code(200);
            }
        }catch (QuestionNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
}