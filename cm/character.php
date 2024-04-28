<?php
include ('character-header.php'); 

if(!empty($_GET['page'])){$page = $_GET['page'];}else{$page = "1";}

if(!empty($access)){print "<br><div id='error'> $access</div><br><br>";}
else
 {
 if(!empty($error)){print "<br><div id='error'> $error</div><br>";}

 if(!empty($_GET['m']))
  {
  print "<br><div id='success'>";
  if($_GET['m'] == '1'){print "The character background approval notifications have been sent.";}
  if($_GET['m'] == '2'){print "The character background denial notifications have been sent.";}
  if($_GET['m'] == '3'){print "The rules configuration has been updated.";}
  if($_GET['m'] == '4'){print "The Player Points were updated.";}
  if($_GET['m'] == '5'){print "The rule books have been updated.";}
  if($_GET['m'] == '6'){print "The new character has been added.";}
  if($_GET['m'] == '7'){print "The character has been deleted.";}
  if($_GET['m'] == '8'){print "The newspaper has been uploaded.";}
  print "</div><br>";
  }

 $userid = $loggedin['user_id'];
 $check_perm = mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$userid'");
 while($gr = mysql_fetch_assoc($check_perm))
  {
  if($gr['group_id'] == '9'){$rules = "1";}
  if($gr['group_id'] == '10'){$st = "1";}
  if($gr['group_id'] == '11'){$log = "1";}
  if($gr['group_id'] == '12'){$org = "1";}
  if($gr['group_id'] == '14'){$cg = "1";}
  if($gr['group_id'] == '15'){$deco = "1";}
  if($gr['group_id'] == '20'){$webmgr = "1";}
  if($gr['group_id'] == '21'){$paper = "1";}
  }

 if(empty($_GET['mode'])){$modeid = $userid;}else{$modeid = $_GET['mode'];}
 $getchar = mysql_query("SELECT * FROM `characters` WHERE `user_id`='$modeid' AND `char_status`!='2' ORDER BY `char_name` ASC");
 $modepp = mysql_fetch_assoc(mysql_query("SELECT user_id,player_points FROM `bb_users` WHERE `user_id`='$modeid' LIMIT 1"));
 $modename = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$modeid'"));

 if($webmgr == '1' || $org == '1' || $paper == '1') 
  {
  print "<br><div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
  print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>Stronghold Gazette</dt></dl></li></ul> <ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>";
  print " <table border='0' cellspacing='3' cellpadding='3' width='800' style='color: #000000;'>";
  print "<tr><td width='700'><strong>Upload:</strong> <form action='' method='post' enctype='multipart/form-data' style='display:inline;'>
Volume: <select name='vol'>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
<option value='5'>5</option>
<option value='6'>6</option>
<option value='7'>7</option>
<option value='8'>8</option>
<option value='9'>9</option>
<option value='10'>10</option>
</select> Number: <select name='num'>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
<option value='4'>4</option>
<option value='5'>5</option>
<option value='6'>6</option>
</select> <input type='file' name='news'> <input type='hidden' id='S_ID' name='S_ID' value='newspaper'><input type='submit' id='submit' name='submit' value='Submit'> <p id='tooltip1'><a href='#'><img border='0' src='/images/icon-question.jpg'><span>Uploading a file with the exact same Volume and Number as an already current uploaded file will replace the current file.</span></a></p></form> </td><td width='100'><strong><a target='_blank' href='/oz.php?page=wiki&forum_id=48'>View Gazette List</a></strong></td></tr>";
  print "</tr></table></ul><span class='corners-bottom'><span></div></div><br>";
  }

 if($webmgr == '1' || $org == '1' || $cg == '1') 
  {
  print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
 print "<ul class='topiclist'><li class='header'><dl class='icon'><dt style='padding: 2px 0;'>Character Guide</dt>";
 print "<dd class='topics' style=' width: 170px;'>Player</dd><dd class='topics'>Text</dd><dd class='posts'>PDF</dd><dd class='lastpost' style=' width: 100px;'><span>Date Submitted</span></dd></dl></li></ul>";
 print "<form method='post' action=''>";
 print "<ul class='topiclist forums'>";
 $getbg = mysql_query("SELECT * FROM `characters` WHERE `bg_approved`!='Y' AND `char_status`!='2' ORDER BY `char_name` ASC");
 while($bg = mysql_fetch_assoc($getbg))
  {
  if(!empty($bg['bg_approved']) && $bg['bg_approved'] == 'S')
   {
  $uname = mysql_fetch_assoc(mysql_query("SELECT user_id,username FROM `bb_users` WHERE `user_id`='$bg[user_id]' LIMIT 1"));
  if(!empty($bg['edit_date'])){$bgdate = $bg['edit_date'];}else{$bgdate = $bg['create_date'];}
  if(!empty($bg['background'])){$bgtext = "<img border='0' src='/images/icon-text-on.gif' />";}else{$bgtext = "<img border='0' src='/images/icon-text-off.gif' />";}
  if(!empty($bg['background_file'])){$bgfile = "<a href='".$bg['background_file']."' target='_blank'><img border='0' src='/images/icon-dwn-on.png' /></a>";}else{$bgfile = "<img border='0' src='/images/icon-dwn-off.png' />";}
  print "<li class='row' style='padding: 5px; height: 20px;'><dl class='icon' style='height: 20px;'>";
  print "<dt title='Background Approval' style='padding: 0;'><input type='checkbox' name='check[]' value='$bg[char_id]' /> $bg[char_name] <dd class='topics' style='padding-top: 0; height: 16px; margin-top: -5px; width: 170px;'><a target='_blank' href='/forum/memberlist.php?mode=viewprofile&u=$bg[user_id]'>$uname[username]</a></dd>";
  print "<dd class='topics' style='padding-top: 0; height: 16px; margin-top: -5px;'><a target='_blank' href='character-bg.php?id=$bg[char_id]' onClick='showPopup(this.href);return(false);'>$bgtext</a></dd>";
  print "<dd class='posts' style='padding-top: 0; height: 16px; margin-top: -5px;'>$bgfile</dd>";
  print "<dd class='lastpost' style='padding-top: 0; width: 100px;'><span>$bgdate</span></dd>";
  print "</dt></dl></li>";
   }
  }
  print "</ul><ul class='topiclist forums' style='padding: 5px;'><input type='radio' name='approve' value='Y'> Approve Checked  <input type='radio' name='approve' value='N'> Deny Checked <input type='hidden' name='S_ID' value='cg_background'> <input type='submit' name='submit' value='Submit'></form> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='/forum/viewtopic.php?f=50&t=360' target='_blank'>Documentation</a></ul><span class='corners-bottom'><span></span></span></div></div><br>";
  }

 if($webmgr == '1' || $org == '1' || $log == '1') 
  { ?>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>	

<script type="text/javascript">
$(function() {
//autocomplete
$(".auto").autocomplete({
source: "admin-search-script.php",
minLength: 1
});	
});
</script>

<?php
  print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
  print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>Logistics</dt></dl></li></ul> <ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>";
  print " <table border='0' cellspacing='3' cellpadding='3' width='800' style='color: #000000;'>";
  print "<tr><td width='800'><strong>QUICK PLAYER POINTS</strong></td></tr>";
  print "<tr><td><form method='post' action=''>
<input type='radio' name='type' value='add'"; if(empty($_POST['type']) || $_POST['type'] == 'add'){print " checked=checked";} print "> Add
<input type='radio' name='type' value='del'"; if(!empty($_POST['type']) && $_POST['type'] == 'del'){print " checked=checked";} print "> Delete 
<input type='text' name='points' size='2' value='"; if(!empty($_POST['points'])){echo $_POST['points'];} print "'> Points for
<select name='reason'>
<option value='1'"; if(!empty($_POST['reason']) && $_POST['reason'] == '1'){print " selected";} print ">Attendence</option> 
<option value='2'"; if(!empty($_POST['reason']) && $_POST['reason'] == '2'){print " selected";} print ">Staff</option>
<option value='3'"; if(!empty($_POST['reason']) && $_POST['reason'] == '3'){print " selected";} print ">Correction</option>
<option value='4'"; if(!empty($_POST['reason']) && $_POST['reason'] == '4'){print " selected";} print ">Donation</option>";
if($webmgr == '1'){print "<option value='5'"; if(!empty($_POST['reason']) && $_POST['reason'] == '5'){print " selected";} print ">Web Mgr Test</option>";}
print "</select> for 
<input type='text' name='player' class='auto' value='"; if(!empty($_POST['player'])){echo $_POST['player'];} print "'> Player <div id='small'>(Case Sensitive)</div> 
<input type='hidden' name='S_ID' value='playerpoints'><input type='submit' name='Submit' value='Submit'></form>";
  print "</td></tr>";
  print "<tr><td><strong>REPORTS: </strong>";

  $name = "Active Characters";
  $year = date('Y');

//  print "<table cellpadding=2 cellspacing=0>";
  $body = "";
  $header =  "<tr bgcolor=#CCCCCC>
  <td width=100><strong>Name</strong></td>
  <td width=100><strong>Player</strong></td>
  <td width=100><strong>Species</strong></td>
  <td width=100><strong>Pure</strong></td>
  <td width=100><strong>Ability</strong></td>
  <td width=100><strong>Restriction</strong></td>
  <td width=100><strong>Skill Path</strong></td>
  <td width=100><strong>Skill 1</strong></td>
<td width=100><strong>Skill 2</strong></td>
<td width=100><strong>Skill 3</strong></td>
<td width=100><strong>Skill 4</strong></td>
<td width=100><strong>Witch Path</strong></td>
<td width=100><strong>Magic</strong></td>
<td width=100><strong>Common Spells</strong></td>
<td width=100><strong>Path Spells</strong></td>
<td width=100><strong>Created</strong></td>
<td width=100><strong>Updated</strong></td>
</tr>";

  $color="1";
  $get_report = mysql_query("SELECT * FROM `characters` WHERE `char_status`='1' OR `char_status`='Approved' ORDER BY `char_name` ASC");
  $count = mysql_num_rows($get_report);
  while($profile = mysql_fetch_assoc($get_report))
   {
   $get_player = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_profile_fields_data` WHERE `user_id`='$profile[user_id]' LIMIT 1"));
   $get_species = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_races` WHERE `race_id`='$profile[race]' LIMIT 1"));
   $get_skillpath = mysql_fetch_assoc(mysql_query("SELECT * FROM `characters_skillpaths` WHERE `skillpath_id`='$profile[skill_path]' LIMIT 1"));
   $get_skills = mysql_query("SELECT * FROM `characters_skills`");
   while($skillname = mysql_fetch_assoc($get_skills))
    {
    if($profile['skill1'] == $skillname['skill_id']){$skill1 = $skillname['skill_name'];}
    if(!empty($profile['skill2_lvl'])){if($profile['skill2'] == $skillname['skill_id']){$skill2 = $skillname['skill_name'];}}else{$skill2 = "";}
    if(!empty($profile['skill3_lvl'])){if($profile['skill3'] == $skillname['skill_id']){$skill3 = $skillname['skill_name'];}}else{$skill3 = "";}
    if(!empty($profile['skill4_lvl'])){if($profile['skill4'] == $skillname['skill_id']){$skill4 = $skillname['skill_name'];}}else{$skill4 = "";}
    }
   $get_spells = mysql_query("SELECT * FROM `characters_spells`");
   while($spellname = mysql_fetch_assoc($get_spells))
    {
    if(!empty($profile['spell1'])){if($profile['spell1'] == $spellname['spell_id']){$spell1 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell1 = "";}
    if(!empty($profile['spell2'])){if($profile['spell2'] == $spellname['spell_id']){$spell2 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell2 = "";}
    if(!empty($profile['spell3'])){if($profile['spell3'] == $spellname['spell_id']){$spell3 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell3 = "";}
    if(!empty($profile['spell4'])){if($profile['spell4'] == $spellname['spell_id']){$spell4 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell4 = "";}
    if(!empty($profile['spell5'])){if($profile['spell5'] == $spellname['spell_id']){$spell5 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell5 = "";}
    if(!empty($profile['spell6'])){if($profile['spell6'] == $spellname['spell_id']){$spell6 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell6 = "";}
    if(!empty($profile['spell7'])){if($profile['spell7'] == $spellname['spell_id']){$spell7 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell7 = "";}
    if(!empty($profile['spell8'])){if($profile['spell8'] == $spellname['spell_id']){$spell8 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell8 = "";}
    if(!empty($profile['spell9'])){if($profile['spell9'] == $spellname['spell_id']){$spell9 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell9 = "";}
    if(!empty($profile['spell10'])){if($profile['spell10'] == $spellname['spell_id']){$spell10 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell10 = "";}
    if(!empty($profile['spell11'])){if($profile['spell11'] == $spellname['spell_id']){$spell11 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell11 = "";}
    if(!empty($profile['spell12'])){if($profile['spell12'] == $spellname['spell_id']){$spell12 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell12 = "";}
    if(!empty($profile['spell13'])){if($profile['spell13'] == $spellname['spell_id']){$spell13 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell13 = "";}
    if(!empty($profile['spell14'])){if($profile['spell14'] == $spellname['spell_id']){$spell14 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell14 = "";}
    if(!empty($profile['spell15'])){if($profile['spell15'] == $spellname['spell_id']){$spell15 = "Lvl 1: ".$spellname['spell_name']."<br>";}}else{$spell15 = "";}
    if(!empty($profile['spell16'])){if($profile['spell16'] == $spellname['spell_id']){$spell16 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell16 = "";}
    if(!empty($profile['spell17'])){if($profile['spell17'] == $spellname['spell_id']){$spell17 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell17 = "";}
    if(!empty($profile['spell18'])){if($profile['spell18'] == $spellname['spell_id']){$spell18 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell18 = "";}
    if(!empty($profile['spell19'])){if($profile['spell19'] == $spellname['spell_id']){$spell19 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell19 = "";}
    if(!empty($profile['spell20'])){if($profile['spell20'] == $spellname['spell_id']){$spell20 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell20 = "";}
    if(!empty($profile['spell21'])){if($profile['spell21'] == $spellname['spell_id']){$spell21 = "Lvl 2: ".$spellname['spell_name']."<br>";}}else{$spell21 = "";}
    if(!empty($profile['spell22'])){if($profile['spell22'] == $spellname['spell_id']){$spell22 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell22 = "";}
    if(!empty($profile['spell23'])){if($profile['spell23'] == $spellname['spell_id']){$spell23 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell23 = "";}
    if(!empty($profile['spell24'])){if($profile['spell24'] == $spellname['spell_id']){$spell24 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell24 = "";}
    if(!empty($profile['spell25'])){if($profile['spell25'] == $spellname['spell_id']){$spell25 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell25 = "";}
    if(!empty($profile['spell26'])){if($profile['spell26'] == $spellname['spell_id']){$spell26 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell26 = "";}
    if(!empty($profile['spell27'])){if($profile['spell27'] == $spellname['spell_id']){$spell27 = "Lvl 3: ".$spellname['spell_name']."<br>";}}else{$spell27 = "";}
    }
   if($color==1)
    {
    $display = "<tr>";
    $color="2";
    }
   else
    {
    $display = "<tr bgcolor=#EAEAEA>";
    $color="1";
    }
   $display .=  "<td>".$profile['char_name']."</td>";
   $display .=  "<td>".$get_player['pf_real_name']."</td>";
   $display .=  "<td>".$get_species['race_name']."</td>";
   $display .=  "<td>".$profile['race_blood']."</td>";
   $display .=  "<td>".$profile['ability']."</td>";
   $display .=  "<td>".$profile['restriction']."</td>";
   $display .=  "<td>".$get_skillpath['skillpath_name']."/".$profile['skill_path_lvl']."</td>";
   $display .=  "<td>".$skill1."/".$profile['skill1_lvl']."</td>";
   $display .=  "<td>".$skill2."/".$profile['skill2_lvl']."</td>";
   $display .=  "<td>".$skill3."/".$profile['skill3_lvl']."</td>";
   $display .=  "<td>".$skill4."/".$profile['skill4_lvl']."</td>";
   $display .=  "<td>".$profile['path']."</td>";
   $display .=  "<td>".$profile['magic_points']."</td>";
   $display .=  "<td>".$spell1.$spell2.$spell3.$spell4.$spell5.$spell6.$spell7.$spell8.$spell9."</td>";
   $display .=  "<td>".$spell10.$spell11.$spell12.$spell13.$spell14.$spell15.$spell16.$spell17.$spell18.$spell19.$spell20.$spell21.$spell22.$spell23.$spell24.$spell25.$spell26.$spell27."</td>";
   $display .=  "<td>".$profile['create_date']."</td>";
   $display .=  "<td>".$profile['edit_date']."</td>";
   $display .=  "</tr>";
   $body .= $display; 
   }
  $body = htmlspecialchars($body); 
//  print "</table><br />

 print "<form action='report-export.php' method='POST' style='display:inline;'>
       <input type='hidden' name='body' value='$body'>
       <input type='hidden' name='header' value='$header'>
       <input type='hidden' name='date' value='$date_format'>
       <input type='hidden' name='year' value='$year'>
       <input type='hidden' name='name' value='$name'>
       <input type='hidden' name='count' value='$count'>
       <input type='submit' name='submit' value='Active Characters'>
    </form> </td></tr>";
  print "<tr><td><strong>VIEW AS PLAYER:</strong> <form method='post' action='' style='display:inline;'><input type='text' name='player' class='auto'> <div id='small'>(Case Sensitive)</div> <input type='hidden' name='S_ID' value='viewas'> <input type='submit' name='Submit' value='View'></form> Currently Viewing as: <strong>$modename[pf_real_name]</strong><br><a href='/forum/viewtopic.php?f=51&t=362' target='_blank'>Documentation</a></td></tr>";
  print "</table></ul><span class='corners-bottom'><span></div></div><br>";
  }

 if($webmgr == '1' || $org == '1' || $rules == '1') 
  {
  print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
  print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>Rules Team</dt></dl></li></ul> <ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>";
  print " <table border='0' cellspacing='3' cellpadding='3' width='800' style='color: #000000;'>";
  print "<tr><td width='200'><strong><a href='admin-rules.php?edit_type=3'>Configuration</a></strong></td><td width='200' align='center'><strong><a href='admin-rules.php?edit_type=4'>Update Rulebook</a></strong></td><td width='200' align='center'></td><td width='200' align='center'></td></tr>";
  print "</tr></table></ul><span class='corners-bottom'><span></div></div><br>";
  }

 if($loggedin['user_id'] == $modeid || $log == '1' || $webmgr == '1') 
  {
 print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
 print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>Active Characters"; if($userid != $modeid){print " for $modename[pf_real_name]";} print "</dt></dl></li></ul>";

 print "<ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>Maximum number of characters: $CF_OZ_CHARMAX (this does not include the unlimited Short-Term Characters)</ul><ul class='topiclist forums'>";

 while($char = mysql_fetch_assoc($getchar))
  {
  if(!empty($char['who_photo'])){$photo = $char['who_photo'];}else{$photo = "/images/oz/logo-oz.png";}
  print "<li class='row' style='padding: 5px;'><dl class='icon' style='background-image: url($photo); background-repeat: no-repeat; background-size: 40px; padding-left: 20px;'>";
  if($userid != $modeid){$mode = "&mode=".$modeid;}
  print "<dt title='View Character' style='width: 750px;'><a href='character-view.php?char_id=$char[char_id]$mode' class='forumtitle'>$char[char_name]</a><br />$char[who_bio]</dt></dl></li>";
  }
 $photo = "/images/oz/logo-oz.png"; 
 $count2 = mysql_num_rows($getchar);
 if($count2 < $CF_OZ_CHARMAX && $userid == $modeid)
  {
  print "<li class='row' style='padding: 5px;'><dl class='icon' style='background-image: url($photo); background-repeat: no-repeat; background-size: 40px; padding-left: 20px;'>";
  print "<dt title='Create Character' style='width: 750px;'><a href='character-new.php' class='forumtitle'>Create a New Character</a></dt></dl></li>";
  }
 if($userid != $modeid)
  {
  print "<li class='row' style='padding: 5px;'><dl class='icon' style='background-image: url($photo); background-repeat: no-repeat; background-size: 40px; padding-left: 20px;'>";
  print "<dt title='Create Character' style='width: 750px;'><strong>Create a New Character</strong><br>Characters added for a Player must be done by the Player.</dt></dl></li>";
  }
 print "</ul><span class='corners-bottom'><span></span></span></div></div><br>";

$start=0;
$limit=10;
$id=1;

if(isset($_GET['id']))
{
$id=$_GET['id'];
$start=($id-1)*$limit;
}

 print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
 print "<ul class='topiclist'><li class='header'><dl class='icon'><dt style='padding: 2px 0;'>Player Point Log"; if($userid != $modeid){print " for $modename[pf_real_name]";} print "<a name='pplog'></a></dt>";
 print "<dd class='topics'>Spent</dd><dd class='posts'>Recieved</dd><dd class='lastpost'><span>Date</span></dd></dl></li></ul>";
 print "<ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>Player Point Total: $modepp[player_points]</ul><ul class='topiclist forums'>";

 $getpp = mysql_query("SELECT * FROM `characters_pplog` WHERE `user_id`='$modeid' ORDER BY `pp_id` DESC LIMIT $start, $limit");
 while($pp = mysql_fetch_assoc($getpp))
  {
  if($pp['pp_detail'] == 'Created Character' || $pp['pp_detail'] == 'Deleted Character' || $pp['pp_detail'] == 'Upgraded Skill(s)' || $pp['pp_detail'] == 'Upgraded Spell(s)'){$colon = ": ".$pp['char_name'];}else{$colon = " ";}
  print "<li class='row' style='padding: 5px; height: 20px;'><dl class='icon' style='height: 20px;'>";
  print "<dt title='Player Point Log' style='padding: 0;'>$pp[pp_detail]$colon";
  print "<dd class='topics' style='padding-top: 0; height: 16px; margin-top: -5px;'>$pp[ppearned_spent]</dd>";
  print "<dd class='posts' style='padding-top: 0; height: 16px; margin-top: -5px;'>$pp[pp_refund]</dd>";
  print "<dd class='lastpost' style='padding-top: 0;'><span>$pp[pp_date] by <a target='_blank' href='/forum/memberlist.php?mode=viewprofile&u=54'>$pp[pp_author]</a></span></dd>";
  print "</dt></dl></li>";
  }

print "</ul><span class='corners-bottom'><span></span></span></div></div>";

$rows=mysql_num_rows(mysql_query("select * from `characters_pplog` WHERE `user_id`='$modeid'"));
$total=ceil($rows/$limit);
print "<ul class='page'>";
for($i=1;$i<=$total;$i++)
{
if($i==$id) { echo "<li style='font-weight:bold; color: #089b01; padding: 5px;'>".$i."</li>"; }

else { echo "<li><a href='?id=".$i."#pplog'>".$i."</a></li>"; }
}
print "</ul><br><br>";

  }
 else
  {
 print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
 print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>You do not have permission to view these characters</dt></dl></li></ul><span class='corners-bottom'><span></span></span></div></div><br>";
  }

 print "<div class='forabg'><div class='inner'><span class='corners-top'><span></span></span>";
 print "<ul class='topiclist'><li class='header'><dl class='icon'><dt>Archives"; if($userid != $modeid){print " for $modename[pf_real_name]";} print "</dt></dl></li></ul>";

 print "<ul class='topiclist forums' style='padding: 5px; border-bottom: solid 1px #000; color: #000;'>Archived characters cannot be edited. There is no limit to the number of archived characters you can have.</ul><ul class='topiclist forums'>";

$getchararc = mysql_query("SELECT * FROM `characters` WHERE `user_id`='$modeid' AND `char_status`='2' ORDER BY `char_name` ASC");
 while($chararc = mysql_fetch_assoc($getchararc))
  {
  if(!empty($chararc['who_photo'])){$photoarc = $chararc['who_photo'];}else{$photoarc = "/images/oz/logo-oz.png";}
  print "<li class='row' style='padding: 5px;'><dl class='icon' style='background-image: url($photoarc); background-repeat: no-repeat; background-size: 40px; padding-left: 20px;'>";
  print "<dt title='View Character' style='width: 750px;'><a href='character-view.php?char_id=$chararc[char_id]' class='forumtitle'>$chararc[char_name]</a><br />$chararc[who_bio]</dt></dl></li>";
  }
 print "</ul><span class='corners-bottom'><span></span></span></div></div><br>";

 }
include ('character-footer.php'); 
?>