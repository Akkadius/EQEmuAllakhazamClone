<?php

error_reporting(0);

$SiteTitle = 'EQEmu Server AllaClone';
$ServerName = 'EQEmu Server';
$cfgversion = '2.0 - Rev2100';
$SiteEmail = '';

$dbhost = "localhost";
$dbname = "peq";
$dbuser = "username";
$dbpasswd = "password";

$use_pace_loader = true; /* shows a loader at the top of the page */
$hide_navbar = false;

$site_logo = "images/logos/logo.png"; /* Where you put your site logo */

$root_url = 'http://www.myserver.com/AllaClone/';
$includes_url = $root_url . 'includes/';
$includes_dir = getcwd() . "/includes/";
$eqemu_dir = "/home/eqemu/server/";
$quests_dir = $eqemu_dir . "quests/";
$quests_datas = "/home/eqemu/server/quests/";
$maps_dir = getcwd() . "/maps/";
$maps_url = $root_url . "/maps/";
$npcs_dir = getcwd() . "/npcs/";
$npcs_url = $root_url . "/npcs/";
$icons_dir = getcwd() . "/images/icons/";
$icons_url = $root_url . "/images/icons/";
$images_url = $root_url . "/images/";
$charbrowser_url = "http://www.myserver.com/CharBrowser/"; // Set to "" to disable CharBrowser links for character names


$DefaultCSS = "allakhazam";

// Available Styles - ( allakhazam, dark_blue )
if (isset($_COOKIE["Theme"])) {
    if ($_COOKIE["Theme"] == "Dark Blue") {
        $CssStyle = "dark_blue";
    }
    if ($_COOKIE["Theme"] == "Allakhazam") {
        $CssStyle = "allakhazam";
    } else {
        $CssStyle = $DefaultCSS;
    }
} else {
    $CssStyle = $DefaultCSS;
}

/* Options */

/* NPC */
$allow_csv_output_options = FALSE; // If TRUE, will display CSV output button for searches that allow them
$allow_quests_npc = FALSE; // quests for npcs are available from NPC's page
$display_named_npcs_info = TRUE; // If TRUE, will display a link to show named NPCs and export to CVS for maps
$display_npc_stats = TRUE; // If TRUE, will display HP, Damage, Special Attacks, etc for NPCs
$display_spawn_group_info = TRUE; // If TRUE, will display the spawngroup link from the zone pages
$group_npcs_by_name = TRUE; // groups the NPCs by their name in zone npcs lists
$max_npcs_returned = 50; // max number of npcs returned by search engines (0=all)
$server_max_npc_level = 95; // Max Level for any NPCs on the Server
$show_npc_drop_chances = TRUE; // if TRUE, chances of droping of each item will be shown when browsing npcs
$show_npc_number_in_zone_level_list = TRUE; // choose between number and x for npcs in zone levels list
$show_npcs_attack_speed = TRUE; // shows informations about NPCs' attack speed
$show_npcs_average_damages = TRUE; // How much NPC does as melee damages, in average, this allows to comparate mobs together
$spawngroup_around_range = 100; // range of surrounding spawngroups, spawngroups page
$trackable_npcs_only = TRUE; // If TRUE, will only display NPCs that are set to be trackable in search results
$hide_invisible_men = TRUE; // hide the invisible mens in bestiaries

/* Items */
$item_add_chance_to_drop = TRUE; // shows what are the chances to see the item droped by the npcs
$discovered_items_max_status = 20; // Max account status for a discovered item entry
$discovered_items_only = FALSE; // If TRUE, only Discovered Items will be displayed
$max_items_returned = 50; // max number of items returned by search engines (0=all)

/* This can increase page load times up to 3-6 seconds without caching */
$item_found_info = TRUE; // If TRUE, it displays where items can drop or be purchased (longer item page load times)

/* Quest */
$always_build_quest = FALSE; // rebuilds the quest each time a visitor browse it, put false if you don't modify them anymore

/* Task */
$display_task_activities = TRUE; // If TRUE, will display all task activities
$display_task_info = TRUE; // If TRUE, will allow search results to show tasks and rewards

/* Misc */
$max_recently_discovered_items = 25; // Max number of recently discovered items to display
$merchants_dont_drop_stuff = TRUE; // if TRUE, you won't see merchants drops, damned PEQ world builders ! :)
$server_max_level = 80; // Max Level for Characters on the Server
$sort_zone_level_list = TRUE; // sort or not the zones in zone levels list

$use_spell_globals = FALSE; // If TRUE, any spells in the spell_globals table will not be displayed
$use_zam_search = TRUE; // If TRUE, will display a ZAM search bar on the left with the sidemenu

$slow_page_caching = false; /* If pages take longer than 1 second to load, they will be cached for further use in cache/ folder */

$ignore_zones = array("load", "loading", "load2", "nektropos", "arttest", "apprentice", "tutorial");

$db = mysql_connect($dbhost, $dbuser, $dbpasswd) or die("Impossible to connect to $dbhost : " . mysql_error());
mysql_select_db($dbname, $db) or die("Impossible to select $dbname : " . mysql_error());

$mysql_debugging = false;

?>
