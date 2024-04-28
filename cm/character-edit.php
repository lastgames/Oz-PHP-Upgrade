<?php
include ('character-header.php'); 

if(!empty($_REQUEST['page'])){$page = $_REQUEST['page'];}else{$page = "1";}
if(!empty($_REQUEST['char_id'])){$charid = $_REQUEST['char_id'];}else{$charid = "no";}
if(empty($_REQUEST['mode'])){$modeid = $loggedin[user_id];}else{$modeid = $_REQUEST['mode'];}
if(!empty($_REQUEST['edit_type'])){$edit_type = $_REQUEST['edit_type'];}else{$edit_type = "no";}
if($charid == 'no'){$access = "Please go back a choose a character.";}
if($edit_type == 'no'){$access = "Please go back and select a command.";}

$groups = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$loggedin[user_id]' OR `group_id`='11' OR `group_id`='20'"));
$char = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters` WHERE `char_id`='$charid' LIMIT 1"));
$pp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points FROM `bb_users` WHERE `user_id`='$char[user_id]' LIMIT 1"));
$player = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$char[user_id]' LIMIT 1"));

if($loggedin['user_id'] != $char['user_id'] && !$groups){$access = "You do not have permission to view this character.";}

if(!empty($error)){print "<br><div id='error'> $error</div><br>";}
if($loggedin['user_id'] != $modeid){print "<br><div id='mode'>Viewing as $player[pf_real_name]</div><br>"; $mode = "&mode=".$modeid;}
if(!empty($access)){print "<br><br><div id='error'> $access</div><br><br>";}

 print "<div id='page-footer' style='font-size: 11px; font-type: Arial; color: #000000;'><div class='navbar'><div class='inner'>";
 print "<ul class='linklist rightside'><li class='icon-ucp' style='height:30px;'><a href='/documents/oz/oz-rulebook.pdf' target='_blank' title='Download Rule Book'><img border='0' src='/images/icon-dwn-rules.png' /></a></li><li class='icon-ucp' style='height:30px;'><a href='character-view.php?char_id=$charid$mode' title='Back to Character Overview'><img border='0' src='/images/icon-back.png' /></a></li></ul><br><br>";

if(empty($access))
 {
 if($edit_type == 'modifydetails')
  {
  print "<h2 style='margin-top: 5px;'>Modify Details: <a href='character-view.php?char_id=$charid'>$char[char_name]</a></h2>";
  print "<form action='' method='post'><table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
  print "<tr><td width='100' align='right'><strong>Player Points</strong></td><td width='700'>$pp[player_points]</td></tr>";

  print "<tr><td align='right'><strong>Status</strong></td><td>
<input type='radio' name='char_status' value='1'"; if($char['char_status'] == '1' || $char['char_status'] == 'Approved'){print " checked=checked";} print "> Active <input type='radio' name='char_status' value='2' onclick='return show_status()'"; if($char['char_status'] == '2'){print " checked=checked";} print "> Archived <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>An Archived character does not count against your Active character total and there is no limit to the number of Archived characters you can have. <br><br>Archived characters cannot be edited for anything that costs or earns Player Points.</span></a></p></td></tr>";

  print "<tr><td align='right'><strong>Species</strong></td><td>";
//  if($groups)
//   {
//   print "<select name='race_id'>";
//   $get_race = mysql_query("SELECT * FROM `characters_races` ORDER BY `race_name` ASC");
//   while($race = mysql_fetch_assoc($get_race))
//    {
//    print "<option value='$race[race_id]'"; if($char['race'] == $race['race_id'] || $_POST['race_id'] == $race['race_id']){print "selected";} print ">$race[race_name]</option>";
//    }
//   print "</select>";
//   }
//  else{
print "$char[race_name] <input type='hidden' name='race_id' value='$char[race]'>";
// }
  
  if($char['race'] == '2')
   {
   if(empty($char['shapeshift']) || $groups)
    {
    print " - Shapeshift: <select name='shapeshift'><option value='Pelt'"; if($char['shapeshift'] == 'Pelt' || $_POST['shapeshift'] == 'Pelt'){print "selected";} print ">Pelt</option><option value='Weapon'"; if($char['shapeshift'] == 'Weapon' || $_POST['shapeshift'] == 'Weapon'){print "selected";} print ">Weapons</option></select>";
    }
   else{print " - Shapeshift: $char[shapeshift] <input type='hidden' name='shapeshift' value='$char[shapeshift]'>";}
   }
  print "</td></tr>";

  print "<tr><td align='right'><strong>Pure Blood</strong></td><td>";
  if(empty($char['race_blood']) || $char['race_blood'] == 'N' || $groups)
   {
   print "<select name='race_blood'>";
   print "<option value='N'"; if(empty($char['race_blood']) || $char['race_blood'] == 'N' || $_POST['race_blood'] == 'N'){print "selected";} print ">No</option>";
   print "<option value='Y'"; if($char['race_blood'] == 'Y' || $_POST['race_blood'] == 'Y'){print "selected";} print ">Yes</option>";
   print "</select> costs $CF_OZ_PURE_PP pp";
   }
  else{print "Yes <input type='hidden' name='race_blood' value='$char[race_blood]'>";}
  print "</td></tr>";

//  print "<tr height='40'><td align='right'></td><td align='center' bgcolor='#FFF'><strong>Delete Character:</strong> <input type='radio' name='delete' value='N'"; if(empty($_POST['delete'])){print " checked=checked";} print "> No <input type='radio' name='delete' value='Y' onclick='return show_confirm()'> Yes, cannot be undone and no Player Point refund will be issued</td></tr>";

   if($groups){print "<tr><td align='right'></td><td><input type='checkbox' name='pp_manage' value='Y'"; if(!empty($_POST['pp_manage']) && $_POST['pp_manage'] == 'Y'){echo " checked=checked";} print "/ > Manage Players Points <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>Player Points will not be spent if purchasing Pure Blood. <br><br>Refunds will be issued for 1) species change, and 2) Character deletion.</span></a></p></td></tr>";}

  print "</td></tr><tr><td></td><td>
<input type='hidden' name='user_id' value='$char[user_id]'>
<input type='hidden' name='player_points' value='$pp[player_points]'>
<input type='hidden' name='player_name' value='$player[pf_real_name]'>
<input type='hidden' name='ppearned_spent' value='$char[ppearned_spent]'>
<input type='hidden' name='edit_type' value='$edit_type'>
<input type='hidden' name='char_name' value='$char[char_name]'>
<input type='hidden' name='magic' value='$char[magic_points]'>
<input type='hidden' name='char_id' value='$charid'>
<input type='hidden' name='race_id_cur' value='$char[race]'>
<input type='hidden' name='race_blood_cur' value='$char[race_blood]'>
<input type='hidden' name='S_ID' value='modifydetails'>
<input type='submit' name='submit' value='Submit'></td></tr></table></form><br><br>";  
  }
 if($edit_type == 'buyskills')
  {
  print "<h2 style='margin-top: 5px;'>Buy Skills: <a href='character-view.php?char_id=$charid'>$char[char_name]</a></h2>";
  print "<form action='' method='post'>";
  print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
  print "<tr><td width='100' align='right'><strong>Player Points</strong></td><td width='700'>$pp[player_points]</td></tr>";
  print "<tr><td align='right' valign='top'><strong>Skill Path</strong></td><td>";
  if(empty($char['skill_path']) || $groups)
   {
   if(!empty($char['shapeshift']) && $char['shapeshift'] == 'Weapon'){$claw = "C"; $amtc = "3";}else{$claw = "No"; $amtc = "0";}
   if(!empty($char['skill1']))
    {
    if($char['skill1'] == '2' || $char['skill1'] == '14'){$amt1 = "3";}else{$amt1 = $char['skill1_lvl'];}
    if($char['skill1'] == '6' || $char['skill1'] == '8' || $char['skill1'] == '9'){$skill = "1";}else{$skill = "No";}
    if($amt1 == '3'){$sk1 = $char['skill1'];}else{$sk1 = "0";}
    }
   if(!empty($char['skill2']))
    {
    if($char['skill2'] == '2' || $char['skill2'] == '14'){$amt2 = "3";}else{$amt2 = $char['skill2_lvl'];}
    if($char['skill2'] == '6' || $char['skill2'] == '8' || $char['skill2'] == '9'){$skill = "1";}else{$skill = "No";}
    if($amt2 == '3'){$sk2 = $char['skill2'];}else{$sk2 = "0";}
    }
   if(!empty($char['skill3']))
    {
    if($char['skill3'] == '2' || $char['skill3'] == '14'){$amt3 = "3";}else{$amt3 = $char['skill3_lvl'];}
    if($char['skill3'] == '6' || $char['skill3'] == '8' || $char['skill3'] == '9'){$skill = "1";}else{$skill = "No";}
    if($amt3 == '3'){$sk3 = $char['skill3'];}else{$sk3 = "0";}
    }
   if(!empty($char['skill4']))
    {
    if($char['skill4'] == '2' || $char['skill4'] == '14'){$amt4 = "3";}else{$amt4 = $char['skill4_lvl'];}
    if($char['skill4'] == '6' || $char['skill4'] == '8' || $char['skill4'] == '9'){$skill = "1";}else{$skill = "No";}
    if($amt4 == '3'){$sk4 = $char['skill4'];}else{$sk4 = "0";}
    }
   $array = array($amt1, $amt2, $amt3, $amt4, $amtc);
   $instances = count(array_keys($array, 3));
   if($instances <= 1){print "Two level 3 Skills, or one level 3 Skill and Shapeshift: Weapons are required.";}
   else 
    {
    if(empty($char['path'])){$witch = "No";}else{if($char['path'] == 'Dark'){$witch = "12D";}else{$witch = "12L";}}
    print "<select name='skill_path_new' style='width: 125px;'><option value=''></option>";
    $get_skillpaths = mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_req1`='$sk1' OR `skillpath_req1`='$sk2' OR `skillpath_req1`='$sk3' OR `skillpath_req1`='$sk4' OR `skillpath_req1`='$witch' OR `skillpath_req1`='$skill' ORDER BY `skillpath_name` ASC");
    while($paths = mysql_fetch_assoc($get_skillpaths))
     {
     if($paths['skillpath_req2'] == $witch || $paths['skillpath_req2'] == $claw || $paths['skillpath_req2'] == $sk1 || $paths['skillpath_req2'] == $sk2 || $paths['skillpath_req2'] == $sk3 || $paths['skillpath_req2'] == $sk4 || $paths['skillpath_req3'] == $witch || $paths['skillpath_req3'] == $claw || $paths['skillpath_req3'] == $sk1 || $paths['skillpath_req3'] == $sk2 || $paths['skillpath_req3'] == $sk3 || $paths['skillpath_req3'] == $sk4 || $paths['skillpath_req4'] == $witch || $paths['skillpath_req4'] == $claw || $paths['skillpath_req4'] == $sk1 || $paths['skillpath_req4'] == $sk2 || $paths['skillpath_req4'] == $sk3 || $paths['skillpath_req4'] == $sk4)
      {
      print "<option value='$paths[skillpath_id]'"; if($_POST['skill_path_new'] == $paths['skillpath_id'] || $char['skill_path'] == $paths['skillpath_id']){print " selected";} print ">$paths[skillpath_name]</option>";
      }
     }
    print "</select> at level <select name='skill_path_lvl_new'>
