<?php
if($page == '2')
 {
 print "<link rel='stylesheet' type='text/css' href='styles/style-zombie.css'>";
 print "<title>Zombie! | $CF_SITENAME</title></head><body><a name='top'></a>";
 print "<div class='container'><div class='header'>";
 print "<div class='head-space'><img src='images/blank.png' height='130' width='400' /></div>";
 print "<div class='logo'><a href='/'><img src='images/zombie/logo.png' /></a></div>";
 print "<div class='facebook'><a href='https://www.facebook.com/pages/Zombie-By-Last-Games/166921540067590'><img src='images/zombie/logo-facebook.png' alt='Facebook' /></a></div>";
 print "<div class='calendar'><a href='calendar.php?page=2' title='Calendar'><img src='images/zombie/logo-calendar.png' border='0' alt='Calendar' /></a></div>";
 if(!empty($loggedin['user_id']))
  {
  print "<div class='login'><a href='/forum/ucp.php' title='Profile'><img src='images/zombie/logo-profile.png' border='0' alt='Profile' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=logout' title='Logout'><img src='images/zombie/logo-logout.png' border='0' alt='Logout' /></a></div>";
  } else { 
  print "<div class='login'><a href='/forum/ucp.php?mode=login' title='Login'><img src='images/zombie/logo-login.png' border='0' alt='Login' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=register' title='Register'><img src='images/zombie/logo-register.png' border='0' alt='Register' /></a></div>";
  } 
 print "<div class='corner'></div>";
 print "<div class='rotator'>";
 print "<img src='images/zombie/rotate-2.jpg' />";
 print "<div class='rotator-screen'></div></div></div>";

 print "<div class='content'><div class='menu'><ul>";
 print "<li><a href='zombie.php'>Home</a></li>";
 print "<li><a href='calendar.php?page=2'>Calendar</a></li>";
 print "<li><a href='/forum/viewforum.php?f=19'>Forum</a></li>";
 print "<li><a href='gallery.php?page=2'>Gallery</a></li>";
 print "<li><a href='zombie.php?page=rules'>Rules</a></li>";
 print "<li><a href='contact.php?page=2'>Contact Us</a></li>";
 print "<li><a href='index.php'>LAST Games</a></li>";
 print "<li><a href='oz.php'>World of Oz</a></li>";
 print "</ul></div>";
 }
else if($page == '3')
 {
 print "<link rel='stylesheet' type='text/css' href='styles/style-oz.css'>";
 print "<title>World of Oz | $CF_SITENAME</title></head><body><a name='top'></a>";
 print "<div class='container'><div class='header'>";
 print "<div class='head-space'><img src='images/blank.png' height='130' width='400' /></div>";
 print "<div class='logo'><a href='/'><img src='images/oz/logo.png' /></a></div>";
 print "<div class='facebook'><a href='https://www.facebook.com/WorldOfOzByLastGames'><img src='images/oz/logo-facebook.png' alt='Facebook' /></a></div>";
 print "<div class='calendar'><a href='calendar.php?page=3' title='Calendar'><img src='images/oz/logo-calendar.png' border='0' alt='Calendar' /></a></div>";
 if(!empty($loggedin['user_id']))
  {
  print "<div class='login'><a href='/forum/ucp.php' title='Profile'><img src='images/oz/logo-profile.png' border='0' alt='Profile' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=logout' title='Logout'><img src='images/oz/logo-logout.png' border='0' alt='Logout' /></a></div>";
  } else { 
  print "<div class='login'><a href='/forum/ucp.php?mode=login' title='Login'><img src='images/oz/logo-login.png' border='0' alt='Login' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=register' title='Register'><img src='images/oz/logo-register.png' border='0' alt='Register' /></a></div>";
  } 
 print "<div class='corner'></div>";
 print "<div class='rotator'>";
 print "<img src='images/oz/rotate-1.jpg' />";
 print "<div class='rotator-screen'></div></div></div>";

 print "<div class='content'><div class='menu'><ul>";
 print "<li><a href='oz.php'>Home</a></li>";
 print "<li><a href='calendar.php?page=3'>Calendar</a></li>";
 print "<li><a href='/forum/viewforum.php?f=18'>Forum</a></li>";
 print "<li><a href='gallery.php?page=3'>Gallery</a></li>";
 print "<li><a href='oz.php?page=rules'>Rules</a></li>";
 print "<li><a href='oz.php?page=wiki'>World Wiki</a></li>";
 print "<li><a href='contact.php?page=3'>Contact Us</a></li>";
 print "<li><a href='index.php'>LAST Games</a></li>";
 print "<li><a href='zombie.php'>Zombie!</a></li>";
 print "</ul></div>";
 }
else // last
 {
 print "<link rel='stylesheet' type='text/css' href='styles/style-last.css'>";
 print "<title>$CF_SITETITLE</title></head><body><a name='top'></a>";
 print "<div class='container'><div class='header'>";
 print "<div class='head-space'><img src='images/blank.png' height='130' width='400' /></div>";
 print "<div class='logo'><a href='/'><img src='images/last/logo.png' /></a></div>";
 print "<div class='facebook'><img src='images/last/logo-facebook.png' alt='Facebook' /></div>";
 print "<div class='calendar'><a href='calendar.php' title='Calendar'><img src='images/last/logo-calendar.png' border='0' alt='Calendar' /></a></div>";
 if(!empty($loggedin['user_id']))
  {
  print "<div class='login'><a href='/forum/ucp.php' title='Profile'><img src='images/last/logo-profile.png' border='0' alt='Profile' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=logout' title='Logout'><img src='images/last/logo-logout.png' border='0' alt='Logout' /></a></div>";
  } else { 
  print "<div class='login'><a href='/forum/ucp.php?mode=login' title='Login'><img src='images/last/logo-login.png' border='0' alt='Login' /></a></div>";
  print "<div class='register'><a href='/forum/ucp.php?mode=register' title='Register'><img src='images/last/logo-register.png' border='0' alt='Register' /></a></div>";
  } 
 print "<div class='corner'></div>";
 print "<div class='rotator'>";

 print "<div class='rotator-screen'></div></div></div>";

 print "<div class='content'><div class='menu'><ul>";
 print "<li><a href='index.php'>Home</a></li>";
 print "<li><a href='calendar.php'>Calendar</a></li>";
 print "<li><a href='/forum/index.php'>Forum</a></li>";
 print "<li><a href='gallery.php'>Gallery</a></li>";
 print "<li><a href='contact.php'>Contact Us</a></li>";
 print "<li><a href='oz.php'>World of Oz</a></li>";
 print "<li><a href='zombie.php'>Zombie!</a></li>";
 print "</ul></div>";
 }

print "<div class='content-index'>";

print "<div class='content-index-text'>";

?>