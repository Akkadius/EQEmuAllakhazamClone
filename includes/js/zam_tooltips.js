/**
 * Created by cmiles on 9/18/2016.
 */

$(document).on("mouseenter", "a", function(e){
    url = $(this).attr('href');

    if(!url)
        return true;

    if(url.match(/a=item&|a=recipe&|a=spell&/i)) {
        request_url = url + '&v_ajax&v_tooltip';
        show_tooltip(e, request_url, $(this), 0, 0);
    }
});

$(document).on("mouseleave", "a", function(){
    hide_tooltip();
});

tooltip_cache_array = [];

var tooltip_object = false;

function show_tooltip(e, request_url, input_element, in_set_height, in_set_width) {

    set_height = in_set_height;
    set_width = in_set_width;

    if (!tooltip_object)    /* Tooltip div not created yet ? */
    {
        tooltip_object = document.createElement('DIV');
        tooltip_object.style.position = 'absolute';
        tooltip_object.id = 'tooltip_object';

        document.body.appendChild(tooltip_object);

        var leftDiv = document.createElement('DIV');
        /* Create arrow div */
        leftDiv.className = 'ajax_tooltip_arrow';
        leftDiv.id = 'ajax_tooltip_arrow';
        tooltip_object.appendChild(leftDiv);

        var contentDiv = document.createElement('DIV');
        /* Create tooltip content div */
        contentDiv.className = 'ajax_tooltip_content';
        tooltip_object.appendChild(contentDiv);
        contentDiv.id = 'ajax_tooltip_content';
        contentDiv.style.marginBottom = '15px';

        if (in_set_width > 0) {
            contentDiv.style.width = in_set_width + 'px';
        }
        else {
            contentDiv.style.width = 'auto';
        }
        if (in_set_height > 0) {
            contentDiv.style.height = in_set_height + 'px';
        }
        else {
            contentDiv.style.height = 'auto';
        }
    }
    /* Find position of tooltip */
    tooltip_object.style.display = 'block';
    $('.ajax_tooltip_content').html('');

    if(tooltip_cache_array[request_url]){
        $('.ajax_tooltip_content').html(tooltip_cache_array[request_url]);
        // console.log('hitting a cached tooltip');
        position_tooltip(e, input_element);
        return;
    }


    $.ajax({
        url: request_url,
        context: document.body,
        type: "GET"
    }).done(function (html) {
        $('.ajax_tooltip_content').html(html);

        tooltip_cache_array[request_url] = html;

        position_tooltip(e, input_element);
    });
}

function position_tooltip(e, input_object) {
    if(input_object){
        var offset = input_object.offset();
        var left_position = (offset.left + input_object.outerWidth());
        var top_position = offset.top;
    }else{
        var left_position = e.clientX;
        var top_position = e.clientY;
    }
    var tooltip_height = document.getElementById('ajax_tooltip_content').offsetHeight +  document.getElementById('ajax_tooltip_arrow').offsetHeight;
    var tooltip_width = $('#ajax_tooltip_content').width();

    if(tooltip_width > ($( window ).width() - e.clientX - 100)){

        left_position = left_position - tooltip_width - 100;
    }else{
        // console.log('On screen');
    }

    // console.log('test ' + ($( window ).height() - e.clientY));
    if(tooltip_width > ($( window ).height() - e.clientY - 150)){
        // console.log('Falling off screen height');
        // console.log('Difference ' + (tooltipHeight - ($( window ).height() - e.clientY - 150)));
        top_position = top_position - (tooltip_height - ($( window ).height() - e.clientY - 150));
    }else{
        // console.log('On screen');
    }

    tooltip_object.style.left = left_position + 'px';
    tooltip_object.style.top = top_position + 30 + 'px';
}

function hide_tooltip() {
    if(tooltip_object)
        tooltip_object.style.display='none';
}