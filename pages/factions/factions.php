<?php
/** If the parameter 'isearch' is set, queries for the factions matching 'iname' and displays them, along with a faction search form.
 *    If only one and only one faction is found then this faction is displayed.
 *  If 'isearch' is not set, displays a search faction form.
 *  If 'iname' is not set then it is equivalent to searching for all factions.
 *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'iname'.
 */

$isearch = (isset($_GET['q']) ? $_GET['q'] : '');
$iname = $_GET['q'];


    if ($iname == "") {
        $name = "";
    } else {
        $name = str_replace('_', '%', addslashes($iname));
    }

    $query = "
        SELECT $faction_list_table.id,$faction_list_table.`name`
        FROM $faction_list_table
        " . ($iname != "" ? "WHERE $faction_list_table.`name` like '%" . $name . "%'" : '') . "
        ORDER BY $faction_list_table.`name`
        LIMIT 500
    ";

    $result = db_mysql_query($query);


$page_title = "Faction Search";

$print_buffer .= "<table border='0' width='0%'><form method='GET' action='" . $PHP_SELF . "'>";
$print_buffer .= "<tr>";
$print_buffer .= '<input type="hidden" name="a" value="factions">';
$print_buffer .= "
    <td>
        " . search_box("q", $iname, "Search for Factions") . "
    </td>
";
$print_buffer .= "</tr>";
$print_buffer .= "<tr align='center'>";
$print_buffer .= "<td colspan='2'>
    <br>
    <a class=\"button submit\">Search</a>
</td>";
$print_buffer .= "</tr>";
$print_buffer .= "</form></table>";
$print_buffer .= "<br>";

if (isset($result)) {
    $print_buffer .= print_query_results($result, 500, "?a=faction&", "faction", "factions", "id", "name");
}


?>
