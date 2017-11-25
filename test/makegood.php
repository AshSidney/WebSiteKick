<?php

require_once 'D:\DevTools\phpunit\phpunit-5.7.21.phar';

$iniFile = __DIR__ . '/../Kick.ini';
$testIniFile = __DIR__ . '/Kick-test.ini';
if (file_get_contents($iniFile) != file_get_contents($testIniFile))
  copy($testIniFile, $iniFile);

?>