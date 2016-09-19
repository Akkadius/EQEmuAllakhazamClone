/**
 * Created by cmiles on 9/18/2016.
 */

function item_search(val){
    u = ".page-content";
    query_string = get_form_query_string(
    $.get("?a=items_search=" + val, function (data) {
        $(u).html(data);
    });
}