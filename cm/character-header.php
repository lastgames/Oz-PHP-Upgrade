<?php

require_once('scripts/database.php');

require_once('scripts/logincheck.php');

$get_cd = mysql_query("SELECT * FROM `config`");
while($each = mysql_fetch_assoc($get_cd)){$$each['config_name'] = $each['config_value'];}

function getExtension($str) 
   {
   $i = strrpos($str,".");
   if (!$i) { return ""; }
   $l = strlen($str) - $i;
   $ext = substr($str,$i+1,$l);
   return $ext;
   }

function generate_session($strlen){
    return substr(md5(uniqid(rand(),1)),1,$strlen);
}

$date = date("Y-m-d");
$date_format = date("M. j, Y");
$siteyear = date("Y");

require_once('scripts/processes.php');
require_once('scripts/processes-addchar.php');
require_once('scripts/processes-admin.php');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="keywords" content="<?php echo $CF_SITEKEYWORDS;?>">
<meta name="description" content="<?php echo $CF_SITEDESC;?>">

<link rel="icon" type="image/png" href="favicon.png">

<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/colours.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/common.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/content.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/cp.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/forms.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/tweaks.css'>
<link rel='stylesheet' type='text/css' href='forum/styles/ProNight/theme/stylesheet.css'>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />

<link rel='stylesheet' type='text/css' href='styles/style-character.css'>

<script type="text/javascript">
function show_confirm() {
    return confirm("Are you sure you want to delete this character? Player Points will not be refunded (unless done by Logistics).");
}
</script>

<script type="text/javascript">
function show_status() {
    return confirm("Are you sure you want to archive this character?");
}
</script>

<script type="text/javascript" src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
toolbar: "undo redo | bold italic",
menubar : false
 });
</script>

<script type="text/javascript">
function showPopup(url) {
newwindow=window.open(url,'name','height=500,width=500,top=200,left=300,resizable,titlebar=0,toolbar=0,location=0,scrollbars');
if (window.focus) {newwindow.focus()}
}
</script>

<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>

<script language="javascript" type="text/javascript">
function limitText2(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>

<title>Character Manager | <?php echo $CF_SITENAME;?></title></head><body><a name='top'></a>

<body>

<div id="wrap">
    <div class="border-left">
    <div class="border-right">
    <div class="border-top">
    <div class="border-top-left">
    <div class="border-top-right">
        <div class="inside" style="direction: {S_CONTENT_DIRECTION}; ">
        	<a id="top" name="top" accesskey="t"></a>
        	<div id="page-header">
        		<div class="headerbar" style="background-color: #000000;">
        			<div class="inner"><span class="corners-top"><span></span></span>
                    <div style="height: 110px;">
        			<div id="site-description">
        				<a href="/index.php" title="Return to LAST Games home" id="logo"><img src='/forum/styles/ProNight/imageset/site_logo.png' border='0' /></a>
 
        			</div>
                    </div>
        			<span class="corners-bottom"><span></span></span></div>
        		</div>
        
        		<div class="navbar">
        			<div class="inner"><span class="corners-top"><span></span></span>
        
        			<ul class="linklist navlinks">
        				<li class="icon-home"><a href='/index.php'>LAST Games Home</a> &bull; <a href="/forum/index.php" accesskey="h">Board Index</a> <strong>&#8249;</strong> <a href="/character.php" accesskey="h">Character Manager</a> <strong>&#8249;</strong> <a style="color: darkgreen" href="/cm_1_5/character_1_5.php">(*New*) Character Manager</a></li>
         

        			</ul>
<?php
if(!empty($loggedin['user_id']))
 { ?>     
<ul class="linklist leftside">
        				<li class="icon-ucp">
        					<a href="/forum/ucp.php" title="User Control Panel" accesskey="e">User Control Panel</a>
						
        				</li>
        			</ul>
 
<?php
} ?> 
       			
        
        			<ul class="linklist rightside">
<?php
if(!empty($loggedin['user_id']))
 { ?> 
<li class="icon-logout"><a href="/forum/ucp.php?mode=logout" title="Logout" accesskey="x">Logout [ <?php echo $loggedin['username'];?> ]</a></li>

<?php
} else { ?>      

<li class="icon-register"><a href="/forum/ucp.php?mode=login">Login</a></li>

<?php
} ?>   				
        			</ul>
        
        			<span class="corners-bottom"><span></span></span></div>
        		</div>
        
        	</div>

<?php
if(empty($loggedin['user_id'])){$access = "You must be logged in to use the Character Manager.";}

?>        
        	<a name="start_here"></a>
        	<div id="page-body">

<!-- <div class='rules' style="padding-top: 5px; padding-bottom: 5px;">Please be prepared to modify your character in a timely manner due to auto-logout after 10 minutes for security.</div> -->