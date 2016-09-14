<?php

	print "<table border='0' width='100%'><form name='fullsearch' method='GET' action='fullsearch.php'>\n";

	// Main section
	print "<tr><td class='menuh' nowrap='1'>Main...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."index.php'>AllaClone Main Page</a></li></td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='http://www.eqemulator.org'>EQEmulator</a></li></td></tr>\n";
	if($EnableNews)
	{
		print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."news.php'>Server News</a></li></td></tr>\n";
	}

	// Name search
	print "<tr><td class='menuh' nowrap='1'>Search...<input type='hidden' name='isearchtype' value=''/></td></tr>\n";
	print "<tr><td><input onfocus=\"if(this.value == 'Name...') { this.value = ''; }\" onkeypress=\"var key=event.keyCode || event.which; if(key==13){ this.form.isearchtype.value = 'name'; this.form.submit(); } else {return true;}\" type='text' name='iname' placeholder='Name...' size='20'/></td></tr>\n";
	print "<tr><td><input onfocus=\"if(this.value == 'ID...') { this.value = ''; }\" onkeypress=\"var key=event.keyCode || event.which; if(key==13){ this.form.isearchtype.value = 'id'; this.form.submit(); } else {return true;}\" type='text' name='iid' placeholder='ID...' size='10'/></td></tr>\n";

	// Zones section
	print "<tr><td class='menuh' nowrap='1'>Zones...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."zonelist.php'>Zones by Era</a></li></td></tr>\n";
	if ($UseCustomZoneList==TRUE)
	{
		print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."customzoneslist.php'>Custom Zone List</a></li></td></tr>\n";
	}
	else
	{
		print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."zones.php'>Populated Zones</a></li></td></tr>\n";
		print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."zoneslevels.php'>Zones by Level</a></li></td></tr>\n";
	}
	// Items section
	print "<tr><td class='menuh' nowrap='1'>Items...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."items.php'>Item Search</a></li></td></tr>\n";

	// Spells section
	print "<tr><td class='menuh' nowrap='1'>Spells...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."spells.php'>Spell Search</a></li></td></tr>\n";

	// Factions section
	print "<tr><td class='menuh' nowrap='1'>Factions...</td></tr>\n";
	print "<tr><td class='menu_item' nowrap='1'><li><a href='".$root_url."factions.php'>Faction Search</a></li></td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."npcfactions.php'>NPCs By Faction</a></li></td></tr>\n";

	// Bestiary eection
	print "<tr><td class='menuh' nowrap='1'>Bestiary...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."npcs.php'>NPC Search</a></li></td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."advnpcs.php'>Advanced NPC Search</a></li></td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."pets.php'>Pets</a></li></td></tr>";

	// Trade Skills section
	print "<tr><td class='menuh' nowrap='1'>Trade skills...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."recipes.php'>Recipe Search</a></li></td></tr>\n";

	// Strategy section
	print "<tr><td class='menuh' nowrap='1'>Strategy...</td></tr>\n";
	print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."strategy/melee.php'>Melee Damage Calculator</a></li></td></tr>\n";

	// Quests section
	if($AllowQuestsNPC)
	{
		print "<tr><td class='menuh' nowrap='1'>Quests...</td></tr>\n";
		print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."quests/zones.php'>Quests by Zone</a></li></td></tr>\n";
	}

	// Players Database section
	if($ShowCharacters || $ShowAccount)
	{
		print "<tr><td class='menuh' nowrap='1'>Players database...</td></tr>\n";
		if($ShowAccounts)
		{
			print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."accounts.php'>Player Accounts</a></li></td></tr>\n";
		}
		if($ShowCharacters)
		{
			print "<tr><td nowrap='1' class='menu_item'><li><a href='".$root_url."chars.php'>Player Characters</a></li></td></tr>\n";
		}
	}
	  
	print "</form></table>\n";

	if($UseZAMSearch)
	{
		echo '<br><script type="text/javascript">var zam_searchbox_site = "everquest"; var zam_searchbox_format = "160x130"</script>
			<script type="text/javascript" src="http://zam.zamimg.com/j/searchbox.js"></script></align>';
	}

?>
