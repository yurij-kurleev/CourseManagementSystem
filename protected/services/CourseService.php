<?php
class CourseService{
    private static $instance = null;
    private $courseModel;
    private $userModel;

    protected function __construct(CourseModel $courseModel, UserModel $userModel)
    {
        $this->courseModel = $courseModel;
        $this->userModel = $userModel;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(CourseModel::getInstance(), UserModel::getInstance());
        }
        return self::$instance;
    }

    public function addCourse(array $data){
        if ($this->courseModel->isCourseWithTitleExists($data['title'])) {
            throw new EntityAlreadyExistsException("Course {$data['title']} already exists.");
        }
        $this->courseModel->addCourse($data);
    }

    public function getCourse(array $data)
    {
        if ($this->userModel->getUserById($data['id_u'])) {
            if ($this->courseModel->isCourseWithTitleExists($data['course_title'])) {
                $course = $this->courseModel->getCourseByTitle($data['course_title']);
                if (!empty($course)) {
                    $course['is_subscribed'] = $this->userModel->isSubscribed([
                        'id_u' => $data['id_u'],
                        'id_course' => $course['id_course']
                    ]);
                    return $course;
                }
            } else
                throw new EntityNotFoundException("Course with title: " . $data['course_title'] . " was not found.");
        }
        else
            throw new EntityNotFoundException("User with id: " . $data['id_u'] . " was not found.");
    }
    
    public function getCoursesList($email_lecturer){
        if (!$this->userModel->isRegistered($email_lecturer)) {
            throw new EntityNotFoundException("Lecturer with email: " . $email_lecturer . " was not found.");
        }
        $coursesList = $this->courseModel->getCoursesListByLecturerEmail($email_lecturer);
        if (!empty($coursesList)) {
            return $coursesList;
        }
        else
            throw new EntityNotFoundException("Courses list by email: {$email_lecturer} was not found.");
    }

    public function getAllCoursesList(){
        $allCoursesList = $this->courseModel->getAllCoursesList();
        if (!empty($allCoursesList)){
            return $allCoursesList;
        }
        else
            throw new EntityNotFoundException("No courses in DB.");
    }

    public function getUserSubscriptionsList($id_user){
        $userSubscriptionList = $this->courseModel->getCoursesListByUserSubscription($id_user);
        if (!empty($userSubscriptionList)){
            return $userSubscriptionList;
        }
        else
            throw new EntityNotFoundException("Courses which user with id: {$id_user} subscribed on was not found.");
    }
    
    public function deleteCourse($course_title){
        if ($this->courseModel->isCourseWithTitleExists($course_title)) {
            $this->courseModel->deleteCourse($course_title);
        }
        else
            throw new EntityNotFoundException("Course with title: " . $course_title ." does not exist.");
    }
    
    public function updateCourse(array $data){
        if ($this->courseModel->isCourseCreated($data['id_course'])) {
            if (!($this->courseModel->isCourseWithTitleExists($data['title']))) {
                $this->courseModel->updateCourse($data);
            }
            else
                throw new EntityAlreadyExistsException("Course with title: {$data['title']} already exists.");
        }
        else{
            throw new EntityNotFoundException("Course with id: " . $data['id_course'] ." does not exist.");
        }
    }
    
    public function checkCourseExistence($id_course){
        if ($this->courseModel->isCourseCreated($id_course)) {
            return true;
        }
        else{
            throw new EntityNotFoundException("Course with id: {$id_course} does not exists.");
        }
    }

    /*protected function isCourseExists($courseId)
    {
        $courseInfo = $this->courseModel->isCourseCreated($courseId);
        if (!$courseInfo) {
            throw new CourseException("No such course with id: " . $courseId);
        }
    }*/
}