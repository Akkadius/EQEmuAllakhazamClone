<?php
	$id = (isset($_GET['id']) ? $_GET['id'] : '');
	if (!is_numeric($id)) {
		header("Location: ?a=task");
		exit();
	}
	$page_title = "Task :: " . get_field_result("title", "SELECT title FROM $task_table WHERE id = '$id'");
	$activity_data = array();
	$query = db_mysql_query("SELECT * FROM $task_table WHERE id = '$id'");
	if (mysqli_num_rows($query) > 0) {
		$task_data = mysqli_fetch_array($query);
	} else {
		header("Location: ?a=task&");
		exit();
	}
	
	$query = db_mysql_query("SELECT * FROM $task_activities_table WHERE taskid = '$id' ORDER BY activityid, step");
	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_array($query)) {
			array_push($activity_data, $row);
		}
	} else {
		header("Location: ?a=task&");
		exit();
	}
	$print_buffer .=  "<table class='display_table container_div'>";
		$print_buffer .=  "<h2>" . $task_data["title"] . "</h2>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class='section_header'>Info</h2>";
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td><ul>";
				$print_buffer .= "<li><b>Task Type: </b>" . $task_types[$task_data["type"]] . "</li>";
				if ($task_data["duration_code"] > 0)
					$print_buffer .= "<li><b>Duration Code: </b>" . $duration_codes[$task_data["duration_code"]] . "</li>";
				
				if ($task_data["duration"] > 0)
					$print_buffer .= "<li><b>Duration: </b>" . translate_time($task_data["duration"]) . "</li>";
				
				if ($task_data["minlevel"] > 0)
					$print_buffer .=  "<li><b>Minimum Level: </b>" . $task_data["minlevel"] . "</li>";
				
				if ($task_data["maxlevel"] > 0)
					$print_buffer .=  "<li><b>Maximum Level: </b>" . $task_data["maxlevel"] . "</li>";
					
				$print_buffer .=  "<li><b>Repeatable: </b>" . ($task_data["repeatable"] == 1 ? "Yes" : "No") . "</li>";
				$print_buffer .=  "<li><b>Quest Reward(s): </b>";
					$print_buffer .=  "<ul>";
						if ($task_data["cashreward"] > 0) { $print_buffer .=  "<li>Money: " . price($task_data["cashreward"]) . "</li>"; }
						if ($task_data["faction_reward"] > 0) { $print_buffer .= "<li>Faction: " . number_format($task_data["faction_reward"]) . "</li>"; }
						if ($task_data["reward"] != "" && $task_data["reward"] != 0) { $print_buffer .= "<li>Reward: " . $task_data["reward"] . "</li>"; }
						if ($task_data["rewardid"] >= 1001) { $print_buffer .=  "<li>Item(s): <a href = '$root_url/?a=item&id=" . $task_data["rewardid"] . "'>" . get_field_result("name", "SELECT name FROM $items_table WHERE id = '" . $task_data["rewardid"] . "'") . "</a></li>"; }
						if ($task_data["xpreward"] > 0) { $print_buffer .=  "<li>Experience: " . number_format($task_data["xpreward"]) ."</li>"; }
						else if ($task_data["rewardid"] > 0 && $task_data["rewardid"] < 1001) { $goal_items = get_goal_items($task_data["rewardid"]); $print_buffer ."<li>Loot: $goal_items</li>"; }
					$print_buffer .=  "</ul>";
				$print_buffer .=  "</li>";
			$print_buffer .=  "</ul></td>";
		$print_buffer .=  "</tr>";
		
		$print_buffer .=  "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class='section_header'>Description</h2>";
			$print_buffer .=  "</td>";
		$print_buffer .=  "</tr>";
		$print_buffer .= "<tr>";		
			$print_buffer .= "<td>";
				if (clean_description($task_data["description"]) != "<ol></ol>")
					$print_buffer .= clean_description($task_data["description"]);
				else
					$print_buffer .= "<ul><li>No task description.</li></ul>";
				
			$print_buffer .= "</td>";
		$print_buffer .= "</tr>";
		$print_buffer .=  "<tr>";
			$print_buffer .=  "<td>";
				$print_buffer .=  "<h2 class ='section_header'>Activities</h2><ul>";
				foreach ($activity_data as $activity) {
					$zones = ((strlen($activity["zones"]) > 1 && $activity["zones"] != 0) ? (" in " . get_zone_names($activity["zones"])) : ".");
					if ($activity["activitytype"] == 1) {
						if ($activity["target_name"] != "" && $activity["item_list"] != "") {
							$print_buffer .=  "<li>Deliver " . $activity["goalcount"] .
							" <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) . "</a> 
							to <a href = '?a=npc&id=" . $activity["delivertonpc"] . "'>" . get_npc_name_human_readable(get_npc_name($activity["delivertonpc"])) . "</a>$zones</li>";
						} else if ($activity["description_override"] != "" && $activity["item_list"] == "") {
							$print_buffer .=  "<li>Deliver " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) . "</a>$zones</li>";
						} else if ($activity["target_name"] != "" && $activity["item_list"] == "") {
							if ($activity["goalid"] > 1000) {
								$print_buffer .=  "<li>Deliver " . $activity["goalcount"]  . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) .
								"</a> to <a href = '?a=npc&id=" . $activity["delivertonpc"] . "'>" . get_npc_name_human_readable(get_npc_name($activity["delivertonpc"])) . "</a>$zones</li>";
							} else {
								$print_buffer .=  "<li>Deliver " . $activity["goalcount"] . " " . get_goal_items($activity["goalid"]) . "$zones</li>";
							}
						} else if ($activity["text3"] != "") {
							$print_buffer .=  "<li>" . $activity["text3"] . "</li>";
						}
					} else if ($activity["activitytype"] == 2) {
						if ($activity["description_override"] == "") {
							if ($activity["goalid"] <= 1000) {
								$print_buffer .= "<li>Kill " . $activity["goalcount"] . " of the following$zones</li>";
								$print_buffer .= get_goal_npcs($activity["goalid"]);
								$print_buffer .= "<br>";
							} else {
								$print_buffer .=  "<li>Kill " . $activity["goalcount"] . " <a href = '?a=npc&id=" . $activity["goalid"] . "'>" . get_npc_name_human_readable($activity["target_name"]) . "</a>$zones</li>";
							}
						} else {
							if ($activity["goalid"] > 1000) {
								$print_buffer .=  "<li>Kill " . $activity["goalcount"] . " <a href = '?a=npc&id=" . $activity["goalid"] . "'>" . get_npc_name_human_readable(get_npc_name($activity["goalid"])) . "</a>$zones</li>";
							} else {
								$print_buffer .= "<li>Kill " . $activity["goalcount"] . " of the following$zones</li>";
								$print_buffer .= get_goal_npcs($activity["goalid"]);
								$print_buffer .= "<br>";
							}
						}
					} else if ($activity["activitytype"] == 3) {
						if ($activity["item_list"] != "" && $activity["goalid"] > 1000) {
							$print_buffer .=  "<li>Loot " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) . "</a>$zones</li>";
						} else if ($activity["item_list"] != "" && $activity["goalid"] <= 1000){
							$print_buffer .=  "<li>Loot " . $activity["goalcount"] . " " . get_goal_items($activity["goalid"]) . "$zones</li>";
						} else if ($activity["description_override"] != "" && $activity["goalid"] == 0) {
							$print_buffer .=  "<li>" . $activity["description_override"] . "</li>";
						} else if ($activity["description_override"] != "" && $activity["goalid"] > 1000) {
							$print_buffer .=  "<li>Loot " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) . "</a>$zones</li>";
						}
					} else if ($activity["activitytype"] == 4) {
						if ($activity["description_override"] == "") {
							if ($activity["target_name"] != "") {
								if ($activity["goalid"] > 0) {
									$print_buffer .=  "<li>Speak with <a href = '?a=npc&id=" . $activity["goalid"] . "'>" . $activity["target_name"] . "</a>$zones</li>";
								} else {
									$print_buffer .=  "<li>Speak with " . $activity["target_name"] . "$zones</li>";
								}
							} else {
								$print_buffer .=  "<li>Speak ?</li>";
							}
						} else {
							if ($activity["goalid"] > 1000) {
								$print_buffer .=  "<li><a href = '?a=npc&id=" . $activity["goalid"] . "'>" . $activity["description_override"] . "</a>$zones</li>";
							} else if ($activity["goalid"] == 0) {
								$print_buffer .= "<li>" . $activity["description_override"] . "</li>";
							}
						}
					} else if ($activity["activitytype"] == 5) {
						if ($activity["target_name"] != "") {
							$print_buffer .=  "<li>Explore " . $activity["target_name"] . "$zones</li>";
						} else if ($activity["description_override"] != "") {
							if ($activity["goalid"] > 1000) {
								$print_buffer .=  "<li><a href = '?a=npc&id=" . $activity["goalid"] . "'>" . $activity["description_override"] . "</a></li>";
							}
						}
					} else if ($activity["activitytype"] == 6) {
						if ($activity["target_name"] == "" && $activity["item_list"] == "") {
							$print_buffer .=  "<li>Create " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . get_item_name($activity["goalid"]) . "</a>$zones</li>";
						} else if ($activity["target_name"] != "") {
							$print_buffer .=  "<li>Create " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . $activity["target_name"] . "</a>$zones</li>";
						} else if ($activity["item_list"] != "") {
							$print_buffer .=  "<li>Create " . $activity["goalcount"] . " <a href = '?a=item&id=" . $activity["goalid"] . "'>" . $activity["item_list"] . "</a>$zones</li>";
						} else {
							$print_buffer .= "<li>Create ?</li>";
						}
					} else if ($activity["activitytype"] == 7) {
						if ($activity["description_override"] == "" && $activity["item_list"] != "") {
							$print_buffer .=  "<li>Fish for " . $activity["goalcount"] . " " . $activity["item_list"] . "$zones</li>";
						} else if ($activity["description_override"] != "" && $activity["item_list"] == "") {
							$print_buffer .=  "<li>" . $activity["description_override"] . "</li>";
						}
					} else if ($activity["activitytype"] == 8) {
						if ($activity["description_override"] == "" && $activity["item_list"] != "") {
							$print_buffer .=  "<li>Forage for " . $activity["goalcount"] . " " . $activity["item_list"] . "$zones</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["description_override"] . "$zones</li>";
						}
					} else if ($activity["activitytype"] == 11) {
						if ($activity["description_override"] == "") {
							$print_buffer .=  "<li>Go to " . get_field_result("long_name", "SELECT long_name FROM $zones_table WHERE zoneidnumber IN (" . $activity["zones"] . ")") . ".</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["description_override"] . ".</li>";
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
						if ($activity["description_override"] == "") {
							$print_buffer .=  "<li>Give $Platinum Platinum, $Gold Gold, $Silver Silver, and $Copper Copper to " . get_field_result("name", "SELECT name FROM $npc_types_table WHERE id = '" . $activity["npctypeid"] . "'") . "</li>";
						} else {
							$print_buffer .=  "<li>" . $activity["description_override"] . "</li>";
						}
					}
				}
			$print_buffer .=  "</ul></td>";
		$print_buffer .=  "</tr>";
	$print_buffer .=  "</table>";
	
	
	function name_cleanup($name) {
		$name = str_replace("#", "", $name);
		$name = str_replace("_", " ", $name);
		return $name;
	}
	
	function get_goal_npcs($goal_list) {
		global $goal_lists_table, $npc_types_table;
		$goal_list_npcs = "<ol>";
		$query = db_mysql_query("
			SELECT n.`id`, n.`name` FROM $npc_types_table n
			INNER JOIN $goal_lists_table gl ON n.`id` = gl.`entry`
			WHERE gl.`listid` = '$goal_list' GROUP BY gl.`entry` ASC
		");
		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_assoc($query)) {
				$goal_list_npcs .= "<li><a href = '?a=npc&id=" . $row["id"] . "'>" . get_npc_name_human_readable($row["name"]) . "</a></li>";
			}
		}
		$goal_list_npcs .= "</ol>";
		return $goal_list_npcs;
	}
	
	function get_goal_items($goal_list) {
		global $goal_lists_table, $items_table;
		$goal_list_items = "<ol>";
		$query = db_mysql_query("
			SELECT i.`id`, i.`name` FROM $items_table i
			INNER JOIN $goal_lists_table gl ON i.`id` = gl.`entry`
			WHERE gl.`listid` = '$goal_list' GROUP BY gl.`entry` ASC
		");
		if (mysqli_num_rows($query) > 0) {
			while ($row = mysqli_fetch_assoc($query)) {
				$goal_list_items .= "<li><a href = '?a=item&id=" . $row["id"] . "'>" . $row["name"] . "</a></li>";
			}
		}
		$goal_list_items .= "</ol>";
		return $goal_list_items;
	}
	
	function clean_description($task_description) {
		if (substr_count($task_description, "[")) {
			$clean_description = explode("]", $task_description);
			$final_description = "";
			$clean_values = array();
			if (count($clean_description) > 2) {
				$final_description .= "<ol>";
				$start = 0;
				foreach ($clean_description as $index => $value) {
					$replace = array("[", "]", "1,", "2,", "3,", "4,", "5,", "6,", "7,", "8,", "9,", "10,");
					$value = str_replace($replace, "", $value);
					$value = explode(" ", $value);
					$description = $value[1];
					$message = "";
					foreach ($value as $v) {
						if ($v != "")
							$message .= "$v ";
					}
					if ($message != "")
						$final_description .= "<li>$message</li>";
				}
				$final_description .= "</ol>";
			} else {
				$replace = array("[", "]", "1,", "2,", "3,", "4,", "5,", "6,", "7,", "8,", "9,", "10,");
				$value = $clean_description[0];
				$value = str_replace($replace, "", $value);
				$value = explode(" ", $value);
				$message = "";
				foreach ($value as $v) {
					if ($v != "")
						$message .= "$v ";
				}
				$final_description .= "<ul><li>$message</li></ul>";
			}
			return $final_description;
		}
		return "<ul><li>$task_description</li></ul>";
	}
?>