<?php

require_once 'DBManager.php';
require_once 'BBForum.php';

use PHPUnit\Framework\TestCase;


class BBForumTest extends TestCase
{
  static $testDb;
  
  public static function setUpBeforeClass()
  {
    self::$testDb = new DBManager("kickTest", "localhost", "kickuser", "kickuser");
    self::$testDb->query("DELETE FROM phpbb_posts");
    self::$testDb->query("CREATE TABLE phpbb_posts (
      post_id mediumint(8) unsigned NOT NULL,
      topic_id mediumint(8) unsigned NOT NULL DEFAULT '0',
      forum_id mediumint(8) unsigned NOT NULL DEFAULT '0',
      poster_id mediumint(8) unsigned NOT NULL DEFAULT '0',
      icon_id mediumint(8) unsigned NOT NULL DEFAULT '0',
      poster_ip varchar(40) NOT NULL DEFAULT '',
      post_time int(11) unsigned NOT NULL DEFAULT '0',
      post_approved tinyint(1) unsigned NOT NULL DEFAULT '1',
      post_reported tinyint(1) unsigned NOT NULL DEFAULT '0',
      enable_bbcode tinyint(1) unsigned NOT NULL DEFAULT '1',
      enable_smilies tinyint(1) unsigned NOT NULL DEFAULT '1',
      enable_magic_url tinyint(1) unsigned NOT NULL DEFAULT '1',
      enable_sig tinyint(1) unsigned NOT NULL DEFAULT '1',
      post_username varchar(255) NOT NULL DEFAULT '',
      post_subject varchar(255) NOT NULL DEFAULT '',
      post_text mediumtext NOT NULL,
      post_checksum varchar(32) NOT NULL DEFAULT '',
      post_attachment tinyint(1) unsigned NOT NULL DEFAULT '0',
      bbcode_bitfield varchar(255) NOT NULL DEFAULT '',
      bbcode_uid varchar(8) NOT NULL DEFAULT '',
      post_postcount tinyint(1) unsigned NOT NULL DEFAULT '1',
      post_edit_time int(11) unsigned NOT NULL DEFAULT '0',
      post_edit_reason varchar(255) NOT NULL DEFAULT '',
      post_edit_user mediumint(8) unsigned NOT NULL DEFAULT '0',
      post_edit_count smallint(4) unsigned NOT NULL DEFAULT '0',
      post_edit_locked tinyint(1) unsigned NOT NULL DEFAULT '0')");
    self::$testDb->query("DELETE FROM phpbb_users");
    self::$testDb->query("CREATE TABLE phpbb_users (
      user_id mediumint(8) unsigned NOT NULL,
      user_type tinyint(2) NOT NULL DEFAULT '0',
      group_id mediumint(8) unsigned NOT NULL DEFAULT '3',
      user_permissions mediumtext NOT NULL,
      user_perm_from mediumint(8) unsigned NOT NULL DEFAULT '0',
      user_ip varchar(40) NOT NULL DEFAULT '',
      user_regdate int(11) unsigned NOT NULL DEFAULT '0',
      username varchar(255) NOT NULL DEFAULT '',
      username_clean varchar(255) NOT NULL DEFAULT '',
      user_password varchar(40) NOT NULL DEFAULT '',
      user_passchg int(11) unsigned NOT NULL DEFAULT '0',
      user_pass_convert tinyint(1) unsigned NOT NULL DEFAULT '0',
      user_email varchar(100) NOT NULL DEFAULT '',
      user_email_hash bigint(20) NOT NULL DEFAULT '0',
      user_birthday varchar(10) NOT NULL DEFAULT '',
      user_lastvisit int(11) unsigned NOT NULL DEFAULT '0',
      user_lastmark int(11) unsigned NOT NULL DEFAULT '0',
      user_lastpost_time int(11) unsigned NOT NULL DEFAULT '0',
      user_lastpage varchar(200) NOT NULL DEFAULT '',
      user_last_confirm_key varchar(10) NOT NULL DEFAULT '',
      user_last_search int(11) unsigned NOT NULL DEFAULT '0',
      user_warnings tinyint(4) NOT NULL DEFAULT '0',
      user_last_warning int(11) unsigned NOT NULL DEFAULT '0',
      user_login_attempts tinyint(4) NOT NULL DEFAULT '0',
      user_inactive_reason tinyint(2) NOT NULL DEFAULT '0',
      user_inactive_time int(11) unsigned NOT NULL DEFAULT '0',
      user_posts mediumint(8) unsigned NOT NULL DEFAULT '0',
      user_lang varchar(30) NOT NULL DEFAULT '',
      user_timezone decimal(5,2) NOT NULL DEFAULT '0.00',
      user_dst tinyint(1) unsigned NOT NULL DEFAULT '0',
      user_dateformat varchar(30) NOT NULL DEFAULT 'd M Y H:i',
      user_style mediumint(8) unsigned NOT NULL DEFAULT '0',
      user_rank mediumint(8) unsigned NOT NULL DEFAULT '0',
      user_colour varchar(6) NOT NULL DEFAULT '',
      user_new_privmsg int(4) NOT NULL DEFAULT '0',
      user_unread_privmsg int(4) NOT NULL DEFAULT '0',
      user_last_privmsg int(11) unsigned NOT NULL DEFAULT '0',
      user_message_rules tinyint(1) unsigned NOT NULL DEFAULT '0',
      user_full_folder int(11) NOT NULL DEFAULT '-3',
      user_emailtime int(11) unsigned NOT NULL DEFAULT '0',
      user_topic_show_days smallint(4) unsigned NOT NULL DEFAULT '0',
      user_topic_sortby_type varchar(1) NOT NULL DEFAULT 't',
      user_topic_sortby_dir varchar(1) NOT NULL DEFAULT 'd',
      user_post_show_days smallint(4) unsigned NOT NULL DEFAULT '0',
      user_post_sortby_type varchar(1) NOT NULL DEFAULT 't',
      user_post_sortby_dir varchar(1) NOT NULL DEFAULT 'a',
      user_notify tinyint(1) unsigned NOT NULL DEFAULT '0',
      user_notify_pm tinyint(1) unsigned NOT NULL DEFAULT '1',
      user_notify_type tinyint(4) NOT NULL DEFAULT '0',
      user_allow_pm tinyint(1) unsigned NOT NULL DEFAULT '1',
      user_allow_viewonline tinyint(1) unsigned NOT NULL DEFAULT '1',
      user_allow_viewemail tinyint(1) unsigned NOT NULL DEFAULT '1',
      user_allow_massemail tinyint(1) unsigned NOT NULL DEFAULT '1',
      user_options int(11) unsigned NOT NULL DEFAULT '895',
      user_avatar varchar(255) NOT NULL DEFAULT '',
      user_avatar_type tinyint(2) NOT NULL DEFAULT '0',
      user_avatar_width smallint(4) unsigned NOT NULL DEFAULT '0',
      user_avatar_height smallint(4) unsigned NOT NULL DEFAULT '0',
      user_sig mediumtext NOT NULL,
      user_sig_bbcode_uid varchar(8) NOT NULL DEFAULT '',
      user_sig_bbcode_bitfield varchar(255) NOT NULL DEFAULT '',
      user_from varchar(100) NOT NULL DEFAULT '',
      user_icq varchar(15) NOT NULL DEFAULT '',
      user_aim varchar(255) NOT NULL DEFAULT '',
      user_yim varchar(255) NOT NULL DEFAULT '',
      user_msnm varchar(255) NOT NULL DEFAULT '',
      user_jabber varchar(255) NOT NULL DEFAULT '',
      user_website varchar(200) NOT NULL DEFAULT '',
      user_occ text NOT NULL,
      user_interests text NOT NULL,
      user_actkey varchar(32) NOT NULL DEFAULT '',
      user_newpasswd varchar(40) NOT NULL DEFAULT '',
      user_form_salt varchar(32) NOT NULL DEFAULT '')");
  }
  
