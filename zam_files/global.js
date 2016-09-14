var ZAM = new function() {
  var cdnHost = 'zam.zamimg.com',
      ajaxRequests = {};

  this.async = function(url) {
    $('head').append($('<script/>', {type: 'text/javascript', src: url}));
  };

  this.ajax = function(url, options) {
    /* OPTIONS:
      callback: function; executed when the ajax succeeds; defaults to nothing.
      errorCallback: fuction; executed when the ajax fails entirely; defaults to nothing.
      tries: int; how many attempts to call the ajax; defaults to 3.
      indicator: CSS selector, DOM object, or jQuery object; if supplied will display a loading indicator within the target element.
      indicatorBgColor: CSS color; if supplied will set the background color of the loading indicator; defaults to unchanged.
      indicatorPosition: 'top', 'right', 'bottom', or 'left'; if supplied the loading indicator will be placed just outside the indicator target's box on that side.
      indicatorSize: 'small' or 'large'; size of the loading indicator; defaults to 'small'.
      leaveIndicator: boolean; whether to leave the indicator in (if it's going to be overidden by the callback, or has some otherwise unique handling).
     */

    ajaxRequests[url] = options;
    ajaxRequests[url]['tried'] = 1;
    var r = ajaxRequests[url];

    if (r.indicator) {
      var classes = r.indicatorSize ? ' ' + r.indicatorSize : ' small';
      var styles = '';
      if (r.indicatorPosition) {
        var pos = r.indicatorPosition;
        if (pos == 'top' || pos == 'right' || pos =='bottom' || pos == 'left') {
          classes += ' positioned';
          styles = pos + ':-';
          if (r.indicatorSize && r.indicatorSize != 'small') {
            styles += '37';
          } else {
            styles += '21';
          }
          styles += 'px;';
        }
      }
      if (r.indicatorBgColor) {
        styles += 'background-color:' + r.indicatorBgColor;
      }

      if (styles) {
        styles = ' style="' + styles + '"';
      }
      $(r.indicator).prepend('<div class="loading' + classes + '"' + styles + '></div>');
    }

    $.ajax(url, {
      success: function() { ajaxSuccess(url) },
      error: function() {ajaxRetry(url) }
    });
  }

  function ajaxRetry(url) {
    var r = ajaxRequests[url];
    if (r) {
      var tries = r.tries ? r.tries : 3;
      if (r.tried >= tries) {
        if (r.indicator && !r.leaveIndicator) {
          $('div.loading', $(r.indicator)).remove();
        }
        if (r.errorCallback) {
          r.errorCallback();
        }
      } else {
        r.tried++;
        $.ajax(url, {
          success: function() { ajaxSuccess(url) },
          error: function() {ajaxRetry(url) }
        });
      }
    }
  }

  function ajaxSuccess(url) {
    var r = ajaxRequests[url];
    if (r) {
      if (r.indicator && !r.leaveIndicator) {
        $('div.loading', $(r.indicator)).remove();
      }
      if (r.callback) {
        r.callback();
      }
    }
  }

  this.boxDim = function(x, y, width, height) { // Return an object with box offsets on all sides from top and left.
    return {
      l: x,
      t: y,
      r: x + width,
      b: y + height
    }
  }

  this.boxInfo = function(element) {
    var position = $(element).offset();
    return ZAM.boxDim(position.left, position.top, element.offsetWidth, element.offsetHeight);
  }

  this.screenInfo = function() {
    var $window = $(window);
    var left = $window.scrollLeft();
    var top = $window.scrollTop();
    return {
      l: left,
      t: top,
      r: left + $window.width(),
      b: top + $window.height()
    }
  }

  this.intersect = function(a, b) { // Test that a doesn't intersect with b - boxDim style objects only.
    return !(a.l >= b.r || b.l >= a.r || a.t >= b.b || b.t >= a.b)
  }

  this.overflow = function(a, b, margin, padding) { // Test that a doesn't overflow outside of b - boxDim style objects only; padding is how much padding shouldn't be overflowed either.
    if (typeof padding != 'undefined') {
      b.t = b.t - padding;
      b.r = b.r - padding;
      b.b = b.b - padding;
      b.l = b.l - padding;
    }
    var a2 = {
      t: a.t + margin,
      r: a.r,
      b: a.b + margin,
      l: a.l
    }
    return !(a2.l >= b.l && b.r >= a2.r && a2.t >= b.t && b.b >= a2.b)
  }

  this.isOver = function(a, b) { // Test if point a is inside box b - a is an object with x and y; b is boxDim objects only.
    return (a.x > b.l && a.x < b.r && a.y > b.t && a.y < b.b)
  }

  this.ua = navigator.userAgent.toLowerCase();

  this.pxToInt = function(num) {
    num = parseInt(num);
    return (typeof num != 'number' || !(num > 0)) ? 0 : num;
  }

  function trueOffset(element, height) { // This gets the true offset sizes in new browsers such as FF4+ and IE9+ which now can have fractions of pixels for widths, but don't report it correctly with the built in .offsetWidth and .offsetHeight.
    var cStyle = element.ownerDocument && element.ownerDocument.defaultView && element.ownerDocument.defaultView.getComputedStyle
      && element.ownerDocument.defaultView.getComputedStyle(element, null),
      ret = cStyle && cStyle.getPropertyValue(height ? 'height' : 'width') || '';
    if (ret && ret.indexOf('.') > -1) {
      ret = parseFloat(ret)
        + parseInt(cStyle.getPropertyValue(height ? 'padding-top' : 'padding-left'))
        + parseInt(cStyle.getPropertyValue(height ? 'padding-bottom' : 'padding-right'))
        + parseInt(cStyle.getPropertyValue(height ? 'border-top-width' : 'border-left-width'))
        + parseInt(cStyle.getPropertyValue(height ? 'border-bottom-width' : 'border-right-width'));
    } else {
      ret = height ? element.offsetHeight : element.offsetWidth;
    }
    return ret;
  }

  this.trueOffsetWidth = function(element) {
    return trueOffset(element);
  }

  this.trueOffsetHeight = function(element) {
    return trueOffset(element, true);
  }

  this.commas = function(num) {
    num = num + '';
    if (num.length > 3) {
      var mod = num.length % 3;
      var ret = mod > 0 ? num.substring(0,mod) : '';
      for (i=0 ; i < Math.floor(num.length / 3); i++) {
        if (mod == 0 && i == 0)
          ret += num.substring(mod+ 3 * i, mod + 3 * i + 3);
        else
          ret+= ',' + num.substring(mod + 3 * i, mod + 3 * i + 3);
      }
      return ret;
    } else {
      return num;
    }
  }

  this.scrollPosition = function() {
    return [
      self.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft,
      self.pageYOffset || document.documentElement.scrollTop  || document.body.scrollTop
    ];
  }

  this.nonScrollingHash = function(hash) {
    hash = hash.replace(/^#/, '');
    if (hash.match(/[^A-Za-z0-9-_]/)) { // If it's not a valid ID anyway, just do it.
      document.location.hash = hash;
      return;
    }
    var node = $('#' + hash);
    if (node.length) {
      node.attr('id', '');
    }
    document.location.hash = hash;
    if (node.length) {
      node.attr('id', hash);
    }
  }

  this.scrolling = function(enabled, element) {
    if (typeof element != 'undefined') {
      if(!enabled) {
        $(element).bind('mousewheel', stopScrolling);
      } else {
        $(element).unbind('mousewheel', stopScrolling);
      }
    } else {
      if(!enabled) {
        $(document).bind('mousewheel', stopScrolling);
        $(window).bind('DOMMouseScroll', stopScrolling);
      } else {
        $(document).unbind('mousewheel', stopScrolling);
        $(window).unbind('DOMMouseScroll', stopScrolling);
      }
    }
  }

  function stopScrolling(e) {
    if (e.stopPropagation) e.stopPropagation();
    if (e.preventDefault) e.preventDefault();
    e.returnValue = false;
    e.cancelBubble = true;

    return false;
  }

  this.staticUrl = function(includeProtocol) {
    var protocol = includeProtocol ? 'http:' : '';
    if ((window.location+'').match(/^https?:\/\/[^\.]+\.(allakha)?zam\.com\//)) {
      return protocol + '//' + cdnHost;
    } else {
      return protocol + (window.location+'').match(/^https?:(\/\/[^\/]+)\//)[1].replace(/\/\/[^\.]+\./, '//common.').replace(/\/\/([^\/]+)\.allakhazam\.com/, '//$1.zam.com');
    }
  }

  this.warnIE = function() {
    $('#header').prepend('<div id="warn-ie"><b>Warning:</b> We have dropped support for your browser (IE' + ($.browser.version).replace(/\..*/, '') + '). Please upgrade to a modern browser such as <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> or <a href="http://www.getfirefox.com/" target="_blank">Firefox</a>. If you cannot change browsers try <a href="http://www.google.com/chromeframe" target="_blank">Chrome Frame</a>.</div>');
  }

  this.ieLog = function(text) { // A quick stand-in for console.log() in IE.
    var $log = $('#ieLog');
    if (!$log.length) {
      $(document.body).prepend('<div id="ieLog" style="bottom:40px;overflow-y:scroll;position:fixed;right:10px;top:40px;z-index:999999999"></div>');
      $log = $('#ieLog');
    }
    if ($log.length) {
      $log.append(text + '<br>');
      $log.get(0).scrollTop = 9999999;
    }
  }

  this.closeAlert = function(duration) {
    $(this).parents('div.alert').each(function() {
      $(this).slideUp('fast');
      if (duration && typeof duration == 'number') {
        $.cookie('close-' + this.id, 1, { path: '/', expires: duration });
      } else {
        $.cookie('close-' + this.id, 1, { path: '/' });
      }
    });
  }

  this.whTooltips = function(force) {
    var loc = ('' + window.location).replace(/^https?:\/\//, '');
    if (force || loc.match(/^wow\./) || loc.match(/^www\./)) {
      $(document).ready(function() {
        $(body).append('<script src="http://static.wowhead.com/widgets/power.js"></script>');
      });
    }
  }

  this.getSite = function() {
    return (window.location + '').replace(/https?:\/\/([^\.]+)\..*/, '$1');
  }
};

function isHomepage() {
  return (document.URL).match(/^https?:\/\/[^\/]+\/$/) ? true : false;
}

function checkSkin() {
  var skinWrap = document.getElementById('skin-wrap');
  if (!skinWrap) {
    setTimeout(checkSkin, 50);
    return;
  }
  window["i" + MonkeyBroker.campaignVals.r]({sz:30,rm:true,cb:postSkinSetup,el:skinWrap});
}

function postSkinSetup(skinId) {
    Ads.site.postSkinSetup({ id: skinId });
}

$(document).ready(function() {
  $('div.alert .close').not('[onclick]').click(ZAM.closeAlert); // Set up default alert box closers if they don't have special rules already set.
});

var OldAds = new function() {
  var spots = [],
      hidden = [],
      adSelectors = ['.header-bg', '.sidebar-bg', '.block-bg'];

  this.init = function() {
    var $ad;
    for (var i in adSelectors) {
      $ad = $(adSelectors[i]);
      if ($ad.length) spots.push($ad.get(0));
    }
  }

  this.showAll = function() {
    $('body > iframe').css('visibility','');
    $('embed').css('visibility','');
    $('object').css('visibility','');
    for (var i = 0, spotsNum = spots.length; i < spotsNum; ++i) {
      spots[i].style.visibility = '';
      hidden = [];
      if (ZAM.ua.search('apple') > -1 && ZAM.ua.search('mac') > -1) spots[i].getElementsByTagName('iframe')[0].style.display = '';
    }
  }

  function hide(element) {
    if (element) element.style.visibility = 'hidden';
    hidden.push(element);
  }

  function isHidden(element) {
    if (element.style && element.style.visibility)
      return element.style.visibility == 'hidden';
    return false;
  }

  this.intersect = function(box, hideIntersections) { // box = box sides array that is being tested for intersection with ads.
    if (spots.length) {
      var ad;
      for (var i in spots) {
        ad = spots[i];
        if (ad && typeof ad == 'object') { // the typeof check is needed to keep IE8 from derping
          if (!isHidden(ad)) {
            var rect = ZAM.boxInfo(ad);
            if (ZAM.intersect(box, rect)) {
              if (hideIntersections) {
                hide(ad);
              }
            }
          }
        }
      }
    }
    return false;
  }
}
$(document).ready(OldAds.init);


/**
 * addEvent written by Dean Edwards, 2005
 * with input from Tino Zijdel
 *
 * http://dean.edwards.name/weblog/2005/10/add-event/
 **/
function addEvent(element, type, handler) {
  // assign each event handler a unique ID
  if (!handler.$$guid) handler.$$guid = addEvent.guid++;
  // create a hash table of event types for the element
  if (!element.events) element.events = {};
  // create a hash table of event handlers for each element/event pair
  var handlers = element.events[type];
  if (!handlers) {
    handlers = element.events[type] = {};
    // store the existing event handler (if there is one)
    if (element["on" + type]) {
      handlers[0] = element["on" + type];
    }
  }
  // store the event handler in the hash table
  handlers[handler.$$guid] = handler;
  // assign a global event handler to do all the work
  element["on" + type] = handleEvent;
}

// a counter used to create unique IDs
addEvent.guid = 1;

function removeEvent(element, type, handler) {
  // delete the event handler from the hash table
  if (element.events && element.events[type]) {
    delete element.events[type][handler.$$guid];
  }
}

function handleEvent(event) {
  var returnValue = true;
  // grab the event object (IE uses a global event object)
  event = event || fixEvent(window.event);
  // get a reference to the hash table of event handlers
  var handlers = this.events[event.type];
  // execute each event handler
  for (var i in handlers) {
    this.$$handleEvent = handlers[i];
    if (this.$$handleEvent(event) === false) {
      returnValue = false;
    }
  }
  return returnValue;
}

function fixEvent(event) {
  // add W3C standard event methods
  event.preventDefault = fixEvent.preventDefault;
  event.stopPropagation = fixEvent.stopPropagation;
  return event;
}

fixEvent.preventDefault = function() {
  this.returnValue = false;
}

fixEvent.stopPropagation = function() {
  this.cancelBubble = true;
}

// end from Dean Edwards

/**
 * Creates an Element for insertion into the DOM tree.
 * From http://simon.incutio.com/archive/2003/06/15/javascriptWithXML
 *
 * @param element the element type to be created.
 *        e.g. ul (no angle brackets)
 **/
function createElement(element) {
  if (typeof document.createElementNS != 'undefined') {
    return document.createElementNS('http://www.w3.org/1999/xhtml', element);
  }
  if (typeof document.createElement != 'undefined') {
    return document.createElement(element);
  }
  return false;
}

/**
 * "targ" is the element which caused this function to be called
 * from http://www.quirksmode.org/js/events_properties.html
 **/
function getEventTarget(e) {
  var targ;
  if (!e) {
    e = window.event;
  }
  if (e.target) {
    targ = e.target;
  } else if (e.srcElement) {
    targ = e.srcElement;
  }
  if (targ.nodeType == 3) { // defeat Safari bug
    targ = targ.parentNode;
  }

  return targ;
}

// cookie control from quirksmode.org
function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return "";
}

function updatePrefs(pref,val) {
  var oldp = readCookie('MsgPrefs2');
  var p = decodeURIComponent(oldp).split(';');
  var setpref = 0;
  for (i=0;i<p.length;i++) {
    var pv = p[i].split('=',2);
    if (pv[0] == pref) {
      pv[1] = val;
      p[i] = pv.join('=');
      setpref = 1;
    }
  }
  var newp = encodeURIComponent(p.join(';'));
  if (setpref == 0) {
    if (newp.length == 0) {
      newp = encodeURIComponent(pref + '=' + val);
    } else {
      newp = newp + encodeURIComponent(';' + pref + '=' + val);
    }
  }
  var da = document.domain.split('.');
  var dd = '.' + da[da.length-2] + '.' + da[da.length-1];

  var date = new Date();
  date.setTime(date.getTime()+(3650*24*60*60*1000));
  var expires = "; expires="+date.toGMTString();
  document.cookie = "MsgPrefs2="+newp+expires+"; path=/; domain="+dd;
}

function getParam( name ) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function rm(e){e.parentNode.removeChild(e)}
function ge(e){return document.getElementById(e)}

function getElementsByClassName (searchClass,tag) {
  var classElements = new Array();
  if ( tag == null )
    tag = '*';
  var els = document.getElementsByTagName(tag);
  var elsLen = els.length;
  var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
  for (i = 0, j = 0; i < elsLen; i++) {
    if ( pattern.test(els[i].className) ) {
      classElements[j] = els[i];
      j++;
    }
  }
  return classElements;
}

if (!document.getElementsByClassName) {
  document.getElementsByClassName = getElementsByClassName;
}

// Empties a select list
function ClearSelect (s) {
  for (var i = 0; i < s.options.length; i++) {
    s.options[i] = null;
  }
  if (s.options.length != 0) {
    ClearSelect (s);
  }
}

// Adds a value to a select list
function Append2Select(s, val,text) {
  s.options[s.options.length] = new Option (text, val);
}

// open external links (for forums really) in a new tab/window
function externalLinks() {
  if (!document.getElementsByTagName) return;
  var anchors = document.getElementsByTagName("a");
  for (var i=0; i<anchors.length; i++) {
    var anchor = anchors[i];
    if (anchor.getAttribute("rel") && anchor.getAttribute("rel").match("\\bexternal\\b")) {
      anchor.target = "_blank";
    }
  }
}

function togglevis (target) {
  if (!target.id) {
    target = document.getElementById(target);
  }
  if (!target.id)
    return;

  if (target.style.display == 'none' || target.style.display == '') {
    target.style.display = 'block';
  } else  {
    target.style.display = 'none';
  }
}

function clearTmp() {
  var obj = document.getElementById('tmpItemFrm');
  var trig = document.getElementById('temporaryIDforshowTmp');
  if (trig) trig.id = '';
  obj.style.display = 'none';
  obj.innerHTML = '';
}

function showTmp(e) {
  var maxX;
  var maxY;
  var obj = document.getElementById('tmpItemFrm');
  obj.style.position = "absolute";
  obj.style.display = "block";
  var evt = e || window.event;
  if (!evt) return;
  if (e) {
    e.target.title = '';
    e.target.id = 'temporaryIDforshowTmp';
  }

  if (document.all&&!window.opera) {
    if (document.documentElement) {
      maxX = document.documentElement.clientWidth + document.documentElement.scrollLeft;
      maxY = document.documentElement.clientHeight + document.documentElement.scrollTop;
      y = evt.clientY + document.documentElement.scrollTop;
      x = evt.clientX + document.documentElement.scrollLeft ;
    } else {
      y = evt.clientY + document.body.scrollTop;
      x = evt.clientX + document.body.scrollLeft;
    }
  } else {
    if (document.body.scrollTop) {
      maxX = window.innerWidth + document.body.scrollLeft;
      maxY = window.innerHeight + document.body.scrollTop;
    } else {
      maxX = window.innerWidth + document.documentElement.scrollLeft;
      maxY = window.innerHeight + document.documentElement.scrollTop;
    }
    y = evt.pageY;
    x = evt.pageX;
  }

  var divW = parseInt(obj.offsetWidth);
  var divH = parseInt(obj.offsetHeight);
  divW = divW ? divW : 400;
  divH = divH ? divH : 100;
  if (maxX && maxY) {
    while (x + divW > (maxX - 10) && x > 0) {
      x = x - (divW + 10);
      obj.style.left = x + 5 +"px";
    }
    while (y + divH > (maxY - 10) && y > 0) {
      y = y - 1;
      obj.style.top = y +"px";
    }
  }

  if (document.body.style.marginTop) y = y - parseInt(document.body.style.marginTop.replace('px',''));

  obj.style.left = x + 16 +"px";
  obj.style.top = y + 16 + "px";
//    document.title = "X:" + x + " MaxX:" + maxX + " divX:" + divW + " y:" + y + " MaxY:" + maxY + " DivY:" + divH;
}

function reloadMsg (id,rs) {
  Ajakz.target = "msg" + id;
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {document.getElementById(Ajakz.target).className = '';}
  Ajakz.Run('/fcluster/mrate3.pl?mid=' + id + ';style=' + rs);
  return false;
}

function reloadMsg2 (id,rs,cnt) {
  Ajakz.target = ge("msg" + id).parentNode.parentNode;
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {Ajakz.target.className = '';}
  Ajakz.Run('/fcluster/mrate3.pl?mid=' + id + ';style=' + rs + ';count=' + cnt);
  return false;
}

function rateMsg (id,w,rs) {
  Ajakz.target = "msg" + id;
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {document.getElementById(Ajakz.target).className = '';}
  var editReason = '';
  if (w == 'l') {
    editReason = prompt('Reason for Lock?');
  }
  if (editReason != null) Ajakz.Run('/fcluster/mrate3.pl?mid=' + id + ';w=' + w + ';style=' + rs + ';edit_reason=' + editReason);
  return false;
}

function rateMsg2(id,w,editReason,cnt) {
  if ($('#msg'+id).parent().parent().siblings('.post-necro').size()) {
    Ajakz.target = ge("msg" + id).parentNode.parentNode.parentNode;
  } else {
    Ajakz.target = ge("msg" + id).parentNode.parentNode;
  }
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {Ajakz.target.className = '';}
  if (editReason == undefined) var editReason = '';
  Ajakz.Run('/fcluster/mrate3.pl?mid=' + id + ';w=' + w + ';edit_reason=' + editReason + ';count=' + cnt);
  return false;
}

function rateNews (id,w) {
  Ajakz.target = "news" + id;
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {document.getElementById(Ajakz.target).className = '';}
  Ajakz.Run('/cgi-bin/news_rate.pl?story_id=' + id + ';w=' + w);
  return false;
}

function loadfile(target,file) {
  Ajakz.target = target;
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.Run(file);
}

function doPic(id,w,h) {
  Ajakz.target = 'ssMain';
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.w = w;
  Ajakz.h = h;
  Ajakz.loadTargetHook = function (t) {var ow = t.offsetWidth;var oh = t.offsetHeight;if (ow < Ajakz.w) { t.style.left = Ajakz.w - ow + 'px';ow = Ajakz.w;}if (oh < Ajakz.h) { oh = Ajakz.h + 24;} t.style.width = ow + 'px';}
  Ajakz.Run('/Im/imagelinks?id=' + id);
}

function showImg () {
  var akzid = this.getAttribute('akzid');
  if (akzid && !this.id.toString()) {
    this.id = akzid;
  }
  var id = this.id.toString();
  var iid = id.match(/(\d+)/);
  if (iid[1]) {
    clearTmp();
    Ajakz.target = 'tmpItemFrm';
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.Run('/Im/imageonly?id=' + iid[1]);
  }
}

Ajakz = {
 file: undefined,
 onload: undefined,
 Ajakz: undefined,
 target: undefined,
 loadTargetHook: undefined,
 req: undefined,

 Run: function (url) {
    url = url ? url : Ajakz.file;
    if (window.XMLHttpRequest) { // Non-IE browsers
      Ajakz.req = new XMLHttpRequest();
      Ajakz.req.onreadystatechange = Ajakz.onload;
      try {
        Ajakz.req.open("GET", url, true);
      } catch (e) {
        // console.log(e);
      }
      Ajakz.req.send(null);
    } else if (window.ActiveXObject) { // IE
      Ajakz.req = new ActiveXObject("Microsoft.XMLHTTP");
      if (Ajakz.req) {
        Ajakz.req.onreadystatechange = Ajakz.onload;
        Ajakz.req.open("GET", url, true);
        Ajakz.req.send();
      }
    }
  },
 loadTarget: function () {
    if (Ajakz.req.readyState == 4) {
      if (Ajakz.req.status == 200) {
        var target;
        if (typeof Ajakz.target == 'string') {
          target = document.getElementById(Ajakz.target);
        } else {
          target = Ajakz.target;
        }
        if (target.tagName.toLowerCase() == 'input') {
          target.value = Ajakz.req.responseText;
        } else {
          target.innerHTML = Ajakz.req.responseText;
        }
        if (Ajakz.loadTargetHook) {
          Ajakz.loadTargetHook(target);
        }
      }
    }
  }
}

function fadeIn (id) {
  var o = document.getElementById(id);
  if (o.fadeIn) window.clearInterval(o.fadeIn);
  var step = 0;
  o.fadeIn = window.setInterval(
    function() {
      o.style.opacity = step * .05;
      step++;
      if (step >= 100) window.clearInterval(o.fadeIn);
    }, 3);
}

addEvent(window,'load',externalLinks);

function restoreads() {
  $('body > iframe').css('visibility','visible');
  $('embed').css('visibility','visible');
  $('object').css('visibility','visible');
  var b = $('.header-bg');
  var t = $('.sidebar-bg');
  var c = $('.block-bg');
  var ed = ge('eyeDiv');
  if (b) b.style.visibility = 'visible';
  if (t) t.style.visibility = 'visible';
  if (c) c.style.visibility = 'visible';
  if (ed) ed.style.visibility = 'visible';
  var ua = navigator.userAgent.toLowerCase();
  if (ua.search('apple') > -1 && ua.search('mac') > -1) {
    b.getElementsByTagName('iframe')[0].style.display = 'block';
    t.getElementsByTagName('iframe')[0].style.display = 'block';
    c.getElementsByTagName('iframe')[0].style.display = 'block';
  }
}

function hideads() {
  $('body > iframe').css('visibility','hidden');
  $('embed').css('visibility','hidden');
  $('object').css('visibility','hidden');
  var b = $('.header-bg');
  var t = $('.sidebar-bg');
  var c = $('.block-bg');
  var ed = ge('eyeDiv');
  if (b) b.style.visibility = 'hidden';
  if (t) t.style.visibility = 'hidden';
  if (c) c.style.visibility = 'hidden';
  if (ed) ed.style.visibility = 'hidden';
  var ua = navigator.userAgent.toLowerCase();
  if (ua.search('apple') > -1 && ua.search('mac') > -1) {
    b.getElementsByTagName('iframe')[0].style.display = 'none';
    t.getElementsByTagName('iframe')[0].style.display = 'none';
    c.getElementsByTagName('iframe')[0].style.display = 'none';
  }
}

function storeSel (dest) {
  if (dest.createTextRange)
    dest.caretPos = document.selection.createRange().duplicate();
}

function doTag(dest, stag, etag, dtext) {
  if (document.selection) {

    var orig;
    if (window.opera) {
      orig = dest.value;
    } else {
      orig = dest.value.replace(/\r\n/g, "\n");
    }

    var r = document.selection.createRange();
    var t = r.text;

    if (t) {
      newval = stag + t + etag;
    } else {
      newval = stag + dtext + etag;
    }

    if (dest.createTextRange && dest.caretPos) {
      var caretPos = dest.caretPos;
      caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? newval + ' ' : newval;
    } else {
      dest.value = dest.value + newval;
    }

    if (r.parentElement() != dest) {
      dest.focus();
      return;
    }

    var changed;
    if (window.opera) {
      changed = dest.value;
    } else {
      changed = dest.value.replace(/\r\n/g, "\n");
    }
    for (var diff = 0; diff < orig.length; diff++) {
      if (orig.charAt(diff) != changed.charAt(diff)) break;
    }

    dest.focus();
    var r2 = dest.createTextRange();
    r2.moveStart('character', -dest.value.length);
    r2.moveEnd('character', -dest.value.length);
    r2.moveStart('character', diff + stag.length);
    r2.moveEnd('character', newval.length - stag.length - etag.length);
    r2.select();
  } else {
    var startpos = dest.selectionStart;
    var endpos = dest.selectionEnd;
    var scrollTop = dest.scrollTop;

    var t = (dest.value).substring(dest.selectionStart,dest.selectionEnd);
    var selnv = false;
    if (t) {
      if (t.charAt(t.length - 1) == ' ') {
        t = t.substring(0, t.length - 1);
        endpos--;
      }

      // if (typeof dtext != 'undefined' && dtext && dtext != t) t = dtext; // This could possible fix the IE selection issue, but dtext needs to be fed a proper value at all times, or not at all.
      newval = stag + t + etag;
    } else {
      var sel = window.getSelection();
      if (!sel.isCollapsed) {
        var seltxt = sel.toString();
        newval = stag + seltxt + etag;
      } else {
        newval = stag + dtext + etag;
        selnv = true;
      }
    }

    dest.value = dest.value.substring(0,startpos) + newval + dest.value.substring(endpos, dest.value.length);
    dest.focus();
    if (selnv) {
      dest.selectionStart = startpos + stag.length;
      dest.selectionEnd = dest.selectionStart + dtext.length;
    } else {
      dest.selectionStart = startpos + newval.length;
      dest.selectionEnd = dest.selectionStart;
    }
    dest.scrollTop = scrollTop;
  }
}

function storeCaret(textEl) {
  if (textEl.createTextRange)
    textEl.caretPos = document.selection.createRange().duplicate();
}

function insertAtCaret(textEl, text) {
  if (textEl.createTextRange && textEl.caretPos) {
    var caretPos = textEl.caretPos;
    caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
  } else {
    textEl.value = textEl.value + text;
  }
}

function doMod(tag,dest) {
  var t;
  var moz;
  if (document.selection) {
    t = document.selection.createRange().text;
  } else {
    var bt = (dest.value).substring(0,dest.selectionStart);
    var st = (dest.value).substring(dest.selectionStart,dest.selectionEnd);
    var et = (dest.value).substring(dest.selectionEnd,dest.textLength);
    t = st;
    moz = 1;
  }
  var newval = new String;
  if (tag == "B") {
    newval = "[b]" + t + "[/b]";
  }
  if (tag == "I") newval = "[i]" + t + "[/i]";
  if (tag == "U") newval = "[u]" + t + "[/u]";
  if (tag == "Sm") newval = "[sm]" + t + "[/sm]";
  if (tag == "Li") newval = "[li]" + t + "[/li]";
  if (tag == "Lg") newval = "[lg]" + t + "[/lg]";
  if (tag == "Qu") newval = "[quote]" + t + "[/quote]";
  if (tag == "Pre") newval = "[pre]" + t + "[/pre]";
  if (tag == "URL") {
    var href = prompt("Enter the URL to go to","http://");
    newval = "[link=" + href + "]" + t + "[/link]";
  }
  if (tag == "Color") {
    var color = document.getElementById('color')[document.getElementById('color').selectedIndex].value;
    newval = "[" + color + "]" + t + "[/" + color + "]";
  }
  if (newval == "") return;
  if (moz) {
    dest.value = bt + newval + et;
  } else {
    insertAtCaret(dest,newval);
  }
}

function loadHelp(helpURL) {
  window.open(helpURL,'help','width=400,height=400,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes');
}

function CheckIt() {
  if (document.pm.subject.value == "") {
    alert('You Need to Enter a Subject');
    return false;
  }
  document.pm.submit();
}

function dosmilie(t) {
  var sm = t.match(/smilies\/([^\.]+)\./);
  var O;
  var moz;
  var dest = document.pm.body;
  if (document.selection) {
    O = document.selection.createRange().text;
  } else {
    var bt = (dest.value).substring(0,dest.selectionStart);
    var st = (dest.value).substring(dest.selectionStart,dest.selectionEnd);
    var et = (dest.value).substring(dest.selectionEnd,dest.textLength);
    O = st;
    moz = 1;
  }
  var newval = new String;
  newval = '[:' + sm[1] + ':]';
  if (newval == "") return;
  if (moz) {
    dest.value = bt + newval + et;
  } else {
    insertAtCaret(document.pm.body,newval);
  }
}

function doPoll(t) {
  var O;
  var moz;
  var dest = document.pm.body;
  if (document.selection) {
    O = document.selection.createRange().text;
  } else {
    var bt = (dest.value).substring(0,dest.selectionStart);
    var st = (dest.value).substring(dest.selectionStart,dest.selectionEnd);
    var et = (dest.value).substring(dest.selectionEnd,dest.textLength);
    O = st;
    moz = 1;
  }
  var newval = new String;
  newval = '[poll]\n[question]Question text[/question]\n[choice]choice 1 [/choice]\n[choice]choice 2 [/choice]\n[choice]choice 3 [/choice]\n[choice]choice 4 [/choice]\n[/poll]';
  if (newval == "") return;
  if (moz) {
     dest.value = bt + newval + et;
  } else {
      insertAtCaret(document.pm.body,newval);
  }
  return false;
}

function tablistview (l,nohash) {
  var cns = l.parentNode.parentNode.parentNode.childNodes;
  var t = l.id.replace(/_tab$/, "");
  if (!nohash) document.location.hash = t;
  for (var i = 0; i < cns.length; i++) {
    if (cns[i].tagName == "DIV" && cns[i].className != "clear") {
      cns[i].style.display = "none";
    }
  }

  var lis = l.parentNode.parentNode.childNodes;
  for (var x = 0; x < lis.length; x++) {
    if (lis[x].tagName == "LI") {
      lis[x].className = "";
    }
  }

  l.parentNode.className = "current";
  ge(t + "_t").style.display = "";
}

function tagchange(te, tag, change, cat, id, threshold, count, url, weight) {
  Ajakz.target = te;
  Ajakz.onload = Ajakz.loadTarget;
  tag = tag.replace('&','_AND_');
  var tagger = '/cgi-bin/tag.pl?tag=' + tag + '&change=' + change + '&cat=' + cat + '&id=' + id;
  tagger += '&threshold=' + threshold + '&count=' + count;
  if (url != '') {
    tagger += '&url=' + url;
  }
  if (weight != undefined) {
    tagger += '&weight=' + weight;
  }
  Ajakz.Run(tagger);

  return false;
}

var inputid = 0;
function tagaddclick(ae, cat, id, threshold, count, url, weight) {
  ae.innerHTML = '<form style="display: inline;" onSubmit="return tagchange(this.parentNode.parentNode,this.tag.value,\'add\',\'' + cat + '\',\'' +
    id + '\',' + threshold + ',' + count + ',\'' + url + '\',' + weight +
    ');"><input type="text" name="tag" size="14" maxlength="80" id="tmpInput' + inputid +'" style="padding:0;vertical-align:middle"></form>';
  document.getElementById('tmpInput'+ inputid).focus();
  inputid++;
  return false;
}

function showalltags(te, cat, id, url) {
  Ajakz.target = te;
  Ajakz.onload = Ajakz.loadTarget;
  var tagger = '/cgi-bin/tag.pl?count=999&threshold=-10&cat=' + cat + '&id=' + id;
  if (url != '') {
    tagger += '&url=' + url;
  }
  Ajakz.Run(tagger);

  return false;
}

function copylink(t, text) {
  if (window.clipboardData) {
    window.clipboardData.setData('Text', text);
  } else {
    document.clipboard.saveEdit(text);
  }
  t.innerHTML = "Link Copied";
}

function togglecont() {
  var cont = document.getElementById("wikicont");
  cont.style.display = cont.style.display == "block" ? "none" : "block";
  var ct = document.getElementById("wikict");
  ct.innerHTML = ct.innerHTML == "hide" ? "show" : "hide";
}

function zamDialogue(c,t,f) {
  if (!ge('jsbox')) {
    var b = document.createElement('div');
    b.id = 'jsbox';
    document.body.insertBefore(b, document.body.childNodes[0]);
  }
  t = t ? '<h1>'+t+'</h1>' : '';
  f = f ? '<button onclick="if ('+f+' != false) zamDialogueClose();">OK</button> &nbsp; <button onclick="zamDialogueClose()">Cancel</button>' : '<button onclick="zamDialogueClose()">Close</button>';
  ge('jsbox').innerHTML = '<div id="dialogue-screen"></div><div id="dialogue"><div id="dialogue-cont"><div class="cont">'+t+c+'<div id="dialogue-bottom">'+f+'</div></div></div></div>';
}

function zamDialogueClose() {
  var b = ge('jsbox');
  if (b) b.parentNode.removeChild(b);
}

function zamLightbox(opts) {
  if (!ge('jsbox')) {
    var b = document.createElement('div');
    b.id = 'jsbox';
    document.body.insertBefore(b, document.body.childNodes[0]);
  }
  opts.title = opts.title ? '<h1>'+opts.title+'</h1>' : '';
  opts.closefunc = opts.closefunc ? '<a href="javascript:;" onclick="if ('+opts.closefunc+' != false) zamLightboxClose()" id="lightbox-close"></a>' : '<a href="javascript:;" onclick="zamLightboxClose()" id="lightbox-close"></a>';
  var mh = '';
  if (opts.top) {
    mh = window.innerHeight - parseInt(opts.top) - parseInt(opts.top);
  } else {
    mh = window.innerHeight - 400;
  }
  if (opts.capheight) opts.contstyle = opts.contstyle ? opts.contstyle + ';max-height:' + mh + 'px' : 'max-height:' + mh + 'px';
  opts.top = opts.top ? 'top:'+opts.top : '';
  opts.width = opts.width ? 'width:'+opts.width : '';
  if (opts.contstyle) {
    opts.contstyle = (opts.top || opts.width) ? ' style="' + opts.top + ';' + opts.width + ';' + opts.contstyle + '"' : '';
  } else {
    opts.contstyle = (opts.top || opts.width) ? ' style="' + opts.top + ';' + opts.width + '"' : '';
  }
  opts.style = opts.style ? ' style="'+opts.style+'"' : '';
  ge('jsbox').innerHTML = '<div id="lightbox-screen"></div><div id="lightbox"'+opts.style+'><div id="lightbox-cont"'+opts.contstyle+'><div class="bcont cont">'+opts.title+opts.content+opts.closefunc+'</div></div></div>';
}

function zamLightboxClose() {
  var b = ge('jsbox');
  if (b) b.parentNode.removeChild(b);
}

function toggleCollapsible (t) {
  if (t.className == 'toggle-collapse') {
    $(t).parent().next().slideToggle('fast');
    t.className='toggle-expand';
  } else {
    $(t).parent().next().slideToggle('fast');
    t.className='toggle-collapse';
  }
}

function addToMenu(t,c) {
  t = t.search('<a') < 0 ? '<a class="no-href">'+t+'</a>' : t;
  var txt = '<li class="has-sub last-child" onmouseover="hideads()" onmouseout="restoreads()">'+t;
  if (c) {
    txt += '<div><em></em><var></var><strong></strong><ul>';
    for (var i=0; i < c.length; i++) {
      c[i] = c[i].search('<a') < 0 ? '<a class="no-href">'+c[i]+'</a>' : c[i];
      txt += '<li>'+c[i]+'</li>';
    }
    txt += '</ul></div>';
  }
  txt += '</li>';
  $('#menu_tabbed > .cur > div > ul > li:last-child').removeClass('last-child');
  $('#menu_tabbed > .cur > div > ul').append(txt);
  $('#menu_tabbed > .cur > div > ul > li:last-child li:first-child').addClass('first-child');
  $('#menu_tabbed > .cur > div > ul > li:last-child li:last-child').addClass('last-child');
}

function setupNotificationScrollAdjuster() {
  $(window).scroll(function() {
    var $notifications = $('#notifications');
    var className = 'fixed-pos';
    if ($(window).scrollTop() > 40) {
      $notifications.addClass(className);
    } else {
      $notifications.removeClass(className);
    }
  });
  setupNotificationScrollAdjuster = function() {};
}

function notifybodymargin() {
  document.getElementsByTagName("body")[0].style.marginTop = ge("notifications").offsetHeight + 40 + 'px';
  setupNotificationScrollAdjuster();
}

function deletenote(el) {
  var e = el;
  while (e.parentNode.id != 'notifications') {
    e = e.parentNode;
  }
  e.parentNode.removeChild(e);
  notifybodymargin();
}

function toggleRecentVisitors(mrv) {
  if (mrv) {
    if (mrv.className.search('collapsed') > 0) {
      mrv.className = mrv.className.replace('collapsed','');
    } else {
      mrv.className += ' collapsed';
    }
  }
}

function menuCollapse(t) {
  var hide = 1;
  t = t.parentNode;
  if (t.className.search('collapse') > -1) {
    t.className = t.className.replace(/\b ?collapse\b/, '');
    hide = 0;
  } else {
    t.className += ' collapse';
  }
  var lis = ge('menu_vert').getElementsByTagName('li');
  for (var i=0; i<lis.length; i++) {
    if (t == lis[i]) {
      saveNewMenu(i,hide);
    }
  }
}

var hideMenu = 0;
function menuToggle(a) {
  if (ge('mctl').innerHTML != 'Show Menu' || a == "hide") {
    document.getElementsByTagName('body')[0].className += ' hideMenu';
    ge('mctl').innerHTML = 'Show Menu';
    hideMenu = 1;
  } else {
    var body = document.getElementsByTagName('body')[0];
    body.className = body.className.replace(/\b ?hideMenu\b/, '');
    ge('mctl').innerHTML = 'Hide Menu';
    hideMenu = 0;
  }
  saveNewMenu(-1,hideMenu);
}

function saveNewMenu (index,value) {
  var menu = readCookie("vertMenuState");
  var opts = menu.split(',');
  var kp = new Array;
  kp[0] = index +":" + value;
  for (var i=0;i < opts.length; i++) {
    var nv = opts[i].split(':');
    if (nv[0] && nv[0] != index) {
      kp.push(opts[i]);
    }
  }
  createCookie("vertMenuState",kp.join(','),300);
}

function doMenu () {
  var menu = readCookie("vertMenuState").split(',');
  var lis = ge('menu_vert').getElementsByTagName('li');
  for (var i=0;i < menu.length; i++) {
    var nv = menu[i].split(':');
    if (nv[0] == -1) {
      if (nv[1] == 1) {
        hideMenu = 1;
        menuToggle('hide');
      }
    } else if (nv[1] == 1) {
      lis[nv[0]].className += ' collapse';
    }
  }
}

function standardHeightCheck(goal,p) {
  if (document.body.offsetWidth < 974) {
    document.body.style.overflowX = 'visible';
  }

  var b = ge('bg-wrapper');
  var m = b.style.marginTop.replace('px','');
  var h = 0;
  if (typeof(window.innerHeight) == 'number') { //Non-IE
    h = window.innerHeight;
  } else if (document.documentElement && document.documentElement.clientHeight) { //IE
    h = document.documentElement.clientHeight;
  }
  if (b.offsetHeight + p < goal && b.offsetHeight + p < h) {
    b.style.minHeight = (h > goal + p + m ? goal : h - p - m)+'px';
  } else if (b.offsetHeight + p > goal && b.offsetHeight + p < h) {
    b.style.minHeight = (goal - p) + 'px';
  } else {
    b.style.minHeight = 'auto';
  }

  var gameColumn = ge('game-column');
  if (gameColumn) {
    var colMain = ge('col-main');
    var rowTop = ge('row-top');
    if (colMain.offsetHeight + rowTop.offsetHeight < gameColumn.offsetHeight) {
      colMain.style.height = gameColumn.offsetHeight - rowTop.offsetHeight + 'px';
    }
  }
}

function heightCheck() {
  document.body.style.minHeight='';
  standardHeightCheck(0,0);
}

function setupFeedPreview () {
  var t = ge('featured_title');
  var d = ge('featured_desc');
  t.onkeydown = feedPreview;
  t.onkeyup = feedPreview;
  d.onkeydown = feedPreview;
  d.onkeyup = feedPreview;
  feedPreview();
}

function feedPreview() {
  var t = ge('featured_title').value;
  var d = ge('featured_desc').value;
  var p = ge('news-feed-recent');
  if (t && d) {
    p.className = 'edit-mode';
    p.innerHTML = '<ul><li><b><a href="javascript:;">'+t+'</a></b>'+d+'</li></ul>';
  } else {
    p.className = 'edit-mode inactive';
    p.innerHTML = '';
  }
}

function liveSearch(s,e) {
  if (s) {
    if (e) {
      var key = e.which || e.keyCode;
      if (key == 38) {
        liveSearchUp();
      } else if (key == 40) {
        liveSearchDown();
      }
    }
    if (livesearchval == document.search.q.value) return;
    Ajakz.target = 'livesearch';
    Ajakz.onload = Ajakz.loadTarget;
    var lang = '';
    var llar = document.search.action.match(/\/(en|de|fr|ja)\/search\.html/);
    if (llar) {
      lang = 'lang=' + llar[1] + '&';
    }
    Ajakz.Run('/cluster/autocomp.pl?' + lang + 'q=' + encodeURIComponent(s));
    livesearchval = document.search.q.value;
    document.body.onclick = function() {liveSearch()}
  } else {
    document.getElementById('livesearch').innerHTML = '';
    livesearchval = '';
    document.body.onclick = '';
  }
}

function enableLiveSearch() {
  document.search.q.setAttribute('autocomplete', 'off');
  document.search.q.onkeyup = function(e) {
    e = e || window.event;
    liveSearch(this.value,e);
  }
  document.search.onsubmit = function(e) {
    if (liveSearchSubmit()) {
      return true;
    } else {
      return false;
    }
  }
  livesearchval = document.search.q.value;
}

function liveSearchUp() {
  if ($('#livesearch ul > .hlight').length > 0) {
    var prev = $('#livesearch ul > .hlight');
    if ($('#livesearch ul > .hlight') == $('#livesearch ul > .hlight').first) {
      prev.removeClass('hlight');
      $('#livesearch ul > li:last-child').addClass('hlight');
    } else {
      prev.removeClass('hlight');
      prev.prev().addClass('hlight');
    }
  } else {
    $('#livesearch ul > li:last-child').addClass('hlight');
  }
}

function liveSearchDown() {
  if ($('#livesearch ul > .hlight').length > 0) {
    var prev = $('#livesearch ul > .hlight');
    if ($('#livesearch ul > .hlight') == $('#livesearch ul > .hlight').last) {
      prev.removeClass('hlight');
      $('#livesearch ul > li:first-child').addClass('hlight');
    } else {
      prev.removeClass('hlight');
      prev.next().addClass('hlight');
    }
  } else {
    $('#livesearch ul > li:first-child').addClass('hlight');
  }
}

function liveSearchSubmit() {
  if ($('#livesearch ul > .hlight').length > 0) {
    location.href = $('#livesearch ul > .hlight a').attr('href');
    return false;
  } else {
    return true;
  }
}

// thread follow async

function followToggle(a,m) {
  var en = 'FOLLOW_MID_' + m;
  var url = '/cgi-bin/follow.pl?noredir=1&action=' + a + '&mid=' + m;

  var c = '';
  var t = '';
  var h = '';

  if (a == 'unfollow') {
    c = 'icon-follow';
    t = 'Follow';
    h = "followToggle('add','" + m + "');";
  } else if (a == 'add') {
    c = 'icon-unfollow';
    t = 'UN-Follow';
    h = "followToggle('unfollow','" + m + "');";
  } else {
    // console.log('huh? a="' + a + '"');
  }

  Ajakz.onload = followLink;
  Ajakz.Run(url);

  var ele = document.getElementById(en);
  ele.innerHTML = t;
  ele.setAttribute('class',c);
  ele.setAttribute('onclick',h);
}

function followToggle2(el) {
  var a = c = t = '';

  if (el.innerHTML.search('Followed') > -1) {
    a = 'unfollow';
    c = 'f-follow';
    t = '<span>Follow</span><b></b>';
  } else {
    a = 'add';
    c = 'f-follow checked';
    t = '<span>Followed</span><b></b>';
  }

  var url = '/cgi-bin/follow.pl?noredir=1&action=' + a + '&mid=' + el.id.replace(/follow_(.*)/, '$1');
  Ajakz.onload = followLink;
  Ajakz.Run(url);

  el.innerHTML = t;
  el.className = c;
}

function stickyToggle(ele,m) {
  var a = '';

  if (ele.className == 'f-sticky') {
    a = 's';
    ele.className = 'f-sticky checked';
    ele.innerHTML = '<span>Stickied</span><b></b>';
  } else {
    a = 'us';
    ele.className = 'f-sticky';
    ele.innerHTML = '<span>Sticky</span><b></b>';
  }

  return rateMsg2(m,a);
}

function lockToggle(ele,m) {
  var a = '';
  var res = '';
  if (ele.className == 'f-lock') {
    res = prompt('Reason for Lock?');
    if (res != null) {
      a = 'l';
      ele.className = 'f-lock checked';
      ele.innerHTML = '<span>Locked</span><b></b>';
    } else {
      return;
    }
  } else {
    a = 'ul';
    ele.className = 'f-lock';
    ele.innerHTML = '<span>Lock</span><b></b>';
  }

  rateMsg2(m,a,res);
}

function followLink () {
  if (Ajakz.req.readyState == 4) {
    if (Ajakz.req.status == 200) {
      var txt = Ajakz.req.responseText;
      if (txt) {
        document.location=txt;
      }
    }
  }
}

function forumTags(clearScreen, text) {
  var el = ge('url-input');
  var result = false;
  if (el) {
    var url = el.value == 'http://' || el.value == '' ? 'http://www.example.com/' : el.value;
    if (typeof text != 'undefined') {
      doTag(document.pm.body, '[link='+url+']', '[/link]', text);
    } else {
      doTag(document.pm.body, '[link='+url+']', '[/link]', url);
    }
    result = true;
  } else {
    el = ge('youtube-input');
    if (el) {
      var v = el.value == '' ? 'dQw4w9WgXcQ' : el.value;
      v = v.replace(/https?:\/\/(www.)?youtube.com\/watch\?v=([^&]+)(&.*)?/,'$2');
      doTag(document.pm.body, '[youtube='+v+']', '', '');
      result = true;
    }
  }
  if (clearScreen) {
    var b = ge('jsbox');
    if (b) b.parentNode.removeChild(b);
  } else {
    return result;
  }
}

// Featured news story box

function featuredTabHover (link,userActivated,nextNum) {
  if (link) {
    if (userActivated == 1) {
      noRotate = 1;
      if (typeof rotateID != 'undefined' && rotateID) clearTimeout(rotateID);
    }

    var prevNum = '';
    if (!nextNum) var nextNum = link.id.replace(/featured-(\d)_tab/, '$1');

    var lis = link.parentNode.parentNode.childNodes;
    for (var i = 0; i < lis.length; i++) {
      if (lis[i].tagName == 'LI') {
        if (lis[i].className != '') prevNum = i+1;
        lis[i].className = '';
      }
    }

    link.parentNode.className = 'current';
    var prevDiv = ge('featured-'+prevNum+'_t');
    var nextDiv = ge('featured-'+nextNum+'_t');
    if (prevNum != nextNum) {
      nextDiv.style.display = 'block';
      if (prevNum < nextNum) {
        prevDiv.style.zIndex = '1';
        $(prevDiv).animate({top:'-402px'},'fast','swing',function(){resetDivs(nextDiv)});
      } else {
        nextDiv.style.zIndex = '1';
        nextDiv.style.top = '-402px';
        $(nextDiv).animate({top:0},'fast','swing',function(){resetDivs(nextDiv)});
      }
    } else {
      prevDiv.style.display = 'none';
      nextDiv.style.display = '';
    }
  }
}

function resetDivs(n) {
  for (var i = 1; i < 6; i++) {
    var p = ge('featured-'+i+'_t');
    if (p.id != n.id) {
      p.style.display='none';
      p.style.top='0';
      p.style.zIndex='0';
    }
  }
  n.style.display='';
  n.style.top='0';
  n.style.zIndex='0';
}

var nextNum = 1;
function rotateFB () {
  var targ = 'featured-' + nextNum +'_tab';
  if (targ) {
    featuredTabHover(document.getElementById(targ));
    rotateID = setTimeout(rotateFB,9000);
    nextNum = (nextNum >=5) ? 1 : nextNum + 1;
  }
}

if (typeof noRotate == 'undefined') addEvent(window,'load',rotateFB);

// Countdown clock

function display() {
  var cd = document.getElementById('countdown-clock');
  var minutes = parseInt(cd.getElementsByTagName('i')[0].innerHTML);
  var hours = parseInt(cd.getElementsByTagName('b')[0].innerHTML);
  var days = parseInt(cd.getElementsByTagName('u')[0].innerHTML);

  if (minutes <= 0){
    minutes = 59;
    hours -= 1;
  }

  if (hours<0){
    hours = 23;
    days -= 1;
  }

  if (days < 0 || (days * 1440) + (hours * 24) + minutes < 1){ //Launched!
    cd.childNodes[0].childNodes[0].innerHTML = '';
    cd.className += ' countdown-complete';
  } else {
    minutes -= 1;
    cd.getElementsByTagName('i')[0].innerHTML = minutes;
    cd.getElementsByTagName('b')[0].innerHTML = hours;
    cd.getElementsByTagName('u')[0].innerHTML = days;
    setTimeout("display()",60000);
  }
}

function enableCountdown() {
  var cd = document.getElementById('countdown-clock');
  if ((cd && !cd.className) || (cd && !cd.className.search('countdown-complete'))) {
    cd = cd.getElementsByTagName('a')[0];
    var minutes = parseInt(cd.getElementsByTagName('i')[0].innerHTML);
    var hours = parseInt(cd.getElementsByTagName('b')[0].innerHTML);
    var days = parseInt(cd.getElementsByTagName('u')[0].innerHTML);
    if (!minutes) minutes = 0;
    if (!hours) hours = 0;
    if (!days) days = 0;

    if (minutes + hours + days > 0) {
      display();
    } else {
      cd.childNodes[0].innerHTML = '';
      cd.parentNode.className += ' countdown-complete';
    }
  } else {
    cd.childNodes[0].childNodes[0].innerHTML = '';
  }
}

function showloading () {
  ge('loading').style.display="";
}

function hideloading () {
  ge('loading').style.display="none";
}

function userconfig (type) {
  showloading();
  Ajakz.target = 'userconfig';
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {hideloading();}
  Ajakz.Run('/cgi-bin/a.pl?config=' + type);
}

function showloading2 () { // For new (forum-revamp branch) profile layout
  var c = ge('config')
  if (c) {
    $(c).append('<div id="screen" class="bcont"></div><div id="loading"></div>');
    c.className = 'content loading';
  }
}

function hideloading2 () { // For new (forum-revamp branch) profile layout
  var c = ge('config')
  if (c) {
    c.className = 'content';
    $(c).append('<a href="javascript:;" onclick="var c=ge(\'config\');c.className=\'\';c.innerHTML=\'\'" id="close"></a>');
  }
}

function loadconfig (type) { // For new (forum-revamp branch) profile layout; userconfig() is the deprecated one.
  showloading2();
  Ajakz.target = 'config';
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {hideloading2();}
  if (type == 'contacts') {
    Ajakz.loadTargetHook = function () {hideloading2();hashtab();}
  }
  Ajakz.Run('/cgi-bin/a.pl?config=' + type);
}

function setconfig2 (type, val, refresh, uid) { // For new (forum-revamp branch) profile layout
  var u = uid ? '&uid=' + uid : '';
  showloading2();
  Ajakz.target = 'config';
  var configscreen = type;
  if (type == 'avatar') {
    configscreen = 'avatar_general';
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.loadTargetHook = function () {updateavatar2();}
  } else if (type == 'email') {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.target = 'chgemf';
    Ajakz.loadTargetHook = function () {hideloading2();}
  } else if ( type == 'admin_notes' ||
     (uid && (type == 'sig' || type == 'bio' || type == 'location'))) {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.target = 'admin_notes_b';
    Ajakz.loadTargetHook = function () {hideloading2();document.getElementById('admin_notes').value='';}
  } else if (type == 'rename' || type == '2p') {
    Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {
      if (Ajakz.req.responseText.match(/^Error:/)) {
        document.getElementById(type+'_e').innerHTML = Ajakz.req.responseText;
        hideloading();
      } else {
        document.location='/users/' + val;
      }
    }}
    Ajakz.loadTargetHook = undefined;
  } else if (type == 'contacts') {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.loadTargetHook = function () {hideloading2();hashtab();}
  } else {
    if (refresh) {
      Ajakz.onload = Ajakz.loadTarget;
      Ajakz.loadTargetHook = function () {hideloading2();}
    } else {
      Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {hideloading2();} }
      Ajakz.loadTargetHook = undefined;
    }
  }
  Ajakz.Run('/cgi-bin/a.pl?set=' + type + '&val=' + val + '&config=' + configscreen + u);
}

function updateavatar2 () {
  Ajakz.target = 'avatarcontainer';
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {$('#config').append('<a href="javascript:;" onclick="var c=ge(\'config\');c.className=\'\';c.innerHTML=\'\'" id="close"></a>');}
  Ajakz.Run('/cgi-bin/a.pl?get=avatar');
}

function chooseAvatar(avatar,gallery) {
  ge('selected-avatar').src = avatar.src;
  ge('selected-avatar').style.height = avatar.offsetHeight+'px';
  ge('selected-avatar').style.width = avatar.offsetWidth+'px';
  if (avatar.id) {
    if (avatar.id == 'img0') { // "No Avatar"
      ge('set-avatar').value = '0';
      ge('avatar-type-none').checked = true;
    } else {
      ge('set-avatar').value = avatar.id.replace(/img(.*)/, '$1');
      if (gallery) {
        ge('avatar-type-gallery').checked = true;
      } else {
        ge('avatar-type-custom').checked = true;
      }
    }
  }
  zamLightboxClose();
}


function reviewAvatarSettings() {
  var av = ge('set-avatar').value;
  if (av.match(/^[0-9]+$/) && av != '0') { // Custom avatar
    ge('avatar-type-custom').checked = true;
  } else if (av == '0') { // No avatar
    ge('avatar-type-none').checked = true;
  } else { // Gallery avatar
    ge('avatar-type-gallery').checked = true;
  }
  return true;
}

function checkAvatarSettings(n) {
  if (n == 1) {
    avatarGallery('gallery');
  } else if (n == 2) {
    avatarGallery('custom');
  } else {
    ge('set-avatar').value = '0';
    ge('selected-avatar').src = ZAM.staticUrl() + '/images/7/3/73ee882dfe9de9b228d4c71d9d0109d4.jpg';
    ge('selected-avatar').style.height = '120px';
    ge('selected-avatar').style.width = '120px';
  }
}

function avatarGallery(type,val) {
  var opts = {width:'840px', content:'<div id="avatar-gallery"><div id="loading"></div></div>', closefunc:'reviewAvatarSettings()'};
  if (type == 'custom') {
    opts.title = 'Your Avatars';
  } else {
    opts.title = 'ZAM Avatar Gallery';
  }
  zamLightbox(opts);

  if (type == 'custom') {
    Ajakz.target = 'avatar-gallery';
    Ajakz.loadTargetHook = avatarGalleryHeight;
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.Run('/cgi-bin/a.pl?config=avatar');
  } else {
    Ajakz.target = 'avatar-gallery';
    Ajakz.loadTargetHook = avatarGalleryHeight;
    Ajakz.onload = Ajakz.loadTarget;
    if (val) {
      Ajakz.Run('/cgi-bin/a.pl?config=avatar_gallery&val=' + val);
    } else {
      Ajakz.Run('/cgi-bin/a.pl?config=avatar_gallery');
    }
  }
}

function avatarGalleryHeight() {
  var d = ge('avatar-gallery-avatars');
  if (d) {
    if (ge('lightbox-cont').offsetHeight + 400 > $(window).height()) {
      d.className = 'scroll';
      var h = $(window).height() - 400;
      if (h < 100) h = 100;
      d.style.maxHeight = h+'px';
    }
  }
}

function setconfig (type, val, refresh, uid) {
  var u = uid ? '&uid=' + uid : '';
  showloading();
  Ajakz.target = 'userconfig';
  if (type == 'avatar') {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.loadTargetHook = function () {updateavatar();}
  } else if (type == 'email') {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.target = 'chgemf';
    Ajakz.loadTargetHook = function () {hideloading();}
  } else if ( type == 'admin_notes' ||
     (uid && (type == 'sig' || type == 'bio' || type == 'location'))) {
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.target = 'admin_notes_b';
    Ajakz.loadTargetHook = function () {hideloading();document.getElementById('admin_notes').value='';}
  } else if (type == 'rename' || type == '2p' || type == 'remail') {
    Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {
      if (Ajakz.req.responseText.match(/^Error:/)) {
        document.getElementById(type+'_e').innerHTML = Ajakz.req.responseText;
        hideloading();
      } else {
        document.location='/users/' + Ajakz.req.responseText;
      }
    }}
    Ajakz.loadTargetHook = undefined;
  } else {
    if (refresh) {
      Ajakz.onload = Ajakz.loadTarget;
      Ajakz.loadTargetHook = function () {hideloading();}
    } else {
      Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {hideloading();} }
      Ajakz.loadTargetHook = undefined;
    }
  }
  Ajakz.Run('/cgi-bin/a.pl?set=' + type + '&val=' + val + '&config=' + type + u);
}

