<?php
class LectureModel extends Model{
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

    public function addLecture(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO lectures(title, content, date, id_lesson) VALUES(:title, :content, :date, :id_lesson)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        return $this->getLectureIdByTitle($data);
    }

    public function getLectureIdByTitle(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lecture FROM lectures WHERE title = ? AND id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(2, $data['id_lesson'], PDO::PARAM_STR);
        $stmt->execute();
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lecture['id_lecture'];
    }
    
    public function isLectureCreated($id_lecture){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_lecture FROM lectures WHERE id_lecture = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lecture, PDO::PARAM_INT);
        $stmt->execute();
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($lecture['id_lecture']);
    }
    
    public function getLectureByLessonId($id_lesson){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM lectures WHERE id_lesson = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lesson, PDO::PARAM_INT);
        $stmt->execute();
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $lecture = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lecture;
    }

    public function deleteLecture($id_lecture){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "DELETE FROM lectures WHERE id_lecture = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_lecture, PDO::PARAM_INT);
        $stmt->execute();
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    public function updateLecture(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "UPDATE lectures SET title = :title, content = :content WHERE id_lecture = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute(array(':title' => $data['title'], ':content' => $data['content'], ':id_lecture' => $data['id_lecture']));
        LectureModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }
}