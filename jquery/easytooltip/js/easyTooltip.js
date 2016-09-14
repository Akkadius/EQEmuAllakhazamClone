/*
 * 	Easy Tooltip 1.0 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4380/easy-tooltip--jquery-plugin
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
(function($) {

	$.fn.easyTooltip = function(options)
	{
	  
		// default configuration properties
		var defaults = {	
			xOffset: 10,		
			yOffset: 25,
			tooltipId: "easyTooltip",
			clickRemove: false,
			content: "",
			useElement: ""
		}; 
			
		if(typeof options == 'string') defaults.selector = options;
		var options = $.extend(defaults, options);  
		var content;

		this.each(function()
		{	
			var title = $(this).attr("title");
			
			$(this).hover(function(e)
			{											 							   
				content = (options.content != "") ? options.content : title;
				content = (options.useElement != "") ? $("#" + options.useElement).html() : content;
				$(this).attr("title","");
				
				if (content != "" && content != undefined)
				{
					$("body").append("<div id='"+ options.tooltipId +"'>"+ content +"</div>");

					// Calculate positioning of the Tooltip and adjust if too close to window edges
					var maxX, maxY, x, y;
					if (document.all && !window.opera)
					{
						if (document.documentElement && typeof document.documentElement.scrollTop != undefined)
						{
							maxX = document.documentElement.clientWidth + document.documentElement.scrollLeft;
							maxY = document.documentElement.clientHeight + document.documentElement.scrollTop;
							y = event.clientY + document.documentElement.scrollTop;
							x = event.clientX + document.documentElement.scrollLeft;
						}
						else
						{
							y = event.clientY + document.body.scrollTop;
							x = event.clientX + document.body.scrollLeft;
						}
					}
					else
					{
						if(document.body.scrollTop)
						{
							maxX = window.innerWidth + document.body.scrollLeft;
							maxY = window.innerHeight + document.body.scrollTop;
						}
						else
						{
							maxX = window.innerWidth + document.documentElement.scrollLeft;
							maxY = window.innerHeight + document.documentElement.scrollTop;
						}
						y = e.pageY;
						x = e.pageX;
					}
					
					var divW = $("#" + options.tooltipId).width();
					var divH = $("#" + options.tooltipId).height();
					divW = divW ? divW : 400;
					divH = divH ? divH : 100;
					
					if (maxX && maxY)
					{
						if ((x + divW + (options.xOffset * 2)) > maxX && x > 0)
						{
							x = x - (divW + (options.xOffset * 4));
						}
						
						if ((y + divH) > maxY && y > 0)
					{
						y = y - ((y + divH) - maxY);
					}
						if (y < 0)
						{
							y = 0;
						}
						if (x < 0)
						{
							x = 0;
						}
					}

					$("#" + options.tooltipId)
						.css("position","absolute")
						.css("top",(y - options.yOffset) + "px")
						.css("left",(x + options.xOffset) + "px")						
						.css("display","none")
						.fadeIn("fast")
				}
			},
			function(){
				$("#" + options.tooltipId).remove();
				$(this).attr("title",title);
			});	
			$(this).mousemove(function(e){

				// Calculate positioning of the Tooltip and adjust if too close to window edges
				var maxX, maxY, x, y;
				if (document.all && !window.opera)
				{
					if (document.documentElement && typeof document.documentElement.scrollTop != undefined)
					{
						maxX = document.documentElement.clientWidth + document.documentElement.scrollLeft;
						maxY = document.documentElement.clientHeight + document.documentElement.scrollTop;
						y = event.clientY + document.documentElement.scrollTop;
						x = event.clientX + document.documentElement.scrollLeft;
					}
					else
					{
						y = event.clientY + document.body.scrollTop;
						x = event.clientX + document.body.scrollLeft;
					}
				}
				else
				{
					if(document.body.scrollTop)
					{
						maxX = window.innerWidth + document.body.scrollLeft;
						maxY = window.innerHeight + document.body.scrollTop;
					}
					else
					{
						maxX = window.innerWidth + document.documentElement.scrollLeft;
						maxY = window.innerHeight + document.documentElement.scrollTop;
					}
					y = e.pageY;
					x = e.pageX;
				}
				
				var divW = $("#" + options.tooltipId).width();
				var divH = $("#" + options.tooltipId).height();
				divW = divW ? divW : 400;
				divH = divH ? divH : 100;
				
				if (maxX && maxY)
				{
					if ((x + divW + (options.xOffset * 2)) > maxX && x > 0)
					{
						x = x - (divW + (options.xOffset * 4));
					}
					
					if ((y + divH) > maxY && y > 0)
					{
						y = y - ((y + divH) - maxY);
					}
					if (y < 0)
					{
						y = 0;
					}
					if (x < 0)
					{
						x = 0;
					}
				}

				$("#" + options.tooltipId)
					.css("top",(y - options.yOffset) + "px")
					.css("left",(x + options.xOffset) + "px")			
			});	
			if(options.clickRemove){
				$(this).mousedown(function(e){
					$("#" + options.tooltipId).remove();
					$(this).attr("title",title);
				});				
			}
		});
	  
	};

})(jQuery);
