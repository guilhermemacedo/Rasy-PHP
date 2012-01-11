<?php

 function __autoload($class = null)
{
    $class = explode('_', $class);
    $class = implode('/', $class);
    if(file_exists(dirname(__FILE__) . '/../../' . $class . '.php')){
        require_once dirname(__FILE__) . '/../../' . $class . '.php';
    }else{
      throw new Exception("teste", 1);
    }
}