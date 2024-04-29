<?php
if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step1') // Basics
 {
 $errors = "0";
 if(empty($_POST['char_name'])){$error = "Empty field. A name for the character is required."; $errors = "1";}
 if($errors != '1')
  {
  $_REQUEST['step'] = "2";
  }
 }

if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step2') // Skills
 {
 if($_POST['submit'] == 'Back'){$_REQUEST['step'] = "1";}
 else
  {
  $errors = "0";
  if($_POST['race_blood'] == 'Y'){$blood = "3";}else{$blood = "0";}

  $skill1 = $_POST['skill1'];
  if($skill1 == '2' || $skill1 == '14'){$skill1_lvl = "1";}else{$skill1_lvl = $_POST['skill1_lvl'];}

  if(!empty($_POST['skill2']))
   {
   $skill2 = $_POST['skill2'];
   if($skill2 == '2' || $skill2 == '14'){$skill2_lvl = "1";}else{if(empty($_POST['skill2_lvl'])){$_POST['skill2_lvl'] = "1";}else{$_POST['skill2_lvl'] = $_POST['skill2_lvl'];} $skill2_lvl = $_POST['skill2_lvl'];}
   }

  if(!empty($_POST['skill3']))
   {
   $skill3 = $_POST['skill3'];
   if($skill3 == '2' || $skill3 == '14'){$skill3_lvl = "1";}else{if(empty($_POST['skill3_lvl'])){$_POST['skill3_lvl'] = "1";}else{$_POST['skill3_lvl'] = $_POST['skill3_lvl'];} $skill3_lvl = $_POST['skill3_lvl'];}
   }

  if(!empty($_POST['skill4']))
   {
   $skill4 = $_POST['skill4'];
   if($skill4 == '2' || $skill4 == '14'){$skill4_lvl = "1";}else{if(empty($_POST['skill4_lvl'])){$_POST['skill4_lvl'] = "1";}else{$_POST['skill4_lvl'] = $_POST['skill4_lvl'];} $skill4_lvl = $_POST['skill4_lvl'];}
   }

  $free_skill = $skill1_lvl - 1;
  $skill_total = $free_skill + $skill2_lvl + $skill3_lvl + $skill4_lvl + $blood;

  if($skill_total > $_POST['pp'])
   {
   if($blood != '3'){$error = "The cost of your selected Skill levels, $skill_total points, exceeds your available Player Points of $_POST[pp]."; $errors = "1"; }
   else{$error = "The cost of your selected Skill levels including Pure Blood, $skill_total points, exceeds your available Player Points of $_POST[pp]."; $errors = "1"; }
   $_REQUEST['step'] = "2";
   }

  if(!empty($skill2) || !empty($skill3) || !empty($skill4))
   {
   if($skill1 == $skill2 || $skill1 == $skill3 || $skill1 == $skill4){$error = "Duplicate Skills selected."; $errors = "1"; $_REQUEST['step'] = "2";}
   if(!empty($skill2) && $skill2 == $skill3 || $skill2 == $skill4){$error = "Duplicate Skills selected."; $errors = "1"; $_REQUEST['step'] = "2";}
   if(!empty($skill3) && $skill3 == $skill4){$error = "Duplicate Skills selected."; $errors = "1"; $_REQUEST['step'] = "2";}
   }

  if($skill1 == '12' || $skill2 == '12' || $skill3 == '12' || $skill4 == '12')
   {
   if($skill1 == '1' || $skill2 == '1' || $skill3 == '1' || $skill4 == '1'){$error = "You cannot have both the Witch and Alchemist skills. Please choose one."; $errors = "1"; $_REQUEST['step'] = "2";}
   }

  if($errors != '1')
   {
   $_REQUEST['step'] = "3";
   }
  }
 }

if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step3') // Paths
 {
 if($_POST['submit'] == 'Back'){$_REQUEST['step'] = "2";}
 else
  {
  $errors = "0";
  if(!empty($_POST['path']) && $_POST['post'] == '1')
   {
   $error = "You may now select a Skill Path."; $errors = "1"; $_REQUEST['step'] = "3";
   }

  if(!empty($_POST['path_cur']) && $_POST['path_cur'] != $_POST['path'])
   {
   $error = "You have changed your Witch Path that changes the Skill Paths available."; $errors = "1"; $_REQUEST['step'] = "3";
   }

  if(!empty($_POST['skill_path']))
   {
   $path_total = $_POST['skill_path_lvl'] * $CF_OZ_PATH_PP;
   if($path_total > $_POST['pp']){$error = "The cost of your selected Skill Path level, $pathcost points, exceeds your available Player Points of $_POST[pp]."; $errors = "1"; $_REQUEST['step'] = "3";}
   }

  if($errors != '1')
   {
   $_REQUEST['step'] = "4";
   }
  }
 }

