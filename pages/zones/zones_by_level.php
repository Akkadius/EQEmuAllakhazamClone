<?php
$page_title = "Populated Zones By Level";

$print_buffer .= "<p><b>The suggested levels are approximate based upon the levels of the majority of creatures found in the zone, and, except for the newbie zones, assume that you will hunt with a group.  Most zones also have some higher and lower level monsters roaming the area, and can thus be selectively hunted at different levels.  Follow the links to get more complete descriptions for the individual zones.</b><p>";
if ($sort_zone_level_list == TRUE) {
    $print_buffer .= "<p><b>Zones are sorted following average npc's levels. If a newbie zone contains high level friendly guards, they count in the average level and false the sort.</b><p>";
}

// Tweak the second SQL query to your needs, if for example you added a field for trigger npcs, or guards, filter it !
// In the default config, invisible men and level 1 npcs are ignored (there's enough lvl 2-5 npcs in newbie zones to have them marked at that lvl)

$levels = array();
$LevelRange = 0;
for ($i = 0; $i <= ($server_max_npc_level / 5); $i++) {
    $LevelRange += 5;
    $levels[$i] = $LevelRange;
}
// Set $lowlimit to the minimum of NPCS signifying the zones is populated for that lvl. 
// Ex : $lowlimit=5 => if a zone has less than 5 npcs of a level, they will be ignored
$lowlimit = 5;
// $MinimumNpcLvl allows you to remove low lvl npcs, such as invisible men used for triggers, set to 0 if not used
$MinimumNpcLvl = 1;
$zones = array();
$query = "SELECT $zones_table.*
        FROM $zones_table";
$v = "WHERE";
foreach ($ignore_zones AS $zid) {
    $query .= " $v $zones_table.short_name!='$zid'";
    $v = " AND ";
}
$query .= "AND $zones_table.min_status = '0'";
$query .= " ORDER BY $zones_table.long_name ASC";
$result = db_mysql_query($query) or message_die('zones_by_level.php', 'MYSQL_QUERY', $query, mysqli_error());
$cpt = 0;
while ($res = mysqli_fetch_array($result)) {
    $zones[$cpt]["shortname"] = $res["short_name"];
    $zones[$cpt]["longname"] = $res["long_name"];
    $zones[$cpt]["npcs"] = 0;
    $zones[$cpt]["val"] = 0;
    $query = "SELECT $npc_types_table.level
          FROM $npc_types_table,$spawn2_table,$spawn_entry_table
          WHERE $spawn2_table.zone='" . $res["short_name"] . "'
          AND $spawn_entry_table.spawngroupID=$spawn2_table.spawngroupID
          AND $spawn_entry_table.npcID=$npc_types_table.id";
    if ($hide_invisible_men == TRUE) {
        $query .= " AND $npc_types_table.race!=127 AND $npc_types_table.race!=240";
    }
    $query .= " AND $npc_types_table.level>$MinimumNpcLvl
          GROUP BY $npc_types_table.id";
    $result2 = db_mysql_query($query) or message_die('zones_by_level.php', 'MYSQL_QUERY', $query, mysqli_error());
    while ($row = mysqli_fetch_array($result2)) {
        $lvl = floor($row["level"] / 5);
        $zones[$cpt][$lvl]++;
        $zones[$cpt]["npcs"]++;
    }
    $cpt++;
}

// Edit config.php and put FALSE to that next variable if you don't want to sort the zones
if ($sort_zone_level_list == TRUE) {
    for ($i = 0; $i < $cpt; $i++) {
        if ($zones[$i]["npcs"] > 0) { // populated
            $zones[$i]["val"] = 0;
            $nb = 0;
            foreach ($levels AS $lkey => $lval) {
                if ($zones[$i][$lkey] > $lowlimit) {
                    $zones[$i]["val"] += $levels[$lkey] * $zones[$i][$lkey];
                    $nb += $zones[$i][$lkey];
                }
            }
            if ($nb == 0) {
                $zones[$i]["val"] = 999;
            } else {
                $zones[$i]["val"] = $zones[$i]["val"] / $nb;
            }
        }
    }
    // lets sort all that data
    $max = $cpt;
    do {
        $max--;
        $end = TRUE;
        for ($z = 0; $z < $max; $z++) {
            if ($zones[$z]["val"] > $zones[$z + 1]["val"]) {
                $end = FALSE;
                $myzone = $zones[$z];
                $zones[$z] = $zones[$z + 1];
                $zones[$z + 1] = $myzone;
            }
        }
    } while ($end == FALSE);
} // end SortZoneLevelList


$print_buffer .= "<table><tr valign=top><td width=100%>";
$print_buffer .= "<table border=1 style='width:100%' class='display_table container_div datatable'>";
$print_buffer .= "<thead class='menuh'><th>Name</th>
       <th class=tab_title>Short name</th>";
$LevelMax = 0;
for ($i = 0; $i <= ($server_max_npc_level / 5); $i++) {
    $LevelMax += 5;
    $LevelMin = $LevelMax - 4;
    $print_buffer .= "<th class=tab_title>" . $LevelMin . " - " . $LevelMax . "</th>";
    $level_iterations = $i;
}
$print_buffer .= "</thead>";

$nb = 0;
for ($i = 0; $i <= $cpt; $i++) {
    if ($zones[$i]["npcs"] > $lowlimit) {
        $nb++;
        if (modulo($nb, 10) == 1) {
            $print_buffer .= "<tr>
       <td class=tab_title>Name</td>
       <td class=tab_title>Short name</td>";
            if ($sort_zone_level_list == TRUE) {
                $print_buffer .= "<td class=tab_title>Avg Lvl</td>";
            }
            foreach ($levels AS $key2 => $val2) {
                $print_buffer .= "<td class=tab_title>$val2</td>";
            }
            $print_buffer .= "</tr>";
        }
        $print_buffer .= "<tr>
           <td><a href=?a=zone&name=" . $zones[$i]["shortname"] . ">" . $zones[$i]["longname"] . "</a></td>
           <td>" . $zones[$i]["shortname"] . "</td>";
        #if ($sort_zone_level_list == TRUE) {
        #    $print_buffer .= "<td align=center>" . round($zones[$i]["val"]) . "</td>";
        #}
        foreach ($levels AS $lkey => $lval) {
            $print_buffer .= "<td align=center>";
            if ($zones[$i][$lkey] > $lowlimit) {
                if ($show_npc_number_in_zone_level_list == TRUE) {
                    $print_buffer .= $zones[$i][$lkey];
                } else {
                    $print_buffer .= "x";
                }
            }
            $print_buffer .= "</td>";
        }
    }
}
$print_buffer .= "</table>";
$print_buffer .= "</td><td width=0% nowrap>";
// right column, unused
$print_buffer .= "</td></tr></table>";


?>
