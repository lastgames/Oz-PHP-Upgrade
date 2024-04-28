<?php
include ('header.php'); 

if(!empty($_GET['page'])){$page = $_GET['page'];}else{$page = "1";}

include ('header-page.php'); 

print "<h1>Gallery</h1><br>";

print "<div class='news'><div class='news-item-content'>";

print "Photos from our events will be available soon!<br><br>";

$today = date('Y-m-d');

if($page == '2') // Zombie
 { ?>



<?php
 }
if($page == '3') // Oz
 { ?>



<?php
  }
else // LAST
 { ?>


<?php
  } 

print "</div></div>";

include ('footer.php'); 

?>