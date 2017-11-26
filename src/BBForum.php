<?php

require_once 'DBManager.php';

class BBForum
{
  function __construct ($db, $opts)
  {
    $this->db = $db;
    $this->options = $opts;
  }
  
  static $tabSpec = array("post" => array("phpbb_posts", "post_id"),
    "user" => array("phpbb_users", "user_id"));
  
  function execute ($xml)
  {
    foreach (self::$tabSpec as $id => $spec)
      if (isset($this->options[$id]))
      {
        $currMax = isset($xml->bbforum->{$id}) ? intval($xml->bbforum->{$id}) : 0;
        $queryMax = $this->db->query("SELECT MAX(" . $spec[1] . ")
          FROM " . $spec[0]);
        if ($row = $queryMax->fetch())
        {
          $newMax = intval($row[0]);
          if ($currMax < $newMax)
          {
            echo $this->options[$id];
            $xml->bbforum->{$id} = $newMax;
          }
        }
      }
  }
}

?>