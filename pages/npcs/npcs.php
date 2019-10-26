<?php
/** If the parameter 'isearch' is set, queries for the factions matching 'q' and displays them, along with a faction display form.
 *    If only one and only one faction is found then this faction is displayed.
 *  If 'isearch' is not set, displays a search faction form.
 *  If 'q' is not set then it is equivalent to searching for all factions.
 *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'q'.
 */

$npc_name = (isset($_GET['q']) ? $_GET['q'] : '');

if ($npc_name != "") {
    if ($npc_name == "") {
        $name = "";
    } else {
        $name = addslashes($npc_name);
    }
    $post_query = "
        SELECT
            $npc_types_table.id,
            $npc_types_table.`name`
        FROM
            $npc_types_table
        WHERE
            1 = 1
    ";

    if ($name != "") {
        $name = str_replace('`', '-', str_replace('_', '%', str_replace(' ', '%', $name)));
        $post_query .= " AND $npc_types_table.Name like '%$name%'";
    }
    if ($hide_invisible_men) {
        $post_query .= " AND $npc_types_table.race != 127 AND $npc_types_table.race != 240";
    }
    $post_query .= " ORDER BY $npc_types_table.Name, $npc_types_table.id LIMIT " . (get_max_query_results_count($max_npcs_returned) + 1);

    $result = db_mysql_query($post_query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        header("Location: ?a=npc&id=" . $row["id"]);
        exit();
    }
}
$page_title = "NPC Search";

$print_buffer .= '
    <form method="GET" action="' . $PHP_SELF . '">
        <input type="hidden" name="a" value="npcs">
        <table>
            <tr>
                <td>
                    ' . search_box("q", $npc_name, "Search for NPCs") . '
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <a class="button submit">Submit</a>
                </td>
            </tr>
        </table>
    </form>
';

if (isset($result)){
    $print_buffer .= '<br><hr>';
    $print_buffer .= print_query_results(
        $result,
        $max_npcs_returned,
        "?a=npc&",
        "npc",
        "npcs",
        "id",
        "name"
    );
}

if (!isset($_GET['v_ajax'])) {
    $footer_javascript .= '
            <script src="pages/npcs/npcs.js"></script>
        ';
}

?>