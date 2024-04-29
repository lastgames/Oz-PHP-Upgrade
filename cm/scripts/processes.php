<?php

if(!empty($_POST['player_name_new']))
 {
 $player_name = mysql_real_escape_string($_POST['player_name_new']);
 $player_name = trim($player_name);
 $player_name = preg_replace("/[^A-Za-z0-9\s\s+]/", "", $player_name);
 $player_name = strtolower($player_name);
 $player_name = ucwords($player_name);
 }

// Contact form
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'contactform')
 {
 $errors = "0";
 if(empty($_POST['name'])){$error = "Your name is required."; $errorname = "1"; $errors = "1";}
 if(empty($_POST['email'])){$error = "Your email is required."; $erroremail = "1"; $errors = "1";}
 if(empty($_POST['limitedtextarea'])){$error = "A comment is required."; $errorcomment = "1"; $errors = "1";}

 require_once('recaptchalib.php');
 $privatekey = "6Le5it0SAAAAABmfqY3J34sEB3xS8ZHhZ6ChXqHd";
 $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
 if (!$resp->is_valid){$error = "ERROR! See below:"; $errorvery = "1";  $errors = "1"; return; }
 if($errors != '1')
  {
  $errors = "0";
  $email = mysql_real_escape_string($_POST['email']);
 if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)){$error = "ERROR! Invalid Email Format: example@url.com"; $erroremail = "1"; $errors = "2";}
  if($errors != '2')
   { 
   $get_groupname = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_groups` WHERE `group_id`='$_POST[group_id]'"));
   $groupname = $get_groupname['group_name'];
   $name = mysql_real_escape_string($_POST['name']);
   $comment = mysql_real_escape_string($_POST['limitedtextarea']);
   $comment_mail = $_POST['limitedtextarea'];
   $headers = "Content-type: text/html\r\n";
   $subject  = "LAST Games: Contact Form";
   $message = "
<table width=500 align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td><img src=http://www.lastgamesnw.org/images/last/logo.png></td>
</tr>
</table>
<table width=500 style='border: solid 1px #E0E0E0;' align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td width=500><font face=arial size=2><strong>Sent from the Contact Us form</strong>
<br><br>
Sender's Name: $name
<br><br>
Sender's Email: $email
<br><br>
Contacting: $groupname
<br><br>
$comment_mail
</td></tr>
</table>
<table width=500 border=0 align=center bgcolor=white cellpadding=5 cellspacing=5>     
<tr height=40>     
<td colspan=500><font face=arial size=1.5>Copyright &copy; $year Live Action Storytelling Games,  All rights reserved.</font></td>     
</tr>
</table>
";
   $get_staff = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `group_id`='$_POST[group_id]'"));
   $get_emails = mysql_query("SELECT user_id,user_email FROM `bb_users`");
   while($staffemail = mysql_fetch_assoc($get_emails))
    {
    if($get_staff['user_id'] == $staffemail['user_id'])
     {
     $smail = $staffemail['user_email'];
     $sent = mail($smail, $subject, $message, $headers);
     }
    }
   $groupid = $_POST['group_id'];
   $id = $_POST['page'];
   if(!$sent){$error = "The email could not be sent to the group. Please contact us via the Forum or Facebook. We apologize for the inconvenience.";}
   else
    {
    $poster = "337";
    $poster_name = "Email";
    $post_time = time();
    $post_subject = "Posted via the Contact Us form";
    $post_checksum = substr(md5(rand()), 0, 32);
    $bbcode = substr(md5(rand()), 0, 8);
    if($groupid == '9'){$fid = "21";} // rules
    else if($groupid == '10'){$fid = "22";} // sc
    else if($groupid == '11'){$fid = "51";} // logistics
    else if($groupid == '12'){$fid = "52";} // gm
    else if($groupid == '14'){$fid = "50";} // cg
    else if($groupid == '15'){$fid = "53";} // deco
    else if($groupid == '20'){$fid = "17";} // technical
    else if($groupid == '13'){$fid = "54";} // zombie
    else {$fid = "55";} // lastgames

    $get_first_topic_id = mysql_fetch_assoc(mysql_query("SELECT topic_first_post_id FROM `bb_topics` ORDER BY `topic_first_post_id` DESC LIMIT 1"));
    $first_post = $get_first_topic_id['topic_first_post_id'] + 1;

    $insert_topic = mysql_query("INSERT INTO `bb_topics` (`forum_id`, `topic_approved`, `topic_title`, `topic_poster`, `topic_time`, `topic_views`, `topic_first_post_id`, `topic_first_poster_name`, `topic_last_post_id`, `topic_last_poster_name`, `topic_last_post_subject`, `topic_last_post_time`, `topic_last_view_time`) VALUES ('$fid', '1', '$post_subject', '$poster', '$post_time','1', '$first_post', '$poster_name', '$first_post', '$poster_name', '$post_subject', '$post_time', '$post_time')");
    if(!$insert_topic){$error = "The email has been sent, but could not be stored in the topic database. Please inform the Web Master in the Technical Forum. Thank you.";}    
    else
     {  
     $get_topic = mysql_fetch_assoc(mysql_query("SELECT topic_id FROM `bb_topics` ORDER BY `topic_id` DESC LIMIT 1"));
     $topic = $get_topic['topic_id'];

     $comment2 = $comment."<br><br>Sent by: ".$name."<br>Email: ".$email;

     $insert_post = mysql_query("INSERT INTO `bb_posts` (`topic_id`, `forum_id`, `poster_id`, `post_time`, `post_approved`, `enable_bbcode`, `enable_smilies`, `enable_magic_url`, `post_subject`, `post_text`, `post_checksum`, `post_attachment`, `bbcode_uid`, `post_postcount`) VALUES ('$topic', '$fid', '$poster','$post_time','1','1','1','1', '$post_subject','$comment2','$post_checksum', '0','$bbcode','1')");
     if(!$insert_post){$error = "The email has been sent, but could not be stored in the post database. Please inform the Web Master in the Technical Forum. Thank you.";}
     else
      {
      header("Location: contact.php?page=".$id."&group=".$groupid."&m=1");
      exit();
      }
     }
    }
   }
  }
 }

// Approving background
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'cg_background')
 {
 $errors = "0";
 if(empty($_POST['check'])){$error = "To manage a background, at least one character must be selected."; $errors = "1";}
 if(empty($_POST['approve'])){$error = "Please select a background option."; $errors = "1";}
 if($errors != '1')
  {
  $check = $_POST['check'];
  foreach($check as $val)
   {
   if($_POST['approve'] == 'Y'){$answer = "Y";}else{$answer = "N";}
   $update = mysql_query("UPDATE `characters` SET `bg_approved`='$answer' WHERE `char_id`='$val'");
   if(!$update){$error = "Unable to update the database. Please contact the Web Manager.";}
   else
    {
    $character = mysql_fetch_assoc(mysql_query("SELECT char_id,user_id,char_name FROM `characters` WHERE `char_id`='$val'"));
    $user = mysql_fetch_assoc(mysql_query("SELECT user_id,user_email,username,player_points FROM `bb_users` WHERE `user_id`='$character[user_id]'"));
    $charname = $character[char_name];
    $email = $user['user_email'];
    if($answer == 'Y')
     {
     $ppaward = $CF_OZ_BG_PP;
     $detail = "Background Approved (CG): ".$character['char_name'];
     $insert = mysql_query("INSERT INTO `characters_pplog` (`char_id`, `char_name`, `pp_refund`, `user_id`, `pp_date`, `pp_detail`, `pp_author`, `sort_date`) VALUES ('$val', '$character[char_name]', '$ppaward', '$character[user_id]', '$date_format', '$detail', '$loggedin[username]', '$date')");
     $newpp =  $user['player_points'] + $ppaward;
     $update = mysql_query("UPDATE `bb_users` SET `player_points`='$newpp' WHERE `user_id`='$character[user_id]' LIMIT 1");
     $headers = "Content-type: text/html\r\n";
     $subject  = "World of Oz: Character Background Approved";
     $message = "
<table width=500 align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td><img src=http://www.lastgamesnw.org/images/last/logo.png></td>
</tr>
</table>
<table width=500 style='border: solid 1px #E0E0E0;' align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td width=500><font face=arial size=2><strong>Character Background Approved</strong>
<br><br>
The background has been approved for $charname and you have been awarded $ppaward player points!
<br><br>
Thank you,
<br><br>
The World of Oz LARP 
</td></tr>
</table>
<table width=500 border=0 align=center bgcolor=white cellpadding=5 cellspacing=5>     
<tr height=40>     
<td colspan=500><font face=arial size=1.5>Copyright &copy; $year Live Action Storytelling Games,  All rights reserved.</font></td>     
</tr>
</table>
";
     $sent = mail($email, $subject, $message, $headers);
     $m = "1";
     }
    else
     {
     $headers = "Content-type: text/html\r\n";
     $subject  = "World of Oz: Character Background Denied";
     $message = "
<table width=500 align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td><img src=http://www.lastgamesnw.org/images/oz/logo.png></td>
</tr>
</table>
<table width=500 style='border: solid 1px #E0E0E0;' align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td width=500><font face=arial size=2><strong>Character Background Denied</strong>
<br><br>
The background for $charname has been denied.
<br><br>
For more information, please contact the Character Guide staff at:
<br><br>
http://www.lastgamesnw.org/contact.php
<br><br>
Thank you,
<br><br>
The World of Oz LARP 
</td></tr>
</table>
<table width=500 border=0 align=center bgcolor=white cellpadding=5 cellspacing=5>     
<tr height=40>     
<td colspan=500><font face=arial size=1.5>Copyright &copy; $year Live Action Storytelling Games,  All rights reserved.</font></td>     
</tr>
</table>
";
     $sent = mail($email, $subject, $message, $headers);  
     $m = "2";
     }
    header("Location: character.php?m=".$m);
    exit();    
    }
   }
  }
 }

// Character - Photo
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'updatephoto')
 {
 if(!empty($_POST['no_image']) && $_POST['no_image'] == 'Y'){$picture = "/images/oz/logo-oz.png"; $update_char = mysql_query("UPDATE `characters` SET `who_photo`='$picture' WHERE `char_id`='$_POST[char_id]' LIMIT 1");}
 else
  {
 if(empty($_FILES['img']['name'])){$error = "Please select an image to upload.";}
 else
  {  
  $photo = $_FILES['img']['name'];
// $CF_OZ_CHARPIC_MAXSIZE
  define("MAX_SIZE", 100);
  $filename = stripslashes($photo);
  $uploadedfile = $_FILES['img']['tmp_name'];
  $extension = getExtension($filename);
  $extension = strtolower($extension);
  if(($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")){$error = "ERROR! Invalid file extension. .jpg, .gif or .png only";}
  else
   {
   $size = $_FILES['img']['size'];
   if ($size > MAX_SIZE*1024){$error = "Photo exceeds size limit. The maximum size is ".MAX_SIZE."kb";}
   else
    {
    if($extension=="jpg" || $extension=="jpeg" ){$src = imagecreatefromjpeg($uploadedfile);}
    else if($extension=="png"){$src = imagecreatefrompng($uploadedfile);} 
    else{$src = imagecreatefromgif($uploadedfile);}
    list($width,$height) = getimagesize($uploadedfile);
    if($width >= 151)
     {    
     $newwidth = 150;
     $newheight = ($height/$width)*$newwidth;
     $tmp = imagecreatetruecolor($newwidth,$newheight);
     imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);

     $img_name = strtolower($_POST['char_name']);
     $img_name = str_replace(" ", "_", $img_name); 
     $newname = "images/oz/publicbios/".$img_name.".".$extension;
     $copied = copy($_FILES['img']['tmp_name'], $newname);
     imagejpeg($tmp,$newname,100);
     imagedestroy($tmp);
     imagedestroy($src);
     }
    else
     {    
     $img_name = strtolower($_POST['char_name']);
     $img_name = str_replace(" ", "_", $img_name); 
     $newname = "images/oz/publicbios/".$img_name.".".$extension;
     $copied = copy($_FILES['img']['tmp_name'], $newname);
     imagedestroy($src);
     } 
    if(!$copied){$error = "Photo upload failed. Please contact the Web Manager about this error.";}
    else
     {
     $picture = "/images/oz/publicbios/".$img_name.".".$extension;
     $update_char = mysql_query("UPDATE `characters` SET `who_photo`='$picture' WHERE `char_id`='$_POST[char_id]' LIMIT 1");
      }
     }
    }
   }
  }
 }

// Character - Biography
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'updatebio')
 {
 $errors = "0";
 $biofield = $_POST['limitedtextarea2'];
 if(empty($biofield)){$error = "There was no text in the biography field."; $errors = "1";}
 $fcount = strlen($biofield);
 if($fcount <= '50'){$error = "Your biography is too short. Please write more text."; $errors = "1";}
 if($fcount >= '550'){$error = "Your biography is too long. 500 character maximum."; $errors = "1";}

 if($errors != '1')
  {
  $id = $_POST['char_id'];
  $bio = mysql_real_escape_string($_POST['limitedtextarea2']);
  $update_char = mysql_query("UPDATE `characters` SET `who_bio`='$bio', `who_visible`='$_POST[who_visible]' WHERE `char_id`='$id' LIMIT 1");
  if(!$update_char){$error = "There was a database error and your biography could not be updated. Please contact the Web Manager about this error."; $errors = "1";}
  else
   {
   if(!empty($_POST['mode'])){$mode = "&mode=".$modeid;}
   header("Location: character-view.php?char_id=".$id."&m=5".$mode);
   exit();   
   }
  }
 }

// Character - Background
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'submitbg')
 {
 $errors = "0";
 $id = $_POST['char_id'];
 $background = trim('');
 $background_file = trim('');
 $charname = $_POST['char_name'];
 if(empty($_POST['limitedtextarea']) && empty($_FILES['pdf']['name'])){$error = "No background was submitted. Please enter text in the textbox or upload a file."; $errors = "1";}
 else if(!empty($_POST['limitedtextarea']) && !empty($_FILES['pdf']['name'])){$error = "You must either submit your background in the textbox or upload a file. Both cannot be subitted."; $errors = "1";}
 else if(!empty($_POST['limitedtextarea']) && empty($_FILES['pdf']['name'])){$background = mysql_real_escape_string($_POST['limitedtextarea']);}
 else if(empty($_POST['limitedtextarea']) && !empty($_FILES['pdf']['name']))
  {
  define("MAX_SIZE","1000");
  $pdf = $_FILES['pdf']['name'];
  $filename = stripslashes($pdf);
  $extension = getExtension($filename);
  $extension = strtolower($extension);
  if(($extension != "pdf") && ($extension != "doc") && ($extension != "txt")){$error = "Invalid file extension. Only .pdf, .doc, or .txt allowed. Posted: $extension"; $errorpdf = "1"; $errors = "1";}
  else
   {
   $size = $_FILES['pdf']['size'];
   if ($size > MAX_SIZE*1024){$error = "File exceeds size limit. The maximum size is ".MAX_SIZE."kb"; $errorpdf = "1"; $errors = "1";}
   else
    {
    $charname = str_replace(" ", "_", $charname);
    $charname = strtolower($charname);
    $newfname = "documents/oz/backgrounds/".$charname.".".$extension;
    $copied = copy($_FILES['pdf']['tmp_name'], $newfname);  
    if(!$copied){$error = "Background file upload failed. Please contact the <a href=/contact.php>Web Manager</a> about this error."; $errors = "1";}
    else{$background_file =  "/documents/oz/backgrounds/".$charname.".".$extension;}
    }
   }
  }
 if($errors != '1')
  {
  $update = mysql_query("UPDATE `characters` SET `bg_approved`='S', `background`='$background', `background_file`='$background_file', `edit_date`='$date_format', `edit_author`='$loggedin[username]' WHERE `char_id`='$id' LIMIT 1"); 
  if(!$update){$error = "Unable to submit the character background due to a database error. Please contact the <a href=/contact.php>Web Manager</a> about this error.";}
  else
   {
   $get_cg = mysql_query("SELECT * FROM `bb_user_group` WHERE `group_id`='14'");
   while($cg = mysql_fetch_assoc($get_cg))
    {
    $get_cgmail = mysql_fetch_assoc(mysql_query("SELECT user_id,user_email FROM `bb_users` WHERE `user_id`='$cg[user_id]'"));
    $cgmail = $get_cgmail['user_email'];
    $player = $_POST['player_name'];
    $char = $_POST['char_name'];
    $headers = "Content-type: text/html\r\n";
    $subject  = "World of Oz: Character Background submitted";
    $message = "
<table width=500 align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td><img src=http://www.lastgamesnw.org/images/oz/logo.png></td>
</tr>
</table>
<table width=500 style='border: solid 1px #E0E0E0;' align=center bgcolor=white cellpadding=5 cellspacing=5>
<tr>
<td width=500><font face=arial size=2><strong>Character Background Submitted</strong>
<br><br>
A background for $charname has been submitted by $player.
<br><br>
Log in at http://lastgamesnw.org, visit the Character Manager to manage the character.
</td></tr>
</table>
<table width=500 border=0 align=center bgcolor=white cellpadding=5 cellspacing=5>     
<tr height=40>     
<td colspan=500><font face=arial size=1.5>Copyright &copy; $year Live Action Storytelling Games,  All rights reserved.</font></td>     
</tr>
</table>
";
    $sent = mail($cgmail, $subject, $message, $headers);
    }
   if(!empty($_POST['mode'])){$mode = "&mode=".$modeid;}
   header("Location: character-view.php?char_id=".$id."&m=6".$mode);
   exit();   
   }
  }
 }

// Character - Modify Details
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'modifydetails')
 {
 if(!empty($_POST['delete']) && $_POST['delete'] == 'Y')
  {
  $delete = mysql_query("DELETE FROM `characters` WHERE `char_id`='$_POST[char_id]' LIMIT 1");
  if(!$delete){$error = "$_POST[char_name] could not be deleted from the database. Please inform the Web Manager of this error.";}
  else
   {
   $delete_pp = mysql_query("DELETE FROM `characters_pplog` WHERE `char_id`='$_POST[char_id]'");
   if(!empty($_POST['pp_manage']) && $_POST['pp_manage'] == 'Y')
    {
    $pp_refund = $_POST['ppearned_spent'];
    $pp_new = $_POST['player_points'] + $pp_refund;
    $pp_detail = "Deleted Character (Logistics): ".$_POST['char_name'];
    $insert_pp = mysql_query("INSERT INTO `characters_pplog` (`pp_refund`, `pp_detail`, `user_id`, `pp_author`, `sort_date`, `pp_date`) VALUES ('$pp_refund', '$pp_detail', '$_POST[user_id]', '$loggedin[username]', '$date', '$date_format')"); 
    $update_user = mysql_query("UPDATE `bb_users` SET `player_points`='$pp_new' WHERE `user_id`='$_POST[user_id]' LIMIT 1");   
    }
   header("Location: character.php?m=7");
   exit();    
   }
  }
 else
  {
  $ppearned_spent = $_POST['ppearned_spent'];
  if(empty($_POST['race_blood_cur'])){$blood = "N";}else{$blood = $_POST['race_blood_cur'];}
  $species = $_POST['race_id_cur'];
  $record = "N";
  $pp_refund = "0";
  $pp_spent = "0";

  if($_POST['race_id'] != $_POST['race_id_cur'])
   {
   $species = $_POST['race_id'];    
   if($blood == 'Y' && $_POST['pp_manage'] == 'Y'){$ppearned_spent = $_POST['ppearned_spent'] - $CF_OZ_PURE_PP; $pp_new = $_POST['player_points'] + $CF_OZ_PURE_PP; $pp_refund = $CF_OZ_PURE_PP; $pp_detail = "Adjusted Pure Blood (Logistics): ".$_POST['char_name']; $record = "Y";}
   $blood = "N";
   }

  $racename = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_races` WHERE `race_id`='$species' LIMIT 1")); 
  $magic = $_POST['magic'];
  if($species == '3'){$magic = $magic + $CF_OZ_MAGIC_BONUS;}

  if($_POST['race_blood'] != $_POST['race_blood_cur'])
   {
   $blood = $_POST['race_blood'];  
   if($_POST['race_blood'] == 'N' && $_POST['race_blood_cur'] == 'Y')
    {
    if($_POST['pp_manage'] == 'Y')
     {
     $ppearned_spent = $_POST['ppearned_spent'] - $CF_OZ_PURE_PP; 
     $pp_new = $_POST['player_points'] + $CF_OZ_PURE_PP; 
     $pp_refund = $CF_OZ_PURE_PP; 
     $pp_detail = "Pure Blood (Logistics): ".$_POST['char_name']; 
     $record = "Y";
     }
    }
   if($_POST['race_blood'] == 'Y' && $_POST['race_blood_cur'] == 'N')
    {
    if($_POST['pp_manage'] != 'Y')
     {
     $ppearned_spent = $_POST['ppearned_spent'] + $CF_OZ_PURE_PP; 
     $pp_new = $_POST['player_points'] - $CF_OZ_PURE_PP; 
     $pp_spent = $CF_OZ_PURE_PP; 
     $pp_detail = "Pure Blood: ".$_POST['char_name']; 
     $record = "Y";
     }
    }
   } 

 $m = "1";
 $char_status = $_POST['char_status'];
 $countcheck = mysql_query("SELECT * FROM `characters` WHERE `user_id`='$_POST[user_id]' AND `char_status`!='2'");
 $count3 = mysql_num_rows($countcheck);
 if($count3 >= $CF_OZ_CHARMAX && $char_status == '1')
  {
  $m = "7";
  $char_status = "2";
  }

  $update_char = mysql_query("UPDATE `characters` SET `char_status`='$char_status', `race`='$species', `race_name`='$racename[race_name]', `ability`='$racename[race_abil]', `restriction`='$racename[race_rest]', `race_blood`='$blood', `magic_points`='$magic', `shapeshift`='$_POST[shapeshift]', `ppearned_spent`='$ppearned_spent', `edit_date`='$date_format', `edit_author`='$loggedin[username]' WHERE `char_id`='$_POST[char_id]' LIMIT 1");  
  if(!$update_char){$error = "$_POST[char_name] could not be modified. Please inform the Web Manager of this error.";}
  else
   {
   if($record == 'Y')
    {
    $insert_pp = mysql_query("INSERT INTO `characters_pplog` (`char_id`, `pp_pureblood`, `pp_refund`, `ppearned_spent`, `pp_detail`, `user_id`, `pp_author`, `sort_date`, `pp_date`) VALUES ('$_POST[char_id]', '3', '$pp_refund', '$pp_spent', '$pp_detail', '$_POST[user_id]', '$loggedin[username]', '$date', '$date_format')");
    $update_user = mysql_query("UPDATE `bb_users` SET `player_points`='$pp_new' WHERE `user_id`='$_POST[user_id]' LIMIT 1");  
    }
   if(!empty($_GET['mode'])){$mode = "&mode=".$_GET['mode'];}
   $id = $_POST['char_id'];
   header("Location: character-view.php?char_id=".$id."&m=".$m.$mode);
   exit(); 
   }
  }
 }

