<?php

require_once 'DBManager.php';
require_once 'WPComment.php';

use PHPUnit\Framework\TestCase;


class WPCommentTest extends TestCase
{
  static $testDb;
  
  public static function setUpBeforeClass()
  {
    self::$testDb = new DBManager("kickTest", "localhost", "kickuser", "kickuser");
    self::$testDb->query("DELETE FROM wp_comments");
    self::$testDb->query("CREATE TABLE wp_comments (
      comment_ID bigint(20) unsigned NOT NULL,
      comment_post_ID bigint(20) unsigned NOT NULL DEFAULT '0',
      comment_author tinytext NOT NULL,
      comment_author_email varchar(100) NOT NULL DEFAULT '',
      comment_author_url varchar(200) NOT NULL DEFAULT '',
      comment_author_IP varchar(100) NOT NULL DEFAULT '',
      comment_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      comment_date_gmt datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      comment_content text NOT NULL,
      comment_karma int(11) NOT NULL DEFAULT '0',
      comment_approved varchar(20) NOT NULL DEFAULT '1',
      comment_agent varchar(255) NOT NULL DEFAULT '',
      comment_type varchar(20) NOT NULL DEFAULT '',
      comment_parent bigint(20) unsigned NOT NULL DEFAULT '0',
      user_id bigint(20) unsigned NOT NULL DEFAULT '0')");
    self::$testDb->query("INSERT INTO wp_comments (
      comment_ID, comment_author, comment_content, comment_approved)
      VALUES (5, 'user1', 'first comment', '1'),
             (9, 'newfriend', 'want to ask something', '0'),
             (13, 'xy', 'anonymous offer', 'spam'),
             (20, 'admin', 'moderating', '0')");
  }
  
  public function testNewCommentNoPrevData ()
  {
    $testCom = new WPComment(self::$testDb, "new approved\n", "new comment awaiting\n");
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations/>");
    
    $output = new OutputBuffer();
    $output->start();
    
    $testCom->execute($xml);
    
    $output->stop();
    
    $this->assertEquals("new approved\nnew comment awaiting\n", $output->getBuffer());
  }
}

?>