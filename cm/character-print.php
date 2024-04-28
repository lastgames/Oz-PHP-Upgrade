<?php

require_once('scripts/database.php');

require_once('scripts/logincheck.php');

require_once('scripts/processes.php');

$get_cd = mysql_query("SELECT * FROM `config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

if(!empty($_REQUEST['char_id'])){$charid = $_REQUEST['char_id'];}else{$charid = "no";}

$groups = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$loggedin[user_id]' AND `group_id`='20'"));
$char = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters` WHERE `char_id`='$charid' LIMIT 1"));
$pp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points FROM `bb_users` WHERE `user_id`='$char[user_id]' LIMIT 1"));
$player = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$char[user_id]' LIMIT 1"));
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="<?php echo $CF_SITEKEYWORDS;?>">
<meta name="description" content="<?php echo $CF_SITEDESC;?>">

<link rel="icon" type="image/png" href="favicon.png">

<title>Character Sheet | <?php echo $CF_SITENAME;?></title></head>

<body><a name='top'></a>

<body>

<?php
if(!$char){$access = "The character could not be found in the database. Please go back and select another.";}
if($loggedin['user_id'] != $char['user_id'] && !$groups){$access = "You do not have permission to view this character.";}
if(!empty($access)){print "<div style='width: 835px; background: #f0c2b8; padding: 5px; border: solid 2px #bc4328; font-weight: bold; color: #000000;'> $access</div>";}
else
 {
 if(!empty($char['who_photo'])){$photo = $char['who_photo'];}else{$photo = "/images/oz/logo-oz.png";}
 if($char['char_status'] != '2'){$status = "Approved";}else{$status = "Archives";}
 if(empty($char['magic_points']) || $char['magic_points'] == '0'){$magic = "n/a";}else{$magic = $char['magic_points'];}
 if(empty($char['race_blood']) || $char['race_blood'] == 'N'){$blood = "No";}else{$blood = "Yes";}
 if($char['race'] == '1'){$health = "20";}else{$health = "10";}
 if(empty($char['shapeshift'])){$shift = "";}else{$shift = " - ".$char['shapeshift'];}

 print "<h3 style='display:inline;'>$char[char_name]</h3>";
 print "<table width='450' cellpadding='3' cellspacing='3' border='0' style='font-size: 14px;'><tr><td colspan='3'><hr></td></tr>";
 print "<tr><td width='150'><img width='150' src='$photo' /></td><td colspan='2' valign='top'>Status: $status<br>Species: $char[race_name]<br>Ability: $char[ability]$shift<br>Restriction: $char[restriction]<br>Pure Blood: $blood<br>Magic: $magic<br> Health: $health<br>Player: $player[pf_real_name]</td></tr>";
 print "<tr><td colspan='3'><hr></td></tr>";
 print "<tr><td valign='top'><strong>Skill Path</strong><br>";
 if(!empty($char['skill_path']))
  {
  $skpath = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$char[skill_path]' LIMIT 1"));
  print "$skpath[skillpath_name] x $char[skill_path_lvl]";
  }else{echo "none";}
 print "<br><br><strong>Skills</strong><br>";
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
  print "$skill1 x $char[skill1_lvl]<br>";
  }

 if(!empty($char['skill2']))
  {
  print "$skill2 x $char[skill2_lvl]<br>";
  }

 if(!empty($char['skill3']))
  {
  print "$skill3 x $char[skill3_lvl]<br>";
  }

 if(!empty($char['skill4']))
  {
  print "$skill4 x $char[skill4_lvl]";
  }
 print "</td><td width='150' valign='top'><strong>Common Spell Path</strong><br><br>";
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
      print "L$comspells[spell_level]: $comspells[spell_name] <br><input type='hidden' name='spell$comspells[spell_var]' value='$comspells[spell_id]'>";
      }
     }
    }
   }
 print "</td><td width='150' valign='top' style='font-size: 14px;'><strong>$char[path] Spell Path</strong><br><br>";
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
      print "L$pspells[spell_level]: $pspells[spell_name] <br><input type='hidden' name='spell$pspells[spell_var]' value='$value_pspells'>";
      }
     }
    }
   }
 print "</td></tr>";
 print "<tr><td colspan='3' align='center'><hr>World of Oz LARP - http://www.lastgamesnw.org<br><br><a href='javascript:window.print()'>print</a> | <a href='javascript: window.close ();'>close</a></td></tr>";
 print "</table>"; 
 }
?> 

</body>
</html>