<?php


	$id   = (isset($_GET[  'id']) ? $_GET[  'id'] : '');

	if(!is_numeric($id))
	{
		header("Location: ?a=recipes&");
		exit();
	}

	$Title="Recipe :: ".str_replace('_',' ',GetFieldByQuery("name","SELECT name FROM $tbtradeskillrecipe WHERE id=$id"));


	if (!isset($id)) { print "<script>document.location=\"index.php\";</script>"; }

	$query="SELECT *
			FROM $tbtradeskillrecipe
			WHERE id=$id";
			
	$result=mysql_query($query) or message_die('recipe.php','MYSQL_QUERY',$query,mysql_error());
	$recipe=mysql_fetch_array($result);

	print "<table  class='display_table container_div'>";
	print '
		<tr>
			<td colspan="2">
				<h2 class=\'section_header\'>Recipe</h2>
			</td>
		</tr>';
	print "<tr><td style='text-align:right;'><b>Recipe: </b></td><td>".ucfirstwords(str_replace('_',' ',$recipe["name"]))."</td></tr>";
	print "<tr><td style='text-align:right'><b>Tradeskill: </b></td><td>".ucfirstwords($dbskills[$recipe["tradeskill"]])."</td></tr>";
	if ($recipe["skillneeded"]>0)
	{
		print "<tr><td style='text-align:right'><b>Skill needed : </b></td><td>".$recipe["skillneeded"]."</td></tr>";
	}
		print "<tr><td style='text-align:right'><b>Trivial at: </b></td><td>".$recipe["trivial"]."</td></tr>";
	if ($recipe["nofail"]>0)
	{
		print "<tr><td style='text-align:right' colspan=2>This recipe cannot fail.</td></tr>";
	}
	if ($recipe["notes"]!="")
	{
		print "<tr><td style='text-align:right' cospan=2><b>Notes : </b>".$recipe["notes"]."</td></tr>";
	}
	print '</table><br>';

	print '<table class="display_table container_div">';
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
		print "<tr><td><h2 class='section_header'>Containers</h2>";
		print "<ul>";
		while ($row=mysql_fetch_array($result))
		{
			CreateToolTip($row["item_id"], BuildItemStats($row, 1));
			print "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
			"<a href=?a=item&id=" . $row["item_id"] . " id=" . $row["item_id"] . ">" .
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
		print "<tr><td><h2 class='section_header'>Creates</h2><ul>";
		while ($row=mysql_fetch_array($result))
		{
			CreateToolTip(($row["item_id"] * 110), BuildItemStats($row, 1));
			print "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
			"<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 110) . ">" .
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
			print "<tr><td><h2 class='section_header'>Failure</h2><ul>";
			while ($row=mysql_fetch_array($result))
			{
				CreateToolTip(($row["item_id"] * 10), BuildItemStats($row, 1));
				print "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
				"<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 10) . ">" .
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
		print "<tr><td><h2 class='section_header'>Required</h2><ul>";

			while ($row=mysql_fetch_array($result))
			{
				CreateToolTip(($row["item_id"] * 100), BuildItemStats($row, 1));
				print "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'	/> " .
					"<a href=?a=item&id=".$row["item_id"]." id=".($row["item_id"] * 100).">".
				str_replace("_"," ",$row["Name"])."</a> x ".$row["componentcount"]." <br>"; 
			}
		print "</td></tr>";
	}
	print "</table>";


?>