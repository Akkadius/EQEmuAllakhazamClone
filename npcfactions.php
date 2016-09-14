<?php
	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');
	
	// Pagination //
	$page = (isset($_GET['page']) ? addslashes($_GET['page']) : 0);
	$targetpage = "npcfactions.php"; 				// File name  (the name of this file)
	$tbl_name=$tbfactionlist;						// Table name
	if($page)
	{
		$start = ($page - 1) * $MaxResultsPerPage; 	//first item to display on this page
	}
	else
	{
		$start = 0;									//if no page var is given, set start to 0
	}
	$total_pages = GetFieldByQuery("num", "SELECT COUNT(*) as num FROM $tbl_name");
	// Pagination //
	
	$Title="Bestiary by Factions";
	include($includes_dir.'headers.php');
	include($includes_dir.'functions.php');

	echo '<br><center><table width="50%" border ="1" style="background-color: ; filter:alpha(opacity=80); -moz-opacity:0.8; opacity: 0.8;"><td align="left">';
	echo "<p class=menuh>Factions:<p>";
	$query="SELECT id,name from $tbfactionlist order by name ASC";
	// Limits for pagination
	$query.=" LIMIT $start, $MaxResultsPerPage";
	$result=mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query,mysql_error());
	$RowClass = "lr";
	
	while ($row=mysql_fetch_array($result)) {
		echo '<tr class="'.$RowClass.'"><td>';
		echo "<li><a href=faction.php?id=" . $row["id"] . ">" . $row["name"] . "</a>";
		echo '</td></tr>';
		if ($RowClass == "lr"){
			$RowClass = "dr";
		}
		else{
			$RowClass = "lr";
		}		
	}
	echo '</table>';
	print "<table><tr><td align='center'>";
	// Print pagination
	print Pagination($targetpage, $page, $total_pages, $MaxResultsPerPage, $AdjacentPages);
	print "</td></tr></table>";

	include($includes_dir."footers.php");
?>
