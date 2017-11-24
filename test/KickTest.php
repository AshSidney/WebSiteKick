<?php

require_once 'OutputBuffer.php';

use PHPUnit\Framework\TestCase;

class serverTest extends TestCase
{
  public function testServiceVersion ()
  {
    $output = new OutputBuffer();
    $output->start();
    
    include 'Kick.php';
    
    $output->stop();
    
    $this->assertEquals($testDb->getSchema()->getVersion(), $output->getBuffer());
  }
}
  
?>