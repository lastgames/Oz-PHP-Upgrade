<?php

require_once('scripts/database.php');

require_once('scripts/logincheck.php');

require_once('scripts/processes.php');

$get_cd = mysql_query("SELECT * FROM `config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

 if(empty($_GET['id'])){$charid = "0";}else{$charid = $_GET['id'];}
 $userid = $loggedin['user_id'];

$groups = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_user_group` WHERE `user_id`='$userid' AND `group_id`='11' OR `group_id`='20'"));

 $char = mysql_fetch_assoc(mysql_query("SELECT char_id,char_name,background,user_id,race_name FROM `characters` WHERE `char_id`='$charid' LIMIT 1"));

if(empty($userid)){$access = "You must be logged in to use the Character Manager.";}
if($userid != $char['user_id'] && !$groups){$access = "You do not have permission to view this character.";}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="<?php echo $CF_SITEKEYWORDS;?>">
<meta name="description" content="<?php echo $CF_SITEDESC;?>">

<link rel="icon" type="image/png" href="favicon.png">

<title>Background Review | <?php echo $CF_SITENAME;?></title></head><body><a name='top'></a>

<body>

<?php
if(!empty($access)){print "<br><br><div style='width: 835px; background: #f0c2b8; padding: 5px; border: solid 2px #bc4328; font-weight: bold; color: #000000;'> $access</div><br><br>";}
else
 {
 print "<h2>Background Review</h2><strong>Character Name:</strong> $char[char_name]<br><br><strong>Species:</strong> $char[race_name]<br><br><strong>Background</strong><br>";
 print "<table width='100%' cellpadding='3' cellspacing='3' align='center' style='white-space: pre-line;'><tr><td width='100%'>$char[background]</td></tr></table>";
 }
?> 

</body>
</html>