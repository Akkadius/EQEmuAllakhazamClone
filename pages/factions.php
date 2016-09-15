<?php
/** If the parameter 'isearch' is set, queries for the factions matching 'iname' and displays them, along with a faction search form.
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
        $name = str_replace('_', '%', addslashes($iname));
    }

    $Query = "
        SELECT $faction_list_table.id,$faction_list_table.name
        FROM $faction_list_table
        WHERE $faction_list_table.name like '%" . $name . "%'
        ORDER BY $faction_list_table.name
        LIMIT
    " . (LimitToUse($MaxFactionsReturned) + 1);

    $QueryResult = mysql_query($Query) or message_die('factions.php', 'MYSQL_QUERY', $Query, mysql_error());

    if (mysql_num_rows($QueryResult) == 1) {
        $row = mysql_fetch_array($QueryResult);
        header("Location: faction.php?id=" . $row["id"]);
        exit();
    }
}

/** Here the following holds :
 *    $QueryResult : factions queried for if any query was issued, otherwise it is not defined
 *    $iname : previously-typed query, or empty by default
 *    $isearch is set if a query was issued
 */

$Title = "Faction Search";


print "<table border='0' width='0%'><form method='GET' action='" . $PHP_SELF . "'>\n";
print "<tr>\n";
print "<td><b>Search : </b></td>\n";
print '<input type="hidden" name="a" value="factions">';
print "<td><input type='text' value=\"$iname\" size='30' name='iname'/></td>\n";
print "</tr>";
print "<tr align='center'>";
print "<td='1' colspan='2'><input type='submit' value='Search' name='isearch' class='form'/></td>\n";
print "</tr>\n";
print "</form></table>\n";
print "\n";

if (isset($QueryResult)) {
    PrintQueryResults($QueryResult, $MaxFactionsReturned, "?a=faction&", "faction", "factions", "id", "name");
}


?>