// Character - Skills
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'buyskills')
 {
 $errors = "0";

 if($_POST['skill_path_new'] == $_POST['skill_path']){$skillp = $_POST['skill_path'];}else{$skillp = $_POST['skill_path_new'];}
 if($_POST['skill1_new'] == $_POST['skill1']){$skill1 = $_POST['skill1'];}else{$skill1 = $_POST['skill1_new'];}
 if($_POST['skill2_new'] == $_POST['skill2']){$skill2 = $_POST['skill2'];}else{$skill2 = $_POST['skill2_new'];}
 if($_POST['skill3_new'] == $_POST['skill3']){$skill3 = $_POST['skill3'];}else{$skill3 = $_POST['skill3_new'];}
 if($_POST['skill4_new'] == $_POST['skill4']){$skill4 = $_POST['skill4'];}else{$skill4 = $_POST['skill4_new'];}

 if(!empty($skill1)){if($skill1 == $skill2 || $skill1 == $skill3 || $skill1 == $skill4){$error = "Duplicate Skills selected."; $errors = "1";}}
 if(!empty($skill2)){if($skill1 == $skill2 || $skill2 == $skill3 || $skill2 == $skill4){$error = "Duplicate Skills selected."; $errors = "1";}}
 if(!empty($skill3)){if($skill1 == $skill3 || $skill2 == $skill3 || $skill3 == $skill4){$error = "Duplicate Skills selected."; $errors = "1";}}
 if(!empty($skill4)){if($skill1 == $skill4 || $skill4 == $skill3 || $skill3 == $skill4){$error = "Duplicate Skills selected."; $errors = "1";}}

 if($_POST['skill_path_lvl_new'] != $_POST['skill_path_lvl']){$skillp_lvl = $_POST['skill_path_lvl_new'];}else{if(empty($_POST['skill_path_lvl'])){$skillp_lvl = "1";}else{$skillp_lvl = $_POST['skill_path_lvl'];}}
 if($_POST['skill1_lvl_new'] != $_POST['skill1_lvl']){$skill1_lvl = $_POST['skill1_lvl_new'];}else{if(empty($_POST['skill1_lvl'])){$skill1_lvl = "1";}else{$skill1_lvl = $_POST['skill1_lvl'];}}
 if($_POST['skill2_lvl_new'] != $_POST['skill2_lvl']){$skill2_lvl = $_POST['skill2_lvl_new'];}else{if(empty($_POST['skill2_lvl'])){$skill2_lvl = "1";}else{$skill2_lvl = $_POST['skill2_lvl'];}}
 if($_POST['skill3_lvl_new'] != $_POST['skill3_lvl']){$skill3_lvl = $_POST['skill3_lvl_new'];}else{if(empty($_POST['skill3_lvl'])){$skill3_lvl = "1";}else{$skill3_lvl = $_POST['skill3_lvl'];}}
 if($_POST['skill4_lvl_new'] != $_POST['skill4_lvl']){$skill4_lvl = $_POST['skill4_lvl_new'];}else{if(empty($_POST['skill4_lvl'])){$skill4_lvl = "1";}else{$skill4_lvl = $_POST['skill4_lvl'];}}

 if($skill1 == '12' || $skill2 == '12' || $skill3 == '12' || $skill4 == '12')
  {
  if($skill1 == '1' || $skill2 == '1' || $skill3 == '1' || $skill4 == '1'){$error = "You cannot have both the Witch and Alchemist skills. Please choose one."; $errors = "1";}
  else
   {
   if($skill1 == '12'){if($skill1_lvl == '1'){$magic = $CF_OZ_MAGIC_1;}else if($skill1_lvl == '2'){$magic = $CF_OZ_MAGIC_2;}else{$magic = $CF_OZ_MAGIC_3;}}
   if($skill2 == '12'){if($skill2_lvl == '1'){$magic = $CF_OZ_MAGIC_1;}else if($skill2_lvl == '2'){$magic = $CF_OZ_MAGIC_2;}else{$magic = $CF_OZ_MAGIC_3;}}
   if($skill3 == '12'){if($skill3_lvl == '1'){$magic = $CF_OZ_MAGIC_1;}else if($skill3_lvl == '2'){$magic = $CF_OZ_MAGIC_2;}else{$magic = $CF_OZ_MAGIC_3;}}
   if($skill4 == '12'){if($skill4_lvl == '1'){$magic = $CF_OZ_MAGIC_1;}else if($skill4_lvl == '2'){$magic = $CF_OZ_MAGIC_2;}else{$magic = $CF_OZ_MAGIC_3;}}
   }
  }
 if($_POST['race'] == '3'){$magic = $magic + $CF_OZ_MAGIC_BONUS;}

 if(empty($_POST['skill_path']) && empty($_POST['skill_path_new'])){$ppP = "0";}
 else if(empty($_POST['skill_path']) && !empty($_POST['skill_path_new'])){$ppP = $_POST['skill_path_lvl_new'] * $CF_OZ_PATH_PP;}
 else if(!empty($_POST['skill_path']) && empty($_POST['skill_path_new'])){$ppPR = $_POST['skill_path_lvl'] * $CF_OZ_PATH_PP;}
 else
  {
  if($_POST['skill_path_lvl_new'] == $_POST['skill_path_lvl']){$ppP = "0";}
  else if($_POST['skill_path_lvl_new'] > $_POST['skill_path_lvl']){$path = $_POST['skill_path_lvl_new'] - $_POST['skill_path_lvl']; $ppP = $path * $CF_OZ_PATH_PP;}
  else if($_POST['skill_path_lvl_new'] < $_POST['skill_path_lvl']){$path = $_POST['skill_path_lvl'] - $_POST['skill_path_lvl_new']; $ppPR = $path * $CF_OZ_PATH_PP;}
  }

 if(empty($_POST['skill1']) && empty($_POST['skill1_new'])){$pp1 = "0";}
 else if(empty($_POST['skill1']) && !empty($_POST['skill1_new'])){$one = $_POST['skill1_lvl_new'] * $CF_OZ_SKILL_PP; $pp1 = $one - 1;}
 else if(!empty($_POST['skill1']) && empty($_POST['skill1_new'])){$one = $_POST['skill1_lvl'] * $CF_OZ_SKILL_PP; $pp1R = $one - 1;}
 else
  {
  if($_POST['skill1_lvl_new'] == $_POST['skill1_lvl']){$pp1 = "0";}
  else if($_POST['skill1_lvl_new'] > $_POST['skill1_lvl']){$one = $_POST['skill1_lvl_new'] - $_POST['skill1_lvl']; $pp1 = $one * $CF_OZ_SKILL_PP;}
  else if($_POST['skill1_lvl_new'] < $_POST['skill1_lvl']){$one = $_POST['skill1_lvl'] - $_POST['skill1_lvl_new']; $pp1R = $one * $CF_OZ_SKILL_PP;}
  }

 if(empty($_POST['skill2']) && empty($_POST['skill2_new'])){$pp2 = "0";}
 else if(empty($_POST['skill2']) && !empty($_POST['skill2_new'])){$pp2 = $_POST['skill2_lvl_new'] * $CF_OZ_SKILL_PP;}
 else if(!empty($_POST['skill2']) && empty($_POST['skill2_new'])){$pp2R = $_POST['skill2_lvl'] * $CF_OZ_SKILL_PP;}
 else
  {
  if($_POST['skill2_lvl_new'] == $_POST['skill2_lvl']){$pp2 = "0";}
  else if($_POST['skill2_lvl_new'] > $_POST['skill2_lvl']){$two = $_POST['skill2_lvl_new'] - $_POST['skill2_lvl']; $pp2 = $two * $CF_OZ_SKILL_PP;}
  else if($_POST['skill2_lvl_new'] < $_POST['skill2_lvl']){$two = $_POST['skill2_lvl'] - $_POST['skill2_lvl_new']; $pp2R = $two * $CF_OZ_SKILL_PP;}
  }

 if(empty($_POST['skill3']) && empty($_POST['skill3_new'])){$pp3 = "0";}
 else if(empty($_POST['skill3']) && !empty($_POST['skill3_new'])){$pp3 = $_POST['skill3_lvl_new'] * $CF_OZ_SKILL_PP;}
 else if(!empty($_POST['skill3']) && empty($_POST['skill3_new'])){$pp3R = $_POST['skill3_lvl'] * $CF_OZ_SKILL_PP;}
 else
  {
  if($_POST['skill3_lvl_new'] == $_POST['skill3_lvl']){$pp3 = "0";}
  else if($_POST['skill3_lvl_new'] > $_POST['skill3_lvl']){$three = $_POST['skill3_lvl_new'] - $_POST['skill3_lvl']; $pp3 = $three * $CF_OZ_SKILL_PP;}
  else if($_POST['skill3_lvl_new'] < $_POST['skill3_lvl']){$three = $_POST['skill3_lvl'] - $_POST['skill3_lvl_new']; $pp3R = $three * $CF_OZ_SKILL_PP;}
  }

 if(empty($_POST['skill4']) && empty($_POST['skill4_new'])){$pp4 = "0";}
 else if(empty($_POST['skill4']) && !empty($_POST['skill4_new'])){$pp4 = $_POST['skill4_lvl_new'] * $CF_OZ_SKILL_PP;}
 else if(!empty($_POST['skill4']) && empty($_POST['skill4_new'])){$pp4R = $_POST['skill4_lvl'] * $CF_OZ_SKILL_PP;}
 else
  {
  if($_POST['skill4_lvl_new'] == $_POST['skill4_lvl']){$pp4 = "0";}
  else if($_POST['skill4_lvl_new'] > $_POST['skill4_lvl']){$four = $_POST['skill4_lvl_new'] - $_POST['skill4_lvl']; $pp4 = $four * $CF_OZ_SKILL_PP;}
  else if($_POST['skill4_lvl_new'] < $_POST['skill4_lvl']){$four = $_POST['skill4_lvl'] - $_POST['skill4_lvl_new']; $pp4R = $four * $CF_OZ_SKILL_PP;}
  }

 $pp_char = $_POST['ppearned_spent'];
 $pp_skill = "0";
 $pp_total ="0";
 $record = "N";
 if(!empty($_POST['pp_manage']) && $_POST['pp_manage'] == 'Y')
  {
  $pp_refund = $ppPR + $pp1R + $pp2R + $pp3R + $pp4R;
  $pp_detail = "Skills (Logistics): ".$_POST['char_name']; 
  $pp_new = $_POST['player_points'] + $pp_refund;
  $pp_char = $_POST['ppearned_spent'] - $pp_refund;
  if($pp_refund >= 1){$record = "Y";}
  }
 else
  {
  $pp_skill = $pp1 + $pp2 + $pp3 + $pp4;
  $pp_total = $pp_skill + $ppP;
  if($pp_total > $_POST['player_points']){$error = "You do not have enough Player Points for the selected purchase."; $errors = "1";}
  else{$pp_detail = "Skills: ".$_POST['char_name']; $pp_new = $_POST['player_points'] - $pp_total; $pp_spent = $pp_total; $pp_char = $_POST['ppearned_spent'] + $pp_total; $record = "Y";}
  }

 if(empty($pp_char) || $pp_char <= '0'){$pp_char = "0";}

 if($errors != '1')
  {
  $update_char = mysql_query("UPDATE `characters` SET `ppearned_spent`='$pp_char', `skill_path`='$skillp', `skill_path_lvl`='$skillp_lvl', `skill1`='$skill1', `skill2`='$skill2', `skill3`='$skill3', `skill4`='$skill4', `skill1_lvl`='$skill1_lvl', `skill2_lvl`='$skill2_lvl', `skill3_lvl`='$skill3_lvl', `skill4_lvl`='$skill4_lvl', `magic_points`='$magic', `edit_date`='$date_format', `edit_author`='$loggedin[username]' WHERE `char_id`='$_POST[char_id]' LIMIT 1");
  if(!$update_char){$error = "An internal database error occured and your character's Skills could not be updated. Please contact the Web Manager about this error.";}
  else
   {
   if($record == 'Y')
    {
    $insert_pp = mysql_query("INSERT INTO `characters_pplog` (`char_id`, `pp_skill`, `pp_skillpath`, `pp_refund`, `ppearned_spent`, `pp_detail`, `user_id`, `pp_author`, `sort_date`, `pp_date`) VALUES ('$_POST[char_id]', '$pp_skill', '$ppP', '$pp_refund', '$pp_spent', '$pp_detail', '$_POST[user_id]', '$loggedin[username]', '$date', '$date_format')");
    $update_user = mysql_query("UPDATE `bb_users` SET `player_points`='$pp_new' WHERE `user_id`='$_POST[user_id]' LIMIT 1");
    }
   $id = $_POST['char_id'];
   if(!empty($_POST['mode'])){$mode = "&mode=".$modeid;}
   header("Location: character-view.php?char_id=".$id."&m=2".$mode);
   exit(); 
   }
  }
 }

