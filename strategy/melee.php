<?php
include('../includes/constantes.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/mysql.php');
$Title="Melee damage calculator";
include('../includes/headers.php');

$istr = (isset($_GET['istr']) ? $_GET['istr'] : 75);
$idex = (isset($_GET['idex']) ? $_GET['idex'] : 75);
$iatk = (isset($_GET['iatk']) ? $_GET['iatk'] : 30);
$ilevel = (isset($_GET['ilevel']) ? $_GET['ilevel'] : 1);
$imoblevel = (isset($_GET['imoblevel']) ? $_GET['imoblevel'] : 1);
$ih1dam = (isset($_GET['ih1dam']) ? $_GET['ih1dam'] : 6);
$ih1del = (isset($_GET['ih1del']) ? $_GET['ih1del'] : 23);
$ih2dam = (isset($_GET['ih2dam']) ? $_GET['ih2dam'] : 6);
$ih2del = (isset($_GET['ih2del']) ? $_GET['ih2del'] : 23);
$i2hdam = (isset($_GET['i2hdam']) ? $_GET['i2hdam'] : 9);
$i2hdel = (isset($_GET['i2hdel']) ? $_GET['i2hdel'] : 27);
$i1skill = (isset($_GET['i1skill']) ? $_GET['i1skill'] : 5);
$i2skill = (isset($_GET['i2skill']) ? $_GET['i2skill'] : 5);
$i2hskill = (isset($_GET['i2hskill']) ? $_GET['i2hskill'] : 5);
$ih1proc = (isset($_GET['ih1proc']) ? $_GET['ih1proc'] : 0);
$ih2proc = (isset($_GET['ih2proc']) ? $_GET['ih2proc'] : 0);
$i2hproc = (isset($_GET['i2hproc']) ? $_GET['i2hproc'] : 0);
$icalculate = (isset($_GET['icalculate']) ? $_GET['icalculate'] : 0);
$iclass = (isset($_GET['iclass']) ? $_GET['iclass'] : 0);

if (!isset($istr)) { $istr=75; }
if (!isset($idex)) { $idex=75; }
if (!isset($iatk)) { $iatk=30; }
if (!isset($ilevel)) { $ilevel=1; }
if (!isset($imoblevel)) { $imoblevel=1; }
if (!isset($ih1dam)) { $ih1dam=6; }
if (!isset($ih1del)) { $ih1del=23; }
if (!isset($ih2dam)) { $ih2dam=6; }
if (!isset($ih2del)) { $ih2del=23; }
if (!isset($i2hdam)) { $i2hdam=9; }
if (!isset($i2hdel)) { $i2hdel=27; }
if (!isset($i1skill)) { $i1skill=5; }
if (!isset($i2skill)) { $i2skill=5; }
if (!isset($i2hskill)) { $i2hskill=5; }
if (!isset($ih1proc)) { $ih1proc=0; }
if (!isset($ih2proc)) { $ih2proc=0; }
if (!isset($i2hproc)) { $i2hproc=0; }

print "<p><center><b>Results given by the calculator are based on EQEmu's source code and may not be in line with live results.</center></b>";
print "<br><center>Proc damages are applied to the formula with full damages (ie, no resists from the mob). If you want to add resists, decrease the amount of damage made by the proc.</center>";

print "<p><center><table border=0 width=0%>";
print "<form method=GET action=$PHP_SELF>";
print "<tr><td nowrap><b>Class : </b></td><td>";
print SelectIClass("iclass",$iclass);
print " <b>Level : </b>";
print SelectLevel("ilevel",80,$ilevel);
print "<td></tr>";

//print "<tr><td nowrap><b>Mob's level : </b></td><td>";
//print SelectLevel("imoblevel",80,$imoblevel);
//print "<td></tr>";

print "<tr><td nowrap><b>STR : </b></td><td><input type=text value=\"$istr\" size=4 name=istr class=form></td></tr>";
print "<tr><td nowrap><b>DEX : </b></td><td><input type=text value=\"$idex\" size=4 name=idex class=form></td></tr>";
print "<tr><td nowrap><b>Weapon affinity (AA) : </b></td><td>";
print SelectLevel("iwa",5,$iwa);
print "<td></tr>";
print "<tr><td nowrap><b>Combat fury (AA) : </b></td><td>";
print SelectLevel("icf",3,$icf);
print "<td></tr>";
print "<tr><td nowrap><b>Ambidexterity (AA) : </b></td><td>";
print SelectLevel("iad",1,$iad);
print "<td></tr>";


//print "<tr><td nowrap><b>Attack : </b></td><td><input type=text value=\"$iatk\" size=4 name=iatk class=form></td></tr>";
print "<tr><td nowrap><b>Skill (primary weapon) : </b></td><td><input type=text value=\"$i1skill\" size=4 name=i1skill class=form></td></tr>";
print "<tr><td nowrap><b>Skill (secondary weapon) : </b></td><td><input type=text value=\"$i2skill\" size=4 name=i2skill class=form></td></tr>";
print "<tr><td nowrap><b>Skill (2H weapon) : </b></td><td><input type=text value=\"$i2hskill\" size=4 name=i2hskill class=form></td></tr>";

