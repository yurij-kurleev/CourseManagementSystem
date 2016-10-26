<?php
class AnswerController{
    public function addQuestionAction(){
        $answerService = new AnswerService();
        $data = [
            'answer' => strip_tags(trim($_POST['answer'])),
            'date' => time(),
            'id_question' => strip_tags(trim($_POST['id_question']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($answerService->addAnswer($data)){
                http_response_code(201);
            }
        }catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function getAnswersListAction(){
        $answerService = new AnswerService();
        $id_question = strip_tags(trim($_POST['id_question']));
        if (empty($id_question)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_test");
        }
        try{
            $answersList = $answerService->getQuestionsList($id_question);
            FrontController::getInstance()->setBody(json_encode($answersList));
        }catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function deleteQuestionAction(){
        $answerService = new AnswerService();
        $id_answer = strip_tags(trim($_POST['id_answer']));
        if (empty($id_answer)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_test");
        }
        try{
            if ($answerService->deleteAnswer($id_answer)){
                http_response_code(200);
            }
        }catch (AnswerNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function updateQuestionAction(){
        $answerService = new AnswerService();
        $data = [
            'id_answer' => strip_tags(trim($_POST['id_answer'])),
            'answer' => strip_tags(trim($_POST['answer']))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($answerService->updateAnswer($data)){
                http_response_code(200);
            }
        }catch (AnswerNotFoundException $e){
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