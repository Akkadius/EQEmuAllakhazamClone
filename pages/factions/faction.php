<?php

/** Displays the faction identified by 'id' if it is specified and a faction by this ID exists.
 *  Otherwise queries for the factions identified by 'name'. Underscores are considered as spaces, for Wiki compatibility.
 *    If exactly one faction is found, displays this faction.
 *    Otherwise redirects to the faction search page, displaying the results for '%name%'.
 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid faction ID, redirects to the faction search page.
 */

require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'functions.php');
require_once($includes_dir . 'mysql.php');

/** Formats the npc/zone info selected in '$QueryResult' to display them by zone
 *  The top-level sort must be on the zone
 */
function PrintNpcsByZone($QueryResult)
{
    if (mysql_num_rows($QueryResult) > 0) {
        $CurrentZone = "";
        while ($row = mysql_fetch_array($QueryResult)) {
            if ($CurrentZone != $row["zone"]) {
                if ($CurrentZone != "")
                    $print_buffer .= "                  <br/><br/>\n";
                $print_buffer .= "                  <b>in <a href='?a=zone&name=" . $row["zone"] . "'>" . $row["long_name"] . "</a> by </b>\n";
                $CurrentZone = $row["zone"];
            }
            $print_buffer .= "<li><a href='?a=npc&id=" . $row["id"] . "'>" . str_replace("_", " ", $row["name"]) . "</a> (" . $row["id"] . ")</li>\n";
        }
        if ($CurrentZone != "")
            $print_buffer .= "                  <br/><br/>\n";
    }
}


$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    $Query = "
        SELECT
            id,
            name
        FROM
            $faction_list_table
        WHERE
            id = '" . $id . "'
    ";
    $QueryResult = db_mysql_query($Query) or message_die('faction.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: factions.php");
        exit();
    }
    $FactionRow = mysql_fetch_array($QueryResult);
    $name = $FactionRow["name"];
} elseif ($name != "") {
    $Query = "
        SELECT
            id,
            name
        FROM
            $faction_list_table
        WHERE
            name LIKE '$name'
    ";
    $QueryResult = db_mysql_query($Query) or message_die('faction.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: factions.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $FactionRow = mysql_fetch_array($QueryResult);
        $id = $FactionRow["id"];
        $name = $FactionRow["name"];
    }
} else {
    header("Location: factions.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the faction to display
 *    $name : name of the faction to display
 *    $FactionRow : row of the faction to display extracted from the database
 *    The faction actually exists
 */

$Title = "Faction :: " . $name;


$print_buffer .= "          \n";
$print_buffer .= "            <table border='1' width='80%' style='background-color: black; filter:alpha(opacity=70); -moz-opacity:0.7; opacity: 0.7;'>\n";

// Title and Icon bar
$print_buffer .= "              <tr valign='top' align='left'>\n";
$print_buffer .= "                <td colspan='2' class='headerrow'>\n";
$print_buffer .= "                  <a href='" . $peqeditor_url . "index.php?editor=faction&amp;fid=" . $id . "'><img src='" . $images_url . "/peq_faction.png' align='right'/></a>\n";
$print_buffer .= "                  <b>" . $name . "</b>\n";
$print_buffer .= "                  <br/>id : " . $id . "\n";
$print_buffer .= "                </td>\n";
$print_buffer .= "              </tr>\n";
$print_buffer .= "            </table>\n";

$print_buffer .= "            <table border='0' width='80%' style='background-color: ; filter:alpha(opacity=70); -moz-opacity:0.7; opacity: 0.7;'>\n";
$print_buffer .= "              <tr valign='top' align='left'>\n";

// NPCs raising the faction by killing them
$print_buffer .= "                <td width='50%' nowrap='1' align='left'>\n";
$print_buffer .= "                  <b>NPCs whom death raises the faction</b><br/><br/>\n";
$Query = "
    SELECT
        $npc_types_table.id,
        $npc_types_table.`name`,
        $zones_table.long_name,
        $spawn2_table.zone
    FROM
        $faction_entries_table,
        $npc_types_table,
        $spawn_entry_table,
        $spawn2_table,
        $zones_table
    WHERE
        $faction_entries_table.faction_id = $id
    AND $faction_entries_table.npc_faction_id = $npc_types_table.npc_faction_id
    AND $faction_entries_table.value > 0
    AND $npc_types_table.id = $spawn_entry_table.npcID
    AND $spawn2_table.spawngroupID = $spawn_entry_table.spawngroupID
    AND $zones_table.short_name = $spawn2_table.zone
    GROUP BY
        $npc_types_table.id
    ORDER BY
        $zones_table.long_name ASC
	";
$QueryResult = db_mysql_query($Query) or message_die('faction.php', 'MYSQL_QUERY', $query, mysql_error());
PrintNpcsByZone($QueryResult);
$print_buffer .= "                </td>\n";


// NPCs lowering the faction by killing them
$print_buffer .= "                <td width='50%' nowrap='1' align='left'>\n";
$print_buffer .= "                  <b>NPCs whom death lowers the faction</b><br/><br/>\n";
$Query = "
    SELECT
        $npc_types_table.id,
        $npc_types_table.`name`,
        $zones_table.long_name,
        $spawn2_table.zone
    FROM
        $faction_entries_table,
        $npc_types_table,
        $spawn_entry_table,
        $spawn2_table,
        $zones_table
    WHERE
        $faction_entries_table.faction_id = $id
    AND $faction_entries_table.npc_faction_id = $npc_types_table.npc_faction_id
    AND $faction_entries_table.value < 0
    AND $npc_types_table.id = $spawn_entry_table.npcID
    AND $spawn2_table.spawngroupID = $spawn_entry_table.spawngroupID
    AND $zones_table.short_name = $spawn2_table.zone
    GROUP BY
        $npc_types_table.id
    ORDER BY
        $zones_table.long_name ASC
";
$QueryResult = db_mysql_query($Query) or message_die('faction.php', 'MYSQL_QUERY', $query, mysql_error());
PrintNpcsByZone($QueryResult);
$print_buffer .= "                </td>\n";

$print_buffer .= "              </tr>\n";
$print_buffer .= "            </table>\n";
$print_buffer .= "          \n";


?>
