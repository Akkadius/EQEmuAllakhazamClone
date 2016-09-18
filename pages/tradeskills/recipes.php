<?php
$page_title = "Search Recipes";

$minskill = (isset($_GET['minskill']) ? $_GET['minskill'] : 0);
$maxskill = (isset($_GET['maxskill']) ? $_GET['maxskill'] : 0);
$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
$iskill = (isset($_GET['iskill']) ? $_GET['iskill'] : 0);

if (!isset($maxskill)) {
    $maxskill = 0;
}
if (!isset($minskill)) {
    $minskill = 0;
}
if (!ctype_digit($maxskill)) {
    $maxskill = 0;
}
if (!ctype_digit($minskill)) {
    $minskill = 0;
}
if ($minskill > $maxskill) {
    $tempskill = $minskill;
    $minskill = $maxskill;
    $maxskill = $tempskill;
}

$print_buffer .= "<table border=0>";
$print_buffer .= "<form method='GET' action=$PHP_SELF>";
$print_buffer .= '<input type="hidden" name="a" value="recipes">';
$print_buffer .= "<tr><td><b>Name : </b></td><td><input type=text value=\"$iname\" size=30 name=iname></td></tr>";
$print_buffer .= "<tr><td><b>Tradeskill : </b></td><td>";
$print_buffer .= SelectTradeSkills("iskill", $iskill);
$print_buffer .= "</td></tr>";
$print_buffer .= "<tr><td><b>Min trivial skill : </b></td><td><input type=text value=\"$minskill\" size=4 name=minskill></td></tr>";
$print_buffer .= "<tr><td><b>Max trivial skill : </b></td><td><input type=text value=\"$maxskill\" size=4 name=maxskill></td></tr>";
$print_buffer .= "<tr align=center><td colspan=2><input type='submit' value='Search' name='isearch' class='form'/> <input type='reset' value='Reset' class='form'/></td></tr>";
$print_buffer .= "</form></table>";

if (isset($isearch) && $isearch != "") {
    if ($minskill > $maxskill) {
        $tempskill = $minskill;
        $minskill = $maxskill;
        $maxskill = $tempskill;
    }
    $query = "
        SELECT
            $trade_skill_recipe_table.id,
            $trade_skill_recipe_table.`name`,
            $trade_skill_recipe_table.tradeskill,
            $trade_skill_recipe_table.trivial
        FROM
            $trade_skill_recipe_table
    ";
    $s = "WHERE";
    if ($iname != "") {
        $iname = str_replace(' ', '%', addslashes($iname));
        $query .= " $s $trade_skill_recipe_table.`name` like '%" . $iname . "%'";
        $s = "AND";
    }
    if ($iskill > 0) {
        $query .= " $s $trade_skill_recipe_table.tradeskill=$iskill";
        $s = "AND";
    }
    if ($minskill > 0) {
        $query .= " $s $trade_skill_recipe_table.trivial>=$minskill";
        $s = "AND";
    }
    if ($maxskill > 0) {
        $query .= " $s $trade_skill_recipe_table.trivial<=$maxskill";
        $s = "AND";
    }
    $query .= " ORDER BY $trade_skill_recipe_table.`name`";
    $result = db_mysql_query($query) or message_die('?a=recipes&', 'MYSQL_QUERY', $query, mysql_error());

    $print_buffer .= '<div>';
    if (isset($result)) {
        $print_buffer .= print_query_results($result, $max_items_returned, "?a=recipe&", "recipe", "recipes", "id", "name", "trivial", "trivial at level", "tradeskill");
    }
    $print_buffer .= '</div>';
}


?>