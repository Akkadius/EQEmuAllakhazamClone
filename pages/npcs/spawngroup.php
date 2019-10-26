<?php

require_once('pages/npcs/functions.php');

if (isset($_GET['view_spawn'])) {
	echo return_npc_spawns($_GET['view_spawn'], 1);
	exit;
}

if (isset($_GET['view_nearby'])) {
	echo return_nearby_npcs($_GET['view_nearby'], 1);
	exit;
}

$id = (isset($_GET['id']) ? addslashes($_GET['id']) : '');

if ($display_spawn_group_info == FALSE) {
	header("Location: index.php");
	exit();
}

$print_buffer .= "<table  class='display_table container_div'>";
if ($id != "" && is_numeric($id)) {
	$print_buffer .= return_npc_spawns_count($id);
	$print_buffer .= return_nearby_npcs_count($id);
}
	
$print_buffer .= "</table>";

if (!isset($_GET['v_ajax']))
    $footer_javascript .= '<script src="pages/npcs/npcs.js"></script>';
?>