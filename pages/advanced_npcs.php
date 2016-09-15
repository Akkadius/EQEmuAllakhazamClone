<?php
$Title = "Advanced NPC Search";
require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'mysql.php');

require_once($includes_dir . 'functions.php');

$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
$id = (isset($_GET['id']) ? addslashes($_GET['id']) : '');
$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
$iminlevel = (isset($_GET['iminlevel']) ? $_GET['iminlevel'] : '');
$imaxlevel = (isset($_GET['imaxlevel']) ? $_GET['imaxlevel'] : '');
$inamed = (isset($_GET['inamed']) ? $_GET['inamed'] : '');
$ishowlevel = (isset($_GET['ishowlevel']) ? $_GET['ishowlevel'] : '');
$irace = (isset($_GET['irace']) ? $_GET['irace'] : '');
if ($irace == 0) {
    $irace = '';
}

print "<table border=0 width=0%><tr valign=top><td>";
print "<table border=0 width=0%>";
print "<form method=GET action=$PHP_SELF>";
echo '<input type="hidden" name="a" value="advanced_npcs">';
print "<tr><td><b>Name : </b></td><td><input type=text value=\"$iname\" size=30 name=iname ></td></tr>";
print "<tr><td><b>Level : </b></td><td>Between ";
print SelectLevel("iminlevel", $server_max_npc_level, $iminlevel);
print " and ";
print SelectLevel("imaxlevel", $server_max_npc_level, $imaxlevel);
print "</tr>";
print "<tr><td><b>Race : </b></td><td>";
print SelectMobRace("irace", $irace);
print "</td></tr>";
print "<tr><td><b>Named mob : </b></td><td><input type=checkbox name=inamed " . ($inamed ? " checked" : "") . "></td></tr>";
print "</table></td><td><table border=0 width=0%>";
print "<tr><td><b>Show level : </b></td><td><input type=checkbox name=ishowlevel " . ($ishowlevel ? " checked" : "") . "></td></tr>";
print "</table>";
print "<tr align=center colspan=2><td colspan=2><input type=submit value=Search name=isearch class=form></td></tr>";
print "</form></table>";

if (isset($isearch) && $isearch != '') {
    $query = "SELECT $tbnpctypes.id,$tbnpctypes.name,$tbnpctypes.level
				FROM $tbnpctypes
				WHERE 1=1";
    if ($iminlevel > $imaxlevel) {
        $c = $iminlevel;
        $iminlevel = $imaxlevel;
        $imaxlevel = $c;
    }
    if ($iminlevel > 0 && is_numeric($iminlevel)) {
        $query .= " AND $tbnpctypes.level>=$iminlevel";
    }
    if ($imaxlevel > 0 && is_numeric($imaxlevel)) {
        $query .= " AND $tbnpctypes.level<=$imaxlevel";
    }
    if ($inamed) {
        $query .= " AND substring($tbnpctypes.name,1,1)='#'";
    }
    if ($irace > 0 && is_numeric($irace)) {
        $query .= " AND $tbnpctypes.race=$irace";
    }
    if ($iname != "") {
        $iname = str_replace('`', '%', str_replace(' ', '%', addslashes($iname)));
        $query .= " AND $tbnpctypes.name LIKE '%$iname%'";
    }
    if ($hide_invisible_men == TRUE) {
        $query .= " AND $tbnpctypes.race!=127";
    }
    $query .= " ORDER BY $tbnpctypes.name";
    $result = mysql_query($query) or message_die('npcs.php', 'MYSQL_QUERY', $query, mysql_error());
    $n = mysql_num_rows($result);
    if ($n > $max_npcs_returned) {
        print "$n ncps found, showing the $max_npcs_returned first ones...";
        $query .= " LIMIT $max_npcs_returned";
        $result = mysql_query($query) or message_die('npcs.php', 'MYSQL_QUERY', $query, mysql_error());
    }
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            print "<li><a href=?a=npc&id=" . $row["id"] . ">" . ReadableNpcName($row["name"]) . "</a>";
            if ($ishowlevel) {
                print " - level " . $row["level"];
            }
        }
    } else {
        print "<li>No npc found.";
    }
}


?>