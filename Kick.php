<?php

class DBManager
{
  function __construct($database, $server, $user, $password)
  {
    $this->dbName = $database;
    $this->serverName = $server;
    $this->userName = $user;
    $this->password = $password;
  }
  
  protected $connection = NULL;
  
  function getConnection ()
  {
    if ($this->connection == NULL)
    {
      try
      {
        $this->connection = new PDO("mysql:host=" . $this->serverName
          . ";dbname=" . $this->dbName . ";charset=utf8",
          $this->userName, $this->password);
      }
      catch (PDOException $e)
      {
        echo 'Connection failed: ' . $e->getMessage();
      }
    }
    return $this->connection;
  }
  
  function query ($sqlComm)
  {
    return $this->getConnection()->query($sqlComm);
  }
  
  function statement ($sqlComm)
  {
    return $this->getConnection()->prepare($sqlComm);
  }
}


class WPComment
{
  void __construct ($db)
  {
    $this->db = $db;
  }

  void execute ($xml)
  {
    echo "stub for execution";
  }
}


$tasks = array(
require('Kick.ini');
  );
    
$confFile = "Kick.xml";
$data = file_exist($confFile) ? simplexml_load_file($confFile)
  : new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><configurations/>");
    
for ($tasks as $task)
  $task->execute($data);
  
?>
