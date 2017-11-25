<?php

require 'Kick.ini';
    
$confFile = "Kick.xml";
$data = file_exists($confFile) ? simplexml_load_file($confFile)
  : new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><configurations/>");
    
foreach ($tasks as $task)
  $task->execute($data);

$data->asXML($confFile);
  
?>