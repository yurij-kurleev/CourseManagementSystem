<?php
class CourseService{
    public function addCourse(array $data){
        $courseModel = new CourseModel();
        if ($courseModel->isCourseWithTitleExists($data['title'])){
            throw new EntityAlreadyExistsException("Course {$data['title']} already exists.");
        }
        $courseModel->addCourse($data);
    }

    public function getCourse(array $data)
    {
        $courseModel = new CourseModel();
        $userModel = new UserModel();
        if ($courseModel->isCourseWithTitleExists($data['course_title'])) {
            $course = $courseModel->getCourseByTitle($data['course_title']);
            if(!empty($course)){
                $course['is_subscribed'] = !empty(
                $userModel->isSubscribed([
                    'id_u' => $data['id_u'],
                    'id_course' => $course['id_course']
                ]));
                return $course;
            }
        }
        else
            throw new EntityNotFoundException("Course with title: " . $data['course_title'] . " was not found.");
    }
    
    public function getCoursesList($email_lecturer){
        $userModel = new UserModel();
        if (!$userModel->isRegistered($email_lecturer)){
            throw new EntityNotFoundException("Lecturer with email: " . $email_lecturer . " was not found.");
        }
        $courseModel = new CourseModel();
        $coursesList = $courseModel->getCoursesListByLecturerEmail($email_lecturer);
        if (!empty($coursesList) && !is_null($coursesList)){
            return $coursesList;
        }
    }

    public function getAllCoursesList(){
        $courseModel = new CourseModel();
        $allCoursesList = $courseModel->getAllCoursesList();
        if (!empty($allCoursesList)){
            return $allCoursesList;
        }
        else
            throw new EntityNotFoundException("No courses in DB.");
    }
    
    public function deleteCourse($course_title){
        $courseModel = new CourseModel();
        if ($courseModel->isCourseWithTitleExists($course_title)){
            $courseModel->deleteCourse($course_title);
        }
        else
            throw new EntityNotFoundException("Course with title: " . $course_title ." does not exist.");
    }
    
    public function updateCourse(array $data){
        $courseModel = new CourseModel();
        if ($courseModel->isCourseCreated($data['id_course'])){
            if (!($courseModel->isCourseWithTitleExists($data['title']))){
                $courseModel->updateCourse($data);
            }
            else
                throw new EntityAlreadyExistsException("Course with title: {$data['title']} already exists.");
        }
        else{
            throw new EntityNotFoundException("Course with id: " . $data['id_course'] ." does not exist.");
        }
    }

    //it must be deleted
    public function checkCourseExistence($id_course){
        $courseModel = new CourseModel();
        if ($courseModel->isCourseCreated($id_course)){
            return true;
        }
        else{
            throw new EntityNotFoundException("Course with id: {$id_course} does not exists.");
        }
    }
}