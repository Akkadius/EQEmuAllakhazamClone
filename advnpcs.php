<?php
	$Title="Advanced NPC Search";
	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'headers.php');
	include($includes_dir.'functions.php');

	$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
	$id   = (isset($_GET[  'id']) ? addslashes($_GET[  'id']) : '');
	$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
	$iminlevel = (isset($_GET['iminlevel']) ? $_GET['iminlevel'] : '');
	$imaxlevel = (isset($_GET['imaxlevel']) ? $_GET['imaxlevel'] : '');
	$inamed = (isset($_GET['inamed']) ? $_GET['inamed'] : '');
	$ishowlevel = (isset($_GET['ishowlevel']) ? $_GET['ishowlevel'] : '');
	$irace = (isset($_GET['irace']) ? $_GET['irace'] : '');
	if ($irace==0) { $irace=''; }

	print "<center><table border=0 width=0%><tr valign=top><td>";
	print "<table border=0 width=0%>";
	print "<form method=GET action=$PHP_SELF>";
	print "<tr><td nowrap><b>Name : </b></td><td><input type=text value=\"$iname\" size=30 name=iname ></td></tr>";
	print "<tr><td nowrap><b>Level : </b></td><td nowrap>Between ";
	print SelectLevel("iminlevel",$ServerMaxNPCLevel,$iminlevel);
	print " and ";
	print SelectLevel("imaxlevel",$ServerMaxNPCLevel,$imaxlevel);
	print "</tr>";
	print "<tr><td nowrap><b>Race : </b></td><td nowrap>";
	print SelectMobRace("irace",$irace);
	print "</td></tr>";
	print "<tr><td nowrap><b>Named mob : </b></td><td><input type=checkbox name=inamed ".($inamed?" checked":"")."></td></tr>";
	print "</table></td><td><table border=0 width=0%>";
	print "<tr><td nowrap><b>Show level : </b></td><td><input type=checkbox name=ishowlevel ".($ishowlevel?" checked":"")."></td></tr>";
	print "</table>";
	print "<tr align=center colspan=2><td nowrap colspan=2><input type=submit value=Search name=isearch class=form></td></tr>";
	print "</form></table></center>";

	if (isset($isearch) && $isearch!='')
	{
		$query="SELECT $tbnpctypes.id,$tbnpctypes.name,$tbnpctypes.level
				FROM $tbnpctypes
				WHERE 1=1";
		if ($iminlevel>$imaxlevel) { $c=$iminlevel; $iminlevel=$imaxlevel; $imaxlevel=$c; }          
		if ($iminlevel>0 && is_numeric($iminlevel)) { $query.=" AND $tbnpctypes.level>=$iminlevel"; }
		if ($imaxlevel>0 && is_numeric($imaxlevel)) { $query.=" AND $tbnpctypes.level<=$imaxlevel"; }
		if ($inamed) { $query.=" AND substring($tbnpctypes.name,1,1)='#'"; }
		if ($irace>0 && is_numeric($irace)) { $query.=" AND $tbnpctypes.race=$irace"; }
		if ($iname!="")
		{ 
			$iname=str_replace('`','%',str_replace(' ','%',addslashes($iname)));
			$query.=" AND $tbnpctypes.name LIKE '%$iname%'"; 
		}
		if ($HideInvisibleMen==TRUE) { $query.=" AND $tbnpctypes.race!=127"; }          
		$query.=" ORDER BY $tbnpctypes.name";
		$result=mysql_query($query) or message_die('npcs.php','MYSQL_QUERY',$query,mysql_error());
		$n=mysql_num_rows($result);
		if ($n>$MaxNpcsReturned)
		{
			print "$n ncps found, showing the $MaxNpcsReturned first ones...";
			$query.=" LIMIT $MaxNpcsReturned";
			$result=mysql_query($query) or message_die('npcs.php','MYSQL_QUERY',$query,mysql_error());
		}
		if (mysql_num_rows($result)>0)
		{
			while ($row=mysql_fetch_array($result))
			{
				print "<li><a href=npc.php?id=".$row["id"].">".ReadableNpcName($row["name"])."</a>";
				if ($ishowlevel) { print " - level ".$row["level"]; }
			}
		}
		else
		{
			print "<li>No npc found.";
		}
	}

	include($includes_dir."footers.php");
?>