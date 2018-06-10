<?php
require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'mysql.php');
require_once($includes_dir . 'functions.php');
require_once($includes_dir . 'spell.inc.php');

$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
$iclass = (isset($_GET['iclass']) ? $_GET['iclass'] : 0);
$ieffect = (isset($_GET['ieffect']) ? $_GET['ieffect'] : 0);
$imin = (isset($_GET['imin']) ? $_GET['imin'] : 0);
$imax = (isset($_GET['imax']) ? $_GET['imax'] : 0);

if ($imin == 0 || !is_numeric($imin)) {
    $imin = 1;
}
if ($imax == 0 || !is_numeric($imax)) {
    $imax = $server_max_level;
}
$query = "SELECT $spells_table.*
        FROM $spells_table
        WHERE 1=1";
if ($iname != "") {
    $iname = str_replace(' ', '%', $iname);
    $query .= " AND name like '%" . str_replace(" ", "%", addslashes($iname)) . "%'";
}
if (is_numeric($ieffect) && $ieffect >= 0) {
    $query .= " AND (";
    $s = "";
    for ($i = 1; $i <= 12; $i++) {
        $query .= " $s effectid$i=$ieffect";
        $s = "OR";
    }
    $query .= ")";
}
if (is_numeric($iclass) && $iclass > 0) {
    $query .= " AND level$iclass>=$imin AND level$iclass<=$imax ORDER BY level$iclass ASC,name ASC";
} else {
    $query .= " ORDER BY name";
}

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=spells.csv");
$result = db_mysql_query($query) or message_die('spells.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $content = "";
    $l = 0;
    if (!($iclass > 0)) {
        print "Spell name,Mana,Duration/Effects,Casting Time,Target\n";
    }
    while ($row = mysqli_fetch_array($result)) {
        if (($iclass > 0) AND ($l != $row["level$iclass"])) {
            print "\nLevel " . $row["level$iclass"] . "\n";
            print "Spell name,Mana,Duration/Effects,Casting Time,Target\n";
            $l = $row["level$iclass"];
        }
        print $row["name"];
        print "," . $row["mana"];
        $duration = CalcBuffDuration($server_max_level, $row["buffformula"], $row["buffduration"]);
        if ($duration == 0) {
            print ",Instant";
        } else {
            print "," . translate_time($duration * 6) . " ($duration ticks)";
        }
        print "," . ($row["casttime"] / 1000) . " sec";
        print "," . $dbspelltargets[$row["targettype"]];
        print "\n";
        for ($n = 1; $n <= 12; $n++) {
            SpellDescription($row, $n, true);
        }
    }
} else {
    print "No spell found";
}
?>