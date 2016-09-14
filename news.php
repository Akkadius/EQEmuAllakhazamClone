<?php
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');
$Title="News and Updates";
include($includes_dir.'headers.php');
include($includes_dir.'functions.php');

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

include($includes_dir."footers.php");
?>