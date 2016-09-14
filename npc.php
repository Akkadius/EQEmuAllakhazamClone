<?php
	/** Displays the NPC identified by 'id' if it is specified and an NPC by this ID exists.
	 *  Otherwise queries for the NPCs identified by 'name'. Underscores are considered as spaces and backquotes as minuses,
	 *    for Wiki-EQEmu compatibility.
	 *    If exactly one NPC is found, displays this NPC.
	 *    Otherwise redirects to the NPC search page, displaying the results for '%name%'.
	 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid NPC ID, redirects to the NPC search page.
	 */

	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'functions.php');
	include($includes_dir.'mysql.php');

	$id   = (isset($_GET['id']) ? $_GET['id'] : '');
	$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

	if($id != "" && is_numeric($id))
	{
		$Query = "SELECT * FROM $tbnpctypes WHERE id='".$id."'";
		$QueryResult = mysql_query($Query) or message_die('npc.php','MYSQL_QUERY',$Query,mysql_error());
		if(mysql_num_rows($QueryResult) == 0)
		{
			header("Location: npcs.php");
			exit();
		}
		$npc=mysql_fetch_array($QueryResult);
		$name=$npc["name"];
	}
	elseif($name != "")
	{
		$Query = "SELECT * FROM $tbnpctypes WHERE name like '$name'";
		$QueryResult = mysql_query($Query) or message_die('npc.php','MYSQL_QUERY',$Query,mysql_error());
		if(mysql_num_rows($QueryResult) == 0)
		{
			header("Location: npcs.php?iname=".$name."&isearch=true");
			exit();
		}
		else
		{
			$npc = mysql_fetch_array($QueryResult);
			$id = $npc["id"];
			$name = $npc["name"];
		}
	}
	else
	{
		header("Location: npcs.php");
		exit();
	}

	if ($UseCustomZoneList==TRUE)
	{
		$query="SELECT $tbzones.note
					FROM $tbzones,$tbspawnentry,$tbspawn2
					WHERE $tbspawnentry.npcID=$id
					AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
					AND $tbspawn2.zone=$tbzones.short_name
					AND LENGTH($tbzones.note) > 0";        
		$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
		if (mysql_num_rows($result) > 0)
		{
			while ($row=mysql_fetch_array($result))
			{
				if (substr_count(strtolower($row["note"]), "disabled") >= 1)
				{
					header("Location: npcs.php");
					exit();
				}
			}
		}
	}

	if ((ReadableNpcName($npc["name"])) == '' || ($npc["trackable"] == 0 && $TrackableNPCsOnly==TRUE))
	{
		header("Location: npcs.php");
		exit();
	}

	/** Here the following stands :
	 *    $id : ID of the NPC to display
	 *    $name : name of the NPC to display
	 *    $NpcRow : row of the NPC to display extracted from the database
	 *    The NPC actually exists
	 */

	$Title="NPC :: ".ReadableNpcName($name);
	//$XhtmlCompliant = TRUE;
	include($includes_dir.'headers.php');

	$DebugNpc=FALSE; // for world builders, set this to false for common use

	print "<center><table border='0' width='0%'><tr valign='top'><td colspan='2' class='headerrow'>";
	print "<a href='".$peqeditor_url."index.php?editor=npc&amp;npcid=".$id."'><img src='".$images_url."/peq_npc.png' align='right'/></a>";
	print "<a href='".$peqeditor_url."index.php?editor=merchant&amp;npcid=".$id."'><img src='".$images_url."/peq_merchant.png' align='right'/></a>";
	print "<b>".ReadableNpcName($npc["name"])."</b>";
	if ($npc["lastname"]!="") {
	  print "<br/>".str_replace("_"," "," (".$npc["lastname"].")")." - id : ".$id;
	}
	else {
	  print "<br/>id : ".$id;
	}
	print "</td></tr>"; 
	print "<tr valign='top'><td width='0%'><table><tr><td><table border='0' width='100%' cellpadding='0' cellspacing='0'><tr><td>";
	//print "<tr valign='top'><td width='0%'><table><tr><td>";
	print "<table border='0' width='0%'><tr valign='top'><td width='100%'>\n";
	print "<p><table border='0' width='100%'>";
	print "<tr><td nowrap='1'><b>Full name : </b></td><td>".ReadableNpcName($npc["name"]);
	if ($npc["lastname"]!="") { print str_replace("_"," "," (".$npc["lastname"].")"); }
	print "</td></tr>";
	print "<tr><td nowrap='1'><b>Level : </b></td><td width='100%'>".$npc["level"]."</td></tr>";
	print "<tr><td nowrap='1'><b>Race : </b></td><td>".$dbiracenames[$npc["race"]]."</td></tr>";
	print "<tr><td nowrap='1'><b>Class : </b></td><td>".$dbclasses[$npc["class"]];
	if ($npc["npc_faction_id"]>0) {
	  $query="SELECT $tbfactionlist.name,$tbfactionlist.id
				FROM $tbfactionlist,$tbnpcfaction 
				WHERE $tbnpcfaction.id=".$npc["npc_faction_id"]." 
				AND $tbnpcfaction.primaryfaction=$tbfactionlist.id";
	  $faction=GetRowByQuery($query);
	  print "<tr><td nowrap='1'><b>Main faction : </b></td><td><a href='faction.php?id=".$faction["id"]."'>".$faction["name"]."</a></td></tr>";
	}
	if ($npc["findable"]==1)
	{
		print " (findable)";
	}

	print "</td></tr>";

	if ($DisplayNPCStats=="TRUE")
	{
		print "<tr><td nowrap='1'><b>Health points : </b></td><td>".$npc["hp"]."</td></tr>";
		print "<tr><td nowrap='1'><b>Damage : </b></td><td>".$npc["mindmg"]." to ".$npc["maxdmg"]."</td></tr>";
	}
	if ($ShowNpcsAttackSpeed==TRUE)
	{
		print "<tr><td nowrap='1'><b>Attack speed : </b></td><td>";
		if ($npc["attack_speed"]==0)
		{
			print "Normal (100%)";
		}
		else
		{
			print (100+$npc["attack_speed"])."%";
		}
		//print "</td></tr>";
	}
	if ($ShowNpcsAverageDamages==TRUE)
	{
		print "<tr><td nowrap='1'><b>Average melee damages : </b></td><td>";
		$avghit=($npc["maxdmg"]+$npc["mindmg"])/2; // average hit
		$dam=$avghit; # first hit of main hand
		$com=$npc["npcspecialattks"];
		if (CanThisNPCDoubleAttack($npc["class"],$npc["level"]))
		{
			# chance to double attack = level+20>rand(0,99) (mobai.cpp)
			$chance2=($npc["level"]+20)/100;
			$com.=" DA=$chance2";
			$dam+=$avghit*$chance2;
			if ($npc["npcspecialattks"]!="")
			{
				# Npc has some special attacks
				# Able to triple (implicitely, if he can quad, then he can triple, this is NOT in source code ATM (3 may 2006).
				if ((strpos($npc["npcspecialattks"],"T")>0) OR (strpos($npc["npcspecialattks"],"Q")>0))
				{
					# chance to triple, happens when we doubled, and if level>rand(0,99)
					$chance3=$chance2*$npc["level"]/100;
					$com.=" TA=$chance3";
					$dam+=$avghit*$chance3;
					if (strpos($npc["npcspecialattks"],"Q")>0)
					{
						# The NPC can quad
						# chance to quad, happens when we tripled and if level-20>rand(0,99)
						$chance4=$chance2*$chance3*($npc["level"]-20)/100;
						$com.=" QA=$chance4";
						$dam+=$avghit*$chance4;
					}
				}
				# the mob can flurry
				if (strpos($npc["npcspecialattks"],"F")>0)
				{
					# the mob has 20% chances to flurry, and if it flurries, it will hit 2 times
					# so, for each round, it has 20%x2 chances to hit
					$dam+=$avghit*0.4;
				}
			}
		}
		# the npc is slower/faster than normal
		if ($npc["attack_speed"]!=0) { $dam=$dam*(100+$npc["attack_speed"])/100; } // dam per hit
		print round($dam)." per round</td></tr>";
	}
	if ($DisplayNPCStats=="TRUE")
	{
		if ($npc["npcspecialattks"]!='')
		{
			print "<tr><td nowrap='1'><b>Special attacks : </b></td><td>".SpecialAttacks($npc["npcspecialattks"])."</td></tr>";
		}
	}
	
	print "</td></tr></table>\n";
	
	print "<tr class='myline' height='6'><td colspan='2'></td><tr>\n";

	print "<tr valign='top'>";

	if ($npc["npc_spells_id"]>0)
	{
		$query="SELECT * FROM $tbnpcspells WHERE id=".$npc["npc_spells_id"];
		$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
		if (mysql_num_rows($result)>0)
		{
			$g=mysql_fetch_array($result);
			print "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>This NPC casts the following spells : </b><p>";
			$query="SELECT $tbnpcspellsentries.*
					FROM $tbnpcspellsentries
					WHERE $tbnpcspellsentries.npc_spells_id=".$npc["npc_spells_id"]."
					AND $tbnpcspellsentries.minlevel<=".$npc["level"]."
					AND $tbnpcspellsentries.maxlevel>=".$npc["level"]."
					ORDER BY $tbnpcspellsentries.priority DESC";
			$result2=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
			if (mysql_num_rows($result2)>0)
			{
				print "</ul><li><b>Listname : </b>".ReadableNpcName($g["name"]);
				if ($DebugNpc) { print " (".$npc["npc_spells_id"].")"; }
				if ($g["attack_proc"]==1) { print " (Procs)"; }
				print "<ul>";
				while ($row=mysql_fetch_array($result2))
				{
					$spell=getspell($row["spellid"]);
					print "<li><a href='spell.php?id=".$row["spellid"]."'>".$spell["name"]."</a>";
					print " (".$dbspelltypes[$row["type"]].")";
					if ($DebugNpc)
					{
						print " (recast=".$row["recast_delay"].", priority= ".$row["priority"].")"; 
					}
				}
			}
			print "</td></tr></table></td>";
		}
	}

	if (($npc["loottable_id"]>0) AND ((!in_array($npc["class"],$dbmerchants)) OR ($MerchantsDontDropStuff==FALSE)))
	{
		$query="SELECT $tbitems.id,$tbitems.Name,$tbitems.itemtype,
			$tblootdropentries.chance,$tbloottableentries.probability,
			$tbloottableentries.lootdrop_id,$tbloottableentries.multiplier";

		if ($DiscoveredItemsOnly==TRUE)
		{
			$query.=" FROM $tbitems,$tbloottableentries,$tblootdropentries,$tbdiscovereditems";
		}
		else
		{
			$query.=" FROM $tbitems,$tbloottableentries,$tblootdropentries";
		}

		$query.=" WHERE $tbloottableentries.loottable_id=".$npc["loottable_id"]."
			AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
			AND $tblootdropentries.item_id=$tbitems.id";

		if ($DiscoveredItemsOnly==TRUE)
		{
			$query.=" AND $tbdiscovereditems.item_id=$tbitems.id";
		}
		$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
		if (mysql_num_rows($result)>0)
		{
			if ($ShowNpcDropChances==TRUE)
			{
				print "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>When killed, this NPC drops : </b><br/>";
			}
			else
			{
				print "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>When killed, this NPC can drop : </b><br/>";
			}
			$ldid=0;
			while ($row=mysql_fetch_array($result))
			{
				if ($ShowNpcDropChances==TRUE)
				{
					if ($ldid!=$row["lootdrop_id"])
					{
						print "</ol><li>With a probability of ".$row["probability"]."% (multiplier : ".$row["multiplier"]."): </li><ol>";
						$ldid=$row["lootdrop_id"];
					}
				}
				print "<li><a href='item.php?id=".$row["id"]."'>".$row["Name"]."</a>";
				print " (".$dbitypes[$row["itemtype"]].")";
				if ($ShowNpcDropChances==TRUE)
				{ 
					print " - ".$row["chance"]."%";
					print " (".($row["chance"]*$row["probability"]/100)."% global)";
				}
				print "</li>";
			}
			print "</td></tr></table></td>";
		}
		else
		{
			print "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>No item drops found. </b><br/>";
			print "</td></tr></table></td>";
		}
	}

	if ($npc["merchant_id"]>0)
	{
		$query="SELECT $tbitems.id,$tbitems.Name,$tbitems.price,$tbitems.ldonprice
				FROM $tbitems,$tbmerchantlist
				WHERE $tbmerchantlist.merchantid=".$npc["merchant_id"]."
				AND $tbmerchantlist.item=$tbitems.id
				ORDER BY $tbmerchantlist.slot";
		$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
		if (mysql_num_rows($result)>0)
		{
			print "<td><table border='0'><tr><td colspan='2' nowrap='1'><b>This NPC sells : </b><br/>";
			while ($row=mysql_fetch_array($result))
			{
				print "<li><a href='item.php?id=".$row["id"]."'>".$row["Name"]."</a> ";
				if ($npc["class"]==41) { print "(".price($row["price"]).")"; } // NPC is a shopkeeper
				if ($npc["class"]==61) { print "(".$row["ldonprice"]." points)"; } // NPC is a LDON merchant
				print "</li>";
			}
			print "</td></tr></table></td>";
		}
	}

	print "</tr></table>";
	
	
	print "</td><td valign='top'><table border='0' width='0%'>"; // right column height='100%'
	print "<tr><td nowrap='1'>"; // image
	if($UseWikiImages)
	{
		$ImageFile = NpcImage($wiki_server_url, $wiki_root_name, $id);
		if($ImageFile == "")
		{
			print "<a href='".$wiki_server_url.$wiki_root_name."/index.php?title=Special:Upload&wpDestFile=Npc-".$id.".jpg'>Click to add an image for this NPC</a>";
		}
		else
		{
			print "<img src='".$ImageFile."'/>";
		}
	}
	else
	{
		if(file_exists($npcs_dir.$id.".jpg"))
		{
			print "<img src=".$npcs_url.$id.".jpg>";
		}
	}

	print "</td></tr><tr><td nowrap='1'>"; 
	// zone list
	$query="SELECT $tbzones.long_name,
				$tbzones.short_name,
				$tbspawn2.x,$tbspawn2.y,$tbspawn2.z,
				$tbspawngroup.name as spawngroup,
				$tbspawngroup.id as spawngroupID,
				$tbspawn2.respawntime
				FROM $tbzones,$tbspawnentry,$tbspawn2,$tbspawngroup
				WHERE $tbspawnentry.npcID=$id
				AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
				AND $tbspawn2.zone=$tbzones.short_name
				AND $tbspawnentry.spawngroupID=$tbspawngroup.id";
	foreach ($IgnoreZones AS $zid)
	{
		$query.=" AND $tbzones.short_name!='$zid'";
	}          
	$query.=" ORDER BY $tbzones.long_name,$tbspawngroup.name";
	$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)>0)
	{
		print "<b>This NPC spawns in : </b>";
		$z="";
		while ($row=mysql_fetch_array($result))
		{
			if ($z!=$row["short_name"])
			{
				print "<p><a href='zone.php?name=".$row["short_name"]."'>".$row["long_name"]."</a>";
				$z=$row["short_name"];
				if ($AllowQuestsNPC==TRUE)
				{
					if (file_exists("$quests_dir$z/".str_replace("#","",$npc["name"]).".pl"))
					{
						print "<br/><a href='".$root_url."quests/index.php?npc=".str_replace("#","",$npc["name"])."&zone=".$z."&amp;npcid=".$id."'>Quest(s) for that NPC</a>"; 
					}
				}
			}
			if ($DisplaySpawnGroupInfo==TRUE)
			{
				print "<li><a href='spawngroup.php?id=".$row["spawngroupID"]."'>".$row["spawngroup"]."</a> : ".floor($row["y"])." / ".floor($row["x"])." / ".floor($row["z"]);
				print "<br/>Spawns every ".translate_time($row["respawntime"]);
			}
		}
	}
	// factions
	$query="SELECT $tbfactionlist.name,
			$tbfactionlist.id,
			$tbnpcfactionentries.value
			FROM $tbfactionlist,$tbnpcfactionentries
			WHERE $tbnpcfactionentries.npc_faction_id=".$npc["npc_faction_id"]."
			AND $tbnpcfactionentries.faction_id=$tbfactionlist.id
			AND $tbnpcfactionentries.value<0
			GROUP BY $tbfactionlist.id";
	$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)>0)
	{
		print "<p><b>Killing this NPC lowers factions with : </b><ul>";
		while ($row=mysql_fetch_array($result))
		{
			print "<li><a href=faction.php?id=".$row["id"].">".$row["name"]."</a> (".$row["value"].")"; 
		}
	}
	print "</ul>";
	$query="SELECT $tbfactionlist.name,
			$tbfactionlist.id,
			$tbnpcfactionentries.value
			FROM $tbfactionlist,$tbnpcfactionentries
			WHERE $tbnpcfactionentries.npc_faction_id=".$npc["npc_faction_id"]."
			AND $tbnpcfactionentries.faction_id=$tbfactionlist.id
			AND $tbnpcfactionentries.value>0
			GROUP BY $tbfactionlist.id";
	$result=mysql_query($query) or message_die('npc.php','MYSQL_QUERY',$query,mysql_error());
	if (mysql_num_rows($result)>0)
	{
		print "<p><b>Killing this NPC raises factions with : </b><ul>";
		while ($row=mysql_fetch_array($result))
		{
			print "<li><a href=faction.php?id=".$row["id"].">".$row["name"]."</a> (".$row["value"].")"; 
		}
	}
	print "</ul>";
	print "</td></tr></table>\n";
	
	print "</td></tr></table>\n";
	print "</td></tr></table>\n";
	print "</td></tr></table></center>\n";

	include($includes_dir."footers.php");
?>