function updateavatar () {
  Ajakz.target = 'avatarcontainer';
  Ajakz.onload = Ajakz.loadTarget;
  Ajakz.loadTargetHook = function () {hideloading();}
  Ajakz.Run('/cgi-bin/a.pl?get=avatar');
}

function togglesl () {
  document.getElementById('settinglist').style.display == 'none' ?
    document.getElementById('settinglist').style.display = '' :
    document.getElementById('settinglist').style.display = 'none';
}

function changemail (t) {
  t.parentNode.style.display='none';
  document.getElementById('chgemf').style.display='';
  var e = document.getElementById('emailinp');
  e.focus();
  e.select();
}

function gc (thing, maxl) {
  if (document.getElementById(thing).value.length > maxl) {
    document.getElementById(thing).value = document.getElementById(thing).value.substr(0,maxl);
    return false;
  }
  document.getElementById('curCount' + thing).innerHTML = document.getElementById(thing).value.length;
}

function addcontact (t, type, val) {
  t.innerHTML = "Adding..";
  Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {t.innerHTML="Done.";} }
  Ajakz.loadTargetHook = undefined;
  Ajakz.Run('/cgi-bin/a.pl?set=contacts&val=' + type + ':a:' + val);
}

function delcontact (t, type, val) {
  t.innerHTML = "Removing..";
  Ajakz.onload = function () { if (Ajakz.req.readyState == 4 && Ajakz.req.status == 200) {t.innerHTML="Done.";} }
  Ajakz.loadTargetHook = undefined;
  Ajakz.Run('/cgi-bin/a.pl?set=contacts&val=' + type + ':d:' + val);
}

