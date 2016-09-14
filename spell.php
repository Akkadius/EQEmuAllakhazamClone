<?php


	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'functions.php');
	include($includes_dir.'spell.inc.php');
	$id = $_GET["id"];
	$spell=getspell($id);
	if(!$spell)
	{
		header("Location: spells.php");
		exit();
	}
	$Title=$spell["name"];
	include($includes_dir.'headers.php');

	print "<center><table border=0 width=50%><tr valign=top><td>
		   <table border=0 width=0%>";
			if(file_exists(getcwd(). "/icons/". $spell['new_icon'] . ".gif"))
			{ 
				echo "<tr align='center'><td nowrap align='center'>
					<img src='". $icons_url . $spell['new_icon'].".gif' align='center'/ border='1'>
					</td><td> <FONT SIZE='3'><b>" . $spell["name"]."</b></FONT></td></tr>"; 
			}
			
	#print "<tr><td nowrap><b>Name : </b></td><td>".
	print "<tr><td nowrap><b>Classes : </b></td><td>";
	$v="";
	$minlvl=70;
	for ($i=1; $i<=16; $i++)
	{
		if (($spell["classes$i"]>0) AND ($spell["classes$i"]<255))
		{
			print "$v ".$dbclasses[$i]." (".$spell["classes$i"].")";
			$v=",";
			if ($spell["classes$i"]<$minlvl) { $minlvl=$spell["classes$i"]; }
		}
	}
	print "</td></tr>";
	if ($spell["you_cast"]!="") { print "<tr><td nowrap><b>When you cast: </b></td><td>".$spell["you_cast"]."</td></tr>"; }
	if ($spell["other_casts"]!="") { print "<tr><td nowrap><b>When others cast : </b></td><td>".$spell["other_casts"]."</td></tr>"; }
	if ($spell["cast_on_you"]!="") { print "<tr><td nowrap><b>When cast on you : </b></td><td>".$spell["cast_on_you"]."</td></tr>"; }
	if ($spell["cast_on_other"]!="") { print "<tr><td nowrap><b>When cast on other : </b></td><td>".$spell["cast_on_other"]."</td></tr>"; }
	if ($spell["spell_fades"]!="") { print "<tr><td nowrap><b>When fading : </b></td><td>".$spell["spell_fades"]."</td></tr>"; }
	print "<tr><td nowrap><b>Mana : </b></td><td>".$spell["mana"]."</td></tr>";
	if ($spell["skill"]<52)
	{
		//print "<tr><td nowrap><b>Skill : </b></td><td>".ucfirstwords($dbskills[$spell["skill"]])."</td></tr>";
		print "<tr><td nowrap><b>Skill : </b></td><td>".$dbskills[$spell["skill"]]."</td></tr>";
	}
	print "<tr><td nowrap><b>Casting time : </b></td><td>".($spell["cast_time"]/1000)." sec</td></tr>";
	print "<tr><td nowrap><b>Recovery time : </b></td><td>".($spell["recovery_time"]/1000)." sec</td></tr>";
	print "<tr><td nowrap><b>Recast time : </b></td><td>".($spell["recast_time"]/1000)." sec</td></tr>";
	print "<tr><td nowrap><b>Range : </b></td><td>".$spell["range"]."</td></tr>";
	print "<tr><td nowrap><b>Target : </b></td><td>";
	if ($dbspelltargets[$spell["targettype"]]!="") { print $dbspelltargets[$spell["targettype"]]; }
	else { print "Unknown target (".$spell["targettype"].")"; }
	print "</td></tr>";
	print "<tr><td nowrap><b>Resist : </b></td><td>".$dbspellresists[$spell["resist"]]." (adjust: ".$spell["ResistDiff"].")</td></tr>";
	if ($spell["TimeOfDay"]==2) { print "<tr><td nowrap><b>Casting restriction : </b></td><td>Nighttime</td></tr>"; }
	$duration=CalcBuffDuration($minlvl,$spell["buffdurationformula"],$spell["buffduration"]);
	print "<tr><td nowrap><b>Duration : </b></td><td>";
	if ($duration==0) { print "Instant"; } 
	else { print translate_time($duration*6)." ($duration ticks)"; }
	print "</td></tr>";
	for ($i=1; $i<=4; $i++)
	{
		// reagents
		if ($spell["components".$i]>0)
		{
			print "<tr><td nowrap><b>Needed reagent $i : </b></td><td>".
				"<a href=item.php?id=".$spell["components".$i].
				">".GetFieldByQuery("Name","SELECT Name FROM $tbitems WHERE id=".
				$spell["components".$i]).
				" </a>(".$spell["component_counts".$i].")</td></tr>";
		}
	}


	print "<tr><td colspan=2><b>Spell effects:</b></td></tr>";
	
	echo '<td align="center" colspan=2><small>';
	for ($n=1; $n<=12; $n++) {
	  SpellDescription($spell,$n);
	}
	echo '</small></td>';
	
	print "</table></td><td nowrap>";

	$query="SELECT $tbitems.id,$tbitems.name
			FROM $tbitems
			WHERE $tbitems.scrolleffect=$id
			ORDER BY $tbitems.name ASC";
	$result=mysql_query($query) or message_die('item.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)) {
			print "<b>Items with that spell</b>";
			while ($row=mysql_fetch_array($result)) {
			print "<li><a href=item.php?id=".$row["id"].">".$row["name"]."</a>";
		}
	}
	print "</td></tr></table></center>";

	include($includes_dir."footers.php");
?>