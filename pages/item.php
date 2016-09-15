<?php
/** Displays the item identified by 'id' if it is specified and a item by this ID exists.
 *  Otherwise queries for the items identified by 'name'. Underscores are considered as spaces, for Wiki compatibility.
 *    If exactly one item is found, displays this item.
 *    Otherwise redirects to the item search page, displaying the results for '%name%'.
 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid item ID, redirects to the item search page.
 */


$id = (isset($_GET['id']) ? addslashes($_GET['id']) : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    if ($discovered_items_only == TRUE) {
        $Query = "SELECT * FROM $tbitems, discovered_items WHERE $tbitems.id='" . $id . "' AND discovered_items.item_id=$tbitems.id";
    } else {
        $Query = "SELECT * FROM $tbitems WHERE id='" . $id . "'";
    }
    $QueryResult = mysql_query($Query) or message_die('item.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: items.php");
        exit();
    }
    $ItemRow = mysql_fetch_array($QueryResult);
    $name = $ItemRow["name"];
} elseif ($name != "") {
    if ($discovered_items_only == TRUE) {
        $Query = "SELECT * FROM $tbitems, discovered_items WHERE $tbitems.name like '$name' AND discovered_items.item_id=$tbitems.id";
    } else {
        $Query = "SELECT * FROM $tbitems WHERE name like '$name'";
    }
    $QueryResult = mysql_query($Query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: items.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $ItemRow = mysql_fetch_array($QueryResult);
        $id = $ItemRow["id"];
        $name = $ItemRow["name"];
    }
} else {
    header("Location: items.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the item to display
 *    $name : name of the item to display
 *    $ItemRow : row of the item to display extracted from the database
 *    The item actually exists
 */

$Title = str_replace('_', ' ', GetFieldByQuery("Name", "SELECT Name FROM $tbitems WHERE id=$id"));

$item = $ItemRow;

print "<table style='width:500px' class='container_div' >\n";

// Title and Icon bar
print "<tr valign='top'>\n";
print "<td colspan='2' class='headerrow' style='padding: 10px;'>\n";

if (file_exists(getcwd() . "/icons/item_" . $item['icon'] . ".png")) {
    echo "<img src='" . $icons_url . "item_" . $item["icon"] . ".png' align='left'/>";
}

print "<img src='" . $images_url . "spacer_1.png' align='left'/><a href='http://lucy.allakhazam.com/item.html?id=" . $id . "'><img src='" . $images_url . "lucy.png' align='right'/></a>\n";
print "                  <b>" . $item["Name"] . "</b>";
if ($item["lore"] != "") {
    print "<br/>(" . $item["lore"] . ") - id : " . $id . "\n";
} else {
    print "<br/>id : " . $id . "\n";
}
print "</td>\n";
print "</tr>\n";
print "<tr valign='top'>\n";
print "<td>\n";
print "<table >\n";


// Prints all Item data into formatted tables
print BuildItemStats($item, 0);

print "<table style='width:100%'>";

// Discovered by
if ($discovered_items_only == TRUE) {
    $CharName = GetFieldByQuery("char_name", "SELECT char_name  FROM $tbdiscovereditems WHERE item_id=$id");
    $DiscoveredDate = GetFieldByQuery("discovered_date", "SELECT discovered_date  FROM $tbdiscovereditems WHERE item_id=$id");
    if ($charbrowser_url) {
        $DiscoveredBy = "<a href=" . $charbrowser_url . "character.php?char=" . $CharName . ">" . $CharName . "</a>";
    } else {
        $DiscoveredBy = $CharName;
    }
    if ($CharName != '') {
        print "<br><tr><td colspan='2' nowrap='1'><b>Discovered by: </b>$DiscoveredBy - " . date("m/d/y", $DiscoveredDate) . "</td></tr>";
    }
}

// places where to forage this item
$query = "SELECT $tbzones.short_name,$tbzones.long_name,$tbforage.chance,$tbforage.level
			FROM $tbzones,$tbforage
			WHERE $tbzones.zoneidnumber=$tbforage.zoneid
			AND $tbforage.itemid=$id
			GROUP BY $tbzones.zoneidnumber";
$result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    print "<tr class='myline' height='6'><td colspan='2'></td><tr>";
    print "<tr><td><h2 class='section_header'>This item can be foraged in:</h2>";
    while ($row = mysql_fetch_array($result)) {
        print "<li><a href='?a=zone&name=" . $row["short_name"] . "'>" . str_replace("_", " ", $row["long_name"]) . "</a></li>";
    }
    print "</td></tr>";
}

// trade skills for which that item is a component
$query = "SELECT $tbtradeskillrecipe.name,$tbtradeskillrecipe.id,$tbtradeskillrecipe.tradeskill
			FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries
			WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
			AND $tbtradeskillrecipeentries.item_id=$id
			AND $tbtradeskillrecipeentries.componentcount>0
			GROUP BY $tbtradeskillrecipe.id";
$result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
$trade_skill_return = "";
if (mysql_num_rows($result) > 0) {
    $trade_skill_return .= '<tr><td colspan="2"><h2 class="section_header">This item is used in tradeskill recipes</h2></td></tr>';
    $trade_skill_return .= "<tr><td><ul>";
    while ($row = mysql_fetch_array($result)) {
        $trade_skill_return .= "<li><a href='?a=recipe&id=" . $row["id"] . "'>" . str_replace("_", " ", $row["name"]) . "</a> (" . ucfirstwords($dbskills[$row["tradeskill"]]) . ")</li>";
    }
    $trade_skill_return .= "</ul></td></tr>";
}
print $trade_skill_return;


// trade skills which result is the component
$query = "SELECT $tbtradeskillrecipe.name,$tbtradeskillrecipe.id,$tbtradeskillrecipe.tradeskill
			FROM $tbtradeskillrecipe,$tbtradeskillrecipeentries
			WHERE $tbtradeskillrecipe.id=$tbtradeskillrecipeentries.recipe_id
			AND $tbtradeskillrecipeentries.item_id=$id
			AND $tbtradeskillrecipeentries.successcount>0
			GROUP BY $tbtradeskillrecipe.id";
$result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
$trade_skill_return = "";
if (mysql_num_rows($result) > 0) {
    $trade_skill_return .= "<tr><td><h2 class='section_header'>This item is the result of tradeskill recipes</h2><ul>";
    while ($row = mysql_fetch_array($result)) {
        $trade_skill_return .= "
            <li>
                <a href='?a=recipe&id=" . $row["id"] . "'>
                    " . str_replace("_", " ", $row["name"]) . "
                </a>
                (" . ucfirst(strtolower($dbskills[$row["tradeskill"]])) . ")
            </li>";
    }
    $trade_skill_return .= "</ul></td></tr>";
}
print $trade_skill_return;

if ($allow_quests_npc == TRUE) {
    // npcs that use that give that item as reward
    $query = "SELECT * FROM $tbquestitems WHERE item_id=$id AND rewarded>0";
    $result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        print "<tr><td><h2 class='section_header'>This item is from the result of a quest</h2></b><ul>";
        while ($res = mysql_fetch_array($result)) {
            print "<li><a href='" . $root_url . "quests/index.php?zone=" . $res["zone"] . "&amp;npc=" . $res["npc"] . "'>" . str_replace("_", " ", $res["npc"]) . "</a>";
            print ", <a href=$root_url" . "?a=zone&name=" . $res["zone"] . ">";
            print GetFieldByQuery("long_name", "SELECT long_name FROM $tbzones WHERE short_name='" . $res["zone"] . "'") . "</a></li>";
        }
        print "</ul></td></tr>";
    }

    // npcs that use that give that item as quest item
    $query = "SELECT * FROM $tbquestitems WHERE item_id=$id AND handed>0";
    $result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        print "<tr><td><b>This item is used in quests.</b></b><ul>";
        while ($res = mysql_fetch_array($result)) {
            print "<li><a href='" . $root_url . "quests/index.php?zone=" . $res["zone"] . "&amp;npc=" . $res["npc"] . "'>" . str_replace("_", " ", $res["npc"]) . "</a>";
            print ", <a href=$root_url" . "?a=zone&name=" . $res["zone"] . ">";
            print GetFieldByQuery("long_name", "SELECT long_name FROM $tbzones WHERE short_name='" . $res["zone"] . "'") . "</a></li>";
        }
        print "</ul></td></tr>";
    }
}

