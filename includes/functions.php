<?php

function return_item_icon_from_icon_id($icon_id, $size = 50)
{
    global $icons_dir, $icons_url;

    if (file_exists($icons_dir . "item_" . $icon_id . ".png")) {
        return "<img src='" . $icons_url . "item_" . $icon_id . ".png' style='width:" . $size . "px;height:auto;'>";
    }

    return;
}

function wrap_content_box($content)
{
    $return_buffer = '
        <table class="container_div display_table">
            <tr>
                <td>
                ' . $content . '
                </td>
            </tr>
        </table>
    ';

    return $return_buffer;
}

function display_header($header)
{
    return '
        <tr>
            <td colspan="2">' . $header . '</td>
        </tr>
    ';
}

function display_table($content, $width = 500)
{
    $return_buffer = '
        <table class="container_div display_table" style="width:' . $width . 'px">
            ' . $content . '
        </table>
    ';

    return $return_buffer;
}

function display_row($left, $right = "")
{
    return '
        <tr>
            <td style="vertical-align:top">' . $left . '</td>
            <td style="vertical-align:top">' . $right . '</td>
        </tr>
    ';
}

function search_box($name = "", $value = "", $placeholder)
{
    return '
        <div class="search_box">
            <input name="' . $name . '" type="text" value="' . $value . '" class="search" autocomplete="off" placeholder="' . $placeholder . '">
            <a href="javascript:document.search.submit();"></a>
        </div>
    ';
}

function strip_underscores($string)
{
    $string = str_replace("_", " ", $string);

    return $string;
}

function print_query_results(
    $mysql_reference_data,
    $rows_to_return,
    $anchor_link_callout,
    $query_description, /* Example: NPCs */
    $object_description,
    $href_id_name,
    $href_name_attribute,
    $extra_field = "",
    $extra_field_description = "",
    $extra_skill = ""
) {
    global $dbskills;

    $mysql_rows_returned = mysqli_num_rows($mysql_reference_data);
    if ($mysql_rows_returned > get_max_query_results_count($rows_to_return)) {
        $mysql_rows_returned = get_max_query_results_count($rows_to_return);
        $more_objects_exist  = true;
    } else {
        $more_objects_exist = false;
    }

    if ($mysql_rows_returned == 0) {
		if ($query_description == "npcs")
			$query_description = "NPCs";
		else
			$query_description = ucfirst($query_description);
		
        $return_buffer .= "<ul><li><b>No " . $query_description . " found.</b></li></ul>";
    } else {
		if ($query_description == "npcs")
			$query_description = "NPCs";
		else
			$query_description = ucfirst($query_description);
		
		
		if ($object_description == "npcs")
			$object_description = "NPCs";
		else
			$object_description = ucfirst($object_description);
		
        $return_buffer .= "<h1>" . $mysql_rows_returned . " " . ($mysql_rows_returned == 1 ? $query_description : $object_description) . " displayed.<br>";
        if ($more_objects_exist) {
            $return_buffer .= " More " . $object_description . " exist but you reached the query limit.";
        }
        $return_buffer .= "</h1>";
        $return_buffer .= "<ul>";
        for ($j = 1; $j <= $mysql_rows_returned; $j++) {
            $row = mysqli_fetch_array($mysql_reference_data);

            $return_buffer .= " <li style='text-align:left'><a href='" . $anchor_link_callout . "id=" . $row[$href_id_name] . "'>";
            if ($query_description == "NPC") {
                // Clean up the name for NPCs
                $return_buffer .= get_npc_name_human_readable($row[$href_name_attribute]);
            } else {
                $return_buffer .= $row[$href_name_attribute];
            }
            $return_buffer .= " (" . $row[$href_id_name] . ")</a>";

            if ($extra_field && $extra_field_description && $extra_skill) {
                $return_buffer .= " - " . ucfirstwords(
                        str_replace("_", " " . $dbskills[$row[$extra_skill]])
                    ) . " $extra_field_description " . $row[$extra_field];
            }
            $return_buffer .= "</li>";
        }
        $return_buffer .= "</ul></ul>";
    }

    return wrap_content_box($return_buffer);
}

function get_max_query_results_count($MaxObjects)
{
    if ($MaxObjects == 0) {
        $Result = 2147483647;
    } else {
        $Result = $MaxObjects;
    }

    return $Result;
}

function get_npc_name_human_readable($DbName)
{
    $Result = str_replace(
        '-',
        '`',
        str_replace(
            '_',
            ' ',
            str_replace('#', '', str_replace('!', '', str_replace('~', '', $DbName)))
        )
    );
    for ($i = 0; $i < 10; $i++) {
        $Result = str_replace($i, '', $Result);
    }

    return $Result;
}

/** Returns the type of NPC based on the name of an NPC from its database-encoded '$DbName'.
 */
function NpcTypeFromName($DbName)
{
    global $NPCTypeArray;
    foreach ($NPCTypeArray as $key => $type) {
        $KeyCount     = substr_count($DbName, $key);
        $StringLength = strlen($DbName);
        $KeyLength    = strlen($key);
        if ($KeyCount > 0 && substr($DbName, 0, $KeyLength) == $key) {
            return $type;
        }
    }

    return "Normal";
}

// Converts the first letter of each word in $str to upper case and the rest to lower case.
function ucfirstwords($str)
{
    return ucwords(strtolower($str));
}

