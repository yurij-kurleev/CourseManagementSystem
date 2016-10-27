<?php
class UserController{
    private $userService;

    public function __construct()
    {
        $this->userService = UserService::getInstance();
    }

    public function registerUserAction(){
        $data = [
            'name' => strip_tags(trim($_POST['name'])),
            'email' => strip_tags(trim($_POST['email'])),
            'password' => hash("sha256", strip_tags(trim($_POST['password']))),
            'role' => strip_tags(trim($_POST['role'])),
            'register_date' => time()
        ];
        foreach ($data as $key=>$value){
            if(empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $this->userService->registerUser($data);
            http_response_code(201);
        }catch (UserExistsException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(403, "Collision", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }

    public function meAction(){
        $data = [
            'email' => strip_tags(trim($_POST['email'])),
            'password' => hash("sha256", strip_tags(trim($_POST['password'])))
        ];
        foreach ($data as $key=>$value){
            if (empty($value)){
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $userInfo = $this->userService->authUser($data);
            FrontController::getInstance()->setBody(json_encode($userInfo));
        } catch (PDOException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (StatementExecutionException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
        catch (AuthorizationException $e){
            HTTPResponseBuilder::getInstance()->sendFailRespond(401, "User unauthorized", $e->getMessage());
        }
    }

    public function subscribeAction()
    {
        $data = [
            'id_course' => strip_tags(trim($_POST['id_course'])),
            'id_u' => strip_tags(trim($_POST['id_u']))
        ];
        foreach ($data as $key => $value) {
            if (empty($value)) {
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        $data['date'] = time();
        try {
            $this->userService->subscribeOnCourse($data);
        } catch (PDOException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        } catch (UserException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "User not found", $e->getMessage());
        } catch (CourseException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Course not found", $e->getMessage());
        } catch (StatementExecutionException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }

    public function unsubscribeAction()
    {
        $data = [
            'id_course' => strip_tags(trim($_POST['id_course'])),
            'id_u' => strip_tags(trim($_POST['id_u']))
        ];
        foreach ($data as $key => $value) {
            if (empty($value)) {
                HTTPResponseBuilder::getInstance()->sendFailRespond(400, "Missing params", "Missing param: `$key`");
            }
        }
        try {
            $this->userService->unsubscribeFromCourse($data);
        } catch (PDOException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        } catch (UserException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "User not found", $e->getMessage());
        } catch (CourseException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(404, "Course not found", $e->getMessage());
        } catch (StatementExecutionException $e) {
            HTTPResponseBuilder::getInstance()->sendFailRespond(500, "Internal error", $e->getMessage());
        }
    }
}