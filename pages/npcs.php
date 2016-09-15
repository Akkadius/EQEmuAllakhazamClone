<?php
/** If the parameter 'isearch' is set, queries for the factions matching 'iname' and displays them, along with a faction display form.
 *    If only one and only one faction is found then this faction is displayed.
 *  If 'isearch' is not set, displays a search faction form.
 *  If 'iname' is not set then it is equivalent to searching for all factions.
 *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'iname'.
 */

$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');

if ($isearch != "") {
    if ($iname == "") {
        $name = "";
    } else {
        $name = addslashes($iname);
    }
    $Query = "SELECT $npc_types_table.id,$npc_types_table.`name`
		FROM $npc_types_table
		WHERE 1=1";
    if ($name != "") {
        $name = str_replace('`', '-', str_replace('_', '%', str_replace(' ', '%', $name)));
        $Query .= " AND $npc_types_table.Name like '%$name%'";
    }
    if ($hide_invisible_men) {
        $Query .= " AND $npc_types_table.race != 127 AND $npc_types_table.race != 240";
    }
    $Query .= " ORDER BY $npc_types_table.Name, $npc_types_table.id LIMIT " . (LimitToUse($max_npcs_returned) + 1);

    $QueryResult = db_mysql_query($Query) or message_die('npcs.php', 'MYSQL_QUERY', $Query, mysql_error());

    if (mysql_num_rows($QueryResult) == 1) {
        $row = mysql_fetch_array($QueryResult);
        header("Location: ?a=npc&id=" . $row["id"]);
        exit();
    }
}


/** Here the following holds :
 *    $QueryResult : NPCs queried for if any query was issued, otherwise it is not defined
 *    $iname : previously-typed query, or empty by default
 *    $isearch is set if a query was issued
 */

$Title = "NPCs search";


$print_buffer .= "<table border='0' width='0%'><form method='GET' action='" . $PHP_SELF . "'>\n";
$print_buffer .= '<input type="hidden" name="a" value="npcs">';
$print_buffer .= "<tr align='left'>\n";
$print_buffer .= "<td><b>Name : </b></td>\n";
$print_buffer .= "<td><input type='text' value=\"$iname\" size='30' name='iname'></td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "<tr tr align='left'>\n";
$print_buffer .= "<td='1' colspan='2'><input type='submit' value='Search' name='isearch'/></td>\n";
$print_buffer .= "</tr>\n";
$print_buffer .= "</form></table>\n";

if (isset($QueryResult))
    PrintQueryResults($QueryResult, $max_npcs_returned, "npc.php", "npc", "npcs", "id", "name");


?>