<option value='1'"; if($_POST['skill_path_lvl_new'] == '1' || $char['skill_path_lvl'] == '1'){echo "selected";} print ">1</option>
<option value='2'"; if($_POST['skill_path_lvl_new'] == '2' || $char['skill_path_lvl'] == '2'){echo "selected";} print ">2</option>
<option value='3'"; if($_POST['skill_path_lvl_new'] == '3' || $char['skill_path_lvl'] == '3'){echo "selected";} print ">3</option></select> Purchase cost: $CF_OZ_PATH_PP pp per level";
    }
   }
  else
   {
   $skillpath_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$char[skill_path]' LIMIT 1"));
   print "$skillpath_name[skillpath_name] at level ";
   if($char['skill_path_lvl'] == '3'){echo $char['skill_path_lvl'];}
   else 
    {
    print "<select name='skill_path_lvl_new'>";
    if($char['skill_path_lvl'] == '1'){$arrP = array(1, 2);}
    else if($char['skill_path_lvl'] == '2'){$arrP = array(2, 3);}
    foreach($arrP as $arrP => $valP)
     {
     echo "<option value=".$valP; if($char['skill_path_lvl'] == $valP){echo " selected";} echo ">".$valP."</option>";
     }
    print "</select> Advancement cost: $CF_OZ_PATH_PP pp";
    }
   }

