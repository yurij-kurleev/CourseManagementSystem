<?php
class LessonController{
    public function addLessonAction(){
        $lessonService = new LessonService(LessonModel::getInstance(),
                             new LectureService(LectureModel::getInstance()),
                             new CourseService(CourseModel::getInstance(), UserModel::getInstance()),
                             new TestService(TestModel::getInstance(),
                                 new QuestionService(QuestionModel::getInstance(),
                                     new AnswerService(AnswerModel::getInstance())
                                 )
                             )
                        );
        $data = json_decode(file_get_contents("php://input"), true);
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            $lessonService->addLesson($data);
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
        $lessonService = new LessonService(LessonModel::getInstance(),
            new LectureService(LectureModel::getInstance()),
            new CourseService(CourseModel::getInstance(), UserModel::getInstance()),
            new TestService(TestModel::getInstance(),
                new QuestionService(QuestionModel::getInstance(),
                    new AnswerService(AnswerModel::getInstance())
                )
            )
        );
        $id_course = strip_tags(trim($_POST['id_course']));
        if (empty($id_course)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_course`");
        }
        try{
            $lessonList = $lessonService->getLessonsList($id_course);
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
        $lessonService = new LessonService(LessonModel::getInstance(),
            new LectureService(LectureModel::getInstance()),
            new CourseService(CourseModel::getInstance(), UserModel::getInstance()),
            new TestService(TestModel::getInstance(),
                new QuestionService(QuestionModel::getInstance(),
                    new AnswerService(AnswerModel::getInstance())
                )
            )
        );
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_lesson`");
        }
        try{
            $lesson = $lessonService->getLesson($id_lesson);
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
        $lessonService = new LessonService();
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_lesson`");
        }
        try{
            $lessonService->deleteLesson($id_lesson);
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
        $lessonService = new LessonService();
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
            $lessonService->updateLesson($data);
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