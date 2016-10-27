<?php
class LessonController{
    private $lessonService;

    public function __construct()
    {
        $this->lessonService = LessonService::getInstance();
    }

    public function addLessonAction(){
        $data = json_decode(file_get_contents("php://input"), true);
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (empty($value)) {
                    HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
                }
            }
        } else {
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Data missed", "Lesson data is empty");
        }
        try{
            $this->lessonService->addLesson($data);
            http_response_code(201);
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

    public function getLessonsListAction(){
        $id_course = strip_tags(trim($_POST['id_course']));
        if (empty($id_course)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_course`");
        }
        try{
            $lessonList = $this->lessonService->getLessonsList($id_course);
            FrontController::getInstance()->setBody(json_encode($lessonList));
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function getLessonAction(){
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_lesson`");
        }
        try{
            $lesson = $this->lessonService->getLesson($id_lesson);
            FrontController::getInstance()->setBody(json_encode($lesson));
        }catch (EmptyEntityException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function deleteLessonAction(){
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_lesson`");
        }
        try{
            $this->lessonService->deleteLesson($id_lesson);
            http_response_code(200);
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Not found", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function updateLessonAction(){
        $data = [
            'id_lesson' => strip_tags(trim($_POST['id_lesson'])),
            'title' => strip_tags(trim($_POST['title'])),
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `title`");
            }
        }
        try {
            $this->lessonService->updateLesson($data);
            http_response_code(200);
        }catch (EntityAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Not found", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
}