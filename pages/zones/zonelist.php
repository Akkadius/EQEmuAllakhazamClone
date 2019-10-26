<?php
$page_title = "Zones By Era";
$print_buffer .= "<div class = 'display_table container_div'>";
	$print_buffer .= "<h2 class='section_header'>Zones</h2>";
	$print_buffer .= "<ol>";
		foreach ($era_zones as $era => $era_value) {
			$print_buffer .= "<li><a href = '?a=zone_era&era=" . $era . "'>" . $era_value . "</a></li>";
		}
	$print_buffer .= "</ol>";
$print_buffer .= "</div>";
?>