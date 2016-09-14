<?php
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');
include($includes_dir.'functions.php');

$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

$Title="PET :: $name";
include($includes_dir.'headers.php');


if (!isset($name)) { print "<script>document.location=\"index.php\";</script>"; }


$query="SELECT $tbnpctypes.*
        FROM $tbnpctypes
        WHERE $tbnpctypes.name = '$name' LIMIT 1";
$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
$npc=mysql_fetch_array($result);

print "<table border=0 width=0%><tr valign=top><td width=100%>\n";
if (file_exists($npcs_dir.$id.".jpg")) {
  print "<center><img src=".$npcs_dir.$id.".jpg></center>";
}
print "<p><table border=0 width=100%>";
print "<tr><td nowrap><b>Full name : </b></td><td>".str_replace("_"," ",$npc["name"]);
if ($npc["lastname"]!="") { print str_replace("_"," "," (".$npc["lastname"].")"); }
print "</td></tr>";
print "<tr><td nowrap><b>Level : </b></td><td width=100%>".$npc["level"]."</td></tr>";
print "<tr><td nowrap><b>Race : </b></td><td>".$dbiracenames[$npc["race"]]."</td></tr>";
print "<tr><td nowrap><b>Class : </b></td><td>".$dbclasses[$npc["class"]]."</td></tr>";
print "<tr><td nowrap><b>HP : </b></td><td>".$npc["hp"]."</td></tr>";
print "<tr><td nowrap><b>Damage : </b></td><td>".$npc["mindmg"]." to ".$npc["maxdmg"]."</td></tr>";
print "<tr><td nowrap><b>HP Regen : </b></td><td>".$npc["hp_regen_rate"]." Per Tick</td></tr>";
print "<tr><td nowrap><b>Mana Regen : </b></td><td>".$npc["mana_regen_rate"]." Per Tick</td></tr>";

print "<tr><td nowrap><b>Strength : </b></td><td>".$npc["STR"]."</td></tr>";
print "<tr><td nowrap><b>Stamina : </b></td><td>".$npc["STA"]."</td></tr>";
print "<tr><td nowrap><b>Dexterity : </b></td><td>".$npc["DEX"]."</td></tr>";
print "<tr><td nowrap><b>Agility : </b></td><td>".$npc["AGI"]."</td></tr>";
print "<tr><td nowrap><b>Intelligence : </b></td><td>".$npc["_INT"]."</td></tr>";
print "<tr><td nowrap><b>Wisdom : </b></td><td>".$npc["WIS"]."</td></tr>";
print "<tr><td nowrap><b>Charisma : </b></td><td>".$npc["CHA"]."</td></tr>";
print "<tr><td nowrap><b>Magic Resist : </b></td><td>".$npc["MR"]."</td></tr>";
print "<tr><td nowrap><b>Fire Resist : </b></td><td>".$npc["FR"]."</td></tr>";
print "<tr><td nowrap><b>Cold Resist : </b></td><td>".$npc["CR"]."</td></tr>";
print "<tr><td nowrap><b>Disease Resist : </b></td><td>".$npc["DR"]."</td></tr>";
print "<tr><td nowrap><b>Poison Resist : </b></td><td>".$npc["PR"]."</td></tr>";


if ($npc["npcspecialattks"]!='')
{
	print "<tr><td nowrap><b>Special attacks : </b></td><td>".SpecialAttacks($npc["npcspecialattks"])."</td></tr>";
}

print "</tr></table>";

print "</td><td width=0% nowrap>"; // right column

print "<tr class=myline height=6><td colspan=2></td><tr>\n";

print "<tr valign=top>";

if ($npc["npc_spells_id"]>0) {
  $query="SELECT * FROM $tbnpcspells WHERE id=".$npc["npc_spells_id"];
  $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
  if (mysql_num_rows($result)>0) {
    $g=mysql_fetch_array($result);
    print "<td><table border=0><tr><td colspan=2 nowrap><b>This pet casts the following spells : </b><p>";
    $query="SELECT $tbnpcspellsentries.spellid
            FROM $tbnpcspellsentries
            WHERE $tbnpcspellsentries.npc_spells_id=".$npc["npc_spells_id"]."
              AND $tbnpcspellsentries.minlevel<=".$npc["level"]."
              AND $tbnpcspellsentries.maxlevel>=".$npc["level"]."
            ORDER BY $tbnpcspellsentries.priority DESC
            ";
    $result2=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
    if (mysql_num_rows($result2)>0) {
      print "</ul><li><b>Listname : </b>".$g["name"];
      if ($g["attack_proc"]==1) { print " (Procs)"; }
      print "<ul>";
      while ($row=mysql_fetch_array($result2)) {
        $spell=getspell($row["spellid"]);
        print "<li><a href=spell.php?id=".$row["spellid"].">".$spell["name"]."</a>";
      }
    }
    print "</td></tr></table></td>";
  }
}



print "</td></tr></table><p>\n";

include($includes_dir."footers.php");
?>