function forumCollapse(t,c,skipsave) {
  var table = ge('forumListCat'+c).getElementsByTagName('table')[0];
  if (t.className == 'toggle-collapse') {
    table.style.display='none';
    t.className='toggle-expand';
    if (!skipsave) saveCollapsedForums(c,1);
  } else {
    table.style.display='table';
    t.className='toggle-collapse';
    if (!skipsave) saveCollapsedForums(c,0);
  }
}

function saveCollapsedForums(cat,value) {
  var fstate = readCookie("forumCollapseState");
  var opts = fstate.split(',');
  var kp = new Array;
  kp[0] = cat + ':' + value;
  for (var i=0;i < opts.length; i++) {
    var nv = opts[i].split(':');
    if (nv[0] && nv[0] != cat) kp.push(opts[i]);
  }
  createCookie("forumCollapseState",kp.join(','),300);
}

function initCollapsedForums() {
  var cookie = readCookie("forumCollapseState").split(',');
  var divs = ge('forumsList').getElementsByTagName('div');
  for (var i in divs) {
    if (divs[i].id) {
      var cat = divs[i].id.replace(/forumListCat([0-9]+)/,'$1');
      if (cat) {
        for (var j in cookie) {
          if (cookie[j].match('^'+cat+':')) {
            var nv = cookie[j].split(':');
            if (nv[1] == 1) forumCollapse(ge('forumListCat'+cat).getElementsByTagName('a')[0],cat,1);
          }
        }
      }
    }
  }
}

