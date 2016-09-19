/**
 * Created by cmiles on 9/14/2016.
 */
var Nav = new function() {
    var menuTimer,
        parent = '';

    this.init = function() {
        // The second var passed to over() and out() are to only check for ad intersections with the relevant divs. There is no check to see if ad intersection checking is needed, so this var is required even on the home page.
        $('#nav li.has-sub')
            .mouseenter(function() {over(this,'#nav')})
            .mouseleave(function() {out(this,'#nav')});
        $('ul.path li.has-sub')
            .mouseenter(function() {over(this,'ul.path')})
            .mouseleave(function() {out(this,'ul.path')});
        $('#network')
            .mouseenter(function() {over(this,'#network')})
            .mouseleave(function() {out(this,'#network')});
        $('#theme')
            .mouseenter(function() {over(this,'#theme')})
            .mouseleave(function() {out(this,'#theme')});
        $('#lang')
            .mouseenter(function() {over(this,'#lang')})
            .mouseleave(function() {out(this,'#lang')});
        // $('nav > ul.toptabs > li > a > span').each(function() { this.innerHTML = this.innerHTML.replace(/^(.)/, '<b>$1</b>'); }); // Bolds the first char of each top level nav item - used on Framehead.
        var $headerOptions = $('#header-misc > span');
        if ($headerOptions.length) {
            $headerOptions.last().addClass('last-option');
        } else {
            $('#header-misc').remove();
        }
    }

    this.homeWidth = function() {
        var width = 0;
        $('nav > ul > li').each(function() {
            // width += ZAM.trueOffsetWidth(this);
        });
        if ($.browser.msie && $.browser.version < 9)
            $('#home-nav').css('width',width+3+'px');
        else
            $('#home-nav').css('width',width+'px');
    }

    function intersectAll() {
        /* Something in here USED TO cause IE8 to revert to IE7 compatibility mode ON THE FRONT PAGE ONLY AND INCONSISTENTLY (~1/6 mouseovers) for unknown reasons. Leaving this note in here as a reminder in case something causes it to happen again because it's unclear why it stopped happening.  */
        var check = (parent.search(' ') > -1) ? parent+' li.hov > div' : parent+' ul';
        $(check).each(function () {
            var coords = $(this).offset();
            // var box = ZAM.boxDim(coords.left, coords.top, this.offsetWidth, this.offsetHeight);
        });
    }

    function over(li, thisParent) {
        parent = thisParent;
        clearTimeout(menuTimer);
        var div = li.getElementsByTagName('div')[0];
        if (!div && li.parentNode.id && li.parentNode.id == 'header-misc') div = li.getElementsByTagName('ul')[0];
        if (div) {
            if ((!li.parentNode.id || li.parentNode.id != 'header-misc') && (!li.parentNode.parentNode.id || li.parentNode.parentNode.id != 'nav')) div.style.left = li.offsetWidth + 'px'; // On Framehead this is -1. In the previous ZAM styling it was effectively -25.
            li.className += ' hov';
            test(div, 0);
            intersectAll();
        }
    }

    function out(li, thisParent) {
        parent = thisParent;
        clearTimeout(menuTimer);
        li.className = li.className.replace(/\b ?hov\b/g, '');
        resetTweaks(li); // Reset any changes that were dictated by test().

    }

    function test(div, last) { // Test a menu div for visibility and pass to change it's columns or positioning accordingly.
        if (div.tagName.toLowerCase() == 'ul') return; // Don't try to columnize or shift the #network, #theme, and #lang dropdowns.
        var box = ZAM.boxInfo(div);
        var screen = ZAM.screenInfo();
        var margin = ZAM.pxToInt(document.getElementsByTagName('body')[0].style.marginTop);
        var shift = 10;
        var ua = $.browser;
        if (ua.mozilla && ua.version.slice(0,3) == "1.9") { // Firefox 3
            shift = 16;
        }
        if (ZAM.overflow(box, screen, 0, parseInt(shift / 3))) {
            var boxheight = box.b - box.t; // Box height.
            if (div.parentNode && div.parentNode.parentNode && div.parentNode.parentNode.parentNode && div.parentNode.parentNode.parentNode.id && div.parentNode.parentNode.parentNode.id == 'nav') { // Set target screen height to columnize when appropriate for top tier menus. // TODO: Change this to #nav and test accordingly.
                var screenheight = screen.b - screen.t - shift - margin - 153;
            } else { // Use the normal screen height and allow shifting up over the navigation area.
                var screenheight = screen.b - screen.t - shift - margin;
            }
            if (boxheight < screenheight) { // If it'll fit shifted and isn't the first dropdown opened adjust top of div to fit.
                div.style.top = -1 * ((box.b - box.t) - (screen.b - box.t) + shift) + 'px';
            } else if (!div.className || div.className.search('cols') < 0) { // Columnize to fit if not already columnized
                if (boxheight / 2 < screenheight) { // Need to also check that width wouldn't go off the screen. Just capping at 2 columns for now.
                    columnize(div, 2, last);
                } else {
                    columnize(div, 3, last);
                }
            }
        }
    }

    function columnize(div, columns, last) {
        if (columns != 2 && columns != 3) columns = 3;
        deColumnize(div);
        div.className = 'cols-'+columns;
        var $div = $(div);
        var $ul = $div.children('ul');
        var itemsPerColumn = Math.ceil($div.children('ul').children('li').length / columns);
        $newDiv = $('<div></div>');

        var $newUL = $('<ul></ul>');
        for (var i = 1; i <= columns; i++) {
            if (i == 1) $newUL.addClass('first');
            for (var j = 1; j <= itemsPerColumn; j++) {
                if ($ul.children('li').length) {
                    $newUL.append($ul.children('li:first-child'));
                } else {
                    $newUL.append('<li class="empty"><a>&nbsp;</a></li>');
                }
            }
            $newDiv.append($newUL);
            $newUL = $('<ul></ul>');
        }

        $newDiv.find('li').removeClass('last-child');
        $newDiv.find('li:last-child').addClass('last-child');
        $newDiv.prepend('<em></em><var></var><strong></strong>');
        $newDiv.append('<p class="clear"></p>');

        $div.html($newDiv.html());

        var size = 0;
        if ($.browser.msie && $.browser.version < 9) {
            if (!$('li.hov', $div).length && $('li.has-sub', $div).length) {
                size += columns * 13;
            }
            $div.children('ul').each(function() {
                size += this.offsetWidth;
            });
        } else {
            $div.children('ul').each(function() {
                size += ZAM.trueOffsetWidth(this);
            });
        }
        $div.css('width', size+'px');

        $div.find('li.has-sub')
            .mouseenter(function() {over(this, parent)})
            .mouseleave(function() {out(this, parent)});

    }

    function deColumnize(div) {
        if (div.className && div.className.search('cols') > -1) {
            div.className = '';
            var $div = $(div);
            var $ul = $('<ul></ul>');
            var uls = $div.children('ul').get();
            $.each(uls, function() {
                var $this = $(this);
                $('li.empty', $this).remove();
                $ul.append($this.html());
            });
            $ul.find('li').removeClass('last-child');
            $ul.find('li:last-child').addClass('last-child');
            $div.html('<em></em><var></var><strong></strong><ul>' + $ul.html() + '</ul>');
            $div.css('width', '');
            $div.find('li.has-sub')
                .mouseenter(function() {over(this, parent)})
                .mouseleave(function() {out(this, parent)});
        }
    }

    function resetTweaks(li) { // Reset any changes that were dictated by test().
        var div = li.getElementsByTagName('div')[0];
        if (div) {
            div.style.top = '';
            deColumnize(div);
        }
    }
}

$( "#global_search" ).submit(function( event ) {
    event.preventDefault();

    push_query = "?a=global_search&q=" + $('#qq').val();

    history.pushState('page_pop', push_query, push_query);

    u = ".page-content-ajax";
    $.get(push_query + "&v_ajax", function (data) {
        $(u).html(data);
    });
});

$( ".submit" ).on( "click", function() {
    $(this).closest("form").submit();
});

function highlight_element(element){
    $('html, body').animate({
        scrollTop: ($(element).offset().top - 100)
    }, 200, function () {
        // $(element).pulsate({ color: "#399bc3", repeat: false });
    });
}

function get_form_query_string(ID){
    query_string = "";
    $('#' + ID + '').find('input, select, textarea').each(function (key) {
        val = $(this).val();
        if (val == 'undefined') {
            val = '';
        }
        if($(this).attr('type') == "checkbox"){
            if (!$(this).is(':checked')) {
                val = 0;
            }
        }

        query_string = query_string + "&" + $(this).attr('id') + "=" + encodeURIComponent(val);
    });
    return query_string;
}