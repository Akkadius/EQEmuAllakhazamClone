<?php

$name = mysql_real_escape_string($_GET['q']);

$page_title = "Global Search :: " . $_GET['q'];

$tab_title = "";

/* Zones */
$query = "SELECT COUNT(*) as found_count FROM `zone` WHERE `short_name` LIKE '%" . $name . "%' OR `long_name` = '%" . $name . "%'";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title = "<li class='current' onclick='tablistview(this.childNodes[0]);'><a  href='javascript:;' onclick='tablistview(this);'>Zones (" . $row['found_count'] . ")<b></b></a></li>";
}

/* NPCS */
$query = "SELECT COUNT(*) as found_count FROM `npc_types` WHERE `name` LIKE '%" . $name . "%'";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title .= "<li onclick='tablistview(this.childNodes[0]);'><a id='Mobs_tab' href='javascript:;' onclick='tablistview(this);'>Mobs (" . $row['found_count'] . ")<b></b></a></li>";
}

/* Items */
$query = "SELECT COUNT(*) as found_count FROM `items` WHERE `Name` LIKE '%" . $name . "%'";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title .= "<li onclick='tablistview(this.childNodes[0]);'><a href='javascript:;' onclick='tablistview(this);'>Items (" . $row['found_count'] . ")<b></b></a></li>";
}

/* Factions */
$query = "SELECT COUNT(*) as found_count FROM `faction_list` WHERE `name` LIKE '%" . $name . "%'";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title .= "<li onclick='tablistview(this.childNodes[0]);'><a href='javascript:;' onclick='tablistview(this);'>Factions (" . $row['found_count'] . ")<b></b></a></li>";
}

/* Tradeskills */
$query = "SELECT COUNT(*) as found_count FROM `tradeskill_recipe`  WHERE `name` LIKE '%" . $name . "%'";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title .= "<li onclick='tablistview(this.childNodes[0]);'><a href='javascript:;' onclick='tablistview(this);'>Tradeskills (" . $row['found_count'] . ")<b></b></a></li>";
}

/* Forage */
$query = "
    SELECT
    COUNT(*) as found_count
    FROM
    forage
    INNER JOIN items ON forage.Itemid = items.id
    WHERE `name` LIKE '%" . $name . "%'
";
$result = db_mysql_query($query);
while ($row = mysql_fetch_array($result)) {
    if($row['found_count'] > 0)
        $tab_title .= "<li onclick='tablistview(this.childNodes[0]);'><a href='javascript:;' onclick='tablistview(this);'>Foraging (" . $row['found_count'] . ")<b></b></a></li>";
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

?>