// Character - Spells
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'buyspells')
 {
 $errors = "0";

 $path = $_POST['path'];
 if(!empty($_POST['path_new']) && $_POST['path_new'] != $path)
  {
  $path = $_POST['path_new'];
  $_POST['spell1'] = trim(''); $_POST['spell2'] = trim(''); $_POST['spell3'] = trim(''); $_POST['spell4'] = trim(''); $_POST['spell5'] = trim(''); $_POST['spell6'] = trim(''); $_POST['spell7'] = trim(''); $_POST['spell8'] = trim(''); $_POST['spell9'] =trim(''); $_POST['spell10'] = trim(''); $_POST['spell11'] = trim(''); $_POST['spell12'] = trim(''); $_POST['spell13'] = trim(''); $_POST['spell14'] = trim(''); $_POST['spell15'] = trim(''); $_POST['spell16'] = trim(''); $_POST['spell17'] = trim(''); $_POST['spell18'] = trim(''); $_POST['spell19'] = trim(''); $_POST['spell20'] = trim(''); $_POST['spell21'] = trim(''); $_POST['spell22'] = trim(''); $_POST['spell23'] = trim(''); $_POST['spell24'] = trim(''); $_POST['spell25'] = trim(''); $_POST['spell26'] = trim(''); $_POST['spell27'] = trim('');
  }

// Spell count calculator
if(!empty($_POST['spell1']) || !empty($_POST['spell1_cur'])){$count_cur1 = "1";}
if(!empty($_POST['spell2']) || !empty($_POST['spell2_cur'])){$count_cur2 = "1";}
if(!empty($_POST['spell3']) || !empty($_POST['spell3_cur'])){$count_cur3 = "1";}
if(!empty($_POST['spell4']) || !empty($_POST['spell4_cur'])){$count_cur4 = "1";}
if(!empty($_POST['spell5']) || !empty($_POST['spell5_cur'])){$count_cur5 = "1";}
if(!empty($_POST['spell6']) || !empty($_POST['spell6_cur'])){$count_cur6 = "1";}
if(!empty($_POST['spell7']) || !empty($_POST['spell7_cur'])){$count_cur7 = "1";}
if(!empty($_POST['spell8']) || !empty($_POST['spell8_cur'])){$count_cur8 = "1";}
if(!empty($_POST['spell9']) || !empty($_POST['spell9_cur'])){$count_cur9 = "1";}
if(!empty($_POST['spell10']) || !empty($_POST['spell10_cur'])){$count_cur10 = "1";}
if(!empty($_POST['spell11']) || !empty($_POST['spell11_cur'])){$count_cur11 = "1";}
if(!empty($_POST['spell12']) || !empty($_POST['spell12_cur'])){$count_cur12 = "1";}
if(!empty($_POST['spell13']) || !empty($_POST['spell13_cur'])){$count_cur13 = "1";}
if(!empty($_POST['spell14']) || !empty($_POST['spell14_cur'])){$count_cur14 = "1";}
if(!empty($_POST['spell15']) || !empty($_POST['spell15_cur'])){$count_cur15 = "1";}
if(!empty($_POST['spell16']) || !empty($_POST['spell16_cur'])){$count_cur16 = "1";}
if(!empty($_POST['spell17']) || !empty($_POST['spell17_cur'])){$count_cur17 = "1";}
if(!empty($_POST['spell18']) || !empty($_POST['spell18_cur'])){$count_cur18 = "1";}
if(!empty($_POST['spell19']) || !empty($_POST['spell19_cur'])){$count_cur19 = "1";}
if(!empty($_POST['spell20']) || !empty($_POST['spell20_cur'])){$count_cur20 = "1";}
if(!empty($_POST['spell21']) || !empty($_POST['spell21_cur'])){$count_cur21 = "1";}
if(!empty($_POST['spell22']) || !empty($_POST['spell22_cur'])){$count_cur22 = "1";}
if(!empty($_POST['spell23']) || !empty($_POST['spell23_cur'])){$count_cur23 = "1";}
if(!empty($_POST['spell24']) || !empty($_POST['spell24_cur'])){$count_cur24 = "1";}
if(!empty($_POST['spell25']) || !empty($_POST['spell25_cur'])){$count_cur25 = "1";}
if(!empty($_POST['spell26']) || !empty($_POST['spell26_cur'])){$count_cur26 = "1";}
if(!empty($_POST['spell27']) || !empty($_POST['spell27_cur'])){$count_cur27 = "1";}

// PP cost calculator
$cost1 = "0"; $ref1= "0";
if(empty($_POST['spell1']) && !empty($_POST['spell1_cur'])){$ref1 = "1"; $spell1 = $_POST['spell1'];}
elseif(!empty($_POST['spell1']) && empty($_POST['spell1_cur'])){$cost1 = "1"; $spell1 = $_POST['spell1'];}
else{$spell1 = $_POST['spell1_cur'];}

$cost2 = "0"; $ref2= "0";
if(empty($_POST['spell2']) && !empty($_POST['spell2_cur'])){$ref2 = "1"; $spell2 = $_POST['spell2'];}
elseif(!empty($_POST['spell2']) && empty($_POST['spell2_cur'])){$cost2 = "1"; $spell2 = $_POST['spell2'];}
else{$spell2 = $_POST['spell2_cur'];}

$cost3 = "0"; $ref3= "0";
if(empty($_POST['spell3']) && !empty($_POST['spell3_cur'])){$ref3 = "1"; $spell3 = $_POST['spell3'];}
elseif(!empty($_POST['spell3']) && empty($_POST['spell3_cur'])){$cost3 = "1"; $spell3 = $_POST['spell3'];}
else{$spell3 = $_POST['spell3_cur'];}

$cost4 = "0"; $ref4= "0";
if(empty($_POST['spell4']) && !empty($_POST['spell4_cur'])){$ref4 = "1"; $spell4 = $_POST['spell4'];}
elseif(!empty($_POST['spell4']) && empty($_POST['spell4_cur'])){$cost4 ="1"; $spell4 = $_POST['spell4'];}
else{$spell4 = $_POST['spell4_cur'];}

$cost5 = "0"; $ref5= "0";
if(empty($_POST['spell5']) && !empty($_POST['spell5_cur'])){$ref5 = "1"; $spell5 = $_POST['spell5'];}
elseif(!empty($_POST['spell5']) && empty($_POST['spell5_cur'])){$cost5 = "1"; $spell5 = $_POST['spell5'];}
else{$spell5 = $_POST['spell5_cur'];}

$cost6 = "0"; $ref6= "0";
if(empty($_POST['spell6']) && !empty($_POST['spell6_cur'])){$ref6 = "1"; $spell6 = $_POST['spell6'];}
elseif(!empty($_POST['spell6']) && empty($_POST['spell6_cur'])){$cost6 = "1"; $spell6 = $_POST['spell6'];}
else{$spell6 = $_POST['spell6_cur'];}

$cost7 = "0"; $ref7= "0";
if(empty($_POST['spell7']) && !empty($_POST['spell7_cur'])){$ref7 = "1"; $spell7 = $_POST['spell7'];}
elseif(!empty($_POST['spell7']) && empty($_POST['spell7_cur'])){$cost7 = "1"; $spell7 = $_POST['spell7'];}
else{$spell7 = $_POST['spell7_cur'];}

$cost8 = "0"; $ref8= "0";
if(empty($_POST['spell8']) && !empty($_POST['spell8_cur'])){$ref8 = "1"; $spell8 = $_POST['spell8'];}
elseif(!empty($_POST['spell8']) && empty($_POST['spell8_cur'])){$cost8 = "1"; $spell8 = $_POST['spell8'];}
else{$spell8 = $_POST['spell8_cur'];}

$cost9 = "0"; $ref9= "0";
if(empty($_POST['spell9']) && !empty($_POST['spell9_cur'])){$ref9 = "1"; $spell9 = $_POST['spell9'];}
elseif(!empty($_POST['spell9']) && empty($_POST['spell9_cur'])){$cost9 = "1"; $spell9 = $_POST['spell9'];}
else{$spell9 = $_POST['spell9_cur'];}

$cost10 = "0"; $ref10= "0";
if(empty($_POST['spell10']) && !empty($_POST['spell10_cur'])){$ref10 = "1"; $spell10 = $_POST['spell10'];}
elseif(!empty($_POST['spell10']) && empty($_POST['spell10_cur'])){$cost10 = "1"; $spell10 = $_POST['spell10'];}
else{$spell10 = $_POST['spell10_cur'];}

$cost11 = "0"; $ref11= "0";
if(empty($_POST['spell11']) && !empty($_POST['spell11_cur'])){$ref11 = "1"; $spell11 = $_POST['spell11'];}
elseif(!empty($_POST['spell11']) && empty($_POST['spell11_cur'])){$cost11 = "1"; $spell11 = $_POST['spell11'];}
else{$spell11 = $_POST['spell11_cur'];}

$cost12 = "0"; $ref12= "0";
if(empty($_POST['spell12']) && !empty($_POST['spell12_cur'])){$ref12 = "1"; $spell12 = $_POST['spell12'];}
elseif(!empty($_POST['spell12']) && empty($_POST['spell12_cur'])){$cost12 = "1"; $spell12 = $_POST['spell12'];}
else{$spell12 = $_POST['spell12_cur'];}

$cost13 = "0"; $ref13= "0";
if(empty($_POST['spell13']) && !empty($_POST['spell13_cur'])){$ref13 = "1"; $spell13 = $_POST['spell13'];}
elseif(!empty($_POST['spell13']) && empty($_POST['spell13_cur'])){$cost13 = "1"; $spell13 = $_POST['spell13'];}
else{$spell13 = $_POST['spell13_cur'];}

$cost14 = "0"; $ref14= "0";
if(empty($_POST['spell14']) && !empty($_POST['spell14_cur'])){$ref14 = "1"; $spell14 = $_POST['spell14'];}
elseif(!empty($_POST['spell14']) && empty($_POST['spell14_cur'])){$cost14 = "1"; $spell14 = $_POST['spell14'];}
else{$spell14 = $_POST['spell14_cur'];}

$cost15 = "0"; $ref15= "0";
if(empty($_POST['spell15']) && !empty($_POST['spell15_cur'])){$ref15 = "1"; $spell15 = $_POST['spell15'];}
elseif(!empty($_POST['spell15']) && empty($_POST['spell15_cur'])){$cost15 = "1"; $spell15 = $_POST['spell15'];}
else{$spell15 = $_POST['spell15_cur'];}

$cost16 = "0"; $ref16= "0";
if(empty($_POST['spell16']) && !empty($_POST['spell16_cur'])){$ref16 = "1"; $spell16 = $_POST['spell16'];}
elseif(!empty($_POST['spell16']) && empty($_POST['spell16_cur'])){$cost16 = "1"; $spell16 = $_POST['spell16'];}
else{$spell16 = $_POST['spell16_cur'];}

$cost17 = "0"; $ref17= "0";
if(empty($_POST['spell17']) && !empty($_POST['spell17_cur'])){$ref17 = "1"; $spell17 = $_POST['spell17'];}
elseif(!empty($_POST['spell17']) && empty($_POST['spell17_cur'])){$cost17 = "1"; $spell17 = $_POST['spell17'];}
else{$spell17 = $_POST['spell17_cur'];}

$cost18 = "0"; $ref18= "0";
if(empty($_POST['spell18']) && !empty($_POST['spell18_cur'])){$ref18 = "1"; $spell18 = $_POST['spell18'];}
elseif(!empty($_POST['spell18']) && empty($_POST['spell18_cur'])){$cost18 =  "1"; $spell18 = $_POST['spell18'];}
else{$spell18 = $_POST['spell18_cur'];}

$cost19 = "0"; $ref19= "0";
if(empty($_POST['spell19']) && !empty($_POST['spell19_cur'])){$ref19 = "1"; $spell19 = $_POST['spell19'];}
elseif(!empty($_POST['spell19']) && empty($_POST['spell19_cur'])){$cost19 = "1"; $spell19 = $_POST['spell19'];}
else{$spell19 = $_POST['spell19_cur'];}

$cost20 = "0"; $ref20= "0";
if(empty($_POST['spell20']) && !empty($_POST['spell20_cur'])){$ref20 = "1"; $spell20 = $_POST['spell20'];}
elseif(!empty($_POST['spell20']) && empty($_POST['spell20_cur'])){$cost20 = "1"; $spell20 = $_POST['spell20'];}
else{$spell20 = $_POST['spell20_cur'];}

$cost21 = "0"; $ref21= "0";
if(empty($_POST['spell21']) && !empty($_POST['spell21_cur'])){$ref21 = "1"; $spell21 = $_POST['spell21'];}
elseif(!empty($_POST['spell21']) && empty($_POST['spell21_cur'])){$cost21 =  "1"; $spell21 = $_POST['spell21'];}
else{$spell21 = $_POST['spell21_cur'];}

$cost22 = "0"; $ref22= "0";
if(empty($_POST['spell22']) && !empty($_POST['spell22_cur'])){$ref22 = "1"; $spell22 = $_POST['spell22'];}
elseif(!empty($_POST['spell22']) && empty($_POST['spell22_cur'])){$cost22 = "1"; $spell22 = $_POST['spell22'];}
else{$spell22 = $_POST['spell22_cur'];}

$cost23 = "0"; $ref23= "0";
if(empty($_POST['spell23']) && !empty($_POST['spell23_cur'])){$ref23 = "1"; $spell23 = $_POST['spell23'];}
elseif(!empty($_POST['spell23']) && empty($_POST['spell23_cur'])){$cost23 = "1"; $spell23 = $_POST['spell23'];}
else{$spell23 = $_POST['spell23_cur'];}

$cost24 = "0"; $ref24= "0";
if(empty($_POST['spell24']) && !empty($_POST['spell24_cur'])){$ref24 = "1"; $spell24 = $_POST['spell24'];}
elseif(!empty($_POST['spell24']) && empty($_POST['spell24_cur'])){$cost24 = "1"; $spell24 = $_POST['spell24'];}
else{$spell24 = $_POST['spell24_cur'];}

$cost25 = "0"; $ref25= "0";
if(empty($_POST['spell25']) && !empty($_POST['spell25_cur'])){$ref25 = "1"; $spell25 = $_POST['spell25'];}
elseif(!empty($_POST['spell25']) && empty($_POST['spell25_cur'])){$cost25 = "1"; $spell25 = $_POST['spell25'];}
else{$spell25 = $_POST['spell25_cur'];}

$cost26 = "0"; $ref26= "0";
if(empty($_POST['spell26']) && !empty($_POST['spell26_cur'])){$ref26 = "1"; $spell26 = $_POST['spell26'];}
elseif(!empty($_POST['spell26']) && empty($_POST['spell26_cur'])){$cost26 = "1"; $spell26 = $_POST['spell26'];}
else{$spell26 = $_POST['spell26_cur'];}

$cost27 = "0"; $ref27= "0";
if(empty($_POST['spell27']) && !empty($_POST['spell27_cur'])){$ref27 = "1"; $spell27 = $_POST['spell27'];}
elseif(!empty($_POST['spell27']) && empty($_POST['spell27_cur'])){$cost27 = "1"; $spell27 = $_POST['spell27'];}
else{$spell27 = $_POST['spell27_cur'];}

$count_com_1 = $count_cur1 + $count_cur2 + $count_cur3;
$count_com_2 = $count_cur4 + $count_cur5 + $count_cur6;
$count_com_3 = $count_cur7 + $count_cur8 + $count_cur9;
$count_other_1 = $count_cur10 + $count_cur11 + $count_cur12 + $count_cur13 + $count_cur14 + $count_cur15; 
$count_other_2 = $count_cur16 + $count_cur17 + $count_cur18 + $count_cur19 + $count_cur20 + $count_cur21; 
$count_other_3 = $count_cur22 + $count_cur23 + $count_cur24 + $count_cur25 + $count_cur26 + $count_cur27; 

   if($count_com_2 > $count_com_1){$error = "The number of Level 2 Common spells exceeds the number of Level 1 Common spells."; $errors = "1";}
   if($count_com_3 > $count_com_2){$error = "The number of Level 3 Common spells exceeds the number of Level 2 Common spells."; $errors = "1";}
   if($count_other_2 > $count_other_1){$error = "The number of Level 2 $path spells exceeds the number of Level 1 $path spells."; $errors = "1";}
   if($count_other_3 > $count_other_2){$error = "The number of Level 3 $path spells exceeds the number of Level 2 $path spells."; $errors = "1";}

  $record = "Y";
  $pp_spent = "0";
  $pp_refund = "0";
  $pp_char = $_POST['ppearned_spent'];
  if(empty($_POST['pp_manage']) || $_POST['pp_manage'] == 'N')
   {
   $level1 = $cost1 + $cost2 + $cost3 + $cost10 + $cost11 + $cost12 + $cost13 + $cost14 + $cost15; 
   if(empty($_POST['spell1_cur']) && empty($_POST['spell2_cur']) && empty($_POST['spell3_cur']) && empty($_POST['spell10_cur']) && empty($_POST['spell11_cur']) && empty($_POST['spell12_cur']) && empty($_POST['spell13_cur']) && empty($_POST['spell14_cur']) && empty($_POST['spell15_cur'])){$level1 = $level1 - 1;}
   $spell_total = $level1 + $cost4 + $cost5 + $cost6 + $cost7 + $cost8 + $cost9 + $cost16 + $cost17 + $cost18 + $cost19 + $cost20 + $cost21 + $cost22 + $cost23 + $cost24 + $cost25 + $cost26 + $cost27;
   if($spell_total <= 0){$spell_total = "0"; $record = "N";}
   $pp_spent = $spell_total * $CF_OZ_SPELL_PP;
   if($pp_spent > $_POST['player_points']){$error = "The cost of your selected Spells, $pp_spent points, exceeds your available Player Points of $_POST[player_points]."; $errors = "1";}else{$pp_new = $_POST['player_points'] - $pp_spent; $pp_char = $_POST['ppearned_spent'] + $pp_spent;}
   $pp_detail = "Spells: ".$_POST['char_name'];
   }
  else // groups manage Yes
   {
   $level1 = $ref1 + $ref2 + $ref3 + $ref10 + $ref11 + $ref12 + $ref13 + $ref14 + $ref15; 
   if(empty($_POST['spell1']) && empty($_POST['spell2']) && empty($_POST['spell3']) && empty($_POST['spell10']) && empty($_POST['spell11']) && empty($_POST['spell12']) && empty($_POST['spell13']) && empty($_POST['spell14']) && empty($_POST['spell15'])){$level1 = $level1 - 1;}
   $spell_total = $level1 + $ref4 + $ref5 + $ref6 + $ref7 + $ref8 + $ref9 + $ref16 + $ref17 + $ref18 + $ref19 + $ref20 + $ref21 + $ref22 + $ref23 + $ref24 + $ref25 + $ref26 + $ref27;
   if($spell_total <= 0){$spell_total = "0"; $record = "N";}
   $pp_refund = $spell_total * $CF_OZ_SPELL_PP;
   $pp_new = $_POST['player_points'] + $pp_refund; 
   $pp_char = $_POST['ppearned_spent'] - $pp_refund;;
   $pp_detail = "Spells (Logistics): ".$_POST['char_name'];
   }
 if(empty($pp_char) || $pp_char <= 0){$pp_char = "0";}
 if($errors != '1')
  {
  $update_char = mysql_query("UPDATE `characters` SET `ppearned_spent`='$pp_char', `path`='$path', `spell1`='$spell1', `spell2`='$spell2', `spell3`='$spell3', `spell4`='$spell4', `spell5`='$spell5', `spell6`='$spell6', `spell7`='$spell7', `spell8`='$spell8', `spell9`='$spell9', `spell10`='$spell10', `spell11`='$spell11', `spell12`='$spell12', `spell13`='$spell13', `spell14`='$spell14', `spell15`='$spell15', `spell16`='$spell16', `spell17`='$spell17', `spell18`='$spell18', `spell19`='$spell19', `spell20`='$spell20', `spell21`='$spell21', `spell22`='$spell22', `spell23`='$spell23', `spell24`='$spell24', `spell25`='$spell25', `spell26`='$spell26', `spell27`='$spell27', `edit_date`='$date_format', `edit_author`='$loggedin[username]' WHERE `char_id`='$_POST[char_id]' LIMIT 1");
  if(!$update_char){$error = "An internal database error occured and your character's Spells could not be updated. Please contact the Web Manager about this error.";}
  else
   {
   if($record == 'Y')
    {
    $insert_pp = mysql_query("INSERT INTO `characters_pplog` (`char_id`, `pp_spell`, `pp_refund`, `ppearned_spent`, `pp_detail`, `user_id`, `pp_author`, `sort_date`, `pp_date`) VALUES ('$_POST[char_id]', '$spell_total', '$pp_refund', '$pp_spent', '$pp_detail', '$_POST[user_id]', '$loggedin[username]', '$date', '$date_format')");
    $update_user = mysql_query("UPDATE `bb_users` SET `player_points`='$pp_new' WHERE `user_id`='$_POST[user_id]' LIMIT 1");
    }
   $id = $_POST['char_id'];
   if(!empty($_POST['mode'])){$mode = "&mode=".$modeid;}
   header("Location: character-view.php?char_id=".$id."&m=3".$mode);
   exit(); 
   }
  }
 }

// Character - Items
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'buyitems')
 {

 }
?>