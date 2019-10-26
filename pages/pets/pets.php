<?php


$class = (isset($_GET['class']) ? $_GET['class'] : 0);

if (!is_numeric($class)) {
    header("Location: ?a=pets");
    exit();
}

$page_title = "Pets :: Pet Statistics";

$print_buffer .= "<table class='container_div display_table'><tr valign=top><td>";
$print_buffer .= "<h2 class='section_header'>Choose a Class</h2><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=pets&class=15 id='class15'>Beastlord</a>";
$print_buffer .= "<li><a href=?a=pets&class=2  id='class2'>Cleric</a>";
$print_buffer .= "<li><a href=?a=pets&class=6  id='class6'>Druid</a>";
$print_buffer .= "<li><a href=?a=pets&class=14  id='class14'>Enchanter</a>";
$print_buffer .= "<li><a href=?a=pets&class=13  id='class13'>Magician</a>";
$print_buffer .= "<li><a href=?a=pets&class=11  id='class11'>Necromancer</a>";
$print_buffer .= "<li><a href=?a=pets&class=5  id='class5'>Shadowknight</a>";
$print_buffer .= "<li><a href=?a=pets&class=10  id='class10'>Shaman</a>";
$print_buffer .= "<li><a href=?a=pets&class=12  id='class12'>Wizard</a>";
$print_buffer .= "</ul>";
$print_buffer .= "</td></tr></table>";

if (isset($class) && $class != 0) {
    $Query = "SELECT
			$spells_table.`name`,
			$spells_table.id,
			$spells_table.new_icon,
			$spells_table.teleport_zone,
			$spells_table.classes" . $class . ",
			$npc_types_table.race,
			$npc_types_table.level,
			$npc_types_table.class,
			$npc_types_table.hp,
			$npc_types_table.mana,
			$npc_types_table.AC,
			$npc_types_table.mindmg,
			$npc_types_table.maxdmg
			FROM
			$spells_table
			INNER JOIN $pets_table ON $pets_table.type = $spells_table.teleport_zone
			INNER JOIN $npc_types_table ON $npc_types_table.`name` = $spells_table.teleport_zone
			WHERE $spells_table.classes" . $class . " > 0
			AND $spells_table.classes" . $class . " < " . $server_max_level;

    if ($use_spell_globals == true) {
        $Query .= " AND ISNULL((SELECT spell_globals.spellid FROM spell_globals WHERE spell_globals.spellid = $spells_table.`id`))";
    }
    $Query  .= " GROUP BY $spells_table.`teleport_zone` ORDER BY $spells_table.classes" . $class;
    $result = db_mysql_query($Query);
    if (!$result) {
        $print_buffer .= 'Could not run query: ' . mysqli_error();
        exit;
    }
    $columns = mysqli_num_fields($result);

    $print_buffer .= '<h1>' . $dbclasses[$class] . '</h1>';

    $print_buffer .= "<table class='datatable' style='clear:none'><thead>";
    $print_buffer .= "<th class='menuh'>Level</th>";
    $print_buffer .= "<th class='menuh'>Icon</th>";
    $print_buffer .= "<th class='menuh'>Spell</th>";
    $print_buffer .= "<th class='menuh'>Details</th>";
    $print_buffer .= "<th class='menuh'>Race</th>";
    $print_buffer .= "<th class='menuh'>Level</th>";
    $print_buffer .= "<th class='menuh'>Class</th>";
    $print_buffer .= "<th class='menuh'>HP</th>";
    $print_buffer .= "<th class='menuh'>Mana</th>";
    $print_buffer .= "<th class='menuh'>Armor Class</th>";
    $print_buffer .= "<th class='menuh'>Damage</th>";


    $RowClass = "lr";
    $print_buffer .= "</tr></thead><tbody>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<tr class='" . $RowClass . "'>";
			$print_buffer .= "<td>" . $row["classes" . $class] . "</td>";
			$print_buffer .= "<td><img src='" . $icons_url . $row["new_icon"] . ".gif' align='center' border='1' width='20' height='20'></td>";
			$print_buffer .= "<td><a href='?a=spell&id=" . $row['id'] . "'>  " . $row['name'] . " </a></td>";
			$print_buffer .= "<td><a href='?a=pet&name=" . $row['teleport_zone'] . "'>View</a></td>";
			$print_buffer .= "<td>" . $dbiracenames[$row["race"]]. "</td>";
			$print_buffer .= "<td>" . $row["level"] . "</td>";
			$print_buffer .= "<td>" . $row["class"] . "</td>";
			$print_buffer .= "<td>" . number_format($row["hp"]) . "</td>";
			$print_buffer .= "<td>" . number_format($row["mana"]) . "</td>";
			$print_buffer .= "<td>" . number_format($row["ac"]) . "</td>";
			$print_buffer .= "<td>" . number_format($row["mindmg"]) . " to " . number_format($row["maxdmg"]) . "</td>";
        $print_buffer .= "</tr>";

        if ($RowClass == "lr") {
            $RowClass = "dr";
        } else {
            $RowClass = "lr";
        }
    }
    $print_buffer .= "</tbody></table>";
}

?>