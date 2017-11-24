<?php

class OutputBuffer
{
  function start()
  {
    $this->buffer = "";
    ob_start(array($this, 'outputHandler'));
  }
  
  function stop()
  {
    ob_end_flush();
  }
  
  function outputHandler ($data)
  {
    $this->buffer .= $data;
  }
  
  function getBuffer ()
  {
    return $this->buffer;
  }
  
  private $buffer = "";
}

