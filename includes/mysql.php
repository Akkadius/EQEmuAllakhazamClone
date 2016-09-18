<?php

/** Outputs an error message if a database error occurs
 */
function message_die($t1, $t2, $t3, $t4)
{
    print "<p><table width='100%' border=0>\n";
    print "<tr><td align=left><b>$t1</b></td></tr>\n";
    print "<tr><td align=left>$t2</td></tr>\n";
    print "<tr><td align=left>$t3</td></tr>\n";
    print "<tr><td align=left>$t4</td></tr>\n";
    print "<tr><td align=center><font color=red><b>Have you sourced the eqbrowser tables in your database ?</b></font></td></tr>\n";
    print "</table></p>\n";
}

/** Runs '$query' and returns the value of '$field' of the first (arbitrarily) found row
 *  If no row is selected by '$query', returns an emty string
 */
function get_field_result($field, $query)
{
    $QueryResult = db_mysql_query($query) or message_die('mysql.php', 'GetFiedByQuery', $query, mysql_error());
    if (mysql_num_rows($QueryResult) > 0) {
        $rows = mysql_fetch_array($QueryResult) or message_die('mysql.php', 'GetFiedByQuery', "MYSQL_FETCH_ARRAY", mysql_error());
        $Result = $rows[$field];
    } else
        $Result = "";

    return $Result;
}

/** Runs '$query' and returns the first (arbitrarily) found row.
 */
function GetRowByQuery($query)
{
    $QueryResult = db_mysql_query($query) or mysql_die($query);
    $Result = mysql_fetch_array($QueryResult);

    return $Result;
}

function db_mysql_query($query){
    global $mysql_debugging, $debug_queries;
    $start = microtime(true);

    $result = mysql_query($query);



    if($query != "" && $mysql_debugging){
        $millisecond_time = number_format((microtime(true) - $start), 2);

        $debug_queries .= ' <pre style="margin: 0px; line-height: 24px;">' . $query . ' <b>Execution Time :: ' . $millisecond_time . 's</b> ' . (mysql_error() ? 'ERROR: ' . mysql_error() : '') . '</pre><hr>';
    }

    if(mysql_error()){
        print $debug_queries;
        print mysql_error();
        die;
    }

    return $result;
}

?>