// ************* SKILLS *************

  if($char['skill1'] == '12' || $char['skill2'] == '12' || $char['skill3'] == '12' || $char['skill4'] == '12')
   {
   $get_skill2 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='1' AND `skill_show`='Y' ORDER BY `skill_name` ASC");
   $get_skill3 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='1' AND `skill_show`='Y' ORDER BY `skill_name` ASC");
   if($char['race'] == '5'){$get_skill4 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='1' AND `skill_show`='Y' ORDER BY `skill_name` ASC");}
   } 
  else if($char['skill1'] == '1' || $char['skill2'] == '1' || $char['skill3'] == '1' || $char['skill4'] == '1')
   {
   $get_skill2 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='12' AND `skill_show`='Y' ORDER BY `skill_name` ASC");
   $get_skill3 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='12' AND `skill_show`='Y' ORDER BY `skill_name` ASC");
   if($char['race'] == '5'){$get_skill4 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`!='12' AND `skill_show`='Y' ORDER BY `skill_name` ASC");}
   }
  else
   {
   $get_skill2 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   $get_skill3 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   if($char['race'] == '5'){$get_skill4 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");}    }

  print "</td></tr><tr><td align='right' valign='top'>"; if(!empty($errorskill1)){echo "<div style='color: #bc4328;'><strong>Skill One</strong></div>";} else {echo "<strong>Skill One</strong>";} print "</td><td>";
  if(empty($char['skill1']) || $groups)
   {
   print "<select name='skill1_new' style='width: 125px;'>";
   $get_skill1 = mysql_query("SELECT * FROM `characters_skills` WHERE `skill_show`='Y' ORDER BY `skill_name` ASC");
   while($skill1 = mysql_fetch_assoc($get_skill1))
    { 
    print "<option value='$skill1[skill_id]'"; if($_POST['skill1_new'] == $skill1['skill_id'] || $char['skill1'] == $skill1['skill_id']){echo "selected";} print ">$skill1[skill_name]</option>";
    }
   print "</select> at level <select name='skill1_lvl_new'>";
   print "<option value='1'"; if($_POST['skill1_lvl_new'] == '1' || $char['skill1_lvl'] == '1'){echo "selected";} print ">1</option>";
   print "<option value='2'"; if($_POST['skill1_lvl_new'] == '2' || $char['skill1_lvl'] == '2'){echo "selected";} print ">2</option>";
   print "<option value='3'"; if($_POST['skill1_lvl_new'] == '3' || $char['skill1_lvl'] == '3'){echo "selected";} print ">3</option>";
   print "</select> Purchase cost: first level free. $CF_OZ_SKILL_PP pp per level beyond the first";
   }
  else
   {
   $skill1_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$char[skill1]' LIMIT 1"));   
   print "<input type='hidden' name='skill1_new' value='$char[skill1]' /> $skill1_name[skill_name] at level ";
   if($char['skill1_lvl'] == '3'){print "3 <input type='hidden' name='skill1_lvl_new' value='3' />";}
   else 
    {
    print "<select name='skill1_lvl_new'>";
    if($char['skill1_lvl'] == '1'){$arr = array(1, 2);}
    else if($char['skill1_lvl'] == '2'){$arr = array(2, 3);} 
    foreach($arr as $arr => $val)
     {
     echo "<option value=".$val; if($char['skill1_lvl'] == $val){echo " selected";} echo ">".$val."</option>";
     }
    print "</select>";
    }
   }
  print "</td></tr><tr><td align='right' valign='top'>"; if(!empty($errorskill2)){echo "<div style='color: #bc4328;'><strong>Skill Two</strong></div>";} else {echo "<strong>Skill Two</strong>";} print "</td><td>";
  if(empty($char['skill2']) || $groups)
   {
   print "<select name='skill2_new' style='width: 125px;'><option value=''"; if(empty($_POST['skill2_lvl_new']) || empty($char['skill2_lvl'])){echo "selected";} print "></option>";
   while($skill2 = mysql_fetch_assoc($get_skill2))
    { 
     if($char['skill1'] != $skill2['skill_id'] && $char['skill3'] != $skill2['skill_id'] && $char['skill4'] != $skill2['skill_id'])
      { 
      print "<option value='$skill2[skill_id]'"; if($_POST['skill2_new'] == $skill2['skill_id'] || $char['skill2'] == $skill2['skill_id']){echo "selected";} print ">$skill2[skill_name]</option>";
      }
    }
   print "</select> at level <select name='skill2_lvl_new'>";
   print "<option value='1'"; if($_POST['skill2_lvl_new'] == '1' || $char['skill2_lvl'] == '1'){echo "selected";} print ">1</option>";
   print "<option value='2'"; if($_POST['skill2_lvl_new'] == '2' || $char['skill2_lvl'] == '2'){echo "selected";} print ">2</option>";
   print "<option value='3'"; if($_POST['skill2_lvl_new'] == '3' || $char['skill2_lvl'] == '3'){echo "selected";} print ">3</option>";
   print "</select> Purchase cost: $CF_OZ_SKILL_PP pp per level";
   }
  else
   {
   $skill2_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$char[skill2]' LIMIT 1"));   
   print "<input type='hidden' name='skill2_new' value='$char[skill2]' /> $skill2_name[skill_name] at level ";
    if($char['skill2_lvl'] == '3'){print "3 <input type='hidden' name='skill2_lvl_new' value='3' />";}
    else 
     {
     print "<select name='skill2_lvl_new'>";
     $i = 0;
     if($char['skill2_lvl'] == '1'){$arr2 = array(1, 2);}
     else if($char['skill2_lvl'] == '2'){$arr2 = array(2, 3);}
     foreach($arr2 as $arr2 => $val2)
      {
      echo "<option value=".$val2; if($char['skill2_lvl'] == $val2){echo " selected";} echo ">".$val2."</option>";
      }
    print "</select>";
    }
   }
  print "</td></tr><tr><td align='right' valign='top'>"; if(!empty($errorskill3)){echo "<div style='color: #bc4328;'><strong>Skill Three</strong></div>";} else {echo "<strong>Skill Three</strong>";} print "</td><td>";
  if(empty($char['skill3']) || $groups)
   {
   print "<select name='skill3_new' style='width: 125px;'><option value=''></option>";
   while($skill3 = mysql_fetch_assoc($get_skill3))
    { 
   if($char['skill1'] != $skill3['skill_id'] && $char['skill2'] != $skill3['skill_id'] && $char['skill4'] != $skill3['skill_id'])
    {
    print "<option value='$skill3[skill_id]'"; if($_POST['skill3_new'] == $skill3['skill_id'] || $char['skill3'] == $skill3['skill_id']){echo "selected";} print ">$skill3[skill_name]</option>";
    }
    }
   print "</select> at level <select name='skill3_lvl_new'>";
   print "<option value='1'"; if($_POST['skill3_lvl_new'] == '1' || $char['skill3_lvl'] == '1'){echo "selected";} print ">1</option>";
   print "<option value='2'"; if($_POST['skill3_lvl_new'] == '2' || $char['skill3_lvl'] == '2'){echo "selected";} print ">2</option>";
   print "<option value='3'"; if($_POST['skill3_lvl_new'] == '3' || $char['skill3_lvl'] == '3'){echo "selected";} print ">3</option>";
   print "</select> Purchase cost: $CF_OZ_SKILL_PP pp per level";
   }
  else
   {
   $skill3_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$char[skill3]' LIMIT 1"));  
   print "<input type='hidden' name='skill3_new' value='$char[skill3]' /> $skill3_name[skill_name] at level ";
  if($char['skill3_lvl'] == '3'){print "3 <input type='hidden' name='skill3_lvl_new' value='3' />";}
  else 
   {
   print "<select name='skill3_lvl_new'>";
   if($char['skill3_lvl'] == '1'){$arr3 = array(1, 2);}
   else if($char['skill3_lvl'] == '2'){$arr3 = array(2, 3);}
   foreach($arr3 as $arr3 => $val3)
    {
    echo "<option value=".$val3; if($char['skill3_lvl'] == $val3){echo " selected";} echo ">".$val3."</option>";
    }
   print "</select>";
   }
   }
  print "</td></tr>";
  if($char['race'] =='5')
   {
  print "</td></tr><tr><td align='right' valign='top'>"; if(!empty($errorskill4)){echo "<div style='color: #bc4328;'><strong>Skill Four</strong></div>";} else {echo "<strong>Skill Four</strong>";} print "</td><td>";
  if(empty($char['skill4']) || $groups)
   {
   print "<select name='skill4_new' style='width: 125px;'><option value=''></option>";
   while($skill4 = mysql_fetch_assoc($get_skill4))
    { 
    if($char['skill1'] != $skill4['skill_id'] && $char['skill2'] != $skill4['skill_id'] && $char['skill3'] != $skill4['skill_id'])
     {
     print "<option value='$skill4[skill_id]'"; if($_POST['skill4_new'] == $skill4['skill_id'] || $char['skill4'] == $skill4['skill_id']){echo "selected";} print ">$skill4[skill_name]</option>";
     }
    }
   print "</select> at level <select name='skill4_lvl_new'>";
   print "<option value='1'"; if($_POST['skill4_lvl_new'] == '1' || $char['skill4_lvl'] == '1'){echo "selected";} print ">1</option>";
   print "<option value='2'"; if($_POST['skill4_lvl_new'] == '2' || $char['skill4_lvl'] == '2'){echo "selected";} print ">2</option>";
   print "<option value='3'"; if($_POST['skill4_lvl_new'] == '3' || $char['skill4_lvl'] == '3'){echo "selected";} print ">3</option>";
   print "</select> Purchase cost: $CF_OZ_SKILL_PP pp per level";
    }
   else
    {
   $skill4_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skills` WHERE `skill_id`='$char[skill4]' LIMIT 1"));
   print "<input type='hidden' name='skill4_new' value='$char[skill4]' /> $skill4_name[skill_name] at level ";
  if($char['skill4_lvl'] == '3'){print "3 <input type='hidden' name='skill4_lvl_new' value='3' />";}
  else 
   {
   print "<select name='skill4_lvl_new'>";
   if($char['skill4_lvl'] == '1'){$arr4 = array(1, 2);}
   else if($char['skill4_lvl'] == '2'){$arr4 = array(2, 3);}
   foreach($arr4 as $arr4 => $val4)
    {
    echo "<option value=".$val4; if($char['skill4_lvl'] == $val4){echo " selected";} echo ">".$val4."</option>";
    }
   print "</select>";
   }
    }
   print "</td></tr>";
   }
  print "<tr><td></td><td>";
   if($groups){print "<input type='checkbox' name='pp_manage' value='Y'"; if(!empty($_POST['pp_manage']) && $_POST['pp_manage'] == 'Y'){echo "checked";} print "/ > Manage Players Points <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>If you lowered a level, a refund will be issued. If you raised a level or added a new Skill / Path, player point expenditure is waived. This management will apply for changes to any and all Skills and Path. <br><br>NOTE: altering Skills will also alter the selections available for Skill Path and may make an already selected Path unavailable.</span></a></p><br><br>";}

  print "
<input type='hidden' name='skill_path' value='$char[skill_path]' />
<input type='hidden' name='skill_path_lvl' value='$char[skill_path_lvl]' />
<input type='hidden' name='skill1' value='$char[skill1]' />
<input type='hidden' name='skill2' value='$char[skill2]' />
<input type='hidden' name='skill3' value='$char[skill3]' />
<input type='hidden' name='skill4' value='$char[skill4]' />
<input type='hidden' name='skill1_lvl' value='$char[skill1_lvl]' />
<input type='hidden' name='skill2_lvl' value='$char[skill2_lvl]' />
<input type='hidden' name='skill3_lvl' value='$char[skill3_lvl]' />
<input type='hidden' name='skill4_lvl' value='$char[skill4_lvl]' />
<input type='hidden' name='race' value='$char[race]'>
<input type='hidden' name='user_id' value='$pp[user_id]'>
<input type='hidden' name='player_points' value='$pp[player_points]'>
<input type='hidden' name='ppearned_spent' value='$char[ppearned_spent]'>
<input type='hidden' name='edit_type' value='$edit_type'>
<input type='hidden' name='char_id' value='$charid'>
<input type='hidden' name='char_name' value='$char[char_name]'>
<input type='hidden' name='S_ID' value='buyskills'>";
if($char['char_status'] != '2' || $groups){print "<input type='submit' name='submit' value='Submit'>";}
else{print "<strong>ARCHIVED</strong></div>";}
  print "</td></tr></table></form><br><br>";
  }
 else if($edit_type == 'buyspells')
  {
  print "<h2 style='margin-top: 5px;'>Buy Spells: $char[char_name]</h2>";
  if($char['skill1'] == '12' || $char['skill2'] == '12' || $char['skill3'] == '12' || $char['skill4'] == '12') // Witch skill check
   {
   print "<form action='' method='post'>";
   print "<table width='800' border='0' cellspacing='5' cellpadding='5' id='cm_table2'>";
   print "<tr><td width='100' align='right'><strong>Player Points</td><td width='700'>$pp[player_points]</td></tr>";
   print "<tr><td align='right'><strong>Skill Level</td><td>"; 
   if($char['skill1'] == '12'){$lvl1 = $char['skill1_lvl']; echo $char['skill1_lvl'];}
   else if($char['skill2'] == '12'){$lvl1 = $char['skill2_lvl']; echo $char['skill2_lvl'];}
   else if($char['skill3'] == '12'){$lvl1 = $char['skill3_lvl']; echo $char['skill3_lvl'];}
   else if($char['skill4'] == '12'){$lvl1 = $char['skill4_lvl']; echo $char['skill4_lvl'];}
   else {$lvl1 = 'n';}
   print "</td></tr>";

   print "<tr><td align='right'><strong>Spell Path</strong></td><td>";
   if(empty($char['path']))
    {
    print "<select name='path_new'>";
    print "<option value='Dark'"; if($_POST['path_new'] == 'Dark'){echo "selected";} print ">Dark</option>";
    print "<option value='Light'"; if($_POST['path_new'] == 'Light'){echo "selected";} print ">Light</option>";
    print "</select> A Path is required to buy Spells";
    }
   else
    {
    if(!$groups){echo $char['path'];}
    else
     {
     print "<select name='path_new'>";
     print "<option value='Dark'"; if($char['path'] == 'Dark' || $_POST['path_new'] == 'Dark'){echo "selected";} print ">Dark</option>";
     print "<option value='Light'"; if($char['path'] == 'Light' || $_POST['path_new'] == 'Light'){echo "selected";} print ">Light</option>";
     print "</select> !! <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>Changing Spell Path will <strong>delete</strong> all Path Spells <strong>without refund</strong> unless the Manage Player Points option is checked.</span></a></p> !!";
     }
    }
   print "</td></tr>";

   print "<tr><td align='right' valign='top'><strong>Current Spells</strong></td><td>";
   if($groups){print "Checked below";}
    else
     { 
     print "<table width='300' border='0' cellpadding='0' cellspacing='0'><tr><td width='150'><strong>Common</strong></td><td width='150'><strong>$char[path]</strong></td></tr><tr><td valign='top'>";
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
        print "Lvl $comspells[spell_level]: $comspells[spell_name] <input type='hidden' name='spell$comspells[spell_var]' value='$comspells[spell_id]' /><br>";
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
        print "Lvl $pspells[spell_level]: $pspells[spell_name] <input type='hidden' name='spell$pspells[spell_var]' value='$pspells[spell_id]' /><br>";
        }
       }
      }
     }
     print "</td></tr></table>";
     }
   print "</td></tr>";

    if($char['path'] == 'Dark'){$path = "2";}else{$path = "3";}

  if(!empty($char['spell1'])){$cost1 = "1";}
  if(!empty($char['spell2'])){$cost2 = "1";} 
  if(!empty($char['spell3'])){$cost3 = "1";} 
  if(!empty($char['spell4'])){$cost4 = "1";} 
  if(!empty($char['spell5'])){$cost5 = "1";} 
  if(!empty($char['spell6'])){$cost6 = "1";} 
  if(!empty($char['spell7'])){$cost7 = "1";} 
  if(!empty($char['spell8'])){$cost8 = "1";} 
  if(!empty($char['spell9'])){$cost9 = "1";}
  if(!empty($char['spell10'])){$cost10 = "1";}
  if(!empty($char['spell11'])){$cost11 = "1";}
  if(!empty($char['spell12'])){$cost12 = "1";}
  if(!empty($char['spell13'])){$cost13 = "1";}
  if(!empty($char['spell14'])){$cost14 = "1";}
  if(!empty($char['spell15'])){$cost15 = "1";}
  if(!empty($char['spell16'])){$cost16 = "1";}
  if(!empty($char['spell17'])){$cost17 = "1";}
  if(!empty($char['spell18'])){$cost18 = "1";}
  if(!empty($char['spell19'])){$cost19 = "1";}
  if(!empty($char['spell20'])){$cost20 = "1";}
  if(!empty($char['spell21'])){$cost21 = "1";}
  if(!empty($char['spell22'])){$cost22 = "1";}
  if(!empty($char['spell23'])){$cost23 = "1";}
  if(!empty($char['spell24'])){$cost24 = "1";}
  if(!empty($char['spell25'])){$cost25 = "1";}
  if(!empty($char['spell26'])){$cost26 = "1";}
  if(!empty($char['spell27'])){$cost27 = "1";}

  $common1 = $cost1 + $cost2 + $cost3;
  $common2 = $cost4 + $cost5 + $cost6;
  $common3 = $cost7 + $cost8 + $cost9;

  $other1 = $cost10 + $cost11 + $cost12 + $cost13 + $cost14 + $cost15;
  $other2 = $cost16 + $cost17 + $cost18 + $cost19 + $cost20 + $cost21;
  $other3 = $cost22 + $cost23 + $cost24 + $cost25 + $cost26 + $cost27;    

  print "<tr><td align='right' valign='top'><strong>Buy Spells</strong></td><td valign='top'>The first Level 1 Spell is free. Each additional Spell costs $CF_OZ_SPELL_PP pp regardless of Level.<br>";
   print "<table width='500' cellpadding='0' cellspacing='5' style='border: solid 1px #FFFFFF; background: #EEEEEE;'>