print "</table>\n";
print "</td>\n";
print "<td width='0%'>\n";
print "<table width='0%'>\n";

$Separator = "";

if ($item_found_info == TRUE) {
    // Check with a quick query before trying the long one
    $IsDropped = GetFieldByQuery("item_id", "SELECT item_id FROM $tblootdropentries WHERE item_id=$id LIMIT 1");

    if ($IsDropped) {
        // npcs dropping this (Very Heavy Query)
        $query = "SELECT $tbnpctypes.id,$tbnpctypes.name,
					$tbspawn2.zone,$tbzones.long_name,
					$tbloottableentries.multiplier,$tbloottableentries.probability,$tblootdropentries.chance
					FROM $tbnpctypes,$tbspawn2,$tbspawnentry,$tbloottableentries,$tblootdropentries,$tbzones
					WHERE $tbnpctypes.id=$tbspawnentry.npcID
					AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
					AND $tbnpctypes.loottable_id=$tbloottableentries.loottable_id
					AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
					AND $tblootdropentries.item_id=$id
					AND $tbzones.short_name=$tbspawn2.zone";
        if ($merchants_dont_drop_stuff == TRUE) {
            $query .= " AND $tbnpctypes.merchant_id=0";
        }
        foreach ($ignore_zones AS $zid) {
            $query .= " AND $tbzones.short_name!='$zid'";
        }
        $query .= " GROUP BY $tbspawnentry.npcID ORDER BY $tbzones.long_name ASC";
        $result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
        if (mysql_num_rows($result) > 0) {
            $DroppedList = "";
            $DroppedList .= $Separator;
            $DroppedList .= "<tr>\n";
            $DroppedList .= "<td><h2 class='section_header'>This item is dropped</h2>\n";
            $CurrentZone = "";
            while ($row = mysql_fetch_array($result)) {
                if ($CurrentZone != $row["zone"]) {
                    if ($CurrentZone != "") {
                        $DroppedList .= "</ul>\n";
                        $DroppedList .= "</ul>\n";
                    }
                    $DroppedList .= "<ul>\n";
                    $DroppedList .= "<li><b>in <a href='?a=zone&name=" . $row["zone"] . "'>" . $row["long_name"] . "</a> by </b></li>\n";
                    $DroppedList .= "<ul>\n";
                    $CurrentZone = $row["zone"];
                }
                $DroppedList .= "<li><a href='?a=npc&id=" . $row["id"] . "'>" . str_replace("_", " ", $row["name"]) . "</a>";
                if ($item_add_chance_to_drop) {
                    $DroppedList .= " (" . ($row["chance"] * $row["probability"] / 100) . "% x " . $row["multiplier"] . ")";
                }
                $DroppedList .= "</li>\n";
            }
            $DroppedList .= "</ul>\n";
            $DroppedList .= "</ul>\n";
            $DroppedList .= "</td>\n";
            $DroppedList .= "</tr>\n";

        }
    }

    // Check with a quick query before trying the long one
    $IsSold = GetFieldByQuery("item", "SELECT item FROM $tbmerchantlist WHERE item=$id LIMIT 1");

    if ($IsSold) {
        // npcs selling this (Very Heavy Query)
        $query = "SELECT $tbnpctypes.id,$tbnpctypes.name,$tbspawn2.zone,$tbzones.long_name,$tbnpctypes.class
					FROM $tbnpctypes,$tbmerchantlist,$tbspawn2,$tbzones,$tbspawnentry
					WHERE $tbmerchantlist.item=$id
					AND $tbnpctypes.id=$tbspawnentry.npcID
					AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
					AND $tbmerchantlist.merchantid=$tbnpctypes.merchant_id
					AND $tbzones.short_name=$tbspawn2.zone";
        $result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
        if (mysql_num_rows($result) > 0) {
            $MerchantList = "";
            $MerchantList .= $Separator;
            $MerchantList .= "<tr>\n";
            $MerchantList .= "<td><h2 class='section_header'>This item is sold:</h2>\n";
            $CurrentZone = "";
            while ($row = mysql_fetch_array($result)) {
                if ($CurrentZone != $row["zone"]) {
                    if ($CurrentZone != "") {
                        $MerchantList .= "</ul>\n";
                        $MerchantList .= "</ul>\n";
                    }
                    $MerchantList .= "<ul>\n";
                    $MerchantList .= "<li><b>in <a href='?a=zone&name=" . $row["zone"] . "'>" . $row["long_name"] . "</a> by </b></li>\n";
                    $MerchantList .= "<ul>\n";
                    $CurrentZone = $row["zone"];
                }
                $MerchantList .= "<li><a href='?a=npc&id=" . $row["id"] . "'>" . str_replace("_", " ", $row["name"]) . "</a>";
                if ($row["class"] == 41) $MerchantList .= " (" . price($item["price"]) . ")"; // NPC is a shopkeeper
                if ($row["class"] == 61) $MerchantList .= " (" . $item["ldonprice"] . " points)"; // NPC is a LDON merchant
                $MerchantList .= "</li>\n";
            }
            $MerchantList .= "</ul>\n";
            $MerchantList .= "</ul>\n";
            $MerchantList .= "</td>\n";
            $MerchantList .= "</tr>\n";

        }
    }
}


// spawn points if its a ground item
$query = "SELECT $tbgroundspawns.*,$tbzones.short_name,$tbzones.long_name
			FROM $tbgroundspawns,$tbzones
			WHERE item=$id
			AND $tbgroundspawns.zoneid=$tbzones.zoneidnumber";
$result = mysql_query($query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    print $Separator;
    print "<tr>\n";
    print "<td><h2 class='section_header'>This item spawns on the ground</h2>";
    $CurrentZone = "";
    while ($row = mysql_fetch_array($result)) {
        if ($CurrentZone != $row["short_name"]) {
            if ($CurrentZone != "") {
                print "</ul>\n";
            }
            print "<b><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a> at: </b>\n";
            print "<ul>\n";
            $CurrentZone = $row["short_name"];
        }
        print "<li>" . $row["max_y"] . " (Y), " . $row["max_x"] . " (X), " . $row["max_z"] . " (Z)</a></li>";
    }
    print "</ul>\n";
    print "</td>\n";
    print "</tr>\n";
}

print "</table>\n";
print $MerchantList;
print $DroppedList;
print "</td>\n";
print "</tr>\n";
print "</table>\n";

print "\n";

?>
