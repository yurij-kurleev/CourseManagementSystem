<?php
class LectureService{
    private static $instance = null;
    private $lectureModel;

    protected function __construct(LectureModel $lectureModel)
    {
        $this->lectureModel = $lectureModel;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(LectureModel::getInstance());
        }
        return self::$instance;
    }

    public function addLecture(array $data, $id_lesson){
        $data['date'] = time();
        $data['id_lesson'] = $id_lesson;
        if ($this->lectureModel->getLectureIdByTitle($data['title'])) {
            throw new EntityAlreadyExistsException("Lecture with title: {$data['title']} already exists.");
        }
        $this->lectureModel->addLecture($data);
    }

    public function getLecture($id_lesson){
        $lecture = $this->lectureModel->getLectureByLessonId($id_lesson);
        if (!empty($lecture)){
            return $lecture;
        }
        else
            throw new EntityNotFoundException("Lecture by id_lesson: {$id_lesson} was not found.");
    }
    
    public function deleteLecture($id_lecture){
        if ($this->lectureModel->isLectureCreated($id_lecture)) {
            $this->lectureModel->deleteLecture($id_lecture);
        }
        else{
            throw new EntityNotFoundException("Lecture with id: {$id_lecture} does not exist.");
        }
    }
    
    public function updateLecture(array $data){
        if ($this->lectureModel->isLectureCreated($data['id_lecture'])) {
            if ($this->lectureModel->getLectureIdByTitle($data['title'])) {
                $this->lectureModel->updateLecture($data);
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