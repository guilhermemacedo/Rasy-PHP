<?php
/**
 * This class manage layouts
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_View_Abstract
{
  protected $_layout;
  
  public function render()
  {
    $front = Rasy_Controller_Front::getInstance();
    $appPath = $front->getPath();
    require $appPath . '/views/' . $this->_layout.'/index/index.php';
  }

  public function __call($name, $arguments)
  {
    
  }
  
  public function setLayout( $layout )
  {
    $this->_layout = $layout;
  }
  
  public function __construct()
  {
    $this->setLayout("layout01");
  }
}