<?php
include ('character-header.php'); 

if(!empty($_REQUEST['char_id'])){$charid = $_REQUEST['char_id'];}else{$charid = "no";}
if(empty($_GET['mode'])){$modeid = $loggedin[user_id];}else{$modeid = $_GET['mode'];}

$groups = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$loggedin[user_id]' OR `group_id`='11' OR `group_id`='20'"));
$char = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters` WHERE `char_id`='$charid' LIMIT 1"));
$pp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points FROM `bb_users` WHERE `user_id`='$char[user_id]' LIMIT 1"));
$player = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$modeid' LIMIT 1"));

if(!$char){$access = "The character could not be found in the database. Please go back and select another.";}
if($loggedin['user_id'] != $char['user_id'] && !$groups){$access = "You do not have permission to view this character.";}

if(!empty($access)){print "<br><br><div id='error'> $access</div><br><br>";}
else
 {
 if(!empty($error)){print "<br><div id='error'> $error</div><br>";}
 if($loggedin['user_id'] != $modeid){print "<br><div id='mode'>Viewing as $player[pf_real_name]</div><br>"; $mode = "?mode=".$modeid;}
 if(!empty($_GET['m']))
  {
  print "<br><div id='success'>";
  if($_GET['m'] == '1'){print "Character details have been modified.";}
  if($_GET['m'] == '2'){print "Character skills have been modified.";}
  if($_GET['m'] == '3'){print "Character spells have been modified.";}
  if($_GET['m'] == '4'){print "Item tags have been requested.";}
  if($_GET['m'] == '5'){print "Character public biography has been modified.";}
  if($_GET['m'] == '6'){print "Character background has been submitted.";}
  if($_GET['m'] == '7'){print "Your character details have been modified. However, the character could not be switched to Active as you already have $CF_OZ_CHARMAX active characters.";}
  print "</div><br>";
  }

 print "<div id='page-footer' style='font-size: 11px; font-type: Arial; color: #000000;'><div class='navbar'><div class='inner'>";
 print "<ul class='linklist rightside'><li class='icon-ucp' style='height:30px;'><a href='/documents/oz/oz-rulebook.pdf' target='_blank' title='Download Rule Book'><img border='0' src='/images/icon-dwn-rules.png' /></a></li><li class='icon-ucp' style='height:30px;'><a href='character-print.php?char_id=$char[char_id]' target='_blank' title='Print Character Sheet' onClick='showPopup(this.href);return(false);'><img border='0' src='/images/icon-print.png' /></a></li><li class='icon-ucp' style='height:30px;'><a href='character.php$mode' title='Back to main page'><img border='0' src='/images/icon-back.png' /></a></li></ul><br><br>";

 print "<h2 style='margin-top: 5px;'>Overview: <a href='character-view.php?char_id=$charid'>$char[char_name]</a></h2>";

 print "<table width='825' border='1' align='center' cellspacing='0' cellpadding='3' id='cm_table'>";

 print "<tr><td align='center' width='235' valign='top'><div id='cm_container'><div id='cm_title'><strong>Photo</strong></div><form style='display:inline;' method='post' action='' enctype='multipart/form-data'>";
 if(empty($char['who_photo'])){print "<img src='/images/oz/logo-oz.png'><input type='hidden' name='photo' value='/images/oz/logo-oz.png'>";}else{print "<img width='150' src='$char[who_photo]'>";}
 print "<br><input type='file' name='img'><br><br>";
 print "<input type='checkbox' name='no_image' value='Y'> Remove custom image<br><br>";
 print "<input type='hidden' name='char_name' value='$char[char_name]'><input type='hidden' name='char_id' value='$charid'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}  
 print "<input type='hidden' name='S_ID' value='updatephoto'><div id='cm_bottom'><input type='submit' name='submit' value='Update Photo'></div></form></div></td>";

 print "<td align='center' width='280' valign='top'><div id='cm_container'><div id='cm_title'><strong>Public Biography</strong><div style='display:inline; position: absolute; top: -20px;'><p id='tooltip1'><a href='#'><img border='0' src='/images/icon-info.png' /><span>A Public Bio is a brief description of your character's concept and goals. The Public Bio can be misleading or spot on, whatever you want the other players, and characters, to believe about your character. <br><br>Your Public Bio will appear on the Denizens of Oz page (archived characters are not included).</span></a></p></div></div><form style='display:inline;' method='post' action=''>"; 
 ?>

<textarea style='width: 280px; height: 100px;' name="limitedtextarea2" onKeyDown="limitText2(this.form.limitedtextarea2,this.form.countdown2,500);" 
onKeyUp="limitText2(this.form.limitedtextarea2,this.form.countdown2,500);">
<?php

if(isset($_POST['limitedtextarea2'])){echo $_POST['limitedtextarea2'];}else{echo $char['who_bio'];}?></textarea><div id='small'>500 character limit</div><br><br>

<?php
 print "<input type='radio' name='who_visible' value='N'"; if(empty($char['who_visible']) || $char['who_visible'] == 'N'){echo " checked";}  print "> Private <input type='radio' name='who_visible' value='Y'";if($char['who_visible'] == 'Y'){echo " checked";} print "> Public";

 print "<br><br><a href='/oz.php?page=denizen' target='_blank'>Denizens of Oz list (link)</a>";

// print "<br><br><strong>Change Character Name:</strong>";
// if(empty($char['char_name_changed']) || $char['char_name_changed'] == 'N')
//  {
//  print " <input type='text' name='new_name' value='"; if(empty($_POST['new_name'])){echo $char['char_name'];}else{echo $_POST//['new_name'];} print "'> 
//<input type='hidden' name='old_name' value='$char[char_name]'>
//<br><div id='small'>can be changed one time only</div>";
//  }
// else
//  {
//  print " changed <input type='hidden' name='old_name' value='$char[char_name]'> <input type='hidden' name='changed' value='$char//[char_name_changed]'>";
//  } 
 print "<input type='hidden' name='char_id' value='$charid'><input type='hidden' name='S_ID' value='updatebio'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}  
 print "<div id='cm_bottom'><input type='submit' name='submit' value='Update Bio'></div></form></div></td>";

 print "<td align='center' width='285' valign='top'><div id='cm_container'><div id='cm_title'><strong>Background</strong> <div style='display:inline; position: absolute; top: -20px;'><p id='tooltip1'><a href='#'><img border='0' src='/images/icon-info.png' /><span>A Background is a truthful description of your character's concept and general information. While only a Character Guide will read your Background, it will help you flesh out your Character.<br><br>Submit a Background and receive $CF_OZ_BG_PP Player Points (upon Character Guide approval). <br><br>Backgrounds for Archived characters will not be approved.</span></a></p></div></div>";

 if(!empty($char['bg_approved']))
  {
  if($char['bg_approved'] == 'Y'){$approved = "Yes";}
  else if($char['bg_approved'] == 'N'){$approved = "No";}
  else{$approved = "Submitted";}
  }

 if(empty($char['bg_approved']) || $char['bg_approved'] == 'N') 
  {
  print "<form style='display:inline;' method='post' action='' enctype='multipart/form-data'>";

  if(!empty($newfname)){print "<a target='_blank' href='$newfname'><img align='right' src='/images/document-uploaded.jpg'></a> <input type='hidden' name='pdf' value='$newfname'>";} ?>

<textarea style='width: 280px; height: 100px;' name="limitedtextarea">
<?php if(!empty($_POST['limitedtextarea'])){echo $_POST['limitedtextarea'];}else{echo $char['background'];}?></textarea><br>

<?php
  if($char['bg_approved'] == 'N' && !empty($char['background_file'])){print "<a target='_blank' href='$char[background_file]'>Current uploaded file (link)</a><br>";}

  if(!empty($errorpdf)){echo "<div id=orange><strong>Upload File: </strong></div>";} else {echo "<strong>Upload File: </strong>";}
  print "<input type='file' name='pdf'><br /><div id='small'>1mb size limit. pdf, doc, txt file types only.</div><br><br>";

  if($char['bg_approved'] == 'N'){print "<div id='orange'><strong>Denied: please contact the CG</strong></div>";}else{print "$CF_OZ_BG_PP player points awarded upon CG approval.";}

  print "<br><input type='hidden' name='char_id' value='$charid'><input type='hidden' name='char_name' value='$char[char_name]'><input type='hidden' name='player_name' value='$player[pf_real_name]'><input type='hidden' name='S_ID' value='submitbg'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}  
 print "<div id='cm_bottom'><input type='submit' name='submit' value='Submit Background'></div></form></div>";
  }
 else 
  {
  if(!empty($char['background_file']))
   {
   print "<a target='_blank' href='$char[background_file]' title='Submitted Background'><img src='/images/icon-text.png'><input type='hidden' name='pdf' value='$char[background_file]'></a>";
   print "<br><br>Approved? $approved";
   if($char['bg_approved'] == 'S'){print "<br><br>The background has been submitted and will be reviewed shortly. You will be notified upon approval or denial.";}
   }
  else 
   {
   print "<a target='_blank' href='character-bg.php?id=$charid' onClick='showPopup(this.href);return(false);'><img src='/images/icon-text.png'></a>";
   print "<br><br>Approved? $approved";
   if($char['bg_approved'] == 'S'){print "<br><br>The background has been submitted and will be reviewed shortly. You will be notified upon approval or denial.";}
   }
  }  

print "</td></tr>";

 print "<tr><td align='center' width='235' valign='top'><div id='cm_container_tall'><div id='cm_title'><strong>Details</strong></div>";
 print "<table width='230' border='0' cellspacing='3' cellpadding='3' id='cm_table2'>";
 print "<tr><td align='right'><strong>Player</strong></td><td>$player[pf_real_name]</td></tr>";
 print "<tr><td width='90' align='right'><strong>Created</strong></td><td width='130'>$char[create_date]</td></tr>";
 if(!empty($char['edit_date']))
  {
  print "<tr><td align='right'><strong>Edited</strong></td><td>$char[edit_date]</td></tr>";
  }
 print "<tr><td align='right'><strong>Status</strong></td><td>"; if($char['char_status'] != '2'){echo "Active";}else{echo "Archived";} print "</td></tr>";
 print "<tr><td align='right'><strong>Species</strong></td><td>$char[race_name]</td></tr>";
 print "<tr><td align='right'><strong>Ability</strong></td><td>$char[ability]";
 if(!empty($char['shapeshift'])){print ": $char[shapeshift]";}
 print "</td></tr>";
 print "<tr><td align='right'><strong>Restriction</strong></td><td>$char[restriction]</td></tr>";
 if(!empty($char['race_blood']))
  {
  print "<tr><td align='right'><strong>Pure Blood</strong></td><td>$char[race_blood]</td></tr>"; 
  }
 if(!empty($char['magic_points']))
  {
  print "<tr><td align='right'><strong>Magic</strong></td><td>$char[magic_points] Points</td></tr>"; 
  } 
 if($char['race'] == '1'){$health = "20";}else{$health = "10";}
 print "<tr><td align='right'><strong>Health</strong></td><td>$health Points</td></tr>"; 

 print "<tr><td align='right' valign='top'><strong>Player Points<br>for Character</strong></td><td valign='top'>";
 print "Total Spent: $char[ppearned_spent]";
 print "</td></tr></table><form style='display:inline;' method='post' action='character-edit.php'><input type='hidden' name='char_id' value='$charid'><input type='hidden' name='edit_type' value='modifydetails'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}
 print "<div id='cm_bottom'><input type='submit' name='submit' value='Modify Details'></div></form></div></td>";

 print "<td align='center' valign='top'><div id='cm_container_tall'><div id='cm_title'><strong>Skills</strong></div><table width='280' cellspacing='3' cellpadding='3' id='cm_table2'>";
 if(!empty($char['skill_path']))
  {
  $skpath = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$char[skill_path]' LIMIT 1"));
  print "<tr><td width='80' align='right'><strong>Skill Path</strong></td><td width='200'>$skpath[skillpath_name] at level $char[skill_path_lvl]</td></tr>";
  }
 $get_skills = mysql_query("SELECT * FROM `characters_skills`");
 while($skills = mysql_fetch_assoc($get_skills))
  {
  if($char['skill1'] == $skills['skill_id']){$skill1 = $skills['skill_name'];}
  if($char['skill2'] == $skills['skill_id']){$skill2 = $skills['skill_name'];}
  if($char['skill3'] == $skills['skill_id']){$skill3 = $skills['skill_name'];}
  if($char['skill4'] == $skills['skill_id']){$skill4 = $skills['skill_name'];}
  }  
 if(!empty($char['skill1']))
  {
  print "<tr><td align='right'><strong>Skill 1</strong></td><td>$skill1 at level $char[skill1_lvl]</td></tr>";
  }

 if(!empty($char['skill2']))
  {
  print "<tr><td align='right'><strong>Skill 2</strong></td><td>$skill2 at level $char[skill2_lvl]</td></tr>";
  }

 if(!empty($char['skill3']))
  {
  print "<tr><td align='right'><strong>Skill 3</strong></td><td>$skill3 at level $char[skill3_lvl]</td></tr>";
  }

 if(!empty($char['skill4']))
  {
  print "<tr><td align='right'><strong>Skill 4</strong></td><td>$skill4 at level $char[skill4_lvl]</td></tr>";
  }
 print "</table><form style='display:inline;' method='post' action='character-edit.php'><input type='hidden' name='char_id' value='$charid'><input type='hidden' name='edit_type' value='buyskills'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}
 print "<div id='cm_bottom'><input type='submit' name='submit' value='Buy Skills'></div></form></div></td>";

 print "<td valign='top' align='center'><div id='cm_container_tall'><div id='cm_title'><strong>Spells</strong></div>";
 if($char['skill1'] == '12' || $char['skill2'] == '12' || $char['skill3'] == '12' || $char['skill4'] == '12')
  {
  print "<table width='280' border='1' cellpadding='0' cellspacing='0' id='cm_table2'><tr><td width='140'><strong>Common</strong></td><td width='140'><strong>$char[path]</strong></td></tr>";
  print "<tr><td valign='top'>"; 
  if(empty($char['spell1']) && empty($char['spell2']) && empty($char['spell3']) && empty($char['spell4']) && empty($char['spell5']) && empty($char['spell6']) && empty($char['spell7']) && empty($char['spell8']) && empty($char['spell9'])){print "none";}
  else
   {
   $array_comspells = array($char['spell1'], $char['spell2'], $char['spell3'], $char['spell4'], $char['spell5'], $_char['spell6'], $char['spell7'], $char['spell8'], $char['spell9']);
   $get_comspells = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' ORDER BY `spell_level` ASC");
   while($comspells = mysql_fetch_assoc($get_comspells))
    {
    foreach($array_comspells as $value_comspells)
     {
     if($comspells['spell_id'] == $value_comspells)
      {
      print "Lvl $comspells[spell_level]: $comspells[spell_name] <br><input type='hidden' name='spell$comspells[spell_var]' value='$comspells[spell_id]'>";
      }
     }
    }
   }
  print "</td><td valign='top'>";
  if(empty($char['spell10']) && empty($char['spell11']) && empty($char['spell12']) && empty($char['spell13']) && empty($char['spell14']) && empty($char['spell15']) && empty($char['spell16']) && empty($char['spell17']) && empty($char['spell18']) && empty($char['spell19']) && empty($char['spell20']) && empty($char['spell21']) && empty($char['spell22']) && empty($char['spell23']) && empty($char['spell24']) && empty($char['spell25']) && empty($char['spell26']) && empty($char['spell27'])){print "none";}
  else
   {
   if($char['path'] == 'Dark'){$path = "2";}else{$path = "3";}
   $array_pspells = array($char['spell10'], $char['spell11'], $char['spell12'], $char['spell13'], $char['spell14'], $char['spell15'], $char['spell16'], $char['spell17'], $char['spell18'], $char['spell19'], $char['spell20'], $char['spell21'], $char['spell22'], $char['spell23'], $char['spell24'], $char['spell25'], $char['spell26'], $char['spell27']);
   $get_pspells = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' ORDER BY `spell_level` ASC");
   while($pspells = mysql_fetch_assoc($get_pspells))
    {
    foreach($array_pspells as $value_pspells)
     {
     if($pspells['spell_id'] == $value_pspells)
      {
      print "Lvl $pspells[spell_level]: $pspells[spell_name] <br><input type='hidden' name='spell$pspells[spell_var]' value='$value_pspells'>";
      }
     }
    }
   }
  print "</table>";
  print "<form style='display:inline;' method='post' action='character-edit.php'><input type='hidden' name='char_id' value='$charid'><input type='hidden' name='edit_type' value='buyspells'>";
 if($loggedin['user_id'] != $modeid){print "<input type='hidden' name='mode' value='$modeid'>";}
 print "<div id='cm_bottom'><input type='submit' name='submit' value='Buy Spells'></div></form></div>";
  }
 else{ print "$char[char_name] does not have any Spells";}

 print "</td></tr></table><br></div></div></div><br>";
 }
include ('character-footer.php'); 
?>