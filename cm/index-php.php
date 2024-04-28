<?php

include ('header.php'); 

if(!empty($_GET['page'])){$page = $_GET['page'];}else{$page = "last";}

include ('header-page.php'); 

$text = mysql_fetch_assoc(mysql_query("SELECT topic_id,post_text FROM `bb_posts` WHERE `topic_id`='335' LIMIT 1"));

$post_intro = str_replace(':fqan7zvh', '', $text['post_text']);

$post_intro = BBCODE($post_intro,'html');

print "<div class='news'><div class='welcome-title'></div><div class='news-item'><div class='news-item-content'>";
print "<div class='news-item-body'><p>$post_intro</p></div>";
print "</div></div></div>";

$news = mysql_fetch_assoc(mysql_query("SELECT * FROM `bb_posts` WHERE `forum_id`='2' ORDER BY `post_time` DESC LIMIT 1"));
$date_news = date('F j, Y', $news['post_time']);

$post_news = str_replace(':fqan7zvh', '', $news['post_text']);

$post_news = BBCODE($post_news,'html');

$post_news = substr($post_news,0,800).'...';

print "<div class='news'><div class='news-title'></div><div class='news-item'><div class='news-item-content'>";
if($news) 
 {
 print "<div class='news-item-title'>$news[post_subject]</div>";
 print "<div class='news-item-subtitle'>$date_news</div>";
 print "<div class='news-item-body'><p>$post_news</p></div>";
 print "<div class='news-item-link'><a href='/forum/viewforum.php?f=$news[forum_id]&t=$news[topic_id]'>Read More >></a> $edit_news</div>";
 }
else
 {
 print "<div class='news-item-title'>There are no articles at this time.</div>";
 print "<div class='news-item-subtitle'></div>";
 print "<div class='news-item-body'><p>Please check back later</p> $edit_news</div>";
 }
print "</div></div></div>";

print "<div class='news'><div class='info-item-title'></div><div class='news-item'><div class='news-item-content'><div class='news-item-title'></div><div class='news-item-subtitle'></div>";
print "<div class='news-item-body'><p><a href='oz.php'><img src='images/logo-oz.jpg' border='0'></a><br><br><a href='zombie.php'><img src='images/logo-zombie.jpg' border='0'></a></p></div>";
print "<div class='news-item-link'><a href='calendar.php'>Full Calendar of Events >></a></div>";
print "</div></div></div>";

include ('footer.php'); 

?>