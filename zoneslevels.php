<?php
$Title="Populated Zones By Level";
include('./includes/config.php');
include($includes_dir.'constantes.php');
include($includes_dir.'headers.php');
include($includes_dir.'mysql.php');
include($includes_dir.'functions.php');

print "<p><b>The suggested levels are approximate based upon the levels of the majority of creatures found in the zone, and, except for the newbie zones, assume that you will hunt with a group.  Most zones also have some higher and lower level monsters roaming the area, and can thus be selectively hunted at different levels.  Follow the links to get more complete descriptions for the individual zones.</b><p>";
if ($SortZoneLevelList==TRUE) {
  print "<p><b>Zones are sorted following average npc's levels. If a newbie zone contains high level friendly guards, they count in the average level and false the sort.</b><p>";
}

// Tweak the second SQL query to your needs, if for example you added a field for trigger npcs, or guards, filter it !
// In the default config, invisible men and level 1 npcs are ignored (there's enough lvl 2-5 npcs in newbie zones to have them marked at that lvl)

$levels=array();
$LevelRange = 0;
for ($i = 0; $i <= ($ServerMaxNPCLevel / 5); $i++)
{
	$LevelRange += 5;
	$levels[$i]=$LevelRange;
}
// Set $lowlimit to the minimum of NPCS signifying the zones is populated for that lvl. 
// Ex : $lowlimit=5 => if a zone has less than 5 npcs of a level, they will be ignored
$lowlimit=5;
// $MinimumNpcLvl allows you to remove low lvl npcs, such as invisible men used for triggers, set to 0 if not used
$MinimumNpcLvl=1;
$zones=array();
$query="SELECT $tbzones.*
        FROM $tbzones";
$v="WHERE";        
foreach ($IgnoreZones AS $zid) {
  $query.=" $v $tbzones.short_name!='$zid'"; $v=" AND ";
}          
$query.=" ORDER BY $tbzones.long_name ASC";
$result=mysql_query($query) or message_die('zoneslevels.php','MYSQL_QUERY',$query,mysql_error());
$cpt=0;
while ($res=mysql_fetch_array($result)) {
  $zones[$cpt]["shortname"]=$res["short_name"];
  $zones[$cpt]["longname"]=$res["long_name"];
  $zones[$cpt]["npcs"]=0;
  $zones[$cpt]["val"]=0;
  $query="SELECT $tbnpctypes.level
          FROM $tbnpctypes,$tbspawn2,$tbspawnentry
          WHERE $tbspawn2.zone='".$res["short_name"]."'
          AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
          AND $tbspawnentry.npcID=$tbnpctypes.id";
  if ($HideInvisibleMen==TRUE) { $query.=" AND $tbnpctypes.race!=127 AND $tbnpctypes.race!=240"; }          
  $query.=" AND $tbnpctypes.level>$MinimumNpcLvl
          GROUP BY $tbnpctypes.id";
  $result2=mysql_query($query) or message_die('zoneslevels.php','MYSQL_QUERY',$query,mysql_error());
  while ($row=mysql_fetch_array($result2)) {
    $lvl=floor($row["level"]/5);
    $zones[$cpt][$lvl]++;
    $zones[$cpt]["npcs"]++;
  }
  $cpt++;
}

// Edit config.php and put FALSE to that next variable if you don't want to sort the zones
if ($SortZoneLevelList==TRUE) {
  for ($i=0; $i<$cpt; $i++) {
    if ($zones[$i]["npcs"]>0) { // populated
      $zones[$i]["val"]=0; $nb=0;
      foreach ($levels AS $lkey=>$lval) {
        if ($zones[$i][$lkey]>$lowlimit) {  
          $zones[$i]["val"]+=$levels[$lkey]*$zones[$i][$lkey];
          $nb+=$zones[$i][$lkey];
        }
      }
      if($nb == 0) {
        $zones[$i]["val"]=999;
      }
      else {
        $zones[$i]["val"]=$zones[$i]["val"]/$nb;
      }
    }
  }
  // lets sort all that data
  $max=$cpt;
  do {
    $max--;
    $end=TRUE;
    for ($z=0; $z<$max; $z++) {
      if ($zones[$z]["val"]>$zones[$z+1]["val"]) {
        $end=FALSE; 
        $myzone=$zones[$z];
        $zones[$z]=$zones[$z+1];
        $zones[$z+1]=$myzone;
      }
    }
  } while ($end==FALSE);
} // end SortZoneLevelList


print "<table border=0 width=100%><tr valign=top><td width=100%>";
print "<center><table border=1>";
print "<tr class='menuh'><th>Name</th>
       <th class=tab_title>Short name</th>";
$LevelMax = 0;
for ($i = 0; $i <= ($ServerMaxNPCLevel / 5); $i++)
{
	$LevelMax += 5;
	$LevelMin = $LevelMax - 4;
	print "<th class=tab_title>" . $LevelMin . " - " . $LevelMax . "</th>";
}
print "</tr>";

$nb=0;
for ($i=0; $i<=$cpt; $i++) {
  if ($zones[$i]["npcs"]>$lowlimit) {
    $nb++;
    if (modulo($nb,10)==1) {
      print "<tr>
       <td class=tab_title>Name</td>
       <td class=tab_title>Short name</td>";
      if ($SortZoneLevelList==TRUE) { print "<td class=tab_title>Avg Lvl</td>"; }
      foreach ($levels AS $key2=>$val2) { print "<td class=tab_title>$val2</td>"; }
      print "</tr>";
    }
    print "<tr>
           <td><a href=zone.php?name=".$zones[$i]["shortname"].">".$zones[$i]["longname"]."</a></td>
           <td>".$zones[$i]["shortname"]."</td>";
    if ($SortZoneLevelList==TRUE) {
      print "<td align=center>".round($zones[$i]["val"])."</td>";           
    }
    foreach ($levels AS $lkey=>$lval) { 
      print "<td align=center>";
      if ($zones[$i][$lkey]>$lowlimit) {
        if ($ShowNPCNumberInZoneLevelList==TRUE) { 
          print $zones[$i][$lkey]; 
        } else { 
          print "x"; 
        }
      }
      print "</td>"; 
    }
  }
}
print "</table></center>";
print "</td><td width=0% nowrap>";
// right column, unused
print "</td></tr></table>";

include($includes_dir."footers.php");
?>
