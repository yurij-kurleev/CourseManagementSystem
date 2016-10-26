<?php
class LectureController{
    public function addLectureAction(){
        $lectureService = new LectureService();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'content' => strip_tags(trim($_POST['content'])),
            'date' => time(),
            'id_lesson' => strip_tags(trim($_POST['id_lesson']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            if ($lectureService->addLecture($data)) {
                http_response_code(201);
            }
        }catch (EntityAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function getLectureAction(){
        $lectureService = new LectureService();
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_lesson");
        }
        try{
            $lecture = $lectureService->getLecture($id_lesson);
            FrontController::getInstance()->setBody(json_encode($lecture));
        }catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function deleteLectureAction(){
        $lectureService = new LectureService();
        $id_lecture = strip_tags(trim($_POST['id_lecture']));
        if (empty($id_lecture)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_lecture");
        }
        try{
            if ($lectureService->deleteLecture($id_lecture)){
                http_response_code(200);
            }
        }catch (LectureNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Mot found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function updateLectureAction(){
        $lectureService = new LectureService();
        $data = [
            'id_lecture' =>strip_tags(trim($_POST['id_lecture'])),
            'title' => strip_tags(trim($_POST['title'])),
            'content' => strip_tags(trim($_POST['content']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            if ($lectureService->updateLecture($data)) {
                http_response_code(200);
            }
        }catch (LectureAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, 'Collision', $e->getMessage());
        }
        catch (LectureNotFoundException $e){
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