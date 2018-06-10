<?php

/**
 * PHP Debugging
 */
$php_debug = false;

error_reporting(0);
if ($php_debug) {
    error_reporting(1);
    ini_set('display_errors', 1);
}

/**
 * Basic site info
 */
$site_title  = 'EQEmu Server AllaClone';

/**
 * MySQL Config
 */
$db_host     = "mariadb";
$db_name     = "peq";
$db_user     = "root";
$db_password = "root";

/**
 * Options
 */
$use_pace_loader = true; /* shows a loader at the top of the page */
$hide_navbar     = false;

$site_logo = ""; /* Where you put your site logo */

$root_url     = 'http://localhost/';
$includes_url = $root_url . 'includes/';
$includes_dir = getcwd() . "/includes/";
$eqemu_dir    = "/home/eqemu/server/";
$quests_dir   = $eqemu_dir . "quests/";
$quests_datas = "/home/eqemu/server/quests/";
$maps_dir     = getcwd() . "/maps/";
$maps_url     = $root_url . "/maps/";
$npcs_dir     = getcwd() . "/npcs/";
$npcs_url     = $root_url . "/npcs/";
$icons_dir    = getcwd() . "/images/icons/";
$icons_url    = $root_url . "/images/icons/";
$images_url   = $root_url . "/images/";

/* Options */

/**
 * NPC
 */
$allow_csv_output_options           = false; // If TRUE, will display CSV output button for searches that allow them
$allow_quests_npc                   = false; // quests for npcs are available from NPC's page
$display_named_npcs_info            = true; // If TRUE, will display a link to show named NPCs and export to CVS for maps
$display_npc_stats                  = true; // If TRUE, will display HP, Damage, Special Attacks, etc for NPCs
$display_spawn_group_info           = true; // If TRUE, will display the spawngroup link from the zone pages
$group_npcs_by_name                 = true; // groups the NPCs by their name in zone npcs lists
$max_npcs_returned                  = 50; // max number of npcs returned by search engines (0=all)
$server_max_npc_level               = 95; // Max Level for any NPCs on the Server
$show_npc_drop_chances              = true; // if TRUE, chances of droping of each item will be shown when browsing npcs
$show_npc_number_in_zone_level_list = true; // choose between number and x for npcs in zone levels list
$show_npcs_attack_speed             = true; // shows informations about NPCs' attack speed
$show_npcs_average_damages          = true; // How much NPC does as melee damages, in average, this allows to comparate mobs together
$spawngroup_around_range            = 100; // range of surrounding spawngroups, spawngroups page
$trackable_npcs_only                = true; // If TRUE, will only display NPCs that are set to be trackable in search results
$hide_invisible_men                 = true; // hide the invisible mens in bestiaries

/**
 * Items
 */
$item_add_chance_to_drop     = true; // shows what are the chances to see the item droped by the npcs
$discovered_items_max_status = 20; // Max account status for a discovered item entry
$discovered_items_only       = false; // If TRUE, only Discovered Items will be displayed
$max_items_returned          = 50; // max number of items returned by search engines (0=all)

/**
 * This can increase page load times up to 3-6 seconds without caching
 */
$item_found_info = true; // If TRUE, it displays where items can drop or be purchased (longer item page load times)

/**
 * Tasks
 */
$display_task_activities = true; // If TRUE, will display all task activities
$display_task_info       = true; // If TRUE, will allow search results to show tasks and rewards

/**
 * Misc
 */
$max_recently_discovered_items = 25; // Max number of recently discovered items to display
$merchants_dont_drop_stuff     = true; // if TRUE, you won't see merchants drops, damned PEQ world builders ! :)
$server_max_level              = 80; // Max Level for Characters on the Server
$sort_zone_level_list          = true; // sort or not the zones in zone levels list

/**
 * Spells
 */
$use_spell_globals = false; // If TRUE, any spells in the spell_globals table will not be displayed
$use_zam_search    = true; // If TRUE, will display a ZAM search bar on the left with the sidemenu

$slow_page_caching = false; /* If pages take longer than 1 second to load, they will be cached for further use in cache/ folder */

$ignore_zones = ["load", "loading", "load2", "nektropos", "arttest", "apprentice", "tutorial"];

$mysql_debugging = false;
$database = mysqli_connect($db_host, $db_user, $db_password, $db_name) or die("Impossible to connect to $db_host");

/**
 * Check if server is alive
 */
if ($mysql_debugging) {
    if (mysqli_ping($database)) {
        printf("Our connection is ok!\n");
    } else {
        printf("Error: %s\n", mysqli_error($database));
    }
}

?>
