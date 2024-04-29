<?php
include ('header.php'); 

if(!empty($_GET['page'])){$page = $_GET['page'];}else{$page = "1";}

include ('header-page.php'); 

print "<h1>Calendar</h1><br><div class='news'>";

if($page == '3') // Oz
 {
print "<div class='paypal'>";
 ?>

<table width='500' align='center' border='0'>
<tr><td width='250' align='center'><strong>Pre-Pay for the next Event</strong><br>
Please pay full price if after one month before event date, or pre-pay rate if coming from Oregon.
</td></tr>
<tr><td align='center' valign='top'>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="P2MHUTAVZFJ5Q">
<table>
<tr><td><input type="hidden" name="on0" value="Price">Price</td></tr><tr><td><select name="os0">
	<option value="Full price">Full price $50.00 USD</option>
	<option value="Pre-pay">Pre-pay $45.00 USD</option>
	<option value="Pre-pay from Oregon">Pre-pay from Oregon $40.00 USD</option>
	<option value="First-time">First-time $25.00 USD</option>
</select> </td></tr>
<tr><td><input type="hidden" name="on1" value="Player Name">Player Name</td></tr><tr><td><input type="text" name="os1" maxlength="200"></td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


</td></tr>
</table></div>

<?php
 }
print "<div class='news-item-content'>";

print "Check out these events and join in the fun!<br><br>Not a Facebook user? No problem! All our events are marked 'public' so anyone, logged in to Facebook or not, can view the event information.<br><br>";

$today = date('Y-m-d');

if($page == '2') // Zombie
 { ?>



<?php
 }
else if($page == '3') // Oz
 { ?>

<iframe src="http://www.fanrx.com/facebook/events.php?theme=custom&page=389428961146637&bgcolor=ffffff&textcolor=000000&linkcolor=555555&max=5" width="400" height="520" frameborder="no" scrolling="auto"></iframe>

<?php
  }
else // LAST
 { ?>

<iframe src="http://www.fanrx.com/facebook/events.php?theme=custom&page=389428961146637&bgcolor=ffffff&textcolor=000000&linkcolor=555555&max=5" width="400" height="520" frameborder="no" scrolling="auto"></iframe>

<?php
  } 

print "</div></div>";

include ('footer.php'); 

?>