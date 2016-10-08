<?php
class FrontController
{
    protected $_controller, $_action, $_params, $_body;
    protected static $instance;

    public static function getInstance(){
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        $request = $_SERVER['REQUEST_URI'];
        $splits = explode('/', trim($request, '/'));
        //Controller
        $this->_controller = !empty($splits[0]) ? ucfirst($splits[0]) . 'Controller' : 'IndexController';
        //Action
        $this->_action = !empty($splits[1]) ? $splits[1] . 'Action' : 'indexAction';
        //Params if exist
        if (!empty($splits[2])) {
            for ($i = 2, $cnt = count($splits); $i < $cnt; $i++) {
                $this->_params[] = $splits[$i];
            }
        }
    }

    public function route(){
        if (class_exists($this->getController())) {
            $rc = new ReflectionClass($this->getController());
            if ($rc->hasMethod($this->getAction())) {
                $controller = $rc->newInstance();
                $method = $rc->getMethod($this->getAction());
                $method->invoke($controller);
            } else {
                throw new Exception("Wrong Action: " . $this->getAction());
            }
        } else {
            throw new Exception("Wrong Controller: " . $this->getController());
        }
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setBody($body)
    {
        $this->_body = $body;
    }
}