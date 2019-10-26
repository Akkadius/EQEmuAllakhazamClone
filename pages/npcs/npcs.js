function npc_spawn_view(spawngroup_id) {
	u = "#npc_spawn_view";
	entire_query = "?a=spawngroup&view_spawn=" + spawngroup_id + "&v_ajax";
	console.log(entire_query);
	$.get(entire_query, function (data) {
		console.log(data);
		$(u).html(data);
	});
}

function npc_nearby_view(spawngroup_id) {
	u = "#npc_nearby_view";
	entire_query = "?a=spawngroup&view_nearby=" + spawngroup_id + "&v_ajax";
	$.get(entire_query, function (data) {
		$(u).html(data);
	});
}