/** Returns the URL in the Wiki to the image illustrating the NPC with ID '$NpcId'
 *  Returns an empty string if the image does not exist in the Wiki
 */
function NpcImage($WikiServerUrl, $WikiRootName, $NpcId)
{
    $SystemCall = "wget -q \"" . $WikiServerUrl . $WikiRootName . "/index.php/Image:Npc-" . $NpcId . ".jpg\" -O -| grep \"/" . $WikiRootName . "/images\" | head -1 | sed 's;.*\\(/" . $WikiRootName . "/images/[^\"]*\\).*;\\1;'";
    $Result     = `$SystemCall`;
    if ($Result != "") {
        $Result = $WikiServerUrl . $Result;
    }

    return $Result;
}

/** Returns a human-readable translation of '$sec' seconds (for respawn times)
 *  If '$sec' is '0', returns 'time' (prints 'Spawns all the time' as a result)
 */
function translate_time($sec)
{
    if ($sec == 0) {
        $Result = "time";
    } else {
		$Result = "";
		$sep = "";
        $h = floor($sec / 3600);
		if ($h > 0) {
			$Result .= "$h Hour(s)";
			$sep = ", ";
		}
		
        $m = floor(($sec - $h * 3600) / 60);
		if ($h > 0 && $m > 0) {
			$Result .= "$sep $m Minute(s)";
			$sep = ", and";
		}
		
		if ($h == 0 && $m > 0) {
			$Result .= "$sep $m Minute(s)";
			$sep = ", ";
		}
		
        $s = $sec - $h * 3600 - $m * 60;
		if ($s > 0) {
			$Result .= "$s Second(s)";
		}
    }

    return $Result;
}

/** Returns the rest of the euclidian division of '$d' by '$v'
 *  Returns '0' if '$v' equals '0'
 *  Supposes '$d' and '$v' are positive
 */
function modulo($d, $v)
{
    if ($v == 0) {
        $Result = 0;
    } else {
        $s      = floor($d / $v);
        $Result = $d - $v * $s;
    }
}

/** Returns the list of slot names '$val' corresponds to (as a bit field)
 */
function get_slots_string($val)
{
    global $dbslots;
    reset($dbslots);
    do {
        $key = key($dbslots);
        if ($key <= $val) {
            $val    -= $key;
            $Result .= $v . current($dbslots);
            $v      = ", ";
        }
    } while (next($dbslots));

    return $Result;
}

function get_class_usable_string($val)
{
    global $dbiclasses;
    reset($dbiclasses);
    do {
        $key = key($dbiclasses);
        if ($key <= $val) {
            $val -= $key;
            $res .= $v . current($dbiclasses);
            $v   = ", ";
        }
    } while (next($dbiclasses));

    return $res;
}

function get_race_usable_string($val)
{
    global $dbraces;
    reset($dbraces);
    do {
        $key = key($dbraces);
        if ($key <= $val) {
            $val -= $key;
            $res .= $v . current($dbraces);
            $v   = ", ";
        }
    } while (next($dbraces));

    return $res;
}

function get_size_string($val)
{
    switch ($val) {
        case 0:
            return "Tiny";
            break;
        case 1:
            return "Small";
            break;
        case 2:
            return "Medium";
            break;
        case 3:
            return "Large";
            break;
        case 4:
            return "Giant";
            break;
        default:
            return "$val?";
            break;
    }
}

function getspell($id)
{
    global $spells_table, $spell_globals_table, $use_spell_globals;
    if ($use_spell_globals == true) {
        $query = "SELECT " . $spells_table . ".* FROM " . $spells_table . " WHERE " . $spells_table . ".id=" . $id . "
			AND ISNULL((SELECT " . $spell_globals_table . ".spellid FROM " . $spell_globals_table . "
			WHERE " . $spell_globals_table . ".spellid = " . $spells_table . ".id))";
    } else {
        $query = "SELECT * FROM $spells_table WHERE id=$id";
    }
    $result = db_mysql_query($query) or message_die('functions.php', 'getspell', $query, mysqli_error());
    $s = mysqli_fetch_array($result);

    return $s;
}

function get_deity_usable_string($val)
{
    global $dbideities;
    reset($dbideities);
    do {
        $key = key($dbideities);
        if ($key <= $val) {
            $val -= $key;
            $res .= $v . current($dbideities);
            $v   = ", ";
        }
    } while (next($dbideities));

    return $res;
}

function SelectMobRace($name, $selected)
{
    global $dbiracenames;
    $return_buffer = "<SELECT name=\"$name\" style='width:100%'>";
    $return_buffer .= "<option value='0'>-</option>";
    foreach ($dbiracenames as $key => $value) {
        $return_buffer .= "<option value='" . $key . "'";
        if ($key == $selected) {
            $return_buffer .= " selected='1'";
        }
        $return_buffer .= ">" . $value . "</option>";
    }
    $return_buffer .= "</SELECT>";

    return $return_buffer;
}

function SelectLevel($name, $maxlevel, $selevel)
{
    $return_buffer = "<SELECT name=\"$name\">";
    $return_buffer .= "<option value='0'>-</option>";
    for ($i = 1; $i <= $maxlevel; $i++) {
        $return_buffer .= "<option value='" . $i . "'";
        if ($i == $selevel) {
            $return_buffer .= " selected='1'";
        }
        $return_buffer .= ">$i</option>";
    }
    $return_buffer .= "</SELECT>";

    return $return_buffer;
}

