<?php

class KickTaskMock
{
  static $tasks;
  
  function execute ($xml)
  {}
}


class TestEchoString extends KickTaskMock
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


class TestEchoXml extends KickTaskMock
{
  function __construct ($id, $value)
  {
    $this->id = $id;
    $this->value = $value;
  }
  
  function execute ($xml)
  {
    if (isset($xml->{$this->id}))
      echo $xml->{$this->id};
      $xml->{$this->id} = $this->value;
  }
}

?>