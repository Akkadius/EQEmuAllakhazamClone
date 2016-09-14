<?php
require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir.'mysql.php');
$Title="News and Updates";
require_once($includes_dir.'headers.php');
require_once($includes_dir.'functions.php');

if ($EnableNews==FALSE)
{
	print "Access forbidden";
	die;
}
$query="SELECT * 
        FROM $tbnews
        ORDER BY DATE desc";
$result=mysql_query($query) or message_die('news.php','MYSQL_QUERY',$query,mysql_error());
$sep="";
while ($res=mysql_fetch_array($result))
{
	print "<center>$sep<b>".WriteDate($res["date"])."</b></center>";
	print "<blockquote><b>".$res["title"]."</b><br>".$res["content"]."</blockquote>";
	$sep="<center><p><img src=images/line.gif width=75% height=5><p></center>";
}

require_once($includes_dir."footers.php");
?>