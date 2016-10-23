<?php
class LessonService{
    public function addLesson(array $data){
        $lessonModel = new LessonModel();
        $lessonData = [
            'title' => $data['title'],
            'date' => time(),
            'id_course' => $data['id_course']
        ];
        if ($lessonModel->addLesson($lessonData)){
            $id_lesson = $lessonModel->isLessonWithTitleExists($data['title']);
            if (!empty($id_lesson)) {
                $data['lecture']['date'] = time();
                $data['lecture']['id_lesson'] = $id_lesson;
                $data['test']['date'] = time();
                $data['test']['id_lesson'] = $id_lesson;
                $lectureService = new LectureService();
                $testService = new TestService();
                if ($lectureService->addLecture($data['lecture']) && $testService->addTest($data['test'])) {
                    return true;
                }
            }
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