<?php
	$id = (isset($_GET['id']) ? $_GET['id'] : '');
	if (!is_numeric($id)) {
		header("Location: ?a=tasks&");
		exit();
	}
	$page_title = "Task :: " . get_field_result("title", "SELECT title FROM $task_table WHERE id = '$id'");
	$activity_data = array();
	if ($id != "" && is_numeric($id)) {
		$query = db_mysql_query("SELECT * FROM $task_table WHERE id = '$id'");
		if (mysqli_num_rows($query) > 0) {
			$task_data = mysqli_fetch_array($query);
		}
		
		$query = db_mysql_query("SELECT * FROM $task_activities_table WHERE taskid = '$id' ORDER BY activityid, step");
		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_array($query)) {
				array_push($activity_data, $row);
			}
		}
	}
	$print_buffer .=  "<table class='display_table container_div'>";
		$print_buffer .=  "<h2>" . $task_data["title"] . "</h2>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class='section_header'>Quest Started By:</h2>";
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<b>Where:</b><br><ul>";
				if ($task_data["startzone"] != 0) {
					$print_buffer .=  "<li><a href = '?a=zone&name=" . get_field_result("short_name", "SELECT short_name  FROM $zones_table WHERE zoneidnumber = '" . $task_data["startzone"] . "'") . "'>" . get_field_result("long_name", "SELECT long_name FROM $zones_table WHERE zoneidnumber = '" . $task_data["startzone"] . "'") . "</a></li></ul>";
				} else {
					$print_buffer .= "<li>Anywhere</li>";
				}
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class='section_header'>Information:</h2>";
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td><ul>";
				$print_buffer .=  "<li><b>Minimum Level: </b>" . $task_data["minlevel"] . "</li>";
				$print_buffer .=  "<li><b>Maximum Level: </b>" . $task_data["maxlevel"] . "</li>";
				$print_buffer .=  "<li><b>Repeatable: </b>" . ($task_data["repeatable"] == 1 ? "Yes" : "No") . "</li>";
				$print_buffer .=  "<li><b>Quest Goal(s): </b>";
					$print_buffer .=  "<ul>";
						if ($task_data["xpreward"] > 0) { $print_buffer .=  "<li>Experience: " . number_format($task_data["xpreward"]) ."</li>"; }
						if ($task_data["cashreward"] > 0) { $print_buffer .=  "<li>Money</li>"; }
						if ($task_data["rewardid"] > 0) { $print_buffer .=  "<li>Loot: <a href = '/Allaclone/?a=item&id=" . $task_data["rewardid"] . "'>" . get_field_result("name", "SELECT name FROM $items_table WHERE id = '" . $task_data["rewardid"] . "'") . "</li>"; }
					$print_buffer .=  "</ul>";
				$print_buffer .=  "</li>";
			$print_buffer .=  "</ul></td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class='section_header'>Description:</h2>";
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<b>Activities:</b><br><ul>";
				foreach ($activity_data as $activity) {
					if ($activity["activitytype"] == 1) {
						if ($activity["text1"] != "" && $activity["text2"] !== "") {
							$print_buffer .=  "<li>Deliver " . $activity["goalcount"] . " " . $activity["text2"] . " to " . $activity["text1"] . "</li>";
						} else if ($activity["text1"] != "" && $activity["text2"] == "") {
							$print_buffer .=  "<li>" . $activity["text1"] . "</li>";
						} else if ($activity["text3"] != "") {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 2) {
						if ($activity["text1"] != "") {
							$print_buffer .=  "<li>Kill " . $activity["goalcount"] . " " . $activity["text1"] . "</li>";
						} else if ($activity["goalid"] < 500) {
							$query = db_mysql_query("SELECT DISTINCT $npc_types_table.`name` FROM $npc_types_table INNER JOIN goallists ON $npc_types_table.id = goallists.entry WHERE goallists.listid = '" . $activity["goalid"] . "'");
							if (mysqli_num_rows($query) > 0) {
								$npcs = array();
								while ($row = mysql_fetch_assoc($query)) {
									array_push($npcs, NameCleanup($row["name"]));
								}
								$print_buffer .=  "<li>Kill " . $activity["goalcount"] . " of any of the following: " . implode(", ", $npcs) . "</li>";
							}
						} else {
							$print_buffer .=  "<li>Kill " . $activity["goalcount"] . " " . $activity["text1"] . "</li>";
						}
					} else if ($activity["activitytype"] == 3) {
						if ($activity["text1"] != "" && $activity["text2"] != "") {
							$print_buffer .=  "<li>Loot " . $activity["goalcount"] . " " . $activity["text2"] . " from " . $activity["text1"] . "</li>";
						} else if ($activity["text1"] == "" && $activity["text2"] != "" ){
							$print_buffer .=  "<li>Loot " . $activity["goalcount"] . " " . $activity["text2"] . "</li>";
						} else if ($activity["text3"] != "") {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 4) {
						if ($activity["text1"] !== "") {
							$print_buffer .=  "<li>Speak with " . $activity["text1"] . "</li>";
						} else if ($activity["text3"] !== "") {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 5) {
						if ($activity["text1"] !== "") {
							$print_buffer .=  "<li>Explore " . $activity["text1"] . "</li>";
						} else if ($activity["text3"] !== "" ) {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 6) {
						if ($activity["text2"] == "") {
							$print_buffer .=  "<li>Create " . $activity["goalcount"] . " " . $activity["text1"] . " using Tradeskills</li>";
						} else {
							$print_buffer .=  "<li>Create " . $activity["goalcount"] . " " . $activity["text2"] . " using Tradeskills</li>";
						}
					} else if ($activity["activitytype"] == 7) {
						$print_buffer .=  "<li>Fish for " . $activity["goalcount"] . " " . $activity["text2"] . "</li>";
					} else if ($activity["activitytype"] == 8) {
						if ($activity["text3"] == "") {
							$print_buffer .=  "<li>Forage for " . $activity["goalcount"] . " " . $activity["text1"] . "</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 11) {
						if ($activity["text3"] == "") {
							$print_buffer .=  "<li>Go to " . get_field_result("long_name", "SELECT long_name FROM $zones_table WHERE zoneidnumber = '" . $activity["zoneid"] . "'") . "</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 100) {
						$Platinum = 0;
						$Gold = 0;
						$Silver = 0;
						$Copper = 0;
					
						if ($activity["goalcount"] > 1000) {
							$Platinum = ((int)($activity["goalcount"] / 1000));
						}
						if (($activity["goalcount"] - ($Platinum * 1000)) > 100) {
							$Gold = ((int)(($activity["goalcount"] - ($Platinum * 1000)) / 100));
						}
						if (($activity["goalcount"] - ($Platinum * 1000) - ($Gold * 100)) > 10) {
							$Silver = ((int)(($activity["goalcount"] - ($Platinum * 1000) - ($Gold * 100)) / 10));
						}
						if (($activity["goalcount"] - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10)) > 0) {
							$Copper = ($activity["goalcount"] - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10));
						}
						if ($activity["text3"] == "") {
							$print_buffer .=  "<li>Give $Platinum Platinum, $Gold Gold, $Silver Silver, and $Copper Copper to " . get_field_result("name", "SELECT name FROM $npc_types_table WHERE id = '" . $activity["npctypeid"] . "'") . "</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					}
				}
			$print_buffer .=  "</ul></td>";
		$print_buffer .=  "</tr>";
	$print_buffer .=  "</table>";
	
	
	function NameCleanup($name) {
		$name = str_replace("#", "", $name);
		$name = str_replace("_", " ", $name);
		return $name;
	}
?>