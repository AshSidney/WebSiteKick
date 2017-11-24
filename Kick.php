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

  void load ($xml)
  {
    
  }
}


class Kicker
{
  void __construct ()
  {
    $this->tasks = array(
    require('kick.ini');
      );
    
    $this->data = file_exist($this->confFile) ? simplexml_load_file($this->confFile)
      : new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><configurations/>");
    
    for ($this->tasks as $task)
      $task->load($this->data);
  }
  
  private $tasks;
  private $data;
  private $confFile = "kick.xml";
}

?>