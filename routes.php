<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 9/18/2016
 * Time: 6:19 PM
 */

if(file_exists("cache/" . $_SERVER['QUERY_STRING'])){
    echo bzdecompress(file_get_contents("cache/" . $_SERVER['QUERY_STRING']));
}
else {
    $route = $_GET['a'];
    if($route == "spells"){ require_once('pages/spells/spells.php'); }
    if($route == "spell"){ require_once('pages/spells/spell.php'); }
    if($route == "item"){ require_once('pages/items/item.php'); }
    if($route == "pets"){ require_once('pages/pets/pets.php'); }
    if($route == "zonelist"){ require_once('pages/zones/zonelist.php'); }
    if($route == "items"){ require_once('pages/items/items.php'); }
    if($route == "factions"){ require_once('pages/factions/factions.php'); }
    if($route == "faction"){ require_once('pages/factions/faction.php'); }
    if($route == "pet"){ require_once('pages/pets/pet.php'); }
    if($route == "zones_by_level"){ require_once('pages/zones/zones_by_level.php'); }
    if($route == "zone"){ require_once('pages/zones/zone.php'); }
    if($route == "npc"){ require_once('pages/npcs/npc.php'); }
    if($route == "recipe"){ require_once('pages/tradeskills/recipe.php'); }
    if($route == "recipes"){ require_once('pages/tradeskills/recipes.php'); }
    if($route == "zones"){ require_once('pages/zones/zones.php'); }
    if($route == "zone_named"){ require_once('pages/zones/zone_named.php'); }
    if($route == "npcs"){ require_once('pages/npcs/npcs.php'); }
    if($route == "advanced_npcs"){ require_once('pages/npcs/advanced_npcs.php'); }
    if($route == "zone_era") {
        echo '<table class=\'display_table container_div\'><tr><td>';
        echo "<h2 class='section_header'>Zones</h2><br>";
        require_once('pages/zones/zones_by_era/' . $_GET['era'] . '.php');
        echo '</td></tr></table>';
    }
}

?>