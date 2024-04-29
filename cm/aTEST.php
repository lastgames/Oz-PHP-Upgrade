<?php
include ('header.php'); 

if(!empty($_GET['page'])){$page = $_GET['page'];}else{$page = "1";}

include ('header-page.php'); 

print "<h1>Contact Us</h1><br>";

 if(!empty($error)){print "<br><div id='error'> $error</div><br>";}

 if(!empty($_GET['m']))
  {
  $groupid = $_GET['group'];
  $groupname = mysql_fetch_assoc(mysql_query("SELECT group_id,group_name FROM `bb_groups` WHERE `group_id`='$groupid' LIMIT 1"));
  print "<br><div id='success'>";
  if($_GET['m'] == '1'){print "Your email has been sent to $groupname[group_name]. One of the group members will contact you shortly. Thank you.";}
  print "</div><br>";
  }

print "<div class='news'><div class='news-item-content'>";

print "If you would like to contact one of our staff members, you have several options:<ul><li>Post in the Group Forum</li><li>Send private message to the Group (via Forum)</li><li>Send private message to the individual (via Forum)</li><li>Facebook</li><li>The contact form below:</li></ul>";

if($page == '2') // Zombie
 { 
 $group_name = mysql_fetch_assoc(mysql_query("SELECT group_id,group_name FROM `bb_groups` WHERE `group_id`='13' LIMIT 1"));
 print "Forum Groups: <a href='/forum/memberlist.php?mode=group&g=13'>$group_name[group_name]</a><ul style='list-style-type: none;'>";
 }
else if($page == '3') // Oz
 { 
 print "Forum Groups: <ul style='list-style-type: none;'>";
 $group_name = mysql_query("SELECT group_id,group_name FROM `bb_groups` ORDER BY `group_name` ASC");
 while($groups = mysql_fetch_assoc($group_name))
  {
  if($groups['group_id'] == '9' || $groups['group_id'] == '10' || $groups['group_id'] == '11' || $groups['group_id'] == '12' || $groups['group_id'] == '14' || $groups['group_id'] == '15')
   {
   print "<li><a href='/forum/memberlist.php?mode=group&g=$groups[group_id]'>$groups[group_name]</a></li>";
   }
  }
 print "</ul>";
 }
else // LAST
 { 
 print "Forum Groups: <ul style='list-style-type: none;'>";
 $group_name = mysql_query("SELECT group_id,group_name FROM `bb_groups` ORDER BY `group_name` ASC");
 while($groups = mysql_fetch_assoc($group_name))
  {
  if($groups['group_id'] == '19' || $groups['group_id'] == '20')
   {
   print "<li><a href='/forum/memberlist.php?mode=group&g=$groups[group_id]'>$groups[group_name]</a></li>";
   }
  }
 print "</ul>";
 }

print "Contact form (all fields required):<br><br><form action='' method='post'><table width='550' cellspacing='3' cellpadding='3' border='0'>";
print "<tr><td width='100' align='right'>"; if(!empty($errorname)){print "<div id='orange'>Your Name</div>";}else{print "Your Name";} print "</td><td width='450'><input id='form_text' type='text' name='name' value='"; if(!empty($_POST['name'])){echo $_POST['name'];}else{echo $realname['pf_real_name'];} print "'></td></tr>";
print "<tr><td align='right'>"; if(!empty($erroremail)){print "<div id='orange'>Your Email</div>";}else{print "Your Email";} print "</td><td><input id='form_text' type='text' name='email' value='"; if(!empty($_POST['email'])){echo $_POST['email'];}else{echo $loggedin['user_email'];} print "'></td></tr>";
print "<tr><td align='right'>Group</td><td><select id='form_select' name='group_id'>";

if($page == '2') // Zombie
 { 
 print "<option value='13'>$group_name[group_name]</option>";
 }
else if($page == '3') // Oz
 { 
 $group_name2 = mysql_query("SELECT group_id,group_name FROM `bb_groups` ORDER BY `group_name` ASC");
 while($groups2 = mysql_fetch_assoc($group_name2))
  {
  if($groups2['group_id'] == '9' || $groups2['group_id'] == '10' || $groups2['group_id'] == '11' || $groups2['group_id'] == '12' || $groups2['group_id'] == '14' || $groups2['group_id'] == '15')
   {
   print "<option value='$groups2[group_id]'"; if(!empty($_POST['group_id']) && $_POST['group_id'] == $groups2[group_id]){print "selected";} print ">$groups2[group_name]</option>";
   }
  }
 }
else // LAST
 { 
 $group_name2 = mysql_query("SELECT group_id,group_name FROM `bb_groups` ORDER BY `group_name` ASC");
 while($groups2 = mysql_fetch_assoc($group_name2))
  {
  if($groups2['group_id'] == '19' || $groups2['group_id'] == '20')
   {
   print "<option value='$groups2[group_id]'"; if(!empty($_POST['group_id']) && $_POST['group_id'] == $groups2[group_id]){print "selected";} print ">$groups2[group_name]</option>";
   }
  }
 }
print "</select></td></tr><tr><td colspan='2' valign='top'>";
 ?>

<textarea id='form_area' name="limitedtextarea" onKeyDown="limitText(this.form.limitedtextarea,this.form.countdown,500);" 
onKeyUp="limitText(this.form.limitedtextarea,this.form.countdown,500);">
<?php if(!empty($_POST['limitedtextarea'])){echo $_POST['limitedtextarea'];}?></textarea></td></tr>
<tr><td></td><td><font size="1">You have <input readonly type="text" name="countdown" size="3" value="500"> characters left.</font>

<?php
print "</td></tr><tr><td></td><td>"; if(!empty($errorvery)){echo "<div id=orange>reCAPTCHA was not entered correctly. Please try again.</div><br>";} 
 $url = $_SERVER['HTTP_HOST'];
 require_once('recaptchalib.php');
 $publickey = "6Le5it0SAAAAANSsngEqsycboytaE0XLjfdE4iN2"; // you got this from the signup page
 echo recaptcha_get_html($publickey);
 print "</td></tr>";

 print "<tr><td></td><td><input type='hidden' name='S_ID' value='contactform'><input type='hidden' name='page' value='1'><input type='submit' value='' id='form_submit'></td></tr>";
 print "</table></form><br><br>";

print "</div></div>";

include ('footer.php'); 

?>