function SelectTradeSkills($name, $selected)
{
    $return_buffer = "<SELECT name=\"$name\">";
    $return_buffer .= WriteIt("0", "-", $selected);
    $return_buffer .= WriteIt("59", "Alchemy", $selected);
    $return_buffer .= WriteIt("60", "Baking", $selected);
    $return_buffer .= WriteIt("63", "Blacksmithing", $selected);
    $return_buffer .= WriteIt("65", "Brewing", $selected);
    $return_buffer .= WriteIt("55", "Fishing", $selected);
    $return_buffer .= WriteIt("64", "Fletching", $selected);
    $return_buffer .= WriteIt("68", "Jewelery making", $selected);
    $return_buffer .= WriteIt("56", "Poison making", $selected);
    $return_buffer .= WriteIt("69", "Pottery making", $selected);
    $return_buffer .= WriteIt("58", "Research", $selected);
    $return_buffer .= WriteIt("61", "Tailoring", $selected);
    $return_buffer .= WriteIt("57", "Tinkering", $selected);
    $return_buffer .= "</SELECT>";

    return $return_buffer;
}

function WriteIt($value, $name, $sel)
{
    $return_buffer = "  <option value='" . $value . "'";
    if ($value == $sel) {
        $return_buffer .= " selected='1'";
    }
    $return_buffer .= ">$name</option>";

    return $return_buffer;
}

function get_item_stat_string($name, $stat, $stat2 = 0, $stat2color = "")
{
    $PrintString = "";
    if (is_numeric($stat)) {
        if ($stat != 0 || $stat2 != 0) {
            $PrintString .= "<tr><td><b>" . $name . ": </b></td><td style='text-align:right'>";
            if ($stat < 0) {
                $PrintString .= "<font color='red'>" . sign($stat) . "</font>";
            } else {
                $PrintString .= $stat;
            }
            if ($stat2 < 0) {
                $PrintString .= "<font color='red'> " . sign($stat2) . "</font>";
            } elseif ($stat2 > 0) {
                if ($stat2color) {
                    $PrintString .= "<font color='" . $stat2color . "'> " . sign($stat2) . "</font>";
                } else {
                    $PrintString .= sign($stat2);
                }
            }
            $PrintString .= "</td></tr>";
        }
    } else {
        if (preg_replace("[^0-9]", "", $stat) > 0) {
            $PrintString .= "<tr><td ><b>" . $name . ": </b></td><td style='text-align:right'>" . $stat . "</td></tr>";
        }
    }

    return $PrintString;
}

// spell_effects.cpp int Mob::CalcSpellEffectValue_formula(int formula, int base, int max, int caster_level, int16 spell_id)
function CalcSpellEffectValue($form, $base, $max, $lvl)
{
    // $return_buffer .= " (base=$base form=$form max=$max, lvl=$lvl)";
    $sign   = 1;
    $ubase  = abs($base);
    $result = 0;
    if (($max < $base) AND ($max != 0)) {
        $sign = -1;
    }
    switch ($form) {
        case 0:
        case 100:
            $result = $ubase;
            break;
        case 101:
            $result = $ubase + $sign * ($lvl / 2);
            break;
        case 102:
            $result = $ubase + $sign * $lvl;
            break;
        case 103:
            $result = $ubase + $sign * $lvl * 2;
            break;
        case 104:
            $result = $ubase + $sign * $lvl * 3;
            break;
        case 105:
        case 107:
            $result = $ubase + $sign * $lvl * 4;
            break;
        case 108:
            $result = floor($ubase + $sign * $lvl / 3);
            break;
        case 109:
            $result = floor($ubase + $sign * $lvl / 4);
            break;
        case 110:
            $result = floor($ubase + $lvl / 5);
            break;
        case 111:
            $result = $ubase + 5 * ($lvl - 16);
            break;
        case 112:
            $result = $ubase + 8 * ($lvl - 24);
            break;
        case 113:
            $result = $ubase + 12 * ($lvl - 34);
            break;
        case 114:
            $result = $ubase + 15 * ($lvl - 44);
            break;
        case 115:
            $result = $ubase + 15 * ($lvl - 54);
            break;
        case 116:
            $result = floor($ubase + 8 * ($lvl - 24));
            break;
        case 117:
            $result = $ubase + 11 * ($lvl - 34);
            break;
        case 118:
            $result = $ubase + 17 * ($lvl - 44);
            break;
        case 119:
            $result = floor($ubase + $lvl / 8);
            break;
        case 121:
            $result = floor($ubase + $lvl / 3);
            break;

        default:
            if ($form < 100) {
                $result = $ubase + ($lvl * $form);
            }
    } // end switch
    if ($max != 0) {
        if ($sign == 1) {
            if ($result > $max) {
                $result = $max;
            }
        } else {
            if ($result < $max) {
                $result = $max;
            }
        }
    }
    if (($base < 0) && ($result > 0)) {
        $result *= -1;
    }

    return $result;
}

