<?php
/** If no proper query is specified (see below), redirects to the item search page (error).
 *  If the parameter 'isearchtype' equals 'id' and 'iid' is set then queries for objects with exactly this ID and displays them.
 *  If the parameter 'isearchtype' equals 'name' and 'iname' is set then queries for objects with approximately this name and displays them.
 *  At the moment the object types supported are factions, NPCs and items.
 */

require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'mysql.php');
require_once($includes_dir . 'functions.php');

$iid = (isset($_GET['iid']) ? addslashes($_GET['iid']) : '');
$iname = (isset($_GET['iname']) ? addslashes($_GET['iname']) : '');
$isearchtype = (isset($_GET['isearchtype']) ? addslashes($_GET['isearchtype']) : '');

$iid = $_GET['search'];
$iname = $_GET['search'];
$isearchtype = 'name';

// Build the WHERE caluse
$Where = "";
if ($isearchtype == 'id' and $iid != "")
    $Where = "id='" . $iid . "'";
if ($isearchtype == 'name' and $iname != "")
    $Where = "name like '%" . str_replace('`', '%', str_replace('_', '%', str_replace(' ', '%', $iname))) . "%'";

if ($Where == "") {
    header("Location: ?a=items");
    exit();
}

// Query for factions
$Query = "SELECT $faction_list_table.id,$faction_list_table.name
        FROM $faction_list_table
        WHERE $Where
        ORDER BY $faction_list_table.name,$faction_list_table.id
        LIMIT " . (LimitToUse($MaxFactionsReturned) + 1);
$FoundFactions = mysql_query($Query) or message_die('fullsearch.php', 'MYSQL_QUERY', $Query, mysql_error());

// Query for Items
if ($discovered_items_only == TRUE) {
    $Query = "SELECT * FROM $items_table, discovered_items WHERE $items_table.id='" . $id . "' AND discovered_items.item_id=$items_table.id";
    $Query = "SELECT $items_table.id,$items_table.name
		FROM $items_table, discovered_items
		WHERE $Where
		AND discovered_items.item_id=$items_table.id
		ORDER BY $items_table.name,$items_table.id
		LIMIT " . (LimitToUse($max_items_returned) + 1);
} else {
    $Query = "SELECT $items_table.id,$items_table.name
		FROM $items_table
		WHERE $Where
		ORDER BY $items_table.name,$items_table.id
		LIMIT " . (LimitToUse($max_items_returned) + 1);
}
$FoundItems = mysql_query($Query) or message_die('fullsearch.php', 'MYSQL_QUERY', $Query, mysql_error());

// Query for NPCs
$Query = "SELECT $npc_types_table.id,$npc_types_table.name
        FROM $npc_types_table
        WHERE $Where
        ORDER BY $npc_types_table.name,$npc_types_table.id
        LIMIT " . (LimitToUse($max_npcs_returned) + 1);
$FoundNpcs = mysql_query($Query) or message_die('fullsearch.php', 'MYSQL_QUERY', $Query, mysql_error());


// In case only one object is found, redirect to its page
if (mysql_num_rows($FoundFactions) == 1
    and mysql_num_rows($FoundItems) == 0
    and mysql_num_rows($FoundNpcs) == 0
) {
    $FactionRow = mysql_fetch_array($FoundFactions);
    header("Location: faction.php?id=" . $FactionRow["id"]);
    exit();
}

if (mysql_num_rows($FoundFactions) == 0
    and mysql_num_rows($FoundItems) == 1
    and mysql_num_rows($FoundNpcs) == 0
) {
    $ItemRow = mysql_fetch_array($FoundItems);
    header("Location: ?a=item&id=" . $ItemRow["id"]);
    exit();
}

if (mysql_num_rows($FoundFactions) == 0
    and mysql_num_rows($FoundItems) == 0
    and mysql_num_rows($FoundNpcs) == 1
) {
    $NpcRow = mysql_fetch_array($FoundNpcs);
    header("Location: ?a=npc&id=" . $NpcRow["id"]);
    exit();
}


/** Here the following holds :
 *    $FoundFactions : factions found
 *    $FoundItems    : items    found
 *    $FoundNpcs     : NPCs     found
 */

$Title = "Search Results";

// Display found objects
print "          <table border='0' width='100%'>\n";
print "            <tr valign='top'>\n";
print "              <td='1' width='34%'>\n";
PrintQueryResults($FoundItems, $max_items_returned, "item.php", "item", "items", "id", "name");
print "              </td>\n";
print "              <td='1' width='33%'>\n";
PrintQueryResults($FoundNpcs, $max_npcs_returned, "npc.php", "NPC", "NPCs", "id", "name");
print "              </td>\n";
print "              <td='1' width='33%'>\n";
PrintQueryResults($FoundFactions, $MaxFactionsReturned, "faction.php", "faction", "factions", "id", "name");
print "              </td>\n";
print "            </tr>\n";
print "          </table>\n";


?>
