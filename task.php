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

$id   = (isset($_GET[  'id']) ? $_GET[  'id'] : '');
$name = (isset($_GET['name']) ? $_GET['name'] : '');

if($id != "" && is_numeric($id)&& $DisplayTaskInfo==TRUE)
{
	$Query = "SELECT * FROM $tbtasks WHERE id='".$id."'";
	$QueryResult = mysql_query($Query) or message_die('task.php','MYSQL_QUERY',$Query,mysql_error());
	if(mysql_num_rows($QueryResult) == 0)
	{
		header("Location: customzoneslist.php");
		exit();
	}
	$task=mysql_fetch_array($QueryResult);
	$name=$task["title"];
}
else
{
	header("Location: customzoneslist.php");
	exit();
}

/** Here the following stands :
 *    $id : ID of the NPC to display
 *    $name : name of the NPC to display
 *    $NpcRow : row of the NPC to display extracted from the database
 *    The Task actually exists
 */

$Title="Task :: ".$name;
$XhtmlCompliant = TRUE;
include($includes_dir.'headers.php');

print "<p><center><table border='0' width='60%'>";
print "<tr><td nowrap='1'><b>Task Title : </b></td><td width='100%'>".$task["title"]."</td></tr>";
print "<tr><td nowrap='1'><b>Task ID : </b></td><td width='100%'>".$task["id"]."</td></tr>";
print "<tr><td nowrap='1'><b>Min Level : </b></td><td width='100%'>".$task["minlevel"]."</td></tr>";
if ($task["maxlevel"] <= $ServerMaxLevel && $task["maxlevel"] != 0)
{
	print "<tr><td nowrap='1'><b>Max Level : </b></td><td width='100%'>".$task["maxlevel"]."</td></tr>";
}
$ZoneID = $task["startzone"];
$ZoneLongName = GetFieldByQuery("long_name","SELECT long_name FROM zone WHERE zoneidnumber = $ZoneID");
$ZoneShortName = GetFieldByQuery("short_name","SELECT short_name FROM zone WHERE zoneidnumber = $ZoneID");
print "<tr><td nowrap='1'><b>Starts In : </b></td><td><a href=zone.php?name=".$ZoneShortName.">".$ZoneLongName."</a></td></tr>";
$Reward = $task["reward"];
if ($task["rewardmethod"] == 0)
{
	if ($task["rewardid"] > 0)
	{
		$ItemID = $task["rewardid"];
		$ItemName = GetFieldByQuery("Name","SELECT Name FROM items WHERE id = $ItemID");
		$Reward = "<a href=item.php?id=".$ItemID.">".$ItemName."</a>";
	}
}
if ($Reward)
{
	print "<tr><td nowrap='1'><b>Reward : </b></td><td width='100%'>".$Reward."</td></tr>";
}

print "<tr class='myline' height='6'><td colspan='2'></td></tr>\n";
print "<tr valign='top'></tr>";
$TaskDesc = $task["description"];
$MultiStepDesc = "";
$MultiStep = substr_count($TaskDesc, "[");
if ($MultiStep)
{
	$MultiStepDesc .=  "<tr><td nowrap='1'><b>Task Description : </b></td><td width='50%'><table>";
	$StepDescArray = split("]", $TaskDesc);
	foreach ($StepDescArray as $value)
	{
		if ($value)
		{
			$MultiStepDesc .= "<tr><td>";
			$MultiStepDesc .= str_replace("[" , "Step(s): " , $value);
			$MultiStepDesc = str_replace("]" , "" , $MultiStepDesc);
			$MultiStepDesc .= "</td></tr>";
		}
	}
	$MultiStepDesc .=  "</table></td></tr>";
	print $MultiStepDesc;
}
else
{
	print "<tr><td nowrap='1'><b>Task Description : </b></td><td width='50%'><p>".$TaskDesc."</p></td></tr>";
}

