<?php
class CourseService{
    public function addCourse(array $data){
        $courseModel = new CourseModel();
        if ($courseModel->addCourse($data)){
            return true;
        }
    }
    
    public function getCourse($course_title){
        $courseModel = new CourseModel();
        $course = $courseModel->getCourseByTitle($course_title);
        if(!empty($course) && !is_null($course)){
            return $course;
        }
    }
    
    public function getCoursesList($email_lecturer){
        $courseModel = new CourseModel();
        $coursesList = $courseModel->getCoursesListByLecturerEmail($email_lecturer);
        if (!empty($coursesList) && !is_null($coursesList)){
            return $coursesList;
        }
    }
    
    public function deleteCourse($course_title){
        $courseModel = new CourseModel();
        if ($courseModel->deleteCourse($course_title)){
            return true;
        }
    }
    
    public function updateCourse(array $data){
        $courseModel = new CourseModel();
        if ($courseModel->updateCourse($data)){
            return true;
        }
    }

    public function checkCourseExistence($id_course){
        $courseModel = new CourseModel();
        if ($courseModel->isCourseCreated($id_course)){
            return true;
        }
        else{
            throw new CourseNotFoundException("Course with id: {$id_course} does not exists.");
        }
    }
}