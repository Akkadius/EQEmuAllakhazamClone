<?php
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');


$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');
$order = (isset($_GET['order']) ? addslashes($_GET["order"]) : 'name');
$mode= (isset($_GET['mode']) ? addslashes($_GET["mode"]) : 'npcs');

if ($UseCustomZoneList==TRUE && $name != '')
{
	$ZoneNote=GetFieldByQuery("note","SELECT note FROM $tbzones WHERE short_name='$name'");
	if (substr_count(strtolower($ZoneNote), "disabled") >= 1)
	{
		header("Location: index.php");
		exit();
	}
}

$Title=GetFieldByQuery("long_name","SELECT long_name FROM $tbzones WHERE short_name='$name'")." ($name)";
include($includes_dir.'headers.php');
include($includes_dir.'functions.php');

if (!isset($name)) { print "<script>document.location=\"zones.php\";</script>"; }

$ZoneDebug=FALSE; // this is new in 0.5.3 but undocumented, it is for world builders

print "<table border=0 width=100%><tr valign=top><td width=100%>";

$query="SELECT $tbzones.*
        FROM $tbzones
        WHERE $tbzones.short_name='$name'";
$result=mysql_query($query) or message_die('zones.php','MYSQL_QUERY',$query,mysql_error());
$zone=mysql_fetch_array($result);
print "<center><table border=0 width=0%><tr valign=top><td>";
print "<p><b>Succor point : X (</b>".floor($zone["safe_x"]).")  Y (".floor($zone["safe_y"]).") Z (".floor($zone["safe_z"]). ")";
if ($zone["minium_level"]>0) { print "<br><b>Minimum level : </b>".floor($zone["minium_level"]); }
print "</td>";
if (file_exists($maps_dir.$name.".jpg"))
{
	if (!file_exists($maps_url.$name."._tn.jpg")) { make_thumb($maps_dir.$name.".jpg"); }
	print "<td>&nbsp;&nbsp;&nbsp;</td><td align=center><a href=".$maps_url.$name.".jpg><img src=".$maps_url.$name."._tn.jpg width=120 height=80 border=0></a><br>
		<a href=".$maps_url.$name.".jpg target=_new>Popup map</a>
		</td>";
}
print "</tr></table></center>";

if ($mode=="npcs") {
	////////////// NPCS
	$query="SELECT $tbnpctypes.id,$tbnpctypes.class,$tbnpctypes.level,$tbnpctypes.trackable,$tbnpctypes.maxlevel,$tbnpctypes.race,$tbnpctypes.name,$tbnpctypes.maxlevel,$tbnpctypes.loottable_id
		FROM $tbnpctypes,$tbspawn2,$tbspawnentry,$tbspawngroup";
	$query.=" WHERE $tbspawn2.zone='$name'
		AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
		AND $tbspawnentry.npcID=$tbnpctypes.id
		AND $tbspawngroup.id=$tbspawnentry.spawngroupID";

	if ($HideInvisibleMen==TRUE) { $query.=" AND $tbnpctypes.race!=127 AND $tbnpctypes.race!=240"; }          
	if ($GroupNpcsByName==TRUE)
	{
		$query.=" GROUP BY $tbnpctypes.name";
	}
	else
	{
		$query.=" GROUP BY $tbnpctypes.id";
	}
	$query.=" ORDER BY $order";
	$result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
	print "<center>";
	if (mysql_num_rows($result) > 0)
	{
		print "<p>Bestiary<p><table border=0 width='0%' cellpadding='5' cellspacing='0'><tr>";
		if ($ZoneDebug==TRUE) { print "<td class='menuh'><b><a href=$PHP_SELF?name=$name&order=id>Id</a></b></td>"; }
		print "<td align='left'  class='menuh'><b><a href=$PHP_SELF?name=$name&order=name>Name</a></b></td>";
		if ($ZoneDebug==TRUE) { print "<td class='menuh' align='left'><b><a href=$PHP_SELF?name=$name&order=loottable_id>Loottable</a></b></td>"; }
		print "<td align='left'  class='menuh'><b><a href=$PHP_SELF?name=$name&order=level>Level Range</a></b></td>";
		print "<td align='left' class='menuh'><b><a href=$PHP_SELF?name=$name&order=race>Race</a></b></td>";
		print "<td align='left' class='menuh'><b>Type</b></td>";
		
		$RowClass = "lr";
		while ($row=mysql_fetch_array($result))
		{
			if ((ReadableNpcName($row["name"])) != '' && ($row["trackable"] > 0 || $TrackableNPCsOnly==FALSE))
			{
				print "<tr class='" .$RowClass. "'>";
				if ($ZoneDebug==TRUE) { print "<td>".$row["id"]."</td>"; }
				print "<td><a href=npc.php?id=".$row["id"].">".ReadableNpcName($row["name"])."</a>";
				if ($ZoneDebug==TRUE) { print "</td><td>".$row["loottable_id"]; }    

				if($row['maxlevel'] == 0){
					$MaxLevel = $row['level'];
				}
				else{
					$MaxLevel = $row['maxlevel'];
				}
				
				print "</td><td align=left>".$row["level"]." - ". $MaxLevel . " </td>";
				print "<td align=left>".$dbiracenames[$row["race"]]."</td>";
				print "<td align=left>".NpcTypeFromName($row["name"])."</td></tr>";
				
				if ($RowClass == "lr")
				{
					$RowClass = "dr";
				}
				else
				{
					$RowClass = "lr";
				}
			}
		}
		print "</table>";
	}
	else
	{
		print "<br><b>No NPCs Found</b>";
	}
	print "</center>";
} // end npcs

