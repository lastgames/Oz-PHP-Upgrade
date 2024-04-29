<?php

$dbhost = 'localhost';
$dbuser = 'i608426_bb2';
$dbpass = 'E&g0&F1qd]60)~1';

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');

$dbname = 'i608426_bb2';
mysql_select_db($dbname) or die ('Error connecting to table');

?>