<?php

$name = mysql_real_escape_string($_GET['q']);

$page_title = "Global Search :: " . $_GET['q'];

if($_GET['get_data']){
    $name = mysql_real_escape_string($_GET['get_data']);
    if($_GET['fetch_type'] == "global_zones"){
        $query = "SELECT * FROM `zone` WHERE `short_name` LIKE '%" . $name . "%' OR `long_name` LIKE '%" . $name . "%' ORDER BY `long_name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysql_fetch_array($result)) {
            echo '<li><a href="?a=zone&name=' . $row['short_name'] . '">' . get_npc_name_human_readable($row['long_name']) . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_factions"){
        $query = "SELECT * FROM `faction_list` WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysql_fetch_array($result)) {
            echo '<li><a href="?a=faction&id=' . $row['id'] . '">' . $row['name'] . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_mobs"){
        $query = "SELECT * FROM `npc_types` WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysql_fetch_array($result)) {
            echo '<li><a href="?a=npc&id=' . $row['id'] . '">' . get_npc_name_human_readable($row['name']) . '</a></li>';
        }
        echo '</ul>';
    }
    if($_GET['fetch_type'] == "global_items") {
        $query = "SELECT * FROM `items` WHERE `name` LIKE '%" . $name . "%' ORDER BY `name`";
        $result = db_mysql_query($query);
        echo '<ul>';
        while ($row = mysql_fetch_array($result)) {
            echo '<a href="?a=item&id=' . $row['id'] . '">' . return_item_icon_from_icon_id($row['icon'], 15) . ' ' . $row['Name'] . '</a><br>';
        }
        echo '</ul>';
    }

    exit;
}

$tab_title = "";

$global_search_count = array(
    "zones" => array("Zones", "SELECT COUNT(*) as found_count FROM `zone` WHERE `short_name` LIKE '%" . $name . "%' OR `long_name` LIKE '%" . $name . "%'"),
    "mobs" => array("Mobs", "SELECT COUNT(*) as found_count FROM `npc_types` WHERE `name` LIKE '%" . $name . "%'"),
    "items" => array("Items", "SELECT COUNT(*) as found_count FROM `items` WHERE `Name` LIKE '%" . $name . "%'"),
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
    while ($row = mysql_fetch_array($result)) {
        if($row['found_count'] > 0)
            $tab_title .= "<li onclick='tablistview(this.childNodes[0]);' id='global_" . $key . "'><a  href='javascript:;' onclick='fetch_global_data(\"global_" . $key . "\")'>" . $value[0]. " (" . $row['found_count'] . ")</a></li>";
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
    $(".tablist li").each(function(i) {
        u = "#active_search_content";
        $.get("?a=global_search&get_data=' . urlencode($_GET['q']) . '&fetch_type=" + $(this).attr("id") + "&v_ajax", function (data) {
            $(u).html(data);
        });
        $(this).addClass("current");
        return false;
    });
    function fetch_global_data(type){
        console.log(type);

        $(".tablist li").each(function(i) {
            $(this).removeClass("current");
        });

        $("#" + type).addClass("current");

        u = "#active_search_content";
        $.get("?a=global_search&get_data=' . urlencode($_GET['q']) . '&fetch_type=" + type + "&v_ajax", function (data) {
            $(u).html(data);
        });
    }
</script>';

?>
