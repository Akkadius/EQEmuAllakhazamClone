<?php
$page_title = "Populated Zones List";
$print_buffer .= "<table class=''><tr valign=top><td>";

$query = "
    SELECT
        $zones_table.short_name AS short_name,
        $zones_table.long_name AS long_name,
        COUNT($spawn2_table.id) AS spawns,
        $zones_table.zoneidnumber AS zoneidnumber
    FROM
        $zones_table,
        $spawn_entry_table,
        $spawn2_table
    WHERE
        $spawn2_table.spawngroupID = $spawn_entry_table.spawngroupID
    AND $spawn2_table.zone = $zones_table.short_name
";
/*foreach ($IgnoreZones AS $zid) {
  $query.=" AND $tbzones.short_name!='$zid'";
}
*/
$query .= " GROUP BY $spawn2_table.zone
        ORDER BY $zones_table.long_name ASC";
$result = db_mysql_query($query) or message_die('zones.php', 'MYSQL_QUERY', $query, mysqli_error());
$print_buffer .= "<table class='display_table datatable container_div'><tr>
       <td style='font-weight:bold'>Name</td>
       <td style='font-weight:bold'>Short name</td>
       <td style='font-weight:bold'>ID</td>
       <td style='font-weight:bold'>Spawn points</td>
       ";
while ($row = mysqli_fetch_array($result)) {
    $print_buffer .= "<tr>
         <td><a href='?a=zone&name=" . $row["short_name"] . "''>" . $row["long_name"] . "</a></td>
         <td>" . $row["short_name"] . "</td>
         <td>" . $row["zoneidnumber"] . "</td>
         <td align=center>" . $row["spawns"] . "</td>
         </tr>";
}
$print_buffer .= "</table>";
$print_buffer .= "</td><td width=0% nowrap>";
$print_buffer .= "</td></tr></table>";


?>
