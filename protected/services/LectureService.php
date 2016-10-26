<?php
class LectureService{
    public function addLecture(array $data, $id_lesson){
        $data['date'] = time();
        $data['id_lesson'] = $id_lesson;
        $lectureModel = new LectureModel();
        if ($lectureModel->getLectureIdByTitle($data['title'])){
            throw new EntityAlreadyExistsException("Lecture with title: {$data['title']} already exists.");
        }
        $lectureModel->addLecture($data);
    }

    public function getLecture($id_lesson){
        $lectureModel = new LectureModel();
        $lecture = $lectureModel->getLectureByLessonId($id_lesson);
        if (!empty($lecture)){
            return $lecture;
        }
        else
            throw new EntityNotFoundException("Lecture by id_lesson: {$id_lesson} was not found.");
    }
    
    public function deleteLecture($id_lecture){
        $lectureModel = new LectureModel();
        if ($lectureModel->isLectureCreated($id_lecture)){
            $lectureModel->deleteLecture($id_lecture);
        }
        else{
            throw new EntityNotFoundException("Lecture with id: {$id_lecture} does not exist.");
        }
    }
    
    public function updateLecture(array $data){
        $lectureModel = new LectureModel();
        if ($lectureModel->isLectureCreated($data['id_lecture'])) {
            if ($this->getLectureIdByTitle($data['title'])){
                $lectureModel->updateLecture($data);
            }
            else{
                throw new EntityAlreadyExistsException("Lecture with title {$data['title']} already exists.");
            }
        }
        else{
            throw new EntityNotFoundException("Lecture with id: {$data['id_lecture']} does not exist.");
        }
    }
}