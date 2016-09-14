<?php

	include('./includes/constantes.php');
	include('./includes/config.php');
	include($includes_dir.'mysql.php');
	include($includes_dir.'functions.php');
	include($includes_dir.'blobs.inc.php');

	$class   = (isset($_GET['class']) ? $_GET['class'] : 0);
	
	if(!is_numeric($class))
	{
		header("Location: pets.php");
		exit();
	}

	$Title="Pets Statistics";
	include($includes_dir.'headers.php');

	print "<table border=0 width=100%><tr valign=top><td nowrap>";
	
	CreateToolTip('class15', '<table> <td> <img src="images/monograms/15.gif"> </td> <td> The Beastlord class is a unique class that is a hybrid of the Shaman and Monk parent classes. One of the class\'s distinguishing features is the ability to summon a warder pet to fight alongside them. The form the warder takes depends on the race of the beastlord. </td>');
	CreateToolTip('class6', '<table> <td> <img src="images/monograms/6.gif"> </td> <td> Druids - a powerful outdoor class. With the triple ability to heal, inflict direct damage and cast damage over time, druids are a popular solo class, as well as popular in a group. They are also a travelling class, given both sow and teleportation abilities.  </td>');
	CreateToolTip('class14', '<table> <td> <img src="images/monograms/14.gif"> </td> <td> Enchanter - the game\'s traffic cop. When the enchanter yells stop, creatures cease what they are doing and just wait to die. A complex class, enchanters get a variety of spells as well as a pet, and can be played in a number of different ways. </td>');
	CreateToolTip('class13', '<table> <td> <img src="images/monograms/13.gif"> </td> <td> Magicians - Dealers of damage. With the combination of a strong pet and the second best set of direct damage spells, magicians can deal out some serious damage.  </td>');
	CreateToolTip('class11', '<table><td><img src="images/monograms/11.gif"> </td> <td> Necromancers - Masters of death. With their powerful pets and variety of damage over time spells, necromancers are one of the best solo classes in the game. </td>');
	CreateToolTip('class5', '<table> <td> <img src="images/monograms/5.gif"> </td> <td> Shadowknights - The evil warriors. A combination of fighter and necromancer, the shadowknight is a complex class to develop and play correctly.  </td>'); 
	CreateToolTip('class10', '<table> <td> <img src="images/monograms/10.gif"> </td> <td> Shamen - primitive power. The only magic using class of the barbarians, ogres and trolls, shamen get a variety of spells that combine aspects of most of the other magic using classes. This combination, along with the racial strength, means the class can be played in a variety of different ways.  </td>');
	CreateToolTip('class2', '<table> <td> <img src="images/monograms/2.gif"> </td> <td> Clerics -- the premier healer in the game. A popular class for any group, the cleric brings the ability to heal the party and keep you from dying, and can even bring you back from death at higher levels.   </td>');
	CreateToolTip('class12', '<table> <td> <img src="images/monograms/12.gif"> </td> <td> S	The Wizard - Master Nuker. The wizard\'s abilities are in direct damage spells and in individual and group teleportation.   </td>');
	print "<table border=0 width=0%><tr valign=top><td nowrap>";
	print "<b>Choose a class:</b><ul>";
	print "<li><a href=$PHP_SELF?class=15 id='class15'>Beastlord</a>";
	print "<li><a href=$PHP_SELF?class=2  id='class2'>Cleric</a>";
	print "<li><a href=$PHP_SELF?class=6  id='class6'>Druid</a>";
	print "<li><a href=$PHP_SELF?class=14  id='class14'>Enchanter</a>";
	print "<li><a href=$PHP_SELF?class=13  id='class13'>Magician</a>";
	print "<li><a href=$PHP_SELF?class=11  id='class11'>Necromancer</a>";
	print "<li><a href=$PHP_SELF?class=5  id='class5'>Shadow knight</a>";
	print "<li><a href=$PHP_SELF?class=10  id='class10'>Shaman</a>";
	print "<li><a href=$PHP_SELF?class=12  id='class12'>Wizard</a>";
	print "</ul>";
	print "</td></tr></table></td><td nowrap>";

	if (isset($class) && $class != 0)
	{
		$Query = "SELECT
			$tbspells.name,
			$tbspells.id,
			$tbspells.new_icon,
			$tbspells.teleport_zone,
			$tbspells.classes". $class .",
			$tbnpctypes.race,
			$tbnpctypes.level,
			$tbnpctypes.class,
			$tbnpctypes.hp,
			$tbnpctypes.mana,
			$tbnpctypes.AC,
			$tbnpctypes.mindmg,
			$tbnpctypes.maxdmg
			FROM
			$tbspells 
			INNER JOIN $tbpets ON $tbpets.type = $tbspells.teleport_zone
			INNER JOIN $tbnpctypes ON $tbnpctypes.name = $tbspells.teleport_zone
			WHERE $tbspells.classes". $class ." > 0 
			AND $tbspells.classes". $class ." < " . $ServerMaxLevel;
			
		if ($UseSpellGlobals==TRUE)
		{
			$Query .= " AND ISNULL((SELECT spell_globals.spellid FROM spell_globals WHERE spell_globals.spellid = $tbspells.`id`))";
		}
		$Query .= " GROUP BY $tbspells.`teleport_zone` ORDER BY $tbspells.classes". $class;
		$result = mysql_query($Query);
		if (!$result)
		{
			print 'Could not run query: ' . mysql_error();
			exit;
		}
		$columns = mysql_num_fields($result);
		print "<center><table border=0 width=100%><thead>";
		print "<th class='menuh'>Level</th>";
		print "<th class='menuh'>Icon</th>";
		print "<th class='menuh'>Spell Name</th>";
		print "<th class='menuh'>Details</th>";
		print "<th class='menuh'>Race</th>";
		print "<th class='menuh'>Pet Level</th>";
		print "<th class='menuh'>Pet Class</th>";
		print "<th class='menuh'>HP</th>";
		print "<th class='menuh'>Mana</th>";
		print "<th class='menuh'>AC</th>";
		print "<th class='menuh'>Min Damage</th>";
		print "<th class='menuh'>Max Damage</th>";
		
		
		
		$RowClass = "lr";
		print "</tr></thead><tbody>";
		while($row = mysql_fetch_array($result))
		{
			print "<tr class='".$RowClass."'>";
			print "<td>" . $row["classes". $class] . "</td>";
			print "<td><img src='". $icons_url . $row["new_icon"] . ".gif' align='center' border='1' width='20' height='20'></td>";
			print "<td><a href='spell.php?id=". $row['id'] . "'>  ". $row['name'] . " </a></td>";
			print "<td><a href='pet.php?name=". $row['teleport_zone'] . "'>View</a></td>";
			print "<td>" . $dbiracenames[$row["race"]] . "</td>";
			print "<td>" . $row["level"] . "</td>";
			print "<td>" . $row["class"] . "</td>";
			print "<td>" . $row["hp"] . "</td>";
			print "<td>" . $row["mana"] . "</td>";
			print "<td>" . $row["ac"] . "</td>";
			print "<td>" . $row["mindmg"] . "</td>";
			print "<td>" . $row["maxdmg"] . "</td>";
			print "</tr>";
			
			if ($RowClass == "lr")
			{
				$RowClass = "dr";
			}
			else
			{
				$RowClass = "lr";
			}	
		}
		print "</tbody></table></center>";
	
	}

	print "</td></tr></table>";

	include($includes_dir."footers.php");

?>