if ($mode=="items") {
	$ItemsFound = 0;
	
	$EquiptmentTable = "<p>Equipment List<p><table border=0><tr>
		<th class='menuh'>Icon</a></th>
		<th class='menuh'><a href=$PHP_SELF?name=$name&mode=items&order=Name>Name</a></th>
		<th class='menuh'><a href=$PHP_SELF?name=$name&mode=items&order=itemtype>Item type</a></th>
		</tr>";

	$query="SELECT $tbnpctypes.id";
	$query.=" FROM $tbnpctypes,$tbspawn2,$tbspawnentry,$tbspawngroup";
	$query.=" WHERE $tbspawn2.zone='$name'
		AND $tbspawnentry.spawngroupID=$tbspawn2.spawngroupID
		AND $tbspawnentry.npcID=$tbnpctypes.id
		AND $tbspawngroup.id=$tbspawnentry.spawngroupID";
    
	if ($MerchantsDontDropStuff==TRUE)
	{
		foreach($dbmerchants AS $c) { $query.=" AND $tbnpctypes.class!=$c";  }
	}
	$query.=" GROUP BY $tbnpctypes.id";

	$result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
    $ItemsData = array();
	$RowClass = "lr";
	while ($row=mysql_fetch_array($result))
	{
		//# For each NPC in the zone...
		$query="SELECT $tbitems.*";
		$query.=" FROM $tbitems,$tbloottableentries,$tbnpctypes,$tblootdropentries";
		if ($DiscoveredItemsOnly==TRUE)
		{
			$query.=",$tbdiscovereditems";
		}
		$query.=" WHERE $tbnpctypes.id=".$row["id"]."
			AND $tbnpctypes.loottable_id=$tbloottableentries.loottable_id
			AND $tbloottableentries.lootdrop_id=$tblootdropentries.lootdrop_id
			AND $tblootdropentries.item_id=$tbitems.id";
		if ($DiscoveredItemsOnly==TRUE)
		{
			$query.=" AND $tbdiscovereditems.item_id=$tbitems.id";
		}
		$query.=" GROUP BY $tbitems.id ORDER BY $tbitems.name";
    
		$result2=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
		
		if (mysql_num_rows($result2) > 0)
		{
			$ItemsFound = mysql_num_rows($result2);
		}
	
		while ($res=mysql_fetch_array($result2))
		{
			$ItemsData[$res["id"]] = $res;
		}
	}

	$ToolTips = "";
	
	$sortby = $order;
	if ($sortby == "name")
	{
		$sortby = "Name";
	}

	// Sort the Array by the desired field of the items table
	$tmp = Array();
	foreach($ItemsData as &$MultiKey)
	{
		$tmp[] = &$MultiKey[$sortby];
	}

	array_multisort($tmp, $ItemsData); 

	foreach ($ItemsData as $key => $ItemData)
	{
		if ($ItemData["itemtype"]>0)
		{ 
			$ItemType=$dbitypes[$ItemData["itemtype"]]; 
		}
		else
		{ 
			if ($ItemData["bagslots"]>0)
			{
				$ItemType="Bag";
			}
			else
			{
				$ItemType=$dbitypes[$ItemData["itemtype"]];
			}
		}
		$EquiptmentTable .= "<tr class='".$RowClass."'>
		<td><img src='".$icons_url."item_".$ItemData["icon"].".gif' align='left'/>
		<img src='".$images_url."spacer_1.png' align='left'/>
		</td><td><a href=item.php?id=".$ItemData["id"]." id='" . $ItemData["id"] . "'>".$ItemData["Name"]."</a></td>
		<td>".$ItemType."</td></tr>";
		if ($RowClass == "lr")
		{
			$RowClass = "dr";
		}
		else
		{
			$RowClass = "lr";
		}
	}

	$EquiptmentTable .= "</table>";
	print "<center>";

	if ($ItemsFound > 0)
	{
		print $EquiptmentTable;
		foreach ($ItemsData as $key => $ItemData)
		{
			$ToolTips .= CreateToolTip($ItemData["id"], BuildItemStats($ItemData, 1));
		}
		print $ToolTips;

	}
	else
	{
		print "<br><b>No Items Found</b>";
	}

	print "</center>";

} // end items

