<?php


$id = (isset($_GET['id']) ? $_GET['id'] : '');

if (!is_numeric($id)) {
    header("Location: ?a=recipes&");
    exit();
}

$page_title = "Recipe :: " . str_replace('_', ' ', get_field_result("name", "SELECT name FROM $trade_skill_recipe_table WHERE id=$id"));


if (!isset($id)) {
    $print_buffer .= "<script>document.location=\"index.php\";</script>";
}

$query = "SELECT *
			FROM $trade_skill_recipe_table
			WHERE id=$id";

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysql_error());
$recipe = mysql_fetch_array($result);

$print_buffer .= "<table  class='display_table container_div'>";
$print_buffer .= '
		<tr>
			<td colspan="2">
				<h2 class=\'section_header\'>Recipe</h2>
			</td>
		</tr>';
$print_buffer .= "<tr><td style='text-align:right;'><b>Recipe: </b></td><td>" . ucfirstwords(str_replace('_', ' ', $recipe["name"])) . "</td></tr>";
$print_buffer .= "<tr><td style='text-align:right'><b>Tradeskill: </b></td><td>" . ucfirstwords($dbskills[$recipe["tradeskill"]]) . "</td></tr>";
if ($recipe["skillneeded"] > 0) {
    $print_buffer .= "<tr><td style='text-align:right'><b>Skill needed : </b></td><td>" . $recipe["skillneeded"] . "</td></tr>";
}
$print_buffer .= "<tr><td style='text-align:right'><b>Trivial at: </b></td><td>" . $recipe["trivial"] . "</td></tr>";
if ($recipe["nofail"] > 0) {
    $print_buffer .= "<tr><td style='text-align:right' colspan=2>This recipe cannot fail.</td></tr>";
}
if ($recipe["notes"] != "") {
    $print_buffer .= "<tr><td style='text-align:right' cospan=2><b>Notes : </b>" . $recipe["notes"] . "</td></tr>";
}
$print_buffer .= '</table><br>';

$print_buffer .= '<table class="display_table container_div">';
// results containers
$query = "
    SELECT
        $trade_skill_recipe_entries.*, $items_table.*, $items_table.id AS item_id
    FROM
        $trade_skill_recipe_table,
        $trade_skill_recipe_entries,
        $items_table
    WHERE
        $trade_skill_recipe_table.id = $trade_skill_recipe_entries.recipe_id
    AND $trade_skill_recipe_entries.recipe_id = $id
    AND $trade_skill_recipe_entries.item_id = $items_table.id
    AND $trade_skill_recipe_entries.iscontainer = 1
";

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysql_error());

if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Containers</h2>";
    $print_buffer .= "<ul>";
    while ($row = mysql_fetch_array($result)) {
        CreateToolTip($row["item_id"], return_item_stat_box($row, 1));
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
            "<a href=?a=item&id=" . $row["item_id"] . " id=" . $row["item_id"] . ">" .
            str_replace("_", " ", $row["Name"]) . "</a><br>";
        if ($recipe["replace_container"] == 1) {
            $print_buffer .= " (this container will disappear after combine)";
        }
    }
    $print_buffer .= "</ul></td></tr>";
}


// results success
$query = "
    SELECT
        $trade_skill_recipe_entries.*, $items_table.*, $items_table.id AS item_id
    FROM
        $trade_skill_recipe_table,
        $trade_skill_recipe_entries,
        $items_table
    WHERE
        $trade_skill_recipe_table.id = $trade_skill_recipe_entries.recipe_id
    AND $trade_skill_recipe_entries.recipe_id = $id
    AND $trade_skill_recipe_entries.item_id = $items_table.id
    AND $trade_skill_recipe_entries.successcount > 0
";

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Creates</h2><ul>";
    while ($row = mysql_fetch_array($result)) {
        CreateToolTip(($row["item_id"] * 110), return_item_stat_box($row, 1));
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
            "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 110) . ">" .
            str_replace("_", " ", $row["Name"]) . "</a> x" . $row["successcount"] . " <br>";
    }
    $print_buffer .= "</ul></td></tr>";
}

if ($recipe["nofail"] == 0) {
    // results fail
    $query = "
        SELECT
            $trade_skill_recipe_entries.*, $items_table.*, $items_table.id AS item_id
        FROM
            $trade_skill_recipe_table,
            $trade_skill_recipe_entries,
            $items_table
        WHERE
            $trade_skill_recipe_table.id = $trade_skill_recipe_entries.recipe_id
        AND $trade_skill_recipe_entries.recipe_id = $id
        AND $trade_skill_recipe_entries.item_id = $items_table.id
        AND $trade_skill_recipe_entries.failcount > 0
    ";

    $result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        $print_buffer .= "<tr><td><h2 class='section_header'>Failure</h2><ul>";
        while ($row = mysql_fetch_array($result)) {
            CreateToolTip(($row["item_id"] * 10), return_item_stat_box($row, 1));
            $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
                "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 10) . ">" .
                str_replace("_", " ", $row["Name"]) . "</a> x" . $row["failcount"] . " <br>";
        }
        $print_buffer .= "</td></tr>";
    }
}

// components
$query = "
    SELECT
        $trade_skill_recipe_entries.*, $items_table.*, $items_table.id AS item_id
    FROM
        $trade_skill_recipe_table,
        $trade_skill_recipe_entries,
        $items_table
    WHERE
        $trade_skill_recipe_table.id = $trade_skill_recipe_entries.recipe_id
    AND $trade_skill_recipe_entries.recipe_id = $id
    AND $trade_skill_recipe_entries.item_id = $items_table.id
    AND $trade_skill_recipe_entries.iscontainer = 0
    AND $trade_skill_recipe_entries.componentcount > 0
";

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysql_error());
if (mysql_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Required</h2><ul>";

    while ($row = mysql_fetch_array($result)) {
        CreateToolTip(($row["item_id"] * 100), return_item_stat_box($row, 1));
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'	/> " .
            "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 100) . ">" .
            str_replace("_", " ", $row["Name"]) . "</a> x " . $row["componentcount"] . " <br>";
    }
    $print_buffer .= "</td></tr>";
}
$print_buffer .= "</table>";


?>