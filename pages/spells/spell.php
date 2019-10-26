<?php

require_once($includes_dir . 'spell.inc.php');
$id = $_GET["id"];
$spell = getspell($id);
if (!$spell) {
    header("Location: ?a=spells");
    exit();
}
$page_title = $spell["name"] . ' ';

$page_title = "Spell :: " . str_replace('"', "'", $page_title);

$print_buffer .= "<table class='container_div ' style='width:500px;padding:10px'><tr style='vertical-align:middle !important'>";

$print_buffer .= "
    <tr>
        <td style='vertical-align:middle;text-align:right;width:150px;padding-right: 15px'>
           " . '<img src="' . $icons_url . $spell['new_icon'] . '.gif" style="border-radius:5px"> ' . "
        </td>
        <td style='vertical-align:middle'>
            <h1>" . $spell['name'] . "</h1>
        </td>
    </tr>
";
$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Info</h2></td></tr>";

$v = "";
$minlvl = 70;
$class_found = 0;
$class_data = "";
for ($i = 1; $i <= 16; $i++) {
    if (($spell["classes$i"] > 0) AND ($spell["classes$i"] < 255)) {
        $class_found = 1;
        $class_data .= "$v " . $dbclasses[$i] . " (" . $spell["classes$i"] . ")";
        $v = ",";
        if ($spell["classes$i"] < $minlvl) {
            $minlvl = $spell["classes$i"];
        }
    }
}

if($class_found) {
    $print_buffer .= "<tr><td><b>Classes</b></td><td>";
    $print_buffer .= $class_data;
}

$print_buffer .= "</td></tr>";
if ($spell["you_cast"] != "")
    $print_buffer .= "<tr><td><b>You Cast: </b></td><td>" . $spell["you_cast"] . "</td></tr>";

if ($spell["other_casts"] != "")
    $print_buffer .= "<tr><td><b>Other Casts: </b></td><td>" . $spell["other_casts"] . "</td></tr>";

if ($spell["cast_on_you"] != "")
    $print_buffer .= "<tr><td><b>Cast on You: </b></td><td>" . $spell["cast_on_you"] . "</td></tr>";

if ($spell["cast_on_other"] != "")
    $print_buffer .= "<tr><td><b>Cast on Other: </b></td><td>" . $spell["cast_on_other"] . "</td></tr>";

if ($spell["spell_fades"] != "")
    $print_buffer .= "<tr><td><b>Fades: </b></td><td>" . $spell["spell_fades"] . "</td></tr>";

$print_buffer .= "<tr><td><b>Mana</b></td><td>" . $spell["mana"] . "</td></tr>";
if ($spell["skill"] < 52)
    $print_buffer .= "<tr><td><b>Skill</b></td><td>" . $dbskills[$spell["skill"]] . "</td></tr>";

$print_buffer .= "<tr><td><b>Cast</b></td><td>" . ($spell["cast_time"] / 1000) . " Second(s)</td></tr>";
$print_buffer .= "<tr><td><b>Recovery</b></td><td>" . ($spell["recovery_time"] / 1000) . " Second(s)</td></tr>";
$print_buffer .= "<tr><td><b>Recast</b></td><td>" . ($spell["recast_time"] / 1000) . " Second(s)</td></tr>";
if ($spell["range"] > 0)
	$print_buffer .= "<tr><td><b>Range</b></td><td>" . $spell["range"] . "</td></tr>";

$print_buffer .= "<tr><td><b>Target</b></td><td>";
if ($dbspelltargets[$spell["targettype"]] != "")
    $print_buffer .= $dbspelltargets[$spell["targettype"]];
else
    $print_buffer .= "Unknown Target (" . $spell["targettype"] . ")";

$print_buffer .= "</td></tr>";
if ($dbspellresists[$spell["resist"]] != "")
	$print_buffer .= "<tr><td><b>Resist Agjust</b></td><td>" . $dbspellresists[$spell["resist"]] . " " . $spell["ResistDiff"] . "</td></tr>";
	
if ($spell["TimeOfDay"] == 2)
    $print_buffer .= "<tr><td><b>Casting Restriction</b></td><td>Night</td></tr>";

$duration = CalcBuffDuration($minlvl, $spell["buffdurationformula"], $spell["buffduration"]);
$print_buffer .= "<tr><td><b>Duration</b></td><td>";
if ($duration == 0)
    $print_buffer .= "Instant";
else
    $print_buffer .= translate_time($duration * 6) . " ($duration Tics)";

$print_buffer .= "</td></tr>";

if ($spell["components1"] > 0 || $spell["components2"] > 0 || $spell["components3"] > 0 || $spell["components4"] > 0)
	$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Components</h2></td></tr>";

for ($i = 1; $i <= 4; $i++) {
    if ($spell["components" . $i] > 0) {
        $print_buffer .= "<tr><td><b>Component $i</b></td><td>" .
            "<a href=?a=item&id=" . $spell["components" . $i] .
            ">" . get_field_result("Name", "SELECT Name FROM $items_table WHERE id=" .
                $spell["components" . $i]) .
            " </a>(" . $spell["component_counts" . $i] . ")</td></tr>";
    }
}
$print_buffer .= "</td></tr>";

$print_buffer .= "<tr><td colspan='2'><h2 class='section_header'>Effects</h2></td></tr>";
for ($n = 1; $n <= 12; $n++) {
	$print_buffer .= SpellDescription($spell, $n);
}

$print_buffer .= "</td></tr><tr><td colspan='2'>";

$query = "
    SELECT
        $items_table.id,
        $items_table.`name`
    FROM
        $items_table
    WHERE
        $items_table.scrolleffect = $id
    ORDER BY
        $items_table.`name` ASC
";
$result = db_mysql_query($query);
if (mysqli_num_rows($result)) {
    $print_buffer .= "<h2 class='section_header'>Items with Spell</h2><ul>";
    while ($row = mysqli_fetch_array($result)) {
        $print_buffer .= "<li><a href=?a=item&id=" . $row["id"] . ">" . get_item_icon_from_id($row['id']) . ' ' . $row["name"] . "</a></li>";
    }
}
$print_buffer .= "</ul></td></tr></table>";

?>