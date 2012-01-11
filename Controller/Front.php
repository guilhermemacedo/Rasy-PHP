<?php
/**
 * This class is the Front Controller for Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Controller_Front extends Rasy_Controller_Front_Abstract
{

  /**
   * Singleton Pattern
   * 
   * @return object
   */
  public static function getInstance()
  {
    if(!(self::$_instance instanceof Rasy_Controller_Front_Abstract )){
      self::$_instance = Rasy_Controller_Front_Abstract::getInstance();
    }

    return self::$_instance;
  }
}