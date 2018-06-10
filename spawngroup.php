<?php
require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'mysql.php');
require_once($includes_dir . 'functions.php');

$id = (isset($_GET['id']) ? $_GET['id'] : '');

if (!is_numeric($id) || $display_spawn_group_info == FALSE) {
    header("Location: index.php");
    exit();
}
$query = "SELECT $spawn_group_table.name AS sgname, $spawn2_table.*,
        $zones_table.long_name AS zone, $zones_table.short_name AS spawnzone
        FROM $spawn_group_table,$spawn2_table,$zones_table
        WHERE $spawn_group_table.id=$id
          AND $spawn2_table.spawngroupID=$spawn_group_table.id
          AND $spawn2_table.zone=$zones_table.short_name";
$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
$spawn = mysqli_fetch_array($result);
$page_title = $spawn["sgname"] . " (" . $spawn["zone"] . " : " . floor($spawn["y"]) . "," . floor($spawn["x"]) . "," . floor($spawn["z"]) . ")";
$x = floor($spawn["x"]);
$y = floor($spawn["y"]);
$z = floor($spawn["z"]);


if (!isset($id) || $id == '') {
    print "<script>document.location=\"index.php\";</script>";
}

print "<table border=0 width=0%><tr valign=top><td width=50% nowrap>\n";
$query = "SELECT $spawn_entry_table.chance,$npc_types_table.name,$npc_types_table.id
        FROM $spawn_entry_table,$npc_types_table
        WHERE $spawn_entry_table.spawngroupID=$id
          AND $spawn_entry_table.npcID=$npc_types_table.id
        ORDER BY $npc_types_table.name ASC
        ";
$result = db_mysql_query($query) or message_die('spawngroup.php', 'MYSQL_QUERY', $query, mysqli_error());
print "<b>NPCs composing that spawngroup :</b>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        print "<li><a href=?a=npc&id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["chance"] . "%)";
    }
}
print "</td><td width=50% nowrap>";
print "<b>NPCs spawning around that spawngroup : </b><br>(Max range : $spawngroup_around_range)<ul>";
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
            print "</ul><li><a href=$PHP_SELF?id=" . $row["sgid"] . ">" . $row["sgname"] . "</a>, range=";
            print floor(sqrt(($x - $row["x"]) * ($x - $row["x"]) + ($y - $row["y"]) * ($y - $row["y"])));
            print " (" . floor($row["y"]) . "," . floor($row["x"]) . "," . floor($row["z"]) . ")<ul>";
        }
        print "<li><a href=?a=npc&id=" . $row["id"] . ">" . $row["name"] . "</a> (" . $row["chance"] . "%)";
    }
} else {
    print "None... ";
}
print "</ul></td></tr></table>";


?>