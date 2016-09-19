var pjax_request_count = 1;
var pjax_debug = 0;

$('a').live('click', function (e) {
    url = $(this).attr('href');

    /* Make sure we have a valid href link */
    if(!$(this).attr('href')){
        return;
    }

    if($(this).attr('href') == ""){
        return;
    }

    if(url.indexOf('javascript:;') != -1) {
        return;
    }

    if($(this).attr('ignore-pjax')){
        return;
    }

    /* Ignore anchors with targets... */
    if($(this).attr('target')){
        return;
    }

    if(pjax_debug){ console.log('pjax :: Clicking :: ' + url); }

    /* Ignore anchors with # references */
    if(url.indexOf('#') != -1){
        if(pjax_debug){ console.log('pjax :: # trigger'); }
        return;
    }

    /* Check if page bar has been hidden */
    if(!$('.page-bar').is(":visible")){
        $(".toggler-close, .page-bar, .toggler, .page-title").show();
    }

    if (
        $(this).attr('href')
        && url.indexOf('javascript') == -1 // If this is a javascript click - Ignore
        && url.match(/a=|i\?/i) // Make sure the anchor that were are trying to pjax is something within the realm of GM
    )
    {

        /* Cleanup routine */
        if (pjax_request_count > 30) {
            return;
        }

        if (history.pushState) {
            if(pjax_debug){ console.log('pjax :: loading page into push_state'); }

            $.ajax({
                url: $(this).attr('href') + '&v_ajax',
                context: document.body
            }).done(function (e_res) {
                e.preventDefault();
                // $('.page-content-ajax').hide().html(e_res).fadeIn(100);
                $('.page-content-ajax').html(e_res);

                document.body.scrollTop = document.documentElement.scrollTop = 0;

                $('#tooltip_object').remove();

            });

            history.pushState('page_pop', $(this).attr('href'), $(this).attr('href'));

            pjax_request_count++;
            if(pjax_debug){ console.log('pjax :: request count ' + pjax_request_count); }
        }
        return false;
    }
});

window.addEventListener("popstate", function(e) {
    state_destination = document.location.href;

    if(pjax_debug){ console.log('pjax :: triggering go back '); }

    if(pjax_debug){  console.log('pjax :: ' + state_destination); }

    if (state_destination) {
        if (state_destination.indexOf('#') != -1) {
            return;
        }
    }

    $.ajax({
        url: state_destination + "&v_ajax",
        context: document.body
    }).done(function(e_res) {
        e.preventDefault();
        $(".page-content-ajax").html(e_res);
    });

});