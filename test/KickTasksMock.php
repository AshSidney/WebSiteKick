<?php

class KickTasksMock
{
  static $tasks;
  
  function __construct ($index)
  {
    $this->index = $index;
  }
  
  function execute ($xml)
  {
    self::$tasks[$this->index]->execute($xml);
  }
}


class TestEchoString
{
  function __construct ($data)
  {
    $this->data = $data;
  }
  
  function execute ($xml)
  {
    echo $this->data;
  }
}

?>