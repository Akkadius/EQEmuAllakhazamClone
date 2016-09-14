<?php

	/** Displays the faction identified by 'id' if it is specified and a faction by this ID exists.
	 *  Otherwise queries for the factions identified by 'name'. Underscores are considered as spaces, for Wiki compatibility.
	 *    If exactly one faction is found, displays this faction.
	 *    Otherwise redirects to the faction search page, displaying the results for '%name%'.
	 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid faction ID, redirects to the faction search page.
	 */

	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'functions.php');
	include($includes_dir.'mysql.php');

	/** Formats the npc/zone info selected in '$QueryResult' to display them by zone
	 *  The top-level sort must be on the zone
	 */
	function PrintNpcsByZone($QueryResult)
	{
			if(mysql_num_rows($QueryResult) > 0)
			{
				$CurrentZone = "";
				while($row = mysql_fetch_array($QueryResult))
				{
				if($CurrentZone != $row["zone"])
				{
					if($CurrentZone != "")
						print "                  <br/><br/>\n";
					print "                  <b>in <a href='zone.php?name=".$row["zone"]."'>".$row["long_name"]."</a> by </b>\n";
					$CurrentZone = $row["zone"];
				}
				print "<li><a href='npc.php?id=".$row["id"]."'>".str_replace("_"," ",$row["name"])."</a> (".$row["id"].")</li>\n";
			}
			if($CurrentZone != "")
				print "                  <br/><br/>\n";
		}
	}


	$id   = (isset($_GET[  'id']) ? $_GET[  'id'] : '');
	$name = (isset($_GET['name']) ? addslashes($_GET['name']) : '');

	if($id != "" && is_numeric($id))
	{
		$Query = "SELECT id,name FROM $tbfactionlist WHERE id='".$id."'";
		$QueryResult = mysql_query($Query) or message_die('faction.php','MYSQL_QUERY',$Query,mysql_error());
		if(mysql_num_rows($QueryResult) == 0)
		{ header("Location: factions.php");
			exit();
		}
		$FactionRow=mysql_fetch_array($QueryResult);
		$name=$FactionRow["name"];
	}
	elseif($name != "")
	{
	$Query = "SELECT id,name FROM $tbfactionlist WHERE name like '$name'";
		$QueryResult = mysql_query($Query) or message_die('faction.php','MYSQL_QUERY',$Query,mysql_error());
		if(mysql_num_rows($QueryResult) == 0)
		{
			header("Location: factions.php?iname=".$name."&isearch=true");
			exit();
		}
		else
		{
			$FactionRow = mysql_fetch_array($QueryResult);
			$id = $FactionRow["id"];
			$name = $FactionRow["name"];
		}
	}
	else
	{
		header("Location: factions.php");
		exit();
	}

	/** Here the following stands :
	 *    $id : ID of the faction to display
	 *    $name : name of the faction to display
	 *    $FactionRow : row of the faction to display extracted from the database
	 *    The faction actually exists
	 */

	$Title = "Faction :: ".$name;
	$XhtmlCompliant = TRUE;
	include($includes_dir.'headers.php');

	print "          <center>\n";
	print "            <table border='1' width='80%' style='background-color: black; filter:alpha(opacity=70); -moz-opacity:0.7; opacity: 0.7;'>\n";

	// Title and Icon bar
	print "              <tr valign='top' align='left'>\n";
	print "                <td colspan='2' class='headerrow'>\n";
	print "                  <a href='".$peqeditor_url."index.php?editor=faction&amp;fid=".$id."'><img src='".$images_url."/peq_faction.png' align='right'/></a>\n";
	print "                  <b>".$name."</b>\n";
	print "                  <br/>id : ".$id."\n";
	print "                </td>\n";
	print "              </tr>\n";
	print "            </table>\n"; 

	print "            <table border='0' width='80%' style='background-color: ; filter:alpha(opacity=70); -moz-opacity:0.7; opacity: 0.7;'>\n";
	print "              <tr valign='top' align='left'>\n";

	// NPCs raising the faction by killing them
	print "                <td width='50%' nowrap='1' align='left'>\n";
	print "                  <b>NPCs whom death raises the faction</b><br/><br/>\n";
	$Query="SELECT $tbnpctypes.id,$tbnpctypes.name,$tbzones.long_name,$tbspawn2.zone
			FROM $tbnpcfactionentries,$tbnpctypes,$tbspawnentry,$tbspawn2,$tbzones
			WHERE $tbnpcfactionentries.faction_id=$id
			AND $tbnpcfactionentries.npc_faction_id=$tbnpctypes.npc_faction_id
			AND $tbnpcfactionentries.value>0
			AND $tbnpctypes.id=$tbspawnentry.npcID
			AND $tbspawn2.spawngroupID=$tbspawnentry.spawngroupID
			AND $tbzones.short_name=$tbspawn2.zone
			GROUP BY $tbnpctypes.id
			ORDER BY $tbzones.long_name ASC
			";       
	$QueryResult = mysql_query($Query) or message_die('faction.php','MYSQL_QUERY',$query,mysql_error());
	PrintNpcsByZone($QueryResult);
	print "                </td>\n";


	// NPCs lowering the faction by killing them
	print "                <td width='50%' nowrap='1' align='left'>\n";
	print "                  <b>NPCs whom death lowers the faction</b><br/><br/>\n";
	$Query="SELECT $tbnpctypes.id,$tbnpctypes.name,$tbzones.long_name,$tbspawn2.zone
			FROM $tbnpcfactionentries,$tbnpctypes,$tbspawnentry,$tbspawn2,$tbzones
			WHERE $tbnpcfactionentries.faction_id=$id
			AND $tbnpcfactionentries.npc_faction_id=$tbnpctypes.npc_faction_id
			AND $tbnpcfactionentries.value<0
			AND $tbnpctypes.id=$tbspawnentry.npcID
			AND $tbspawn2.spawngroupID=$tbspawnentry.spawngroupID
			AND $tbzones.short_name=$tbspawn2.zone
			GROUP BY $tbnpctypes.id
			ORDER BY $tbzones.long_name ASC
			";
	$QueryResult = mysql_query($Query) or message_die('faction.php','MYSQL_QUERY',$query,mysql_error());
	PrintNpcsByZone($QueryResult);
	print "                </td>\n";

	print "              </tr>\n";
	print "            </table>\n";
	print "          </center>\n";

	include($includes_dir."footers.php");

?>