print "<tr><td nowrap><b>Primary weapon, damage : </b></td><td><input type=text value=\"$ih1dam\" size=4 name=ih1dam class=form>";
print " <b>Delay : </b><input type=text value=\"$ih1del\" size=4 name=ih1del class=form>";
print " <b>Proc damage : </b><input type=text value=\"$ih1proc\" size=4 name=ih1proc class=form></td></tr>";

print "<tr><td nowrap><b>Secondary weapon, damage : </b></td><td><input type=text value=\"$ih2dam\" size=4 name=ih2dam class=form>";
print " <b>Delay : </b><input type=text value=\"$ih2del\" size=4 name=ih2del class=form>";
print " <b>Proc damage : </b><input type=text value=\"$ih2proc\" size=4 name=ih2proc class=form></td></tr>";

print "<tr><td nowrap><b>2H weapon, damage : </b></td><td><input type=text value=\"$i2hdam\" size=4 name=i2hdam class=form>";
print " <b>Delay : </b><input type=text value=\"$i2hdel\" size=4 name=i2hdel class=form>";
print " <b>Proc damage : </b><input type=text value=\"$i2hproc\" size=4 name=i2hproc class=form></td></tr>";

print "<tr align=center><td nowrap colspan=2><input type=submit value=Calculate name=icalculate class=form>
       </td></tr>";
print "</form></table></center>";

