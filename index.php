<?php
	#ini_set('display_errors', 'On');
	#error_reporting(E_ALL);

	require_once('./includes/config.php');
	require_once($includes_dir.'constants.php');

	require_once($includes_dir.'mysql.php');
	require_once($includes_dir.'functions.php');
	
	// Pagination //
	$page = (isset($_GET['page']) ? addslashes($_GET['page']) : 0);
	$targetpage = "index.php"; 					// File name  (the name of this file)
	$tbl_name=$discovered_items_table;				// Table name
	if($page)
	{
		$start = ($page - 1) * $MaxResultsPerPage; 			//first item to display on this page
	}
	else
	{
		$start = 0;								//if no page var is given, set start to 0
	}
	$total_pages = get_field_result("num", "SELECT COUNT(*) as num FROM $tbl_name");
	// Pagination //

	

	$page_title="Wecome to AllaClone!";


	// Here's the main page of the website

	if (file_exists("design/index.html"))
	{
		print "<table width=95% border=0><tr valign=top><td>";
		require_once("design/index.html");
		print "</td></tr></table>";
	}

	if ($discovered_items_only==TRUE){
		$page_title="Recently Discovered Items";
		print "<table border='0' width='0%'><tr valign='top'><td class='header_cell'>";
		print "<b>$page_title</b>";
		print "</td></tr>"; 

		print "<table border=0 width=100%><tr valign=top><td width=100%>";

		$query="SELECT  items.*,
					$discovered_items_table.item_id,
					$discovered_items_table.char_name,
					$discovered_items_table.discovered_date
					FROM $items_table, $discovered_items_table
					WHERE $items_table.id = $discovered_items_table.item_id
					AND $discovered_items_table.account_status < $discovered_items_max_status";

		// Limits added for pagination
		$query.=" ORDER BY $discovered_items_table.discovered_date DESC LIMIT $start, $MaxResultsPerPage";

		$result=db_mysql_query($query) or message_die('index.php','MYSQL_QUERY',$query,mysql_error());
		print "<table border=0 cellpadding='5' cellspacing='0'><tr>
			   <td class='menuh'>Item Name</td>
			   <td class='menuh'>Item ID</td>
			   <td class='menuh'>Discovered By</td>
			   <td class='menuh'>Discovered Date</td>
			   ";
			   
		$RowClass = "lr";
		while ($row=mysql_fetch_array($result)) {
				print "<tr class='" .$RowClass. "'>
				<td><a href=?a=item&id=".$row["item_id"]." id='" . $row["item_id"] . "'>";
				
				if(file_exists(getcwd(). "/icons/item_". $row['icon'] . ".png")){
					echo "<img src='".$icons_url. "item_" . $row['icon'].".png' align='left'/ width='20' height='20'>  ";
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
		print "</table>";
		
		print "<tr><td align='center'>";
		print Pagination($targetpage, $page, $total_pages, $MaxResultsPerPage, $AdjacentPages);
		print "</td></tr>";

		//print "</td><td width=0% nowrap>";
		print "</td></tr></table>";
	}
	

?>


