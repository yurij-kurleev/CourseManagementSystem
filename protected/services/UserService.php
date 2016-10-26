<?php
class UserService{
    private $userModel;
    private $courseModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
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
        $userModel = new UserModel();
        $this->isUserExists($data['']);
        $this->isCourseExists($data['']);
        $userModel->subscribeOnCourse($data);
    }

    protected function isUserExists($userId)
    {
        $userInfo = $this->userModel->getUserById($userId);
        if (empty($userInfo)) {
            throw new UserException("No such user with id: " . $userId);
        }
    }

    protected function isCourseExists($courseId)
    {
        $courseInfo = $this->courseModel->isCourseCreated($courseId);
        if (!$courseInfo) {
            throw new CourseException("No such course with id: " . $courseId);
        }
    }

    public function unsubscribeFromCourse(array $data)
    {
        $userModel = new UserModel();
        $courseModel = new CourseModel();
        $userInfo = $userModel->getUserById($data['id_user']);
        $courseInfo = $courseModel->isCourseCreated($data['id_course']);
        if (empty($userInfo)) {
            throw new UserException("No such user with id: " . $data['id_user']);
        }
        if (!$courseInfo) {
            throw new CourseException("No such course with id: " . $data['id_course']);
        }
        $userModel->subscribeOnCourse($data);
    }
}