<?php
/** Displays the item identified by 'id' if it is specified and a item by this ID exists.
 *  Otherwise queries for the items identified by 'name'. Underscores are considered as spaces, for Wiki compatibility.
 *    If exactly one item is found, displays this item.
 *    Otherwise redirects to the item search page, displaying the results for '%name%'.
 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid item ID, redirects to the item search page.
 */


require_once('functions.php');

$item_id = (isset($_GET['id']) ? addslashes($_GET['id']) : '');
$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

if ($item_id != "" && is_numeric($item_id)) {
    if ($discovered_items_only == TRUE) {
        $Query = "SELECT * FROM $items_table, discovered_items WHERE $items_table.id='" . $item_id . "' AND discovered_items.item_id=$items_table.id";
    } else {
        $Query = "SELECT * FROM $items_table WHERE id='" . $item_id . "'";
    }
    $QueryResult = db_mysql_query($Query) or message_die('item.php', 'MYSQL_QUERY', $Query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: items.php");
        exit();
    }
    $ItemRow = mysql_fetch_array($QueryResult);
    $name = $ItemRow["name"];
} elseif ($name != "") {
    if ($discovered_items_only == TRUE) {
        $Query = "SELECT * FROM $items_table, discovered_items WHERE $items_table.`name` like '$name' AND discovered_items.item_id=$items_table.id";
    } else {
        $Query = "SELECT * FROM $items_table WHERE name like '$name'";
    }
    $QueryResult = db_mysql_query($Query) or message_die('item.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($QueryResult) == 0) {
        header("Location: items.php?iname=" . $name . "&isearch=true");
        exit();
    } else {
        $ItemRow = mysql_fetch_array($QueryResult);
        $item_id = $ItemRow["id"];
        $name = $ItemRow["name"];
    }
} else {
    header("Location: items.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the item to display
 *    $name : name of the item to display
 *    $ItemRow : row of the item to display extracted from the database
 *    The item actually exists
 */

$page_title = strip_underscores($item['Name']);

$item = $ItemRow;

$print_buffer .= "<table >";

$item_data = return_item_stat_box($item, 0);
$item_icon = return_item_icon_from_icon_id($item['icon'], 25);

$content = display_header('<h2>' . $item_icon . ' ' . $item['Name'] . '</h2>');
$content .= display_row($item_data);
$content .= return_where_item_foraged($item_id);
$content .= return_where_item_used_trade_skills($item_id);
$content .= return_where_item_result_trade_skill($item_id);
$content .= return_where_item_sold($item_id);
$content .= return_where_item_ground_spawn($item_id);
$content .= $where_item_ground_spawn;

$print_buffer .= display_table($content);

?>
