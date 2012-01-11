<?php
/**
 * This class is the Abstracion Front Controller for Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Controller_Front_Abstract
{
  /**
   * Modules for load
   * 
   * @var array
   * @access private
   */
  private $_modules;
  /**
   * Loaded module
   * 
   * @var string
   * @access private
   */
  private $_module;
  /**
   * Actual controller
   * 
   * @var string
   * @access private
   */
  private $_controller;
  /**
   * Actual action
   * 
   * @var string
   * @access private
   */
  private $_action;
  /**
   * Path to loaded module
   * 
   * @var string
   * @access private
   */
  private $_path;
  /**
   * Split request URI
   * 
   * @var array
   * @access private
   */
  private $_split;
  /**
   * Request URI
   * 
   * @var string
   * @access private
   */
  private $_uri;
  /**
   * Routes for MVC
   * 
   * @var array
   * @access private
   */
  private $_routes = array();
  /**
   * Request params 
   * 
   * @var array
   * @access private
   */
  private $_params = array();
  /**
   * Instance for this class
   * 
   * @var object
   * @access public
   * @static
   */
  static $_instance;

  /**
   * Singleton Pattern
   * 
   * @return object
   */
  public static function getInstance()
  {
    if(!(self::$_instance instanceof self)){
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  /**
   * Trim a unidimensional array
   * 
   * @param array $array input array
   * @return array return trimmed array
   */
  public function arrayTrim(array $array)
  {
    foreach($array as $key => $value){
      if(empty($value)){
        unset($array[$key]);
      }
    }

    return $array;
  }

  /**
   * Sets path to controllers folder
   * 
   * @param string $path
   */
  public function setPath($path)
  {
    $this->_path = $path;
    return $this;
  }

  /**
   * Get path to controllers folder
   * 
   * @return string path to controller
   */
  public function getPath()
  {
    return $this->_path;
  }

  /**
   * Add route
   * 
   * @access protected
   * @param array $route
   */
  protected function addRoute(array $route)
  {
    $this->_routes[] = $route;
  }

  /**
   * Set routes
   * 
   * @access protected
   * @param array $routes
   */
  protected function setRoutes(array $routes)
  {
    $this->_routes = $routes;
  }

  /**
   * Get routes
   * 
   * @access protected
   * @return array
   */
  protected function getRoutes()
  {
    return $this->_routes;
  }

  /**
   * Get all params
   * 
   * @return array
   */
  public function getParams()
  {
    return $this->_params;
  }

  /**
   * Return an specific parameter
   * 
   * @param string $key
   * @return mixed
   */
  public function getParam($key)
  {
    return $this->_params[$key];
  }

  /**
   * Set an parameter for multiples purposes
   * 
   * @param string $key
   * @param mixed $value
   */
  public function setParam($key, $value)
  {
    $this->_params[$key] = $value;
  }

  /**
   * Bind routes
   * 
   * @access private
   */
  private function bindRoutes()
  {
    foreach($this->getRoutes() as $route){
      //explode route
      $router = explode(":", str_replace("/", "", $route['route']));

      if(isset($router[0])){
        $controller = $router[0];
      }

      if(isset($router[1])){
        $action = $router[1];
      }

      $params = array();
      if(isset($router[2])){
        for($i = 2; $i < count($router); $i++){
          $params[] = $router[$i];
        }
      }

      //bind parameters
      $this->bindParams();

      if($this->getController() == $controller){
        $this->setAction($route['action']);
        $this->bindParams($params);
        break;
      }
    }
  }

  /**
   * Bind params
   * 
   * @param array $routedParams
   * @access private
   */
  private function bindParams(array $routedParams = array())
  {
    //request uri
    $uri = $this->bindUri();

    //replace controller and action
    $params = str_replace("/" . $this->getController(), "", $uri);
    $params = str_replace("/" . $this->getAction(), "", $params);

    //replace module
    if($this->getModule() != "default"){
      $params = str_replace("/" . $this->getModule(), "", $params);
    }

    //explode params
    $params = explode("/", $params);

    $i = 0;
    $keyParams = array();
    foreach($routedParams as $param){
      $keyParams[$i] = $param;
      $i++;
    }

    if(count($keyParams) > 0){
      for($i = 1; $i < count($params); $i++){
        $this->setParam($keyParams[$i - 1], $params[$i]);
      }
    }else{
      for($i = 1; $i < count($params); $i +=2){
        $this->setParam($params[$i], $params[$i + 1]);
      }
    }
  }

  /**
   * Bind request and set module, controller and action
   * 
   * @access private
   */
  private function bindRequest()
  {
    //request uri
    $reqUri = $this->bindUri();

    //split and trim request uri
    $split = explode("/", $reqUri);
    $this->_split = $this->arrayTrim($split);

    //binds module, controller, action and routes
    $this->bindModule();
    $this->bindController();
    $this->bindAction();
    $this->bindRoutes();
  }

  /**
   * Bind controller
   * 
   * @access private
   */
  private function bindController()
  {
    if($this->getModule() == "default"){
      if(!empty($this->_split[1])){
        $this->setController($this->_split[1]);
      }
    }else{
      if(!empty($this->_split[2])){
        $this->setController($this->_split[2]);
      }
    }
  }

  /**
   * Bind action
   * 
   * @access private
   */
  private function bindAction()
  {
    if($this->getModule() == "default"){
      if(!empty($this->_split[2])){
        $this->setAction($this->_split[2]);
      }
    }else{
      if(!empty($this->_split[3])){
        $this->setAction($this->_split[3]);
      }
    }
  }

  /**
   * Bind module
   * 
   * @access private
   */
  private function bindModule()
  {
    if(array_key_exists($this->_split[1], $this->getModules())){
      $this->setModule($this->_split[1]);

      //set path to this module
      $modules = $this->getModules();
      $this->setPath($modules[$this->_split[1]]['path']);
    }
  }

  /**
   * Bind uri
   * 
   * @return string binded uri
   */
  public function bindUri()
  {
    return $this->replaceInitUri();
  }

  /**
   * Default values for modules, module, controller and action
   * Access is set to private for apply singleton pattern
   * 
   * @access private
   */
  private function __construct()
  {
    $this->_modules = array();
    $this->_module = "default";
    $this->_controller = "index";
    $this->_action = "index";
  }

  /**
   * Run MVC, case success execute specified controller and action
   * otherwise execute error controller and index action
   * 
   */
  public function run()
  {
    $this->bindRequest();

    try{
      $this->dispatch();
    }catch(Rasy_Exception_Controller $e){
      try{
        $this->errorDispatch();
      }catch(Rasy_Exception_Controller $e){
        echo "Error Controller not found";
      }
    }
  }

  /**
   * Execute error controller
   * 
   */
  private function errorDispatch()
  {
    $this->setController("error");
    $this->setAction("index");

    $this->dispatch();
  }

  /**
   * Execute specified  and action controller
   * 
   */
  private function dispatch()
  {
    //require controller if this exists
    if(file_exists(str_replace("//", "/", $this->getPath() . "/controllers/" .
                            $this->getController() . "Controller.php"))){

      require_once str_replace("//", "/", $this->getPath() . "/controllers/" .
                      $this->getController() . "Controller.php");
    }else{
      throw new Rasy_Exception_Controller("Controller file not found on this
        module, check controller file name and try again.", 1);
    }
    $controller = $this->getController() . "Controller";

    try{
      $controller = new $controller();
    }catch(Exception $e){
      throw new Rasy_Exception_Controller("Controller class not found on this 
        controller file, check class name in controller file and try again.", 2);
    }

    $action = $this->getAction() . "Action";
    if(method_exists($controller, $action)){
      $controller->$action();
    }else{
      throw new Rasy_Exception_Controller("Action method not found on this 
        controller file, check action name in controller file and try again.", 3);
    }
  }

  /**
   * Set initial uri
   * 
   * @param string $uri
   */
  public function initUri($uri)
  {
    $this->_uri = str_replace("//", "/", "/" . $uri . "/");
  }

  /**
   * Get initial uri
   * 
   * @return string
   */
  public function getInitUri()
  {
    return $this->_uri;
  }

  /**
   * Get full uri
   * 
   * @return string
   */
  public function getUri()
  {
    return str_replace("//", "/", $_SERVER["REQUEST_URI"]);
  }

  /**
   * Replace init uri
   * 
   * @return string replaced uri
   */
  public function replaceInitUri()
  {
    return str_replace($this->getInitUri(), "/", $this->getUri());
  }

  /**
   * Set module
   * 
   * @param string $module
   */
  public function setModule($module)
  {
    $this->_module = $module;
  }
  
  /**
   * Add an module
   * 
   * @param string $module module name to add
   * @param string $path path to module
   * @access protected
   */
  protected function addModule($module, $path)
  {
    $this->_modules[$module] = array("path" => $path);
  }

  /**
   * Get module
   * 
   * @return string actual module
   * @access protected
   */
  protected function getModule()
  {
    return $this->_module;
  }

  /**
   * Get modules
   * 
   * @return array all declared modules
   * @access protected
   */
  protected function getModules()
  {
    return $this->_modules;
  }

  /**
   * Set controller
   * 
   * @param string $controller
   * @access protected
   */
  protected function setController($controller)
  {
    $this->_controller = $controller;
  }

  /**
   * Get controller
   * 
   * @return string actual controller
   */
  public function getController()
  {
    return $this->_controller;
  }

  /**
   * Set action
   * 
   * @param string $action
   */
  public function setAction($action)
  {
    $this->_action = $action;
  }

  /**
   * Get action
   * 
   * @return string actual action
   */
  public function getAction()
  {
    return $this->_action;
  }
}