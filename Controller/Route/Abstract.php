<?php
/**
 * This class is the route map for Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
abstract class Rasy_Controller_Route_Abstract extends Rasy_Controller_Front
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
   * Set routes
   * 
   * @param array $routes multidimensional array with routes
   */
  public function setRoutes(array $routes)
  {
    $this->_front->setRoutes($routes);
  }
  
  /**
   * Add route for MVC
   * 
   * @param array $route array with route
   */
  public function addRoute(array $route)
  {
    $this->_front->addRoute($route);
  }
  
  /**
   * Get all routes
   * 
   * @return array 
   */
  public function getRoutes()
  {
    return $this->_front->getRoutes();
  }
}