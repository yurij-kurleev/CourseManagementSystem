<?php
class CourseController{
    private $courseService;

    public function __construct()
    {
        $this->courseService = CourseService::getInstance();
    }

    public function addCourseAction(){
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
            $this->courseService->addCourse($data);
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
        $data = [
            'course_title' => strip_tags(trim($_POST['course_title'])),
            'id_u' => strip_tags(trim($_POST['id_u']))
        ];
        foreach ($data as $key => $value) {
            if (empty($value)) {
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $course = $this->courseService->getCourse($data);
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
        $email_lecturer = strip_tags(trim($_POST['email']));
        if (empty($email_lecturer)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: 'email_lecturer'");
        }
        try{
            $coursesList = $this->courseService->getCoursesList($email_lecturer);
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
        try{
            $allCoursesList = $this->courseService->getAllCoursesList();
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

    public function getUserSubscriptionsListAction(){
        $id_user = strip_tags(trim($_POST['id_user']));
        if (empty($id_user)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: id_user");
        }
        try{
            $userSubscriptionList = $this->courseService->getUserSubscriptionsList($id_user);
            FrontController::getInstance()->setBody(json_encode($userSubscriptionList));
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
        $course_title = strip_tags(trim($_POST['title']));
        if (empty($course_title)){
            HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: 'course_title'");
        }
        try {
            $this->courseService->deleteCourse($course_title);
            http_response_code(200);
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
            $this->courseService->updateCourse($data);
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