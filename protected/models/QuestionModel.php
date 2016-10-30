<?php
class QuestionModel extends Model{
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

    public function addQuestion(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "INSERT INTO questions(question, points, date, id_test) VALUES (:question, :points, :date, :id_test)";
        $stmt = $link->prepare($sql);
        $stmt->execute($data);
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
        return $this->getQuestionIdByTestId($data['id_test']);
    }

    public function getQuestionIdByTestId($id_test)
    {
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_question FROM questions WHERE id_test = ? 
                AND id_question >= ALL( SELECT id_question FROM questions  WHERE id_test = ?)";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_test, PDO::PARAM_INT);
        $stmt->execute();
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        return $question['id_question'];
    }

    public function isQuestionCreated($id_question)
    {
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_question FROM questions WHERE id_question = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
        $stmt->execute();
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($question['id_question']);
    }

    public function getQuestionsListByTestId($id_test){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT * FROM questions WHERE  id_test = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_test, PDO::PARAM_INT);
        $stmt->execute();
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $questionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $questionsList;
    }

    public function deleteQuestion($id_question){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "DELETE FROM questions WHERE id_question = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
        $stmt->execute();
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    public function updateQuestion(array $data){
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "UPDATE questions SET question = :question, points = :points WHERE id_question = :id_question";
        $stmt = $link->prepare($sql);
        $stmt->execute(array(':question' => $data['question'], 'points' => $data['points'], ':id_question' => $data['id_question']));
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
    }

    public function getQuestionById($id_question)
    {
        $link = PDOConnection::getInstance()->getConnection();
        $sql = "SELECT id_question, question, points, date, id_test 
                FROM questions WHERE id_question = ?";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(1, $id_question, PDO::PARAM_INT);
        $stmt->execute();
        QuestionModel::checkErrorArrayEmptiness($stmt->errorInfo());
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        return $question;
    }
}