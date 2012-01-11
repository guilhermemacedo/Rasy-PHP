<?php
/**
 * This class is the Abstracion Front Controller for Rasy MVC
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Config_Abstract
{
  /**
   * Front Controller instance
   * 
   * @var object
   * @access private
   */
  private $_front;
  /**
   * Save configuration data
   * 
   * @var array 
   * @access private
   */
  private $_config = array();
  /**
   * Save environment section
   * 
   * @var string
   * @access private
   */
  private $_environment;
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
   * Load configuration data
   * 
   * @param string $environment Environment to load
   * @param string $path Optional path to ini file
   * @return array Parsed INI
   */
  public function load($environment = null, $path = null)
  {
    $this->setEnvironment("production");
    if(!is_null($environment)){
      $this->setEnvironment($environment);
    }

    if(!is_null($path)){
      $this->setConfigs($this->loadIni($path, $this->_environment));
    }else{
      $this->_front = Rasy_Controller_Front::getInstance();
      $this->setConfigs($this->loadIni($this->_front->getPath() . "/config.ini", $this->_environment));
    }
    
    return $this->getConfigs();
  }

  /**
   * Set environment
   * 
   * @param string $environment 
   */
  public function setEnvironment($environment)
  {
    $this->_environment = $environment;
  }

  /**
   * Set configuration data
   * 
   * @param array $configs
   */
  public function setConfigs($configs)
  {
    $this->_config[$this->_environment] = $configs;
  }

  /**
   * Get configuration data
   * 
   * @return array
   */
  public function getConfigs()
  {
    return $this->_config[$this->_environment];
  }

  /**
   * Load INI file
   * 
   * @param string $path path to ini
   * @return array parsed ini
   */
  public function loadIni($path, $environment = null)
  {
    if(!is_null($environment)){
      $ini = parse_ini_file($path, true);
      return $ini[$environment];
    }

    return parse_ini_file($path, true);
  }
}