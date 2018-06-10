<?php
/** Displays the NPC identified by 'id' if it is specified and an NPC by this ID exists.
 *  Otherwise queries for the NPCs identified by 'name'. Underscores are considered as spaces and backquotes as minuses,
 *    for Wiki-EQEmu compatibility.
 *    If exactly one NPC is found, displays this NPC.
 *    Otherwise redirects to the NPC search page, displaying the results for '%name%'.
 *  If neither 'id' nor 'name' are specified or if 'id' is not a valid NPC ID, redirects to the NPC search page.
 */

require_once('./includes/constants.php');
require_once('./includes/config.php');
require_once($includes_dir . 'functions.php');
require_once($includes_dir . 'mysql.php');

$id = (isset($_GET['id']) ? $_GET['id'] : '');
$name = (isset($_GET['name']) ? $_GET['name'] : '');

if ($id != "" && is_numeric($id) && $display_task_info == TRUE) {
    $Query = "SELECT * FROM $tasks_table WHERE id='" . $id . "'";
    $QueryResult = db_mysql_query($Query) or message_die('task.php', 'MYSQL_QUERY', $Query, mysqli_error());
    if (mysqli_num_rows($QueryResult) == 0) {
        header("Location: customzoneslist.php");
        exit();
    }
    $task = mysqli_fetch_array($QueryResult);
    $name = $task["title"];
} else {
    header("Location: customzoneslist.php");
    exit();
}

/** Here the following stands :
 *    $id : ID of the NPC to display
 *    $name : name of the NPC to display
 *    $NpcRow : row of the NPC to display extracted from the database
 *    The Task actually exists
 */

$page_title = "Task :: " . $name;


print "<p><table border='0' width='60%'>";
print "<tr><td><b>Task Title : </b></td><td width='100%'>" . $task["title"] . "</td></tr>";
print "<tr><td><b>Task ID : </b></td><td width='100%'>" . $task["id"] . "</td></tr>";
print "<tr><td><b>Min Level : </b></td><td width='100%'>" . $task["minlevel"] . "</td></tr>";
if ($task["maxlevel"] <= $server_max_level && $task["maxlevel"] != 0) {
    print "<tr><td><b>Max Level : </b></td><td width='100%'>" . $task["maxlevel"] . "</td></tr>";
}
$ZoneID = $task["startzone"];
$ZoneLongName = get_field_result("long_name", "SELECT long_name FROM zone WHERE zoneidnumber = $ZoneID");
$ZoneShortName = get_field_result("short_name", "SELECT short_name FROM zone WHERE zoneidnumber = $ZoneID");
print "<tr><td><b>Starts In : </b></td><td><a href=?a=zone&name=" . $ZoneShortName . ">" . $ZoneLongName . "</a></td></tr>";
$Reward = $task["reward"];
if ($task["rewardmethod"] == 0) {
    if ($task["rewardid"] > 0) {
        $ItemID = $task["rewardid"];
        $ItemName = get_field_result("Name", "SELECT Name FROM items WHERE id = $ItemID");
        $Reward = "<a href=?a=item&id=" . $ItemID . ">" . $ItemName . "</a>";
    }
}
if ($Reward) {
    print "<tr><td><b>Reward : </b></td><td width='100%'>" . $Reward . "</td></tr>";
}

print "<tr class='myline' height='6'><td colspan='2'></td></tr>\n";
print "<tr valign='top'></tr>";
$TaskDesc = $task["description"];
$MultiStepDesc = "";
$MultiStep = substr_count($TaskDesc, "[");
if ($MultiStep) {
    $MultiStepDesc .= "<tr><td><b>Task Description : </b></td><td width='50%'><table>";
    $StepDescArray = split("]", $TaskDesc);
    foreach ($StepDescArray as $value) {
        if ($value) {
            $MultiStepDesc .= "<tr><td>";
            $MultiStepDesc .= str_replace("[", "Step(s): ", $value);
            $MultiStepDesc = str_replace("]", "", $MultiStepDesc);
            $MultiStepDesc .= "</td></tr>";
        }
    }
    $MultiStepDesc .= "</table></td></tr>";
    print $MultiStepDesc;
} else {
    print "<tr><td><b>Task Description : </b></td><td width='50%'><p>" . $TaskDesc . "</p></td></tr>";
}

