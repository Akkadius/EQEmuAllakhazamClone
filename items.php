<?php
	 /** If the parameter 'isearch' is set, queries for the items matching the criterias and displays them, along with an item search form.
	  *    If only one and only one item is found then this item is displayed.
	  *  If 'isearch' is not set, displays a search item form.
	  *  If no criteria is set then it is equivalent to searching for all items.
	  *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'iname'.
	  */
	include('./includes/config.php');
	include($includes_dir.'constantes.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'functions.php');

	
	$isearch       = (isset($_GET[       'isearch']) ? $_GET[       'isearch'] : '');
	$iname         = (isset($_GET[         'iname']) ? $_GET[         'iname'] : '');
	$iclass        = (isset($_GET[        'iclass']) ? addslashes($_GET[        'iclass']) : '');
	$irace         = (isset($_GET[         'irace']) ? addslashes($_GET[         'irace']) : '');
	$islot         = (isset($_GET[         'islot']) ? addslashes($_GET[         'islot']) : '');
	$istat1        = (isset($_GET[        'istat1']) ? addslashes($_GET[        'istat1']) : '');
	$istat1comp    = (isset($_GET[    'istat1comp']) ? addslashes($_GET[    'istat1comp']) : '');
	$istat1value   = (isset($_GET[   'istat1value']) ? addslashes($_GET[   'istat1value']) : '');
	$istat2        = (isset($_GET[        'istat2']) ? addslashes($_GET[        'istat2']) : '');
	$istat2comp    = (isset($_GET[    'istat2comp']) ? addslashes($_GET[    'istat2comp']) : '');
	$istat2value   = (isset($_GET[   'istat2value']) ? addslashes($_GET[   'istat2value']) : '');
	$iresists      = (isset($_GET[      'iresists']) ? addslashes($_GET[      'iresists']) : '');
	$iresistscomp  = (isset($_GET[  'iresistscomp']) ? addslashes($_GET[  'iresistscomp']) : '');
	$iresistsvalue = (isset($_GET[ 'iresistsvalue']) ? addslashes($_GET[ 'iresistsvalue']) : '');
	$iheroics      = (isset($_GET[      'iheroics']) ? addslashes($_GET[      'iheroics']) : '');
	$iheroicscomp  = (isset($_GET[  'iheroicscomp']) ? addslashes($_GET[  'iheroicscomp']) : '');
	$iheroicsvalue = (isset($_GET[ 'iheroicsvalue']) ? addslashes($_GET[ 'iheroicsvalue']) : '');
	$imod          = (isset($_GET[          'imod']) ? addslashes($_GET[          'imod']) : '');
	$imodcomp      = (isset($_GET[      'imodcomp']) ? addslashes($_GET[      'imodcomp']) : '');
	$imodvalue     = (isset($_GET[     'imodvalue']) ? addslashes($_GET[     'imodvalue']) : '');
	$itype         = (isset($_GET[         'itype']) ? addslashes($_GET[         'itype']) : -1);
	$iaugslot      = (isset($_GET[      'iaugslot']) ? addslashes($_GET[      'iaugslot']) : '');
	$ieffect       = (isset($_GET[       'ieffect']) ? addslashes($_GET[       'ieffect']) : '');
	$ireqlevel     = (isset($_GET[     'ireqlevel']) ? addslashes($_GET[     'ireqlevel']) : '');
	$iminlevel     = (isset($_GET[     'iminlevel']) ? addslashes($_GET[     'iminlevel']) : '');
	$inodrop       = (isset($_GET[       'inodrop']) ? addslashes($_GET[       'inodrop']) : '');
	$iavailability = (isset($_GET[ 'iavailability']) ? addslashes($_GET[ 'iavailability']) : '');
	$iavailevel    = (isset($_GET[    'iavailevel']) ? addslashes($_GET[    'iavailevel']) : '');
	$ideity        = (isset($_GET[        'ideity']) ? addslashes($_GET[        'ideity']) : '');

	if($isearch != "")
	{
		$Query  = "SELECT $tbitems.* FROM ($tbitems";
				
		if ($DiscoveredItemsOnly==TRUE)
		{
			$Query .= ",discovered_items";
		}

		if($iavailability == 1)
		{
			// mob dropped
			$Query .= ",$tblootdropentries,$tbloottableentries,$tbnpctypes";
		}
		$Query  .= ")";
		$s = " WHERE";
		if($ieffect!="")
		{
			$effect="%".str_replace(',','%',str_replace(' ','%',addslashes($ieffect)))."%";
			$Query.=" LEFT JOIN $tbspells AS proc_s ON proceffect=proc_s.id";
			$Query.=" LEFT JOIN $tbspells AS worn_s ON worneffect=worn_s.id";
			$Query.=" LEFT JOIN $tbspells AS focus_s ON focuseffect=focus_s.id";
			$Query.=" LEFT JOIN $tbspells AS click_s ON clickeffect=click_s.id";
			$Query.=" WHERE (proc_s.name LIKE '$effect' 
				OR worn_s.name LIKE '$effect' 
				OR focus_s.name LIKE '$effect' 
				OR click_s.name LIKE '$effect') ";
			$s="AND";
		}     
		if(($istat1 != "") AND ($istat1value != ""))
		{
			if($istat1 == "ratio")
			{
				$Query .= " $s ($tbitems.delay/$tbitems.damage $istat1comp $istat1value) AND ($tbitems.damage>0)"; 
				$s="AND"; 
			}
			else
			{
				$Query .= " $s ($tbitems.$istat1 $istat1comp $istat1value)"; 
				$s="AND"; 
			}
		}
		if(($istat2 != "") AND ($istat2value != ""))
		{
			if($istat2 == "ratio")
			{
				$Query .= " $s ($tbitems.delay/$tbitems.damage $istat2comp $istat2value) AND ($tbitems.damage>0)"; 
				$s="AND"; 
			}
			else
			{
				$Query .= " $s ($tbitems.$istat2 $istat2comp $istat2value)"; 
				$s="AND"; 
			}
		}
		if(($imod != "") AND ($imodvalue != ""))
		{
			$Query .= " $s ($tbitems.$imod $imodcomp $imodvalue)"; 
			$s="AND"; 
		}
		if($iavailability == 1) // mob dropped
		{
			$Query .= " $s $tblootdropentries.item_id=$tbitems.id
				AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
				AND $tbloottableentries.loottable_id=$tbnpctypes.loottable_id"; 
			if($iavaillevel > 0)
			{
				$Query .= " AND $tbnpctypes.level<=$iavaillevel";
			}
			$s="AND";
		}
		if($iavailability == 2) // merchant sold
		{
			$Query .= ",$tbmerchantlist $s $tbmerchantlist.item=$tbitems.id"; 
			$s="AND";
		}
		if ($DiscoveredItemsOnly==TRUE)
		{
			$Query .= " $s discovered_items.item_id=$tbitems.id"; 
			$s="AND"; 
		}
		if($iname != "")
		{
			$name = addslashes(str_replace("_", "%", str_replace(" ","%",$iname)));
			$Query .= " $s ($tbitems.Name like '%".$name."%')"; $s="AND"; 
		}
		if($iclass > 0)    { $Query.=" $s ($tbitems.classes & $iclass) ";    $s="AND"; }
		if($ideity > 0)    { $Query.=" $s ($tbitems.deity   & $ideity) ";    $s="AND"; }
		if($irace > 0)     { $Query.=" $s ($tbitems.races   & $irace) ";     $s="AND"; }
		if($itype >= 0)    { $Query.=" $s ($tbitems.itemtype=$itype) ";      $s="AND"; }
		if($islot > 0)     { $Query.=" $s ($tbitems.slots   & $islot) ";     $s="AND"; }
		if($iaugslot > 0)
		{
			$AugSlot = pow(2, $iaugslot) / 2;
			$Query.=" $s ($tbitems.augtype & $AugSlot) ";  $s="AND";
		}
		if($iminlevel > 0) { $Query.=" $s ($tbitems.reqlevel>=$iminlevel) "; $s="AND"; }
		if($ireqlevel > 0) { $Query.=" $s ($tbitems.reqlevel<=$ireqlevel) "; $s="AND"; }
		if($inodrop)       { $Query.=" $s ($tbitems.nodrop=1)";              $s="AND"; }
		$Query.=" GROUP BY $tbitems.id ORDER BY $tbitems.Name LIMIT ".(LimitToUse($MaxItemsReturned) + 1);
		$QueryResult = mysql_query($Query) or message_die('items.php','MYSQL_QUERY',$Query,mysql_error());

		if(mysql_num_rows($QueryResult) == 1)
		{
			$row = mysql_fetch_array($QueryResult);
			header("Location: item.php?id=".$row["id"]);
			exit();
		}
	}
	else
	{
		$iname = "";
	}

	/** Here the following holds :
	 *    $QueryResult : items queried for if any query was issued, otherwise it is not defined
	 *    $i* : previously-typed criterias, or empty by default
	 *    $isearch is set if a query was issued
	 */

	$Title="Item Search";
	$XhtmlCompliant = TRUE;
	include($includes_dir.'headers.php');

	echo "<center><table><tr><td style='toggle_btn'><a href='#' onclick='toggleItem(\"myTbody\")'><b><img src='" . $images_url . "dropdown.png' align='left' width='20' height='20'/>(Toggle Search Fields)</b></a></td></tr></table></center>";
	echo "<center><table border='0' width='0%' cellpadding='15'>\n";

	echo "<tbody id='myTbody'>";
	// Hide the search fields when results show

	echo "<form method='GET' action='".$PHP_SELF."'>\n";
	// Split into 2 tables side by side
	echo "<tr><td><table border='0' width='0%'>";
	echo "<tr><td nowrap='1'><b>Name : </b></td><td><input type='text' value=\"$iname\" size='30' name='iname'/></td></tr>\n";
	echo "<tr><td nowrap='1'><b>Class : </b></td><td>"; SelectIClass("iclass", $iclass); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Race : </b></td><td>";  SelectRace  ("irace",   $irace); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Slot : </b></td><td>";  SelectSlot  ("islot",   $islot); echo "</td></tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Stats : </b></td>\n";
	echo "  <td>"; SelectStats("istat1", $istat1); echo "\n";
	echo "    <select name='istat1comp'>\n";
	echo "      <option value='&gt;='".   ($istat1comp == '>=' ? " selected='1'" : "").">&gt;=</option>\n";
	echo "      <option value='&lt;='".($istat1comp == '<=' ? " selected='1'" : "").">&lt;=</option>\n";
	echo "      <option value='='".    ($istat1comp == '='  ? " selected='1'" : "").">=</option>\n";
	echo "      <option value='&lt'".($istat1comp == '<' ? " selected='1'" : "").">&lt</option>\n";
	echo "    </select>\n";
	echo "    <input type='text' size='4' name='istat1value' value='".$istat1value."'/>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Stats : </b></td>\n";
	echo "  <td>"; SelectStats("istat2",$istat2); echo "\n";
	echo "    <select name='istat2comp'>\n";
	echo "      <option value='&gt;='".   ($istat2comp == '>=' ? " selected='1'" : "").">&gt;=</option>\n";
	echo "      <option value='&lt;='".($istat2comp == '<=' ? " selected='1'" : "").">&lt;=</option>\n";
	echo "      <option value='='".    ($istat2comp == '='  ? " selected='1'" : "").">=</option>\n";
	echo "      <option value='&lt'".($istat2comp == '<' ? " selected='1'" : "").">&lt</option>\n";
	echo "    </select>\n";
	echo "    <input type='text' size='4' name='istat2value' value='".$istat2value."'/>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Resists : </b></td>\n";
	echo "  <td>"; SelectResists("iresists",$iresists); echo "\n";
	echo "    <select name='iresistscomp'>\n";
	echo "      <option value='&gt;='".   ($iresistscomp == '>=' ? " selected='1'" : "").">&gt;=</option>\n";
	echo "      <option value='&lt;='".($iresistscomp == '<=' ? " selected='1'" : "").">&lt;=</option>\n";
	echo "      <option value='='".    ($iresistscomp == '='  ? " selected='1'" : "").">=</option>\n";
	echo "      <option value='&lt'".($iresistscomp == '<' ? " selected='1'" : "").">&lt</option>\n";
	echo "    </select>\n";
	echo "    <input type='text' size='4' name='iresistsvalue' value='".$iresistsvalue."'/>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Heroic Stats : </b></td>\n";
	echo "  <td>"; SelectHeroicStats("iheroics",$iheroics); echo "\n";
	echo "    <select name='iheroicscomp'>\n";
	echo "      <option value='&gt;='".   ($iheroicscomp == '>=' ? " selected='1'" : "").">&gt;=</option>\n";
	echo "      <option value='&lt;='".($iheroicscomp == '<=' ? " selected='1'" : "").">&lt;=</option>\n";
	echo "      <option value='='".    ($iheroicscomp == '='  ? " selected='1'" : "").">=</option>\n";
	echo "      <option value='&lt'".($iheroicscomp == '<' ? " selected='1'" : "").">&lt</option>\n";
	echo "    </select>\n";
	echo "    <input type='text' size='4' name='iheroicsvalue' value='".$iheroicsvalue."'/>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Modifiers : </b></td>\n";
	echo "  <td>"; SelectModifiers("imod",$imod); echo "\n";
	echo "    <select name='imodcomp'>\n";
	echo "      <option value='&gt;='".   ($imodcomp == '>=' ? " selected='1'" : "").">&gt;=</option>\n";
	echo "      <option value='&lt;='".($imodcomp == '<=' ? " selected='1'" : "").">&lt;=</option>\n";
	echo "      <option value='='".    ($imodcomp == '='  ? " selected='1'" : "").">=</option>\n";
	echo "      <option value='&lt'".($imodcomp == '<' ? " selected='1'" : "").">&lt</option>\n";
	echo "    </select>\n";
	echo "    <input type='text' size='4' name='imodvalue' value='".$imodvalue."'/>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</td></tr></table></td><td>";
	// Left Table End and Right Table Start
	echo "<table border='0' width='0%'>";
	echo "<tr><td nowrap='1'><b>Item Type : </b></td><td>"; SelectIType("itype",$itype); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Augmentation Type : </b></td><td>"; SelectAugSlot("iaugslot",$iaugslot); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>With Effect : </b></td><td><input type='text' value='".$ieffect."' size='30' name='ieffect'/></td></tr>\n";
	echo "<tr><td nowrap='1'><b>Min Required Level : </b></td><td>\n"; SelectLevel("iminlevel",$ServerMaxLevel,$iminlevel); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Max Required Level : </b></td><td>\n"; SelectLevel("ireqlevel",$ServerMaxLevel,$ireqlevel); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Tradeable Items Only : </b></td><td><input type='checkbox' name='inodrop'".($inodrop?" checked='1'":"")."/></td></tr>\n";
	echo "<tr>\n";
	echo "  <td nowrap='1'><b>Item availability : </b></td>\n";
	echo "  <td>\n";
	echo "    <select name='iavailability'>\n";
	echo "      <option value='0' ".($iavailability==0?" selected='1'":"").">-</option>\n";
	echo "      <option value='1' ".($iavailability==1?" selected='1'":"").">Mob Dropped</option>\n";
	echo "      <option value='2' ".($iavailability==2?" selected='1'":"").">Merchant Sold</option>\n";
	echo "    </select>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "<tr><td nowrap='1'><b>Max Level : </b></td><td>"; SelectLevel("iavaillevel",$ServerMaxLevel,$iavaillevel); echo "</td></tr>\n";
	echo "<tr><td nowrap='1'><b>Deity : </b></td><td>"; SelectDeity("ideity",$ideity); echo "</td></tr>\n";
	echo "</td></tr></table>";
	echo "<tr align='center'><td nowrap='1' colspan='2'><input type='submit' value='Search' name='isearch'/>&nbsp;<input type='reset' value='Reset'/></td></tr>\n";
	echo "</form>\n";
	echo "</tbody>";
	echo "</table>\n";

	// Print the query results if any
	if(isset($QueryResult)) 
	{

		$Tableborder = 0;

		$num_rows = mysql_num_rows($QueryResult);
		$total_row_count = $num_rows;
		if($num_rows > LimitToUse($MaxItemsReturned))
		{
			$num_rows = LimitToUse($MaxItemsReturned);
		}
		echo "<center>";
		if($num_rows == 0)
		{
			echo "<b>No items found...</b><br>";
		}
		else
		{
			$OutOf = "";
			if ($total_row_count > $num_rows)
			{
				$OutOf = " (Searches are limited to 100 Max Results)";
			}
			echo "<b>" . $num_rows . " " . ($num_rows == 1 ? "item" : "items") . " displayed</b>" . $OutOf . "<br>";
			echo "</center>";

			echo "<center><table border='$Tableborder' cellpadding='5' width='0%'>";
			echo "<tr>
					<th class='menuh'>Icon</th>
					<th class='menuh'>Item Name</th>
					<th class='menuh'>Item Type</th>
					<th class='menuh'>AC</th>
					<th class='menuh'>HPs</th>
					<th class='menuh'>Mana</th>
					<th class='menuh'>Damage</th>
					<th class='menuh'>Delay</th>
					<th class='menuh'>Item ID</th>
					</tr>";
			$RowClass = "lr";
			for( $count = 1 ; $count <= $num_rows ; $count++ )
			{
				$TableData = "";
				$row = mysql_fetch_array($QueryResult);
				$TableData .= "<tr valign='top' class='".$RowClass."'><td>";
				if(file_exists(getcwd() . "/icons/item_" . $row["icon"] . ".gif"))
				{ 
					$TableData .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".gif' align='left'/>";
				}
				else
				{
					$TableData .= "<img src='" . $icons_url . "item_.gif' align='left'/>"; 
				}
				$TableData .= "</td><td>";

				CreateToolTip($row["id"], BuildItemStats($row, 1));
				$TableData .= "<a href='item.php?id=" . $row["id"] . "' id='" . $row["id"] . "'>" . $row["Name"] . "</a>";
				
				$TableData .= "</td><td>";
				$TableData .= $dbitypes[$row["itemtype"]];
				$TableData .= "</td><td>";
				$TableData .= $row["ac"];
				$TableData .= "</td><td>";
				$TableData .= $row["hp"];
				$TableData .= "</td><td>";
				$TableData .= $row["mana"];
				$TableData .= "</td><td>";
				$TableData .= $row["damage"];
				$TableData .= "</td><td>";
				$TableData .= $row["delay"];
				
				$TableData .= "</td><td>";
				$TableData .= $row["id"];
				$TableData .= "</td></tr>";
				 
				if ($RowClass == "lr")
				{
					$RowClass = "dr";
				}
				else
				{
					$RowClass = "lr";
				}
				
				print $TableData;
			}
			echo "</table></center>";
		}
	}

	include($includes_dir."footers.php");

?>
