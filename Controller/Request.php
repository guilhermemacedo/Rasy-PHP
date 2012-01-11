<?php
/**
 * This class is extended by controllers in Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Controller_Request
{
  private $_front;
  
  public function __construct()
  {
    $this->_front = Rasy_Controller_Front::getInstance();
  }

  /**
   * Get params
   * 
   * @return array 
   */
  public function getParams()
  {
    return $this->_front->getParams();
  }

  /**
   * Get specific param
   * 
   * @param string $key
   * @return mixed
   */
  public function getParam($key)
  {
    return $this->_front->getParam($key);
  }

  /**
   * Set param
   * 
   * @param string $key
   * @param mixed $value
   */
  public function setParam($key, $value)
  {
    $this->_front->setParam($key, $value);
  }
}