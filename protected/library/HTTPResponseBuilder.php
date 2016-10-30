<?php
class HTTPResponseBuilder{
    private static $instance;
    
    public static function getInstance(){
        if (is_null(self::$instance)){
            self::$instance = new HTTPResponseBuilder();
        }
        return self::$instance;
    }
    
    private function __construct(){}
    
    private function createErrorPointer(){
        $fc = FrontController::getInstance();
        $path_parts = explode('\\', __DIR__);
        foreach ($path_parts as $path_part){
            if ($path_part === 'protected') break;
            array_shift($path_parts);
        }
        return '/' . implode('/', $path_parts) . '/' . $fc->getController() . '/' . $fc->getAction();
    }

    public function sendFailRespond($code, $title, $description){
        http_response_code($code);
        echo "
                    \"errors\": [
                        \"status\": \"{$code}\",
                        \"source\": { \"pointer\" : \"{$this->createErrorPointer()}\"},
                        \"title\": \"{$title}\",
                        \"description\": \"{$description}\"
                    ]
                ";
        exit();
    }
}