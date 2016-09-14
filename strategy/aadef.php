<?php
include('../includes/constantes.php');
include('../includes/config.php');
include('../includes/functions.php');
include('../includes/mysql.php');
$Title="Defensive AA";
include('../includes/headers.php');

print "<p><center><table border=0 width=0%>";
print "<form method=POST action=$PHP_SELF>";
print "<tr><td nowrap><b>Combat stability : </b></td><td>";
print SelectLevel("ics",3,$ics);
print "<td></tr>";
print "<tr><td nowrap><b>Combat agility : </b></td><td>";
print SelectLevel("ica",3,$ica);
print "<td></tr>";
print "<tr><td nowrap><b>Innate defense : </b></td><td>";
print SelectLevel("iid",5,$iid);
print "<td></tr>";
#print "<tr><td nowrap><b>Lightning reflex : </b></td><td>";
#print SelectLevel("ilr",5,$ilr);
#print "<td></tr>";
if (!isset($imhits)) { $imhits=25; }
if (!isset($imdam)) { $imdam=140; }

print "<tr><td nowrap><b>Number of hits from the mob : </b></td><td><input type=text value=\"$imhits\" size=4 name=imhits class=form></td></tr>";
print "<tr><td nowrap><b>Average per hit from the mob : </b></td><td><input type=text value=\"$imdam\" size=4 name=imdam class=form></td></tr>";
print "<tr align=center><td nowrap colspan=2><input type=submit value=Calculate name=icalculate class=form>
       </td></tr>";
print "</form></table></center>";

if (isset($icalculate)) {
  $total["dam"]=$imhits*$imdam;
  switch ($ics) {
    case 1:
      $corr["dam"]=floor(0.98*$imdam);
      break;
    case 2:
      $corr["dam"]=floor(0.95*$imdam);
      break;
    case 3:
      $corr["dam"]=floor(0.90*$imdam);
      break;
    default:
      $corr["dam"]=$imdam;
  }
  switch ($ica) {
    case 1:
      $corr["hits"]=floor(0.98*$imhits);
      break;
    case 2:
      $corr["hits"]=floor(0.95*$imhits);
      break;
    case 3:
      $corr["hits"]=floor(0.90*$imhits);
      break;
    default:
      $corr["hits"]=$imhits;
  }
  $corr["total"]=$corr["dam"]*$corr["hits"];
  $corr["saved"]=($total["dam"]-$corr["total"]);
  $corr["persaved"]=floor(100*$corr["saved"]/$total["dam"]);
  print "<center><table border=0>";
  print "<tr align=center><td></td><td><b>Without AA</b></td><td><b>With AA</b></td></tr>";
  print "<tr><td><b>Hits taken : </b></td><td align=center>".$imhits."</td><td align=center>".$corr["hits"]."</td></tr>";
  print "<tr><td><b>Average hit : </b></td><td align=center>".$imhits."</td><td align=center>".$corr["dam"]."</td></tr>";
  print "<tr><td><b>Damage taken : </b></td><td align=center>".$total["dam"]."</td><td align=center>".$corr["total"]."</td></tr>";
  print "<tr><td><b>Health point saved : </b></td><td colspan=2 align=center>".$corr["saved"]." (-".$corr["persaved"]."%)</td></tr>";
  
  print "</table></center>";
  
} // fin calculate


include("../includes/footers.php");
?>