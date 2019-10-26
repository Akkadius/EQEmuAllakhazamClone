<?php
$page_title = "Advanced NPC Search";

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

$print_buffer .= "<table border=0 width=0%><tr valign=top><td>";
$print_buffer .= "<table border=0 width=0%>";
$print_buffer .= "<form method=GET action=$PHP_SELF>";
$print_buffer .= '<input type="hidden" name="a" value="advanced_npcs">';
$print_buffer .= "<tr><td><b>Name : </b></td><td><input type=text value=\"$iname\" size=30 name=iname ></td></tr>";
$print_buffer .= "<tr><td><b>Level : </b></td><td>Between ";
$print_buffer .= SelectLevel("iminlevel", $server_max_npc_level, $iminlevel);
$print_buffer .= " and ";
$print_buffer .= SelectLevel("imaxlevel", $server_max_npc_level, $imaxlevel);
$print_buffer .= "</tr>";
$print_buffer .= "<tr><td><b>Race : </b></td><td>";
$print_buffer .= SelectMobRace("irace", $irace);
$print_buffer .= "</td></tr>";
$print_buffer .= "<tr><td><b>Named mob : </b></td><td><input type=checkbox name=inamed " . ($inamed ? " checked" : "") . "></td></tr>";
$print_buffer .= "</table></td><td><table border=0 width=0%>";
$print_buffer .= "<tr><td><b>Show level : </b></td><td><input type=checkbox name=ishowlevel " . ($ishowlevel ? " checked" : "") . "></td></tr>";
$print_buffer .= "</table>";
$print_buffer .= "<tr align=center colspan=2><td colspan=2><input type=submit value=Search name=isearch class=form></td></tr>";
$print_buffer .= "</form></table>";

if (isset($isearch) && $isearch != '') {
    $query = "
        SELECT
            $npc_types_table.id,
            $npc_types_table.`name`,
            $npc_types_table.level
        FROM
            $npc_types_table
        WHERE
            1 = 1
    ";
    if ($iminlevel > $imaxlevel) {
        $c = $iminlevel;
        $iminlevel = $imaxlevel;
        $imaxlevel = $c;
    }
    if ($iminlevel > 0 && is_numeric($iminlevel)) {
        $query .= " AND $npc_types_table.level>=$iminlevel";
    }
    if ($imaxlevel > 0 && is_numeric($imaxlevel)) {
        $query .= " AND $npc_types_table.level<=$imaxlevel";
    }
    if ($inamed) {
        $query .= " AND substring($npc_types_table.`name`,1,1)='#'";
    }
    if ($irace > 0 && is_numeric($irace)) {
        $query .= " AND $npc_types_table.race=$irace";
    }
    if ($iname != "") {
        $iname = str_replace('`', '%', str_replace(' ', '%', addslashes($iname)));
        $query .= " AND $npc_types_table.`name` LIKE '%$iname%'";
    }
    if ($hide_invisible_men == TRUE) {
        $query .= " AND $npc_types_table.race!=127";
    }
    $query .= " ORDER BY $npc_types_table.`name`";
    $result = db_mysql_query($query) or message_die('npcs.php', 'MYSQL_QUERY', $query, mysqli_error());
    $n = mysqli_num_rows($result);
    if ($n > $max_npcs_returned) {
        $print_buffer .= "$n NPCs found, showing the $max_npcs_returned first ones...";
        $query .= " LIMIT $max_npcs_returned";
        $result = db_mysql_query($query) or message_die('npcs.php', 'MYSQL_QUERY', $query, mysqli_error());
    }
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $print_buffer .= "<li><a href=?a=npc&id=" . $row["id"] . ">" . get_npc_name_human_readable($row["name"]) . "</a>";
            if ($ishowlevel) {
                $print_buffer .= " - level " . $row["level"];
            }
        }
    } else {
        $print_buffer .= "<li>No npc found.";
    }
}


?>