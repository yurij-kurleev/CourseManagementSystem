<?php
class LectureController{
    public function addLectureAction(){
        $lectureModel = new LectureModel();
        $data = [
            'title' => strip_tags(trim($_POST['title'])),
            'content' => strip_tags(trim($_POST['content'])),
            'id_lesson' => strip_tags(trim($_POST['id_lesson']))
        ];
        foreach ($data as $key => $value){
            if (empty($value)){
                
            }
        }
        if ($lectureModel->addLecture($data)){
        }
    }
}