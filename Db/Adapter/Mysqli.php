<?php
/**
 * This class is the MySQL Adapter for database
 * 
 * @author Guilherme Macêdo
 * @version 1.0
 * 
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Rasy_Db_Adapter_Mysqli extends mysqli
{
  /**
   * Save last executed query
   * 
   * @var object
   * @access private
   */
  private $_last;
  /**
   * Sql table
   * 
   * @var string
   * @access private
   */
  private $_table;
  /**
   * Sql fields
   * 
   * @var array
   * @access private
   */
  private $_fields;
  /**
   * Sql where
   * 
   * @var array
   * @access private
   */
  private $_where;
  /**
   * Sql limit
   * 
   * @var string
   * @access private
   */
  private $_limit;
  /**
   * Sql order
   * 
   * @var string
   * @access private
   */
  private $_order;

  /**
   * Create database connection
   * 
   * @param array $config
   */
  public function __construct($config)
  {
    parent::__construct($config['host'], $config['user'], $config['pass'], $config['database']);
  }

  /**
   * Set table for query
   * 
   * @param string $table
   * @return object
   */
  public function table($table)
  {
    $this->_table = $table;
    return $this;
  }

  /**
   * Set fields for query
   * 
   * @param string|array
   * @return object
   */
  public function fields($fields)
  {
    if(is_array($fields)){
      $this->_fields = implode(",", $fields);
    }else{
      $this->_fields = $fields;
    }

    return $this;
  }

  /**
   * Set condition for query
   * 
   * @var string
   * @return object
   */
  public function where($where)
  {
    $this->_where = " WHERE " . $where;
    return $this;
  }

  /**
   * Set limit for query
   * 
   * @var string
   * @return object
   */
  public function limit($limit, $start = null)
  {
    $this->_limit = " LIMIT ";
    if(!is_null($start)){
      $this->_limit .= $start . "," . $limit;
    }else{
      $this->_limit .= $limit;
    }

    return $this;
  }

  /**
   * Set order for query
   * 
   * @var string
   * @return object
   */
  public function order($order)
  {
    $this->_order = " ORDER BY " . $order;
    return $this;
  }

  /**
   * Execute delete query
   * 
   */
  public function delete()
  {
    $query = "DELETE FROM {$this->_table} {$this->_where} {$this->_limit}";
    return $this->execute($query);
  }

  /**
   * Execute update query
   * 
   */
  public function update($changes)
  {
    $update = "UPDATE " . $this->_table . " SET ";
    foreach($changes as $field => $value){
      $update .= "`" . $field . "`='{$value}',";
    }
    $update = substr($update, 0, -1);
    $update .= $this->_where;

    return $this->execute($update);
  }

  /**
   * Execute insert query
   * 
   */
  public function insert($data)
  {
    $fields = "";
    $values = "";
    foreach($data as $f => $v){
      $fields .= "`$f`,";
      $values .= ( is_numeric($v) && ( intval($v) == $v ) ) ? $v . "," : "'$v',";
    }
    $fields = substr($fields, 0, -1);
    $values = substr($values, 0, -1);
    $insert = "INSERT INTO $this->_table ({$fields}) VALUES({$values})";
    echo $insert;
    return $this->execute($insert);
  }

  /**
   * Execute query
   * 
   */
  public function execute($query)
  {
    $this->query("SET NAMES 'utf8'");
    if($result = $this->query($query)){
      $this->_last = $result;
      return true;
    }else{
      $this->_last = false;
      return false;
    }
  }

  public function getRow()
  {
    return $this->_last->fetch_array(MYSQLI_ASSOC);
  }

  public function getAll()
  {
    $i = 0;
    $rows = array();
    while($row = $this->_last->fetch_assoc()){
      $rows[$i] = $row;
      $i++;
    }
    return $rows;
  }

  public function fetch()
  {
    $query = "SELECT " . $this->_fields . " FROM " . $this->_table .
            $this->_where . " " . $this->_order . " " . $this->_limit;

    $this->execute($query);
    
    if( !$this->_last ){
      return false;
    }
    return $this->getRow();
  }

  public function fetchAll() {
    $query = "SELECT " . $this->_fields . " FROM " . $this->_table .
            $this->_where . " " . $this->_order . " " . $this->_limit;

    $this->execute($query);
    if( !$this->_last ){
      return false;
    }
    
    
    return $this->getAll();
  }

  public function numRows()
  {
    return $this->_last->num_rows;
  }

  public function affectedRows()
  {
    return $this->_last->affected_rows;
  }

  public function lastId()
  {
    return $this->insert_id;
  }

  public function __deconstruct()
  {
    $this->close();
  }
}