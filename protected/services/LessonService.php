<?php
class LessonService{
    private $lessonModel;
    private $lectureService;
    private $courseService;
    private $testService;

    public function __construct(LessonModel $lessonModel, LectureService $lectureService, CourseService $courseService, TestService $testService)
    {
        $this->lessonModel = $lessonModel;
        $this->lectureService = $lectureService;
        $this->courseService = $courseService;
        $this->testService = $testService;
    }
    
    public function initLessonService(){
        
    }

    public function addLesson(array $data){
        $lessonData = [
            'title' => $data['title'],
            'date' => time(),
            'id_course' => $data['id_course']
        ];
        if ($this->lessonModel->getLessonIdByTitle($data['title'])) {
            throw new EntityAlreadyExistsException("Lesson {$data['title']} already exists.");
        }
        $id_lesson = $this->lessonModel->addLesson($lessonData);
        if (!empty($id_lesson)) {
            $this->lectureService->addLecture($data['lecture'], $id_lesson);
            if (!empty($data['test'])) {
                $this->testService->addTest($data['test'], $id_lesson);
            }
        }
    }

    public function getLessonsList($id_course){
        if ($this->courseService->checkCourseExistence($id_course)) {
            $lessonsList = $this->lessonModel->getLessonsListByCourseId($id_course);
            if (!empty($lessonsList)) {
                for ($i = 0; $i < count($lessonsList); $i++){
                    try {
                        $lecture = $this->lectureService->getLecture($lessonsList[$i]['id_lesson']);
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
        $lesson = $this->lessonModel->getLessonById($id_lesson);
        if (!empty($lesson)){
            try {
                $lecture = $this->lectureService->getLecture($lesson['id_lesson']);
                $lesson['lecture'] = $lecture;
            }catch (EntityNotFoundException $e){
                throw new EmptyEntityException("Lesson with id: {$lesson['id_lesson']} 
                                                does not contain any lecture - lesson is empty.");
            }
            try {
                $test = $this->testService->getTest($lesson['id_lesson']);
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
        if ($this->lessonModel->isLessonCreated($id_lesson)) {
            $this->lessonModel->deleteLesson($id_lesson);
        }else{
            throw new EntityNotFoundException("Lesson with id: " . $id_lesson . "does not exists.");
        }
    }

    public function updateLesson(array $data){
        if ($this->lessonModel->isLessonCreated($data['id_lesson'])) {
            if (!$this->lessonModel->getLessonIdByTitle($data['title'])) {
                $this->lessonModel->updateLesson($data);
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