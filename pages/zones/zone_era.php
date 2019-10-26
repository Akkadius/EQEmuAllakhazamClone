<?php
$print_buffer .= "<div class = 'display_table container_div'>";
	$print_buffer .= "<h2 class='section_header'>Zones</h2>";
	$print_buffer .= "<ol>";
		if (!isset($_GET["era"])) {
			$page_title = "Zones By Era";
			foreach ($era_zones as $era => $era_value) {
				$print_buffer .= "<li><a href = '?a=zone_era&era=" . $era . "'>" . $era_value . "</a></li>";
			}
		} else {
			$era = $_GET["era"];
			$page_title = $era_zones[$era];
			$print_buffer .= get_zones_by_era($era);
		}
	$print_buffer .= "</ol>";
$print_buffer .= "</div>";
?>