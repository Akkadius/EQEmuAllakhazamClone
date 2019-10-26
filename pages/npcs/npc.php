<?php

require_once('pages/npcs/functions.php');

$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    $Query = "SELECT * FROM $npc_types_table WHERE id='" . $id . "'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysqli_error());
    if (mysqli_num_rows($QueryResult) == 0) {
        header("Location: npcs.php");
        exit();
    }
    $npc = mysqli_fetch_array($QueryResult);
    $name = $npc["name"];
} elseif ($name != "") {
    $Query = "SELECT * FROM $npc_types_table WHERE name like '$name'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysqli_error());
    if (mysqli_num_rows($QueryResult) == 0) {
        header("Location: npcs.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $npc = mysqli_fetch_array($QueryResult);
        $id = $npc["id"];
        $name = $npc["name"];
    }
} else {
    header("Location: npcs.php");
    exit();
}

if ($use_custom_zone_list == TRUE) {
    $query = "
        SELECT
            $zones_table.note
        FROM
            $zones_table,
            $spawn_entry_table,
            $spawn2_table
        WHERE
            $spawn_entry_table.npcID = $id
        AND $spawn_entry_table.spawngroupID = $spawn2_table.spawngroupID
        AND $spawn2_table.zone = $zones_table.short_name
        AND LENGTH($zones_table.note) > 0
    ";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if (substr_count(strtolower($row["note"]), "disabled") >= 1) {
                header("Location: npcs.php");
                exit();
            }
        }
    }
}

