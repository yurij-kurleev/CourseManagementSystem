<?php
class UserService{
    private $userModel;
    private $courseModel;

    public function __construct(UserModel $userModel, CourseModel $courseModel)
    {
        $this->userModel = $userModel;
        $this->courseModel = $courseModel;
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
        $this->isUserExists($data['id_u']);
        $this->isCourseExists($data['id_course']);
        $this->userModel->subscribeOnCourse($data);
    }

    public function isUserExists($userId)
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
        $this->isUserExists($data['id_u']);
        $this->isCourseExists($data['id_course']);
        $this->userModel->unsubscribeFromCourse($data);
    }
}