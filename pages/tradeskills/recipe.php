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

$result = db_mysql_query($query);
$recipe = mysqli_fetch_array($result);

$print_buffer .= "<table  class='display_table container_div'>";
$print_buffer .= '
		<tr>
			<td colspan="2">
				<h2 class="section_header">Info</h2>
			</td>
		</tr>';

$print_buffer .= '<tr><td><ul>';
$print_buffer .= "<b>Tradeskill: </b>" . $dbskills[$recipe["tradeskill"]] . "<br>";
if ($recipe["skillneeded"] > 0)
    $print_buffer .= "<b>Skill Needed: </b></td><td>" . $recipe["skillneeded"] . "<br>";
	
if ($recipe["trivial"])
	$print_buffer .= "<b>Trivial: </b>" . $recipe["trivial"] . "<br>";

if ($recipe["notes"] != "")
    $print_buffer .= "<b>Notes: </b>" . $recipe["notes"] . "<br>";

if ($recipe["nofail"] > 0)
    $print_buffer .= "<b>This recipe cannot fail.</b><br>";

$print_buffer .= '</ul></td></tr>';

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

$result = db_mysql_query($query);

if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Containers</h2>";
    $print_buffer .= "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
            "<a href=?a=item&id=" . $row["item_id"] . " id=" . $row["item_id"] . ">" .
            str_replace("_", " ", $row["Name"]) . "</a>";
        if ($recipe["replace_container"] == 1)
            $print_buffer .= " (Consumed)";
		
		$print_buffer .= "<br>";
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

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Creates</h2><ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
           $row["successcount"] . " " .
		   "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 110) . ">" .
            str_replace("_", " ", $row["Name"]) . "</a><br>";
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

    $result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        $print_buffer .= "<tr><td><h2 class='section_header'>Failure</h2><ul>";
        while ($row = mysqli_fetch_array($result)) {
            $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'/>" .
				$row["failcount"] . " " .
                "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 10) . ">" .
                str_replace("_", " ", $row["Name"]) . "</a><br>";
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

$result = db_mysql_query($query) or message_die('recipe.php', 'MYSQL_QUERY', $query, mysqli_error());
if (mysqli_num_rows($result) > 0) {
    $print_buffer .= "<tr><td><h2 class='section_header'>Required</h2><ul>";

    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<img src='" . $icons_url . "item_" . $row["icon"] . ".png' align='left' width='15' height='15' class='icon_pad'	/> " .
			$row["componentcount"] . " " . 
            "<a href=?a=item&id=" . $row["item_id"] . " id=" . ($row["item_id"] * 100) . ">" .
            str_replace("_", " ", $row["Name"]) . "</a><br>";
    }
    $print_buffer .= "</td></tr>";
}
$print_buffer .= "</table>";


?>