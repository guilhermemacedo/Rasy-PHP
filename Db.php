<?php
/**
 * This class represents database ORM
 * 
 * @author Guilherme Macêdo
 * @version
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Db
{
  /**
   * Instance of database adapter
   * 
   * @var object
   * @access private
   */
  private $_db;

  /**
   * Constructor method for database
   * 
   * @param array $config
   */
  public function __construct($config)
  {
    $db = "Rasy_Db_Adapter_" . $config['adapter'];
    $this->_db = new $db($config);
  }

  public function delete($table, $condition, $limit=NULL)
  {
    return $this->db->delete($table, $condition, $limit);
  }

  public function update($table, $changes, $condition)
  {
    return $this->db->update($table, $changes, $condition);
  }

  public function insert($table, $data)
  {
    return $this->db->insert($table, $data);
  }

  public function executeQuery($queryStr)
  {
    return $this->db->executeQuery($queryStr);
  }

  public function getRow()
  {
    return $this->db->getRows();
  }

  public function getAll()
  {
    return $this->db->getAll();
  }

  public function lastId()
  {
    return $this->db->lastId();
  }

  public function fetch($table, $fields, $conditions = NULL)
  {
    return $this->db->fetch($table, $fields);
  }

  public function fetchAll($table, $fields, $complemento = NULL, $conditions = NULL)
  {
    return $this->db->fetchAll($table, $fields, $complemento, $conditions);
  }

  public function numRows()
  {
    return $this->db->numRows();
  }
}