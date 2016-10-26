<?php
class CourseController{
    public function addCourseAction(){
        $courseService = new CourseService();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'description' => strip_tags(trim($_POST['description'])),
            'date' => time(),
            'id_auth' => strip_tags(trim($_POST['id_auth']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $courseService->addCourse($data);
            http_response_code(201);
        }catch (EntityAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }

    public function getCourseAction(){
        $courseService = new CourseService();
        $course_title = strip_tags(trim($_POST['title']));
        if (empty($course_title)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: 'course_title'");
        }
        try {
            $course = $courseService->getCourse($course_title);
            FrontController::getInstance()->setBody(json_encode($course));
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }
    
    public function getCoursesListAction(){
        $courseService = new CourseService();
        $email_lecturer = strip_tags(trim($_POST['email']));
        if (empty($email_lecturer)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: 'email_lecturer'");
        }
        try{
            $coursesList = $courseService->getCoursesList($email_lecturer);
            FrontController::getInstance()->setBody(json_encode($coursesList));
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }
    
    public function getAllCoursesListAction(){
        $courseService = new CourseService();
        try{
            $allCoursesList = $courseService->getAllCoursesList();
            FrontController::getInstance()->setBody(json_encode($allCoursesList));
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }

    public function deleteCourseAction(){
        $courseService = new CourseService();
        $course_title = strip_tags(trim($_POST['title']));
        if (empty($course_title)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: 'course_title'");
        }
        try {
            $courseService->deleteCourse($course_title);
            HTTPResponseBuilder::getInstance()->sendSuccessRespond(200);
        }catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", "Deleting course failed. " . $e->getMessage());
        }
    }
    
    public function updateCourseAction(){
        $courseService = new CourseService();
        $data = [
            'id_course' => strip_tags(trim($_POST['id_course'])),
            'title' => strip_tags(trim($_POST['title'])),
            'description' => strip_tags(trim($_POST['description']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $courseService->updateCourse($data);
            http_response_code(200);
        }catch (EntityAlreadyExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (EntityNotFoundException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, 'Not found', $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", "Updating course failed. " . $e->getMessage());
        }
    }
}