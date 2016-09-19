<?php
/** If the parameter 'isearch' is set, queries for the items matching the criterias and displays them, along with an item search form.
 *    If only one and only one item is found then this item is displayed.
 *  If 'isearch' is not set, displays a search item form.
 *  If no criteria is set then it is equivalent to searching for all items.
 *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'iname'.
 */

$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
$iclass = (isset($_GET['iclass']) ? addslashes($_GET['iclass']) : '');
$irace = (isset($_GET['irace']) ? addslashes($_GET['irace']) : '');
$islot = (isset($_GET['islot']) ? addslashes($_GET['islot']) : '');
$istat1 = (isset($_GET['istat1']) ? addslashes($_GET['istat1']) : '');
$istat1comp = (isset($_GET['istat1comp']) ? addslashes($_GET['istat1comp']) : '');
$istat1value = (isset($_GET['istat1value']) ? addslashes($_GET['istat1value']) : '');
$istat2 = (isset($_GET['istat2']) ? addslashes($_GET['istat2']) : '');
$istat2comp = (isset($_GET['istat2comp']) ? addslashes($_GET['istat2comp']) : '');
$istat2value = (isset($_GET['istat2value']) ? addslashes($_GET['istat2value']) : '');
$iresists = (isset($_GET['iresists']) ? addslashes($_GET['iresists']) : '');
$iresistscomp = (isset($_GET['iresistscomp']) ? addslashes($_GET['iresistscomp']) : '');
$iresistsvalue = (isset($_GET['iresistsvalue']) ? addslashes($_GET['iresistsvalue']) : '');
$iheroics = (isset($_GET['iheroics']) ? addslashes($_GET['iheroics']) : '');
$iheroicscomp = (isset($_GET['iheroicscomp']) ? addslashes($_GET['iheroicscomp']) : '');
$iheroicsvalue = (isset($_GET['iheroicsvalue']) ? addslashes($_GET['iheroicsvalue']) : '');
$imod = (isset($_GET['imod']) ? addslashes($_GET['imod']) : '');
$imodcomp = (isset($_GET['imodcomp']) ? addslashes($_GET['imodcomp']) : '');
$imodvalue = (isset($_GET['imodvalue']) ? addslashes($_GET['imodvalue']) : '');
$itype = (isset($_GET['itype']) ? addslashes($_GET['itype']) : -1);
$iaugslot = (isset($_GET['iaugslot']) ? addslashes($_GET['iaugslot']) : '');
$ieffect = (isset($_GET['ieffect']) ? addslashes($_GET['ieffect']) : '');
$ireqlevel = (isset($_GET['ireqlevel']) ? addslashes($_GET['ireqlevel']) : '');
$iminlevel = (isset($_GET['iminlevel']) ? addslashes($_GET['iminlevel']) : '');
$inodrop = (isset($_GET['inodrop']) ? addslashes($_GET['inodrop']) : '');
$iavailability = (isset($_GET['iavailability']) ? addslashes($_GET['iavailability']) : '');
$iavailevel = (isset($_GET['iavailevel']) ? addslashes($_GET['iavailevel']) : '');
$ideity = (isset($_GET['ideity']) ? addslashes($_GET['ideity']) : '');