function forumCollapse2(toggle,cat,skipsave) {
  var table = ge('f-cat-'+cat).parentNode.parentNode.parentNode.parentNode;
  if (toggle.className == 'toggle-collapse') {
    table.className='collapsed';
    toggle.className='toggle-expand';
    if (!skipsave) saveCollapsedForums2(cat,1);
  } else {
    table.className='';
    toggle.className='toggle-collapse';
    if (!skipsave) saveCollapsedForums2(cat,0);
  }
}

function saveCollapsedForums2(cat,value) {
  var fstate = readCookie("forumCollapseState");
  var opts = fstate.split(',');
  var kp = new Array;
  kp[0] = cat + ':' + value;
  for (var i=0;i < opts.length; i++) {
    var nv = opts[i].split(':');
    if (nv[0] && nv[0] != cat) kp.push(opts[i]);
  }
  createCookie("forumCollapseState",kp.join(','),300);
}

function initCollapsedForums2() {
  var cookie = readCookie("forumCollapseState").split(',');
  var as = ge('forums').getElementsByTagName('a');
  for (var i in as) {
    if (as[i].id) {
      var cat = as[i].id.replace(/f-cat-([0-9]+)/,'$1');
      if (cat) {
        for (var j in cookie) {
          if (cookie[j].match('^'+cat+':')) {
            var nv = cookie[j].split(':');
            if (nv[1] == 1) forumCollapse2(ge('f-cat-'+cat),cat,1);
          }
        }
      }
    }
  }
}

