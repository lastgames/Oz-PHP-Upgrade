<?php
include ('character-header.php'); 

// if($loggedin['user_id'] != $char['user_id'] || !$groups){$access = "You do not have permission to view this character.";}

 $player = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$loggedin[user_id]' LIMIT 1"));
 $pp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points FROM `bb_users` WHERE `user_id`='$loggedin[user_id]' LIMIT 1"));

  $race = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_races` WHERE `race_id`='$_POST[race]' LIMIT 1"));

 if(!empty($error)){print "<br><div id='error'> $error</div><br>";}

 if(!empty($access)){print "<br><br><div id='error'> $access</div><br><br>";}

 print "<div id='page-footer' style='font-size: 11px; font-type: Arial; color: #000000;'><div class='navbar'><div class='inner'>";

if(empty($access))
 {
 print "<h2 style='margin-top: 5px;'>Create a New Character</h2>";
 print "Using Character Manager is really easy! Just fill out the form on each step and submit when finished. You'll still need to visit Logistics at the event to pay the event fee and collect any items for your character.<br><br>";

 if(empty($_REQUEST['step']) || $_REQUEST['step'] == '1')
  {
  print "<strong>Step 1: Basics</strong><br /><br />";
  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player Points</strong></td><td width='600'>$pp[player_points]</td></tr>";
  print "<tr><td align='right'><strong>Player</strong></td><td>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td><input type='text' name='char_name' value='"; if(!empty($_POST['char_name'])){echo $_POST['char_name'];} print "'></td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>";
  print "<select name='race'>";
  $get_races = mysql_query("SELECT * FROM `characters_races` ORDER BY `race_name` ASC");
  while($races = mysql_fetch_assoc($get_races))
   {
   print "<option value='$races[race_id]'"; if($char['race'] == $races['race_id'] || $_POST['race'] == $races['race_id']){print "selected";} print ">$races[race_name]</option>";
   }
  print "</select>";
  print "<tr><td align='right'>&nbsp;</td><td><input type='hidden' name='S_ID' value='step1'>
<input type='submit' name='submit' value='Next' id='next'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 else if(empty($_REQUEST['step']) || $_REQUEST['step'] == '2')
  {
  print "<strong>Step 2: Skills</strong><br /><br />";

  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
   if(empty($pp['player_points'])){$pp_cur = "0";}else{$pp_cur = $pp['player_points'];}
   $pp_total = $pp_new + $pp_cur;

   print "<div id='pp-box'><strong>Player Points</strong><br><br>Starting Points: $pp_total<br><br>Your Earned PP: $pp_cur<br><br>Build Bonus: $pp_new<br><br><strong>Available PP: $pp_total</strong></div>";

  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player</strong></td><td width='600'>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td>$_POST[char_name]</td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>$race[race_name]</td></tr>";

   print "<tr><td align='right'><strong>Ability</strong></td><td>$race[race_abil]"; 
   if($race['race_abil'] == 'Shapeshift')
    {
    print " - select <select name='shapeshift'>";
    print "<option value='Pelt'"; if(!empty($_POST['shapeshift']) && $_POST['shapeshift'] == 'Pelt'){echo "selected";} print ">Pelt</option>";
    print "<option value='Weapon'"; if(empty($_POST['shapeshift']) || $_POST['shapeshift'] == 'Weapon'){echo "selected";} print ">Weapon</option>";
    print "</select> ";
    }
   print " <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span><strong>$race[race_abil]</strong><br>$race[race_abil_desc]</span></a></p></td></tr><tr><td align='right'><strong>Restriction</strong></td><td>$race[race_rest] <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>$race[race_rest_desc]</span></a></p> </td></tr>"; 

   print "<td align='right'><strong>Pure Blood</strong></td><td>";
   print "<input type='radio' name='race_blood' value='N'"; if(empty($_POST['race_blood']) || $_POST['race_blood'] == 'N'){print " checked";} print "> No <input type='radio' name='race_blood' value='Y'"; if(!empty($_POST['race_blood']) && $_POST['race_blood'] == 'Y'){print " checked";} print "> Yes for $CF_OZ_PURE_PP pp <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span><strong>Pure Blood ability</strong><br>$race[race_blood]</span></a></p></td></tr>"; 

   print "<tr><td align='right'>"; if(isset($errorskill1)){echo "<div id=orange><strong>Skill One</strong></div>";} else {echo "<strong>Skill One</strong>";}
   print "</td><td>";

   $lvl = "0"; 
   print "<select name='skill1'>";
   $get_skill1 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   while($skill1 = mysql_fetch_assoc($get_skill1))
    { 
    print "<option value='$skill1[skill_id]'"; if($_POST['skill1'] == $skill1['skill_id']){echo "selected";} print ">$skill1[skill_name]</option>";
    }
   print "</select> ";

   print "Level <select name='skill1_lvl'><option value='1'";
  if(!empty($_POST['skill1_lvl']) && $_POST['skill1_lvl'] == '1' || $lvl == '1'){echo "selected";} 
    print ">1</option><option value='2'"; if(!empty($_POST['skill1_lvl']) && $_POST['skill1_lvl'] == '2'){echo "selected";}
    print ">2</option><option value='3'"; if(!empty($_POST['skill1_lvl']) && $_POST['skill1_lvl'] == '3'){echo "selected";}
    print ">3</option></select> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>The first Level of the first Skill is free. Each Level additional Level costs $CF_OZ_SKILL_PP pp per Level</span></a></p></td></tr>";

   print "<tr><td align='right'>"; if(isset($errorskill2)){echo "<div id=orange><strong>Skill Two</strong></div>";} else {echo "<strong>Skill Two</strong>";}
   print "</td><td>";

   print "<select name='skill2'><option value=''></option>";
   $get_skill2 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   while($skill2 = mysql_fetch_assoc($get_skill2))
    { 
    print "<option value='$skill2[skill_id]'"; if($_POST['skill2'] == $skill2['skill_id']){echo "selected";} print ">$skill2[skill_name]</option>";
    }
   print "</select> ";

   print "Level <select name='skill2_lvl'><option value=''"; if(empty($_POST['skill2_lvl'])){echo "selected";} print "></option><option value='1'";
   if(!empty($_POST['skill2_lvl']) && $_POST['skill2_lvl'] == '1'){echo "selected";} 
   print ">1</option><option value='2'"; if(!empty($_POST['skill2_lvl']) && $_POST['skill2_lvl'] == '2'){echo "selected";}
   print ">2</option><option value='3'"; if(!empty($_POST['skill2_lvl']) && $_POST['skill2_lvl'] == '3'){echo "selected";}
   print ">3</option></select> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>To buy a second Skill, spend $CF_OZ_SKILL_PP pp per Level.</span></a></p></td></tr>";

   print "<tr><td align='right'>"; if(isset($errorskill3)){echo "<div id=orange><strong>Skill Three</strong></div>";} else {echo "<strong>Skill Three</strong>";}
   print "</td><td>";

   print "<select name='skill3'><option value=''></option>";
   $get_skill3 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   while($skill3 = mysql_fetch_assoc($get_skill3))
    { 
    print "<option value='$skill3[skill_id]'"; if($_POST['skill3'] == $skill3['skill_id']){echo "selected";} print ">$skill3[skill_name]</option>";
    }
   print "</select> ";

   print "Level <select name='skill3_lvl'><option value=''"; if(empty($_POST['skill3_lvl'])){echo "selected";} print "></option><option value='1'";
   if(!empty($_POST['skill3_lvl']) && $_POST['skill3_lvl'] == '1'){echo "selected";} 
   print ">1</option><option value='2'"; if(!empty($_POST['skill3_lvl']) && $_POST['skill3_lvl'] == '2'){echo "selected";}
   print ">2</option><option value='3'"; if(!empty($_POST['skill3_lvl']) && $_POST['skill3_lvl'] == '3'){echo "selected";}
   print ">3</option></select> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>To buy a third Skill, spend $CF_OZ_SKILL_PP pp per Level.</span></a></p></td></tr>";

   if($_POST['race'] == '5')
    {
    print "<tr><td align='right'>"; if(isset($errorskill4)){echo "<div id=orange><strong>Skill Four</strong></div>";} else {echo "<strong>Skill Four</strong>";}
    print "</td><td>";

    print "<select name='skill4'><option value=''></option>";
    $get_skill4 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
    while($skill4 = mysql_fetch_assoc($get_skill4))
     { 
     print "<option value='$skill4[skill_id]'"; if($_POST['skill4'] == $skill4['skill_id']){echo "selected";} print ">$skill4[skill_name]</option>";
     }
    print "</select> ";

    print "Level <select name='skill4_lvl'><option value=''"; if(empty($_POST['skill4_lvl'])){echo "selected";} print "></option><option value='1'";
    if(!empty($_POST['skill4_lvl']) && $_POST['skill4_lvl'] == '1'){echo "selected";} 
    print ">1</option><option value='2'"; if(!empty($_POST['skill4_lvl']) && $_POST['skill4_lvl'] == '2'){echo "selected";}
    print ">2</option><option value='3'"; if(!empty($_POST['skill4_lvl']) && $_POST['skill4_lvl'] == '3'){echo "selected";}
    print ">3</option> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>To buy a fourth Skill, spend $CF_OZ_SKILL_PP pp per Level.</span></a></p></select></td></tr>";
    }

  print "<tr><td align='right'>&nbsp;</td><td>
<input type='hidden' name='char_name' value='$_POST[char_name]'>
<input type='hidden' name='race' value='$_POST[race]'>
<input type='hidden' name='pp' value='$pp_total'>
<input type='hidden' name='S_ID' value='step2'>
<input type='submit' name='submit' value='Back' id='back'> &nbsp;&nbsp;
<input type='submit' name='submit' value='Next' id='next'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 else if(empty($_REQUEST['step']) || $_REQUEST['step'] == '3')
  {
  print "<strong>Step 3: Paths</strong><br /><br />";

  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
   if(empty($pp['player_points'])){$pp_cur = "0";}else{$pp_cur = $pp['player_points'];}
   $pp_avail = $pp_new + $pp_cur;

   if(!empty($_POST['skill_total'])){$skill_total = $_POST['skill_total'];}else{$skill_total = $skill_total;}
   if(empty($skill_total)){$skill_total = "0";}

   $pp_total = $pp_avail - $skill_total; 

   print "<div id='pp-box'><strong>Player Points</strong><br><br>Starting Points: $pp_avail<br><br>Spent on Skills: $skill_total<br><br><strong>Available: $pp_total</strong></div>";

  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player</strong></td><td width='600'>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td>$_POST[char_name]</td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>$race[race_name]</td></tr>";

   print "<tr><td align='right'><strong>Ability</strong></td><td>$race[race_abil]"; 
   if($race['race_abil'] == 'Shapeshift'){echo ": ".$_POST['shapeshift'];}
   print "</td></tr><tr><td align='right'><strong>Restriction</strong></td><td>$race[race_rest]</td></tr>"; 

   print "<tr><td align='right'><strong>Pure Blood</strong></td><td>$_POST[race_blood]</td></tr>";

   $skill1_lvl = $_POST['skill1_lvl'];
   $skill1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill1]' LIMIT 1"));
   print "<tr><td align='right'><strong>Skill One</strong></strong></td><td>$skill1[skill_name], Level $_POST[skill1_lvl]</td></tr>";

   if(!empty($_POST['skill2']))
    {
    $skill2_lvl = $_POST['skill2_lvl'];
    $skill2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill2]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Two</strong></strong></td><td>$skill2[skill_name], Level $_POST[skill2_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill3']))
    {
    $skill3_lvl = $_POST['skill3_lvl'];
    $skill3 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill3]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Three</strong></strong></td><td>$skill3[skill_name], Level $_POST[skill3_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill4']))
    {
    $skill4_lvl = $_POST['skill4_lvl'];
    $skill4 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill4]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Four</strong></strong></td><td>$skill4[skill_name], Level $_POST[skill4_lvl]</td></tr>";
    } 

  $level_total = $skill1_lvl + $skill2_lvl + $skill3_lvl + $skill4_lvl;

  print "<tr><td align='right'><strong>Witch Path</strong></td><td><input type='hidden' name='sk_path' value='on'>";
  if($_POST['skill1'] != '12' && $_POST['skill2'] != '12' && $_POST['skill3'] != '12' && $_POST['skill4'] != '12')
   {
   print "not applicable";
   if($level_total >= 6){$sk_path = "on";}else{$message = "Two level 3 Skills, or one level 3 Skill and Shapeshift: Weapon are required."; $sk_path = "off";}
   }
  else
   {
   print "<select name='path' style='width: 100px;'>";
   print "<option value='Dark'"; if($_POST['path'] == 'Dark'){echo "selected";} print ">Dark</option>
<option value='Light'"; if($_POST['path'] == 'Light'){echo "selected";} print ">Light</option></select> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>Selecting a Path determines the Spells this character can buy and the available Skill Paths. Witches can switch from Light to Dark only via the appropriate Art.</span></a></p>";
   if(empty($_POST['path']))
    {
    if($level_total >= 6){print "<input type='hidden' name='post' value='1'>"; $message = "A Witch Path must be selected first, then click Next."; $sk_path = "off";}else{$message = "Two level 3 Skills, or one level 3 Skill and Shapeshift: Weapon are required."; $sk_path = "off";}
    }
  else
    {
    print "<input type='hidden' name='path_cur' value='$_POST[path]'>";
    if($level_total >= 6){$sk_path = "on";}else{$sk_path = "off"; $message = "Two level 3 Skills, or one level 3 Skill and Shapeshift: Weapon are required.";}
    }
   }
  print "</td></tr>"; 

  print "<tr><td align='right'><strong>Skill Path</strong></td><td>";
  if($sk_path == 'off'){echo $message;}
  else
   {
   if(!empty($_POST['shapeshift']) && $_POST['shapeshift'] == 'Weapon'){$claw = "C";}else{$claw = "NO";}
   if(empty($_POST['path'])){$witch = "NO";}else{if($_POST['path'] == 'Dark'){$witch = "12D";}else{$witch = "12L";}}
   $sk1 = $_POST['skill1'];
   $sk2 = $_POST['skill2'];
   $sk3 = $_POST['skill3'];
   $sk4 = $_POST['skill4'];

   $check = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_req1`='$sk1' OR `skillpath_req1`='$sk2' OR `skillpath_req1`='$sk3' OR `skillpath_req1`='$sk4' OR `skillpath_req1`='$witch' OR `skillpath_req1`='$claw' ORDER BY `skillpath_name` ASC"));
   if($check['skillpath_req2'] == $witch || $check['skillpath_req2'] == $claw || $check['skillpath_req2'] == $sk1 || $check['skillpath_req2'] == $sk2 || $check['skillpath_req2'] == $sk3 || $check['skillpath_req2'] == $sk4 || $check['skillpath_req3'] == $witch || $check['skillpath_req3'] == $claw || $check['skillpath_req3'] == $sk1 || $check['skillpath_req3'] == $sk2 || $check['skillpath_req3'] == $sk3 || $check['skillpath_req3'] == $sk4 || $check['skillpath_req4'] == $witch || $check['skillpath_req4'] == $claw || $check['skillpath_req4'] == $sk1 || $check['skillpath_req4'] == $sk2 || $check['skillpath_req4'] == $sk3 || $check['skillpath_req4'] == $sk4)
    {
    print "<select name='skill_path'><option value=''></option>";
    $get_skillpaths = mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_req1`='$sk1' OR `skillpath_req1`='$sk2' OR `skillpath_req1`='$sk3' OR `skillpath_req1`='$sk4' OR `skillpath_req1`='$witch' OR `skillpath_req1`='$skill' ORDER BY `skillpath_name` ASC");
    while($paths = mysql_fetch_assoc($get_skillpaths))
     {
     if($paths['skillpath_req2'] == $witch || $paths['skillpath_req2'] == $claw || $paths['skillpath_req2'] == $sk1 || $paths['skillpath_req2'] == $sk2 || $paths['skillpath_req2'] == $sk3 || $paths['skillpath_req2'] == $sk4 || $paths['skillpath_req3'] == $witch || $paths['skillpath_req3'] == $claw || $paths['skillpath_req3'] == $sk1 || $paths['skillpath_req3'] == $sk2 || $paths['skillpath_req3'] == $sk3 || $paths['skillpath_req3'] == $sk4 || $paths['skillpath_req4'] == $witch || $paths['skillpath_req4'] == $claw || $paths['skillpath_req4'] == $sk1 || $paths['skillpath_req4'] == $sk2 || $paths['skillpath_req4'] == $sk3 || $paths['skillpath_req4'] == $sk4)
      {
      print "<option value='$paths[skillpath_id]'"; if(!empty($_POST['skill_path']) && $_POST['skill_path'] == $paths['skillpath_id']){print " selected";} print ">$paths[skillpath_name]</option>";
      }
     }
    print "</select> x <select name='skill_path_lvl'>
<option value='1'"; if(!empty($_POST['skill_path_lvl']) && $_POST['skill_path_lvl'] == '1'){print " selected";} print">1</option>
<option value='2'"; if(!empty($_POST['skill_path_lvl']) && $_POST['skill_path_lvl'] == '2'){print " selected";} print">2</option>
<option value='3'"; if(!empty($_POST['skill_path_lvl']) && $_POST['skill_path_lvl'] == '3'){print " selected";} print">3</option></select> 2pp per level";

   print " <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>Skill Paths represent a field of expertise or special abilities a character has gained. Skill Paths will always have 2 prerequisite skills. No character may possess more than one skill path. A character may purchase a skill path, when they have the PP available. Skill Path descriptions are found in the rule book. <strong>If selecting a Skill Path that provides alternate item costs, those cost will NOT be reflected below.</strong></span></a></p>";
    } 
   else
    {
    print "There are no Paths for your Skill set.";
    }
   }
  print "</td></tr>"; 

  print "<tr><td align='right'>&nbsp;</td><td>
<input type='hidden' name='char_name' value='$_POST[char_name]'>
<input type='hidden' name='race' value='$_POST[race]'>
<input type='hidden' name='race_blood' value='$_POST[race_blood]'>
<input type='hidden' name='ability' value='$_POST[ability]'>
<input type='hidden' name='shapeshift' value='$_POST[shapeshift]'>
<input type='hidden' name='restriction' value='$_POST[restriction]'>
<input type='hidden' name='pp' value='$pp_total'>
<input type='hidden' name='skill_total' value='$skill_total'>
<input type='hidden' name='skill1' value='$_POST[skill1]'>
<input type='hidden' name='skill2' value='$_POST[skill2]'>
<input type='hidden' name='skill3' value='$_POST[skill3]'>
<input type='hidden' name='skill4' value='$_POST[skill4]'>
<input type='hidden' name='skill1_lvl' value='$_POST[skill1_lvl]'>
<input type='hidden' name='skill2_lvl' value='$_POST[skill2_lvl]'>
<input type='hidden' name='skill3_lvl' value='$_POST[skill3_lvl]'>
<input type='hidden' name='skill4_lvl' value='$_POST[skill4_lvl]'>
<input type='hidden' name='S_ID' value='step3'>
<input type='submit' name='submit' value='Back' id='back'> &nbsp;&nbsp;
<input type='submit' name='submit' value='Next' id='next'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 else if(empty($_REQUEST['step']) || $_REQUEST['step'] == '4')
  {
  print "<strong>Step 4: Spells</strong><br /><br />";

  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
   if(empty($pp['player_points'])){$pp_cur = "0";}else{$pp_cur = $pp['player_points'];}
   $pp_avail = $pp_new + $pp_cur;

   if(!empty($_POST['skill_total'])){$skill_total = $_POST['skill_total'];}else{$skill_total = $skill_total;}
   if(empty($skill_total)){$skill_total = "0";}

   if(!empty($_POST['path_total'])){$path_total = $_POST['path_total'];}else{$path_total = $path_total;}
   if(empty($path_total)){$path_total = "0";}

   $spent = $skill_total + $path_total;
   $pp_total = $pp_avail - $spent; 

   print "<div id='pp-box'><strong>Player Points</strong><br><br>Starting Points: $pp_avail<br><br>Spent on Skills: $skill_total<br><br>Spent on Skill Path: $path_total<br><br><strong>Available: $pp_total</strong></div>";

  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player</strong></td><td width='600'>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td>$_POST[char_name]</td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>$race[race_name]</td></tr>";

   print "<tr><td align='right'><strong>Ability</strong></td><td>$race[race_abil]"; 
   if($race['race_abil'] == 'Shapeshift'){echo ": ".$_POST['shapeshift'];}
   print "</td></tr><tr><td align='right'><strong>Restriction</strong></td><td>$race[race_rest]</td></tr>"; 

   print "<tr><td align='right'><strong>Pure Blood</strong></td><td>$_POST[race_blood]</td></tr>";

   $skill1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill1]' LIMIT 1"));
   print "<tr><td align='right'><strong>Skill One</strong></strong></td><td>$skill1[skill_name], Level $_POST[skill1_lvl]</td></tr>";

   if(!empty($_POST['skill2']))
    {
    $skill2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill2]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Two</strong></strong></td><td>$skill2[skill_name], Level $_POST[skill2_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill3']))
    {
    $skill3 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill3]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Three</strong></strong></td><td>$skill3[skill_name], Level $_POST[skill3_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill4']))
    {
    $skill4 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill4]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Four</strong></strong></td><td>$skill4[skill_name], Level $_POST[skill4_lvl]</td></tr>";
    } 

   print "<tr><td align='right'><strong>Skill Path</strong></td><td>"; 
   if(empty($_POST['skill_path'])){print "N";}
   else
    {
    $skillp = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$_POST[skill_path]' LIMIT 1"));
    print "$skillp[skillpath_name], Level $_POST[skill_path_lvl]";
    }
   print "</td></tr>";

  print "<tr><td align='right' valign='top'><strong>Spells</strong></strong></td><td>";

  $magic = "0";
  if($_POST['race'] == '3'){$magic = $CF_OZ_MAGIC_BONUS;}
  if($_POST['skill1'] == '12' || $_POST['skill2'] == '12' || $_POST['skill3'] == '12' || $_POST['skill4'] == '12')
   {
   if($_POST['skill1'] == '12'){$lvl1 = $_POST['skill1_lvl'];}
   else if($_POST['skill2'] == '12'){$lvl1 = $_POST['skill2_lvl'];}
   else if($_POST['skill3'] == '12'){$lvl1 = $_POST['skill3_lvl'];}
   else if($_POST['skill4'] == '12'){$lvl1 = $_POST['skill4_lvl'];}

   if($lvl1 == '1'){$skillmagic = $CF_OZ_MAGIC_1;}elseif($lvl1 == '2'){$skillmagic = $CF_OZ_MAGIC_2;}else{$skillmagic = $CF_OZ_MAGIC_3;}
   $magic = $magic + $skillmagic;

   if($_POST['path'] == 'Dark'){$path = "2";}else{$path = "3";}

   print "Select one Level 1 Spell for free. Additional Spells cost $CF_OZ_SPELL_PP pp each.<br><input type='hidden' name='spell_check' value='Y'>";
   print "<table width='400' align='center' border='0' cellspacing='3' cellpadding='3'>";
   print "<tr><td width='200' align='center'><strong>Common Spells</strong></td><td width='200' align='center'><strong>$_POST[path] Spells</strong></td></tr>";
   print "<tr><td valign='top'>Level One<br><br>";
   $get_spa = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='1' ORDER BY `spell_name` ASC");
   while($spa = mysql_fetch_assoc($get_spa))
    {
    print "<input type='checkbox' name='spell$spa[spell_var]' value='$spa[spell_id]'"; if($spa['spell_id'] == $_POST['spell1'] || $spa['spell_id'] == $_POST['spell2'] || $spa['spell_id'] == $_POST['spell3']){echo "checked";} print "> $spa[spell_name]<br>";
    }
   if($lvl1 == '2' || $lvl1 == '3')
    {
    print "<br>Level Two<br><br>";
    $get_spa2 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='2' ORDER BY `spell_name` ASC");
    while($spa2 = mysql_fetch_assoc($get_spa2))
     {
     print "<input type='checkbox' name='spell$spa2[spell_var]' value='$spa2[spell_id]'"; if($spa2['spell_id'] == $_POST['spell4'] || $spa2['spell_id'] == $_POST['spell5'] || $spa2['spell_id'] == $_POST['spell6']){echo "checked";} print "> $spa2[spell_name]<br>";
     }
    }
   if($lvl1 == '3')
    {
    print "<br>Level Three<br><br>";
    $get_spa3 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='3' ORDER BY `spell_name` ASC");
    while($spa3 = mysql_fetch_assoc($get_spa3))
     {
     print "<input type='checkbox' name='spell$spa3[spell_var]' value='$spa3[spell_id]'"; if($spa3['spell_id'] == $_POST['spell7'] || $spa3['spell_id'] == $_POST['spell8'] || $spa3['spell_id'] == $_POST['spell9']){echo "checked";} print "> $spa3[spell_name]<br>";
     }
    }
   print "</td><td valign='top'>Level One<br><br>";
   $get_spa4 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='1' ORDER BY `spell_name` ASC");
   while($spa4 = mysql_fetch_assoc($get_spa4))
    {
    print "<input type='checkbox' name='spell$spa4[spell_var]' value='$spa4[spell_id]'"; if($spa4['spell_id'] == $_POST['spell10'] || $spa4['spell_id'] == $_POST['spell11'] || $spa4['spell_id'] == $_POST['spell12'] || $spa4['spell_id'] == $_POST['spell13'] || $spa4['spell_id'] == $_POST['spell14'] || $spa4['spell_id'] == $_POST['spell15']){echo "checked";} print "> $spa4[spell_name]<br>";
    }
   if($lvl1 == '2' || $lvl1 == '3')
    {
    print "<br>Level Two<br><br>";
    $get_spa5 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='2' ORDER BY `spell_name` ASC");
    while($spa5 = mysql_fetch_assoc($get_spa5))
     {
     print "<input type='checkbox' name='spell$spa5[spell_var]' value='$spa5[spell_id]'"; if($spa5['spell_id'] == $_POST['spell16'] || $spa5['spell_id'] == $_POST['spell17'] || $spa5['spell_id'] == $_POST['spell18'] || $spa5['spell_id'] == $_POST['spell19'] || $spa5['spell_id'] == $_POST['spell20'] || $spa5['spell_id'] == $_POST['spell21']){echo "checked";} print "> $spa5[spell_name]<br>";
     }
    }
   if($lvl1 == '3')
    {
    print "<br>Level Three<br><br>";
    $get_spa6 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='3' ORDER BY `spell_name` ASC");
    while($spa6 = mysql_fetch_assoc($get_spa6))
     {
     print "<input type='checkbox' name='spell$spa6[spell_var]' value='$spa6[spell_id]'"; if($spa6['spell_id'] == $_POST['spell22'] || $spa6['spell_id'] == $_POST['spell23'] || $spa6['spell_id'] == $_POST['spell24'] || $spa6['spell_id'] == $_POST['spell25'] || $spa6['spell_id'] == $_POST['spell26'] || $spa6['spell_id'] == $_POST['spell27']){echo "checked";} print "> $spa6[spell_name]<br>";
     }
    }
   print "</td></tr></table>";
   }
  else
   {
   print "The Witch Skill was not purchased. Click Next.";
   }
  print "</td></tr>";

  print "<tr><td align='right'>&nbsp;</td><td>
<input type='hidden' name='char_name' value='$_POST[char_name]'>
<input type='hidden' name='race' value='$_POST[race]'>
<input type='hidden' name='race_blood' value='$_POST[race_blood]'>
<input type='hidden' name='ability' value='$_POST[ability]'>
<input type='hidden' name='shapeshift' value='$_POST[shapeshift]'>
<input type='hidden' name='restriction' value='$_POST[restriction]'>
<input type='hidden' name='pp' value='$pp_total'>
<input type='hidden' name='skill_total' value='$skill_total'>
<input type='hidden' name='path_total' value='$path_total'>
<input type='hidden' name='skill1' value='$_POST[skill1]'>
<input type='hidden' name='skill2' value='$_POST[skill2]'>
<input type='hidden' name='skill3' value='$_POST[skill3]'>
<input type='hidden' name='skill4' value='$_POST[skill4]'>
<input type='hidden' name='skill1_lvl' value='$_POST[skill1_lvl]'>
<input type='hidden' name='skill2_lvl' value='$_POST[skill2_lvl]'>
<input type='hidden' name='skill3_lvl' value='$_POST[skill3_lvl]'>
<input type='hidden' name='skill4_lvl' value='$_POST[skill4_lvl]'>
<input type='hidden' name='skill_path' value='$_POST[skill_path]'>
<input type='hidden' name='skill_path_lvl' value='$_POST[skill_path_lvl]'>
<input type='hidden' name='path' value='$_POST[path]'>
<input type='hidden' name='magic' value='$magic'>
<input type='hidden' name='S_ID' value='step4'>
<input type='submit' name='submit' value='Back' id='back'> &nbsp;&nbsp;
<input type='submit' name='submit' value='Next' id='next'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 else if(empty($_REQUEST['step']) || $_REQUEST['step'] == '5')
  {
  print "<strong>Step 5: Items</strong><br /><br />";

  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
   if(empty($pp['player_points'])){$pp_cur = "0";}else{$pp_cur = $pp['player_points'];}
   $pp_avail = $pp_new + $pp_cur;

   if(!empty($_POST['skill_total'])){$skill_total = $_POST['skill_total'];}else{$skill_total = $skill_total;}
   if(empty($skill_total)){$skill_total = "0";}

   if(!empty($_POST['path_total'])){$path_total = $_POST['path_total'];}else{$path_total = $path_total;}
   if(empty($path_total)){$path_total = "0";}

   if(!empty($_POST['spell_total'])){$spell_total = $_POST['spell_total'];}else{$spell_total = $spell_total;}
   if(empty($spell_total)){$spell_total = "0";}

   $spent = $skill_total + $path_total + $spell_total;
   $pp_total = $pp_avail - $spent; 

   print "<div id='pp-box'><strong>Player Points</strong><br><br>Starting Points: $pp_avail<br><br>Spent on Skills: $skill_total<br><br>Spent on Skill Path: $path_total<br><br>Spent on Spells: $spell_total<br><br><strong>Available: $pp_total</strong></div>";

  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player</strong></td><td width='600'>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td>$_POST[char_name]</td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>$race[race_name]</td></tr>";

   print "<tr><td align='right'><strong>Ability</strong></td><td>$race[race_abil]"; 
   if($race['race_abil'] == 'Shapeshift'){echo ": ".$_POST['shapeshift'];}
   print "</td></tr><tr><td align='right'><strong>Restriction</strong></td><td>$race[race_rest]</td></tr>"; 

   print "<tr><td align='right'><strong>Pure Blood</strong></td><td>$_POST[race_blood]</td></tr>";

   $skill1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill1]' LIMIT 1"));
   print "<tr><td align='right'><strong>Skill One</strong></strong></td><td>$skill1[skill_name], Level $_POST[skill1_lvl]</td></tr>";

   if(!empty($_POST['skill2']))
    {
    $skill2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill2]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Two</strong></strong></td><td>$skill2[skill_name], Level $_POST[skill2_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill3']))
    {
    $skill3 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill3]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Three</strong></strong></td><td>$skill3[skill_name], Level $_POST[skill3_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill4']))
    {
    $skill4 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill4]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Four</strong></strong></td><td>$skill4[skill_name], Level $_POST[skill4_lvl]</td></tr>";
    } 

   print "<tr><td align='right'><strong>Skill Path</strong></td><td>"; 
   if(empty($_POST['skill_path'])){print "N";}
   else
    {
    $skillp = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$_POST[skill_path]' LIMIT 1"));
    print "$skillp[skillpath_name], Level $_POST[skill_path_lvl]";
    }
   print "</td></tr>";

   if(!empty($_POST['magic'])){print "<tr><td align='right'><strong>Magic Points</strong></td><td>$_POST[magic]</td></tr>";}

   if(!empty($_POST['path']))
    {
    if($_POST['path'] == 'Dark'){$path = "2";}else{$path = "3";}
    print "<tr><td align='right'><strong>Witch Path</strong></td><td>$_POST[path]</td></tr>";
    print "<tr><td align='right' valign='top'><strong>Spells</strong></td><td>";
    print "<table width='300' border='0' cellpadding='0' cellspacing='0'><tr><td width='150'><strong>Common</strong></td><td width='150'><strong>$_POST[path]</strong></td></tr>";
    print "<tr><td valign='top'>"; 
    if(empty($_POST['spell1']) && empty($_POST['spell2']) && empty($_POST['spell3']) && empty($_POST['spell4']) && empty($_POST['spell5']) && empty($_POST['spell6']) && empty($_POST['spell7']) && empty($_POST['spell8']) && empty($_POST['spell9'])){print "none";}
    else
     {
     $array_comspells = array($_POST['spell1'], $_POST['spell2'], $_POST['spell3'], $_POST['spell4'], $_POST['spell5'], $_POST['spell6'], $_POST['spell7'], $_POST['spell8'], $_POST['spell9']);
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
    if(empty($_POST['spell10']) && empty($_POST['spell11']) && empty($_POST['spell12']) && empty($_POST['spell13']) && empty($_POST['spell14']) && empty($_POST['spell15']) && empty($_POST['spell16']) && empty($_POST['spell17']) && empty($_POST['spell18']) && empty($_POST['spell119']) && empty($_POST['spell120']) && empty($_POST['spell121']) && empty($_POST['spell122']) && empty($_POST['spell123']) && empty($_POST['spell124']) && empty($_POST['spell125']) && empty($_POST['spell126']) && empty($_POST['spell127'])){print "none";}
    else
     {
     $array_pspells = array($_POST['spell10'], $_POST['spell11'], $_POST['spell12'], $_POST['spell13'], $_POST['spell14'], $_POST['spell15'], $_POST['spell16'], $_POST['spell17'], $_POST['spell18'], $_POST['spell19'], $_POST['spell20'], $_POST['spell21'], $_POST['spell22'], $_POST['spell23'], $_POST['spell24'], $_POST['spell25'], $_POST['spell26'], $_POST['spell27']);
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
    print "</td></tr></table></td></tr>";
    } 
   
  print "<tr><td valign='top' align='right'><strong>Buy Items</strong></td><td>A new item purchasing system is in the works! In the meantime, you can select your item tags in person at Logistics at the beginning of the next event. Click Next.</td></tr>"; 

  print "<tr><td align='right'>&nbsp;</td><td>
<input type='hidden' name='char_name' value='$_POST[char_name]'>
<input type='hidden' name='race' value='$_POST[race]'>
<input type='hidden' name='race_blood' value='$_POST[race_blood]'>
<input type='hidden' name='ability' value='$_POST[ability]'>
<input type='hidden' name='shapeshift' value='$_POST[shapeshift]'>
<input type='hidden' name='restriction' value='$_POST[restriction]'>
<input type='hidden' name='pp' value='$pp_total'>
<input type='hidden' name='skill_total' value='$skill_total'>
<input type='hidden' name='path_total' value='$path_total'>
<input type='hidden' name='spell_total' value='$spell_total'>
<input type='hidden' name='skill1' value='$_POST[skill1]'>
<input type='hidden' name='skill2' value='$_POST[skill2]'>
<input type='hidden' name='skill3' value='$_POST[skill3]'>
<input type='hidden' name='skill4' value='$_POST[skill4]'>
<input type='hidden' name='skill1_lvl' value='$_POST[skill1_lvl]'>
<input type='hidden' name='skill2_lvl' value='$_POST[skill2_lvl]'>
<input type='hidden' name='skill3_lvl' value='$_POST[skill3_lvl]'>
<input type='hidden' name='skill4_lvl' value='$_POST[skill4_lvl]'>
<input type='hidden' name='skill_path' value='$_POST[skill_path]'>
<input type='hidden' name='skill_path_lvl' value='$_POST[skill_path_lvl]'>
<input type='hidden' name='path' value='$_POST[path]'>
<input type='hidden' name='magic' value='$_POST[magic]'>
<input type='hidden' name='spell1' value='$_POST[spell1]'>
<input type='hidden' name='spell2' value='$_POST[spell2]'>
<input type='hidden' name='spell3' value='$_POST[spell3]'>
<input type='hidden' name='spell4' value='$_POST[spell4]'>
<input type='hidden' name='spell5' value='$_POST[spell5]'>
<input type='hidden' name='spell6' value='$_POST[spell6]'>
<input type='hidden' name='spell7' value='$_POST[spell7]'>
<input type='hidden' name='spell8' value='$_POST[spell8]'>
<input type='hidden' name='spell9' value='$_POST[spell9]'>
<input type='hidden' name='spell10' value='$_POST[spell10]'>
<input type='hidden' name='spell11' value='$_POST[spell11]'>
<input type='hidden' name='spell12' value='$_POST[spell12]'>
<input type='hidden' name='spell13' value='$_POST[spell13]'>
<input type='hidden' name='spell14' value='$_POST[spell14]'>
<input type='hidden' name='spell15' value='$_POST[spell15]'>
<input type='hidden' name='spell16' value='$_POST[spell16]'>
<input type='hidden' name='spell17' value='$_POST[spell17]'>
<input type='hidden' name='spell18' value='$_POST[spell18]'>
<input type='hidden' name='spell19' value='$_POST[spell19]'>
<input type='hidden' name='spell20' value='$_POST[spell20]'>
<input type='hidden' name='spell21' value='$_POST[spell21]'>
<input type='hidden' name='spell22' value='$_POST[spell22]'>
<input type='hidden' name='spell23' value='$_POST[spell23]'>
<input type='hidden' name='spell24' value='$_POST[spell24]'>
<input type='hidden' name='spell25' value='$_POST[spell25]'>
<input type='hidden' name='spell26' value='$_POST[spell26]'>
<input type='hidden' name='spell27' value='$_POST[spell27]'>
<input type='hidden' name='S_ID' value='step5'>
<input type='submit' name='submit' value='Back' id='back'> &nbsp;&nbsp;
<input type='submit' name='submit' value='Next' id='next'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 else if(empty($_REQUEST['step']) || $_REQUEST['step'] == '6')
  {
  print "<strong>Step 6: Review and Submit</strong><br /><br />";

  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
   if(empty($pp['player_points'])){$pp_cur = "0";}else{$pp_cur = $pp['player_points'];}
   $pp_avail = $pp_new + $pp_cur;

   if(!empty($_POST['skill_total'])){$skill_total = $_POST['skill_total'];}else{$skill_total = $skill_total;}
   if(empty($skill_total)){$skill_total = "0";}

   if(!empty($_POST['path_total'])){$path_total = $_POST['path_total'];}else{$path_total = $path_total;}
   if(empty($path_total)){$path_total = "0";}

   if(!empty($_POST['spell_total'])){$spell_total = $_POST['spell_total'];}else{$spell_total = $spell_total;}
   if(empty($spell_total)){$spell_total = "0";}

   if(!empty($_POST['item_total'])){$item_total = $_POST['item_total'];}else{$item_total = $item_total;}
   if(empty($item_total)){$item_total = "0";}

   $spent = $skill_total + $path_total + $spell_total + $item_total;
   $pp_total = $pp_avail - $spent; 

   print "<div id='pp-box'><strong>Player Points</strong><br><br>Starting Points: $pp_avail<br><br>Spent on Skills: $skill_total<br><br>Spent on Skill Path: $path_total<br><br>Spent on Spells: $spell_total<br><br>Spent on Items: $item_total<br><br><strong>Available: $pp_total</strong></div>";

  print "<form method='post' action=''><table width='800' border='0' cellspacing='5' cellpadding='5'>";
  print "<tr><td width='200' align='right'><strong>Player</strong></td><td width='600'>$player[pf_real_name]</td></tr>";
  print "<tr><td align='right'><strong>Character Name</strong></td><td>$_POST[char_name]</td></tr>";
  print "<tr><td align='right'><strong>Species</strong></td><td>$race[race_name]</td></tr>";

   print "<tr><td align='right'><strong>Ability</strong></td><td>$race[race_abil]"; 
   if($race['race_abil'] == 'Shapeshift'){echo ": ".$_POST['shapeshift'];}
   print "</td></tr><tr><td align='right'><strong>Restriction</strong></td><td>$race[race_rest]</td></tr>"; 

   print "<tr><td align='right'><strong>Pure Blood</strong></td><td>$_POST[race_blood]</td></tr>";

   $skill1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill1]' LIMIT 1"));
   print "<tr><td align='right'><strong>Skill One</strong></strong></td><td>$skill1[skill_name], Level $_POST[skill1_lvl]</td></tr>";

   if(!empty($_POST['skill2']))
    {
    $skill2 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill2]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Two</strong></strong></td><td>$skill2[skill_name], Level $_POST[skill2_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill3']))
    {
    $skill3 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill3]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Three</strong></strong></td><td>$skill3[skill_name], Level $_POST[skill3_lvl]</td></tr>";
    } 

   if(!empty($_POST['skill4']))
    {
    $skill4 = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$_POST[skill4]' LIMIT 1"));
    print "<tr><td align='right'><strong>Skill Four</strong></strong></td><td>$skill4[skill_name], Level $_POST[skill4_lvl]</td></tr>";
    } 

   print "<tr><td align='right'><strong>Skill Path</strong></td><td>"; 
   if(empty($_POST['skill_path'])){print "N";}
   else
    {
    $skillp = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$_POST[skill_path]' LIMIT 1"));
    print "$skillp[skillpath_name], Level $_POST[skill_path_lvl]";
    }
   print "</td></tr>";

   if(!empty($_POST['magic'])){print "<tr><td align='right'><strong>Magic Points</strong></td><td>$_POST[magic]</td></tr>";}
   if(empty($_POST['path'])){print "<tr><td align='right'><strong>Spells</strong></td><td>none</td></tr>";}
   else
    {
    if($_POST['path'] == 'Dark'){$path = "2";}else{$path = "3";}
    print "<tr><td align='right'><strong>Witch Path</strong></td><td>$_POST[path]</td></tr>";
    print "<tr><td align='right' valign='top'><strong>Spells</strong></td><td>";
    print "<table width='300' border='0' cellpadding='0' cellspacing='0'><tr><td width='150'><strong>Common</strong></td><td width='150'><strong>$_POST[path]</strong></td></tr>";
    print "<tr><td valign='top'>"; 
    if(empty($_POST['spell1']) && empty($_POST['spell2']) && empty($_POST['spell3']) && empty($_POST['spell4']) && empty($_POST['spell5']) && empty($_POST['spell6']) && empty($_POST['spell7']) && empty($_POST['spell8']) && empty($_POST['spell9'])){print "none";}
    else
     {
     $array_comspells = array($_POST['spell1'], $_POST['spell2'], $_POST['spell3'], $_POST['spell4'], $_POST['spell5'], $_POST['spell6'], $_POST['spell7'], $_POST['spell8'], $_POST['spell9']);
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
    if(empty($_POST['spell10']) && empty($_POST['spell11']) && empty($_POST['spell12']) && empty($_POST['spell13']) && empty($_POST['spell14']) && empty($_POST['spell15']) && empty($_POST['spell16']) && empty($_POST['spell17']) && empty($_POST['spell18']) && empty($_POST['spell119']) && empty($_POST['spell120']) && empty($_POST['spell121']) && empty($_POST['spell122']) && empty($_POST['spell123']) && empty($_POST['spell124']) && empty($_POST['spell125']) && empty($_POST['spell126']) && empty($_POST['spell127'])){print "none";}
    else
     {
     $array_pspells = array($_POST['spell10'], $_POST['spell11'], $_POST['spell12'], $_POST['spell13'], $_POST['spell14'], $_POST['spell15'], $_POST['spell16'], $_POST['spell17'], $_POST['spell18'], $_POST['spell19'], $_POST['spell20'], $_POST['spell21'], $_POST['spell22'], $_POST['spell23'], $_POST['spell24'], $_POST['spell25'], $_POST['spell26'], $_POST['spell27']);
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
    print "</td></tr></table></td></tr>";
    }

  print "<tr><td valign='top' align='right'><strong>Buy Items</strong></td><td>none</td></tr>"; 

  print "<tr><td align='right'>&nbsp;</td><td><strong>ALL DONE! Please check to make sure the above information is correct. Use the Back button below to go back and make any edits. If everything looks good, click Finish to submit your character!</strong><br><br>
The Background, Public Biography and Photo can be managed from your Active Character list once you submit your new character here.
<br><br>
<input type='hidden' name='user_id' value='$pp[user_id]'>
<input type='hidden' name='char_name' value='$_POST[char_name]'>
<input type='hidden' name='race' value='$_POST[race]'>
<input type='hidden' name='race_blood' value='$_POST[race_blood]'>
<input type='hidden' name='race_name' value='$race[race_name]'>
<input type='hidden' name='ability' value='$race[race_abil]'>
<input type='hidden' name='shapeshift' value='$_POST[shapeshift]'>
<input type='hidden' name='restriction' value='$race[race_rest]'>
<input type='hidden' name='player_points' value='$pp[player_points]'>
<input type='hidden' name='skill_total' value='$skill_total'>
<input type='hidden' name='path_total' value='$path_total'>
<input type='hidden' name='spell_total' value='$spell_total'>
<input type='hidden' name='item_total' value='$item_total'>
<input type='hidden' name='skill1' value='$_POST[skill1]'>
<input type='hidden' name='skill2' value='$_POST[skill2]'>
<input type='hidden' name='skill3' value='$_POST[skill3]'>
<input type='hidden' name='skill4' value='$_POST[skill4]'>
<input type='hidden' name='skill1_lvl' value='$_POST[skill1_lvl]'>
<input type='hidden' name='skill2_lvl' value='$_POST[skill2_lvl]'>
<input type='hidden' name='skill3_lvl' value='$_POST[skill3_lvl]'>
<input type='hidden' name='skill4_lvl' value='$_POST[skill4_lvl]'>
<input type='hidden' name='skill_path' value='$_POST[skill_path]'>
<input type='hidden' name='skill_path_lvl' value='$_POST[skill_path_lvl]'>
<input type='hidden' name='path' value='$_POST[path]'>
<input type='hidden' name='magic' value='$_POST[magic]'>
<input type='hidden' name='spell1' value='$_POST[spell1]'>
<input type='hidden' name='spell2' value='$_POST[spell2]'>
<input type='hidden' name='spell3' value='$_POST[spell3]'>
<input type='hidden' name='spell4' value='$_POST[spell4]'>
<input type='hidden' name='spell5' value='$_POST[spell5]'>
<input type='hidden' name='spell6' value='$_POST[spell6]'>
<input type='hidden' name='spell7' value='$_POST[spell7]'>
<input type='hidden' name='spell8' value='$_POST[spell8]'>
<input type='hidden' name='spell9' value='$_POST[spell9]'>
<input type='hidden' name='spell10' value='$_POST[spell10]'>
<input type='hidden' name='spell11' value='$_POST[spell11]'>
<input type='hidden' name='spell12' value='$_POST[spell12]'>
<input type='hidden' name='spell13' value='$_POST[spell13]'>
<input type='hidden' name='spell14' value='$_POST[spell14]'>
<input type='hidden' name='spell15' value='$_POST[spell15]'>
<input type='hidden' name='spell16' value='$_POST[spell16]'>
<input type='hidden' name='spell17' value='$_POST[spell17]'>
<input type='hidden' name='spell18' value='$_POST[spell18]'>
<input type='hidden' name='spell19' value='$_POST[spell19]'>
<input type='hidden' name='spell20' value='$_POST[spell20]'>
<input type='hidden' name='spell21' value='$_POST[spell21]'>
<input type='hidden' name='spell22' value='$_POST[spell22]'>
<input type='hidden' name='spell23' value='$_POST[spell23]'>
<input type='hidden' name='spell24' value='$_POST[spell24]'>
<input type='hidden' name='spell25' value='$_POST[spell25]'>
<input type='hidden' name='spell26' value='$_POST[spell26]'>
<input type='hidden' name='spell27' value='$_POST[spell27]'>
<input type='hidden' name='S_ID' value='step6'>
<input type='submit' name='submit' value='Back' id='back'> &nbsp;&nbsp;
<input type='submit' name='submit' value='Finished' id='check'> &nbsp;&nbsp;&nbsp;&nbsp; <a href='character.php'>Cancel</a></td></tr>";
  print "</table></form>";
  }
 }
 print "</div></div></div>";
include ('character-footer.php'); 
?>