if ($display_task_activities == TRUE) {
    print "<tr class='myline' height='6'><td colspan='2'></td></tr>\n";
    print "<tr valign='top'>";
    $Query = "SELECT * FROM $activities_table WHERE taskid='" . $id . "' ORDER BY activityid";
    $QueryResult = db_mysql_query($Query) or message_die('task.php', 'MYSQL_QUERY', $Query, mysqli_error());

    if (mysqli_num_rows($QueryResult) > 0) {
        print "<tr><td><b>Task Activities</b></td></tr>";
        while ($row = mysqli_fetch_array($QueryResult)) {


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

            if ($GoalMethod == 0) {
                // Single Goal == 0
                // Goal List == 1
                // Perl Controlled Goals == 2
                $SingleGoal = $row["goalid"];
            }

            if (!$StepDescription) {

                switch ($ActivityType) {
                    case 1:
                        // Deliver
                        $StepDescription = "Deliver " . $GoalCount . " " . $Text2 . " to " . $Text1;
                        $ItemID = $SingleGoal;
                        $ItemName = $Text2;
                        $NPCID = $DeliverToNPC;
                        $NPCName = $Text1;
                        $GoalType = "ItemID";
                        break;
                    case 2:
                        // Kill
                        $StepDescription = "Kill " . $GoalCount . " " . $Text1;
                        $NPCID = $SingleGoal;
                        $NPCName = $Text1;
                        $GoalType = "NPCID";
                        break;
                    case 3:
                        // Loot
                        $StepDescription = "Loot " . $GoalCount . " " . $Text2 . " from " . $Text1;
                        $ItemID = $SingleGoal;
                        $ItemName = $Text2;
                        $GoalType = "ItemID";
                        break;
                    case 4:
                        // SpeakWith
                        $StepDescription = "Speak with " . $Text1;
                        $NPCID = $SingleGoal;
                        $NPCName = $Text1;
                        $GoalType = "NPCID";
                        break;
                    case 5:
                        // Explore
                        $StepDescription = "Explore " . $Text1;
                        break;
                    case 6:
                        // TradeSkill
                        $StepDescription = "Create " . $GoalCount . " " . $Text1;
                        $ItemID = $SingleGoal;
                        $ItemName = $Text1;
                        $GoalType = "ItemID";
                        break;
                    case 7:
                        // Fish
                        $StepDescription = "Fish " . $GoalCount . " " . $Text1;
                        $ItemID = $SingleGoal;
                        $ItemName = $Text1;
                        $GoalType = "ItemID";
                        break;
                    case 8:
                        // Forage
                        $StepDescription = "Forage " . $GoalCount . " " . $Text1;
                        $ItemID = $SingleGoal;
                        $ItemName = $Text1;
                        $GoalType = "ItemID";
                        break;
                    case 9:
                        // ActivityUse1
                        $StepDescription = "Use " . $GoalCount . " " . $Text1;
                        break;
                    case 10:
                        // ActivityUse2
                        $StepDescription = "Use " . $GoalCount . " " . $Text1;
                        break;
                    case 11:
                        // ActivityTouch
                        $StepDescription = "Touch " . $Text1;
                        break;
                    case 100:
                        // ActivityGiveCash
                        $StepDescription = "Give " . $GoalCount . " " . $Text1 . " to " . $Text2;
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
            if ($Optional) {
                $StepDescription .= " (Optional)";
            }

            $ZoneID = $row["zoneid"];
            if ($ZoneID == 0) {
                $ZoneName = "Any Zone";
            } else {
                $ZoneLongName = get_field_result("long_name", "SELECT long_name FROM zone WHERE zoneidnumber = $ZoneID");
                $ZoneShortName = get_field_result("short_name", "SELECT short_name FROM zone WHERE zoneidnumber = $ZoneID");
                $ZoneName = "<a href=?a=zone&name=" . $ZoneShortName . ">" . $ZoneLongName . "</a>";
            }
            print "<tr><td colspan='2' nowrap='1'><ul><li>Step " . ($row["activityid"] + 1) . ": $StepDescription - Zone: $ZoneName</li><ul>";
            // Single Goals
            if ($GoalMethod == 0) {
                if ($NPCID > 0) {
                    print "<li>Related NPC: <a href=?a=npc&id=" . $NPCID . ">" . $NPCName . "</a></li>";
                }
                if ($ItemID > 0) {
                    print "<li>Related Item: <a href=?a=item&id=" . $ItemID . ">" . $ItemName . "</a></li>";
                }
            }
            // Goal List
            if ($GoalMethod == 1) {
                $Query2 = "SELECT * FROM goallists WHERE listid='" . $GoalID . "'";
                $QueryResult2 = db_mysql_query($Query2) or message_die('task.php', 'MYSQL_QUERY', $Query2, mysqli_error());
                $GoalListString = "";
                if (mysqli_num_rows($QueryResult2) > 0) {

                    while ($row2 = mysqli_fetch_array($QueryResult2)) {

                        if ($GoalType == "NPCID" && $row2["entry"]) {
                            $NPCID = $row2["entry"];
                            $NPCName = get_field_result("name", "SELECT name FROM npc_types WHERE id = $NPCID");
                            $GoalListString .= "<li>Related NPC: <a href=?a=npc&id=" . $NPCID . ">" . get_npc_name_human_readable($NPCName) . "</a></li>";
                        }
                        if ($GoalType == "ItemID" && $row2["entry"]) {
                            $ItemID = $row2["entry"];
                            $ItemName = get_field_result("name", "SELECT name FROM items WHERE id = $ItemID");
                            $GoalListString .= "<li>Related Item: <a href=?a=item&id=" . $ItemID . ">" . $ItemName . "</a></li>";
                        }
                    }
                }
                print $GoalListString;
            }
            print "</ul></ul></td></tr>";
        }
    } else {
        print "<tr><td><b>No Task Activities Listed</b></td></tr>";
    }

}
print "</table></p>";


?>
