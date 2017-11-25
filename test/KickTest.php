<?php

require_once 'KickTaskMock.php';
require_once 'OutputBuffer.php';

use PHPUnit\Framework\TestCase;

class KickTest extends TestCase
{
  public static function setUpBeforeClass()
  {
    chdir(__DIR__ . '/..');
  }
  
  public function testKickTasksEcho ()
  {
    KickTaskMock::$tasks = array(new TestEchoString("first "),
      new TestEchoString("second one;"),
      new TestEchoString("the last")
    );
    
    $output = new OutputBuffer();
    $output->start();
    
    include 'Kick.php';
    
    $output->stop();
    
    $this->assertEquals("first second one;the last", $output->getBuffer());
  }
  
  public function testKickTasksInitialXmlData ()
  {
    if (file_exists("Kick.xml"))
      unlink("Kick.xml");
    
      KickTaskMock::$tasks = array(new TestEchoXml("test1", "first "),
        new TestEchoXml("test2", "second one;")
      );
      
      $output = new OutputBuffer();
      $output->start();
      
      include 'Kick.php';
      
      $output->stop();
      
      $this->assertEquals("", $output->getBuffer());
      $this->assertTrue(file_exists("Kick.xml"));
      $data = simplexml_load_file("Kick.xml");
      $this->assertEquals("first ", (string)$data->test1);
      $this->assertEquals("second one;", (string)$data->test2);
  }
}
  
?>