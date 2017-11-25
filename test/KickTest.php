<?php

require_once 'KickTasksMock.php';
require_once 'OutputBuffer.php';

use PHPUnit\Framework\TestCase;

class KickTest extends TestCase
{
  public function testKickTasksEcho ()
  {
    KickTasksMock::$tasks = array(new TestEchoString("first "),
      new TestEchoString("second one; "),
      new TestEchoString("the last")
    );
    
    $output = new OutputBuffer();
    $output->start();
    
    include 'Kick.php';
    
    $output->stop();
    
    $this->assertEquals("first second one; the last", $output->getBuffer());
  }
}
  
?>