<tr><td width='250' style='border-right: solid 1px #FFFFFF;' valign='top'><strong>Common Spells</strong></td><td width='250' valign='top'><strong>$char[path] Spells</strong></td></tr>";
   print "<tr><td style='border-right: solid 1px #FFFFFF;' valign='top'><u>Level One</u><br><br>";
   if($groups){$get_spa = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='1' ORDER BY `spell_name` ASC");}else{$get_spa = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='1' AND `spell_var`!='$char[spell1]' AND `spell_var`!='$char[spell2]' AND `spell_var`!='$char[spell3]' ORDER BY `spell_name` ASC");}
   while($spa = mysql_fetch_assoc($get_spa))
    {
    print "<input type='checkbox' name='spell$spa[spell_var]' value='$spa[spell_id]'"; if($spa['spell_id'] == $_POST['spell1'] || $spa['spell_id'] == $_POST['spell2'] || $spa['spell_id'] == $_POST['spell3'] || $spa['spell_id'] == $char['spell1'] || $spa['spell_id'] == $char['spell2'] || $spa['spell_id'] == $char['spell3']){echo " checked='checked'";} print "> $spa[spell_name]<br>";
    } 
   if($groups)
    {
   if($lvl1 == '2' || $lvl1 == '3')
    {
    print "<br><u>Level Two</u><br><br>";
    if($common1 !='0')
     {
     if($common1 >= $common2)
      {
      $get_spa2 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='2' ORDER BY `spell_name` ASC");
       while($spa2 = mysql_fetch_assoc($get_spa2))
        {
        print "<input type='checkbox' name='spell$spa2[spell_var]' value='$spa2[spell_id]'"; if($spa2['spell_id'] == $_POST['spell4'] || $spa2['spell_id'] == $_POST['spell5'] || $spa2['spell_id'] == $_POST['spell6'] || $spa2['spell_id'] == $char['spell4'] || $spa2['spell_id'] == $char['spell5'] || $spa2['spell_id'] == $char['spell6']){echo " checked";} print "> $spa2[spell_name]<br>";
        }
      }else {print "One more Level 1 Common Spell is required to purchase a Level 2 Common Spell<br>";}
     }
    else {print "A Level 1 Common Spell is required to purchase a Level 2 Common Spell<br>";}
    }
   if($lvl1 == '3')
    {
    print "<br><u>Level Three</u><br><br>";
    if($common2 !='0')
     {
     if($common2 >= $common3)
      {
      $get_spa3 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='3' ORDER BY `spell_name` ASC");
       while($spa3 = mysql_fetch_assoc($get_spa3))
        {
        print "<input type='checkbox' name='spell$spa3[spell_var]' value='$spa3[spell_id]'"; if($spa3['spell_id'] == $_POST['spell7'] || $spa3['spell_id'] == $_POST['spell8'] || $spa3['spell_id'] == $_POST['spell9'] || $spa3['spell_id'] == $char['spell7'] || $spa3['spell_id'] == $char['spell8'] || $spa3['spell_id'] == $char['spell9']){echo " checked";} print "> $spa3[spell_name]<br>";
        }
      }else {print "One more Level 2 Common Spell is required to purchase a Level 3 Common Spell<br>";}
     }
    else {print "A Level 2 Common Spell is required to purchase a Level 3 Common Spell<br>";}
    }
    }
   else // not in groups
    {
   if($lvl1 == '2' || $lvl1 == '3')
    {
    print "<br><u>Level Two</u><br><br>";
    if($common1 !='0')
     {
     if($common1 > $common2)
      {
      $get_spa2 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='2' AND `spell_var`!='$char[spell4]' AND `spell_var`!='$char[spell5]' AND `spell_var`!='$char[spell6]' ORDER BY `spell_name` ASC");
       while($spa2 = mysql_fetch_assoc($get_spa2))
        {
        print "<input type='checkbox' name='spell$spa2[spell_var]' value='$spa2[spell_id]'"; if($spa2['spell_id'] == $_POST['spell4'] || $spa2['spell_id'] == $_POST['spell5'] || $spa2['spell_id'] == $_POST['spell6'] || $spa2['spell_id'] == $char['spell4'] || $spa2['spell_id'] == $char['spell5'] || $spa2['spell_id'] == $char['spell6']){echo " checked";} print "> $spa2[spell_name]<br>";
        }
      }else {print "One more Level 1 Common Spell is required to purchase a Level 2 Common Spell<br>";}
     }
    else {print "A Level 1 Common Spell is required to purchase a Level 2 Common Spell<br>";}
    }
   if($lvl1 == '3')
    {
    print "<br><u>Level Three</u><br><br>";
    if($common2 !='0')
     {
     if($common2 > $common3)
      {
      $get_spa3 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='1' AND `spell_level`='3' AND `spell_var`!='$char[spell7]' AND `spell_var`!='$char[spell8]' AND `spell_var`!='$char[spell9]' ORDER BY `spell_name` ASC");
       while($spa3 = mysql_fetch_assoc($get_spa3))
        {
        print "<input type='checkbox' name='spell$spa3[spell_var]' value='$spa3[spell_id]'"; if($spa3['spell_id'] == $_POST['spell7'] || $spa3['spell_id'] == $_POST['spell8'] || $spa3['spell_id'] == $_POST['spell9'] || $spa3['spell_id'] == $char['spell7'] || $spa3['spell_id'] == $char['spell8'] || $spa3['spell_id'] == $char['spell9']){echo " checked";} print "> $spa3[spell_name]<br>";
        }
      }else {print "One more Level 2 Common Spell is required to purchase a Level 3 Common Spell<br>";}
     }
    else {print "A Level 2 Common Spell is required to purchase a Level 3 Common Spell<br>";}
    }
    }
   print "</td>";
   print "<td style='border-right: solid 1px #FFFFFF;' valign='top'><u>Level One</u><br><br>";
   if($groups){$get_spa4 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='1' ORDER BY `spell_name` ASC");}else{$get_spa4 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='1' AND `spell_id`!='$char[spell10]' AND `spell_id`!='$char[spell11]' AND `spell_id`!='$char[spell12]' AND `spell_id`!='$char[spell13]' AND `spell_id`!='$char[spell14]' AND `spell_id`!='$char[spell15]' ORDER BY `spell_name` ASC");}
   while($spa4 = mysql_fetch_assoc($get_spa4))
    {
    print "<input type='checkbox' name='spell$spa4[spell_var]' value='$spa4[spell_id]'"; if($spa4['spell_id'] == $_POST['spell10'] || $spa4['spell_id'] == $_POST['spell11'] || $spa4['spell_id'] == $_POST['spell12'] || $spa4['spell_id'] == $_POST['spell13'] || $spa4['spell_id'] == $_POST['spell14'] || $spa4['spell_id'] == $_POST['spell15'] || $spa4['spell_id'] == $char['spell10'] || $spa4['spell_id'] == $char['spell11'] || $spa4['spell_id'] == $char['spell12'] || $spa4['spell_id'] == $char['spell13'] || $spa4['spell_id'] == $char['spell14'] || $spa4['spell_id'] == $char['spell15']){echo " checked";} print "> $spa4[spell_name]<br>";
    }  
   if($groups)
    {
    if($lvl1 == '2' || $lvl1 == '3')
     {
     print "<br><u>Level Two</u><br><br>";
     if($other1 != '0' && $other1 >= $other2)
       {
       $get_spa5 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='2' ORDER BY `spell_name` ASC");
       while($spa5 = mysql_fetch_assoc($get_spa5))
        {
        print "<input type='checkbox' name='spell$spa5[spell_var]' value='$spa5[spell_id]'"; if($spa5['spell_id'] == $_POST['spell16'] || $spa5['spell_id'] == $_POST['spell17'] || $spa5['spell_id'] == $_POST['spell18'] || $spa5['spell_id'] == $_POST['spell19'] || $spa5['spell_id'] == $_POST['spell20'] || $spa5['spell_id'] == $_POST['spell21'] || $spa5['spell_id'] == $char['spell16'] || $spa5['spell_id'] == $char['spell17'] || $spa5['spell_id'] == $char['spell18'] || $spa5['spell_id'] == $char['spell19'] || $spa5['spell_id'] == $char['spell20'] || $spa5['spell_id'] == $char['spell21']){echo " checked";} print "> $spa5[spell_name]<br>";
        }
       }else {print "One more Level 1 $char[path] Spell is required to purchase a Level 2 $char[path] Spell<br>";}
     }
    if($lvl1 == '3')
     {
     print "<br><u>Level Three</u><br><br>";
     if($other2 != '0' && $other2 >= $other3)
       {
       $get_spa6 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='3' ORDER BY `spell_name` ASC");
       while($spa6 = mysql_fetch_assoc($get_spa6))
        {
        print "<input type='checkbox' name='spell$spa6[spell_var]' value='$spa6[spell_id]'"; if($spa6['spell_id'] == $_POST['spell22'] || $spa6['spell_id'] == $_POST['spell23'] || $spa6['spell_id'] == $_POST['spell24'] || $spa6['spell_id'] == $_POST['spell25'] || $spa6['spell_id'] == $_POST['spell26'] || $spa6['spell_id'] == $_POST['spell27'] || $spa6['spell_id'] == $char['spell22'] || $spa6['spell_id'] == $char['spell23'] || $spa6['spell_id'] == $char['spell24'] || $spa6['spell_id'] == $char['spell25'] || $spa6['spell_id'] == $char['spell26'] || $spa6['spell_id'] == $char['spell27']){echo " checked='checked'";} print "> $spa6[spell_name]<br>";
        }
       }else {print "One more Level 2 $char[path] Spell is required to purchase a Level 3 $char[path] Spell<br>";}
     }
    }
   else // not in groups
    {
    if($lvl1 == '2' || $lvl1 == '3')
     {
     print "<br><u>Level Two</u><br><br>";
     if($other1 != '0')
      {
      if($other1 > $other2)
       {
       $get_spa5 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='2' AND `spell_id`!='$char[spell16]' AND `spell_id`!='$char[spell17]' AND `spell_id`!='$char[spell18]' AND `spell_id`!='$char[spell19]' AND `spell_id`!='$char[spell20]' AND `spell_id`!='$char[spell21]' ORDER BY `spell_name` ASC");
       while($spa5 = mysql_fetch_assoc($get_spa5))
        {
        print "<input type='checkbox' name='spell$spa5[spell_var]' value='$spa5[spell_id]'"; if($spa5['spell_id'] == $_POST['spell16'] || $spa5['spell_id'] == $_POST['spell17'] || $spa5['spell_id'] == $_POST['spell18'] || $spa5['spell_id'] == $_POST['spell19'] || $spa5['spell_id'] == $_POST['spell20'] || $spa5['spell_id'] == $_POST['spell21'] || $spa5['spell_id'] == $char['spell16'] || $spa5['spell_id'] == $char['spell17'] || $spa5['spell_id'] == $char['spell18'] || $spa5['spell_id'] == $char['spell19'] || $spa5['spell_id'] == $char['spell20'] || $spa5['spell_id'] == $char['spell21']){echo " checked";} print "> $spa5[spell_name]<br>";
        }
       }else {print "One more Level 1 $char[path] Spell is required to purchase a Level 2 $char[path] Spell<br>";}
      }
     }
    if($lvl1 == '3')
     {
     print "<br><u>Level Three</u><br><br>";
     if($other2 != '0')
      {
      if($other2 > $other3)
       {
       $get_spa6 = mysql_query("SELECT * FROM `characters_spells` WHERE `spell_type`='$path' AND `spell_level`='3' AND `spell_id`!='$char[spell22]' AND `spell_id`!='$char[spell23]' AND `spell_id`!='$char[spell24]' AND `spell_id`!='$char[spell25]' AND `spell_id`!='$char[spell26]' AND `spell_id`!='$char[spell27]' ORDER BY `spell_name` ASC");
       while($spa6 = mysql_fetch_assoc($get_spa6))
        {
        print "<input type='checkbox' name='spell$spa6[spell_var]' value='$spa6[spell_id]'"; if($spa6['spell_id'] == $_POST['spell22'] || $spa6['spell_id'] == $_POST['spell23'] || $spa6['spell_id'] == $_POST['spell24'] || $spa6['spell_id'] == $_POST['spell25'] || $spa6['spell_id'] == $_POST['spell26'] || $spa6['spell_id'] == $_POST['spell27']){echo " checked='checked'";} print "> $spa6[spell_name]<br>";
        }
       }else {print "One more Level 2 $char[path] Spell is required to purchase a Level 3 $char[path] Spell<br>";}
      }
     }
    }
   print "</td></tr>";
   print "</table>";
  print "</td></tr>";

  print "<tr><td></td><td>";
   if($groups){print "<input type='checkbox' name='pp_manage' value='Y'"; if(!empty($_POST['pp_manage']) && $_POST['pp_manage'] == 'Y'){echo "checked";} print "/ > Manage Players Points <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>If you removed Spells, a refund will be issued. If you added Spells, player point expenditure is waived. This management will apply for changes to any and all Spells. </span></a></p><br><br>";}

  print "<input type='hidden' name='user_id' value='$pp[user_id]'>
