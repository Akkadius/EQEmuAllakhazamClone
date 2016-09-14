<?php
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');

$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : 'name');
$mode= (isset($_GET['mode']) ? addslashes($_GET["mode"]) : 'npcs');

if ($DisplayNamedNPCsInfo==FALSE)
{
	header("Location: index.php");
	exit();
}

$Title=GetFieldByQuery("long_name","SELECT long_name FROM $tbzones WHERE short_name='$name'")." ($name)";
include($includes_dir.'headers.php');
include($includes_dir.'functions.php');

if (!isset($name)) { print "<script>document.location=\"zones.php\";</script>"; }

$ZoneDebug=FALSE; // this is new in 0.5.3 but undocumented, it is for world builders

if ($ZoneDebug==TRUE) { print "<p>ZoneDebug at TRUE ! Edit source code and set it to false.<p>"; }

print "<table border=0 width=100%><tr valign=top><td width=100%>";

$query="SELECT $tbzones.*
        FROM $tbzones
        WHERE $tbzones.short_name='$name'";
$result=mysql_query($query) or message_die('zones.php','MYSQL_QUERY',$query,mysql_error());
$zone=mysql_fetch_array($result);
print "<center><table border=0 width=0%><tr valign=top><td>";
print "<p><b>Succor point : </b>".floor($zone["safe_x"])." / ".floor($zone["safe_y"])." / ".floor($zone["safe_z"]);
if ($zone["minium_level"]>0) { print "<br><b>Minimum level : </b>".floor($zone["minium_level"]); }
print "</td>";
if (file_exists($maps_dir.$name.".jpg")) {
  if (!file_exists($maps_url.$name."._tn.jpg")) { make_thumb($maps_dir.$name.".jpg"); }
  print "<td>&nbsp;&nbsp;&nbsp;</td><td align=center><a href=".$maps_url.$name.".jpg><img src=".$maps_url.$name."._tn.jpg width=120 height=80 border=0></a><br>
         <a href=".$maps_url.$name.".jpg target=_new>Popup map</a>
         </td>";
}
print "</tr></table>";

function isChecked($id) {
  for ($i=0; $i<count($_POST["npc"]); $i++) {
    if($id==$_POST["npc"][$i]) return true;  
  }
}

if (isset($submitDetailCSV)) {
  $submitDetail=true;
  $liste=""; $sep="";
  foreach ($_POST["npc"] as $id) {
    $liste=$liste.$sep.$id; $sep=":";
  }
  print "<iframe src=zonenamedscsv.php?name=$name&liste=$liste
                width=0
                border=0 frameborder=0  
                height=0>
         </iframe>";
}

if (isset($submitDetailMaps)) {
  $submitDetail=true;
  print "<p><b>Map file's entries</b><p>";
  print "<table border=0><tr><td bgcolor=white>";
  $v="";
  for ($i=0; $i<count($_POST["npc"]); $i++) {
    $query="SELECT $tbnpctypes.*
            FROM $tbnpctypes
            WHERE $tbnpctypes.id=".$_POST["npc"][$i];
    $mymob=GetRowByQuery($query);

    $query="SELECT $tbspawn2.x,$tbspawn2.y,$tbspawn2.z,
               $tbspawngroup.name as spawngroup,
               $tbspawngroup.id as spawngroupID,
               $tbspawn2.respawntime
               FROM $tbspawnentry,$tbspawn2,$tbspawngroup
               WHERE $tbspawnentry.npcID=".$_POST["npc"][$i]."
                 AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
                 AND $tbspawn2.zone='$name'
                 AND $tbspawnentry.spawngroupID=$tbspawngroup.id";
    $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
    if (mysql_num_rows($result)>0) {
      while ($row=mysql_fetch_array($result)) {
       //    P 195.0000, 210.0000, 94.8135,  0, 0, 0,  3,  Gruppip_(Wizard_Spells)
        print $v."P ".round($row["x"],2).", ".round($row["y"],2).", ".round($row["z"],2).",0,0,0,3,".str_replace(" ","_",$mymob["name"]);
        $v="<br>\n"; 
      }
    }
  }   
  print "</td></tr></table><p>";
}