if (isset($icalculate)) {
  $do=true;
  if (($do==true) and ($iclass==0)) { print "No class selected..."; $do=false; }
  if (($do==true) and (($istr<=0) or (!isinteger($istr)))) { print "Invalid strength (STR)..."; $do=false; }
  if (($do==true) and (($idex<=0) or (!isinteger($idex)))) { print "Invalid dexterity (DEX)..."; $do=false; }
  //if (($do==true) and (($iatk<=0) or (!isinteger($iatk)))) { print "Invalid attack..."; $do=false; }
  if (($do==true) and (($ih1dam<=0) or (!isinteger($ih1dam)))) { print "Invalid main hand damages..."; $do=false; }
  if (($do==true) and (($ih1del<=0) or (!isinteger($ih1del)))) { print "Invalid main hand delay..."; $do=false; }
  if (($do==true) and (($ih2dam<=0) or (!isinteger($ih2dam)))) { print "Invalid off hand damages..."; $do=false; }
  if (($do==true) and (($ih2del<=0) or (!isinteger($ih2del)))) { print "Invalid off hand delay..."; $do=false; }
  if (($do==true) and (($i2hdam<=0) or (!isinteger($i2hdam)))) { print "Invalid 2H damages..."; $do=false; }
  if (($do==true) and (($i2hdel<=0) or (!isinteger($i2hdel)))) { print "Invalid 2H delay..."; $do=false; }
  if (($do==true) and (($ilevel<=0) or (!isinteger($ilevel)))) { print "Invalid level..."; $do=false; }
  if (($do==true) and (($i1skill<=0) or (!isinteger($i1skill)))) { print "Invalid primary weapon's skill..."; $do=false; }
  if (($do==true) and (($i2skill<=0) or (!isinteger($i2skill)))) { print "Invalid secondary weapon's skill..."; $do=false; }
  if (($do==true) and (($i2hskill<=0) or (!isinteger($i2hskill)))) { print "Invalid 2H skill..."; $do=false; }
  if (($do==true) and (($ih1proc<0) or (!isinteger($ih1proc)))) { print "Invalid primary weapon proc..."; $do=false; }
  if (($do==true) and (($ih2proc<0) or (!isinteger($ih2proc)))) { print "Invalid secondary weapon proc..."; $do=false; }
  if (($do==true) and (($i2hproc<0) or (!isinteger($i2hproc)))) { print "Invalid 2H weapon proc..."; $do=false; }
  //if (($do==true) and (($imoblevel<=0) or (!isinteger($imoblevel)))) { print "Invalid mob level..."; $do=false; }
  
  if ($do==false) {
    include("../includes/footers.php");
    exit;
  }

  // weapon real damages
  $w1dam=$ih1dam;
  $w2dam=$ih2dam;
  $w2hdam=$i2hdam;
  if ($iclass==32768) { // berserker
    $bonus=3+$ilevel/10;
    $w1dam=$w1dam*(100+$bonus)/100;
    $w2dam=$w2dam*(100+$bonus)/100;
    $i2hdam=$w2hdam*(100+$bonus)/100;
  }  

  // max hits
  $maxhit1=floor($w1dam*($istr*2+$i1skill*1.5+$ilevel)/100);
  $maxhit2=floor($w2dam*($istr*2+$i2skill*1.5+$ilevel)/100);
  $maxhit2h=floor($w2hdam*($istr*2+$i2hskill*1.5+$ilevel)/100);
  
  // damage bonus for main hand  
  $bon2h=0; $bon1h=0;
  if ($ilevel>=25) switch ($iclass) {
    case 1:
    case 2:
    case 4:
    case 8:
    case 16:
    case 32:
    case 64:
    case 128:
    case 256:
    case 512:
    case 16384:
    case 32768:
      $bon1h=floor(($ilevel-25)/3+1);
      $bon2h=0;
      if ($ih1del<=27) { $bon2h=floor(1+$bon1h); }
      elseif (($ih1del>27) AND ($ih1del<=39)) { $bon2h=floor($bon1h+($ilevel-27)/4); }
      elseif (($ih1del>39) AND ($ih1del<=42)) { $bon2h=floor($bon1h+1+($ilevel-27)/4); }
      else { $bon2h=floor($bon1h+1+($ilevel-27)/4+($i1hdel-34)/3); }
      break; 
  }
  

  // chance to crit
  $critchance=0; $critchanceber=0;
  if (($iclass==1) and ($ilevel>=12)) { $critchance=3; } // warrior
  switch ($icf) {
    case 1: $critchance+=2; break;
    case 2: $critchance+=4; break;
    case 3: $critchance+=7; break;
  } 
  if ($iclass==1) { $critchanceber=$critchance+6; }

  // chance to proc
  $procbonus=5*$iwa;
  $prochance=round(($idex/3020)*(100+$procbonus),2); // this should be the right formula, but EMU devs still use a wrong one.
  //$prochance=round(100*$idex/3020,2)+$procbonus; // old formula
  
  // DW chance
  $dwskill=min(252,($ilevel*7)+5);
  $dwchance=0;
  switch ($iclass) {
    case 1: // war
      if ($ilevel>12) { $dwchance=($dwskill+$ilevel)/400; }
      break;
    case 8:
    case 128:
    case 256:
    case 16384:
      if ($ilevel>16) { $dwchance=($dwskill+$ilevel)/400; }
      break; 
    case 64: // monk
      $dwchance=($dwskill+$ilevel)/400;
      break;
  }
  if (($dwchance>0) and ($iad==1)) { $dwchance+=0.1; }

  // We add the procs
  if ($ih1proc>0) { $w1dam+=$prochance*$ih1proc/100; }
  if ($ih2proc>0) { $w2dam+=$prochance*$ih2proc/100; }
  if ($i2hproc>0) { $w2hdam+=$prochance*$i2hproc/100; }
  
  
  // modals
  $modal1prim=$w1dam*2+$bon1h;
  $compared1modal=round($modal1prim/$ih1del,3);
  $modal1sec=$w2dam*2;
  $compared2modal=round($modal1sec/$ih2del*$dwchance,3);
  
  $modal2=$w2hdam*2+$bon2h;
  $compared2Hmodal=round($modal2/$i2hdel,3);
  
  // We make a table to print all this
  print "<center><table border=0 width=80%>";

  print "<tr align=center><td class=menu_title colspan=2>General informations</td></tr>";
  print "<tr><td><b>Chance to dual weild : </b></td><td align=center>".round($dwchance*100,2)."%</td></tr>";
  print "<tr><td><b>Chance to land a critical hit : </b></td><td align=center>$critchance%";
  if ($critchanceber>0) { print " ($critchanceber% when berserk)"; }
  print "</td></tr>";
  print "<tr><td><b>Chance to proc : </b></td><td align=center>$prochance%</td></tr>";
  
  print "<tr align=center><td nowrap class=menu_title><b>Using one handed weapons</b></td>
                          <td nowrap class=menu_title><b>Using 2 handed weapon</b></td></tr>";
  print "<tr valign=top><td nowrap><b>Minimum hit : </b>".($bon1h+1); 
  if ($dwchance>0) { print " / 1"; } 
  print "<br><b>Maximum hit : </b>".($maxhit1+$bon1h);
  if ($dwchance>0) { print " / $maxhit2"; }
  if ($critchance>0) {
    print "<br><b>Max critical hit : </b>".floor(($maxhit1+$bon1h)*2.5);
    if ($dwchance>0) { print " / ".floor($maxhit2*2.5); }
  }
  if (($critchance>0) && ($critchanceber>0)) { 
    print "<br><b>Max crippling blow : </b>".floor(($maxhit1+$bon1h)*5);
    if ($dwchance>0) { print " / ".floor($maxhit2*5); }
  }
  print "<br><b>Compared efficiency : </b>$compared1modal";
  if ($dwchance>0) { print " + $compared2modal = ".($compared1modal+$compared2modal); }
  
  // 2H weapon
  print "</td><td nowrap><b>Minimum hit : </b>".($bon2h+1);
  print "<br><b>Maximum hit : </b>".($maxhit2h+$bon2h);
  if ($critchance>0) {
    print "<br><b>Max critical hit : </b>".floor(($maxhit2h+$bon2h)*2.5);
  }
  if (($critchance>0) && ($critchanceber>0)) { 
    print "<br><b>Max crippling blow : </b>".floor(($maxhit2h+$bon2h)*5); 
  }
  print "<br><b>Compared efficiency : </b>$compared2Hmodal";
  print "</td></tr>";
  
  print "</table></center>";
  
}

include("../includes/footers.php");
?>