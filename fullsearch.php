<?php
/** If no proper query is specified (see below), redirects to the item search page (error).
 *  If the parameter 'isearchtype' equals 'id' and 'iid' is set then queries for objects with exactly this ID and displays them.
 *  If the parameter 'isearchtype' equals 'name' and 'iname' is set then queries for objects with approximately this name and displays them.
 *  At the moment the object types supported are factions, NPCs and items.
 */
include('./includes/constantes.php');
include('./includes/config.php');
include($includes_dir.'mysql.php');
include($includes_dir.'functions.php');

$iid         = (isset($_GET[        'iid']) ? addslashes($_GET[        'iid']) : '');
$iname       = (isset($_GET[      'iname']) ? addslashes($_GET[      'iname']) : '');
$isearchtype = (isset($_GET['isearchtype']) ? addslashes($_GET['isearchtype']) : '');

// Build the WHERE caluse
$Where = "";
if($isearchtype == 'id' and $iid != "")
  $Where = "id='".$iid."'";
if($isearchtype == 'name' and $iname != "")
  $Where = "name like '%".str_replace('`', '%', str_replace('_', '%', str_replace(' ', '%', $iname)))."%'";

if($Where == "")
{
	header("Location: items.php");
	exit();
}

// Query for factions
$Query="SELECT $tbfactionlist.id,$tbfactionlist.name
        FROM $tbfactionlist
        WHERE $Where
        ORDER BY $tbfactionlist.name,$tbfactionlist.id
        LIMIT ".(LimitToUse($MaxFactionsReturned) + 1);
$FoundFactions = mysql_query($Query) or message_die('fullsearch.php','MYSQL_QUERY',$Query,mysql_error());

// Query for Items
if ($DiscoveredItemsOnly==TRUE)
{
		$Query = "SELECT * FROM $tbitems, discovered_items WHERE $tbitems.id='".$id."' AND discovered_items.item_id=$tbitems.id";
	$Query="SELECT $tbitems.id,$tbitems.name
		FROM $tbitems, discovered_items
		WHERE $Where
		AND discovered_items.item_id=$tbitems.id 
		ORDER BY $tbitems.name,$tbitems.id
		LIMIT ".(LimitToUse($MaxItemsReturned) + 1);
}
else
{
	$Query="SELECT $tbitems.id,$tbitems.name
		FROM $tbitems
		WHERE $Where
		ORDER BY $tbitems.name,$tbitems.id
		LIMIT ".(LimitToUse($MaxItemsReturned) + 1);
}
$FoundItems = mysql_query($Query) or message_die('fullsearch.php','MYSQL_QUERY',$Query,mysql_error());

// Query for NPCs
$Query="SELECT $tbnpctypes.id,$tbnpctypes.name
        FROM $tbnpctypes
        WHERE $Where
        ORDER BY $tbnpctypes.name,$tbnpctypes.id
        LIMIT ".(LimitToUse($MaxNpcsReturned) + 1);
$FoundNpcs = mysql_query($Query) or message_die('fullsearch.php','MYSQL_QUERY',$Query,mysql_error());


// In case only one object is found, redirect to its page
if(     mysql_num_rows($FoundFactions) == 1
    and mysql_num_rows($FoundItems)    == 0
    and mysql_num_rows($FoundNpcs)     == 0
  )
{ $FactionRow = mysql_fetch_array($FoundFactions);
   header("Location: faction.php?id=".$FactionRow["id"]);
  exit();
}

if(     mysql_num_rows($FoundFactions) == 0
    and mysql_num_rows($FoundItems)    == 1
    and mysql_num_rows($FoundNpcs)     == 0
  )
{ $ItemRow = mysql_fetch_array($FoundItems);
   header("Location: item.php?id=".$ItemRow["id"]);
  exit();
}

if(     mysql_num_rows($FoundFactions) == 0
    and mysql_num_rows($FoundItems)    == 0
    and mysql_num_rows($FoundNpcs)     == 1
  )
{ $NpcRow = mysql_fetch_array($FoundNpcs);
   header("Location: npc.php?id=".$NpcRow["id"]);
  exit();
}


/** Here the following holds :
 *    $FoundFactions : factions found
 *    $FoundItems    : items    found
 *    $FoundNpcs     : NPCs     found
 */

$Title="Search Results";
$XhtmlCompliant = TRUE;
include($includes_dir.'headers.php');

// Display found objects
print "          <table border='0' width='100%'>\n";
print "            <tr valign='top'>\n";
print "              <td nowrap='1' width='34%'>\n";
PrintQueryResults($FoundItems,       $MaxItemsReturned,    "item.php",    "item",    "items", "id", "name");
print "              </td>\n";
print "              <td nowrap='1' width='33%'>\n";
PrintQueryResults($FoundNpcs,         $MaxNpcsReturned,     "npc.php",     "NPC",     "NPCs", "id", "name");
print "              </td>\n";
print "              <td nowrap='1' width='33%'>\n";
PrintQueryResults($FoundFactions, $MaxFactionsReturned, "faction.php", "faction", "factions", "id", "name");
print "              </td>\n";
print "            </tr>\n";
print "          </table>\n";


include($includes_dir."footers.php");
?>