function toggleFilters(t) {
  if (t.innerHTML == filter_toggle) {
    t.innerHTML=filter_initial; $('.listfilters').slideToggle('fast');
  } else {
    t.innerHTML=filter_toggle; $('.listfilters').slideToggle('fast');
  }
}

function multiQuote(el) { // This is the forum revamp version, since multiQuote2 happens to be the live version right now.
  if (el != undefined) {
    if (el.className.search('checked') > -1) {
      el.className = el.className.replace(/ checked/g, '');
    } else {
      el.className += ' checked';
    }
  }
  var qs=[];
  if (typeof oldqs == 'undefined') {
    oldqs=[];
    if (location.href.search('&quote=') > -1) {
      var lqs = location.href.replace(/.*&quote=([0-9,]+).*/, '$1');
      oldqs = lqs.split(',');
    }
  }
  var type = ge('thread') ? 'thread' : 'comments';
  var as=ge(type).getElementsByTagName('a');
  for (var i in as) {
    if (as[i].className && as[i].className.search('f-quote') > -1 && as[i].className.search('checked') > -1) {
      qs.push(as[i].id.replace(/^quote-/,''));
    }
  }
  if (oldqs.length > 0 || qs.length > 0) {
    var newqs = '&quote='+oldqs.join(',');
    if (oldqs.length > 0 && qs.length > 0) newqs += ',';
    newqs += qs.join(',');
    $('#'+type+' a.f-reply').each(function() {
      if (this.href.search('&quote=') > -1) {
        this.href = this.href.replace(/&quote=[0-9,]+/, newqs);
      } else {
        this.href = this.href.replace(/(#post)?$/, newqs+'$1');
      }
    });
    $('#'+type+' .post-buttons > a:first-child').each(function() {
      if (this.href.search('&quote=') > -1) {
        this.href = this.href.replace(/&quote=[0-9,]+/, newqs);
      } else {
        this.href = this.href.replace(/(#post)?$/, newqs+'$1');
      }
    });
    $('#'+type+' .pages a').each(function() {
      if (this.href.search('&quote=') > -1) {
        this.href = this.href.replace(/&quote=[0-9,]+/, newqs);
      } else {
        this.href = this.href.replace(/(#post)?$/, newqs+'$1');
      }
    });
    var hq = ge('hidden-quote');
    if (hq) hq.style.display = 'inline';
  } else {
    $('#'+type+' a.f-reply').each(function() {
      this.href = this.href.replace(/&quote=[0-9,]+/, '');
    });
    $('#'+type+' .post-buttons > a:first-child').each(function() {
      this.href = this.href.replace(/&quote=[0-9,]+/, '');
    });
    $('#'+type+' .pages a').each(function() {
      this.href = this.href.replace(/&quote=[0-9,]+/, '');
    });
    var hq = ge('hidden-quote');
    if (hq) hq.style.display = '';
  }
}

function ajaxMultiQuote() {
  var qs=[];
  var type = ge('thread') ? 'thread' : 'comments';
  var as=ge(type).getElementsByTagName('a');
  for (var i in as) {
    if (as[i].className && as[i].className.search('f-quote') > -1 && as[i].className.search('checked') > -1) {
      qs.push(as[i].id.replace(/^quote-/,''));
    }
  }
  var ret = '';
  if (qs.length > 0) {
    Ajakz.target = 'hidden-quote-response';
    Ajakz.onload = Ajakz.loadTarget;
    Ajakz.loadTargetHook = function () {ajaxMultiQuotePlace()}
    Ajakz.Run('/cgi-bin/forum.pl?getquotes='+qs);
  }
}

function ajaxMultiQuotePlace() {
  var r = ge('hidden-quote-response');
  if (r) insertAtCaret(document.pm.body,r.value);
}

function multiQuote2() {
  var is=document.getElementsByTagName('input');
  var qs=[];
  if (typeof oldqs == 'undefined') {
    oldqs=[];
    if (location.href.search('&quote=') > -1) {
      var lqs = location.href.replace(/.*&quote=([0-9,]+).*/, '$1');
      oldqs = lqs.split(',');
    }
  }
  for (var i in is) {
    if (is[i].className == 'msgQuoteCheck') {
      if (is[i].checked == 1) {
        qs.push(is[i].id.replace(/^quote-/,''));
      }
    }
  }
  if (oldqs.length > 0 || qs.length > 0) {
    var newqs = '&quote='+oldqs.join(',');
    if (oldqs.length > 0 && qs.length > 0) newqs += ',';
    newqs += qs.join(',');
    $('a.icon-replytothis').each(function() {
      if (this.href.search('&quote=') > -1) {
        this.href = this.href.replace(/&quote=[0-9,]+/, newqs);
      } else {
        this.href = this.href + newqs;
      }
    });
    $('div.msgCtrlPager a').each(function() {
      if (this.href.search('fsearch') < 0 && this.href.search('live') < 0 && this.href.search('follow') < 0) {
        if (this.href.search('&quote=') > -1) {
          this.href = this.href.replace(/&quote=[0-9,]+/, newqs);
        } else {
          this.href = this.href + newqs;
        }
      }
    });
  } else {
    $('a.icon-replytothis').each(function() {
      this.href = this.href.replace(/&quote=[0-9,]+/, '');
    });
    $('div.msgCtrlPager a').each(function() {
      if (this.href.search('fsearch') < 0 && this.href.search('live') < 0 && this.href.search('follow') < 0) {
        this.href = this.href.replace(/&quote=[0-9,]+/, '');
      }
    });
  }
}

// Akz Ratings
var starscale    = 5;
var lastRatable  = false;
var avgboxname   = undefined;
var lastRatePC   = undefined;
var lastRatePI   = undefined;
var lastResponse = undefined;
var lastRating   = undefined;
var lastRateU    = undefined;

function deleteRating(c,i,u) {
  avgboxname = 'ratingdisp' + u + c + i;
  lastRatePC = c;
  lastRatePI = i;
  lastRateU = u;
  lastRating = 0;
  lastRatable = true;
  Ajakz.onload = ratingStarHandler;
  var url = '/cgi-bin/ratings.pl?showavg=1&amp;action=delrate&amp;page_cat=' + c + '&amp;page_id=' + i + '&amp;scale=' + starscale;
  Ajakz.Run(url);
}

function addRating(u,i,c,r,s) {
  avgboxname = 'ratingdisp' + u + c + i;
  starscale = s;
  lastRatePC = c;
  lastRatePI = i;
  lastRating = r;
  lastRateU = u;
  lastRatable = true;
  Ajakz.onload = ratingStarHandler;
  var url = '/cgi-bin/ratings.pl?showavg=1&amp;action=rate&amp;rating=' + r + '&amp;scale=' + s + '&amp;page_cat=' + c + '&amp;page_id=' + i;
  Ajakz.Run(url);
}

function ratingStarHandler() {
  if (Ajakz.req.readyState == 4) {
    if (Ajakz.req.status == 200) {
      lastResponse = Ajakz.req.responseText;
      updateRatingStars(lastRating,starscale,lastRatePC,lastRatePI,lastRatable,lastRateU);
    }
  }
}

function makeRatingStars(rate1,rate2,scale,pagecat,pageid,ratable,user) {
  var starhtml = '';
  for (i=1;i<=starscale;i++) {
    var hclass = '';
    var tmprate = rate1;
    tmprate++;
    if (rate2 > 0) {
      if (i == tmprate) {
        hclass = ' class="half"';
      }
    } else {
      hclass = '';
    }
    var iclass = ratable ? ' onclick="addRating(\''  + user  + '\',\'' + pageid + '\',\'' + pagecat + '\',\'' + i + '\',\'' + scale + '\')"' : '';
    starhtml = starhtml + '<b' + hclass + '><i' + iclass + '><i>*</i></i>';
  }

  for (i=1;i<=scale;i++) {
    starhtml = starhtml + '</b>';
  }

  return starhtml;

}

// update the stars for the user rating
function updateRatingStars(rating,scale,pagecat,pageid,ratable,user) {

  // track whether or not we voted.
  var nv = ge('numVotes' + user + pagecat + pageid);
  var hasVoted = 0;
  var numVotes = 0;
  if ( nv && nv.innerHTML.length > 0 ) {
    hasVoted = nv.innerHTML.split('/')[0] && parseInt(nv.innerHTML.split('/')[0]) == 1 ? 1 : 0;
    numVotes = nv.innerHTML.split('/')[1] ? parseInt(nv.innerHTML.split('/')[1]) : 0;

    if ( hasVoted == 1 && rating <= 0 ) {
      hasVoted = 0;
      numVotes--;
    } else if ( hasVoted == 0 && rating > 0 ) {
      hasVoted = 1;
      numVotes++
    }

    nv.innerHTML = hasVoted + '/' + numVotes;
  }

  var votestr = numVotes == 1 ? 'rating' : 'ratings';

  // OK. If I've rated this, use my rating. Otherwise, use the average.
  var rateToUse = hasVoted == 1 && rating > 0 ? rating : lastResponse;

  rating = rateToUse.toString();
  var rateStr = 0.0;
  if ( !rating.match(/\./) ) {
    if ( rating ) {
      rateStr = rating + '.0';
    }
  } else {
    rateStr = rating;
  }
  var rate1 = 0;
  var rate2 = 0;
  var re = /(\d+)\.(\d+)/;
  m = re.exec(rateStr);

  // roundingish thing.
  rate1 = m[1];
  if (m[2] > 0) {
    if (m[2] <= 33) {
      ;
    } else if (m[2] <= 66) {
      rate2 = 5;
    } else {
      rate1++;
    }
  }

  // no need to do half-stars here since this only runs when we update, and that update uses the current scale
  var starhtml = '';

  if (rateToUse > 0) {
    var ratedWord = hasVoted == 1 ? ' rated' : ''
    starhtml = '<span class="ratingstars ratingstars-' + rate1 + ratedWord + '">';
  } else {
    starhtml = '<span class="ratingstars">';
  }

  starhtml += makeRatingStars(rate1,rate2,scale,pagecat,pageid,ratable,user);

  // and write it.
  ge('ratingdisp' + user + pagecat + pageid).innerHTML = starhtml + '</span>';

  // get the vote text and all that fun
  var avgtext = '';
  if (hasVoted == 1 && parseInt(lastResponse) > 0 && numVotes > 1) {
    lastResponse = lastResponse.replace(/0+$/g, '');
    lastResponse = lastResponse.replace(/\.$/g, '');
    avgtext = '<small>(Average: ' + lastResponse + ' from';
  } else {
    if (numVotes > 1) { // Supposed to check if there is more than 1 vote to average from.
      avgtext = '<small>(Average from';
    } else {
      avgtext = '<small>(From';
    }
  }

  // only have the link text if there's actually a reason (i.e. there is at least one non-me vote)
  if ( numVotes <= 0 ) {
    avgtext = '';
  } else if ( numVotes == 1 && hasVoted == 1 ) {
    avgtext += ' ' + numVotes + ' ' + votestr + ')</small>';
  } else {
    avgtext += ' <a href="/cgi-bin/ratings.pl?action=listall&amp;page_cat=' + pagecat + '&amp;page_id=' + pageid + '">' + numVotes + ' ' + votestr + '</a>)</small>';
  }

  avgtext = avgtext ? avgtext : '';

  // write the avg
  ge('ratingavgdisp' + user + pagecat + pageid).innerHTML = avgtext;

  // clear button
  var cdiv = ge('clearrating' + user + pagecat + pageid);
  if ( hasVoted == 1 ) {
    if ( cdiv && cdiv.innerHTML && cdiv.innerHTML.length > 0 ) {
      ; // assume it's already correct and right. Also, js really needs an 'unless' like Perl.
    } else {
       cdiv.innerHTML = ' &nbsp;<a onclick="deleteRating(\'' + pagecat + '\',\'' + pageid + '\',\'' + user + '\')" class="ratingstars-clear bcont" title="Clear my rating"><span>Clear</span></a>';
    }
  } else {
    if ( cdiv ) {
      cdiv.innerHTML = '';
    }
  }

  // reset da vars
  starscale    = 5;
  lastRatable  = false;
  avgboxname   = undefined;
  lastRatePC   = undefined;
  lastRatePI   = undefined;
  lastResponse = undefined;
  lastRateU    = undefined;
}

function findPos(obj) {
    if (obj.offsetParent) {
      var curleft = curtop = 0;
      do {
        curleft += obj.offsetLeft;
        curtop += obj.offsetTop;
      } while (obj = obj.offsetParent);
      return [curleft,curtop];
    }
}

function googleMapExpand() {
  var l = ge('lightbox');
  if (l) { // close
    var $ml = $('#google-map-lightbox');
    if ($ml.size()) {
      var $m = $('#google-map');
      $ml.children('div').appendTo($m);
      $m.children('div').css({height:'250px',width:'300px'});
      google.maps.event.trigger(map, "resize");
      zamLightboxClose();
      return true;
    }
  } else { // open
    var $m = $('#google-map');
    if ($m.size()) {
      var opts = {width: '840px', content: '<div id="google-map-lightbox"></div>', closefunc: 'googleMapExpand()'};
      zamLightbox(opts);
      var $ml = $('#google-map-lightbox');
      $m.children('div').appendTo($ml);
      $ml.children('div').css({height:'615px',width:'820px'});
      google.maps.event.trigger(map, "resize");
    }
  }
}

function showSpoiler(e) {
  e.onclick=function(){hideSpoiler(this)};
  $(e.parentNode).children('span').fadeIn();
}

function hideSpoiler(e) {
  e.onclick=function(){showSpoiler(this)};
  $(e.parentNode).children('span').fadeOut();
}

function showBetaSchedule(html) {
  $('#zam-giveaway').append($('<div/>', {
    'class': 'bcont',
    'style': 'box-shadow:0 0 15px #000;font-size:15px;left:150px;line-height:1.5em;padding:20px;position:absolute;right:150px;top:150px',
    'html': html + '<p style="margin-bottom:0"><a href="javascript:;" onclick="rm(this.parentNode.parentNode)">Close</a></p>'
  }));
}

function logClick(category, label) {
  if (category && label) {
    if (typeof _gaq != 'undefined') {
      _gaq.push(['_trackEvent', category, 'Clickthrough', label]);
    } else {
      setTimeout(function(){logClick(category, label), 5});
    }
  }
}

function logView(category, label) {
  if (category && label) {
    if (typeof _gaq != 'undefined') {
      _gaq.push(['_trackEvent', category, 'Impression', label]);
    } else {
      setTimeout(function(){logView(category, label), 50});
    }
  }
}

/**
 * Site-specific configuration and functions for ads
 */
Ads.site = new function() {
    /**
     * @type {object} config - This object contains the site-specific config for ad support.
     */
    var config = {
        /**
         * @property {string} provider - Which provider module to use. Matches the name of the provider that is attached to Ads. E.g. Ads.MonkeyBroker, thus 'MonkeyBroker'.
         * @property {string} defaultChannel - The channel to use when no specific channel is found. Channel names used must be communicated to Jerek/Amanda.
         * @property {boolean} [parentIsPartOfUnit] - Whether to treat the immediate parent of the Ads.create targets as part of the ad unit when hiding or removing ads. Undefined is equivalent to false.
         */
        provider: 'MonkeyBroker',
        defaultChannel: 'Other',
        parentIsPartOfUnit: true,
        /**
         * @property {object} [skins] - Configuration specific to skins. Not required even if your site gets ads served through a provider, assuming the defaults work for your site.
         */
        skins: {
            /**
             * @property {string} [bodyClass] - What class to apply to the body tag when a skin is loaded. This is so you can make page-wide style adjustments accordingly.
             * @property {string} [classPrefix] - The prefix to use on skin element classes. A value of 'foo' would result in 'foo-wrap', 'foo-pixel', 'foo-link-1', etc.
             * @property {number} [linkCount] - How many <a> tags to print out for a skin, to be manipulated by your site as needed. For example, 2 would result in 2 <a> tags with the classes 'foo-link-1' and 'foo-link-2', where 'foo' is defined in the classPrefix above.
             */
            bodyClass: 'skinned',
            classPrefix: 'skin',
            linkCount: 2
        },

        /**
         * @property {object} units - These are the basic ad unit definitions for what units your site supports and how the ad script should handle them.
         */
        units: {
            /**
             * @property {object} leaderboard|leaderboardBTF|medrec|medrecBTF|skyscraper|skyscraperBTF|skin|fixedFooter - You can rename these properties to whatever you like; these possible names are just suggestions for clarity. Whatever name they are given is what will be used as the first argument in an Ads.create call. The 'dimensions' property is the only one required within each unit definition.
             */
            leaderboard: {
                /**
                 * @property {object} [applyClasses] - What classes to apply to the target ad unit, or its parent.
                 * @property {Array} [applyClasses.unit] - An array of strings to apply to the target ad unit.
                 * @property {Array} [applyClasses.parent] - An array of strings to apply to the target ad unit's parent.
                 * @property {Array} dimensions - The pixel dimensions of the unit. Must be width then height, as integers. This is the only property required within each unit definition.
                 */
                applyClasses: {
                    parent: ['header-bg']
                },
                dimensions: [728, 90]
            },
            leaderboardBTF: {
                applyClasses: {
                    parent: ['footer-bg']
                },
                dimensions: [728, 90]
            },
            horizontal: {
                applyClasses: {
                    parent: ['header-bg']
                },
                dimensions: [728, 90]
            },
            medrec: {
                applyClasses: {
                    parent: ['block-bg']
                },
                dimensions: [300, 250]
            },
            medrecBTF: {
                applyClasses: {
                    parent: ['block-bg']
                },
                dimensions: [300, 250]
            },
            skyscraper: {
                applyClasses: {
                    parent: ['sidebar-bg']
                },
                dimensions: [160, 600]
            },
            vertical: {
                applyClasses: {
                    parent: ['block-bg']
                },
                dimensions: [160, 600]
            },
            horizontalBTF: {
                applyClasses: {
                    parent: ['horizontal-bg']
                },
                dimensions: [728, 90]
            },
            skin: {
                dimensions: [1, 1]
            },
            fixedFooter: {
                dimensions: [1, 1]
            }
        },

        /**
         * @property {object} providers - The site-specific config for each provider.
         */
        providers: {
            /**
             * @property {object} MonkeyBroker - Settings for MonkeyBroker support.
             */
            MonkeyBroker: {
                /**
                 * @property {object} site - The MonkeyBroker site settings object. This is passed directly to MonkeyBroker.
                 * @property {number} site.id - Your site's ID in MonkeyBroker's control panel.
                 * @property {string} site.customDomainName - The domain ads are served from. Aside from special cases this should be 'mb.zam.com'.
                 * @property {number} [refresh] - Seconds on a page until ads refresh. Do not define this unless requested by Jerek/Amanda.
                 * @property {boolean} [reporting] - Whether you have prepared styling support for individual ad reporting on your site. Undefined is equivalent to false.
                 * @property {string} [reportText] - Customize the individual ad report link text for your site. Intended primarily for localization. If undefined and reporting is enabled the ad script will use "Report ad".
                 */
                site: {
                    id: 1663,
                    customDomainName: 'mb.zam.com'
                },
                reporting: true
            },

            /**
             * @property {object} OpenX - Settings for OpenX support.
             */
            OpenX: {
                /**
                 * Since the Wowhead code supports multiple sites the this.getProviderConfig function defined below specifies getting this property on a per-site basis.
                 *
                 * @property {object} adUnitIds - The OpenX unit IDs your site uses for each unit defined in config.units.
                 * @property {object} adUnitIds.wowhead|hearthhead|thottbot - The site-specific unit IDs for this site.
                 * @property {number|boolean} adUnitIds.wowhead|hearthhead|thottbot.leaderboard|leaderboardBTF|medrec|medrecBTF|skyscraper|skyscraperBTF|skin|fixedFooter - The unit ID number, or false to indicate to not print this unit for this site.
                 */
                adUnitIds: {
                    leaderboard:    436614, // 728x90
                    leaderboardBTF: false,  // 728x90 BTF
                    medrec:         436615, // 300x250
                    medrecBTF:      false,  // 300x250 BTF
                    skyscraper:     436613, // 160x600
                    skin:           436616,
                    fixedFooter:    false
                }
            }
        }
    };

    /**
     * Get configuration values
     *
     * @param {string} key
     * @returns {*}
     */
    this.getConfig = function(key) {
        return config[key];
    };

    /**
     * Get provider-specific configuration values
     *
     * @param {string} key
     * @param {string} [provider]
     * @returns {*}
     */
    this.getProviderConfig = function(key, provider) {
        if (!provider) {
            provider = config.provider;
        }

        if (config.providers[provider]) {
            return config.providers[provider][key];
        }

        return undefined;
    };

    /**
     * Check whether the user should see ads, according to any preferred site methods.
     * Result is checked for truthiness.
     * If falsey no ads are displayed.
     *
     * @returns {boolean}
     */
    this.checkUserShouldSeeAds = function() {
        return (typeof ads_show != 'undefined' && ads_show);
    };

    /**
     * Check whether the specified ad type in the specified target should be created, according to any preferred site methods.
     * Result is checked for truthiness.
     * If falsey creating the unit will be cancelled.
     *
     * @param {string} type The unit type (leaderboard, medrec, etc).
     * @param {string|HTMLElement} target Where the add will be placed.
     * @param {boolean} [excludeFromCurrentUnits] Whether the ad is a unique case which will not be kept track of for normal hide/reveal functions.
     * @param {boolean} [allowTargetAsId] Whether the target passed in can remain an ID, to be fully processed later.
     * @returns {boolean}
    */
    this.checkCreateConditions = function(type, target, excludeFromCurrentUnits, allowTargetAsId) {
        if (type == 'skin' && window.outerWidth < 1220) {
            return false;
        }

        return true;
    };

    /**
     * Get the name of the current channel, according to any preferred site methods. E.g. "Item List".
     * This can be calculated here (though it's recommended that you then set the function to always return whatever
     *     the result is), or simply return a global variable printed by the application on the page.
     *
     * @returns {string}
     */
    this.getChannelName = function() {
        if (typeof ads_channel == 'string') {
            return ads_channel;
        }

        return 'Other';
    };

    /**
     * Site-specific changes once a skin is created.
     *
     * @param {object} skin The skin's data object from the provider.
     * @param {HTMLElement} skinParent The element the skin elements were added to.
     */
    this.postSkinSetup = function(skin, skinParent) {
        if (!skin.usePageLeaderboard) {
            $('.skinned #mini-features, .skinned .header-bg').remove();
        }

        if (skin.id) {
            var site = ZAM.getSite();
            logView('ZAM Skins - ' + site, skin.id);
            $('.skin-link-1, .skin-link-2, .skin-link-3').click(function() {
                logClick('ZAM Skins - ' + site, skin.id);
            });
        }
    };

    /**
     * If this function is defined, it's executed whenever a slot is being filled. It is passed information on the ad unit.
     * @param {object} unit The internal ad unit, including the target element.
     */
    this.onUpdateSlotSize = function(unit) {
        // Set an attribute on the body saying what size leaderboard-borne unit we're using, so area styles can be appropriately updated.
        if (unit.w == 728 || unit.w == 970) {
            // Uncomment to test large units
            // unit.w = 970;
            // unit.h = 250;
            // unit.el.parentNode.className = unit.el.parentNode.className.replace(/728x90/, '970x250');
            document.body.setAttribute('data-contains-block', unit.w + 'x' + unit.h);
        } else {
            heightCheck();
        }
    };

    /**
     * Get the name of the current user.
     * If the user is not logged in return null.
     * Used in MonkeyBroker analytics when analyzing bad ad feedback.
     *
     * @returns {string|null}
     */
    this.getUserName = function() {
        if (typeof username == 'string') {
            return username;
        }

        return null;
    };
};

/**
 * Initialize the ads once Ads.site is ready. Any DOM readiness requirements will be handled internally as needed.
 */
//Ads.init();

ZUL.setOptions({
    bodyIsRelative: false,
    states: {
        loggedOut: {
            pre: [
                {
                    type: 'link',
                    name: 'login',
                    innerHTML: 'Login',
                    href: 'https://secure.zam.com/login.html?action=login'
                },
                {
                    type: 'link',
                    name: 'register',
                    innerHTML: 'Create Account',
                    href: 'https://secure.zam.com/login.html?action=new'
                },
                {
                    type: 'link',
                    name: 'premium',
                    innerHTML: 'Premium Services',
                    href: 'http://legacy.zam.com/premium.html'
                }
            ]
        },
        loggedIn: {
            pre: [
                {
                    type: 'custom',
                    custom: function(target, settings) {
                        if (!settings.user) {
                            return;
                        }

                        var userMenu = document.createElement('div');
                        userMenu.id = userMenu.className = 'zul-bar-user-item-menu';

                        var userLink = document.createElement('a');
                        userLink.innerHTML = settings.user.name;
                        userLink.href = '/users/' + settings.user.name;
                        userMenu.appendChild(userLink);

                        if (settings.user.options) {
                            var userOptions = document.createElement('ul');
                            for (var i = 0, option; option = settings.user.options[i]; i++) {
                                var li = document.createElement('li');

                                if (!option.href) {
                                    li.className = 'zul-bar-user-item-menu-heading';
                                    li.innerHTML = option.innerHTML;
                                    userOptions.appendChild(li);
                                    continue;
                                }

                                var a = document.createElement('a');
                                a.href = option.href;
                                a.innerHTML = option.innerHTML;

                                li.appendChild(a);
                                userOptions.appendChild(li);
                            }
                            userMenu.appendChild(userOptions);
                        }

                        target.appendChild(userMenu);
                    }
                }
            ]
        }
    }
});

