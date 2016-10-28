<?php
class LessonModel extends Model{
    private static $instance = null;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addLesson(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO lessons(title, date, id_course) VALUES (:title, :date, :id_course)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
        return $this->getLessonIdByTitle(array('title' => $data['title'], 'id_course' => $data['id_course']));
    }

    public function getLessonIdByTitle(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lesson FROM lessons WHERE title = :title AND id_course = :id_course";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lesson['id_lesson'];
    }

    public function isLessonCreated($id_lesson){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lesson FROM lessons WHERE id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
        $stmt->execute();
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($lesson['id_lesson']);
    }

    public function getLessonsListByCourseId($id_course){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lesson, title FROM lessons WHERE id_course = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_course, PDO::PARAM_INT);
        $stmt->execute();
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lessonsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $lessonsList;
    }

    public function getLessonById($id_lesson){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM lessons WHERE id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
        $stmt->execute();
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lesson = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lesson;
    }

    public function deleteLesson($id_lesson){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "DELETE FROM lessons WHERE id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
        $stmt->execute();
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }
    
    public function updateLesson(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "UPDATE lessons SET title = :title WHERE id_lesson = :id_lesson";
        $stmt = $link->prepare($sql);
        $stmt->execute(array(':title' => $data['title'], ':id_lesson' => $data['id_lesson']));
        LessonModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }
}