if ($DisplayTaskActivities==TRUE)
{
	print "<tr class='myline' height='6'><td colspan='2'></td></tr>\n";
	print "<tr valign='top'>";
	$Query = "SELECT * FROM $tbtaskactivities WHERE taskid='".$id."' ORDER BY activityid";
	$QueryResult = mysql_query($Query) or message_die('task.php','MYSQL_QUERY',$Query,mysql_error());

	if (mysql_num_rows($QueryResult) > 0)
	{
		print "<tr><td><b>Task Activities</b></td></tr>";
		while ($row=mysql_fetch_array($QueryResult))
		{
			
			
			$Text1 = $row["text1"];
			$Text2 = $row["text2"];
			$GoalID = $row["goalid"];
			$GoalMethod = $row["goalmethod"];
			$GoalCount = $row["goalcount"];
			$DeliverToNPC = $row["delivertonpc"];
			$Optional = $row["optional"];
			$ActivityType = $row["activitytype"];
			$ItemID = 0;
			$NPCID = 0;
			$StepDescription = $row["text3"];
			$SingleGoal = 0;
			$ItemName = "";
			$NPCName = "";
			$GoalType = "";
			
			if ($GoalMethod == 0)
			{
				// Single Goal == 0
				// Goal List == 1
				// Perl Controlled Goals == 2
				$SingleGoal = $row["goalid"];
			}

			if (!$StepDescription)
			{

				switch ($ActivityType)
				{
					case 1:
						// Deliver
						$StepDescription = "Deliver ".$GoalCount." ".$Text2." to ".$Text1;
						$ItemID = $SingleGoal;
						$ItemName = $Text2;
						$NPCID = $DeliverToNPC;
						$NPCName = $Text1;
						$GoalType = "ItemID";
						break;
					case 2:
						// Kill
						$StepDescription = "Kill ".$GoalCount." ".$Text1;
						$NPCID = $SingleGoal;
						$NPCName = $Text1;
						$GoalType = "NPCID";
						break;
					case 3:
						// Loot
						$StepDescription = "Loot ".$GoalCount." ".$Text2." from ".$Text1;
						$ItemID = $SingleGoal;
						$ItemName = $Text2;
						$GoalType = "ItemID";
						break;
					case 4:
						// SpeakWith
						$StepDescription = "Speak with ".$Text1;
						$NPCID = $SingleGoal;
						$NPCName = $Text1;
						$GoalType = "NPCID";
						break;
					case 5:
						// Explore
						$StepDescription = "Explore ".$Text1;
						break;
					case 6:
						// TradeSkill
						$StepDescription = "Create ".$GoalCount." ".$Text1;
						$ItemID = $SingleGoal;
						$ItemName = $Text1;
						$GoalType = "ItemID";
						break;
					case 7:
						// Fish
						$StepDescription = "Fish ".$GoalCount." ".$Text1;
						$ItemID = $SingleGoal;
						$ItemName = $Text1;
						$GoalType = "ItemID";
						break;
					case 8:
						// Forage
						$StepDescription = "Forage ".$GoalCount." ".$Text1;
						$ItemID = $SingleGoal;
						$ItemName = $Text1;
						$GoalType = "ItemID";
						break;
					case 9:
						// ActivityUse1
						$StepDescription = "Use ".$GoalCount." ".$Text1;
						break;
					case 10:
						// ActivityUse2
						$StepDescription = "Use ".$GoalCount." ".$Text1;
						break;
					case 11:
						// ActivityTouch
						$StepDescription = "Touch ".$Text1;
						break;
					case 100:
						// ActivityGiveCash
						$StepDescription = "Give ".$GoalCount." ".$Text1." to ".$Text2;
						$NPCID = $DeliverToNPC;
						$NPCName = $Text2;
						$GoalType = "NPCID";
						break;
					case 255:
						// Custom Task Activity Type
						$StepDescription = $Text3;
						break;
					default:
						// Custom Task Activity Type
						$StepDescription = $Text3;
						break;
				}
			}
			if ($Optional)
			{
				$StepDescription .= " (Optional)";
			}
			
			$ZoneID = $row["zoneid"];
			if ($ZoneID == 0)
			{
				$ZoneName = "Any Zone";
			}
			else
			{
				$ZoneLongName = GetFieldByQuery("long_name","SELECT long_name FROM zone WHERE zoneidnumber = $ZoneID");
				$ZoneShortName = GetFieldByQuery("short_name","SELECT short_name FROM zone WHERE zoneidnumber = $ZoneID");
				$ZoneName = "<a href=zone.php?name=".$ZoneShortName.">".$ZoneLongName."</a>";
			}
			print "<tr><td colspan='2' nowrap='1'><ul><li>Step ".($row["activityid"] + 1).": $StepDescription - Zone: $ZoneName</li><ul>";
			// Single Goals
			if ($GoalMethod == 0)
			{
				if ($NPCID > 0)
				{
					print "<li>Related NPC: <a href=npc.php?id=".$NPCID.">".$NPCName."</a></li>";
				}
				if ($ItemID > 0)
				{
					print "<li>Related Item: <a href=item.php?id=".$ItemID.">".$ItemName."</a></li>";
				}
			}
			// Goal List
			if ($GoalMethod == 1)
			{
				$Query2 = "SELECT * FROM goallists WHERE listid='".$GoalID."'";
				$QueryResult2 = mysql_query($Query2) or message_die('task.php','MYSQL_QUERY',$Query2,mysql_error());
				$GoalListString = "";
				if (mysql_num_rows($QueryResult2) > 0)
				{
					
					while ($row2=mysql_fetch_array($QueryResult2))
					{
						
						if ($GoalType == "NPCID" && $row2["entry"])
						{
							$NPCID = $row2["entry"];
							$NPCName = GetFieldByQuery("name","SELECT name FROM npc_types WHERE id = $NPCID");
							$GoalListString .= "<li>Related NPC: <a href=npc.php?id=".$NPCID.">".ReadableNpcName($NPCName)."</a></li>";
						}
						if ($GoalType == "ItemID" && $row2["entry"])
						{
							$ItemID = $row2["entry"];
							$ItemName = GetFieldByQuery("name","SELECT name FROM items WHERE id = $ItemID");
							$GoalListString .= "<li>Related Item: <a href=item.php?id=".$ItemID.">".$ItemName."</a></li>";
						}
					}
				}
				print $GoalListString;
			}
			print "</ul></ul></td></tr>";
		}
	}
	else
	{
		print "<tr><td nowrap='1'><b>No Task Activities Listed</b></td></tr>";
	}

}
print "</table></center></p>";

include($includes_dir."footers.php");

?>
