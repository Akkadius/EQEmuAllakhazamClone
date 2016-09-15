<?php


$class = (isset($_GET['class']) ? $_GET['class'] : 0);

if (!is_numeric($class)) {
    header("Location: ?a=pets");
    exit();
}

$Title = "Pets Statistics";

CreateToolTip('class15', '<table> <td> <img src="images/monograms/15.gif"> </td> <td> The Beastlord class is a unique class that is a hybrid of the Shaman and Monk parent classes. One of the class\'s distinguishing features is the ability to summon a warder pet to fight alongside them. The form the warder takes depends on the race of the beastlord. </td>');
CreateToolTip('class6', '<table> <td> <img src="images/monograms/6.gif"> </td> <td> Druids - a powerful outdoor class. With the triple ability to heal, inflict direct damage and cast damage over time, druids are a popular solo class, as well as popular in a group. They are also a travelling class, given both sow and teleportation abilities.  </td>');
CreateToolTip('class14', '<table> <td> <img src="images/monograms/14.gif"> </td> <td> Enchanter - the game\'s traffic cop. When the enchanter yells stop, creatures cease what they are doing and just wait to die. A complex class, enchanters get a variety of spells as well as a pet, and can be played in a number of different ways. </td>');
CreateToolTip('class13', '<table> <td> <img src="images/monograms/13.gif"> </td> <td> Magicians - Dealers of damage. With the combination of a strong pet and the second best set of direct damage spells, magicians can deal out some serious damage.  </td>');
CreateToolTip('class11', '<table><td><img src="images/monograms/11.gif"> </td> <td> Necromancers - Masters of death. With their powerful pets and variety of damage over time spells, necromancers are one of the best solo classes in the game. </td>');
CreateToolTip('class5', '<table> <td> <img src="images/monograms/5.gif"> </td> <td> Shadowknights - The evil warriors. A combination of fighter and necromancer, the shadowknight is a complex class to develop and play correctly.  </td>');
CreateToolTip('class10', '<table> <td> <img src="images/monograms/10.gif"> </td> <td> Shamen - primitive power. The only magic using class of the barbarians, ogres and trolls, shamen get a variety of spells that combine aspects of most of the other magic using classes. This combination, along with the racial strength, means the class can be played in a variety of different ways.  </td>');
CreateToolTip('class2', '<table> <td> <img src="images/monograms/2.gif"> </td> <td> Clerics -- the premier healer in the game. A popular class for any group, the cleric brings the ability to heal the party and keep you from dying, and can even bring you back from death at higher levels.   </td>');
CreateToolTip('class12', '<table> <td> <img src="images/monograms/12.gif"> </td> <td> S	The Wizard - Master Nuker. The wizard\'s abilities are in direct damage spells and in individual and group teleportation.   </td>');
$print_buffer .= "<table class='container_div display_table'><tr valign=top><td>";
$print_buffer .= "<h2 class='section_header'>Choose a class:</h2><ul style='text-align:left'>";
$print_buffer .= "<li><a href=?a=pets&class=15 id='class15'>Beastlord</a>";
$print_buffer .= "<li><a href=?a=pets&class=2  id='class2'>Cleric</a>";
$print_buffer .= "<li><a href=?a=pets&class=6  id='class6'>Druid</a>";
$print_buffer .= "<li><a href=?a=pets&class=14  id='class14'>Enchanter</a>";
$print_buffer .= "<li><a href=?a=pets&class=13  id='class13'>Magician</a>";
$print_buffer .= "<li><a href=?a=pets&class=11  id='class11'>Necromancer</a>";
$print_buffer .= "<li><a href=?a=pets&class=5  id='class5'>Shadow knight</a>";
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

    if ($use_spell_globals == TRUE) {
        $Query .= " AND ISNULL((SELECT spell_globals.spellid FROM spell_globals WHERE spell_globals.spellid = $spells_table.`id`))";
    }
    $Query .= " GROUP BY $spells_table.`teleport_zone` ORDER BY $spells_table.classes" . $class;
    $result = db_mysql_query($Query);
    if (!$result) {
        $print_buffer .= 'Could not run query: ' . mysql_error();
        exit;
    }
    $columns = mysql_num_fields($result);

    $print_buffer .= '<h1>' . $dbclasses[$class] . '</h1>';

    $print_buffer .= '<hr>';

    $print_buffer .= "<table><thead>";
    $print_buffer .= "<th class='menuh'>Level</th>";
    $print_buffer .= "<th class='menuh'>Icon</th>";
    $print_buffer .= "<th class='menuh'>Spell Name</th>";
    $print_buffer .= "<th class='menuh'>Details</th>";
    $print_buffer .= "<th class='menuh'>Race</th>";
    $print_buffer .= "<th class='menuh'>Pet Level</th>";
    $print_buffer .= "<th class='menuh'>Pet Class</th>";
    $print_buffer .= "<th class='menuh'>HP</th>";
    $print_buffer .= "<th class='menuh'>Mana</th>";
    $print_buffer .= "<th class='menuh'>AC</th>";
    $print_buffer .= "<th class='menuh'>Min Damage</th>";
    $print_buffer .= "<th class='menuh'>Max Damage</th>";


    $RowClass = "lr";
    $print_buffer .= "</tr></thead><tbody>";
    while ($row = mysql_fetch_array($result)) {
        $print_buffer .= "<tr class='" . $RowClass . "'>";
        $print_buffer .= "<td>" . $row["classes" . $class] . "</td>";
        $print_buffer .= "<td><img src='" . $icons_url . $row["new_icon"] . ".gif' align='center' border='1' width='20' height='20'></td>";
        $print_buffer .= "<td><a href='?a=spell&id=" . $row['id'] . "'>  " . $row['name'] . " </a></td>";
        $print_buffer .= "<td><a href='?a=pet&name=" . $row['teleport_zone'] . "'>View</a></td>";
        $print_buffer .= "<td>" . $dbiracenames[$row["race"]] . "</td>";
        $print_buffer .= "<td>" . $row["level"] . "</td>";
        $print_buffer .= "<td>" . $row["class"] . "</td>";
        $print_buffer .= "<td>" . $row["hp"] . "</td>";
        $print_buffer .= "<td>" . $row["mana"] . "</td>";
        $print_buffer .= "<td>" . $row["ac"] . "</td>";
        $print_buffer .= "<td>" . $row["mindmg"] . "</td>";
        $print_buffer .= "<td>" . $row["maxdmg"] . "</td>";
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