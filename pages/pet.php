<?php

$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

$Title = "Pet :: $name";

$query = "
    SELECT
        $npc_types_table.*
    FROM
        $npc_types_table
    WHERE
        $npc_types_table.`name` = '$name'
    LIMIT 1
";
$result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
$npc = mysql_fetch_array($result);

print "<table class='container_div'><tr valign=top><td>\n";
if (file_exists($npcs_dir . $id . ".jpg")) {
    print "<img src=" . $npcs_dir . $id . ".jpg>";
}
print "<p><table >";
print "<tr><td style='text-align:right'><b>Full name : </b></td><td>" . str_replace("_", " ", $npc["name"]);
if ($npc["lastname"] != "") {
    print str_replace("_", " ", " (" . $npc["lastname"] . ")");
}
print "</td></tr>";
print "<tr><td style='text-align:right'  ><b>Level : </b></td><td width=100%>" . $npc["level"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Race : </b></td><td>" . $dbiracenames[$npc["race"]] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Class : </b></td><td>" . $dbclasses[$npc["class"]] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>HP : </b></td><td>" . $npc["hp"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Damage : </b></td><td>" . $npc["mindmg"] . " to " . $npc["maxdmg"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>HP Regen : </b></td><td>" . $npc["hp_regen_rate"] . " Per Tick</td></tr>";
print "<tr><td style='text-align:right' ><b>Mana Regen : </b></td><td>" . $npc["mana_regen_rate"] . " Per Tick</td></tr>";

print "<tr><td style='text-align:right' ><b>Strength : </b></td><td>" . $npc["STR"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Stamina : </b></td><td>" . $npc["STA"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Dexterity : </b></td><td>" . $npc["DEX"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Agility : </b></td><td>" . $npc["AGI"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Intelligence : </b></td><td>" . $npc["_INT"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Wisdom : </b></td><td>" . $npc["WIS"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Charisma : </b></td><td>" . $npc["CHA"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Magic Resist : </b></td><td>" . $npc["MR"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Fire Resist : </b></td><td>" . $npc["FR"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Cold Resist : </b></td><td>" . $npc["CR"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Disease Resist : </b></td><td>" . $npc["DR"] . "</td></tr>";
print "<tr><td style='text-align:right' ><b>Poison Resist : </b></td><td>" . $npc["PR"] . "</td></tr>";


if ($npc["npcspecialattks"] != '') {
    print "<tr><td><b>Special attacks : </b></td><td>" . SpecialAttacks($npc["npcspecialattks"]) . "</td></tr>";
}

print "</tr></table>";

print "</td><td width=0% nowrap>"; // right column

print "<tr class=myline height=6><td colspan=2></td><tr>\n";

print "<tr valign=top>";

if ($npc["npc_spells_id"] > 0) {
    $query = "SELECT * FROM $npc_spells_table WHERE id=" . $npc["npc_spells_id"];
    $result = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
    if (mysql_num_rows($result) > 0) {
        $g = mysql_fetch_array($result);
        print "<td><table border=0><tr><td colspan=2 nowrap><b>This pet casts the following spells : </b><p>";
        $query = "
            SELECT
                $npc_spells_entries_table.spellid
            FROM
                $npc_spells_entries_table
            WHERE
                $npc_spells_entries_table.npc_spells_id = " . $npc["npc_spells_id"] . "
            AND $npc_spells_entries_table.minlevel <= " . $npc["level"] . "
            AND $npc_spells_entries_table.maxlevel >= " . $npc["level"] . "
            ORDER BY
                $npc_spells_entries_table.priority DESC
        ";
        $result2 = db_mysql_query($query) or message_die('npc.php', 'MYSQL_QUERY', $query, mysql_error());
        if (mysql_num_rows($result2) > 0) {
            print "</ul><li><b>Listname : </b>" . $g["name"];
            if ($g["attack_proc"] == 1) {
                print " (Procs)";
            }
            print "<ul>";
            while ($row = mysql_fetch_array($result2)) {
                $spell = getspell($row["spellid"]);
                print "<li><a href=?a=spell&id=" . $row["spellid"] . ">" . $spell["name"] . "</a>";
            }
        }
        print "</td></tr></table></td>";
    }
}

print "</td></tr></table><p>\n";


?>