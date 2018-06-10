<?php
require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'mysql.php');
require_once($includes_dir . 'functions.php');
require_once($includes_dir . 'spell.inc.php');

$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : 'name');
$mode = (isset($_GET['mode']) ? addslashes($_GET["mode"]) : 'npcs');

if ($name == "") exit;
$zone = GetRowByQuery("SELECT * FROM $zones_table WHERE short_name='$name'");

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=$name.csv");

print $zone["long_name"];
print "\nName,Race,Class,Level,Spawn points,Drops\n";

$npcs = preg_split("/:/", $liste);
foreach ($npcs as $id) {
    $txt = "";
    $spawns = array();
    $loots = array();
    $query = "SELECT $npc_types_table.* FROM $npc_types_table WHERE $npc_types_table.id=$id";
    $mymob = GetRowByQuery($query);
    $txt = str_replace(array('_', '#'), ' ', $mymob["name"]) . ",";
    $txt .= $dbiracenames[$mymob["race"]] . ",";
    $txt .= $dbclasses[$mymob["class"]] . ",";
    $txt .= $mymob["level"] . ",";

    $query = "SELECT $spawn2_table.x,$spawn2_table.y,$spawn2_table.z,
             $spawn_group_table.name as spawngroup,
             $spawn_group_table.id as spawngroupID,
             $spawn2_table.respawntime
          FROM $spawn_entry_table,$spawn2_table,$spawn_group_table
          WHERE $spawn_entry_table.npcID=$id
            AND $spawn_entry_table.spawngroupID=$spawn2_table.spawngroupID
            AND $spawn2_table.zone='$name'
            AND $spawn_entry_table.spawngroupID=$spawn_group_table.id";
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $cpt = 0;
        while ($row = mysqli_fetch_array($result)) {
            $spawns[$cpt] = floor($row["y"]) . " / " . floor($row["x"]) . " / " . floor($row["z"]);
            $spawns[$cpt] .= " (" . translate_time($row["respawntime"]) . ")";
            $cpt++;
        }
    }

    if (($mymob["loottable_id"] > 0) AND ((!in_array($mymob["class"], $dbmerchants)) OR ($merchants_dont_drop_stuff == FALSE))) {
        $query = "SELECT $items_table.id,$items_table.Name,$items_table.itemtype,
                   $loot_drop_entries_table.chance,$loot_table_entries.probability,
                   $loot_table_entries.lootdrop_id,$loot_table_entries.multiplier
            FROM $items_table,$loot_table_entries,$loot_drop_entries_table
            WHERE $loot_table_entries.loottable_id=" . $mymob["loottable_id"] . "
              AND $loot_table_entries.lootdrop_id=$loot_drop_entries_table.lootdrop_id
              AND $loot_drop_entries_table.item_id=$items_table.id";
        $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysqli_error());
        if (mysqli_num_rows($result) > 0) {
            $cpt = 0;
            while ($row = mysqli_fetch_array($result)) {
                $loots[$cpt] = $row["Name"];
                $loots[$cpt] .= " (" . $dbitypes[$row["itemtype"]] . ")";
                $cpt++;
            }
        }
    }
    $n = max(count($spawns), count($loots));
    for ($i = 0; $i < $n; $i++) {
        if ($i == 0) {
            print $txt;
        } else {
            print ",,,,";
        }
        if ($i < count($spawns)) {
            print $spawns[$i];
        }
        print ",";
        if ($i < count($loots)) {
            print $loots[$i];
        }
        print "\n";
    }
}

?>