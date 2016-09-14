if (typeof $AkzToolTip=="undefined") {
  var $AkzToolTip = new function () {
    var head = document.getElementsByTagName("head")[0];
    var body = document.getElementsByTagName("body")[0];
    var tt,currentId;
    var items=[];
    var itemDiv;

    function createAkzElement(type,p) {
      // type = html element type (ie: link, a, p)
      // p = array of attributes for type
      var newelement = document.createElement(type);
      if (p) {
        createAkzObject(newelement, p);
      }
      return newelement;
    }

    function addAkzElement(p,element) {
      return p.appendChild(element);
    }

    function addAkzEvent(z,y,x) {
      if (window.attachEvent) {
        z.attachEvent("on"+y,x);
      } else {
        z.addEventListener(y,x,false);
      }
    }

    function createAkzObject(ele,s) {
      for (var p in s) {
        if (typeof s[p]=="object") {
          if (!ele[p]) {
            ele[p] = {};
          }
          createAkzObject(ele[p],s[p]);
        } else {
          ele[p] = s[p];
        }
      }
    }

    function $E(e) {
      if (!e) {
        e = event;
      }
      if (!e.button) {
        e._button = e.which ? e.which : e.button;
        e._target = e.target ? e.target : e.srcElement;
      }
      return e;
    }

    function onMouseOver(e) {
      e = $E(e);
      var t=e._target;

      if (t.nodeName!="A") {
        if (t.parentNode && t.parentNode.nodeName == "A") {
          t = t.parentNode;
        } else if (t.parentNode.parentNode && t.parentNode.parentNode.nodeName == "A") {
          t = t.parentNode.parentNode;
        } else {
          return;
        }
      }

      if ( !t.href.length || t.href.match(/post=1/i) ) {
        return;
      }

      var m = [];
      if (t.className) m['class'] = t.className;
      var site;
      var v;
      var valid = 0;
      var thref = t.href.replace('%3A',':');

      var isZAM = 0;
      if (document.URL.match(/^http:\/\/.*\.(allakhazam\.com|zam\.com)\/.*/)) {
        isZAM = 1;
      }
      if (v = thref.match(/^http:\/\/([^\.]+)(\..+)?\.(allakhazam\.com|zam\.com)\/(.+)\/([^\?]+)\?(.*)$/i)) {
        m['host'] = v[1];
        m['box'] = v[2];
        m['domain'] = v[3];
        m['path'] = v[4];
        m['file'] = v[5];
        m['qs'] = v[6];

        if (m['host'] == 'eq2' && m['file'] == 'item.html') {
          if (z = m['qs'].match(/eq2item=([A-Za-z0-9]{32})/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'ffxiv' && m['file'] == 'item.html' ) {
          if (z = m['qs'].match(/ffxivitem=(\d+)/)) {
            if (! m['qs'].match(/setcookie=1/)) {
              m['id'] = z[1];
              valid = 1;
            }
          }
        } else if (m['host'] == 'ffxiv' && m['file'] == 'guildleve.html') {
          if (z = m['qs'].match(/ffxivguildleve=(\d+)/)) {
            if (! m['qs'].match(/setcookie=1/)) {
              m['id'] = z[1];
              valid = 1;
            }
          }
        } else if (m['host'] == 'ffxiv' && m['file'] == 'localleve.html') {
          if (z = m['qs'].match(/ffxivlocalleve=(\d+)/)) {
            if (! m['qs'].match(/setcookie=1/)) {
              m['id'] = z[1];
              valid = 1;
            }
          }
        } else if (m['host'] == 'ffxiv' && m['file'] == 'ability.html') {
          if (z = m['qs'].match(/ffxivability=(\d+)/)) {
            if (! m['qs'].match(/setcookie=1/)) {
              m['id'] = z[1];
              valid = 1;
            }
          }
        } else if (m['host'] == 'war' && (m['file'] == 'item.html' || m['file'] == 'quest.html')) {
          var re = new RegExp('war' + m['file'].replace('.html','') + '=(\\d+)');
          if (z = m['qs'].match(re)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'everquest' && m['file'] == 'item.html') {
          if (z = m['qs'].match(/item=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
            if (m['qs'].match(/source=lucy/)) {
              m['source'] = 'lucy';
            }
          }
        } else if (m['host'] == 'ffxi' && m['file'] == 'item.html') {
          if (z = m['qs'].match(/fitem=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'lotro' && m['file'] == 'item.html') {
          if (z = m['qs'].match(/lotritem=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'achievement.html') {
          if (z = m['qs'].match(/riftachievement=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          } else if (z = m['qs'].match(/riftach=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'collection.html') {
          if (z = m['qs'].match(/riftcollection=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'recipe.html') {
          if (z = m['qs'].match(/riftrecipe=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'item.html') {
          if (z = m['qs'].match(/riftitem=([A-Za-z0-9]+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'quest.html') {
          if (z = m['qs'].match(/riftquest=([A-Za-z0-9]+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'npc.html') {
          if (z = m['qs'].match(/riftnpc=([A-Za-z0-9]+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'stc.html') {
          if (z = m['qs'].match(/t=([A-Za-z0-9_\.]+)/)) {
            m['id'] = z[1];
            valid = 1;
          } else if (z = m['qs'].match(/stc=([A-Za-z0-9_\.]+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        } else if (m['host'] == 'rift' && m['file'] == 'ability.html') {
          if (z = m['qs'].match(/riftability=(\d+)/)) {
            m['id'] = z[1];
            valid = 1;
          }
        }
      } else if (v = thref.match(/^http:\/\/([^\.]+)(\..+)?\.(allakhazam\.com|zam\.com)\/(.+)\/(.+)\/([A-Za-z0-9]+)\/(.*)$/i)) {
        m['host'] = v[1];
        m['box'] = v[2];
        m['domain'] = v[3];
        m['path'] = v[4];
        m['file'] = v[5];
        m['id'] = v[6];
        if (m['host'] == 'rift' && (m['file'] == 'item' || m['file'] == 'achievement' || m['file'] == 'ability' || m['file'] == 'recipe' || m['file'] == 'collection' || m['file'] == 'stc' || m['file'] == 'quest' || m['file'] == 'npc')) {
          m['qs'] = m['host'] + m['file'] + '=' + m['id'];
          valid = 1;
        }
      } else if (v = thref.match(/^http:\/\/([^\.]+)(\..+)?\.(allakhazam\.com|zam\.com)\/wiki\/([^\:]+)\:([^\/\?]+)/i)) {
        m['host'] = v[1];
        m['box'] = v[2];
        m['domain'] = v[3];
        var cat = v[4];
        valid = 1;

        if (cat.match(/eq2[_ ]item/i)) {
          m['qs'] = 'eq2itemname=' + v[5];
          m['name'] = 'eq2itemname' + v[5];
        } else if (cat.match(/ffxiv[_ ]item/i)) {
          m['qs'] = 'ffxivitem=' + v[5];
          m['name'] = 'ffxivitem' + v[5];
          m['path'] = 'en';
        } else if (cat.match(/ffxiv[_ ]ability/i)) {
          m['qs'] = 'ffxivability=' + v[5];
          m['name'] = 'ffxivability' + v[5];
          m['path'] = 'en';
        } else if (cat.match(/ffxiv[_ ]local[_ ]leve/i)) {
          m['qs'] = 'ffxivlocalleve=' + v[5];
          m['name'] = 'ffxivlocalleve' + v[5];
          m['path'] = 'en';
        } else if (cat.match(/ffxiv[_ ]guildleve/i)) {
          m['qs'] = 'ffxivguildleve=' + v[5];
          m['name'] = 'ffxivguildleve' + v[5];
          m['path'] = 'en';
        } else if (cat.match(/ffxi[_ ]item/i)) {
          m['site'] = 'ffxi';
          m['name'] = v[5];
          m['type'] = 'item';
        } else if (cat.match(/war[_ ]item/i)) {
          m['site'] = 'war';
          m['name'] = v[5];
          m['type'] = 'item';
        } else if (cat.match(/rift[_ ]ability/i)) {
          m['qs'] = 'riftability=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]achievement/i)) {
          m['qs'] = 'riftachievement=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]item/i)) {
          m['qs'] = 'wiki=1&riftitem=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]quest/i)) {
          m['qs'] = 'wiki=1&riftquest=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]mob/i)) {
          m['qs'] = 'wiki=1&riftnpc=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]recipe/i)) {
          m['qs'] = 'riftrecipe=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else if (cat.match(/rift[_ ]collection/i)) {
          m['qs'] = 'riftcollection=' + v[5];
          m['name'] = v[5];
          m['path'] = 'en';
        } else {
          valid = 0;
        }
      }

      if (v && valid == 1) {
        t.title = '';  //remove the title attribute from items in the forums

        if (!t.onmouseover) {
          t.onmousemove=onMouseMove;
          t.onmouseout=onMouseOut;
        }

        displayToolTip(m,isZAM);
      }
    }

    function onMouseMove(e) {
      e=$E(e);
      showAtCursor(e);
    }

    function onMouseOut(e) {
      tt = null;
      itemDiv.style.display='none';
    }

    function displayToolTip(m,isZAM) {
      tt = 1;

      if (m['id']) {
        currentId = m['id'];
      } else {
        currentId = decodeURIComponent(m['name']);
      }

      var key = m['site'] + currentId + m['locale'] + m['source'] + m['type'];

      if (typeof items[key]=="object") { //If it's already in the items array
        showToolTip(items[key].tooltip) ;
      } else {
        if (!items[key]) {
          showLoading();
          requestToolTip(m,isZAM);
        } else {
          showLoading();
        }
      }
    }

    function showToolTip(itemstr) {
      itemDiv.style.display="block";
      itemDiv.innerHTML = itemstr;
    }

    function requestToolTip(m,isZAM) {
      var url = '';
      if (m['host']=='war') {
        if (m['box']) m['host'] += m['box'];
        if (m['file']=='item.html') {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/iover.pl?id=" + m['id'];
        } else if (m['file']=='quest.html') {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/qover.pl?id=" + m['id'];
        } else if (m['type']=='item' && m['name']) {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/iover.pl?name=" + m['name'];
        }
      } else if (m['host']=='lotro') {
        if (m['box']) m['host'] += m['box'];
        if (m['file']=='item.html') {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/ihtml.pl?tooltip=1&item=" + m['id'];
        }
      } else if (m['host']=='everquest') {
        if (m['box']) m['host'] += m['box'];
        if (m['file']=='item.html') {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/ihtml.pl?tooltip=1&item=" + m['id'];
          if (m['source']) {
            url += "&source=" + m['source'];
          }
        }
      } else if (m['host']=='ffxi') {
        if (m['box']) m['host'] += m['box'];
        if (m['file']=='item.html') {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/fitem.pl?tooltip=1&id=" + m['id'];
        } else if (m['type']=='item' && m['name']) {
          url = "http://" + m['host'] + "." + m['domain'] + "/cluster/fitem.pl?tooltip=1&name=" + m['name'];
        }
      } else {
        if (m['box']) m['host'] += m['box'];
        if (m['path'] && (m['path'] == 'en' || m['path'] == 'de' || m['path'] == 'fr' || m['path'] == 'ja')) {
          url = "http://" + m['host'] + "." + m['domain'] + "/" + m['path'] + "/tooltip.html?" + m['qs'];
        } else {
          url = "http://" + m['host'] + "." + m['domain'] + "/db/tooltip.html?" + m['qs'];
        }
      }
      if (isZAM == 0) {
        url += '&remote=1';
      }
      if (!m['class'] || m['class'].search('icon-link') < 0) {
        url += '&icon=1';
      }
      getToolTip(url);
    }

    function getToolTip(url) {
      addAkzElement(head,createAkzElement("script",{type:"text/javascript",src:url}));
    }

    function showLoading() {
      itemDiv.innerHTML = "";
      itemDiv.style.display = 'block';
    }

    function showAtCursor(e) {
      var obj = itemDiv;
      var maxX;
      var maxY;
      obj.style.position = "absolute";
      obj.style.display = "block";
      if (document.all && !window.opera) {
        if (document.documentElement && typeof document.documentElement.scrollTop != undefined) {
          maxX = document.documentElement.clientWidth + document.documentElement.scrollLeft;
          maxY = document.documentElement.clientHeight + document.documentElement.scrollTop;
          y = event.clientY + document.documentElement.scrollTop;
          x = event.clientX + document.documentElement.scrollLeft;
        } else {
          y = event.clientY + document.body.scrollTop;
          x = event.clientX + document.body.scrollLeft;
        }
      } else {
        if(document.body.scrollTop) {
          maxX = window.innerWidth + document.body.scrollLeft;
          maxY = window.innerHeight + document.body.scrollTop;
        } else {
          maxX = window.innerWidth + document.documentElement.scrollLeft;
          maxY = window.innerHeight + document.documentElement.scrollTop;
        }
        y = e.pageY;
        x = e.pageX;
      }

      var divW = parseInt(obj.offsetWidth);
      var divH = parseInt(obj.offsetHeight);
      divW = divW ? divW : 400;
      divH = divH ? divH : 100;

      if (maxX && maxY) {
        while (x + divW > (maxX - 10) && x > 0) {
          x = x - (divW + 10);
        }

        while (y + divH > (maxY - 25) && y > 0) {
          y = y - 1;
        }
      }

      if (document.body.style.marginTop) y = y - parseInt(document.body.style.marginTop.replace('px',''));

      obj.style.left = x + 15 +"px";
      obj.style.top = y + 15 +"px";
    }

    this.registerItem=function(obj) {
      var site = obj.site;
      var id;

      if (obj.key) {
        id = obj.key;
      } else if (obj.id) {
        id = obj.id;
      } else {
        id = obj.name;
        id = id.replace(/\+/g,'%2B');
      }

      var locale = typeof obj.locale != 'undefined' ? obj.locale : 'enUS';
      var source = typeof obj.source != 'undefined' ? obj.source : 'live';
      var key = site + id + locale + source;
      items[key] = obj;
      if (tt == 1 && id == currentId) {
        showToolTip(items[key].tooltip);
      }
    }

    function onPageShow(e) {
      if (e.persisted) {
        tt = null;
        itemDiv.style.display='none';
      }
    }

    function init() {
      if (!document.getElementById('tmpItemFrm')) {
        addAkzElement(body, createAkzElement("div",{id:'tmpItemFrm'}));
        document.getElementById('tmpItemFrm').style.display = 'none';
      }

      itemDiv = document.getElementById('tmpItemFrm');
      addAkzElement(head, createAkzElement("link",{type:"text/css",href:"http://common.zam.com/shared/tooltip.css?1",rel:"stylesheet"}));
      addAkzEvent(document,"mouseover",onMouseOver);
      addAkzEvent(window,"pageshow",onPageShow);
    }

    init();
  }
}