if ($mode=="spawngroups") {
	if ($DisplaySpawnGroupInfo==TRUE)
	{
		print "</center>";
		$query="SELECT $tbspawngroup.*,$tbspawn2.x,$tbspawn2.y,$tbspawn2.z,$tbspawn2.respawntime
			FROM $tbspawn2,$tbspawngroup
			WHERE $tbspawn2.zone='$name'
			AND $tbspawngroup.id=$tbspawn2.spawngroupID
			ORDER BY $tbspawngroup.name ASC";
		$result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());

		if (mysql_num_rows($result) > 0)
		{
			while ($row=mysql_fetch_array($result))
			{
				print "<li><a href=spawngroup.php?id=".$row["id"].">".$row["name"]."</a> (".
				floor($row["y"])." / ".floor($row["x"])." / ".floor($row["z"]).") (respawn time : ".
				translate_time($row["respawntime"]).")<ul>";
				$query="SELECT $tbspawnentry.npcID,$tbnpctypes.name,$tbspawnentry.chance,$tbnpctypes.level
					FROM $tbspawnentry,$tbnpctypes
					WHERE $tbspawnentry.npcID=$tbnpctypes.id
					AND $tbspawnentry.spawngroupID=".$row["id"]."
					ORDER BY $tbnpctypes.name ASC";           
				$result2=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());           
				while ($res=mysql_fetch_array($result2))
				{
					print "<li><a href=npc.php?id=".$res["npcID"].">".$res["name"]."</a>, chance ".$res["chance"]."%";
					print " (level ".$res["level"].")";
				}           
				print "</ul>";
			}
		}
		else
		{
			print "<center><br><b>No Spawns Found</b></center>";
		}
 	 
		print "<center>";
	}

} // end spawngroups

if ($mode=="forage") {
	$query="SELECT $tbitems.Name,$tbitems.id
		FROM $tbitems,$tbforage,$tbzones
		WHERE $tbitems.id=$tbforage.itemid
		AND $tbforage.zoneid=$tbzones.zoneidnumber
		AND $tbzones.short_name='$name'
		ORDER BY $tbitems.Name ASC";
	$result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
	print "<center>";
	if (mysql_num_rows($result) > 0)
	{
		print "<p>Forageable Items<p><table border=1><tr>
			<td class=tab_title>Name</a></td>
			</tr>";
		while ($row=mysql_fetch_array($result))
		{
			print "<tr><td><a href=item.php?id=".$row["id"].">".$row["Name"]."</a></td></tr>";
		}
		print "</table>";
	}
	else
	{
		print "<br><b>No Forageable Items Found</b>";
	}
	print "</center>";
} // end forage