function CalcBuffDuration($lvl, $form, $duration)
{ // spells.cpp, carefull, return value in ticks, not in seconds
    //$return_buffer .= " Duration lvl=$lvl, form=$form, duration=$duration ";
    switch ($form) {
        case 0:
            return 0;
            break;
        case 1:
            $i = ceil($lvl / 2);

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 2:
            $i = ceil($duration / 5 * 3);

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 3:
            $i = $lvl * 30;

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 4:
            return $duration;
            break;
        case 5:
            $i = $duration;

            return ($i < 3 ? ($i < 1 ? 1 : $i) : 3);
            break;
        case 6:
            $i = ceil($lvl / 2);

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 7:
            $i = $lvl;

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 8:
            $i = $lvl + 10;

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 9:
            $i = $lvl * 2 + 10;

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 10:
            $i = $lvl * 3 + 10;

            return ($i < $duration ? ($i < 1 ? 1 : $i) : $duration);
            break;
        case 11:
        case 12:
            return $duration;
            break;
        case 50:
            return 72000;
        case 3600:
            return ($duration ? $duration : 3600);
    }
}

function SpecialAttacks($att)
{
    $data = '';
    $v    = '';
    // from mobs.h
    for ($i = 0; $i < strlen($att); $i++) {
        switch ($att{$i}) {
            case 'A' :
                $data .= $v . " Immune to melee";
                $v    = ', ';
                break;
            case 'B' :
                $data .= $v . " Immune to magic";
                $v    = ', ';
                break;
            case 'C' :
                $data .= $v . " Uncharmable";
                $v    = ', ';
                break;
            case 'D' :
                $data .= $v . " Unfearable";
                $v    = ', ';
                break;
            case 'E' :
                $data .= $v . " Enrage";
                $v    = ', ';
                break;
            case 'F' :
                $data .= $v . " Flurry";
                $v    = ', ';
                break;
            case 'f' :
                $data .= $v . " Immune to fleeing";
                $v    = ', ';
                break;
            case 'I' :
                $data .= $v . " Unsnarable";
                $v    = ', ';
                break;
            case 'M' :
                $data .= $v . " Unmezzable";
                $v    = ', ';
                break;
            case 'N' :
                $data .= $v . " Unstunable";
                $v    = ', ';
                break;
            case 'O' :
                $data .= $v . " Immune to melee except bane";
                $v    = ', ';
                break;
            case 'Q' :
                $data .= $v . " Quadruple Attack";
                $v    = ', ';
                break;
            case 'R' :
                $data .= $v . " Rampage";
                $v    = ', ';
                break;
            case 'S' :
                $data .= $v . " Summon";
                $v    = ', ';
                break;
            case 'T' :
                $data .= $v . " Triple Attack";
                $v    = ', ';
                break;
            case 'U' :
                $data .= $v . " Unslowable";
                $v    = ', ';
                break;
            case 'W' :
                $data .= $v . " Immune to melee except magical";
                $v    = ', ';
                break;
        }
    }

    return $data;
}

function price($price) {
	global $icons_url;
    $res = "";
    if ($price >= 1000) {
        $p = floor($price / 1000);
        $price -= $p * 1000;
    }
    if ($price >= 100) {
        $g = floor($price / 100);
        $price -= $g * 100;
    }
    if ($price >= 10) {
        $s = floor($price / 10);
        $price -= $s * 10;
    }
    $c = $price;
    if ($p > 0) {
        $res = $p . " <img src = '" . $icons_url . "item_644.png' width = '14' height = '14'>";
        $sep = " ";
    }
    if ($g > 0) {
        $res .= $sep . $g . " <img src = '" . $icons_url . "item_645.png' width = '14' height = '14'>";
        $sep = " ";
    }
    if ($s > 0) {
        $res .= $sep . $s . " <img src = '" . $icons_url . "item_646.png' width = '14' height = '14'>";
        $sep = " ";
    }
    if ($c > 0) {
        $res .= $sep . $c . " <img src = '" . $icons_url . "item_647.png' width = '14' height = '14'>";
    }

    return $res;
}

function sign($val)
{
    if ($val > 0) {
        return "+$val";
    } else {
        return $val;
    }
}

function isinteger($val)
{
    return (intval($val) == $val);
}

function CanThisNPCDoubleAttack($class, $level)
{ // mob.cpp
    if ($level > 26) {
        return true;
    } #NPC over lvl 26 all double attack
    switch ($class) {
        case 0: # monks and warriors
        case 1:
        case 20:
        case 26:
        case 27:
            if ($level < 15) {
                return false;
            }
            break;
        case 9: # rogues
        case 28:
            if ($level < 16) {
                return false;
            }
            break;
        case 4: # rangers
        case 23:
        case 5: # shadowknights
        case 24:
        case 3: # paladins
        case 22:
            if ($level < 20) {
                return false;
            }
            break;
    }

    return false;
}

