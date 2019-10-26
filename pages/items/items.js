/**
 * Created by cmiles on 9/18/2016.
 */

function item_search() {
	u = ".page-content-ajax";
	query_string = get_form_query_string("item_search_form");
	console.log(query_string);
	entire_query = "?a=items_search&v_ajax&" + query_string;
	push_query = "?a=items_search&" + query_string;
	$.get(entire_query, function (data) {
		history.pushState('page_pop', push_query, push_query);
		$(u).html(data);
	});
}

function npc_dropped_view(item_id) {
	u = "#npc_dropped_view";
	entire_query = "?a=item&view_dropped=" + item_id + "&v_ajax";
	$.get(entire_query, function (data) {
		$(u).html(data);
	});
}

function npc_sold_view(item_id) {
	u = "#npc_sold_view";
	entire_query = "?a=item&view_sold=" + item_id + "&v_ajax";
	$.get(entire_query, function (data) {
		$(u).html(data);
	});
}

$('#iname').keypress(function (e) {
	var key = e.which;
	if (key === 13)  // the enter key code
	{
		item_search();
		return false;
	}
});
