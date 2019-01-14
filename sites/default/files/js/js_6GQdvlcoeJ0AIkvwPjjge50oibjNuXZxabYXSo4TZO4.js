(function ($) {

/**
 * A progressbar object. Initialized with the given id. Must be inserted into
 * the DOM afterwards through progressBar.element.
 *
 * method is the function which will perform the HTTP request to get the
 * progress bar state. Either "GET" or "POST".
 *
 * e.g. pb = new progressBar('myProgressBar');
 *      some_element.appendChild(pb.element);
 */
Drupal.progressBar = function (id, updateCallback, method, errorCallback) {
  var pb = this;
  this.id = id;
  this.method = method || 'GET';
  this.updateCallback = updateCallback;
  this.errorCallback = errorCallback;

  // The WAI-ARIA setting aria-live="polite" will announce changes after users
  // have completed their current activity and not interrupt the screen reader.
  this.element = $('<div class="progress" aria-live="polite"></div>').attr('id', id);
  this.element.html('<div class="bar"><div class="filled"></div></div>' +
                    '<div class="percentage"></div>' +
                    '<div class="message">&nbsp;</div>');
};

/**
 * Set the percentage and status message for the progressbar.
 */
Drupal.progressBar.prototype.setProgress = function (percentage, message) {
  if (percentage >= 0 && percentage <= 100) {
    $('div.filled', this.element).css('width', percentage + '%');
    $('div.percentage', this.element).html(percentage + '%');
  }
  $('div.message', this.element).html(message);
  if (this.updateCallback) {
    this.updateCallback(percentage, message, this);
  }
};

/**
 * Start monitoring progress via Ajax.
 */
Drupal.progressBar.prototype.startMonitoring = function (uri, delay) {
  this.delay = delay;
  this.uri = uri;
  this.sendPing();
};

/**
 * Stop monitoring progress via Ajax.
 */
Drupal.progressBar.prototype.stopMonitoring = function () {
  clearTimeout(this.timer);
  // This allows monitoring to be stopped from within the callback.
  this.uri = null;
};

/**
 * Request progress data from server.
 */
Drupal.progressBar.prototype.sendPing = function () {
  if (this.timer) {
    clearTimeout(this.timer);
  }
  if (this.uri) {
    var pb = this;
    // When doing a post request, you need non-null data. Otherwise a
    // HTTP 411 or HTTP 406 (with Apache mod_security) error may result.
    $.ajax({
      type: this.method,
      url: this.uri,
      data: '',
      dataType: 'json',
      success: function (progress) {
        // Display errors.
        if (progress.status == 0) {
          pb.displayError(progress.data);
          return;
        }
        // Update display.
        pb.setProgress(progress.percentage, progress.message);
        // Schedule next timer.
        pb.timer = setTimeout(function () { pb.sendPing(); }, pb.delay);
      },
      error: function (xmlhttp) {
        pb.displayError(Drupal.ajaxError(xmlhttp, pb.uri));
      }
    });
  }
};

/**
 * Display errors on the page.
 */
Drupal.progressBar.prototype.displayError = function (string) {
  var error = $('<div class="messages error"></div>').html(string);
  $(this.element).before(error).hide();

  if (this.errorCallback) {
    this.errorCallback(this);
  }
};

})(jQuery);
;
(function ($) {

  Drupal.behaviors.autologout = {
    attach: function(context, settings) {

      if (context != document) {
        return;
      }

      var paddingTimer;
      var t;
      var theDialog;
      var localSettings;

      // Activity is a boolean used to detect a user has
      // interacted with the page.
      var activity;

      // Timer to keep track of activity resets.
      var activityResetTimer;

      // Prevent settings being overriden by ajax callbacks by cloning the settings.
      localSettings = jQuery.extend(true, {}, settings.autologout);

      if (localSettings.refresh_only) {
        // On pages that cannot be logged out of don't start the logout countdown.
        t = setTimeout(keepAlive, localSettings.timeout);
      }
      else {
        // Set no activity to start with.
        activity = false;

        // Bind formUpdated events to preventAutoLogout event.
        $('body').bind('formUpdated', function(event) {
          $(event.target).trigger('preventAutologout');
        });

        // Support for CKEditor.
        if (typeof CKEDITOR !== 'undefined') {
          CKEDITOR.on('instanceCreated', function(e) {
            e.editor.on('contentDom', function() {
              e.editor.document.on('keyup', function(event) {
                // Keyup event in ckeditor should prevent autologout.
                $(e.editor.element.$).trigger('preventAutologout');
              });
            });
          });
        }

        $('body').bind('preventAutologout', function(event) {
          // When the preventAutologout event fires
          // we set activity to true.
          activity = true;

          // Clear timer if one exists.
          clearTimeout(activityResetTimer);

          // Set a timer that goes off and resets this activity indicator
          // after a minute, otherwise sessions never timeout.
          activityResetTimer = setTimeout(function () {
            activity = false;
          }, 60000);
        });

        // On pages where the user can be logged out, set the timer to popup
        // and log them out.
        t = setTimeout(init, localSettings.timeout);
      }

      function init() {
        var noDialog = Drupal.settings.autologout.no_dialog;

        if (activity) {
          // The user has been active on the page.
          activity = false;
          refresh();
        }
        else {

          // The user has not been active, ask them if they want to stay logged in
          // and start the logout timer.
          paddingTimer = setTimeout(confirmLogout, localSettings.timeout_padding);

          // While the countdown timer is going, lookup the remaining time. If there
          // is more time remaining (i.e. a user is navigating in another tab), then
          // reset the timer for opening the dialog.
          Drupal.ajax['autologout.getTimeLeft'].autologoutGetTimeLeft(function(time) {
              if (time > 0) {
                clearTimeout(paddingTimer);
                t = setTimeout(init, time);
              }
              else {
                // Logout user right away without displaying a confirmation dialog.
                if (noDialog) {
                  logout();
                  return;
                }
                theDialog = dialog();
              }
          });
        }
      }

      function dialog() {
        var buttons = {};
        buttons[Drupal.t('Yes')] = function() {
          $(this).dialog("destroy");
          clearTimeout(paddingTimer);
          refresh();
        };

        buttons[Drupal.t('No')] = function() {
          $(this).dialog("destroy");
          logout();
        };

        return $('<div id="autologout-confirm"> ' +  localSettings.message + '</div>').dialog({
          modal: true,
               closeOnEscape: false,
               width: "auto",
               dialogClass: 'autologout-dialog',
               title: localSettings.title,
               buttons: buttons,
               close: function(event, ui) {
                 logout();
               }
        });
      }

      // A user could have used the reset button on the tab/window they're actively
      // using, so we need to double check before actually logging out.
      function confirmLogout() {
        $(theDialog).dialog('destroy');

        Drupal.ajax['autologout.getTimeLeft'].autologoutGetTimeLeft(function(time) {
          if (time > 0) {
            t = setTimeout(init, time);
          }
          else {
            logout();
          }
        });
      }

      function logout() {
        if (localSettings.use_alt_logout_method) {
          window.location = Drupal.settings.basePath + "?q=autologout_ahah_logout";
        }
        else {
          $.ajax({
            url: Drupal.settings.basePath + "?q=autologout_ahah_logout",
            type: "POST",
            success: function() {
              window.location = localSettings.redirect_url;
            },
            error: function(XMLHttpRequest, textStatus) {
              if (XMLHttpRequest.status == 403 || XMLHttpRequest.status == 404) {
                window.location = localSettings.redirect_url;
              }
            }
          });
        }
      }

      /**
       * Use the Drupal ajax library to handle get time remaining events
       * because if using the JS Timer, the return will update it.
       *
       * @param function callback(time)
       *   The function to run when ajax is successful. The time parameter
       *   is the time remaining for the current user in ms.
       */
      Drupal.ajax.prototype.autologoutGetTimeLeft = function(callback) {
        var ajax = this;

        if (ajax.ajaxing) {
          return false;
        }

        ajax.options.success = function (response, status) {
          if (typeof response == 'string') {
            response = $.parseJSON(response);
          }

          if (typeof response[1].command === 'string' && response[1].command == 'alert') {
            // In the event of an error, we can assume
            // the user has been logged out.
            window.location = localSettings.redirect_url;
          }

          callback(response[2].settings.time);

          // Let Drupal.ajax handle the JSON response.
          return ajax.success(response, status);
        };

        try {
          $.ajax(ajax.options);
        }
        catch (e) {
          ajax.ajaxing = false;
        }
      };

      Drupal.ajax['autologout.getTimeLeft'] = new Drupal.ajax(null, $(document.body), {
        url: Drupal.settings.basePath  + '?q=autologout_ajax_get_time_left',
        event: 'autologout.getTimeLeft',
        error: function(XMLHttpRequest, textStatus) {
          // Disable error reporting to the screen.
        }
      });

      /**
       * Use the Drupal ajax library to handle refresh events
       * because if using the JS Timer, the return will update
       * it.
       *
       * @param function timerFunction
       *   The function to tell the timer to run after its been
       *   restarted.
       */
      Drupal.ajax.prototype.autologoutRefresh = function(timerfunction) {
        var ajax = this;

        if (ajax.ajaxing) {
          return false;
        }

        ajax.options.success = function (response, status) {
          if (typeof response == 'string') {
            response = $.parseJSON(response);
          }

          if (typeof response[1].command === 'string' && response[1].command == 'alert') {
            // In the event of an error, we can assume
            // the user has been logged out.
            window.location = localSettings.redirect_url;
          }

          t = setTimeout(timerfunction, localSettings.timeout);
          activity = false;

          // Let Drupal.ajax handle the JSON response.
          return ajax.success(response, status);
        };

        try {
          $.ajax(ajax.options);
        }
        catch (e) {
          ajax.ajaxing = false;
        }
      };

      Drupal.ajax['autologout.refresh'] = new Drupal.ajax(null, $(document.body), {
        url: Drupal.settings.basePath  + '?q=autologout_ahah_set_last',
        event: 'autologout.refresh',
        error: function(XMLHttpRequest, textStatus) {
          // Disable error reporting to the screen.
        }
      });

      function keepAlive() {
        Drupal.ajax['autologout.refresh'].autologoutRefresh(keepAlive);
      }

      function refresh() {
        Drupal.ajax['autologout.refresh'].autologoutRefresh(init);
      }

      // Check if the page was loaded via a back button click.
      var $dirty_bit = $('#autologout-cache-check-bit');
      if ($dirty_bit.length !== 0) {

        if ($dirty_bit.val() == '1') {
          // Page was loaded via a back button click, we should
          // refresh the timer.
          refresh();
        }

        $dirty_bit.val('1');
      }
    }
  };
})(jQuery);
;
/**
 * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch and iPad, should also work with Android mobile phones (not tested yet!)
 * Common usage: wipe images (left and right to show the previous or next image)
 * 
 * @author Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
 * @version 1.1.1 (9th December 2010) - fix bug (older IE's had problems)
 * @version 1.1 (1st September 2010) - support wipe up and wipe down
 * @version 1.0 (15th July 2010)
 */