function Pagination($targetpage, $page, $total_pages, $limit, $adjacents)
{

    /* Setup page vars for display. */
    if ($page == 0) {
        $page = 1;
    }                    //if no page var is given, default to 1.
    $prev     = $page - 1;                            //previous page is page - 1
    $next     = $page + 1;                            //next page is page + 1
    $lastpage = ceil($total_pages / $limit);        //lastpage is = total pages / items per page, rounded up.
    $lpm1     = $lastpage - 1;                        //last page minus 1

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<div class=\"pagination\">";
        //previous button
        if ($page > 1) {
            $pagination .= "<a href=\"$targetpage&page=$prev\">previous</a>";
        } else {
            $pagination .= "<span class=\"disabled\">previous</span>";
        }

        //pages
        if ($lastpage < 7 + ($adjacents * 2))    //not enough pages to bother breaking it up
        {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    $pagination .= "<span class=\"current\">$counter</span>";
                } else {
                    $pagination .= "<a href=\"$targetpage&page=$counter\">$counter</a>";
                }
            }
        } elseif ($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
        {
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<span class=\"current\">$counter</span>";
                    } else {
                        $pagination .= "<a href=\"$targetpage&page=$counter\">$counter</a>";
                    }
                }
                $pagination .= "...";
                $pagination .= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";
            } //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<a href=\"$targetpage&page=1\">1</a>";
                $pagination .= "<a href=\"$targetpage&page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<span class=\"current\">$counter</span>";
                    } else {
                        $pagination .= "<a href=\"$targetpage&page=$counter\">$counter</a>";
                    }
                }
                $pagination .= "...";
                $pagination .= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";
            } //close to end; only hide early pages
            else {
                $pagination .= "<a href=\"$targetpage&page=1\">1</a>";
                $pagination .= "<a href=\"$targetpage&page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<span class=\"current\">$counter</span>";
                    } else {
                        $pagination .= "<a href=\"$targetpage&page=$counter\">$counter</a>";
                    }
                }
            }
        }

        //next button
        if ($page < $counter - 1) {
            $pagination .= "<a href=\"$targetpage&page=$next\">next</a>";
        } else {
            $pagination .= "<span class=\"disabled\">next</span>";
        }
        $pagination .= "</div>";
    }

    return $pagination;
}


