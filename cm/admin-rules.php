<?php
include ('character-header.php'); 

if(!empty($_REQUEST['page'])){$page = $_REQUEST['page'];}else{$page = "1";}
if(!empty($_REQUEST['edit_type'])){$edit_type = $_REQUEST['edit_type'];}else{$edit_type = "no";}
if($edit_type == 'no'){$access = "Please go back and select a command.";}

$groups = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$loggedin[user_id]' AND `group_id`='20' OR `group_id`='9'"));

if(!$groups){$access = "You do not have permission to view this page.";}

if(!empty($error)){print "<br><div id='error'> $error</div><br>";}
if(!empty($_GET['m'])){print "<br><div id='success'>The rulebook has been successfully uploaded</div><br>";}

if(!empty($access)){print "<br><br><div id='error'> $access</div><br><br>";}

 print "<div id='page-footer' style='font-size: 11px; font-type: Arial; color: #000000;'><div class='navbar'><div class='inner'>";
 print "<ul class='linklist rightside'><li class='icon-ucp' style='height:30px;'><a href='character.php' title='Back to main page'><img border='0' src='/images/icon-back.png' /></a></li></ul><br><br>";

if(empty($access))
 {
if($edit_type == '1')
  {
  print "<h2 style='margin-top: 5px;'>Rules - Skills</h2>";
  print "<form action='' method='post'>";
  print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";

  print "<tr><td></td><td>
<input type='hidden' id='edit_type' vname='edit_type' value='$edit_type'>
<input type='hidden' id='S_ID' name='S_ID' value='rules_skills'>
<input type='submit' id='submit' name='submit' value='Submit'></td></tr></table></form><br><br>";
  }
 else if($edit_type == '2')
  {
  print "<h2 style='margin-top: 5px;'>Rules - Spells</h2>";

  $spells = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_spells`"));

  print "<form action='' method='post'>";
  print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
  print "<tr><td width='200' align='right'><strong>Spell 1</strong></td><td width='600'><input type='text' name='spell1' value='"; if(!empty($_POST['spell1'])){echo $_POST['spell1'];}else{echo $spells['spell_name'];} print "'></td></td></tr>";
  print "<tr><td></td><td>
<input type='hidden' id='edit_type' vname='edit_type' value='$edit_type'>
<input type='hidden' id='S_ID' name='S_ID' value='rules_spells'>
<input type='submit' id='submit' name='submit' value='Submit'></td></tr></table></form><br><br>";
  }
 else if($edit_type == '3')
  {
  print "<h2 style='margin-top: 5px;'>Rules - Configuration</h2>";
  print "<form action='' method='post'>";
  print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
  print "<tr><td width='200' align='right'><strong>Maximum # Characters</strong></td><td width='600'><input type='text' name='CF_OZ_CHARMAX' id='CF_OZ_CHARMAX' value='"; if(!empty($_POST['CF_OZ_CHARMAX'])){echo $_POST['CF_OZ_CHARMAX'];}else{echo $CF_OZ_CHARMAX;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Background Award (PP)</strong></td><td><input type='text' name='CF_OZ_BG_PP' id='CF_OZ_BG_PP' value='"; if(!empty($_POST['CF_OZ_BG_PP'])){echo $_POST['CF_OZ_BG_PP'];}else{echo $CF_OZ_BG_PP;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Skill Cost (PP)</strong></td><td><input type='text' name='CF_OZ_SKILL_PP' id='CF_OZ_SKILL_PP' value='"; if(!empty($_POST['CF_OZ_SKILL_PP'])){echo $_POST['CF_OZ_SKILL_PP'];}else{echo $CF_OZ_SKILL_PP;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Skill Path Cost (PP)</strong></td><td><input type='text' name='CF_OZ_PATH_PP' id='CF_OZ_PATH_PP' value='"; if(!empty($_POST['CF_OZ_PATH_PP'])){echo $_POST['CF_OZ_PATH_PP'];}else{echo $CF_OZ_PATH_PP;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Spell Cost (PP)</strong></td><td><input type='text' name='CF_OZ_SPELL_PP' id='CF_OZ_SPELL_PP' value='"; if(!empty($_POST['CF_OZ_SPELL_PP'])){echo $_POST['CF_OZ_SPELL_PP'];}else{echo $CF_OZ_SPELL_PP;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Immortal Magic Point Bonus</strong></td><td><input type='text' name='CF_OZ_MAGIC_BONUS' id='CF_OZ_MAGIC_BONUS' value='"; if(!empty($_POST['CF_OZ_MAGIC_BONUS'])){echo $_POST['CF_OZ_MAGIC_BONUS'];}else{echo $CF_OZ_MAGIC_BONUS;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Witch lvl 1 Magic Points</strong></td><td><input type='text' name='CF_OZ_MAGIC_1' id='CF_OZ_MAGIC_1' value='"; if(!empty($_POST['CF_OZ_MAGIC_1'])){echo $_POST['CF_OZ_MAGIC_1'];}else{echo $CF_OZ_MAGIC_1;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Witch lvl 2 Magic Points</strong></td><td><input type='text' name='CF_OZ_MAGIC_2' id='CF_OZ_MAGIC_2' value='"; if(!empty($_POST['CF_OZ_MAGIC_2'])){echo $_POST['CF_OZ_MAGIC_2'];}else{echo $CF_OZ_MAGIC_2;} print "'></td></td></tr>";
  print "<tr><td align='right'><strong>Witch lvl 3 Magic Points</strong></td><td><input type='text' name='CF_OZ_MAGIC_3' id='CF_OZ_MAGIC_3' value='"; if(!empty($_POST['CF_OZ_MAGIC_3'])){echo $_POST['CF_OZ_MAGIC_3'];}else{echo $CF_OZ_MAGIC_3;} print "'></td></td></tr>";
  print "<tr><td></td><td><strong>NOTE</strong> - changes made will not instantly update characters, but characters will be updated as players and staff edit them.<br><br>
<input type='hidden' id='edit_type' vname='edit_type' value='$edit_type'>
<input type='hidden' id='S_ID' name='S_ID' value='rules_misc'>
<input type='submit' id='submit' name='submit' value='Submit'></td></tr></table></form><br><br>";
  }
 else if($edit_type == '4')
  {
  print "<h2 style='margin-top: 5px;'>Rules - Update Rulebook </h2>";
 $dirname = "documents/oz/";
 $dir = opendir($dirname);
 while(false != ($file = readdir($dir)))
  {
  if(($file != ".") and ($file != "..") and ($file != "index.php"))
   {
   if($file == 'oz-rulebook.pdf'){$color = $dirname."oz-rulebook.pdf"; $colordate = date("m-d-Y", filemtime($color));}
   if($file == 'oz-rulebook-bw.pdf'){$bw = $dirname."oz-rulebook-bw.pdf"; $bwdate = date("m-d-Y", filemtime($bw));}
   }
  }
 closedir($dir);

  print "PDF files only<br><br><form action='' method='post' enctype='multipart/form-data'>";
  print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
  print "<tr><td width='100' valign='top' align='right'><strong>Color</strong></td><td width='700'>"; if(!empty($color)){print "<a target='_blank' href='/$color'>Current color online (link) as of $colordate</a><br>";} print "Update: <input type='file' name='color'><br><br></td></tr>";
  print "<tr><td valign='top' align='right'><strong>Black & White</strong></td><td>"; if(!empty($bw)){print "<a target='_blank' href='/$bw'>Current black and white online (link) as of $bwdate</a><br>";} print "Update: <input type='file' name='bw'></td></tr>";
  print "<tr><td></td><td>
<input type='hidden' id='edit_type' vname='edit_type' value='$edit_type'>
<input type='hidden' id='S_ID' name='S_ID' value='rulebook'>
<input type='submit' id='submit' name='submit' value='Submit'></td></tr></table></form><br><br>";
  }
 }
 print "</div></div></div>";
include ('character-footer.php'); 
?>