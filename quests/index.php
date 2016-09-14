<?php
include('../includes/constantes.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/mysql.php');

if (!isset($npc)) { print "<script>document.location=\"../index.php\";</script>"; }
if (!isset($zone)) { print "<script>document.location=\"../index.php\";</script>"; }
if (!isset($npcid)) { $npcid=0; }
if (!isset($iclass)) { $iclass="c"; }
if (!isset($ilevel)) { $ilevel="l"; }
if (!isset($ifaction)) { $ifaction="5"; }
if (!isset($irace)) { $irace='\&lt\;your\&nbsp\;race\&gt\;'; }

$Title="Quest(s) for :: ".str_replace('_',' ',$npc);
if ($zone!="") { $myzone=GetFieldByQuery("long_name","SELECT long_name FROM zone WHERE short_name like '".addslashes($zone)."'"); }
$Title.="<br><a href=zones.php?aZone=$zone>$myzone</a>";
include('../includes/headers.php');

if ($AlwaysBuildQuest==TRUE) {
  print "<center><table border=0 width=0%>";
  print "<form method=POST action=$PHP_SELF>";
  print "<input type=hidden value=\"$zone\" name=zone>";
  print "<input type=hidden value=\"$npc\" name=npc>";
  print "<tr><td nowrap><b>Run the quest as a </b></td>";
  print "<td><select name=iclass class=form>";
  print "<option value=\"c\"".($c==""?" selected":"").">-</option>\n";
  foreach ($dbclasses_names AS $c) {
    print "<option value=$c".($c==$iclass?" selected":"").">$c</option>\n";
  }
  print "</select></td><td>";
  print SelectLevel("ilevel",70,$ilevel);
  print "</td><td><select name=ifaction class=form>";
  foreach ($dbfactions AS $k=>$c) {
    print "<option value=$k ".($k==$ifaction?" selected":"").">$c</option>";
  }
  print "</select></td><td>";
  print "</td><td nowrap colspan=2><input type=submit value=\"Run again\" class=form></td></tr>";
  print "</form></table></center>";
}

$file="$quests_datas".urlencode($zone)."/".urlencode($npc).".inc";

if (($AlwaysBuildQuest==TRUE) or (!file_exists($file))) {
  system("/usr/bin/perl parse_quest.pl '$zone' '$npc.pl' '$npcid' '$iclass' '$irace' '$ilevel' '$ifaction'");
}

require($file);

include("../includes/footers.php");
?>