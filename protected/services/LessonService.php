<?php
class LessonService{
    public function addLesson(array $data){
        $lessonModel = new LessonModel();
        if ($lessonModel->addLesson($data)){
            return true;
        }
    }

    public function getLessonsList($id_course){
        $lessonModel = new LessonModel();
        $lessonsList = $lessonModel->getLessonsListByCourseId($id_course);
        if (!empty($lessonsList) && !is_null($lessonsList)){
            return $lessonsList;
        }
    }
    
    public function getLesson($id_lesson){
        $lessonModel = new LessonModel();
        $lesson = $lessonModel->getLessonById($id_lesson);
        if (!empty($lesson) && !is_null($lesson)){
            return $lesson;
        }
    }
    
    public function deleteLesson($id_lesson){
        $lessonModel = new LessonModel();
        if ($lessonModel->deleteLesson($id_lesson)){
            return true;
        }
    }

    public function updateLesson(array $data){
        $lessonModel = new LessonModel();
        if ($lessonModel->updateLesson($data)){
            return true;
        }
    }
}