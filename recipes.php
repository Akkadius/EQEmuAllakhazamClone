<?php
	$Title="Search Recipes";
	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'headers.php');
	include($includes_dir.'functions.php');

	$minskill = (isset($_GET['minskill']) ? $_GET['minskill'] : 0);
	$maxskill = (isset($_GET['maxskill']) ? $_GET['maxskill'] : 0);
	$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
	$iname = (isset($_GET[  'iname']) ? $_GET[  'iname'] : '');
	$iskill = (isset($_GET[  'iskill']) ? $_GET[  'iskill'] : 0);

	if (!isset($maxskill)) { $maxskill=0; }
	if (!isset($minskill)) { $minskill=0; }
	if (!ctype_digit($maxskill)) { $maxskill=0; }
	if (!ctype_digit($minskill)) { $minskill=0; }
	if ($minskill>$maxskill) { $tempskill=$minskill; $minskill=$maxskill; $maxskill=$tempskill; }

	print "<table border=0 width=0%>";
	print "<form method='GET' action=$PHP_SELF>";
	print "<tr><td nowrap><b>Name : </b></td><td><input type=text value=\"$iname\" size=30 name=iname></td></tr>";
	print "<tr><td nowrap><b>Tradeskill : </b></td><td>";
	print SelectTradeSkills("iskill",$iskill);
	print "</td></tr>";
	print "<tr><td nowrap><b>Min trivial skill : </b></td><td><input type=text value=\"$minskill\" size=4 name=minskill></td></tr>";
	print "<tr><td nowrap><b>Max trivial skill : </b></td><td><input type=text value=\"$maxskill\" size=4 name=maxskill></td></tr>";
	print "<tr align=center><td nowrap colspan=2><input type='submit' value='Search' name='isearch' class='form'/> <input type='reset' value='Reset' class='form'/></td></tr>";
	print "</form></table></center>";

	if (isset($isearch) && $isearch != "")
	{
		if ($minskill>$maxskill) { $tempskill=$minskill; $minskill=$maxskill; $maxskill=$tempskill; }
		$query="SELECT $tbtradeskillrecipe.id,$tbtradeskillrecipe.name,
				$tbtradeskillrecipe.tradeskill,$tbtradeskillrecipe.trivial
				FROM $tbtradeskillrecipe";
		$s="WHERE";
		if ($iname!="")
		{
			$iname=str_replace(' ','%',addslashes($iname));
			$query.=" $s $tbtradeskillrecipe.name like '%".$iname."%'"; $s="AND"; 
		}
		if ($iskill>0) { $query.=" $s $tbtradeskillrecipe.tradeskill=$iskill"; $s="AND"; }
		if ($minskill>0) { $query.=" $s $tbtradeskillrecipe.trivial>=$minskill"; $s="AND"; }
		if ($maxskill>0) { $query.=" $s $tbtradeskillrecipe.trivial<=$maxskill"; $s="AND"; }
		$query.=" ORDER BY $tbtradeskillrecipe.name";
		$result=mysql_query($query) or message_die('recipes.php','MYSQL_QUERY',$query,mysql_error());

		if(isset($result))
		{
			PrintQueryResults($result, $MaxItemsReturned, "recipe.php", "recipe", "recipes", "id", "name", "trivial", "trivial at level", "tradeskill");
		}
	}

	include($includes_dir."footers.php");

?>