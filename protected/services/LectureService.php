<?php
class LectureService{
    public function addLecture(array $data){
        $lectureModel = new LectureModel();
        if ($lectureModel->addLecture($data)){
            return true;
        }
    }

    public function getLecture($id_lesson){
        $lectureModel = new LectureModel();
        $lecture = $lectureModel->getLectureByLessonId($id_lesson);
        if (!empty($lecture) && !is_null($lecture)){
            return $lecture;
        }
    }
    
    public function deleteLecture($id_lecture){
        $lectureModel = new LectureModel();
        if ($lectureModel->deleteLecture($id_lecture)){
            return true;
        }
    }
    
    public function updateLecture(array $data){
        $lectureModel = new LectureModel();
        if ($lectureModel->updateLecture($data)){
            return true;
        }
    }
}