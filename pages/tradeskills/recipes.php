<?php
$page_title = "Search Recipes";

$minskill = (isset($_GET['minskill']) ? $_GET['minskill'] : 0);
$maxskill = (isset($_GET['maxskill']) ? $_GET['maxskill'] : 0);
$isearch = (isset($_GET['isearch']) ? $_GET['isearch'] : '');
$iname = (isset($_GET['iname']) ? $_GET['iname'] : '');
$iskill = (isset($_GET['iskill']) ? $_GET['iskill'] : 0);

if (!isset($maxskill))
    $maxskill = 0;
	
if (!isset($minskill))
    $minskill = 0;
	
if (!ctype_digit($maxskill))
    $maxskill = 0;
	
if (!ctype_digit($minskill))
    $minskill = 0;
	
if ($minskill > $maxskill) {
    $tempskill = $minskill;
    $minskill = $maxskill;
    $maxskill = $tempskill;
}

$print_buffer .= '
<div class="display_table container_div">
    <form method="GET" lpformnum="1">
        <table border="0">
            <input type="hidden" name="a" value="recipes">
            <tbody>
                <tr>
                    <td style="text-align:right"><b>Name</b>
                    </td>
                    <td>
                        <input type="text" value="" size="30" name="iname" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right"><b>Tradeskill</b>
                    </td>
                    <td>
                        <select name="iskill">
                            <option value="0" selected="1">-</option>
                            <option value="59">Alchemy</option>
                            <option value="60">Baking</option>
                            <option value="63">Blacksmithing</option>
                            <option value="65">Brewing</option>
                            <option value="55">Fishing</option>
                            <option value="64">Fletching</option>
                            <option value="68">Jewelery Making</option>
                            <option value="56">Poison Making</option>
                            <option value="69">Pottery Making</option>
                            <option value="58">Research</option>
                            <option value="61">Tailoring</option>
                            <option value="57">Tinkering</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right"><b>Minimum Skill</b>
                    </td>
                    <td>
                        <input type="text" value="0" size="4" name="minskill">
                    </td>
                </tr>
                <tr>
                    <td style="text-align:right"><b>Maximum Skill</b>
                    </td>
                    <td>
                        <input type="text" value="0" size="4" name="maxskill">
                    </td>
                </tr>
                <tr align="center">
                    <td> 
                        <br>
                        <a class="button submit">Search</a>
                        <a class="button" href="?a=recipes">Reset</a>
                        <input type="hidden" name="isearch" value="1">
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    </div>
    <br>

';

if (isset($isearch) && $isearch != "") {
    if ($minskill > $maxskill) {
        $tempskill = $minskill;
        $minskill = $maxskill;
        $maxskill = $tempskill;
    }
	
    $query = "
        SELECT
            $trade_skill_recipe_table.id,
            $trade_skill_recipe_table.`name`,
            $trade_skill_recipe_table.tradeskill,
            $trade_skill_recipe_table.trivial
        FROM
            $trade_skill_recipe_table
    ";
    $s = "WHERE";
    if ($iname != "") {
        $iname = str_replace(' ', '%', addslashes($iname));
        $query .= " $s $trade_skill_recipe_table.`name` like '%" . $iname . "%'";
        $s = "AND";
    }
	
    if ($iskill > 0) {
        $query .= " $s $trade_skill_recipe_table.tradeskill = '$iskill'";
        $s = "AND";
    }
	
    if ($minskill > 0) {
        $query .= " $s $trade_skill_recipe_table.trivial >= '$minskill'";
        $s = "AND";
    }
	
    if ($maxskill > 0) {
        $query .= " $s $trade_skill_recipe_table.trivial <= '$maxskill'";
        $s = "AND";
    }
	
    $query .= " ORDER BY $trade_skill_recipe_table.`name` ASC";
    $result = db_mysql_query($query);

    $print_buffer .= '<div>';
    if (isset($result))
        $print_buffer .= print_query_results($result, $max_items_returned, "?a=recipe&", "recipe", "recipes", "id", "name", "trivial");
	
    $print_buffer .= '</div>';
}
?>