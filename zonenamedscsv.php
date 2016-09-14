<?php
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');
include($includes_dir.'functions.php');
include($includes_dir.'spell.inc.php');

$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : 'name');
$mode= (isset($_GET['mode']) ? addslashes($_GET["mode"]) : 'npcs');

if ($name=="") exit;
$zone=GetRowByQuery("SELECT * FROM $tbzones WHERE short_name='$name'");

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=$name.csv"); 

print $zone["long_name"];
print "\nName,Race,Class,Level,Spawn points,Drops\n";

$npcs=preg_split("/:/",$liste);
foreach ($npcs as $id) {
  $txt=""; 
  $spawns=array();
  $loots=array();
  $query="SELECT $tbnpctypes.* FROM $tbnpctypes WHERE $tbnpctypes.id=$id";
  $mymob=GetRowByQuery($query);
  $txt=str_replace(array('_','#'),' ',$mymob["name"]).",";
  $txt.=$dbiracenames[$mymob["race"]].",";
  $txt.=$dbclasses[$mymob["class"]].",";
  $txt.=$mymob["level"].",";
    
  $query="SELECT $tbspawn2.x,$tbspawn2.y,$tbspawn2.z,
             $tbspawngroup.name as spawngroup,
             $tbspawngroup.id as spawngroupID,
             $tbspawn2.respawntime
          FROM $tbspawnentry,$tbspawn2,$tbspawngroup
          WHERE $tbspawnentry.npcID=$id
            AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
            AND $tbspawn2.zone='$name'
            AND $tbspawnentry.spawngroupID=$tbspawngroup.id";
  $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
  if (mysql_num_rows($result)>0) {
    $cpt=0;
    while ($row=mysql_fetch_array($result)) {
      $spawns[$cpt]=floor($row["y"])." / ".floor($row["x"])." / ".floor($row["z"]);
      $spawns[$cpt].=" (".translate_time($row["respawntime"]).")";
      $cpt++;
    }
  }
    
  if (($mymob["loottable_id"]>0) AND ((!in_array($mymob["class"],$dbmerchants)) OR ($MerchantsDontDropStuff==FALSE))) {
    $query="SELECT $tbitems.id,$tbitems.Name,$tbitems.itemtype,
                   $tblootdropentries.chance,$tbloottableentries.probability,
                   $tbloottableentries.lootdrop_id,$tbloottableentries.multiplier
            FROM $tbitems,$tbloottableentries,$tblootdropentries
            WHERE $tbloottableentries.loottable_id=".$mymob["loottable_id"]."
              AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
              AND $tblootdropentries.item_id=$tbitems.id";
    $result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
    if (mysql_num_rows($result)>0) {
      $cpt=0;
      while ($row=mysql_fetch_array($result)) {
        $loots[$cpt]=$row["Name"];
        $loots[$cpt].=" (".$dbitypes[$row["itemtype"]].")";
        $cpt++;
      }
    }
  }
  $n=max(count($spawns),count($loots));
  for ($i=0; $i<$n; $i++) {
    if ($i==0) { print $txt; } else { print ",,,,"; }
    if ($i<count($spawns)) { print $spawns[$i]; } 
    print ","; 
    if ($i<count($loots)) { print $loots[$i]; }
    print "\n";
  }
}

?>