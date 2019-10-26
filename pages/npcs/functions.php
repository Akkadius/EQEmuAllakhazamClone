<?php
function return_npc_primary_faction($faction_id){
    global $faction_list_table, $npc_faction_table;
    if($faction_id < 0)
        return;

    $query = "
        SELECT
            $faction_list_table.`name`,
            $faction_list_table.id
        FROM
            $faction_list_table,
            $npc_faction_table
        WHERE
            $npc_faction_table.id = " . $faction_id . "
        AND $npc_faction_table.primaryfaction = $faction_list_table.id
    ";
    $faction = GetRowByQuery($query);

    return "<a href='?a=faction&id=" . $faction["id"] . "'>" . $faction["name"] . "</a>";
}

function return_npc_spawns($spawngroup_id, $via_ajax = 0) {
	global $npc_types_table, $spawn_entry_table;
	
	$return_buffer = "";
	$query = "SELECT $spawn_entry_table.chance,$npc_types_table.name,$npc_types_table.id
        FROM $spawn_entry_table,$npc_types_table
        WHERE $spawn_entry_table.spawngroupID = $spawngroup_id
          AND $spawn_entry_table.npcID = $npc_types_table.id
        ORDER BY $npc_types_table.name ASC";
	$result = db_mysql_query($query) or message_die('functions.php', 'MYSQL_QUERY', $query, mysqli_error());	
	if (mysqli_num_rows($result) > 0) {
		if ($via_ajax == 0) {
			$return_buffer .= "<tr>";
			$return_buffer .= "<td><h2 class = 'section_header'>NPCs Spawned</h2>";
		}
		
		$return_buffer .= "<ul>";
			while ($row = mysqli_fetch_array($result)) {
				$return_buffer .= "<li><a href='?a=npc&id=" . $row["id"] . "'>" . $row["name"] . "</a> (" . $row["chance"] . "%)</li>";
			}
		$return_buffer .= "</ul>";
		
		if ($via_ajax == 0)
			$return_buffer .= "</tr>";
	}
	return $return_buffer;
}

function return_npc_spawns_count($spawngroup_id) {
	global $npc_types_table, $spawn_entry_table;
	$return_buffer = "";
	$query = "SELECT $npc_types_table.id
        FROM $spawn_entry_table, $npc_types_table
        WHERE $spawn_entry_table.spawngroupID = $spawngroup_id
		LIMIT 1";
	$result = db_mysql_query($query) or message_die('functions.php', 'MYSQL_QUERY', $query, mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		$return_buffer .=
		"<tr colspan = '2'>
			<td><h2 class = 'section_header'>NPC Spawns</h2></td>
		</tr>";
		$return_buffer .= "<tr id = 'npc_spawn_view'>";
			$return_buffer .= "<td><ul><li><a onclick = 'npc_spawn_view(" . $spawngroup_id . ")'>Click to View</a></li></ul></td>";
		$return_buffer .= "</tr>";
	}
	return $return_buffer;
}


#function return_nearby_npcs($spawngroup_id, $via_ajax = 0) {
#	global $npc_types_table, $spawn2_table, $spawn_entry_table, $spawn_group_table, ;
#	#$range = ($spawngroup_around_range * $spawngroup_around_range);
#	#$query = 
#}

function return_nearby_npcs_count($spawngroup_id) {
	global $npc_types_table, $spawn2_table, $spawn_entry_table, $spawn_group_table, $zones_table;
	$return_buffer = "";
	$query = "SELECT $spawn_group_table.name AS sgname, $spawn2_table.*,
        $zones_table.long_name AS zone, $zones_table.short_name AS spawnzone
        FROM $spawn_group_table,$spawn2_table,$zones_table
        WHERE $spawn_group_table.id=$spawngroup_id
          AND $spawn2_table.spawngroupID=$spawn_group_table.id
          AND $spawn2_table.zone=$zones_table.short_name";
	$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
	if (mysqli_num_rows($result) > 0) {
		$spawn = mysqli_fetch_array($result);
		$page_title = $spawn["sgname"] . " (" . $spawn["zone"] . ": " . number_format($spawn["x"], 2) . "," . number_format($spawn["y"], 2) . "," . number_format($spawn["z"], 2) . ")";
		$x = floor($spawn["x"]);
		$y = floor($spawn["y"]);
		$z = floor($spawn["z"]);
		$query = "SELECT $spawn_entry_table.chance,$spawn2_table.x AS x, $spawn2_table.y AS y, $spawn2_table.z AS z,
					$npc_types_table.name,$npc_types_table.id,
					$spawn_group_table.id AS sgid,$spawn_group_table.name AS sgname
				FROM $spawn_entry_table,$npc_types_table,$spawn_group_table,$spawn2_table
				WHERE $spawn2_table.zone='" . $spawn["spawnzone"] . "'
				AND $spawn2_table.spawngroupID=$spawn_group_table.id
				AND $spawn2_table.spawngroupID=$spawn_entry_table.spawngroupID
				AND $spawn_entry_table.npcID=$npc_types_table.id
				AND(($x - $spawn2_table.x) *
				($x - $spawn2_table.x)) +
				(($y - $spawn2_table.y) *
				($y-$spawn2_table.y)) < $range
				AND (abs($z-$spawn2_table.z) < 20)
				AND $spawn_group_table.id != $spawngroup_id
				ORDER BY sgid ASC, $npc_types_table.name ASC
				";
		$result = db_mysql_query($query) or message_die('functions.php', 'MYSQL_QUERY', $query, mysqli_error());
		if (mysqli_num_rows($result) > 0) {
			$return_buffer .=
			"<tr colspan = '2'>
				<td><h2 class = 'section_header'>Nearby NPC Spawns</h2></td>
			</tr>";
			$return_buffer .= "<tr id = 'npc_spawn_view'>";
				$return_buffer .= "<td><ul><li><a onclick = 'nearby_npc_view(" . $spawngroup_id . ")'>Click to View</a></li></ul></td>";
			$return_buffer .= "</tr>";
		}
	}
	return $return_buffer;	
}

?>