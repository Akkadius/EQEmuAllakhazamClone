<?php

require_once('pages/npcs/functions.php');

if (isset($_GET['view_spawn'])) {
	echo return_npc_spawns($_GET['view_spawn'], 1);
	exit;
}

if (isset($_GET['view_nearby'])) {
	echo return_nearby_npcs($_GET['view_nearby'], 1);
	exit;
}

if ($display_spawn_group_info == FALSE) {
	header("Location: index.php");
	exit();
}

/*$query = "SELECT $spawn_group_table.name AS sgname, $spawn2_table.*,
        $zones_table.long_name AS zone, $zones_table.short_name AS spawnzone
        FROM $spawn_group_table,$spawn2_table,$zones_table
        WHERE $spawn_group_table.id=$id
          AND $spawn2_table.spawngroupID=$spawn_group_table.id
          AND $spawn2_table.zone=$zones_table.short_name";
$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
$spawn = mysqli_fetch_array($result);
$page_title = $spawn["sgname"] . " (" . $spawn["zone"] . ": " . number_format($spawn["x"], 2) . "," . number_format($spawn["y"], 2) . "," . number_format($spawn["z"], 2) . ")";
$x = floor($spawn["x"]);
$y = floor($spawn["y"]);
$z = floor($spawn["z"]);*/


$print_buffer =  "<table border=0 width=0%><tr valign=top><td width=50% nowrap>\n";
if ($id != "" && is_numeric($id))
	$print_buffer .= return_npc_spawns($id);

# $print_buffer .= return_nearby_npcs_count($id);
# $print_buffer .= return_nearby_npcs($id);
/*echo $v_ajax;
if ($v_ajax) {
	$query = "SELECT $spawn_entry_table.chance,$npc_types_table.name,$npc_types_table.id
        FROM $spawn_entry_table,$npc_types_table
        WHERE $spawn_entry_table.spawngroupID=$id
          AND $spawn_entry_table.npcID=$npc_types_table.id
        ORDER BY $npc_types_table.name ASC
        ";
	$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
	$content .=  "<b>NPCs Spawned:</b>";
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$content .= "<li><a href=?a=npc&id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["chance"] . "%)</li>";
		}
	}
	$content .=  "</td><td width=50% nowrap>";
	$content .=  "<b>Nearby NPCs: </b><br>(Max Range: $spawngroup_around_range)<ul>";
	$myrange = $spawngroup_around_range * $spawngroup_around_range; // precalculate, saves some mysql time
	$query = "SELECT $spawn_entry_table.chance,$spawn2_table.x AS x, $spawn2_table.y AS y, $spawn2_table.z AS z,
				$npc_types_table.name,$npc_types_table.id,
				$spawn_group_table.id AS sgid,$spawn_group_table.name AS sgname
			FROM $spawn_entry_table,$npc_types_table,$spawn_group_table,$spawn2_table
			WHERE $spawn2_table.zone='" . $spawn["spawnzone"] . "'
			AND $spawn2_table.spawngroupID=$spawn_group_table.id
			AND $spawn2_table.spawngroupID=$spawn_entry_table.spawngroupID
			AND $spawn_entry_table.npcID=$npc_types_table.id
			AND(($x-$spawn2_table.x)*($x-$spawn2_table.x))+(($y-$spawn2_table.y)*($y-$spawn2_table.y))<$myrange
			AND (abs(z-$spawn2_table.z)<20)
			AND $spawn_group_table.id!=$id
			ORDER BY sgid ASC, $npc_types_table.name ASC
			";
	$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
	$sg = 0;
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			if ($sg != $row["sgid"]) {
				$sg = $row["sgid"];
				$content .=  "</ul>";
				$content .= "
					<li>
						<a href=?a=npc&id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["chance"] . "%)
					</li>
					<ul>
						<li>
							<a href='?a=spawngroup&id=" . $row["sgid"] . "'>" . $row["sgname"] . "</a>
						</li>
						<li>Range: " .
							floor(sqrt(
								($x - $row["x"]) * ($x - $row["x"]) +
								($y - $row["y"]) * ($y - $row["y"])
							)) .
						"</li>
						<li>XYZ: " .
							number_format($row["x"], 2) . ", " .
							number_format($row["y"], 2) . ", " .
							number_format($row["z"], 2) .
						"</li>
					</ul>";
			}
			$content .= "</li>";
		}
	} else {
		$content .= "<li>There are no Nearby NPCs spawning.";
	}
}
$content .= "</ul></td></tr></table>";
$print_buffer .= display_table($content);*/

if (!$v_ajax)
    $footer_javascript .= '<script src="pages/npcs/npcs.js"></script>';

?>