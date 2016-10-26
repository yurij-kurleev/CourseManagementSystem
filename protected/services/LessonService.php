<?php
class LessonService{
    public function addLesson(array $data){
        $lessonModel = new LessonModel();
        $lessonData = [
            'title' => $data['title'],
            'date' => time(),
            'id_course' => $data['id_course']
        ];
        if ($lessonModel->getLessonIdByTitle($data['title'])){
            throw new EntityAlreadyExistsException("Lesson {$data['title']} already exists.");
        }
        $id_lesson = $lessonModel->addLesson($lessonData);
        if (!empty($id_lesson)) {
            $lectureService = new LectureService();
            $lectureService->addLecture($data['lecture'], $id_lesson);
            $testService = new TestService();
            $testService->addTest($data['test'], $id_lesson);
        }
    }

    public function getLessonsList($id_course){
        $lessonModel = new LessonModel();
        $lessonsList = $lessonModel->getLessonsListByCourseId($id_course);
        if (!empty($lessonsList)){
            return $lessonsList;
        }
        else{
            throw new EntityNotFoundException("LessonList by id_course: {$id_course} was not found.");
        }
    }
    
    public function getLesson($id_lesson){
        $lessonModel = new LessonModel();
        $lesson = $lessonModel->getLessonById($id_lesson);
        if (!empty($lesson)){
            return $lesson;
        }
        else{
            throw new EntityNotFoundException("Lesson with id: {$id_lesson} does not exist.");
        }
    }
    
    public function deleteLesson($id_lesson){
        $lessonModel = new LessonModel();
        if ($lessonModel->isLessonCreated($id_lesson)) {
           $lessonModel->deleteLesson($id_lesson);
        }else{
            throw new EntityNotFoundException("Lesson with id: " . $id_lesson . "does not exists.");
        }
    }

    public function updateLesson(array $data){
        $lessonModel = new LessonModel();
        if ($lessonModel->isLessonCreated($data['id_lesson'])) {
            if (!$lessonModel->getLessonIdByTitle($data['title'])) {
                $lessonModel->updateLesson($data);
            }
            else{
                throw new EntityAlreadyExistsException("Lesson with title: {$data['title']} already exists.");
            }
        }
        else{
            throw new EntityNotFoundException("Lesson with id: {$data['id_lesson']} does not exist");
        }
    }
}