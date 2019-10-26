<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 9/18/2016
 * Time: 6:19 PM
 */

$route = $_GET['a'];
if ($route == "spells") {
    require_once('pages/spells/spells.php');
} else if ($route == "spell") {
    require_once('pages/spells/spell.php');
} else if ($route == "item") {
    require_once('pages/items/item.php');
} else if ($route == "pets") {
    require_once('pages/pets/pets.php');
} else if ($route == "zonelist") {
    require_once('pages/zones/zonelist.php');
} else if ($route == "items") {
    require_once('pages/items/items.php');
} else if ($route == "task") {
    require_once('pages/tasks/task.php');
}  else if ($route == "tasks") {
    require_once('pages/tasks/tasks.php');
} /*else if ($route == "factions") {
    require_once('pages/factions/factions.php');
} else if ($route == "faction") {
    require_once('pages/factions/faction.php');
} */ else if ($route == "pet") {
    require_once('pages/pets/pet.php');
} else if ($route == "zones_by_level") {
    require_once('pages/zones/zones_by_level.php');
} else if ($route == "zone") {
    require_once('pages/zones/zone.php');
} else if ($route == "npc") {
    require_once('pages/npcs/npc.php');
} else if ($route == "spawngroup") {
    require_once('pages/npcs/spawngroup.php');
} else if ($route == "spawngroups") {
    require_once('pages/npcs/spawngroups.php');
} else if ($route == "recipe") {
    require_once('pages/tradeskills/recipe.php');
} else if ($route == "recipes") {
    require_once('pages/tradeskills/recipes.php');
} else if ($route == "zones") {
    require_once('pages/zones/zones.php');
} else if ($route == "zone_named") {
    require_once('pages/zones/zone_named.php');
} else if ($route == "npcs") {
    require_once('pages/npcs/npcs.php');
} /*else if ($route == "advanced_npcs") {
    require_once('pages/npcs/advanced_npcs.php');
} else if ($route == "global_search") {
    require_once('pages/global_search.php');
} */ else if ($route == "zone_era") {
	require_once('pages/zones/zone_era.php');
} else {
    if (file_exists('pages/front_page.php')) {
        require_once('pages/front_page.php');
    } else {
        echo '
            <h2>Welcome to ' . $site_name . ' EQEmu Allakhazam!</h2>
            <br>
            Get started with the menu on the left!
        ';
    }
}


?>