<input type='hidden' name='player_points' value='$pp[player_points]'>
<input type='hidden' name='player_name' value='$player[pf_real_name]'>
<input type='hidden' name='ppearned_spent' value='$char[ppearned_spent]'>
<input type='hidden' name='edit_type' value='$edit_type'>
<input type='hidden' name='char_id' value='$charid'>
<input type='hidden' name='char_name' value='$char[char_name]'>
<input type='hidden' name='path' value='$char[path]'>
<input type='hidden' name='spell1_cur' value='$char[spell1]'>
<input type='hidden' name='spell2_cur' value='$char[spell2]'>
<input type='hidden' name='spell3_cur' value='$char[spell3]'>
<input type='hidden' name='spell4_cur' value='$char[spell4]'>
<input type='hidden' name='spell5_cur' value='$char[spell5]'>
<input type='hidden' name='spell6_cur' value='$char[spell6]'>
<input type='hidden' name='spell7_cur' value='$char[spell7]'>
<input type='hidden' name='spell8_cur' value='$char[spell8]'>
<input type='hidden' name='spell9_cur' value='$char[spell9]'>
<input type='hidden' name='spell10_cur' value='$char[spell10]'>
<input type='hidden' name='spell11_cur' value='$char[spell11]'>
<input type='hidden' name='spell12_cur' value='$char[spell12]'>
<input type='hidden' name='spell13_cur' value='$char[spell13]'>
<input type='hidden' name='spell14_cur' value='$char[spell14]'>
<input type='hidden' name='spell15_cur' value='$char[spell15]'>
<input type='hidden' name='spell16_cur' value='$char[spell16]'>
<input type='hidden' name='spell17_cur' value='$char[spell17]'>
<input type='hidden' name='spell18_cur' value='$char[spell18]'>
<input type='hidden' name='spell19_cur' value='$char[spell19]'>
<input type='hidden' name='spell20_cur' value='$char[spell20]'>
<input type='hidden' name='spell21_cur' value='$char[spell21]'>
<input type='hidden' name='spell22_cur' value='$char[spell22]'>
<input type='hidden' name='spell23_cur' value='$char[spell23]'>
<input type='hidden' name='spell24_cur' value='$char[spell24]'>
<input type='hidden' name='spell25_cur' value='$char[spell25]'>
<input type='hidden' name='spell26_cur' value='$char[spell26]'>
<input type='hidden' name='spell27_cur' value='$char[spell27]'>
<input type='hidden' name='S_ID' value='buyspells'>";
if($char['char_status'] != '2' || $groups){print "<input type='submit' name='submit' value='Submit'>";}
else{print "<strong>ARCHIVED</strong></div>";}
  print "</td></tr></table></form><br><br>";
   }else {print "This Character does not have the Witch Skill required to buy Spells.";} // Witch skill check
  }
 elseif($edit_type == 'buyitems') // buy items
  {
  print "<h2 style='margin-top: 5px;'>Buy Items: $char[char_name]</h2>";
  print "<form action='' method='post'>";
  print "<table width='825' border='0' align='center' cellspacing='0' cellpadding='3' id='cm_table'><tr><td>";
  


  print "</td></tr></table>";
  }
 }
 print "</div></div></div>";
include ('character-footer.php'); 
?>