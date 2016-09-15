<?php
$Title = "Populated Zones List";
print "<table class=''><tr valign=top><td>";

$query = "SELECT $tbzones.short_name AS short_name,
               $tbzones.long_name AS long_name,
               COUNT($tbspawn2.id) AS spawns,
               $tbzones.zoneidnumber AS zoneidnumber
        FROM $tbzones,$tbspawnentry,$tbspawn2
        WHERE $tbspawn2.spawngroupID=$tbspawnentry.spawngroupID 
          AND $tbspawn2.zone=$tbzones.short_name";
/*foreach ($IgnoreZones AS $zid) {
  $query.=" AND $tbzones.short_name!='$zid'";
}
*/
$query .= " GROUP BY $tbspawn2.zone
        ORDER BY $tbzones.long_name ASC";
$result = mysql_query($query) or message_die('zones.php', 'MYSQL_QUERY', $query, mysql_error());
print "<table class='display_table datatable container_div'><tr>
       <td style='font-weight:bold'>Name</td>
       <td style='font-weight:bold'>Short name</td>
       <td style='font-weight:bold'>ID</td>
       <td style='font-weight:bold'>Spawn points</td>
       ";
while ($row = mysql_fetch_array($result)) {
    print "<tr>
         <td><a href='?a=zone&name=" . $row["short_name"] . "''>" . $row["long_name"] . "</a></td>
         <td>" . $row["short_name"] . "</td>
         <td>" . $row["zoneidnumber"] . "</td>
         <td align=center>" . $row["spawns"] . "</td>
         </tr>";
}
print "</table>";
print "</td><td width=0% nowrap>";
print "</td></tr></table>";


?>
