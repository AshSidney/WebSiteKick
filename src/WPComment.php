<?php

require 'DBManager.php';

class WPComment
{
  function __construct ($db)
  {
    $this->db = $db;
  }

  function execute ($xml)
  {
    echo "stub for execution";
  }
}

?>