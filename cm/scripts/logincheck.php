<?php

session_start();

$is_logged = false; 
$loggedin = "0";

$cookie = $_COOKIE['phpbb3_krcdl_sid'];

if(isset($cookie))
 {
 $check_user = @mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_sessions` WHERE `session_user_id`!='1' AND `session_id`='$cookie'"));
 if($check_user)
  {
  $is_logged = true;
  $user_id = $check_user['session_user_id'];
  $loggedin = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_users` WHERE `user_id`='$user_id'"));
  $realname = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$user_id'"));
  }
 }

?>