if (isset($submitDetail)) {
  print "<p><b>Detailled List</b>";
  print "<p><table border=1><tr>";
  if ($ZoneDebug==TRUE) { print "<td class=tab_title>Id</td>"; }
  print "<td class=tab_title>Name</a></td>";
  print "<td class=tab_title>Race</a></td>";
  print "<td class=tab_title>Class</a></td>";
  print "<td class=tab_title>Level</a></td>";
  print "<td class=tab_title>Spawn points</td>";
  print "<td class=tab_title>Drops</td>";
  print "</tr>";
  for ($i=0; $i<count($_POST["npc"]); $i++) {
    print "<tr valign=top>";
    $query="SELECT * FROM $tbnpctypes WHERE $tbnpctypes.id=".$_POST["npc"][$i];
    $mymob=GetRowByQuery($query);
    if ($ZoneDebug==TRUE) { print "<td align=center>".$_POST["npc"][$i]."</td>"; }
    print "<td nowrap><a href=npc.php?id=".$mymob["id"].">".str_replace(array('_','#'),' ',$mymob["name"])."</a></td>";
    print "<td nowrap>".$dbiracenames[$mymob["race"]]."</td>";
    print "<td nowrap>".$dbclasses[$mymob["class"]]."</td>";
    print "<td nowrap align=center>".$mymob["level"]."</td>";
    
    
    $query="SELECT $tbspawn2.x,$tbspawn2.y,$tbspawn2.z,
               $tbspawngroup.name as spawngroup,
               $tbspawngroup.id as spawngroupID,
               $tbspawn2.respawntime
               FROM $tbspawnentry,$tbspawn2,$tbspawngroup
               WHERE $tbspawnentry.npcID=".$_POST["npc"][$i]."
                 AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
                 AND $tbspawn2.zone='$name'
                 AND $tbspawnentry.spawngroupID=$tbspawngroup.id";
    $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
    if (mysql_num_rows($result)>0) {
      print "<td nowrap>"; 
      $sep="";
      while ($row=mysql_fetch_array($result)) {
        print "$sep".floor($row["y"])." / ".floor($row["x"])." / ".floor($row["z"]);
        print ", ".translate_time($row["respawntime"]);
        $sep="<br>";
      }
      print "</td>";
    }
    
    
    if (($mymob["loottable_id"]>0) AND ((!in_array($mymob["class"],$dbmerchants)) OR ($MerchantsDontDropStuff==FALSE))) {
      $query="SELECT $tbitems.id,$tbitems.Name,$tbitems.itemtype,
                     $tblootdropentries.chance,$tbloottableentries.probability,
                     $tbloottableentries.lootdrop_id,$tbloottableentries.multiplier
              FROM $tbitems,$tbloottableentries,$tblootdropentries
              WHERE $tbloottableentries.loottable_id=".$mymob["loottable_id"]."
                AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
              AND $tblootdropentries.item_id=$tbitems.id
             ";
      $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
      if (mysql_num_rows($result)>0) {
        print "<td nowrap>";
        $sep="";
        while ($row=mysql_fetch_array($result)) {
          print "$sep<a href=item.php?id=".$row["id"].">".$row["Name"]."</a>";
          print ", ".$dbitypes[$row["itemtype"]];
          $sep="<br>";
        }
        print "</td>";
      } else { print "<td align=center>-</td>"; }
    } else { print "<td align=center>-</td>"; }
    print "</tr>";
  }
  print "</table><p>";
}