if ((get_npc_name_human_readable($npc["name"])) == '' || ($npc["trackable"] == 0 && $trackable_npcs_only == TRUE)) {
    header("Location: npcs.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the NPC to display
 *    $name : name of the NPC to display
 *    $NpcRow : row of the NPC to display extracted from the database
 *    The NPC actually exists
 */

$page_title = "NPC :: " . get_npc_name_human_readable($name);

$DebugNpc = FALSE; // for world builders, set this to false for common use

$print_buffer .= "
    <table class='display_table container_div'>
        <tr valign='top'>
            <td colspan='2'>
                <h1>" . get_npc_name_human_readable($name) . "</h1>
            </td>
        </tr>
";
$print_buffer .= "
    <tr valign='top'>
        <td width='0%'>
            <table>
                <tr>
                    <td>
                        ";



$print_buffer .= "<table border='0' width='100%'>";

$npc_attack_speed = "";
if ($show_npcs_attack_speed == TRUE) {
    $npc_attack_speed = "<tr><td style='text-align:right'><b>Attack Speed</td><td>";
    if ($npc["attack_speed"] == 0) {
        $npc_attack_speed .= "Normal (100%)";
    } else {
        $npc_attack_speed .= (100 + $npc["attack_speed"]) . "%";
    }
    $print_buffer .= "</td></tr>";
}

$print_buffer .= "</td></tr></table>";

if ($npc["lastname"] != "") 
	$lastname = "(" . $npc["lastname"] . ")";

$npc_data = '
    <table border="0" width="100%">
        <tbody>
            <tr>
                <td style="width:250px !important; text-align:right"><b>Name</b>
                </td>
                <td>' . get_npc_name_human_readable($npc["name"]) . ' ' . $lastname . '</td>
            </tr>
            <tr>
                <td style="text-align:right""><b>Level</b>
                </td>
                <td>' . $npc["level"] . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Race</b>
                </td>
                <td>' . $dbiracenames[$npc["race"]]  .'</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Class</b>
                </td>
                <td>' . $dbclasses[$npc["class"]] . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Health</b>
                </td>
                <td>' . number_format($npc["hp"]) . '</td>
            </tr>
            <tr>
                <td style="text-align:right"><b>Damage</b>
                </td>
                <td>' . number_format($npc["mindmg"]) . " to " . number_format($npc["maxdmg"]) . '</td>
            </tr>
            ' . $npc_attack_speed . '
        </tbody>
    </table>

';

$print_buffer .= $npc_data;

$print_buffer .= "<tr valign='top'>";

if ($npc["npc_spells_id"] > 0) {
    $query = "SELECT * FROM $npc_spells_table WHERE id=" . $npc["npc_spells_id"];
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $g = mysqli_fetch_array($result);
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>NPC Spells</h2><p>";
        $query = "
            SELECT
                npc_spells_entries.*,
                spells_new.`name`,
                spells_new.`new_icon`
            FROM
                npc_spells_entries, spells_new
            WHERE
            	{$npc_spells_entries_table}.spellid = spells_new.id
            AND $npc_spells_entries_table.npc_spells_id = " . $npc["npc_spells_id"] . "
            AND $npc_spells_entries_table.minlevel <= " . $npc["level"] . "
            AND $npc_spells_entries_table.maxlevel >= " . $npc["level"] . "
            ORDER BY
                $npc_spells_entries_table.priority DESC
        ";
        $result2 = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
        if (mysqli_num_rows($result2) > 0) {
            $list_name = get_npc_name_human_readable($g["name"]);

            $print_buffer .= "</ul>{$list_name}";
            if ($DebugNpc) {
                $print_buffer .= " (" . $npc["npc_spells_id"] . ")";
            }
            if ($g["attack_proc"] == 1) {
                $print_buffer .= " (Procs)";
            }
            $print_buffer .= "<ul>";
            while ($row = mysqli_fetch_array($result2)) {

                $icon = '<img src="' . $icons_url . $row['new_icon'] . '.gif" align="center" border="1" style="border-radius:5px;height:15px;width:auto">';

                $print_buffer .= "<li><a href='?a=spell&id=" . $row["spellid"] . "'>{$icon} {$row['name']} </a>";
                $print_buffer .= " (" . $dbspelltypes[$row["type"]] . ")";
                if ($DebugNpc) {
                    $print_buffer .= " (recast=" . $row["recast_delay"] . ", priority= " . $row["priority"] . ")";
                }
            }
        }
        $print_buffer .= "</td></tr></table></td>";
    }
}

if (($npc["loottable_id"] > 0) AND ((!in_array($npc["class"], $dbmerchants)) OR ($merchants_dont_drop_stuff == FALSE))) {
    $query = "
        SELECT
        $items_table.id,
        $items_table.Name,
        $items_table.itemtype,
        $loot_drop_entries_table.chance,
        $loot_table_entries.probability,
        $loot_table_entries.lootdrop_id,
        $loot_table_entries.multiplier
    ";

    if ($discovered_items_only == TRUE) {
        $query .= " FROM $items_table,$loot_table_entries,$loot_drop_entries_table,$discovered_items_table";
    } else {
        $query .= " FROM $items_table,$loot_table_entries,$loot_drop_entries_table";
    }

    $query .= " WHERE $loot_table_entries.loottable_id=" . $npc["loottable_id"] . "
			AND $loot_table_entries.lootdrop_id=$loot_drop_entries_table.lootdrop_id
			AND $loot_drop_entries_table.item_id=$items_table.id";

    if ($discovered_items_only == TRUE) {
        $query .= " AND $discovered_items_table.item_id=$items_table.id";
    }
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $print_buffer .= "<td><table><tr><td colspan='2' nowrap><h2 class='section_header'>NPC Drops</h2>";
        $ldid = 0;
		$print_buffer .= "<ul>";
        while ($row = mysqli_fetch_array($result)) {
            if ($show_npc_drop_chances == TRUE) {
                if ($ldid != $row["lootdrop_id"]) {
                    $print_buffer .= "</ol>";
					$print_buffer .= "<li>" . $row["probability"] . "% Probability with a Multiplier of " . $row["multiplier"] . ".</li>";
					$print_buffer .= "<ol>";
                    $ldid = $row["lootdrop_id"];
                }
            }
            $print_buffer .= "<li>" . get_item_icon_from_id($row["id"]) . " <a href='?a=item&id=" . $row["id"] . "'>" . $row["Name"] . "</a>";
			if ($dbitypes[$row["itemtype"]] != "")
				$print_buffer .= " (" . $dbitypes[$row["itemtype"]] . ")";
			
            if ($show_npc_drop_chances == TRUE) {
                $print_buffer .= " - " . $row["chance"] . "%";
                $print_buffer .= " (" . ($row["chance"] * $row["probability"] / 100) . "% Global)";
            }
            $print_buffer .= "</li>";
        }
		
		$print_buffer .= "</ul>";
        $print_buffer .= "</td></tr></table></td>";
    } else {
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>No Drops.</b><br/>";
        $print_buffer .= "</td></tr></table></td>";
    }
}