if ($isearch != "") {
    $query = "SELECT $items_table.* FROM ($items_table";

    if ($discovered_items_only == TRUE) {
        $query .= ",discovered_items";
    }

    if ($iavailability == 1) {
        // mob dropped
        $query .= ",$loot_drop_entries_table,$loot_table_entries,$npc_types_table";
    }
    $query .= ")";
    $s = " WHERE";
    if ($ieffect != "") {
        $effect = "%" . str_replace(',', '%', str_replace(' ', '%', addslashes($ieffect))) . "%";

        $query .= " LEFT JOIN $spells_table AS proc_s ON proceffect=proc_s.id";
        $query .= " LEFT JOIN $spells_table AS worn_s ON worneffect=worn_s.id";
        $query .= " LEFT JOIN $spells_table AS focus_s ON focuseffect=focus_s.id";
        $query .= " LEFT JOIN $spells_table AS click_s ON clickeffect=click_s.id";
        $query .= " WHERE (proc_s.`name` LIKE '$effect'
				OR worn_s.`name` LIKE '$effect'
				OR focus_s.`name` LIKE '$effect'
				OR click_s.`name` LIKE '$effect') ";
        $s = "AND";
    }
    if (($istat1 != "") AND ($istat1value != "")) {
        if ($istat1 == "ratio") {
            $query .= " $s ($items_table.delay/$items_table.damage $istat1comp $istat1value) AND ($items_table.damage>0)";
            $s = "AND";
        } else {
            $query .= " $s ($items_table.$istat1 $istat1comp $istat1value)";
            $s = "AND";
        }
    }
    if (($istat2 != "") AND ($istat2value != "")) {
        if ($istat2 == "ratio") {
            $query .= " $s ($items_table.delay/$items_table.damage $istat2comp $istat2value) AND ($items_table.damage>0)";
            $s = "AND";
        } else {
            $query .= " $s ($items_table.$istat2 $istat2comp $istat2value)";
            $s = "AND";
        }
    }
    if (($imod != "") AND ($imodvalue != "")) {
        $query .= " $s ($items_table.$imod $imodcomp $imodvalue)";
        $s = "AND";
    }
    if ($iavailability == 1) // mob dropped
    {
        $query .= " $s $loot_drop_entries_table.item_id=$items_table.id
				AND $loot_table_entries.lootdrop_id=$loot_drop_entries_table.lootdrop_id
				AND $loot_table_entries.loottable_id=$npc_types_table.loottable_id";
        if ($iavaillevel > 0) {
            $query .= " AND $npc_types_table.level<=$iavaillevel";
        }
        $s = "AND";
    }
    if ($iavailability == 2) // merchant sold
    {
        $query .= ",$merchant_list_table $s $merchant_list_table.item=$items_table.id";
        $s = "AND";
    }
    if ($discovered_items_only == TRUE) {
        $query .= " $s discovered_items.item_id=$items_table.id";
        $s = "AND";
    }
    if ($iname != "") {
        $name = addslashes(str_replace("_", "%", str_replace(" ", "%", $iname)));
        $query .= " $s ($items_table.Name like '%" . $name . "%')";
        $s = "AND";
    }
    if ($iclass > 0) {
        $query .= " $s ($items_table.classes & $iclass) ";
        $s = "AND";
    }
    if ($ideity > 0) {
        $query .= " $s ($items_table.deity   & $ideity) ";
        $s = "AND";
    }
    if ($irace > 0) {
        $query .= " $s ($items_table.races   & $irace) ";
        $s = "AND";
    }
    if ($itype >= 0) {
        $query .= " $s ($items_table.itemtype=$itype) ";
        $s = "AND";
    }
    if ($islot > 0) {
        $query .= " $s ($items_table.slots   & $islot) ";
        $s = "AND";
    }
    if ($iaugslot > 0) {
        $AugSlot = pow(2, $iaugslot) / 2;
        $query .= " $s ($items_table.augtype & $AugSlot) ";
        $s = "AND";
    }
    if ($iminlevel > 0) {
        $query .= " $s ($items_table.reqlevel>=$iminlevel) ";
        $s = "AND";
    }
    if ($ireqlevel > 0) {
        $query .= " $s ($items_table.reqlevel<=$ireqlevel) ";
        $s = "AND";
    }
    if ($inodrop) {
        $query .= " $s ($items_table.nodrop=1)";
        $s = "AND";
    }
    $query .= " GROUP BY $items_table.id ORDER BY $items_table.Name LIMIT " . (get_max_query_results_count($max_items_returned) + 1);
    $QueryResult = db_mysql_query($query) or message_die('items.php', 'MYSQL_QUERY', $query, mysql_error());

    if (mysql_num_rows($QueryResult) == 1) {
        $row = mysql_fetch_array($QueryResult);
        header("Location: ?a=item&id=" . $row["id"]);
        exit();
    }
} else {
    $iname = "";
}

/** Here the following holds :
 *    $QueryResult : items queried for if any query was issued, otherwise it is not defined
 *    $i* : previously-typed criterias, or empty by default
 *    $isearch is set if a query was issued
 */

$page_title = "Item Search";

$print_buffer .= "<table ><tr><td style='toggle_btn'></td></tr></table>";
$print_buffer .= "<table class='display_table container_div' border='0' style='width:900px !important;' cellpadding='15'>\n";

$print_buffer .= "<tbody id='myTbody'>";
// Hide the search fields when results show

