<?php

require 'WPComment.php';

//require 'Kick.ini';
$tasks = array(
new WPComment(new DBManager("db name", "server name", "user name", "password")) );
    
/*$confFile = "Kick.xml";
$data = file_exist($confFile) ? simplexml_load_file($confFile)
  : new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><configurations/>");*/
$data = "XML";
    
for ($tasks as $task)
  $task->execute($data);
  
?>
