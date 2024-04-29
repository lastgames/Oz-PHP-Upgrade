<?php

// Rules - config
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'rules_misc')
 {
 $errors = "0";
 if(empty($_POST['CF_OZ_CHARMAX'])){$error = "Empty field. A number must be entered in the Characters field."; $errors = "1";}

 if($errors != '1')
  { 
 $remove_array = array('S_ID','Submit','edit_type');
 foreach($_POST as $is => $what)
  {
  if(!in_array($is,$remove_array)) 
   {
   $update = mysql_query("UPDATE `config` SET `config_value`='$what' WHERE `config_name`='$is'");
   if(!$update){$error = "ERROR! $is could not be updated. Please contact the Web Manager.";}
   else 
    {
    $edittype = $_POST['edit_type'];
    header("Location: character.php?m=3");
    exit();
    }
   }
  }
  }
 }

// Stronghold Gazette
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'newspaper')
 {
 $errors = "0";
 if(empty($_FILES['news']['name'])){$error = "A file was not selected. Please browse for a file then click Submit."; $errors = "1";}
 if($errors != '1')
  {
  $good = "Y";
  if(!empty($_FILES['news']['name']))
   {
   $pdf = $_FILES['news']['name'];
   $filename = stripslashes($pdf);
   $extension = getExtension($filename);
   $extension = strtolower($extension);
   if($extension != 'pdf'){$error = "Invalid file extension. PDF only."; $errors = "1";}
   else
    {
    $charname = "gazette-volume-".$_POST['vol']."-number-".$_POST['num'];
    $newfname = "documents/oz/newspaper/".$charname.".".$extension;
    $copied = copy($_FILES['news']['tmp_name'], $newfname);  
    if(!$copied){$error = "The newspaper could not be uploaded. Please contact the <a href=/contact.php>Web Manager</a> about this error.";}
    else
     {
     header("Location: character.php?m=8");
     exit();
     }
    }
   }
  }
 }

// Rules - update rule books
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'rulebook')
 {
 $errors = "0";
 if(empty($_FILES['color']['name']) && empty($_FILES['bw']['name'])){$error = "A file was not selected. Please browse for a file then click Submit."; $errors = "1";}
 if($errors != '1')
  {
  $good = "Y";
  if(!empty($_FILES['color']['name']))
   {
   $pdf = $_FILES['color']['name'];
   $filename = stripslashes($pdf);
   $extension = getExtension($filename);
   $extension = strtolower($extension);
   if($extension != 'pdf'){$error = "Invalid file extension. PDF only."; $errors = "1";}
   else
    {
    $newfname = "documents/oz/oz-rulebook.".$extension;
    $copied = copy($_FILES['color']['tmp_name'], $newfname);  
    if(!$copied){$error = "The rulebook in color could not be uploaded. Please contact the <a href=/contact.php>Web Manager</a> about this error."; $good = "N";}
    }
   }
  if(!empty($_FILES['bw']['name']))
   {
   $pdf2 = $_FILES['bw']['name'];
   $filename2 = stripslashes($pdf2);
   $extension2 = getExtension($filename2);
   $extension2 = strtolower($extension2);
   if(($extension2 != "pdf")){$error = "Invalid file extension. PDF only. Posted: $extension"; $errors = "1";}
   else
    {
    $newfname2 = "documents/oz/oz-rulebook-bw.".$extension2;
    $copied2 = copy($_FILES['bw']['tmp_name'], $newfname2);  
    if(!$copied2){$error = "The rulebook in b&w could not be uploaded. Please contact the <a href=/contact.php>Web Manager</a> about this error."; $good = "N";}
    }
   }
  if($copied || $copied2)
   {
   header("Location: admin-rules.php?edit_type=4&m=1");
   exit();
   }
  }
 }

// Logistics - View as Player mode
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'viewas')
 {
 if(empty($_POST['player'])){$error = "Empty field. To view Character for a Player, a name must be entered in the View as Player field."; $errors = "1";}
 if($errors != '1')
  { 
  $player = $_POST['player'];
  $getn = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `pf_real_name`='$player'"));
  $id = $getn['user_id'];
  header("Location: character.php?mode=".$id);
  exit();
  }
 }

// Logistics - Player Points
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'playerpoints')
 {
 $errors = "0";
 if(empty($_POST['points'])){$error = "Empty field. A number must be entered in the Points field."; $errors = "1";}
 if(empty($_POST['player'])){$error = "Empty field. A name must be entered in the Player field."; $errors = "1";}
 if(empty($_POST['type'])){$error = "Empty selection. Please choose whether you are adding or subtracting Player Points."; $errors = "1";}
 if($_POST['reason'] == '1'){$reason = "Attendance";}
 if($_POST['reason'] == '2'){$reason = "Staff";}
 if($_POST['reason'] == '3'){$reason = "Correction";}
 if($_POST['reason'] == '4'){$reason = "Donation";}
 if($errors != '1')
  { 
  $player = $_POST['player'];
  $getn = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `pf_real_name`='$player'"));
  $getp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points,user_email FROM `bb_users` WHERE `user_id`='$getn[user_id]'"));
  if($_POST['type'] == 'add')
   {
   $newpoints = $getp['player_points'] + $_POST['points'];
   $pp_refund = $_POST['points'];
   $detail = "Player Points (Logistics): ".$reason;
   }
  else // del
   {
   $newpoints = $getp['player_points'] - $_POST['points'];
   if($newpoints <= '-1'){$newpoints = "0";}else{$newpoints = $newpoints;}
   $pp_spent = $_POST['points'];
   $detail = "Player Points (Logistics): ".$reason;
   }
  $update = mysql_query("UPDATE `bb_users` SET `player_points`='$newpoints' WHERE `user_id`='$getn[user_id]'");
  if(!$update){$error = "The player points could not be updated in the database. Please contact the Web Manager.";}
  else 
   {
   if($_POST['reason'] != '5')
    {
    $insert = mysql_query("INSERT INTO `characters_pplog` (`pp_refund`, `ppearned_spent`, `user_id`, `pp_date`, `pp_detail`, `pp_author`, `sort_date`) VALUES ('$pp_refund', '$pp_spent', '$getn[user_id]', '$date_format', '$detail', '$loggedin[username]', '$date')");
    $name = $_POST['player'];
    $email = $getp['user_email'];
    $headers = "Content-type: text/html\r\n";
    $subject  = "LAST Games: ".$detail;
    $message = "
<table width=500 align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td><img src=http://www.lastgamesnw.org/images/logo-last.jpg></td>
</tr>
</table>
<table width=500 style='border: solid 1px #E0E0E0;' align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td width=500><font face=arial size=2><strong>Player Point Adjustment</strong>
<br><br>
Hello $name,
<br><br>
Your player points have been adjusted by an Oz staff member.
<br><br>
Please log in and visit the Character Manager to view.
<br><br>
http://www.lastgamesnw.org/forum/index.php
<br><br>
Thank you!
</td></tr>
</table>
<table width=500 border=0 align=center bgcolor=white cellpadding=5 cellspacing=5>     
<tr height=40>     
<td colspan=500><font face=arial size=1.5>Copyright &copy; $siteyear Live Action Storytelling Games,  All rights reserved.</font></td>     
</tr>
</table>
";
    $sent = mail($email, $subject, $message, $headers);
    }
   header("Location: character.php?m=4");
   exit();
   }  
  }
 }
?>