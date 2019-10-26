<?php
/** If the parameter 'isearch' is set, queries for the factions matching 'q' and displays them, along with a faction display form.
 *    If only one and only one faction is found then this faction is displayed.
 *  If 'isearch' is not set, displays a search faction form.
 *  If 'q' is not set then it is equivalent to searching for all factions.
 *  For compatbility with Wikis and multi-word searches, underscores are treated as jokers in 'q'.
 */

$task_name = (isset($_GET['q']) ? $_GET['q'] : '');
if ($task_name != "") {
	if ($task_name == "") 
		$name = "";
	else
		$name = get_task_name($task_name);
	
	$post_query = "
		SELECT
			$task_table.id,
			$task_table.`title`
		FROM
			$task_table
		WHERE
			1 = 1
	";
	
	if ($name != "") {
		$name = str_replace('`', '-', str_replace('_', '%', str_replace(' ', '%', $name)));
		$post_query .= " AND $task_table.`title` like '%$name%'";
	}
	
	$post_query .= " ORDER BY $task_table.id, $task_table.title LIMIT " . (get_max_query_results_count($max_tasks_returned) + 1);
	
	$result = db_mysql_query($post_query);
	
	if (mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_array($result);
		header("Location: ?a=task&id=" . $row["id"]);
		exit();
	}
}
$page_title = "Task Search";

$print_buffer .= '
    <form method="GET" action="' . $PHP_SELF . '">
        <input type="hidden" name="a" value="tasks">
        <table>
            <tr>
                <td>
                    ' . search_box("q", $task_name, "Search for Tasks") . '
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
        $max_tasks_returned,
        "?a=task&",
        "task",
        "tasks",
        "id",
        "title"
    );
}

?>