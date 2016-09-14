<?php
	#ini_set('display_errors', 'On');
	#error_reporting(E_ALL);

	include('./includes/config.php');
	include($includes_dir.'constantes.php');

	include($includes_dir.'mysql.php');
	include($includes_dir.'functions.php');
	
	// Pagination //
	$page = (isset($_GET['page']) ? addslashes($_GET['page']) : 0);
	$targetpage = "index.php"; 					// File name  (the name of this file)
	$tbl_name=$tbdiscovereditems;				// Table name
	if($page)
	{
		$start = ($page - 1) * $MaxResultsPerPage; 			//first item to display on this page
	}
	else
	{
		$start = 0;								//if no page var is given, set start to 0
	}
	$total_pages = GetFieldByQuery("num", "SELECT COUNT(*) as num FROM $tbl_name");
	// Pagination //

	

	$Title="Wecome to AllaClone!";
	include($includes_dir.'headers.php');

	// Here's the main page of the website

	if (file_exists("design/index.html"))
	{
		print "<center><table width=95% border=0><tr valign=top><td>";
		include("design/index.html");
		print "</td></tr></table></center>";
	}

	if ($DiscoveredItemsOnly==TRUE){
		$Title="Recently Discovered Items";
		print "<center><table border='0' width='0%'><tr valign='top'><td class='header_cell'>";
		print "<b><center>$Title</center></b>";
		print "</td></tr>"; 

		print "<table border=0 width=100%><tr valign=top><td width=100%>";

		$query="SELECT  items.*,
					$tbdiscovereditems.item_id,
					$tbdiscovereditems.char_name,
					$tbdiscovereditems.discovered_date
					FROM $tbitems, $tbdiscovereditems
					WHERE $tbitems.id = $tbdiscovereditems.item_id
					AND $tbdiscovereditems.account_status < $DiscoveredItemsMaxStatus";

		// Limits added for pagination
		$query.=" ORDER BY $tbdiscovereditems.discovered_date DESC LIMIT $start, $MaxResultsPerPage";

		$result=mysql_query($query) or message_die('index.php','MYSQL_QUERY',$query,mysql_error());
		print "<center><table border=0 cellpadding='5' cellspacing='0'><tr>
			   <td class='menuh'>Item Name</td>
			   <td class='menuh'>Item ID</td>
			   <td class='menuh'>Discovered By</td>
			   <td class='menuh'>Discovered Date</td>
			   ";
			   
		$RowClass = "lr";
		while ($row=mysql_fetch_array($result)) {
				CreateToolTip($row["item_id"], BuildItemStats($row, 1));
				print "<tr class='" .$RowClass. "'>
				<td><a href=item.php?id=".$row["item_id"]." id='" . $row["item_id"] . "'>";
				
				if(file_exists(getcwd(). "/icons/item_". $row['icon'] . ".gif")){ 
					echo "<img src='".$icons_url. "item_" . $row['icon'].".gif' align='left'/ width='20' height='20'>  "; 
				}
				
				if ($charbrowser_url)
				{
					$DiscoveredBy = "<a href=".$charbrowser_url."character.php?char=".$row["char_name"].">".$row["char_name"]."</a>";
				}
				else
				{
					$DiscoveredBy = $row["char_name"];
				}
				echo $row["Name"] . "</a></td> 
					<td align=center>".$row["item_id"]."</td>
					<td align=center>".$DiscoveredBy."</td>
					<td>".date("m/d/y - H:i",$row["discovered_date"])."</td>
					</tr>";
				if ($RowClass == "lr"){
					$RowClass = "dr";
				}
				else{
					$RowClass = "lr";
				}
		}
		print "</table></center>";
		
		print "<tr><td align='center'>";
		print Pagination($targetpage, $page, $total_pages, $MaxResultsPerPage, $AdjacentPages);
		print "</td></tr>";

		//print "</td><td width=0% nowrap>";
		print "</td></tr></table>";
	}
	
	include($includes_dir."footers.php");
?>


