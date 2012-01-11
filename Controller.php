<?php
/**
 * This class is extended by controllers in Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Controller
{
  /**
   * Rasy Controller Request instance
   * 
   * @var object
   * @access protected
   */
  protected $request;
  /**
   * Rasy View instance
   * 
   * @var object
   * @access protected
   */
  protected $view;

  /**
   * Construct method
   * 
   */
  public function __construct()
  {
    $this->request = new Rasy_Controller_Request();
    $this->view = new Rasy_View_Abstract();
  }
  
  /**
   * Destruct method
   * 
   */
  public function __destruct()
  {
    $this->view->render();
  }
}