if ($npc["merchant_id"] > 0) {
    $query = "
        SELECT
            $items_table.id,
            $items_table.Name,
            $items_table.price,
            $items_table.ldonprice,
            $items_table.icon
        FROM
            $items_table,
            $merchant_list_table
        WHERE
            $merchant_list_table.merchantid = " . $npc["merchant_id"] . "
        AND $merchant_list_table.item = $items_table.id
        ORDER BY
            $merchant_list_table.slot
    ";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>This NPC sells</b><br/><br>";
        while ($row = mysqli_fetch_array($result)) {
            $print_buffer .= "<li style='list-style-type:none;margin-left:15px;'><a href='?a=item&id=" . $row["id"] . "'>" .
                '<img src="' . $icons_url . $row['icon'] . '.gif" align="center" border="1" style="border-radius:5px;height:15px;width:auto"> ' .
                 $row["Name"] .
                 "</a> ";
            if ($npc["class"] == 41)
                $print_buffer .= "(" . price($row["price"]) . ")";
			
            if ($npc["class"] == 61)
                $print_buffer .= "(" . $row["ldonprice"] . " points)";
			
            $print_buffer .= "</li>";
        }
        $print_buffer .= "</td></tr></table></td>";
    }
}

$print_buffer .= "</tr></table>";


$print_buffer .= "</td>"; // right column height='100%'
$data_buffer = "";
if ($UseWikiImages) {
    $ImageFile = NpcImage($wiki_server_url, $wiki_root_name, $id);
    if ($ImageFile == "") {
        $data_buffer .= "<a href='" . $wiki_server_url . $wiki_root_name . "/index.php?title=Special:Upload&wpDestFile=Npc-" . $id . ".jpg'>Click to add an image for this NPC</a>";
    } else {
        $data_buffer .= "<img src='" . $ImageFile . "'/>";
    }
} else {
    if (file_exists($npcs_dir . $id . ".jpg")) {
        $data_buffer .= "<img src=" . $npcs_url . $id . ".jpg>";
    }
}

$data_buffer .= "</td></tr><tr><td>";
// zone list
$query = "
    SELECT
        $zones_table.long_name,
        $zones_table.short_name,
        $spawn2_table.x,
        $spawn2_table.y,
        $spawn2_table.z,
        $spawn_group_table.`name` AS spawngroup,
        $spawn_group_table.id AS spawngroupID,
        $spawn2_table.respawntime
    FROM
        $zones_table,
        $spawn_entry_table,
        $spawn2_table,
        $spawn_group_table
    WHERE
        $spawn_entry_table.npcID = $id
    AND $spawn_entry_table.spawngroupID = $spawn2_table.spawngroupID
    AND $spawn2_table.zone = $zones_table.short_name
    AND $spawn_entry_table.spawngroupID = $spawn_group_table.id
";
foreach ($ignore_zones AS $zid) {
    $query .= " AND $zones_table.short_name!='$zid'";
}
$query .= "AND $zones_table.min_status = '0'";
$query .= " ORDER BY $zones_table.long_name,$spawn_group_table.`name`";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $data_buffer .= "<h2 class='section_header'>NPC Spawns</h2>";
    $z = "";
	$data_buffer .= "<ul>";
	while ($row = mysqli_fetch_array($result)) {
		if ($z != $row["short_name"]) {
			$data_buffer .= "<p><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a></p>";
			$z = $row["short_name"];
			if ($allow_quests_npc == TRUE) {
				if (file_exists("$quests_dir$z/" . str_replace("#", "", $npc["name"]) . ".pl")) {
					$data_buffer .= "<br/><a href='" . $root_url . "quests/index.php?npc=" . str_replace("#", "", $npc["name"]) . "&zone=" . $z . "&amp;npcid=" . $id . "'>Quest(s) for that NPC</a>";
				}
			}
		}
		if ($display_spawn_group_info == TRUE) {
			$data_buffer .= "<li><a href = '?a=spawngroup&id=" . $row["spawngroupID"] . "'>" . $row["spawngroup"] . "</a> Spawns every " . translate_time($row["respawntime"]) . " at X (" . number_format($row["x"], 2) . "), Y (" . number_format($row["y"], 2) . "), Z (" . number_format($row["z"], 2) . ").";
		}
	}
	$data_buffer .= "</ul>";
}

if ($data_buffer != "</td></tr><tr><td>") {
	$print_buffer .= "<td valign='top'><table class='display_table container_div'><tr><td>";
	$print_buffer .= $data_buffer;
}

$print_buffer .= "</td></tr></table>";
$print_buffer .= "</td></tr></table>";
$print_buffer .= "</td></tr></table>";
$print_buffer .= "</td></tr></table>";

if (!isset($_GET['v_ajax']))
    $footer_javascript .= "<script src = 'pages/npcs/npcs.js'></script>";

?>