if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step4') // Spells
 {
 $errors = "0";
 if($_POST['submit'] == 'Back'){$_REQUEST['step'] = "3";}
 else
  {
  $errors = "0";

  if(!empty($_POST['spell_check']) && $_POST['spell_check'] == 'Y')
   {
  if(empty($_POST['spell1']) && empty($_POST['spell2']) && empty($_POST['spell3']) && empty($_POST['spell10']) && empty($_POST['spell11']) && empty($_POST['spell12']) && empty($_POST['spell13']) && empty($_POST['spell14']) && empty($_POST['spell15'])){$error = "Please select at least one Level 1 Spell."; $errors = "1"; $_REQUEST['step'] = "4";}

  if(!empty($_POST['spell1'])){$spell_cost1 = "1";}
  if(!empty($_POST['spell2'])){$spell_cost2 = "1";} 
  if(!empty($_POST['spell3'])){$spell_cost3 = "1";} 
  if(!empty($_POST['spell4'])){$spell_cost4 = "1";} 
  if(!empty($_POST['spell5'])){$spell_cost5 = "1";} 
  if(!empty($_POST['spell6'])){$spell_cost6 = "1";} 
  if(!empty($_POST['spell7'])){$spell_cost7 = "1";} 
  if(!empty($_POST['spell8'])){$spell_cost8 = "1";} 
  if(!empty($_POST['spell9'])){$spell_cost9 = "1";}
  if(!empty($_POST['spell10'])){$spell_cost10 = "1";}
  if(!empty($_POST['spell11'])){$spell_cost11 = "1";}
  if(!empty($_POST['spell12'])){$spell_cost12 = "1";}
  if(!empty($_POST['spell13'])){$spell_cost13 = "1";}
  if(!empty($_POST['spell14'])){$spell_cost14 = "1";}
  if(!empty($_POST['spell15'])){$spell_cost15 = "1";}
  if(!empty($_POST['spell16'])){$spell_cost16 = "1";}
  if(!empty($_POST['spell17'])){$spell_cost17 = "1";}
  if(!empty($_POST['spell18'])){$spell_cost18 = "1";}
  if(!empty($_POST['spell19'])){$spell_cost19 = "1";}
  if(!empty($_POST['spell20'])){$spell_cost20 = "1";}
  if(!empty($_POST['spell21'])){$spell_cost21 = "1";}
  if(!empty($_POST['spell22'])){$spell_cost22 = "1";}
  if(!empty($_POST['spell23'])){$spell_cost23 = "1";}
  if(!empty($_POST['spell24'])){$spell_cost24 = "1";}
  if(!empty($_POST['spell25'])){$spell_cost25 = "1";}
  if(!empty($_POST['spell26'])){$spell_cost26 = "1";}
  if(!empty($_POST['spell27'])){$spell_cost27 = "1";}

  $common_lvl1 = $spell_cost1 + $spell_cost2 + $spell_cost3;
  $common_lvl2 = $spell_cost4 + $spell_cost5 + $spell_cost6;
  $common_lvl3 = $spell_cost7 + $spell_cost8 + $spell_cost9;

  if($common_lvl2 > $common_lvl1){$error = "The number of Level 2 Common spells exceeds the number of Level 1 Common spells."; $errors = "1"; $_REQUEST['step'] = "4";}

  if($common_lvl3 > $common_lvl2){$error = "The number of Level 3 Common spells exceeds the number of Level 2 Common spells."; $errors = "1"; $_REQUEST['step'] = "4";}

  $other_lvl1 = $spell_cost10 + $spell_cost11 + $spell_cost12 + $spell_cost13 + $spell_cost14 + $spell_cost15;
  $other_lvl2 = $spell_cost16 + $spell_cost17 + $spell_cost18 + $spell_cost19 + $spell_cost20 + $spell_cost21;
  $other_lvl3 = $spell_cost22 + $spell_cost23 + $spell_cost24 + $spell_cost25 + $spell_cost26 + $spell_cost27;

  if($other_lvl2 > $other_lvl1){$error = "The number of Level 2 $_POST[path] spells exceeds the number of Level 1 $_POST[path] spells."; $errors = "1"; $_REQUEST['step'] = "4";}

  if($other_lvl3 > $other_lvl2){$error = "The number of Level 3 $_POST[path] spells exceeds the number of Level 2 $_POST[path] spells."; $errors = "1"; $_REQUEST['step'] = "4";}

  $freespell = $common_lvl1 + $other_lvl1;
  $freespell = $freespell - 1;
  $spell_total = $freespell + $common_lvl2 + $other_lvl2 + $common_lvl3 + $other_lvl3;

  if($spell_total > $_POST['pp']){$error = "The cost of your selected Spells, $spell_total points, exceeds your available Player Points of $_POST[pp]."; $errors = "1"; $_REQUEST['step'] = "4";}  
   }
  if($errors != '1')
   {
   $_REQUEST['step'] = "5";
   }
  }
 }