if ($mode=="tasks") {

	if ($DisplayTaskInfo==TRUE)
	{
		$ZoneID = GetFieldByQuery("zoneidnumber","SELECT zoneidnumber FROM zone WHERE short_name = '$name'");
		$query="SELECT $tbtasks.id, $tbtasks.title, $tbtasks.startzone, $tbtasks.minlevel, $tbtasks.maxlevel, $tbtasks.reward, $tbtasks.rewardid, $tbtasks.rewardmethod
			FROM $tbtasks
			WHERE $tbtasks.startzone=$ZoneID
			ORDER BY $tbtasks.id ASC";

		$result=mysql_query($query) or message_die('zone.php','MYSQL_QUERY',$query,mysql_error());
		print "<center>";
		if (mysql_num_rows($result) > 0)
		{
			print "<table border=0 width=100% cellpadding='5' cellspacing='0'><tr valign=top><td width=100%>";
			print "<center><table border=0 cellpadding='5' cellspacing='0'><tr>
				<td class='menuh'>Task Name</td>
				<td class='menuh'>Task ID</td>
				<td class='menuh'>MinLevel</td>
				<td class='menuh'>MaxLevel</td>
				<td class='menuh'>Reward</td>
				";
				
			$RowClass = "lr";
			while ($row=mysql_fetch_array($result))
			{
				$Reward = $row["reward"];
				if ($row["rewardmethod"] == 0)
				{
					if ($row["rewardid"] > 0)
					{
						$ItemID = $row["rewardid"];
						$ItemName = GetFieldByQuery("Name","SELECT Name FROM items WHERE id = $ItemID");
						$Reward = "<a href=item.php?id=".$ItemID.">".$ItemName."</a>";
					}
				}

				print "<tr class='" .$RowClass. "'>
					<td><a href=task.php?id=".$row["id"].">".$row["title"]."</a></td>
					<td align=center valign='top'>".$row["id"]."</td>
					<td align=center valign='top'>".$row["minlevel"]."</td>
					<td align=center valign='top'>".$row["maxlevel"]."</td>
					<td>".$Reward."</td>
					</tr>";
				if ($RowClass == "lr"){
					$RowClass = "dr";
				}
				else{
					$RowClass = "lr";
				}
			}
			print "</table></center>";
			print "</td><td width=0% nowrap>";
			print "</td></tr></table>";
		}
		else
		{
			print "<br><b>No Tasks Found</b>";
		}
		print "</center>";

	}

} // end Tasks

print "</td><td width=0% nowrap>"; // end first column
print "<p class=menuh>Resources:</p>";
print "<li><a href=$PHP_SELF?name=$name&mode=npcs>".$zone["long_name"]." Bestiary List</a>";
if ($DisplayNamedNPCsInfo==TRUE)
{
	print "<li><a href=zonenameds.php?name=$name&mode=npcs>".$zone["long_name"]." Named Mobs List</a>";
}
print "<li><a href=$PHP_SELF?name=$name&mode=items>".$zone["long_name"]." Equipment List </a>";
if (file_exists($maps_dir.$name.".jpg")) {
  print "<li><a href=".$maps_url.$name.".jpg>".$zone["long_name"]." Map</a>";
}
if ($DisplaySpawnGroupInfo==TRUE)
{
	print "<li><a href=$PHP_SELF?name=$name&mode=spawngroups>".$zone["long_name"]." Spawn Groups</a>";
}
print "<li><a href=$PHP_SELF?name=$name&mode=forage>".$zone["long_name"]." Forageable items</a>";
if ($DisplayTaskInfo==TRUE)
{
	print "<li><a href=$PHP_SELF?name=$name&mode=tasks>".$zone["long_name"]." Tasks</a>";
}
if ($AllowQuestsNPC==TRUE) {
  print "<li><a href=$root_url"."quests/zones.php?aZone=$name>".$zone["long_name"]." Quest NPCs</a>";
}
print "</td></tr></table>";

include($includes_dir."footers.php");
?>