  public function setUp()
  {
    self::$testDb->query("DELETE FROM phpbb_posts");
    self::$testDb->query("INSERT INTO phpbb_posts (post_id, post_text)
      VALUES (5, 'first post'),
             (9, 'following one'),
             (21, 'last discussion')");
    self::$testDb->query("DELETE FROM phpbb_users");
    self::$testDb->query("INSERT INTO phpbb_users (
      user_id, user_permissions, user_sig, user_occ, user_interests)
      VALUES (1, '', 'user1', '', ''),
             (6, '', 'admin', '', ''),
             (14, '', 'spammer', '', '')");
  }
  
  function execute ($testForum, $xml)
  {
    $output = new OutputBuffer();
    $output->start();
    
    $testForum->execute($xml);
    
    $output->stop();
    
    return $output->getBuffer();
  }
  
  public function testNewPostsUsersNoStartData ()
  {
    self::$testDb->query("DELETE FROM phpbb_posts WHERE post_id > 10");
    self::$testDb->query("DELETE FROM phpbb_users WHERE user_id > 10");
    
    $testForum = new BBForum(self::$testDb, array("post" => "new posts\n", "user" => "new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?><configurations/>");
    
    $this->assertEquals("new posts\nnew users\n", $this->execute($testForum, $xml));
    $this->assertEquals(9, intval($xml->bbforum->post));
    $this->assertEquals(6, intval($xml->bbforum->user));
  }
  
  public function testNewPostsUsersFromStartData ()
  {
    $testForum = new BBForum(self::$testDb, array("post" => "new posts\n", "user" => "new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>5</post><user>6</user></bbforum></configurations>");
    
    $this->assertEquals("new posts\nnew users\n", $this->execute($testForum, $xml));
    $this->assertEquals(21, intval($xml->bbforum->post));
    $this->assertEquals(14, intval($xml->bbforum->user));
  }
  
  public function testNewPosts ()
  {
    $testForum = new BBForum(self::$testDb, array("post" => "new posts\n", "user" => "new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>5</post><user>14</user></bbforum></configurations>");
    
    $this->assertEquals("new posts\n", $this->execute($testForum, $xml));
    $this->assertEquals(21, intval($xml->bbforum->post));
    $this->assertEquals(14, intval($xml->bbforum->user));
  }
  
  public function testNewUsers ()
  {
    $testForum = new BBForum(self::$testDb, array("post" => "new posts\n", "user" => "new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>30</post><user>10</user></bbforum></configurations>");
    
    $this->assertEquals("new users\n", $this->execute($testForum, $xml));
    $this->assertEquals(30, intval($xml->bbforum->post));
    $this->assertEquals(14, intval($xml->bbforum->user));
  }
  
  public function testNoNewUsersPosts ()
  {
    $testForum = new BBForum(self::$testDb, array("post" => "new posts\n", "user" => "new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>30</post><user>20</user></bbforum></configurations>");
    
    $this->assertEquals("", $this->execute($testForum, $xml));
    $this->assertEquals(30, intval($xml->bbforum->post));
    $this->assertEquals(20, intval($xml->bbforum->user));
  }
  
  public function testJustNewPostsWatched ()
  {
    $testForum = new BBForum(self::$testDb, array("post" => "new posts only\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>2</post><user>1</user></bbforum></configurations>");
    
    $this->assertEquals("new posts only\n", $this->execute($testForum, $xml));
    $this->assertEquals(21, intval($xml->bbforum->post));
    $this->assertEquals(1, intval($xml->bbforum->user));
  }
  
  public function testJustNewUsersWatched ()
  {
    $testForum = new BBForum(self::$testDb, array("user" => "just new users\n"));
    
    $xml = new SimpleXMLElement("<?xml version='1.0' standalone='yes'?>
      <configurations><bbforum><post>2</post><user>1</user></bbforum></configurations>");
    
    $this->assertEquals("just new users\n", $this->execute($testForum, $xml));
    $this->assertEquals(2, intval($xml->bbforum->post));
    $this->assertEquals(14, intval($xml->bbforum->user));
  }
}
  
?>