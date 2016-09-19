<?php
/**
 * Created by PhpStorm.
 * User: cmiles
 * Date: 9/19/2016
 * Time: 1:52 AM
 */

function return_npc_primary_faction($faction_id){
    global
        $faction_list_table,
        $npc_faction_table;

    if($faction_id < 0)
        return;

    $query = "
        SELECT
            $faction_list_table.`name`,
            $faction_list_table.id
        FROM
            $faction_list_table,
            $npc_faction_table
        WHERE
            $npc_faction_table.id = " . $faction_id . "
        AND $npc_faction_table.primaryfaction = $faction_list_table.id
    ";
    $faction = GetRowByQuery($query);

    return "<a href='?a=faction&id=" . $faction["id"] . "'>" . $faction["name"] . "</a>";
}

?>