if ($mode=="npcs") {
  ////////////// NPCS
  $query="SELECT $tbnpctypes.id,$tbnpctypes.class,$tbnpctypes.level,$tbnpctypes.race,$tbnpctypes.name,$tbnpctypes.loottable_id
          FROM $tbnpctypes,$tbspawn2,$tbspawnentry,$tbspawngroup
          WHERE $tbspawn2.zone='$name'
          AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
          AND $tbspawnentry.npcID=$tbnpctypes.id
          AND $tbspawngroup.id=$tbspawnentry.spawngroupID";
  if ($HideInvisibleMen==TRUE) { $query.=" AND $tbnpctypes.race!=127 AND $tbnpctypes.race!=240"; }          
  if ($GroupNpcsByName==TRUE) { $query.=" GROUP BY $tbnpctypes.name"; }
  else { $query.=" GROUP BY $tbnpctypes.id"; }
  $query.=" ORDER BY $order";
  $result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
  print "<p><b>Bestiary</b><p><table border=1><tr>";
  print "<form method=POST action=$PHP_SELF>";
  print "<input type=submit name=submitDetail value=\"Detailled List\" class=form>";
  print "&nbsp;<input type=submit name=submitDetailCSV value=\"Detailled List CSV\" class=form>";
  print "&nbsp;<input type=submit name=submitDetailMaps value=\"Export map entries\" class=form>";
  print "<input type=hidden name=name value=$name>";
  if ($ZoneDebug==TRUE) { print "<td class=tab_title><a href=$PHP_SELF?name=$name&order=id>Id</a></td>"; }
  print "<td class=tab_title>List</a></td><td class=tab_title><a href=$PHP_SELF?name=$name&order=name>Name</a></td>";
  if ($ZoneDebug==TRUE) { print "<td class=tab_title><a href=$PHP_SELF?name=$name&order=loottable_id>Loottable</a></td>"; }
  print "
         <td class=tab_title><a href=$PHP_SELF?name=$name&order=class>Class</a></td>
         <td class=tab_title><a href=$PHP_SELF?name=$name&order=race>Race</a></td>
         <td class=tab_title><a href=$PHP_SELF?name=$name&order=level>Level</a></td>
         ";
  while ($row=mysql_fetch_array($result)) {
    print "<tr>";
    if ($ZoneDebug==TRUE) { print "<td>".$row["id"]."</td>"; }
    print "<td align=center><input type=checkbox name=npc[] value=".$row["id"].(isChecked($row["id"])?" checked":"")." class=form></td>";
    print "<td><a href=npc.php?id=".$row["id"].">".str_replace(array('_','#'),' ',$row["name"])."</a>";
    if ($ZoneDebug==TRUE) { print "</td><td>".$row["loottable_id"]; }           
    print "</td>
           <td align=center>".$dbclasses[$row["class"]]."</td>
           <td align=center>".$dbiracenames[$row["race"]]."</td>
           <td align=center>".$row["level"]."</td>
           </tr>";
  }
  print "</form>";
  print "</table><p></center>";
} // end npcs



print "</td><td width=0% nowrap>"; // end first column
print "<p class=page_small_title>Ressources</p>";
print "<li><a href=zone.php?name=$name&mode=npcs>".$zone["long_name"]." Bestiary List</a>";
print "<li><a href=zonenameds.php?name=$name&mode=npcs>".$zone["long_name"]." Named Mobs List</a>";
print "<li><a href=zone.php?name=$name&mode=items>".$zone["long_name"]." Equipment List </a>";
if (file_exists($maps_dir.$name.".jpg")) {
  print "<li><a href=".$maps_url.$name.".jpg>".$zone["long_name"]." Map</a>";
}
print "<li><a href=zone.php?name=$name&mode=spawngroups>".$zone["long_name"]." Spawn Groups</a>";
print "<li><a href=zone.php?name=$name&mode=forage>".$zone["long_name"]." Forageable items</a>";
if ($AllowQuestsNPC==TRUE) {
  print "<li><a href=$root_url"."quests/zones.php?aZone=$name>".$zone["long_name"]." Quest NPCs</a>";
}
print "</td></tr></table>";

include($includes_dir."footers.php");
?>