<?php

// Trim whitespace from start and end of query; this works for direct entry and JS updates
$_GET['q'] = trim($_GET['q']);

$name = mysqli_real_escape_string($database, $_GET['q']);

$page_title = "Global Search :: " . $_GET['q'];

if($_GET['get_data']){
    $name = mysqli_real_escape_string($database, $_GET['get_data']);
    if($_GET['fetch_type'] == "global_tradeskills"){
        $query = "SELECT * FROM `tradeskill_recipe`  WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=recipe&id=' . $row['id'] . '">' . $row['name'] . ' ' . (trim(ucfirstwords($dbskills[$row["tradeskill"]])) ? '(' . ucfirstwords($dbskills[$row["tradeskill"]]) . ')' : '') . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_forage"){
        $query = "SELECT * FROM
            forage
            INNER JOIN items ON forage.Itemid = items.id
            WHERE `name` LIKE '%" . $name . "%'";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=item&id=' . $row['id'] . '">' . return_item_icon_from_icon_id($row['icon'], 15) . ' ' . $row['Name'] . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_spells"){
        $query = "SELECT * FROM `spells_new` WHERE `name` LIKE '%" . $name . "%'";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=spell&id=' . $row['id'] . '">' . '<img src="' . $icons_url . $row['new_icon'] . '.gif" style="border-radius:5px;height:15px;width:auto"> ' . $row['name'] . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_zones"){
        $query = "SELECT * FROM `zone` WHERE `short_name` LIKE '%" . $name . "%' OR `long_name` LIKE '%" . $name . "%' ORDER BY `long_name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=zone&name=' . $row['short_name'] . '">' . get_npc_name_human_readable($row['long_name']) . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_factions"){
        $query = "SELECT * FROM `faction_list` WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=faction&id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_mobs"){
        $query = "SELECT * FROM `npc_types` WHERE `name` LIKE '%" . str_replace(' ', '_', $name) . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<li><a href="?a=npc&id=' . $row['id'] . '">' . get_npc_name_human_readable($row['name']) . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_items") {
        $query = "SELECT * FROM `items` WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysqli_fetch_array($result)) {
            echo '<a href="?a=item&id=' . $row['id'] . '">' . return_item_icon_from_icon_id($row['icon'], 15) . ' ' . $row['Name'] . '</a><br>';
        }
        echo '</ul>';
    }

    exit;
}

$tab_title = "";

$global_search_count = array(
    "zones" => array("Zones", "SELECT COUNT(*) as found_count FROM `zone` WHERE `short_name` LIKE '%" . $name . "%' OR `long_name` LIKE '%" . $name . "%'"),
    "mobs" => array("Mobs", "SELECT COUNT(*) as found_count FROM `npc_types` WHERE `name` LIKE '%" . str_replace(' ', '_', $name) . "%'"),
    "items" => array("Items", "SELECT COUNT(*) as found_count FROM `items` WHERE `Name` LIKE '%" . $name . "%'"),
    "spells" => array("Spells", "SELECT COUNT(*) as found_count FROM `spells_new` WHERE `name` LIKE '%" . $name . "%'"),
    "factions" => array("Factions", "SELECT COUNT(*) as found_count FROM `faction_list` WHERE `name` LIKE '%" . $name . "%'"),
    "tradeskills" => array("Trade Skills", "SELECT COUNT(*) as found_count FROM `tradeskill_recipe`  WHERE `name` LIKE '%" . $name . "%'"),
    "forage" => array("Forage", "
        SELECT
        COUNT(*) as found_count
        FROM
        forage
        INNER JOIN items ON forage.Itemid = items.id
        WHERE `name` LIKE '%" . $name . "%'"
    ),
);


$tab_title = "";
foreach ($global_search_count as $key => $value){
    $result = db_mysql_query($value[1]);
    while ($row = mysqli_fetch_array($result)) {
        if($row['found_count'] > 0)
            $tab_title .= "<li id='global_" . $key . "'><a  href='javascript:;' onclick='fetch_global_data(\"global_" . $key . "\")'>" . $value[0]. " (" . $row['found_count'] . ")</a></li>";
    }
}

echo '
    <div class="tabwrapper">
        <ul class="tablist">
            ' . $tab_title . '
            </li>
        </ul>
        <br>
        <br>
        <br>
        <div id="active_search_content"></div>
    </div>
';

echo '<script type="text/javascript">

    global_tab_cache = [];

    $(".tablist li").each(function(i) {
        u = "#active_search_content";
        $.get("?a=global_search&get_data=' . urlencode($_GET['q']) . '&fetch_type=" + $(this).attr("id") + "&v_ajax", function (data) {
            $(u).html(data);
            global_tab_cache[$(this).attr("id")] = data;
        });
        $(this).addClass("current");
        return false;
    });
    function fetch_global_data(type){
        // console.log(type);

        $(".tablist li").each(function(i) {
            $(this).removeClass("current");
        });

        $("#" + type).addClass("current");

        if(global_tab_cache[type]){
            // console.log("cache hit");
            $(u).html(global_tab_cache[type]);
        }

        u = "#active_search_content";
        $.get("?a=global_search&get_data=' . urlencode($_GET['q']) . '&fetch_type=" + type + "&v_ajax", function (data) {
            $(u).html(data);
            global_tab_cache[type] = data;
        });
    }
</script>';

?>
