<?php
class LessonController{
    public function addLessonAction(){
        $lessonService = new LessonService();
        $data = json_decode(file_get_contents("php://input"),true);
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try{
            if ($lessonService->addLesson($data)){
                http_response_code(201);
            }
        }catch (LessonAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }

    public function getLessonsListAction(){
        $lessonService = new LessonService();
        $id_course = strip_tags(trim($_POST['id_course']));
        if (empty($id_course)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_course`");
        }
        try{
            $courseService = new CourseService();
            if ($courseService->checkCourseExistence($id_course)){
                $lessonList = $lessonService->getLessonsList($id_course);
                for ($i = 0; $i < count($lessonList) ; $i++){
                    //add test + lecture
                    $lectureService = new LectureService();
                    $lecture = $lectureService->getLecture($lessonList[$i]['id_lesson']);
                    $lessonList[$i]['lecture'] = $lecture;
                }
                FrontController::getInstance()->setBody(json_encode($lessonList));
            }
        }catch (CourseNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
    
    public function getLessonAction(){
        $lessonService = new LessonService();
        $id_lesson = strip_tags(trim($_POST['id_lesson']));
        if (empty($id_lesson)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `id_lesson`");
        }
        try{
            $lesson = $lessonService->getLesson($id_lesson);
            FrontController::getInstance()->setBody(json_encode($lesson));
        }catch (LessonNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutingException $e){
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
            if ($lessonService->deleteLesson($id_lesson)){
                http_response_code(200);
            }
        }catch (LessonNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Not found", $e->getMessage());
        }
        catch (StatementExecutingException $e){
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
            if ($lessonService->updateLesson($data)) {
                http_response_code(200);
            }
        }catch (LessonAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (LecturerNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Not found", $e->getMessage());
        }
        catch (StatementExecutingException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, 'Internal Error', $e->getMessage());
        }
    }
}