(function ($) {
    $.fn.touchwipe = function (settings) {
        var config = {
            min_move_x: 20,
            min_move_y: 20,
            wipeLeft: function () {},
            wipeRight: function () {},
            wipeUp: function () {},
            wipeDown: function () {},
            preventDefaultEvents: true
        };
        if (settings) $.extend(config, settings);
        this.each(function () {
            var startX;
            var startY;
            var isMoving = false;

            function cancelTouch() {
                this.removeEventListener('touchmove', onTouchMove);
                startX = null;
                isMoving = false
            }
            function onTouchMove(e) {
                if (config.preventDefaultEvents) {
                    e.preventDefault()
                }
                if (isMoving) {
                    var x = e.touches[0].pageX;
                    var y = e.touches[0].pageY;
                    var dx = startX - x;
                    var dy = startY - y;
                    if (Math.abs(dx) >= config.min_move_x) {
                        cancelTouch();
                        if (dx > 0) {
                            config.wipeLeft()
                        } else {
                            config.wipeRight()
                        }
                    } else if (Math.abs(dy) >= config.min_move_y) {
                        cancelTouch();
                        if (dy > 0) {
                            config.wipeDown()
                        } else {
                            config.wipeUp()
                        }
                    }
                }
            }
            function onTouchStart(e) {
                if (e.touches.length == 1) {
                    startX = e.touches[0].pageX;
                    startY = e.touches[0].pageY;
                    isMoving = true;
                    this.addEventListener('touchmove', onTouchMove, false)
                }
            }
            if ('ontouchstart' in document.documentElement) {
                this.addEventListener('touchstart', onTouchStart, false)
            }
        });
        return this
    }
})(jQuery);;
/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransforms3d-csstransitions-touch-shiv-cssclasses-prefixed-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function z(a){j.cssText=a}function A(a,b){return z(m.join(a+";")+(b||""))}function B(a,b){return typeof a===b}function C(a,b){return!!~(""+a).indexOf(b)}function D(a,b){for(var d in a){var e=a[d];if(!C(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function E(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:B(f,"function")?f.bind(d||b):f}return!1}function F(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return B(b,"string")||B(b,"undefined")?D(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),E(e,b,c))}var d="2.6.2",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x={}.hasOwnProperty,y;!B(x,"undefined")&&!B(x.call,"undefined")?y=function(a,b){return x.call(a,b)}:y=function(a,b){return b in a&&B(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:w(["@media (",m.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},q.csstransforms3d=function(){var a=!!F("perspective");return a&&"webkitPerspective"in g.style&&w("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},q.csstransitions=function(){return F("transition")};for(var G in q)y(q,G)&&(v=G.toLowerCase(),e[v]=q[G](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)y(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},z(""),i=k=null,function(a,b){function k(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function l(){var a=r.elements;return typeof a=="string"?a.split(" "):a}function m(a){var b=i[a[g]];return b||(b={},h++,a[g]=h,i[h]=b),b}function n(a,c,f){c||(c=b);if(j)return c.createElement(a);f||(f=m(c));var g;return f.cache[a]?g=f.cache[a].cloneNode():e.test(a)?g=(f.cache[a]=f.createElem(a)).cloneNode():g=f.createElem(a),g.canHaveChildren&&!d.test(a)?f.frag.appendChild(g):g}function o(a,c){a||(a=b);if(j)return a.createDocumentFragment();c=c||m(a);var d=c.frag.cloneNode(),e=0,f=l(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function p(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return r.shivMethods?n(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+l().join().replace(/\w+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(r,b.frag)}function q(a){a||(a=b);var c=m(a);return r.shivCSS&&!f&&!c.hasCSS&&(c.hasCSS=!!k(a,"article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}mark{background:#FF0;color:#000}")),j||p(a,c),a}var c=a.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,e=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f,g="_html5shiv",h=0,i={},j;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",f="hidden"in a,j=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){f=!0,j=!0}})();var r={elements:c.elements||"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video",shivCSS:c.shivCSS!==!1,supportsUnknownElements:j,shivMethods:c.shivMethods!==!1,type:"default",shivDocument:q,createElement:n,createDocumentFragment:o};a.html5=r,q(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.testProp=function(a){return D([a])},e.testAllProps=F,e.testStyles=w,e.prefixed=function(a,b,c){return b?F(a,b,c):F(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};;
/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1
 * to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
*/

// t: current time, b: begInnIng value, c: change In value, d: duration
jQuery.easing["jswing"]=jQuery.easing["swing"];jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(a,b,c,d,e){return jQuery.easing[jQuery.easing.def](a,b,c,d,e)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e)==1)return c+d;if(!g)g=e*.3;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158;var g=0;var h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;if(!g)g=e*.3*1.5;if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*(b/=e)*b*((f+1)*b-f)+c},easeOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;return d*((b=b/e-1)*b*((f+1)*b+f)+1)+c},easeInOutBack:function(a,b,c,d,e,f){if(f==undefined)f=1.70158;if((b/=e/2)<1)return d/2*b*b*(((f*=1.525)+1)*b-f)+c;return d/2*((b-=2)*b*(((f*=1.525)+1)*b+f)+2)+c},easeInBounce:function(a,b,c,d,e){return d-jQuery.easing.easeOutBounce(a,e-b,0,d,e)+c},easeOutBounce:function(a,b,c,d,e){if((b/=e)<1/2.75){return d*7.5625*b*b+c}else if(b<2/2.75){return d*(7.5625*(b-=1.5/2.75)*b+.75)+c}else if(b<2.5/2.75){return d*(7.5625*(b-=2.25/2.75)*b+.9375)+c}else{return d*(7.5625*(b-=2.625/2.75)*b+.984375)+c}},easeInOutBounce:function(a,b,c,d,e){if(b<e/2)return jQuery.easing.easeInBounce(a,b*2,0,d,e)*.5+c;return jQuery.easing.easeOutBounce(a,b*2-e,0,d,e)*.5+d*.5+c}})
/*
 *
 * TERMS OF USE - EASING EQUATIONS
 * 
 * Open source under the BSD License. 
 * 
 * Copyright © 2001 Robert Penner
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * Redistributions of source code must retain the above copyright notice, this list of 
 * conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this list 
 * of conditions and the following disclaimer in the documentation and/or other materials 
 * provided with the distribution.
 * 
 * Neither the name of the author nor the names of contributors may be used to endorse 
 * or promote products derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 *  EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 *  GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED 
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE. 
 *
 */;
!function(t){effectsIn=["bounceIn","bounceInDown","bounceInUp","bounceInLeft","bounceInRight","fadeIn","fadeInUp","fadeInDown","fadeInLeft","fadeInRight","fadeInUpBig","fadeInDownBig","fadeInLeftBig","fadeInRightBig","flipInX","flipInY","foolishIn","lightSpeedIn","rollIn","rotateIn","rotateInDownLeft","rotateInDownRight","rotateInUpLeft","rotateInUpRight","twisterInDown","twisterInUp","swap","swashIn","tinRightIn","tinLeftIn","tinUpIn","tinDownIn"],effectsOut=["bombRightOut","bombLeftOut","bounceOut","bounceOutDown","bounceOutUp","bounceOutLeft","bounceOutRight","fadeOut","fadeOutUp","fadeOutDown","fadeOutLeft","fadeOutRight","fadeOutUpBig","fadeOutDownBig","fadeOutLeftBig","fadeOutRightBig","flipOutX","flipOutY","foolishOut","hinge","holeOut","lightSpeedOut","puffOut","rollOut","rotateOut","rotateOutDownLeft","rotateOutDownRight","rotateOutUpLeft","rotateOutUpRight","rotateDown","rotateUp","rotateLeft","rotateRight","swashOut","tinRightOut","tinLeftOut","tinUpOut","tinDownOut","vanishOut"];var i=effectsIn.length,e=effectsOut.length;t.fn.mdSlider=function(s){function a(){"ActiveXObject"in window&&t(".md-item-opacity",J).addClass("md-ieopacity"),J.addClass("loading-image");var i="";if(s.responsive&&(i+=" md-slide-responsive"),s.fullwidth&&(i+=" md-slide-fullwidth"),s.showBullet&&s.posBullet&&(i+=" md-slide-bullet-"+s.posBullet),!s.showBullet&&s.showThumb&&s.posThumb&&(i+=" md-slide-thumb-"+s.posThumb),J.wrap('<div class="'+s.className+i+'"><div class="md-item-wrap"></div></div>'),P=J.parent(),_=P.parent(),E=s.responsive?J.width():s.width,j=s.height,K=[],U=C(),U&&_.addClass("md-touchdevice"),J.find("."+s.itemClassName).each(function(i){Z++,K[i]=t(this),t(this).find(".md-object").each(function(){var i=t(this).data("y")?t(this).data("y"):0,e=t(this).data("x")?t(this).data("x"):0,a=t(this).data("width")?t(this).data("width"):0,n=t(this).data("height")?t(this).data("height"):0;a>0&&t(this).width(a/s.width*100+"%"),n>0&&t(this).height(n/s.height*100+"%");var o={top:i/s.height*100+"%",left:e/s.width*100+"%"};t(this).css(o)}),i>0&&t(this).hide()}),n(),o(),s.slideShow&&(ai=!0),t(".md-object",J).hide(),t(".md-video",_).size()>0)if(s.videoBox)t(".md-video",_).mdvideobox();else{var e=t('<div class="md-video-control" style="display: none"></div>');_.append(e),t(".md-video",_).click(function(){var i=t("<iframe></iframe>");i.attr("allowFullScreen","").attr("frameborder","0").css({width:"100%",height:"100%",background:"black"}),i.attr("src",t(this).attr("href"));var s=t('<a href="#" class="md-close-video" title="Close video"></a>');return s.click(function(){return e.html("").hide(),ai=!0,!1}),e.html("").append(i).append(s).show(),ai=!1,!1})}t(window).resize(function(){S()}).trigger("resize"),k();var a=!1;t(window).blur(function(){a=(new Date).getTime()}),t(window).focus(function(){if(a){var t=(new Date).getTime()-a;t>si-oi?oi=si-200:oi+=t,a=!1}})}function n(){if(s.slideShow&&s.showLoading){var i=t('<div class="loading-bar-hoz loading-bar-'+s.loadingPosition+'"><div class="br-timer-glow" style="left: -100px;"></div><div class="br-timer-bar" style="width:0px"></div></div>');_.append(i),H=t(".br-timer-bar",i),q=t(".br-timer-glow",i)}if(s.slideShow&&s.pauseOnHover&&P.hover(function(){ni=!0},function(){ni=!1}),0!=s.styleBorder){var e='<div class="border-top border-style-'+s.styleBorder+'"></div>';e+='<div class="border-bottom border-style-'+s.styleBorder+'"></div>',s.fullwidth||(e+='<div class="border-left border-style-'+s.styleBorder+'"><div class="edge-top"></div><div class="edge-bottom"></div></div>',e+='<div class="border-right border-style-'+s.styleBorder+'"><div class="edge-top"></div><div class="edge-bottom"></div></div>'),_.append(e)}if(0!=s.styleShadow){'<div class="md-shadow md-shadow-style-'+s.styleShadow+'"></div>'}if(s.showArrow&&(A=t('<div class="md-arrow"><div class="md-arrow-left"><span></span></div><div class="md-arrow-right"><span></span></div></div>'),P.append(A),t(".md-arrow-right",A).bind("click",function(){m()}),t(".md-arrow-left",A).bind("click",function(){u()})),0!=s.showBullet){Y=t('<div class="md-bullets"></div>'),_.append(Y);for(var a=0;Z>a;a++)Y.append('<div class="md-bullet"  rel="'+a+'"><a></a></div>');if(s.showThumb){for(var n=parseInt(J.data("thumb-width")),o=parseInt(J.data("thumb-height")),a=0;Z>a;a++){{var d=K[a].data("thumb"),r=K[a].data("thumb-type");K[a].data("thumb-alt")}if(d){var h;h="image"==r?t("<img />").attr("src",d).attr("alt",K[a].data("thumb-alt")).css({top:-(9+o)+"px",left:-(n/2-2)+"px",opacity:0}):t("<span></span>").attr("style",d).css({top:-(9+o)+"px",left:-(n/2-2)+"px",opacity:0}),t("div.md-bullet:eq("+a+")",Y).append(h).append('<div class="md-thumb-arrow" style="opacity: 0"></div>')}}t("div.md-bullet",Y).hover(function(){t(this).addClass("md_hover"),t("img, span",this).show().animate({opacity:1},200),t(".md-thumb-arrow",this).show().animate({opacity:1},200)},function(){t(this).removeClass("md_hover"),t("img, span",this).animate({opacity:0},200,function(){t(this).hide()}),t(".md-thumb-arrow",this).animate({opacity:0},200,function(){t(this).hide()})})}t("div.md-bullet",_).click(function(){if(t(this).hasClass("md-current"))return!1;var i=t(this).attr("rel");l(i)})}else if(s.showThumb){var c=t('<div class="md-thumb"><div class="md-thumb-container"><div class="md-thumb-items"></div></div></div>').appendTo(_);F=t(".md-thumb-items",c);for(var a=0;Z>a;a++){{var d=K[a].data("thumb"),r=K[a].data("thumb-type");K[a].data("thumb-alt")}if(d){var p=t('<a class="md-thumb-item" />').attr("rel",a);p.append("image"==r?t("<img />").attr("src",d).attr("alt",K[a].data("thumb-alt")):t("<span />").attr("style",d).css("display","inline-block")),F.append(p)}}t("a",F).click(function(){if(t(this).hasClass("md-current")||ei)return!1;var i=t(this).attr("rel");l(i)})}}function o(){U?(J.bind("touchstart",function(t){return ii?!1:(t=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],ii=!0,Q=void 0,J.mouseY=t.pageY,void(J.mouseX=t.pageX))}),J.bind("touchmove",function(t){if(t=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],ii){var i=t.pageX||t.clientX,e=t.pageY||t.clientY;return"undefined"==typeof Q&&(Q=!!(Q||Math.abs(e-J.mouseY)>Math.abs(i-J.mouseX))),Q?void(ii=!1):(N=i-J.mouseX,!1)}}),J.bind("touchend",function(){if(ii){if(ii=!1,N>s.touchSensitive)return u(),N=0,!1;if(N<-s.touchSensitive)return m(),N=0,!1}})):(P.hover(function(){A&&A.addClass("active")},function(){A&&A.removeClass("active")}),_.trigger("hover")),s.enableDrag&&(J.mousedown(function(t){return ii||(ii=!0,Q=void 0,J.mouseY=t.pageY,J.mouseX=t.pageX),!1}),J.mousemove(function(t){if(ii){var i=t.pageX||t.clientX,e=t.pageY||t.clientY;return"undefined"==typeof Q&&(Q=!!(Q||Math.abs(e-J.mouseY)>Math.abs(i-J.mouseX))),Q?void(ii=!1):(N=i-J.mouseX,!1)}}),J.mouseup(function(){return ii?(ii=!1,N>s.touchSensitive?u():N<-s.touchSensitive&&m(),N=0,!1):void 0}),J.mouseleave(function(){J.mouseup()}))}function d(){if(F){F.unbind("touchstart"),F.unbind("touchmove"),F.unbind("touchmove"),F.css("left",0);var i=0,e=F.parent().parent();if(t("a.md-thumb-item",F).each(function(){t("img",t(this)).length>0?(t("img",t(this)).css("borderLeftWidth")&&(i+=parseInt(t("img",t(this)).css("borderLeftWidth"),10)),t("img",t(this)).css("borderRightWidth")&&(i+=parseInt(t("img",t(this)).css("borderRightWidth"),10)),t("img",t(this)).css("marginLeft")&&(i+=parseInt(t("img",t(this)).css("marginLeft"),10)),t("img",t(this)).css("marginRight")&&(i+=parseInt(t("img",t(this)).css("marginRight"),10))):(t("span",t(this)).css("borderLeftWidth")&&(i+=parseInt(t("span",t(this)).css("borderLeftWidth"),10)),t("span",t(this)).css("borderRightWidth")&&(i+=parseInt(t("span",t(this)).css("borderRightWidth"),10)),t("span",t(this)).css("marginLeft")&&(i+=parseInt(t("span",t(this)).css("marginLeft"),10)),t("span",t(this)).css("marginRight")&&(i+=parseInt(t("span",t(this)).css("marginRight"),10))),t(this).css("borderLeftWidth")&&(i+=parseInt(t(this).css("borderLeftWidth"),10)),t(this).css("borderRightWidth")&&(i+=parseInt(t(this).css("borderRightWidth"),10)),t(this).css("marginLeft")&&(i+=parseInt(t(this).css("marginLeft"),10)),t(this).css("marginRight")&&(i+=parseInt(t(this).css("marginRight"),10)),i+=parseInt(J.data("thumb-width"))}),t(".md-thumb-next",e).remove(),t(".md-thumb-prev",e).remove(),i>t(".md-thumb-container",e).width()&&(ti=t(".md-thumb-container",e).width()-i,F.width(i),e.append('<div class="md-thumb-prev"></div><div class="md-thumb-next"></div>'),t(".md-thumb-prev",e).click(function(){r("right")}),t(".md-thumb-next",e).click(function(){r("left")}),h(),U)){ei=!0;var a,n;F.bind("touchstart",function(t){return t=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],a=!0,this.mouseX=t.pageX,n=F.position().left,!1}),F.bind("touchmove",function(t){return t.preventDefault(),t=t.originalEvent.touches[0]||t.originalEvent.changedTouches[0],a&&F.css("left",n+t.pageX-this.mouseX),!1}),F.bind("touchend",function(i){if(i.preventDefault(),i=i.originalEvent.touches[0]||i.originalEvent.changedTouches[0],a=!1,Math.abs(i.pageX-this.mouseX)<s.touchSensitive){var e=t(i.target).closest("a.md-thumb-item");return e.length&&l(e.attr("rel")),F.stop(!0,!0).animate({left:n},400),!1}return F.position().left<ti?F.stop(!0,!0).animate({left:ti},400,function(){h()}):F.position().left>0&&F.stop(!0,!0).animate({left:0},400,function(){h()}),n=0,!1})}}}function r(i){if(F)if("left"==i){var e=F.position().left;if(e>ti){var s=t(".md-thumb-container",_).width();e-s>ti?F.stop(!0,!0).animate({left:e-s},400,function(){h()}):F.stop(!0,!0).animate({left:ti},400,function(){h()})}}else if("right"==i){var e=F.position().left;if(0>e){var s=t(".md-thumb-container",_).width();0>e+s?F.stop(!0,!0).animate({left:e+s},400,function(){h()}):F.stop(!0,!0).animate({left:0},400,function(){h()})}}else{var a=t("a",F).index(t("a.md-current",F));if(a>=0){var e=F.position().left,n=a*t("a",F).width();if(0>n+e)F.stop(!0,!0).animate({left:-n},400,function(){h()});else{var o=n+t("a",F).width(),s=t(".md-thumb-container",_).width();o+e>s&&F.stop(!0,!0).animate({left:s-o},400,function(){h()})}}}}function h(){var i=F.position().left;i>ti?t(".md-thumb-next",_).show():t(".md-thumb-next",_).hide(),0>i?t(".md-thumb-prev",_).show():t(".md-thumb-prev",_).hide()}function l(i){if(oi=0,si=K[i].data("timeout")?K[i].data("timeout"):s.slideShowDelay,H){var e=oi*E/si;H.width(e),q.css({left:e-100+"px"})}if(z=V,V=i,s.onStartTransition.call(J),K[z]){t("div.md-bullet:eq("+z+")",Y).removeClass("md-current"),t("a:eq("+z+")",F).removeClass("md-current"),v(K[z]);var a=s.transitions;if("random"==s.transitions.toLowerCase()){var n=new Array("slit-horizontal-left-top","slit-horizontal-top-right","slit-horizontal-bottom-up","slit-vertical-down","slit-vertical-up","strip-up-right","strip-up-left","strip-down-right","strip-down-left","strip-left-up","strip-left-down","strip-right-up","strip-right-down","strip-right-left-up","strip-right-left-down","strip-up-down-right","strip-up-down-left","left-curtain","right-curtain","top-curtain","bottom-curtain","slide-in-right","slide-in-left","slide-in-up","slide-in-down","fade");a=n[Math.floor(Math.random()*(n.length+1))],void 0==a&&(a="fade"),a=t.trim(a.toLowerCase())}if(-1!=s.transitions.indexOf(",")){var n=s.transitions.split(",");a=n[Math.floor(Math.random()*n.length)],void 0==a&&(a="fade"),a=t.trim(a.toLowerCase())}if(K[V].data("transition")){var n=K[V].data("transition").split(",");a=n[Math.floor(Math.random()*n.length)],a=t.trim(a.toLowerCase())}(this.support=Modernizr.csstransitions&&Modernizr.csstransforms3d)||"slit-horizontal-left-top"!=a&&"slit-horizontal-top-right"!=a&&"slit-horizontal-bottom-up"!=a&&"slit-vertical-down"!=a&&"slit-vertical-up"!=a||(a="fade"),$=!0,O(a),Y&&t("div.md-bullet:eq("+V+")",Y).addClass("md-current"),F&&t("a:eq("+V+")",F).addClass("md-current"),r()}else K[V].css({top:0,left:0}).show(),g(K[i]),Y&&t("div.md-bullet:eq("+V+")",Y).addClass("md-current"),F&&t("a:eq("+V+")",F).addClass("md-current"),r(),$=!1}function c(){l(0),G=setInterval(p,40)}function p(){if($)return!1;if(ai&&!ni)if(oi+=40,oi>si)m();else if(H){var t=oi*E/si;H.width(t),q.css({left:t-100+"px"})}}function m(){if($)return!1;var t=V;t++,t>=Z&&s.loop?(t=0,l(t)):Z>t&&l(t)}function u(){if($)return!1;var t=V;t--,0>t&&s.loop?(t=Z-1,l(t)):t>=0&&l(t)}function f(t){var i=t.data("easeout")?t.data("easeout"):"",s=!!window.ActiveXObject&&+/msie\s(\d+)/i.exec(navigator.userAgent)[1]||0/0;s=0/0!=s?11:parseInt(s),clearTimeout(t.data("timer-start")),""!=i&&"keep"!=i&&9>=s?t.fadeOut():(t.removeClass(effectsIn.join(" ")),""!=i?("random"==i&&(i=effectsOut[Math.floor(Math.random()*e)]),t.addClass(i)):t.hide())}function v(i){i.find(".md-object").each(function(){var i=t(this);i.stop(!0,!0).hide(),clearTimeout(i.data("timer-start")),clearTimeout(i.data("timer-stop"))})}function g(e){t(".md-object",e).each(function(){var e=t(this);e.data("easeout")&&e.removeClass(effectsOut.join(" "));var s=e.data("easein")?e.data("easein"):"",a=!!window.ActiveXObject&&+/msie\s(\d+)/i.exec(navigator.userAgent)[1]||0/0;a=0/0!=a?11:parseInt(a),"random"==s&&(s=effectsIn[Math.floor(Math.random()*i)]),e.removeClass(effectsIn.join(" ")),e.hide(),void 0!=e.data("start")?e.data("timer-start",setTimeout(function(){""!=s&&9>=a?e.fadeIn():e.show().addClass(s)},e.data("start"))):e.show().addClass(s),void 0!=e.data("stop")&&e.data("timer-stop",setTimeout(function(){f(e)},e.data("stop")))})}function w(){s.onEndTransition.call(J),t(".md-strips-container",J).remove(),K[z].hide(),K[V].show(),$=!1,g(K[V])}function b(i,e){var a,e=e?e:s,n=t('<div class="md-strips-container"></div>'),o=Math.round(E/e.strips),d=Math.round(j/e.strips),r=t(".md-mainimg img",K[V]),h=t(".md-slider-overlay",K[V]);if(h.length){var l=t('<div class="md-slider-overlay"></div>');l.css({"background-color":h.css("background-color")}),n.append(l)}0==r.length&&(r=t(".md-mainimg",K[V]));for(var c=0;c<e.strips;c++){var p,m,u=i?d*c+"px":"0px",f=i?"0px":o*c+"px";c==e.strips-1?(p=i?"0px":E-o*c+"px",m=i?j-d*c+"px":"0px"):(p=i?"0px":o+"px",m=i?d+"px":"0px"),a=t('<div class="mdslider-strip"></div>').css({width:p,height:m,top:u,left:f,opacity:0}).append(r.clone().css({marginLeft:i?0:-(c*o)+"px",marginTop:i?-(c*d)+"px":0})),n.append(a)}J.append(n)}function y(i,e,s){var a,n=t('<div class="md-strips-container"></div>'),o=E/i,d=j/e,r=t(".md-mainimg img",K[s]),h=t(".md-slider-overlay",K[s]);if(h.length){var l=t('<div class="md-slider-overlay"></div>');l.css({"background-color":h.css("background-color")}),n.append(l)}0==r.length&&(r=t(".md-mainimg",K[s]));for(var c=0;e>c;c++)for(var p=0;i>p;p++){var m=d*c+"px",u=o*p+"px";a=t('<div class="mdslider-tile"/>').css({width:o,height:d,top:m,left:u}).append(r.clone().css({marginLeft:"-"+u,marginTop:"-"+m})),n.append(a)}J.append(n)}function I(){var i,e=[],s=t('<div class="md-strips-container"></div>'),a=t(".md-slider-overlay",K[V]);if(a.length){var n=t('<div class="md-slider-overlay"></div>');n.css({"background-color":a.css("background-color")}),s.append(n)}t(".md-mainimg img",K[z]),t(".md-mainimg img",K[V]),e.push(t(".md-mainimg img",K[z]).length>0?t(".md-mainimg img",K[z]):t(".md-mainimg",K[z])),e.push(t(".md-mainimg img",K[V]).length>0?t(".md-mainimg img",K[V]):t(".md-mainimg",K[V]));for(var o=0;2>o;o++)i=t('<div class="mdslider-strip"></div>').css({width:E,height:j}).append(e[o].clone()),s.append(i);J.append(s)}function x(i){var e=t('<div class="md-strips-container '+i+'"></div>'),s=t(".md-mainimg img",K[z]).length>0?t(".md-mainimg img",K[z]):t(".md-mainimg",K[z]),a=t('<div class="mdslider-slit"/>').append(s.clone()),n=t('<div class="mdslider-slit"/>'),o=s.position(),d=t(".md-slider-overlay",K[V]);if(d.length){var r=t('<div class="md-slider-overlay"></div>');r.css({"background-color":d.css("background-color")}),e.append(r)}n.append(s.clone().css("top",o.top-j/2+"px")),("slit-vertical-down"==i||"slit-vertical-up"==i)&&(n=t('<div class="mdslider-slit"/>').append(s.clone().css("left",o.left-E/2+"px"))),e.append(a).append(n),J.append(e)}function O(i){switch(i){case"slit-horizontal-left-top":case"slit-horizontal-top-right":case"slit-horizontal-bottom-up":case"slit-vertical-down":case"slit-vertical-up":x(i),t(".md-object",K[V]).hide(),K[z].hide(),K[V].show();var e=t(".mdslider-slit",J).first(),a=t(".mdslider-slit",J).last(),n={transition:"all "+s.transitionsSpeed+"ms ease-in-out","-webkit-transition":"all "+s.transitionsSpeed+"ms ease-in-out","-moz-transition":"all "+s.transitionsSpeed+"ms ease-in-out","-ms-transition":"all "+s.transitionsSpeed+"ms ease-in-out"};t(".mdslider-slit",J).css(n),setTimeout(function(){e.addClass("md-trans-elems-1"),a.addClass("md-trans-elems-2")},50),setTimeout(function(){s.onEndTransition.call(J),t(".md-strips-container",J).remove(),$=!1,g(K[V])},s.transitionsSpeed);break;case"strip-up-right":case"strip-up-left":y(s.stripCols,1,V);var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripCols/2,r=s.transitionsSpeed/2;"strip-up-right"==i&&(o=t(".mdslider-tile",J).reverse()),o.css({height:"1px",bottom:"0px",top:"auto"}),o.each(function(i){var e=t(this);setTimeout(function(){e.animate({height:"100%",opacity:"1.0"},r,"easeInOutQuart",function(){i==s.stripCols-1&&w()})},i*d)});break;case"strip-down-right":case"strip-down-left":y(s.stripCols,1,V);var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripCols/2,r=s.transitionsSpeed/2;"strip-down-right"==i&&(o=t(".mdslider-tile",J).reverse()),o.css({height:"1px",top:"0px",bottom:"auto"}),o.each(function(i){var e=t(this);setTimeout(function(){e.animate({height:"100%",opacity:"1.0"},r,"easeInOutQuart",function(){i==s.stripCols-1&&w()})},i*d)});break;case"strip-left-up":case"strip-left-down":y(1,s.stripRows,V);var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripRows/2,r=s.transitionsSpeed/2;"strip-left-up"==i&&(o=t(".mdslider-tile",J).reverse()),o.css({width:"1px",left:"0px",right:"auto"}),o.each(function(i){var e=t(this);setTimeout(function(){e.animate({width:"100%",opacity:"1.0"},r,"easeInOutQuart",function(){i==s.stripRows-1&&w()})},i*d)});break;case"strip-right-up":case"strip-right-down":y(1,s.stripRows,V);var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripRows/2,r=s.transitionsSpeed/2;"strip-left-right-up"==i&&(o=t(".mdslider-tile",J).reverse()),o.css({width:"1px",left:"auto",right:"1px"}),o.each(function(i){var e=t(this);setTimeout(function(){e.animate({width:"100%",opacity:"1.0"},r,"easeInOutQuart",function(){i==s.stripRows-1&&w()})},i*d)});break;case"strip-right-left-up":case"strip-right-left-down":y(1,s.stripRows,z),K[z].hide(),K[V].show();var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripRows,r=s.transitionsSpeed/2;"strip-right-left-up"==i&&(o=t(".mdslider-tile",J).reverse()),o.filter(":odd").css({width:"100%",right:"0px",left:"auto",opacity:1}).end().filter(":even").css({width:"100%",right:"auto",left:"0px",opacity:1}),o.each(function(i){var e=t(this),a=i%2==0?{left:"-50%",opacity:"0"}:{right:"-50%",opacity:"0"};setTimeout(function(){e.animate(a,r,"easeOutQuint",function(){i==s.stripRows-1&&(s.onEndTransition.call(J),t(".md-strips-container",J).remove(),$=!1,g(K[V]))})},i*d)});break;case"strip-up-down-right":case"strip-up-down-left":y(s.stripCols,1,z),K[z].hide(),K[V].show();var o=t(".mdslider-tile",J),d=s.transitionsSpeed/s.stripCols/2,r=s.transitionsSpeed/2;"strip-up-down-right"==i&&(o=t(".mdslider-tile",J).reverse()),o.filter(":odd").css({height:"100%",bottom:"0px",top:"auto",opacity:1}).end().filter(":even").css({height:"100%",bottom:"auto",top:"0px",opacity:1}),o.each(function(i){var e=t(this),a=i%2==0?{top:"-50%",opacity:0}:{bottom:"-50%",opacity:0};setTimeout(function(){e.animate(a,r,"easeOutQuint",function(){i==s.stripCols-1&&(s.onEndTransition.call(J),t(".md-strips-container",J).remove(),$=!1,g(K[V]))})},i*d)});break;case"left-curtain":y(s.stripCols,1,V);var o=t(".mdslider-tile",J),h=E/s.stripCols,d=s.transitionsSpeed/s.stripCols/2;o.each(function(i){var e=t(this);e.css({left:h*i,width:0,opacity:0}),setTimeout(function(){e.animate({width:h,opacity:"1.0"},s.transitionsSpeed/2,function(){i==s.stripCols-1&&w()})},d*i)});break;case"right-curtain":y(s.stripCols,1,V);var o=t(".mdslider-tile",J).reverse(),h=E/s.stripCols,d=s.transitionsSpeed/s.stripCols/2;o.each(function(i){var e=t(this);e.css({right:h*i,left:"auto",width:0,opacity:0}),setTimeout(function(){e.animate({width:h,opacity:"1.0"},s.transitionsSpeed/2,function(){i==s.stripCols-1&&w()})},d*i)});break;case"top-curtain":y(1,s.stripRows,V);var o=t(".mdslider-tile",J),l=j/s.stripRows,d=s.transitionsSpeed/s.stripRows/2;o.each(function(i){var e=t(this);e.css({top:l*i,height:0,opacity:0}),setTimeout(function(){e.animate({height:l,opacity:"1.0"},s.transitionsSpeed/2,function(){i==s.stripRows-1&&w()})},d*i)});break;case"bottom-curtain":y(1,s.stripRows,V);var o=t(".mdslider-tile",J).reverse(),l=j/s.stripRows,d=s.transitionsSpeed/s.stripRows/2;o.each(function(i){var e=t(this);e.css({bottom:l*i,height:0,opacity:0}),setTimeout(function(){e.animate({height:l,opacity:"1.0"},s.transitionsSpeed/2,function(){i==s.stripRows-1&&w()})},d*i)});break;case"slide-in-right":var c=0;I();var o=t(".mdslider-strip",J);o.each(function(){m=t(this);var i=c*E;m.css({left:i}),m.animate({left:i-E},s.transitionsSpeed,function(){w()}),c++});break;case"slide-in-left":var c=0;I();var o=t(".mdslider-strip",J);o.each(function(){m=t(this);var i=-c*E;m.css({left:i}),m.animate({left:E+i},2*s.transitionsSpeed,function(){w()}),c++});break;case"slide-in-up":var c=0;I();var o=t(".mdslider-strip",J);o.each(function(){m=t(this);var i=c*j;m.css({top:i}),m.animate({top:i-j},s.transitionsSpeed,function(){w()}),c++});break;case"slide-in-down":var c=0;I();var o=t(".mdslider-strip",J);o.each(function(){m=t(this);var i=-c*j;m.css({top:i}),m.animate({top:j+i},s.transitionsSpeed,function(){w()}),c++});break;case"fade":default:var p={strips:1};b(!1,p);var m=t(".mdslider-strip:first",J);m.css({height:"100%",width:E}),"slide-in-right"==i?m.css({height:"100%",width:E,left:E+"px",right:""}):"slide-in-left"==i&&m.css({left:"-"+E+"px"}),m.animate({left:"0px",opacity:1},s.transitionsSpeed,function(){w()})}}function C(){return"ontouchstart"in window||"createTouch"in document}function S(){if(_.width(),E=s.responsive?_.width():s.width,s.responsive&&(j=s.fullwidth&&E>s.width?s.height:Math.round(E/s.width*s.height)),s.responsive||s.fullwidth||_.width(E),!s.responsive&&s.fullwidth&&_.css({"min-width":E+"px"}),s.fullwidth){t(".md-objects",J).width(s.width);var i=20;(_.width()-s.width)/2>20&&(i=(_.width()-s.width)/2),_.find(".md-bullets").css({left:i,right:i}),_.find(".md-thumb").css({left:i,right:i})}s.responsive&&s.fullwidth&&_.width()<s.width&&t(".md-objects",J).width(E),_.height(j),t(".md-slide-item",J).height(j),T(),d(),B(),X(),D()}function T(){t(".md-slide-item",J).each(function(){var i=t(".md-mainimg img",this);if(1==i.length){if(i.data("defW")&&i.data("defH")){var e=i.data("defW"),s=i.data("defH");L(i,e,s)}}else t(".md-mainimg",t(this)).width(t(".md-slide-item:visible",J).width()).height(t(".md-slide-item:visible",J).height())})}function k(){var i=t(".md-slide-item .md-mainimg img",J).length;J.data("count",i),0==J.data("count")&&R(),t(".md-slide-item .md-mainimg img",J).each(function(){t(this).load(function(){var i=t(this);if(!i.data("defW")){var e=W(i.attr("src"));L(i,e.width,e.height),i.data({defW:e.width,defH:e.height})}J.data("count",J.data("count")-1),0==J.data("count")&&R()}),this.complete&&t(this).load()})}function R(){J.removeClass("loading-image"),c()}function L(i,e,s){var a=t(".md-slide-item:visible",J).width(),n=t(".md-slide-item:visible",J).height();if(s>0&&n>0){var o=n-a/e*s;i.css({width:a+"px",height:"auto"}),i.css(0>o?{top:o/2+"px",left:0}:{left:0,top:0})}}function B(){var i=1;parseInt(t.browser.version,10)<9&&(i=6),t(".md-objects",J).css(_.width()<s.width?{"font-size":_.width()/s.width*100-i+"%"}:{"font-size":100-i+"%"})}function X(){t(".md-objects div.md-object",J).each(_.width()<s.width&&s.responsive?function(){var i=_.width()/s.width,e=t(this),a={};e.data("paddingtop")&&(a["padding-top"]=e.data("paddingtop")*i),e.data("paddingright")&&(a["padding-right"]=e.data("paddingright")*i),e.data("paddingbottom")&&(a["padding-bottom"]=e.data("paddingbottom")*i),e.data("paddingleft")&&(a["padding-left"]=e.data("paddingleft")*i),t("> a",e).length?t("> a",e).css(a):e.css(a)}:function(){var i=t(this),e={};i.data("paddingtop")&&(e["padding-top"]=i.data("paddingtop")),i.data("paddingtop")&&(e["padding-top"]=i.data("paddingtop")),i.data("paddingright")&&(e["padding-right"]=i.data("paddingright")),i.data("paddingbottom")&&(e["padding-bottom"]=i.data("paddingbottom")),i.data("paddingleft")&&(e["padding-left"]=i.data("paddingleft")),t("> a",i).length?t("> a",i).css(e):i.css(e)})}function D(){if(s.showThumb&&!s.showBullet){var t=J.data("thumb-height");if("1"==s.posThumb){var i=t/2;_.find(".md-thumb").css({height:t+10,bottom:-i-10}),_.css({"margin-bottom":i+10})}else _.find(".md-thumb").css({height:t+10,bottom:-(t+40)}),_.css({"margin-bottom":t+50})}}function W(t){var i=new Image;i.src=t;var e={height:i.height,width:i.width};return e}var M={className:"md-slide-wrap",itemClassName:"md-slide-item",transitions:"strip-down-left",transitionsSpeed:800,width:990,height:420,responsive:!0,fullwidth:!0,styleBorder:0,styleShadow:0,posBullet:2,posThumb:1,stripCols:20,stripRows:10,slideShowDelay:6e3,slideShow:!0,loop:!1,pauseOnHover:!1,showLoading:!0,loadingPosition:"bottom",showArrow:!0,showBullet:!0,videoBox:!1,showThumb:!0,enableDrag:!0,touchSensitive:50,onEndTransition:function(){},onStartTransition:function(){}};s=t.extend({},M,s);var z,E,j,_,P,U,A,Y,H,q,F,Q,N,G,J=t(this),K=[],V=-1,Z=0,$=!0,ti=0,ii=!1,ei=!1,si=0,ai=!1,ni=!1,oi=0;t(document).ready(function(){a()})},t.fn.reverse=[].reverse;var s=function(t,i,e){this.m_pfnPercent=i,this.m_pfnFinished=e,this.m_nLoaded=0,this.m_nProcessed=0,this.m_aImages=new Array,this.m_nICount=t.length;for(var s=0;s<t.length;s++)this.Preload(t[s])};s.prototype={Preload:function(t){var i=new Image;this.m_aImages.push(i),i.onload=s.prototype.OnLoad,i.onerror=s.prototype.OnError,i.onabort=s.prototype.OnAbort,i.oImagePreload=this,i.bLoaded=!1,i.source=t,i.src=t},OnComplete:function(){this.m_nProcessed++,this.m_nProcessed==this.m_nICount?this.m_pfnFinished():this.m_pfnPercent(Math.round(this.m_nProcessed/this.m_nICount*10))},OnLoad:function(){this.bLoaded=!0,this.oImagePreload.m_nLoaded++,this.oImagePreload.OnComplete()},OnError:function(){this.bError=!0,this.oImagePreload.OnComplete()},OnAbort:function(){this.bAbort=!0,this.oImagePreload.OnComplete()}},t.fn.mdvideobox=function(i){t(this).each(function(){function e(){if(0==t("#md-overlay").length){var i=t('<div id="md-overlay" class="md-overlay"></div>').hide().click(s),e=t('<div id="md-videocontainer" class="md-videocontainer"><div id="md-video-embed"></div><div class="md-description clearfix"><div class="md-caption"></div><a id="md-closebtn" class="md-closebtn" href="#"></a></div></div>');e.css({width:c.initialWidth+"px",height:c.initialHeight+"px",display:"none"}),t("#md-closebtn",e).click(s),t("body").append(i).append(e)}o=t("#md-overlay"),d=t("#md-videocontainer"),h=t("#md-video-embed",d),r=t(".md-caption",d),p.click(a)}function s(){return o.fadeTo("fast",0,function(){t(this).css("display","none")}),h.html(""),d.hide(),!1}function a(){c.click.call(),o.css({height:t(window).height()+"px"});var i=t(window).height()/2-c.initialHeight/2,e=t(window).width()/2-c.initialWidth/2;return d.css({top:i,left:e}).show(),h.css({background:"#fff url(css/loading.gif) no-repeat center",height:c.contentsHeight,width:c.contentsWidth}),o.css("display","block").fadeTo("fast",c.defaultOverLayFade),r.html(u),h.fadeIn("slow",function(){n()}),!1}function n(){h.css("background","#fff"),l='<iframe src="'+m+'" width="'+c.contentsWidth+'" height="'+c.contentsHeight+'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',h.html(l)}var o,d,r,h,l,c=t.extend({initialWidth:640,initialHeight:400,contentsWidth:640,contentsHeight:350,defaultOverLayFade:.8,click:function(){}},i),p=t(this),m=p.attr("href"),u=p.attr("title");e()})}}(jQuery);
;
