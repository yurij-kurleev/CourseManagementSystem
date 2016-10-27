<?php
class CourseModel extends Model{
    /**
     * Adds new course in DB
     * @param array $data
     * @throws StatementExecutionException
     */
    public function addCourse(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO courses(title, description, date, id_auth) VALUES(:title, :description, :date, :id_auth)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    /**
     * Checks if course with such title exists in DB. It's needed because title is unique.
     * @param $title
     * @return bool
     * @throws StatementExecutionException
     */
    public function isCourseWithTitleExists($title){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_course FROM courses WHERE title = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($course['id_course']);
    }

    /**
     * Checks if course with such id exists in DB.
     * @param $id_course
     * @return bool
     * @throws StatementExecutionException
     */
    public function isCourseCreated($id_course){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_course FROM courses WHERE id_course = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_course, PDO::PARAM_STR);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($course['id_course']);
    }

    /**
     * Gets course which title equal to pointed title.
     * @param $title
     * @return mixed
     * @throws StatementExecutionException
     */
    public function getCourseByTitle($title){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM courses WHERE title = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return $course;
    }

    /**
     * Gets list of courses of definite lecturer by his email.
     * @param $email_lecturer
     * @return array
     * @throws StatementExecutionException
     */
    public function getCoursesListByLecturerEmail($email_lecturer){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_course, title, description FROM courses WHERE id_auth = (SELECT id_u FROM users WHERE email = ?)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $email_lecturer, PDO::PARAM_STR);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $courses;
    }

    /**
     * Gets list of courses which user subscribed on.
     * @param $id_user
     * @return array
     * @throws StatementExecutionException
     */
    public function getCoursesListByUserSubscription($id_user){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM courses LEFT JOIN subscriptions 
                ON courses.id_course = subscriptions.id_course
                WHERE id_u = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_user, PDO::PARAM_INT);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $courses;
    }

    /**
     * Get all existing courses from DB.
     * @return array
     */
    public function getAllCoursesList(){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM courses";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $allCoursesList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $allCoursesList;
    }

    /**
     * Deletes course from DB by it id.
     * @param $title
     * @throws StatementExecutionException
     */
    public function deleteCourse($title){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "DELETE FROM courses WHERE title = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->execute();
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    /**
     * Updates course in DB.
     * @param array $data
     * @throws StatementExecutionException
     */
    public function updateCourse(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "UPDATE courses SET title = :title, description = :description WHERE id_course = :id_course";
        $stmt = $link->prepare($sql);
        $stmt->execute(array(':title' => $data['title'], 'description' => $data['description'], 'id_course' => $data['id_course']));
        CourseModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }
}