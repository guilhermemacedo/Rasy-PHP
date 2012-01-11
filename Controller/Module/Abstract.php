<?php
/**
 * This class is the route map for Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
abstract class Rasy_Controller_Module_Abstract extends Rasy_Controller_Front
{
  /**
   * Front controller instance
   * 
   * @var object
   * @access private
   */
  private $_front;

  /**
   * Create front controller instance
   * 
   */
  public function __construct()
  {
    $this->_front = Rasy_Controller_Front::getInstance();
  }

  /**
   * Add an module
   * 
   * @param string $module module name to add
   * @param string $path path to module
   * @access protected
   */
  public function addModule($module, $path)
  {
    $this->_front->addModule($module, $path);
  }

  /**
   * Get module
   * 
   * @return string actual module
   * @access protected
   */
  public function getModule()
  {
    return $this->_front->getModule();
  }

  /**
   * Get modules
   * 
   * @return array all declared modules
   * @access protected
   */
  public function getModules()
  {
    return $this->_front->getModules();
  }

  /**
   * Set controller
   * 
   * @param string $controller
   * @access protected
   */
  public function setController($controller)
  {
    $this->_front->setController($controller);
  }
}