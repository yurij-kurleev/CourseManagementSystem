<?php
class CourseModel{
    public function addCourse(array $data){
        try {
            if ($this->isCourseWithTitleExists($data['title'])){
                throw new CourseAlreadyExistsException("Course {$data['title']} already exists.");
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "INSERT INTO courses(title, description, date, id_auth) VALUES(:title, :description, :date, :id_auth)";
            $stmt = $link->prepare($sql);
            $stmt->execute($data);
            if (!empty($stmt->errorInfo()[1])) {
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            return true;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function isCourseWithTitleExists($title){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_course FROM courses WHERE title = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])) {
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($course['id_course']);
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function isCourseCreated($id_course){
        try{
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_course FROM courses WHERE id_course = ?";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $id_course, PDO::PARAM_STR);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])) {
                throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($course['id_course']);
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function getCourseByTitle($title){
        try{
            if ($this->isCourseWithTitleExists($title)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "SELECT * FROM courses WHERE title = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $title, PDO::PARAM_STR);
                $stmt->execute();
                if (!empty($stmt->errorInfo()[1])) {
                    throw new StatementExecutingException("Error " . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                $course = $stmt->fetch(PDO::FETCH_ASSOC);
                return $course;
            }
            else
                throw new CourseNotFoundException("Course with title: " . $title . " was not found.");
        } catch(PDOException $e){
            throw $e;
        }
    }

    public function getCoursesListByLecturerEmail($email_lecturer){
        $userModel = new UserModel();
        try{
            if (!$userModel->isRegistered($email_lecturer)){
                throw new LecturerNotFoundException("Lecturer with email: " . $email_lecturer . " was not found.");
            }
            $link = PDOConnection::getInstance()->getConnection();
            $sql = "SELECT id_course, title, description FROM courses WHERE id_auth = (SELECT id_u FROM users WHERE email = ?)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $email_lecturer, PDO::PARAM_STR);
            $stmt->execute();
            if (!empty($stmt->errorInfo()[1])) {
                throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
            }
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $courses;
        }catch (PDOException $e){
            throw $e;
        }
    }

    public function deleteCourse($title){
        try{
            if ($this->isCourseWithTitleExists($title)){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "DELETE FROM courses WHERE title = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $title, PDO::PARAM_STR);
                $stmt->execute();
                if (!empty($stmt->errorInfo()[1])) {
                    throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                return true;
            }
            else{
                throw new CourseNotFoundException("Course with title: " . $title ." does not exist.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
    
    public function updateCourse(array $data){
        try{
            if (!($this->isCourseWithTitleExists($data['title']))){
                $link = PDOConnection::getInstance()->getConnection();
                $sql = "SELECT id_course FROM courses WHERE id_course = ?";
                $stmt = $link->prepare($sql);
                $stmt->bindParam(1, $data['id_course'], PDO::PARAM_INT);
                $stmt->execute();
                if (!empty($stmt->errorInfo()[1])) {
                    throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                }
                $course = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!empty($course['id_course'])) {
                    $sql = "UPDATE courses SET title = :title, description = :description WHERE id_course = :id_course";
                    $stmt = $link->prepare($sql);
                    $stmt->execute(array(':title' => $data['title'], 'description' => $data['description'], 'id_course' => $data['id_course']));
                    if (!empty($stmt->errorInfo()[1])) {
                        throw new StatementExecutingException("Error" . $stmt->errorInfo()[0] . ": " . $stmt->errorInfo()[2]);
                    }
                    return true;
                }
                else{
                    throw new CourseNotFoundException("Course with id: " . $data['id_course'] ." does not exist.");
                }
            }
            else{
                throw new CourseAlreadyExistsException("Course with title: {$data['title']} already exists.");
            }
        }catch (PDOException $e){
            throw $e;
        }
    }
}