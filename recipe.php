<?php
	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');

	$id   = (isset($_GET[  'id']) ? $_GET[  'id'] : '');

	if(!is_numeric($id))
	{
		header("Location: recipes.php");
		exit();
	}

	$Title="Recipe : ".str_replace('_',' ',GetFieldByQuery("name","SELECT name FROM $tbtradeskillrecipe WHERE id=$id"));
	include($includes_dir.'headers.php');
	include($includes_dir.'functions.php');

	if (!isset($id)) { print "<script>document.location=\"index.php\";</script>"; }

	$query="SELECT *
			FROM $tbtradeskillrecipe
			WHERE id=$id";
			
	$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
	$recipe=mysql_fetch_array($result);
	print "<table border=0 width=0%>";
	print "<tr><td nowrap><b>Recipe : </b></td><td nowrap>".ucfirstwords(str_replace('_',' ',$recipe["name"]))."</td></tr>";
	print "<tr><td nowrap><b>Tradeskill : </b></td><td nowrap>".ucfirstwords($dbskills[$recipe["tradeskill"]])."</td></tr>";
	if ($recipe["skillneeded"]>0)
	{
		print "<tr><td nowrap><b>Skill needed : </b></td><td nowrap>".$recipe["skillneeded"]."</td></tr>";
	}
		print "<tr><td nowrap><b>Trivial at : </b></td><td nowrap>".$recipe["trivial"]."</td></tr>";
	if ($recipe["nofail"]>0)
	{
		print "<tr><td nowrap colspan=2>This recipe cannot fail.</td></tr>";
	}
	if ($recipe["notes"]!="")
	{
		print "<tr><td cospan=2><b>Notes : </b>".$recipe["notes"]."</td></tr>";
	}
	// results containers
	$query="SELECT $tbtradeskillrecipeentries.*,$tbitems.*,$tbitems.id AS item_id
			FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries,$tbitems
			WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
			  AND $tbtradeskillrecipeentries.recipe_id=$id
			  AND $tbtradeskillrecipeentries.item_id=$tbitems.id
			  AND $tbtradeskillrecipeentries.iscontainer=1";
			  
	$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
	
	if (mysql_num_rows($result)>0)
	{
		print "<tr class=myline height=6><td colspan=2></td><tr>";
		print "<tr><td nowrap><b>Containers needed for the combine </b>";
		print "<ul>";
		while ($row=mysql_fetch_array($result))
		{
			CreateToolTip($row["item_id"], BuildItemStats($row, 1));
			print "<img src='" . $icons_url . "item_" . $row["icon"] . ".gif' align='left' width='15' height='15'/>" .
			"<a href=item.php?id=" . $row["item_id"] . " id=" . $row["item_id"] . ">" .
			str_replace("_"," ",$row["Name"])."</a><br>";
			if ($recipe["replace_container"]==1)
			{
				print " (this container will disappear after combine)";
			}           
		}
		print "</ul></td></tr>";
	}


	// results success
	$query="SELECT $tbtradeskillrecipeentries.*,$tbitems.*,$tbitems.id AS item_id
			FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries,$tbitems
			WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
			  AND $tbtradeskillrecipeentries.recipe_id=$id
			  AND $tbtradeskillrecipeentries.item_id=$tbitems.id
			  AND $tbtradeskillrecipeentries.successcount>0";
			  
	$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)>0)
	{
		print "<tr class=myline height=6><td colspan=2></td><tr>";
		print "<tr><td nowrap><b>Items resulting of a <FONT COLOR='#FFFF00'> successfull combine </FONT></b><ul>";
		while ($row=mysql_fetch_array($result))
		{
			CreateToolTip(($row["item_id"] * 110), BuildItemStats($row, 1));
			print "<img src='" . $icons_url . "item_" . $row["icon"] . ".gif' align='left' width='15' height='15'/>" .
			"<a href=item.php?id=" . $row["item_id"] . " id=" . ($row["item_id"] * 110) . ">" .
			str_replace("_"," ",$row["Name"])."</a> x".$row["successcount"]." <br>"; 
		}
		print "</ul></td></tr>";
	} 

	if ($recipe["nofail"]==0)
	{
		// results fail
		$query="SELECT $tbtradeskillrecipeentries.*,$tbitems.*,$tbitems.id AS item_id
				FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries,$tbitems
				WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
				  AND $tbtradeskillrecipeentries.recipe_id=$id
				  AND $tbtradeskillrecipeentries.item_id=$tbitems.id
				  AND $tbtradeskillrecipeentries.failcount>0";
				  
		$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
		if (mysql_num_rows($result)>0)
		{
			print "<tr class=myline height=6><td colspan=2></td><tr>";
			print "<tr><td nowrap><b>Items resulting of a <FONT COLOR='#FF0000'> failed combine </FONT></b><ul>";
			while ($row=mysql_fetch_array($result))
			{
				CreateToolTip(($row["item_id"] * 10), BuildItemStats($row, 1));
				print "<img src='" . $icons_url . "item_" . $row["icon"] . ".gif' align='left' width='15' height='15'/>" .
				"<a href=item.php?id=" . $row["item_id"] . " id=" . ($row["item_id"] * 10) . ">" .
				str_replace("_"," ",$row["Name"])."</a> x".$row["failcount"]." <br>"; 
			}
			print "</td></tr>";
		}
	}

	// components
	$query="SELECT $tbtradeskillrecipeentries.*,$tbitems.*,$tbitems.id AS item_id
			FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries,$tbitems
			WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
			AND $tbtradeskillrecipeentries.recipe_id=$id
			AND $tbtradeskillrecipeentries.item_id=$tbitems.id
			AND $tbtradeskillrecipeentries.iscontainer=0
			AND $tbtradeskillrecipeentries.componentcount>0";
			
	$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)>0)
	{
		print "<tr class=myline height=6><td colspan=2></td><tr>";
		print "<tr><td nowrap><b>Components needed : </b><ul>";

			while ($row=mysql_fetch_array($result))
			{
				CreateToolTip(($row["item_id"] * 100), BuildItemStats($row, 1));
				print "<img src='" . $icons_url . "item_" . $row["icon"] . ".gif' align='left' width='15' height='15'/>" . "<a href=item.php?id=".$row["item_id"]." id=".($row["item_id"] * 100).">".
				str_replace("_"," ",$row["Name"])."</a> x ".$row["componentcount"]." <br>"; 
			}
		print "</td></tr>";
	}
	print "</table>";

	include($includes_dir."footers.php");
?>