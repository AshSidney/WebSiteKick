<?php

require_once 'DBManager.php';

class WPComment
{
  function __construct ($db, $approvedMsg, $awaitingMsg)
  {
    $this->db = $db;
    $this->approvedMessage = $approvedMsg;
    $this->awaitingMessage = $awaitingMsg;
  }

  function execute ($xml)
  {
    $currMax = isset($xml->wpcomment) ? intval($xml->wpcomment) : 0;
    $newMax = $currMax;
    $queryMax = $this->db->query("SELECT MAX(comment_ID)
      FROM wp_comments
      WHERE comment_approved = '1'");
    if ($row = $queryMax->fetch())
    {
      $newApprovedMax = intval($row[0]);
      if ($currMax < $newApprovedMax)
      {
        $newMax = $newApprovedMax;
        echo $this->approvedMessage;
      }
    }
    $queryMax = $this->db->query("SELECT MAX(comment_ID)
      FROM wp_comments
      WHERE comment_approved NOT IN ('1', 'spam')");
    if ($row = $queryMax->fetch())
    {
      $newAwaitingMax = intval($row[0]);
      if ($currMax < $newApprovedMax)
      {
        if ($newMax < $newApprovedMax)
          $newMax = $newApprovedMax;
        echo $this->awaitingMessage;
      }
    }
  }
}

?>