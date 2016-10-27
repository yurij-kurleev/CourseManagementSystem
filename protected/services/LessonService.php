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
            if (!empty($data['test'])) {
                $testService = new TestService();
                $testService->addTest($data['test'], $id_lesson);
            }
        }
    }

    public function getLessonsList($id_course){
        $lessonModel = new LessonModel();
        $courseService = new CourseService();
        if ($courseService->checkCourseExistence($id_course)) {
            $lessonsList = $lessonModel->getLessonsListByCourseId($id_course);
            if (!empty($lessonsList)) {
                for ($i = 0; $i < count($lessonsList); $i++){
                    try {
                        $lectureService = new LectureService();
                        $lecture = $lectureService->getLecture($lessonsList[$i]['id_lesson']);
                        $lessonsList[$i]['lecture'] = $lecture;
                    } catch (EntityNotFoundException $e) {
                        throw new EmptyEntityException("Lesson with id: {$lessonsList[$i]['id_lesson']} 
                                                    does not contain any lecture - lesson is empty.");
                    }
                }
                return $lessonsList;
            } else {
                throw new EntityNotFoundException("LessonList by id_course: {$id_course} was not found.");
            }
        }
    }
    
    public function getLesson($id_lesson){
        $lessonModel = new LessonModel();
        $lesson = $lessonModel->getLessonById($id_lesson);
        if (!empty($lesson)){
            try {
                $lectureService = new LectureService();
                $lecture = $lectureService->getLecture($lesson['id_lesson']);
                $lesson['lecture'] = $lecture;
            }catch (EntityNotFoundException $e){
                throw new EmptyEntityException("Lesson with id: {$lesson['id_lesson']} 
                                                does not contain any lecture - lesson is empty.");
            }
            try {
                $testService = new TestService();
                $test = $testService->getTest($lesson['id_lesson']);
                $lesson['test'] = $test;
            }catch (EntityNotFoundException $e){
                $lesson['test'] = '';
            }
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