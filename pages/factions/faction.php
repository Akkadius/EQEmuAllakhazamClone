<?php

require_once('functions.php');

$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    $query = "
        SELECT
            id,
            name
        FROM
            $faction_list_table
        WHERE
            id = '" . $id . "'
    ";
    $result = db_mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        header("Location: ?a=factions");
        exit();
    }
    $FactionRow = mysql_fetch_array($result);
    $name = $FactionRow["name"];
} elseif ($name != "") {
    $query = "
        SELECT
            id,
            name
        FROM
            $faction_list_table
        WHERE
            name LIKE '$name'
    ";
    $result = db_mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        header("Location: factions.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $FactionRow = mysql_fetch_array($result);
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

$page_title = "Faction :: " . $name;

$raise_faction = "<h2 class='section_header'>NPCs whose death raise faction</h2>";
$query = "
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
$result = db_mysql_query($query);
$raise_faction .= print_npcs_by_zone($result);


$lower_faction = "<h2 class='section_header'>NPCs whom death lowers the faction</h2>";
$query = "
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
$result = db_mysql_query($query);

$lower_faction .= print_npcs_by_zone($result);

$content = display_header('<h2>' . $name . '</h2>');
$content .= display_row($raise_faction, $lower_faction);
$print_buffer .= display_table($content, 700);


?>