// Function to build item stats tables
// Used for item.php as well as for tooltips for items
function return_item_stat_box($item, $show_name_icon) {
    global $dbitypes, $dam2h, $dbbagtypes, $dbskills, $icons_url, $spells_table, $dbiaugrestrict, $dbiracenames;

    $html_string = "";
    $html_string .= "<table width='100%'><tr><td valign='top'>";
    if ($show_name_icon) {
        $html_string .= "<h4 style='margin-top:0'>" . $item["Name"] . "</h4></td>";
        $html_string .= "<td><img src='" . $icons_url . "item_" . $item["icon"] . ".png' align='right' valign='top'/></td></tr><tr><td>";
    }

    $html_string .= "<table width='100%' cellpadding='0' cellspacing='0'>";

    /* Item Tags */
    $item_tags = "";

    $html_string .= "<tr>";
    $html_string .= "<td colspan='2' nowrap='1'>";
    if ($item["itemtype"] == 54)
        $item_tags .= " Augment";
		
    if ($item["magic"] == 1)
        $item_tags .= " Magic,";
		
    if ($item["loreflag"] == 1)
        $item_tags .= " Lore,";
		
    if ($item["nodrop"] == 0)
        $item_tags .= " No Trade,";
		
    if ($item["norent"] == 0)
        $item_tags .= " No Rent,";
		
    if ($item_tags)
        $html_string .= substr($item_tags, 0, -1);

    $html_string .= "</td></tr>";

    /* Classes */
    if ($item["classes"] > 0)
        $html_string .= "<tr><td colspan='2'><b>Class: </b>" . get_class_usable_string($item["classes"]) . "</td></tr>";

    /* Races */
    if ($item["races"] > 0)
        $html_string .= "<tr><td colspan='2'><b>Race: </b>" . get_race_usable_string($item["races"]) . "</td></tr>";

    /* Deity */
    if ($item["deity"] > 0)
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Deity: </b>" . get_deity_usable_string($item["deity"]) . "</td></tr>";

    /* Slots */
    if ($item["slots"] > 0)
        $html_string .= "<tr><td colspan='2'><b>Slot(s):</b> " . get_slots_string($item["slots"]) . "</td></tr>";
	
    if ($item["slots"] == 0)
        $html_string .= "<tr><td colspan='2'><b>Slot(s):</b> None</td></tr>";

    $TypeString = "";
    switch ($item["itemtype"]) {
        case 0: // 1HS
        case 2: // 1HP
        case 3: // 1HB
        case 42: // H2H
        case 1: // 2hs
        case 4: // 2hb
        case 35: // 2hp
            $TypeString = "Skill";
            break;
        default:
            $TypeString = "Item Type";
            break;
    }
    // Item type or Skill
    // Bags show as 1HS


    // Bag-specific information
    if ($item["bagslots"] > 0) {
        $html_string .= "<tr><td width='0%' nowrap='1'><b>Item Type: </b>Container</td></tr>";
        $html_string .= "<tr><td width='0%' nowrap='1'><b>Bag Slots: </b>" . $item["bagslots"] . "</td></tr>";
        if ($item["bagtype"] > 0)
            $html_string .= "<tr><td width='0%' nowrap='1'><b>Tradeskill Container: </b>" . $dbbagtypes[$item["bagtype"]] . "</td></tr>";
			
        if ($item["bagwr"] > 0)
            $html_string .= "<tr><td width='0%'  nowrap='1'><b>Weight Reduction: </b>" . $item["bagwr"] . "%</td></tr>";
		
        $html_string .= "<tr><td width='0%' nowrap='1' colspan='2'>This can hold " . get_size_string($item["bagsize"]) . " and smaller items.</td></tr>";
    }

    $html_string .= "</table>";

    $html_string .= "<br><table>";

    // Weight, Size, Rec/Req Level, skill
    $html_string .= "<tr valign='top'><td>";
    $html_string .= "<table style='width: 125px;'>";
    $html_string .= "<tr><td><b>Size: </b></td><td style='text-align:right'>" . get_size_string($item["size"]) . "</td></tr>";
    $html_string .= get_item_stat_string("Weight", number_format(($item["weight"] / 10), 1));

    if ($dbitypes[$item["itemtype"]] != "" && $item["bagslots"] == 0) {
        if ($item["slots"] == 0) {
            $html_string .= "<tr><td><b>" . $TypeString . ": </b></td><td>Inventory";
        } else {
            $html_string .= "<tr><td><b>" . $TypeString . ": </b></td><td style='text-align:right'>" . $dbitypes[$item["itemtype"]];
        }
        if ($item["stackable"] > 0) {
            $html_string .= " (stackable)";
        }
        $html_string .= "</td></tr>";
    }

    $html_string .= get_item_stat_string("Recommended Level", $item["reclevel"]);
    $html_string .= get_item_stat_string("Required Level", $item["reqlevel"]);
    $html_string .= "</table>";
    $html_string .= "</td><td>";

    // AC, HP, Mana, End, Haste
    $html_string .= "<table style='width: 125px;'>";
    $html_string .= get_item_stat_string("Armor Class", number_format($item["ac"]));
    $html_string .= get_item_stat_string("Attack", number_format($item["attack"]));
    $html_string .= get_item_stat_string("Health", number_format($item["hp"]));
    $html_string .= get_item_stat_string("Mana", number_format($item["mana"]));
    $html_string .= get_item_stat_string("Endurance", number_format($item["endur"]));
    $html_string .= get_item_stat_string("Haste", $item["haste"] . "%");
    $html_string .= "</table>";

    $html_string .= "</td><td>";

    // Base Damage, Ele/Bane/BodyType Damage, BS Damage, Delay, Range, Damage Bonus, Range
    $html_string .= "<table style='width: 125px;'>";
    $html_string .= get_item_stat_string("Base Damage", number_format($item["damage"]));
    $html_string .= get_item_stat_string(
        ucfirstwords($dbelements[$item["elemdmgtype"]]) . " Damage",
        $item["elemdmgamt"]
    );
    if (($item["banedmgrace"] > 0) && ($item["banedmgamt"] != 0)) {
        $html_string .= "<tr><td><b>Bane Damage (";
        $html_string .= $dbiracenames[$item["banedmgrace"]];
        $html_string .= ") </b></td><td>" . number_format($item["banedmgamt"]) . "</td></tr>";
    }
    $html_string .= get_item_stat_string($dbbodytypes[$item["banedmgbody"]], number_format($item["banedmgamt"]));
    $html_string .= get_item_stat_string("Backstab Damage", number_format($item["backstabdmg"]));
    $html_string .= get_item_stat_string("Delay", $item["delay"]);
    if ($item["damage"] > 0) {
        switch ($item["itemtype"]) {
            case 0: // 1HS
            case 2: // 1HP
            case 3: // 1HB
            case 42: // H2H
                $dmgbonus    = 13; // floor((65-25)/3)  main hand
                $html_string .= "<tr><td><b>Damage Bonus: </b></td><td>$dmgbonus</td></tr>";
                break;
            case 1: // 2hs
            case 4: // 2hb
            case 35: // 2hp
                $dmgbonus    = $dam2h[$item["delay"]];
                $html_string .= "<tr><td><b>Damage Bonus: </b></td><td>$dmgbonus</td></tr>";
                break;
        }
    }
    $html_string .= get_item_stat_string("Range", $item["range"]);
    $html_string .= "</table>";
    $html_string .= "</td></tr><tr><td colspan='2'>&nbsp;</td></td>";

    $html_string .= "<tr valign='top'><td>";

    $html_string .= "<table style='width:100%'>";
    $html_string .= get_item_stat_string("Strength", $item["astr"], $item["heroic_str"], "Gold");
    $html_string .= get_item_stat_string("Stamina", $item["asta"], $item["heroic_sta"], "Gold");
    $html_string .= get_item_stat_string("Intelligence", $item["aint"], $item["heroic_int"], "Gold");
    $html_string .= get_item_stat_string("Wisdom", $item["awis"], $item["heroic_wis"], "Gold");
    $html_string .= get_item_stat_string("Agility", $item["aagi"], $item["heroic_agi"], "Gold");
    $html_string .= get_item_stat_string("Dexterity", $item["adex"], $item["heroic_dex"], "Gold");
    $html_string .= get_item_stat_string("Charisma", $item["acha"], $item["heroic_cha"], "Gold");
    $html_string .= "</table>";

    $html_string .= "</td><td>";

    $html_string .= "<table style='width:100%'>";
    $html_string .= get_item_stat_string("Magic", $item["mr"], $item["heroic_mr"], "Gold");
    $html_string .= get_item_stat_string("Fire", $item["fr"], $item["heroic_fr"], "Gold");
    $html_string .= get_item_stat_string("Cold", $item["cr"], $item["heroic_cr"], "Gold");
    $html_string .= get_item_stat_string("Disease", $item["dr"], $item["heroic_dr"], "Gold");
    $html_string .= get_item_stat_string("Poison", $item["pr"], $item["heroic_pr"], "Gold");
    $html_string .= get_item_stat_string("Corruption", $item["svcorruption"], $item["heroic_svcorrup"], "Gold");
    $html_string .= "</table>";

    $html_string .= "</td><td>";

    $html_string .= "<table style='width:100%'>";
    $html_string .= get_item_stat_string("HP Regen", number_format($item["regen"]));
    $html_string .= get_item_stat_string("Mana Regen", number_format($item["manaregen"]));
    $html_string .= get_item_stat_string("Endurance Regen", number_format($item["enduranceregen"]));
    $html_string .= get_item_stat_string("Heal Amount", number_format($item["healamt"]));
    $html_string .= get_item_stat_string("Spell Damage", number_format($item["spelldmg"]));
    $html_string .= get_item_stat_string("Spell Shielding", $item["spellshield"]);
    $html_string .= get_item_stat_string("Combat Effects", $item["combateffects"]);
    $html_string .= get_item_stat_string("Shielding", $item["shielding"]);
    $html_string .= get_item_stat_string("DoT Shielding", $item["dotshielding"]);
    $html_string .= get_item_stat_string("Avoidance", $item["avoidance"]);
    $html_string .= get_item_stat_string("Accuracy", $item["accuracy"]);
    $html_string .= get_item_stat_string("Stun Resist", $item["stunresist"]);
    $html_string .= get_item_stat_string("Strikethrough", $item["strikethrough"]);
    $html_string .= get_item_stat_string("Damage Shield", $item["damageshield"]);
    $html_string .= "</td></tr></table>";

    $html_string .= "</td></tr></table><br>";
    if ($item["extradmgamt"] > 0)
        $html_string .= "<tr><td><b>" . $dbskills[$item["extradmgskill"]] . " Damage: </b>" . $item["extradmgamt"] . "</td></tr>";

    // Skill Mod
    if (($item["skillmodtype"] > 0) && ($item["skillmodvalue"] != 0))
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Skill Modifier: " . $dbskills[$item["skillmodtype"]] . ": </b>" . $item["skillmodvalue"] . "%</td></tr>";
	
    // Augmentations
    for ($i = 1; $i <= 6; $i++) {
        if ($item["augslot" . $i . "type"] > 0)
            $html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><img src='images/icons/blank_slot.gif' style='width:auto;height:10px'> <b>Slot " . $i . ": </b>Type " . $item["augslot" . $i . "type"] . "</td></tr>";
    }
    $html_string .= '<td><td>&nbsp;</td><td></tr>';
    //item proc
    if (($item["proceffect"] > 0) && ($item["proceffect"] < 65535)) {
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Proc Effect: </b><a href='?a=spell&id=" . $item["proceffect"] . "'>" . get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $item["proceffect"]
            ) . "</a>";
        if ($item["proclevel2"] > 0)
            $html_string .= "<br><b>Level for effect: </b>" . $item["proclevel2"];
		
        $html_string .= "</td></tr>";
    }
    // worn effect
    if (($item["worneffect"] > 0) && ($item["worneffect"] < 65535)) {
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Worn Effect: </b><a href='?a=spell&id=" . $item["worneffect"] . "'>" . get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $item["worneffect"]
            ) . "</a>";
        if ($item["wornlevel"] > 0)
            $html_string .= "<br><b>Level for effect: </b>" . $item["wornlevel"];
		
        $html_string .= "</td></tr>";
    }
    // focus effect
    if (($item["focuseffect"] > 0) && ($item["focuseffect"] < 65535)) {
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Focus Effect: </b><a href='?a=spell&id=" . $item["focuseffect"] . "'>" . get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $item["focuseffect"]
            ) . "</a>";
        if ($item["focuslevel"] > 0)
            $html_string .= "<br/><b>Level for effect: </b>" . $item["focuslevel"];
		
        $html_string .= "</td></tr>";
    }
    // clicky effect
    if (($item["clickeffect"] > 0) && ($item["clickeffect"] < 65535)) {
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Click Effect: </b><a href='?a=spell&id=" . $item["clickeffect"] . "'>" . get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $item["clickeffect"]
            ) . "</a> (";
        if ($item["clicktype"] == 4)
            $html_string .= "Must Equip. ";
		
        if ($item["casttime"] > 0)
            $html_string .= "<b>Cast Time: </b>" . ($item["casttime"] / 1000) . " Seconds";
        else
            $html_string .= "<b>Cast Time: </b>Instant";
        $html_string .= ")";
        if ($item["clicklevel"] > 0)
            $html_string .= "<br/><b>Level for effect: </b>" . $item["clicklevel"];
		
        if ($item["maxcharges"] > 0)
            $html_string .= "<br/><b>Charges: </b>" . $item["maxcharges"];
        elseif ($item["maxcharges"] < 0)
            $html_string .= "<br/><b>Charges: </b>Unlimited";
        else
            $html_string .= "<br/><b>Charges: </b>None";
		
        $html_string .= "</td></tr>";
    }
    // scroll
    if (($item["scrolleffect"] > 0) && ($item["scrolleffect"] < 65535)) {
        $html_string .= "<tr><td colspan='2' nowrap='1'><b>Scroll Effect: </b><a href='?a=spell&id=" . $item["scrolleffect"] . "'>" . get_field_result(
                "name",
                "SELECT name FROM $spells_table WHERE id=" . $item["scrolleffect"]
            ) . "</a>";
        $html_string .= "</td></tr>";
    }
    // bard item ?
    if (($item["bardtype"] > 22) && ($item["bardtype"] < 65535)) {
        $html_string .= "<tr><td width='0%' nowrap='1' colspan='2'><b>Bard Skill: </b> " . $dbbardskills[$item["bardtype"]];
        if ($dbbardskills[$item["bardtype"]] == "")
            $html_string .= "Unknown" . $item["bardtype"];
		
        $val = ($item["bardvalue"] * 10) - 100;
        if ($val > 0)
            $html_string .= " (" . $val . "%)</td></tr>";
    }

    // Augmentation type
    if ($item["itemtype"] == 54) {
        if ($item["augtype"] > 0) {
            $Comma    = "";
            $AugSlots = "";
            $AugType  = $item["augtype"];
            $Bit      = 1;
            for ($i = 1; $i <= 30; $i++) {
                if ($Bit <= $AugType && $Bit & $AugType) {
                    $AugSlots .= $Comma . $i;
                    $Comma = ", ";
                }
                $Bit *= 2;
            }
            $html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Slot(s): </b>" . $AugSlots . "</td></tr>";
        } else {
            $html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Slot(s): </b>All Slots</td></tr>";
        }
        if ($item["augrestrict"] > 0) {
            if ($item["augrestrict"] > 15)
                $html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Restriction: </b>Unknown</td></tr>";
            else {
                $Restriction = $dbiaugrestrict[$item["augrestrict"]];
                $html_string .= "<tr><td colspan='2' nowrap='1'><b>Augmentation Restriction: </b>$Restriction</td></tr>";
            }
        }
    }

    $ItemPrice = $item["price"];
    $ItemValue = "";
    $Platinum  = 0;
    $Gold = 0;
    $Silver = 0;
    $Copper = 0;

    if ($ItemPrice > 1000)
        $Platinum = ((int)($ItemPrice / 1000));
	
    if (($ItemPrice - ($Platinum * 1000)) > 100)
        $Gold = ((int)(($ItemPrice - ($Platinum * 1000)) / 100));
	
    if (($ItemPrice - ($Platinum * 1000) - ($Gold * 100)) > 10)
        $Silver = ((int)(($ItemPrice - ($Platinum * 1000) - ($Gold * 100)) / 10));
	
    if (($ItemPrice - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10)) > 0)
        $Copper = ($ItemPrice - ($Platinum * 1000) - ($Gold * 100) - ($Silver * 10));

    $ItemValue   .= "<tr><td><br><b>Value: </b>";
    $ItemValue   .= $Platinum . " <img src='" . $icons_url . "item_644.png' width='14' height='14'/> " .
                    $Gold . " <img src='" . $icons_url . "item_645.png' width='14' height='14'/> " .
                    $Silver . " <img src='" . $icons_url . "item_646.png' width='14' height='14'/> " .
                    $Copper . " <img src='" . $icons_url . "item_647.png' width='14' height='14'/>";
    $ItemValue   .= "</td></tr>";
    $html_string .= $ItemValue;

    $html_string .= "<br></td></tr></table><br>";

    return $html_string;

}

