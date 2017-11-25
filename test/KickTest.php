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
  
  public function testKickTasksLoadXmlData ()
  {
    file_put_contents("Kick.xml", "<?xml version='1.0' standalone='yes'?>
      <configurations><task1>these are 1. data,</task1>
      <task-2>2. data</task-2></configurations>");
      
    KickTaskMock::$tasks = array(new TestEchoXml("task1", "replaced data,"),
      new TestEchoXml("task-2", "last value")
    );
    
    $output = new OutputBuffer();
    $output->start();
    
    include 'Kick.php';
    
    $output->stop();
    
    $this->assertEquals("these are 1. data,2. data", $output->getBuffer());
    $this->assertTrue(file_exists("Kick.xml"));
    $data = simplexml_load_file("Kick.xml");
    $this->assertEquals("replaced data,", (string)$data->task1);
    $this->assertEquals("last value", (string)$data->{"task-2"});
  }
}
  
?>