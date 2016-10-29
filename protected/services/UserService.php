<?php
class UserService{
    private static $instance = null;
    private $userModel;
    private $courseModel;

    protected function __construct(UserModel $userModel, CourseModel $courseModel)
    {
        $this->userModel = $userModel;
        $this->courseModel = $courseModel;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(UserModel::getInstance(), CourseModel::getInstance());
        }
        return self::$instance;
    }

    public function registerUser(array $data){
        if ($this->userModel->isRegistered($data['email'])) {
            throw new UserExistsException("User {$data['email']}:{$data['password']} already exists");
        }
        $this->userModel->addUser($data);
            mail($data['email'], "Course Management System",
                "Congratulations, " . $data['name'] . "! You've been successfully registered in Course Management System.
                 Have a nice day :)");
    }

    public function authUser(array $data){
        $userInfo = $this->userModel->getUserByEmailPassword($data);
        if (empty($userInfo)){
            throw new AuthorizationException("No such user or password is not correct!");
        }
        else
            return $userInfo;
    }

    public function subscribeOnCourse(array $data)
    {
        if ($this->userModel->getUserById($data['id_u'])){
            if ($this->courseModel->isCourseCreated($data['id_course'])){
                $this->userModel->subscribeOnCourse($data);
            }
            else
                throw new EntityNotFoundException("Course with id: {$data['id_course']} was not found.");
        }
        else
            throw new EntityNotFoundException("User with id: {$data['id_u']} was not found.");
    }

    public function unsubscribeFromCourse(array $data)
    {
        if ($this->userModel->getUserById($data['id_u'])) {
            if ($this->courseModel->isCourseCreated($data['id_course'])) {
                $this->userModel->unsubscribeFromCourse($data);
            } else
                throw new EntityNotFoundException("Course with id: {$data['id_course']} was not found.");
        } else
            throw new EntityNotFoundException("User with id: {$data['id_u']} was not found.");
    }
}