function get_item_icon_from_id($id) {
    global $icon_cache, $icons_url;

    if ($icon_cache[$id])
        return $icon_cache[$id];

    $query  = "SELECT `icon` FROM `items` WHERE `id` = " . $id;
    $result = db_mysql_query($query);
    while ($row = mysqli_fetch_array($result)) {
        $icon_cache[$id] = '<img src="' . $icons_url . 'item_' . $row['icon'] . '.png" style = "width: 15px; height: auto;">';
        return $icon_cache[$id];
    }
}

function get_task_name($task_id) {
	global $task_table;
	$query = "SELECT `title` FROM `$task_table` WHERE `id` = '$task_id'";
	$result = db_mysql_query($query);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			return $row["title"];
		}
	}
	return "";
}

function get_npc_name($npc_id) {
	global $npc_types_table;
	$query = "SELECT `name` FROM `$npc_types_table` WHERE `id` = '$npc_id'";
	$result = db_mysql_query($query);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			return $row["name"];
		}
	}
	return "";
}

function get_item_name($item_id) {
	global $items_table;
	$query = "SELECT `name` FROM `$items_table` WHERE `id` = '$item_id'";
	$result = db_mysql_query($query);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			return $row["name"];
		}
	}
	return "";
}

function get_zone_names($zones) {
	global $dbizonenames;
	$zones = explode(",", $zones);
	$zone_array = array();
	foreach ($zones as $zone) {
		array_push($zone_array, "<a href = '?a=zone&name=" . $dbizonenames[$zone][0] . "'>" . $dbizonenames[$zone][1] . "</a>");
	}
	return implode(", ", $zone_array) . ".";
}

function get_zones_by_era($era) {
	global $zones_table;
	$query = "SELECT `long_name`, `short_name` FROM $zones_table WHERE `expansion` = '$era' AND `min_status` = '0'";
	$result = db_mysql_query($query);
	$message = "";
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$message .= "<li><a href = '?a=zone&name=" . $row["short_name"] . "'>" . $row["long_name"] . "</a></li>";
		}
	}
	return $message;	
}