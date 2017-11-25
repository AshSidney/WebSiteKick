<?php

require_once 'D:\DevTools\phpunit\phpunit-5.7.21.phar';

$iniFile = __DIR__ . '/../Kick.ini';
if (!file_exists($iniFile))
  copy(__DIR__ . '/Kick-test.ini', $iniFile);

?>