if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step5') // Items
 {
 $errors = "0";
 if($_POST['submit'] == 'Back'){$_REQUEST['step'] = "4";}
 else
  {

  if($errors != '1')
   {
   $_REQUEST['step'] = "6";
   }
  }
 }

if(!empty($_POST['S_ID']) &&  $_POST['S_ID'] == 'step6') // Finishing Touches
 {
 $errors = "0";
 if($_POST['submit'] == 'Back'){$_REQUEST['step'] = "5";}
 else
  {
  $status = "1";
  $magic = $_POST['magic'];
  if($_POST['race'] == '5'){$pp_new = "7";}else{$pp_new = "5";}
  $totalspent = $_POST['skill_total'] + $_POST['spell_total'] + $_POST['item_total'];
  $spent = $totalspent - $pp_new;
  if($spent < '1'){$spent = "0";}else{$spent = $spent;}
  $pp_update = $_POST['player_points'] - $spent;
  $racename = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_races` WHERE `race_id`='$_POST[race]' LIMIT 1"));

  $insert = mysql_query("INSERT INTO `characters` 
(`user_id`, `char_status`, `char_name`, `race`, `race_name`, `race_blood`, `shapeshift`, `ability`, `restriction`, `skill_path`, `skill_path_lvl`, `skill1`, `skill2`, `skill3`, `skill4`, `skill1_lvl`, `skill2_lvl`, `skill3_lvl`, `skill4_lvl`, `magic_points`, `path`, `spell1`, `spell2`, `spell3`, `spell4`, `spell5`, `spell6`, `spell7`, `spell8`, `spell9`, `spell10`, `spell11`, `spell12`, `spell13`, `spell14`, `spell15`, `spell16`, `spell17`, `spell18`, `spell19`, `spell20`, `spell21`, `spell22`, `spell23`, `spell24`, `spell25`, `spell26`, `spell27`, `create_date`, `sort_date`, `pptotal_spent`, `ppearned_spent`) VALUES ('$_POST[user_id]', '$status', '$_POST[char_name]', '$_POST[race]', '$racename[race_name]', '$_POST[race_blood]', '$_POST[shapeshift]', '$_POST[ability]', '$_POST[restriction]', '$_POST[skill_path]', '$_POST[skill_path_lvl]', '$_POST[skill1]', '$_POST[skill2]', '$_POST[skill3]', '$_POST[skill4]', '$_POST[skill1_lvl]', '$_POST[skill2_lvl]', '$_POST[skill3_lvl]', '$_POST[skill4_lvl]', '$magic', '$_POST[path]', '$_POST[spell1]', '$_POST[spell2]', '$_POST[spell3]', '$_POST[spell4]', '$_POST[spell5]', '$_POST[spell6]', '$_POST[spell7]', '$_POST[spell8]', '$_POST[spell9]', '$_POST[spell10]', '$_POST[spell11]', '$_POST[spell12]', '$_POST[spell13]', '$_POST[spell14]', '$_POST[spell15]', '$_POST[spell16]', '$_POST[spell17]', '$_POST[spell18]', '$_POST[spell19]', '$_POST[spell20]', '$_POST[spell21]', '$_POST[spell22]', '$_POST[spell23]', '$_POST[spell24]', '$_POST[spell25]', '$_POST[spell26]', '$_POST[spell27]', '$date_format', '$date', '$totalspent', '$spent')"); 
  if(!$insert){$error = "Your character could not be added to the database. Please contact the Web Manager about this error."; $_REQUEST['step'] = "6";}
  else
   { 
   $get_id = mysql_fetch_assoc(mysql_query("SELECT char_id,char_name,user_id FROM `characters` WHERE `char_name`='$_POST[char_name]' AND `user_id`='$_POST[user_id]' LIMIT 1"));

   $editdetail = "Created Character: ".$_POST['char_name'];
   $insert_pplog = mysql_query("INSERT INTO `characters_pplog` (`char_id`,`char_name`, `user_id`, `pp_detail`, `pp_date`, `pp_author`, `sort_date`, `pp_skill`, `pp_spell`, `ppearned_spent`, `ppbonus_spent`, `pp_totalspent`) VALUES ('$get_id[char_id]','$_POST[char_name]', '$_POST[user_id]', '$editdetail', '$date_format', '$loggedin[username]', '$date', '$_POST[skill_total]', '$_POST[spell_total]', '$spent', '$pp_new', '$totalspent')");

   $update_pp = mysql_query("UPDATE `bb_users` SET `player_points`='$pp_update' WHERE `user_id`='$_POST[user_id]' LIMIT 1");
   header("Location: character.php?m=6");
   exit();
   }
  }
 }

?>
