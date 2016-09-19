<?php

function print_npcs_by_zone($query_result)
{
    if (mysql_num_rows($query_result) > 0) {
        $current_zone_iteration = "";
        while ($row = mysql_fetch_array($query_result)) {
            if ($current_zone_iteration != $row["zone"]) {
                if ($current_zone_iteration != "")
                    $print_buffer .= "                  <br/><br/>\n";
                $print_buffer .= "                  <b>in <a href='?a=zone&name=" . $row["zone"] . "'>" . $row["long_name"] . "</a> by </b>\n";
                $current_zone_iteration = $row["zone"];
            }
            $print_buffer .= "<li><a href='?a=npc&id=" . $row["id"] . "'>" . str_replace("_", " ", $row["name"]) . "</a> (" . $row["id"] . ")</li>\n";
        }
        if ($current_zone_iteration != "")
            $print_buffer .= "                  <br/><br/>\n";
    }
    return $print_buffer;
}



?>