$print_buffer .= "<form method='GET' action='" . $PHP_SELF . "' class='item_search'>\n";
$print_buffer .= '<input type="hidden" name="a" value="items">';
// Split into 2 tables side by side
$print_buffer .= "<tr><td><table border='0' width='0%'>";
$print_buffer .= "<tr><td style='text-align:right'><b>Name</b></td><td><input type='text' value=\"$iname\" style='width:100%' name='iname'/></td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Class</b></td><td>";
$print_buffer .= SelectIClass("iclass", $iclass);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Race</b></td><td>";
$print_buffer .= SelectRace("irace", $irace);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Slot</b></td><td>";
$print_buffer .= SelectSlot("islot", $islot);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Stats</b></td>\n";
$print_buffer .= "  <td>";
$print_buffer .= SelectStats("istat1", $istat1);
$print_buffer .= "\n";
$print_buffer .= "    <select name='istat1comp'>\n";
$print_buffer .= "      <option value='&gt;='" . ($istat1comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>\n";
$print_buffer .= "      <option value='&lt;='" . ($istat1comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>\n";
$print_buffer .= "      <option value='='" . ($istat1comp == '=' ? " selected='1'" : "") . ">=</option>\n";
$print_buffer .= "      <option value='&lt'" . ($istat1comp == '<' ? " selected='1'" : "") . ">&lt</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "    <input type='text' size='4' name='istat1value' value='" . $istat1value . "'/>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Stats</b></td>\n";
$print_buffer .= "  <td>";
$print_buffer .= SelectStats("istat2", $istat2);
$print_buffer .= "\n";
$print_buffer .= "    <select name='istat2comp'>\n";
$print_buffer .= "      <option value='&gt;='" . ($istat2comp == '>=' ? " selected='1'" : "") . ">&gt;=</option>\n";
$print_buffer .= "      <option value='&lt;='" . ($istat2comp == '<=' ? " selected='1'" : "") . ">&lt;=</option>\n";
$print_buffer .= "      <option value='='" . ($istat2comp == '=' ? " selected='1'" : "") . ">=</option>\n";
$print_buffer .= "      <option value='&lt'" . ($istat2comp == '<' ? " selected='1'" : "") . ">&lt</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "    <input type='text' size='4' name='istat2value' value='" . $istat2value . "'/>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Resists</b></td>\n";
$print_buffer .= "  <td>";
$print_buffer .= SelectResists("iresists", $iresists);
$print_buffer .= "\n";
$print_buffer .= "    <select name='iresistscomp'>\n";
$print_buffer .= "      <option value='&gt;='" . ($iresistscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>\n";
$print_buffer .= "      <option value='&lt;='" . ($iresistscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>\n";
$print_buffer .= "      <option value='='" . ($iresistscomp == '=' ? " selected='1'" : "") . ">=</option>\n";
$print_buffer .= "      <option value='&lt'" . ($iresistscomp == '<' ? " selected='1'" : "") . ">&lt</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "    <input type='text' size='4' name='iresistsvalue' value='" . $iresistsvalue . "'/>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Heroic Stats</b></td>\n";
$print_buffer .= "  <td>";
$print_buffer .= SelectHeroicStats("iheroics", $iheroics);
$print_buffer .= "\n";
$print_buffer .= "    <select name='iheroicscomp'>\n";
$print_buffer .= "      <option value='&gt;='" . ($iheroicscomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>\n";
$print_buffer .= "      <option value='&lt;='" . ($iheroicscomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>\n";
$print_buffer .= "      <option value='='" . ($iheroicscomp == '=' ? " selected='1'" : "") . ">=</option>\n";
$print_buffer .= "      <option value='&lt'" . ($iheroicscomp == '<' ? " selected='1'" : "") . ">&lt</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "    <input type='text' size='4' name='iheroicsvalue' value='" . $iheroicsvalue . "'/>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Modifiers</b></td>\n";
$print_buffer .= "  <td>";
$print_buffer .= SelectModifiers("imod", $imod);
$print_buffer .= "\n";
$print_buffer .= "    <select name='imodcomp'>\n";
$print_buffer .= "      <option value='&gt;='" . ($imodcomp == '>=' ? " selected='1'" : "") . ">&gt;=</option>\n";
$print_buffer .= "      <option value='&lt;='" . ($imodcomp == '<=' ? " selected='1'" : "") . ">&lt;=</option>\n";
$print_buffer .= "      <option value='='" . ($imodcomp == '=' ? " selected='1'" : "") . ">=</option>\n";
$print_buffer .= "      <option value='&lt'" . ($imodcomp == '<' ? " selected='1'" : "") . ">&lt</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "    <input type='text' size='4' name='imodvalue' value='" . $imodvalue . "'/>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "</td></tr></table></td><td>";
// Left Table End and Right Table Start
$print_buffer .= "<table border='0' width='0%'>";
$print_buffer .= "<tr><td style='text-align:right'><b>Item Type</b></td><td>";
$print_buffer .= SelectIType("itype", $itype);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Augmentation Type</b></td><td>";
$print_buffer .= SelectAugSlot("iaugslot", $iaugslot);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>With Effect</b></td><td><input type='text' value='" . $ieffect . "' size='30' name='ieffect'/></td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Min Required Level</b></td><td>\n";
$print_buffer .= SelectLevel("iminlevel", $server_max_level, $iminlevel);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Max Required Level</b></td><td>\n";
$print_buffer .= SelectLevel("ireqlevel", $server_max_level, $ireqlevel);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Tradeable Items Only</b></td><td><input type='checkbox' name='inodrop'" . ($inodrop ? " checked='1'" : "") . "/></td></tr>\n";
$print_buffer .= "<tr>\n";
$print_buffer .= "  <td style='text-align:right'><b>Item availability</b></td>\n";
$print_buffer .= "  <td>\n";
$print_buffer .= "    <select name='iavailability' style='width:100%'>\n";
$print_buffer .= "      <option value='0' " . ($iavailability == 0 ? " selected='1'" : "") . ">-</option>\n";
$print_buffer .= "      <option value='1' " . ($iavailability == 1 ? " selected='1'" : "") . ">Mob Dropped</option>\n";
$print_buffer .= "      <option value='2' " . ($iavailability == 2 ? " selected='1'" : "") . ">Merchant Sold</option>\n";
$print_buffer .= "    </select>\n";
$print_buffer .= "  </td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Max Level</b></td><td>";
$print_buffer .= SelectLevel("iavaillevel", $server_max_level, $iavaillevel);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "<tr><td style='text-align:right'><b>Deity</b></td><td>";
$print_buffer .= SelectDeity("ideity", $ideity);
$print_buffer .= "</td></tr>\n";
$print_buffer .= "</td></tr></table>";
$print_buffer .= "<tr><td colspan='2'>
    <a class='button' onclick='$(\".item_search\").submit();' ignore-pjax='1'>Search</a>
    <a class='button' href='?a=items'>Reset</a>
    <input type='hidden' name='isearch' value='1'>
</td></tr>\n";
$print_buffer .= "</form>\n";
$print_buffer .= "</tbody>";
$print_buffer .= "</table><center>\n";

// Print the query results if any
if (isset($QueryResult)) {

    $Tableborder = 0;

    $num_rows = mysql_num_rows($QueryResult);
    $total_row_count = $num_rows;
    if ($num_rows > get_max_query_results_count($max_items_returned)) {
        $num_rows = get_max_query_results_count($max_items_returned);
    }
    $print_buffer .= "";
    if ($num_rows == 0) {
        $print_buffer .= "<b>No items found...</b><br>";
    } else {
        $OutOf = "";
        if ($total_row_count > $num_rows) {
            $OutOf = " (Searches are limited to 100 Max Results)";
        }
        # $print_buffer .= "<b>" . $num_rows . " " . ($num_rows == 1 ? "item" : "items") . " displayed</b>" . $OutOf . "<br>";
        $print_buffer .= "<br>";

        $print_buffer .= "<table cellpadding='5' class='display_table container_div' style='width:700px'>";
        $print_buffer .= "<tr>
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
        for ($count = 1; $count <= $num_rows; $count++) {
            $TableData = "";
            $row = mysql_fetch_array($QueryResult);
            $TableData .= "<tr valign='top' class='" . $RowClass . "'><td>";
            if (file_exists($icons_dir . "item_" . $row["icon"] . ".png")) {
                $TableData .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left'/>";
            } else {
                $TableData .= "<img src='" . $icons_url . "item_.gif' align='left'/>";
            }
            $TableData .= "</td><td>";

            # CreateToolTip($row["id"], return_item_stat_box($row, 1));
            $TableData .= "<a href='?a=item&id=" . $row["id"] . "' id='" . $row["id"] . "'>" . $row["Name"] . "</a>";

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

            if ($RowClass == "lr") {
                $RowClass = "dr";
            } else {
                $RowClass = "lr";
            }

            $print_buffer .= $TableData;
        }
        $print_buffer .= "</table>";
    }
}


?>
