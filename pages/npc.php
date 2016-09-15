<?php
/** Displays the NPC identified by 'id' if it is specified and an NPC by this ID exists.
 *  Otherwise queries for the NPCs identified by 'name'. Underscores are considered as spaces and backquotes as minuses,
 *    for Wiki-EQEmu compatibility.
 *    If exactly one NPC is found, displays this NPC.
 *    Otherwise redirects to the NPC search page, displaying the results for '%name%'.
 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid NPC ID, redirects to the NPC search page.
 */


$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($id != "" && is_numeric($id)) {
    $Query = "SELECT * FROM $npc_types_table WHERE id='" . $id . "'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: npcs.php");
        exit();
    }
    $npc = mysql_fetch_array($QueryResult);
    $name = $npc["name"];
} elseif ($name != "") {
    $Query = "SELECT * FROM $npc_types_table WHERE name like '$name'";
    $QueryResult = db_mysql_query($Query) or message_die('npc.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: npcs.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $npc = mysql_fetch_array($QueryResult);
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
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            if (substr_count(strtolower($row["note"]), "disabled") >= 1) {
                header("Location: npcs.php");
                exit();
            }
        }
    }
}

if ((ReadableNpcName($npc["name"])) == '' || ($npc["trackable"] == 0 && $trackable_npcs_only == TRUE)) {
    header("Location: npcs.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the NPC to display
 *    $name : name of the NPC to display
 *    $NpcRow : row of the NPC to display extracted from the database
 *    The NPC actually exists
 */

$Title = "NPC :: " . ReadableNpcName($name);

$DebugNpc = FALSE; // for world builders, set this to false for common use

$print_buffer .= "<table class='display_table container_div'><tr valign='top'><td colspan='2' class='headerrow'>";
$print_buffer .= "<a href='" . $peqeditor_url . "index.php?editor=npc&amp;npcid=" . $id . "'><img src='" . $images_url . "/peq_npc.png' align='right'/></a>";
$print_buffer .= "<a href='" . $peqeditor_url . "index.php?editor=merchant&amp;npcid=" . $id . "'><img src='" . $images_url . "/peq_merchant.png' align='right'/></a>";
$print_buffer .= "<b>" . ReadableNpcName($npc["name"]) . "</b>";
if ($npc["lastname"] != "") {
    $print_buffer .= "<br/>" . str_replace("_", " ", " (" . $npc["lastname"] . ")") . " - id : " . $id;
} else {
    $print_buffer .= "<br/>id : " . $id;
}
$print_buffer .= "</td></tr>";
$print_buffer .= "<tr valign='top'><td width='0%'><table><tr><td><table border='0' width='100%' cellpadding='0' cellspacing='0'><tr><td>";
//$print_buffer .= "<tr valign='top'><td width='0%'><table><tr><td>";
$print_buffer .= "<table border='0' width='0%'><tr valign='top'><td width='100%'>\n";
$print_buffer .= "<p><table border='0' width='100%'>";
$print_buffer .= "<tr><td><b>Full name : </b></td><td>" . ReadableNpcName($npc["name"]);
if ($npc["lastname"] != "") {
    $print_buffer .= str_replace("_", " ", " (" . $npc["lastname"] . ")");
}
$print_buffer .= "</td></tr>";
$print_buffer .= "<tr><td><b>Level : </b></td><td width='100%'>" . $npc["level"] . "</td></tr>";
$print_buffer .= "<tr><td><b>Race : </b></td><td>" . $dbiracenames[$npc["race"]] . "</td></tr>";
$print_buffer .= "<tr><td><b>Class : </b></td><td>" . $dbclasses[$npc["class"]];
if ($npc["npc_faction_id"] > 0) {
    $query = "
        SELECT
            $faction_list_table.`name`,
            $faction_list_table.id
        FROM
            $faction_list_table,
            $npc_faction_table
        WHERE
            $npc_faction_table.id = " . $npc["npc_faction_id"] . "
        AND $npc_faction_table.primaryfaction = $faction_list_table.id
    ";
    $faction = GetRowByQuery($query);
    $print_buffer .= "<tr><td><b>Main faction : </b></td><td><a href='faction.php?id=" . $faction["id"] . "'>" . $faction["name"] . "</a></td></tr>";
}
if ($npc["findable"] == 1) {
    $print_buffer .= " (findable)";
}

$print_buffer .= "</td></tr>";

if ($display_npc_stats == "TRUE") {
    $print_buffer .= "<tr><td><b>Health points : </b></td><td>" . $npc["hp"] . "</td></tr>";
    $print_buffer .= "<tr><td><b>Damage : </b></td><td>" . $npc["mindmg"] . " to " . $npc["maxdmg"] . "</td></tr>";
}
if ($show_npcs_attack_speed == TRUE) {
    $print_buffer .= "<tr><td><b>Attack speed : </b></td><td>";
    if ($npc["attack_speed"] == 0) {
        $print_buffer .= "Normal (100%)";
    } else {
        $print_buffer .= (100 + $npc["attack_speed"]) . "%";
    }
    //$print_buffer .= "</td></tr>";
}
if ($show_npcs_average_damages == TRUE) {
    $print_buffer .= "<tr><td><b>Average melee damages : </b></td><td>";
    $avghit = ($npc["maxdmg"] + $npc["mindmg"]) / 2; // average hit
    $dam = $avghit; # first hit of main hand
    $com = $npc["npcspecialattks"];
    if (CanThisNPCDoubleAttack($npc["class"], $npc["level"])) {
        # chance to double attack = level+20>rand(0,99) (mobai.cpp)
        $chance2 = ($npc["level"] + 20) / 100;
        $com .= " DA=$chance2";
        $dam += $avghit * $chance2;
        if ($npc["npcspecialattks"] != "") {
            # Npc has some special attacks
            # Able to triple (implicitely, if he can quad, then he can triple, this is NOT in source code ATM (3 may 2006).
            if ((strpos($npc["npcspecialattks"], "T") > 0) OR (strpos($npc["npcspecialattks"], "Q") > 0)) {
                # chance to triple, happens when we doubled, and if level>rand(0,99)
                $chance3 = $chance2 * $npc["level"] / 100;
                $com .= " TA=$chance3";
                $dam += $avghit * $chance3;
                if (strpos($npc["npcspecialattks"], "Q") > 0) {
                    # The NPC can quad
                    # chance to quad, happens when we tripled and if level-20>rand(0,99)
                    $chance4 = $chance2 * $chance3 * ($npc["level"] - 20) / 100;
                    $com .= " QA=$chance4";
                    $dam += $avghit * $chance4;
                }
            }
            # the mob can flurry
            if (strpos($npc["npcspecialattks"], "F") > 0) {
                # the mob has 20% chances to flurry, and if it flurries, it will hit 2 times
                # so, for each round, it has 20%x2 chances to hit
                $dam += $avghit * 0.4;
            }
        }
    }
    # the npc is slower/faster than normal
    if ($npc["attack_speed"] != 0) {
        $dam = $dam * (100 + $npc["attack_speed"]) / 100;
    } // dam per hit
    $print_buffer .= round($dam) . " per round</td></tr>";
}
if ($display_npc_stats == "TRUE") {
    if ($npc["npcspecialattks"] != '') {
        $print_buffer .= "<tr><td><b>Special attacks : </b></td><td>" . SpecialAttacks($npc["npcspecialattks"]) . "</td></tr>";
    }
}

$print_buffer .= "</td></tr></table>\n";

$print_buffer .= "<tr valign='top'>";

if ($npc["npc_spells_id"] > 0) {
    $query = "SELECT * FROM $npc_spells_table WHERE id=" . $npc["npc_spells_id"];
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        $g = mysql_fetch_array($result);
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>This NPC casts the following spells</h2><p>";
        $query = "
            SELECT
                $npc_spells_entries_table.*
            FROM
                $npc_spells_entries_table
            WHERE
                $npc_spells_entries_table.npc_spells_id = " . $npc["npc_spells_id"] . "
            AND $npc_spells_entries_table.minlevel <= " . $npc["level"] . "
            AND $npc_spells_entries_table.maxlevel >= " . $npc["level"] . "
            ORDER BY
                $npc_spells_entries_table.priority DESC
        ";
        $result2 = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
        if (mysql_num_rows($result2) > 0) {
            $print_buffer .= "</ul><li><b>Listname : </b>" . ReadableNpcName($g["name"]);
            if ($DebugNpc) {
                $print_buffer .= " (" . $npc["npc_spells_id"] . ")";
            }
            if ($g["attack_proc"] == 1) {
                $print_buffer .= " (Procs)";
            }
            $print_buffer .= "<ul>";
            while ($row = mysql_fetch_array($result2)) {
                $spell = getspell($row["spellid"]);
                $print_buffer .= "<li><a href='?a=spell&id=" . $row["spellid"] . "'>" . $spell["name"] . "</a>";
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
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        if ($show_npc_drop_chances == TRUE) {
            $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>When killed, this NPC drops</h2><br/>";
        } else {
            $print_buffer .= " <td><table border='0'><tr><td colspan='2' nowrap='1'><h2 class='section_header'>When killed, this NPCcan drop</h2><br/>";
        }
        $ldid = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($show_npc_drop_chances == TRUE) {
                if ($ldid != $row["lootdrop_id"]) {
                    $print_buffer .= "</ol><li>With a probability of " . $row["probability"] . "% (multiplier : " . $row["multiplier"] . "): </li><ol>";
                    $ldid = $row["lootdrop_id"];
                }
            }
            $print_buffer .= "<li><a href='?a=item&id=" . $row["id"] . "'>" . $row["Name"] . "</a>";
            $print_buffer .= " (" . $dbitypes[$row["itemtype"]] . ")";
            if ($show_npc_drop_chances == TRUE) {
                $print_buffer .= " - " . $row["chance"] . "%";
                $print_buffer .= " (" . ($row["chance"] * $row["probability"] / 100) . "% global)";
            }
            $print_buffer .= "</li>";
        }
        $print_buffer .= "</td></tr></table></td>";
    } else {
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>No item drops found. </b><br/>";
        $print_buffer .= "</td></tr></table></td>";
    }
}

if ($npc["merchant_id"] > 0) {
    $query = "
        SELECT
            $items_table.id,
            $items_table.Name,
            $items_table.price,
            $items_table.ldonprice
        FROM
            $items_table,
            $merchant_list_table
        WHERE
            $merchant_list_table.merchantid = " . $npc["merchant_id"] . "
        AND $merchant_list_table.item = $items_table.id
        ORDER BY
            $merchant_list_table.slot
    ";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        $print_buffer .= "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>This NPC sells : </b><br/>";
        while ($row = mysql_fetch_array($result)) {
            $print_buffer .= "<li><a href='?a=item&id=" . $row["id"] . "'>" . $row["Name"] . "</a> ";
            if ($npc["class"] == 41) {
                $print_buffer .= "(" . price($row["price"]) . ")";
            } // NPC is a shopkeeper
            if ($npc["class"] == 61) {
                $print_buffer .= "(" . $row["ldonprice"] . " points)";
            } // NPC is a LDON merchant
            $print_buffer .= "</li>";
        }
        $print_buffer .= "</td></tr></table></td>";
    }
}

$print_buffer .= "</tr></table>";


$print_buffer .= "</td><td valign='top'><table border='0' width='0%'>"; // right column height='100%'
$print_buffer .= "<tr><td>"; // image
if ($UseWikiImages) {
    $ImageFile = NpcImage($wiki_server_url, $wiki_root_name, $id);
    if ($ImageFile == "") {
        $print_buffer .= "<a href='" . $wiki_server_url . $wiki_root_name . "/index.php?title=Special:Upload&wpDestFile=Npc-" . $id . ".jpg'>Click to add an image for this NPC</a>";
    } else {
        $print_buffer .= "<img src='" . $ImageFile . "'/>";
    }
} else {
    if (file_exists($npcs_dir . $id . ".jpg")) {
        $print_buffer .= "<img src=" . $npcs_url . $id . ".jpg>";
    }
}

$print_buffer .= "</td></tr><tr><td>";
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
$query .= " ORDER BY $zones_table.long_name,$spawn_group_table.`name`";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<b>This NPC spawns in : </b>";
    $z = "";
    while ($row = mysql_fetch_array($result)) {
        if ($z != $row["short_name"]) {
            $print_buffer .= "<p><a href='?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a>";
            $z = $row["short_name"];
            if ($allow_quests_npc == TRUE) {
                if (file_exists("$quests_dir$z/" . str_replace("#", "", $npc["name"]) . ".pl")) {
                    $print_buffer .= "<br/><a href='" . $root_url . "quests/index.php?npc=" . str_replace("#", "", $npc["name"]) . "&zone=" . $z . "&amp;npcid=" . $id . "'>Quest(s) for that NPC</a>";
                }
            }
        }
        if ($display_spawn_group_info == TRUE) {
            $print_buffer .= "<li><a href='spawngroup.php?id=" . $row["spawngroupID"] . "'>" . $row["spawngroup"] . "</a> : " . floor($row["y"]) . " / " . floor($row["x"]) . " / " . floor($row["z"]);
            $print_buffer .= "<br/>Spawns every " . translate_time($row["respawntime"]);
        }
    }
}
// factions
$query = "
    SELECT
        $faction_list_table.`name`,
        $faction_list_table.id,
        $faction_entries_table.
    VALUE

    FROM
        $faction_list_table,
        $faction_entries_table
    WHERE
        $faction_entries_table.npc_faction_id = " . $npc["npc_faction_id"] . "
    AND $faction_entries_table.faction_id = $faction_list_table.id
    AND $faction_entries_table.value < 0
    GROUP BY
        $faction_list_table.id
";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<p><b>Killing this NPC lowers factions with : </b><ul>";
    while ($row = mysql_fetch_array($result)) {
        $print_buffer .= "<li><a href=faction.php?id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["value"] . ")";
    }
}
$print_buffer .= "</ul>";
$query = "
    SELECT
        $faction_list_table.`name`,
        $faction_list_table.id,
        $faction_entries_table.value
    FROM
        $faction_list_table,
        $faction_entries_table
    WHERE
        $faction_entries_table.npc_faction_id = " . $npc["npc_faction_id"] . "
    AND $faction_entries_table.faction_id = $faction_list_table.id
    AND $faction_entries_table.value > 0
    GROUP BY
        $faction_list_table.id
";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<p><b>Killing this NPC raises factions with : </b><ul>";
    while ($row = mysql_fetch_array($result)) {
        $print_buffer .= "<li><a href=faction.php?id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["value"] . ")";
    }
}
$print_buffer .= "</ul>";
$print_buffer .= "</td></tr></table>\n";

$print_buffer .= "</td></tr></table>\n";
$print_buffer .= "</td></tr></table>\n";
$print_buffer .= "</td></tr></table>\n";


?>
