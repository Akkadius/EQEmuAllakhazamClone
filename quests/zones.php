<?php
include('../includes/constantes.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/mysql.php');
$Title="Quests by zones";
include('../includes/headers.php');

print "<center><table border=0 width=0%>";
print "<form method=POST action=$PHP_SELF>";
print "<tr><td nowrap><b>Zone : </b></td><td>";
print "<select name=aZone>";
$query="SELECT short_name,long_name 
        FROM $tbzones";
$v=" WHERE ";        
foreach ($IgnoreZones AS $zid) {
  $query.=" $v $tbzones.short_name!='".addslashes($zid)."'";
  $v=" AND ";
}          
$query.=" ORDER BY long_name ASC";
$result=mysql_query($query);
while ($row=mysql_fetch_array($result)) {
  
  print "<option value=\"".$row["short_name"]."\"";
  if ($row["short_name"]==$aZone) { print " selected"; }
  print ">".$row["long_name"]."</option>";
}
print "</select></td></tr>";
print "<tr align=center><td nowrap colspan=2><input type=submit value=Search name=isearch class=form></td></tr>";
print "</form></table></center>";

print "<br><b>$aZone</b><br>";
if ($aZone!="") {
  print "<hr><table border=0 width=100%>";
  if (file_exists($quests_datas.$aZone)) {
    print "<br><b>$aZone</b><br>";
    $cpt=0;
    $handle=opendir($quests_datas.$aZone);
    while ($file=readdir($handle)) {
      if (strpos($file,".inc")>0) {
        $cpt++;
        if ($cpt==1) { print "<tr>"; }
        $npc=str_replace(".inc","",$file);
        print "<td width=25%><a href=index.php?npc=".urlencode($npc)."&zone=".urlencode($aZone).">".str_replace("_"," ",$npc)."</a></td>";
        if ($cpt==4) { print "</tr>"; $cpt=0; }
      }
    }
    closedir($handle);
    print "</table><p>";
  } else {
    print "No quests for that zone...<p>";
  }
}

include("../includes/footers.php");
?>
