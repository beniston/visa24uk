(function($){Drupal.behaviors.betterExposedFilters={attach:function(context){$('.bef-tree input[type=checkbox], .bef-checkboxes input[type=checkbox]').change(function(){_bef_highlight(this,context);}).filter(':checked').closest('.form-item',context).addClass('highlight');}};Drupal.behaviors.betterExposedFiltersSelectAllNone={attach:function(context){var selected=$('.form-checkboxes.bef-select-all-none:not(.bef-processed)');if(selected.length){var selAll=Drupal.t('Select All');var selNone=Drupal.t('Select None');var link=$('<a class="bef-toggle" href="#">'+selAll+'</a>')
link.click(function(event){event.preventDefault();event.stopPropagation();if(selAll==$(this).text()){$(this).html(selNone).siblings('.bef-checkboxes, .bef-tree').find('.form-item input:checkbox').each(function(){$(this).attr('checked',true);_bef_highlight(this,context);}).end().find('input[type=checkbox]:first').change();}
else{$(this).html(selAll).siblings('.bef-checkboxes, .bef-tree').find('.form-item input:checkbox').each(function(){$(this).attr('checked',false);_bef_highlight(this,context);}).end().find('input[type=checkbox]:first').change();}});selected.addClass('bef-processed').each(function(index){var newLink=link.clone(true);newLink.insertBefore($('.bef-checkboxes, .bef-tree',this));if($('input:checkbox:checked',this).length==$('input:checkbox',this).length){newLink.click();}});}
var befSettings=Drupal.settings.better_exposed_filters;if(befSettings&&befSettings.datepicker&&befSettings.datepicker_options&&$.fn.datepicker){var opt=[];$.each(befSettings.datepicker_options,function(key,val){if(key&&val){opt[key]=JSON.parse(val);}});$('.bef-datepicker').datepicker(opt);}}};Drupal.behaviors.betterExposedFiltersAllNoneNested={attach:function(context,settings){$('.form-checkboxes.bef-select-all-none-nested li').has('ul').once('bef-all-none-nested',function(){$(this).find('input.form-checkboxes:first').click(function(){var checkedParent=$(this).attr('checked');if(!checkedParent){$(this).parents('li:first').find('ul input.form-checkboxes').removeAttr('checked');}
else{$(this).parents('li:first').find('ul input.form-checkboxes').attr('checked',$(this).attr('checked'));}}).end().find('ul input.form-checkboxes').click(function(){var checked=$(this).attr('checked');var ct=$(this).parents('ul:first').find('input.form-checkboxes:not(:checked)').size();if(!checked){$(this).parents('li:first').parents('li:first').find('input.form-checkboxes:first').removeAttr('checked');}
if(!ct){$(this).parents('li:first').parents('li:first').find('input.form-checkboxes:first').attr('checked',checked);}});});}};Drupal.behaviors.better_exposed_filters_slider={attach:function(context,settings){var befSettings=settings.better_exposed_filters;if(befSettings&&befSettings.slider&&befSettings.slider_options){$.each(befSettings.slider_options,function(i,sliderOptions){var containing_parent="#"+sliderOptions.viewId+" #edit-"+sliderOptions.id+"-wrapper .views-widget";var $filter=$(containing_parent);if(!$filter.length){containing_parent="#"+sliderOptions.viewId+" .bef-slider-wrapper";$filter=$(containing_parent);}
$filter.once('slider-filter',function(){var $input=$(this).find('input[type=text]');if($input.length==2){var $min=$input.parent().find('input#edit-'+sliderOptions.id+'-min'),$max=$input.parent().find('input#edit-'+sliderOptions.id+'-max'),default_min,default_max;if(!$min.length||!$max.length){return;}
default_min=parseFloat(($min.val()=='')?sliderOptions.min:$min.val(),10);default_max=parseFloat(($max.val()=='')?sliderOptions.max:$max.val(),10);$min.val(default_min);$max.val(default_max);$min.parents(containing_parent).after($('<div class="bef-slider"></div>').slider({range:true,min:parseFloat(sliderOptions.min,10),max:parseFloat(sliderOptions.max,10),step:parseFloat(sliderOptions.step,10),animate:sliderOptions.animate?sliderOptions.animate:false,orientation:sliderOptions.orientation,values:[default_min,default_max],slide:function(event,ui){$min.val(ui.values[0]);$max.val(ui.values[1]);},change:function(event,ui){$min.val(ui.values[0]);$max.val(ui.values[1]);},stop:function(event,ui){$(this).parents('form').find('.ctools-auto-submit-click').click();}}));$min.blur(function(){befUpdateSlider($(this),0,sliderOptions);});$max.blur(function(){befUpdateSlider($(this),1,sliderOptions);});}
else if($input.length==1){if($input.attr('id')!='edit-'+sliderOptions.id){return;}
var default_value=parseFloat(($input.val()=='')?sliderOptions.min:$input.val(),10);$input.val(default_value);$input.parents(containing_parent).after($('<div class="bef-slider"></div>').slider({min:parseFloat(sliderOptions.min,10),max:parseFloat(sliderOptions.max,10),step:parseFloat(sliderOptions.step,10),animate:sliderOptions.animate?sliderOptions.animate:false,orientation:sliderOptions.orientation,value:default_value,slide:function(event,ui){$input.val(ui.value);},change:function(event,ui){$input.val(ui.value);},stop:function(event,ui){$(this).parents('form').find('.ctools-auto-submit-click').click();}}));$input.blur(function(){befUpdateSlider($(this),null,sliderOptions);});}
else{return;}})});}}};Drupal.behaviors.better_exposed_filters_select_as_links={attach:function(context,settings){$('.bef-select-as-links',context).once(function(){var $element=$(this);if(typeof settings.views=='undefined'||typeof settings.views.ajaxViews=='undefined'){return;}
var $uses_ajax=false;$.each(settings.views.ajaxViews,function(i,item){var $view_name=item.view_name.replace(/_/g,'-');var $view_display_id=item.view_display_id.replace(/_/g,'-');var $id='views-exposed-form-'+$view_name+'-'+$view_display_id;var $form_id=$element.parents('form').attr('id');if($form_id==$id){$uses_ajax=true;return;}});if(!$uses_ajax){return;}
$(this).find('a').click(function(event){var $wrapper=$(this).parents('.bef-select-as-links');var $options=$wrapper.find('select option');event.preventDefault();event.stopPropagation();$wrapper.find('select option').removeAttr('selected');var link_text=$(this).text();$selected=$options.filter(function(){return $(this).text()==link_text;});$selected.attr('selected','selected');$wrapper.find('.bef-new-value').val($selected.val());$wrapper.find('a').removeClass('active');$(this).addClass('active');$wrapper.parents('form').find('.views-submit-button *[type=submit]').click();});});}};Drupal.behaviors.betterExposedFiltersRequiredFilter={attach:function(context,settings){$('.bef-select-as-checkboxes',context).once('bef-required-filter').ajaxComplete(function(e,xhr,s){var $element=$(this);if(typeof settings.views=='undefined'||typeof settings.views.ajaxViews=='undefined'){return;}
var $view_name;var $view_display_id;var $uses_ajax=false;$.each(settings.views.ajaxViews,function(i,item){$view_name=item.view_name;$view_display_id=item.view_display_id;var $id='views-exposed-form-'+$view_name.replace(/_/g,'-')+'-'+$view_display_id.replace(/_/g,'-');var $form_id=$element.parents('form').attr('id');if($form_id==$id){$uses_ajax=true;return false;}});if($('input',this).length>0){var $filter_name=$('input',this).attr('name').slice(0,-2);if(Drupal.settings.better_exposed_filters.views[$view_name].displays[$view_display_id].filters[$filter_name].required&&$('input:checked',this).length==0){$('input',this).prop('checked',true);}}});}}
function _bef_highlight(elem,context){$elem=$(elem,context);$elem.attr('checked')?$elem.closest('.form-item',context).addClass('highlight'):$elem.closest('.form-item',context).removeClass('highlight');}
function befUpdateSlider($el,valIndex,sliderOptions){var val=parseFloat($el.val(),10),currentMin=$el.parents('div.views-widget').next('.bef-slider').slider('values',0),currentMax=$el.parents('div.views-widget').next('.bef-slider').slider('values',1);if(valIndex!=null){if(valIndex==0&&val>currentMax){val=currentMax;}
if(valIndex==1&&val<currentMin){val=currentMin;}
if(isNaN(val)){val=$el.parents('div.views-widget').next('.bef-slider').slider('values',valIndex);}}
else{if(isNaN(val)){val=$el.parents('div.views-widget').next('.bef-slider').slider('value');}}
val=parseFloat(val,10);if(valIndex!=null){$el.parents('div.views-widget').next('.bef-slider').slider('values',valIndex,val);}
else{$el.parents('div.views-widget').next('.bef-slider').slider('value',val);}}})(jQuery);;
(function($){
/**
 * To make a form auto submit, all you have to do is 3 things:
 *
 * ctools_add_js('auto-submit');
 *
 * On gadgets you want to auto-submit when changed, add the ctools-auto-submit
 * class. With FAPI, add:
 * @code
 *  '#attributes' => array('class' => array('ctools-auto-submit')),
 * @endcode
 *
 * If you want to have auto-submit for every form element,
 * add the ctools-auto-submit-full-form to the form. With FAPI, add:
 * @code
 *   '#attributes' => array('class' => array('ctools-auto-submit-full-form')),
 * @endcode
 *
 * If you want to exclude a field from the ctool-auto-submit-full-form auto submission,
 * add the class ctools-auto-submit-exclude to the form element. With FAPI, add:
 * @code
 *   '#attributes' => array('class' => array('ctools-auto-submit-exclude')),
 * @endcode
 *
 * Finally, you have to identify which button you want clicked for autosubmit.
 * The behavior of this button will be honored if it's ajaxy or not:
 * @code
 *  '#attributes' => array('class' => array('ctools-use-ajax', 'ctools-auto-submit-click')),
 * @endcode
 *
 * Currently only 'select', 'radio', 'checkbox' and 'textfield' types are supported. We probably
 * could use additional support for HTML5 input types.
 */

Drupal.behaviors.CToolsAutoSubmit = {
  attach: function(context) {
    // 'this' references the form element
    function triggerSubmit (e) {
      if ($.contains(document.body, this)) {
        var $this = $(this);
        if (!$this.hasClass('ctools-ajaxing')) {
          $this.find('.ctools-auto-submit-click').click();
        }
      }
    }

    // the change event bubbles so we only need to bind it to the outer form
    $('form.ctools-auto-submit-full-form', context)
      .add('.ctools-auto-submit', context)
      .filter('form, select, input:not(:text, :submit)')
      .once('ctools-auto-submit')
      .change(function (e) {
        // don't trigger on text change for full-form
        if ($(e.target).is(':not(:text, :submit, .ctools-auto-submit-exclude)')) {
          triggerSubmit.call(e.target.form);
        }
      });

    // e.keyCode: key
    var discardKeyCode = [
      16, // shift
      17, // ctrl
      18, // alt
      20, // caps lock
      33, // page up
      34, // page down
      35, // end
      36, // home
      37, // left arrow
      38, // up arrow
      39, // right arrow
      40, // down arrow
       9, // tab
      13, // enter
      27  // esc
    ];
    // Don't wait for change event on textfields
    $('.ctools-auto-submit-full-form input:text, input:text.ctools-auto-submit', context)
      .filter(':not(.ctools-auto-submit-exclude)')
      .once('ctools-auto-submit', function () {
        // each textinput element has his own timeout
        var timeoutID = 0;
        $(this)
          .bind('keydown keyup', function (e) {
            if ($.inArray(e.keyCode, discardKeyCode) === -1) {
              timeoutID && clearTimeout(timeoutID);
            }
          })
          .keyup(function(e) {
            if ($.inArray(e.keyCode, discardKeyCode) === -1) {
              timeoutID = setTimeout($.proxy(triggerSubmit, this.form), 500);
            }
          })
          .bind('change', function (e) {
            if ($.inArray(e.keyCode, discardKeyCode) === -1) {
              timeoutID = setTimeout($.proxy(triggerSubmit, this.form), 500);
            }
          });
      });
  }
}
})(jQuery);
;
(function ($) {

Drupal.googleanalytics = {};

$(document).ready(function() {

  // Attach mousedown, keyup, touchstart events to document only and catch
  // clicks on all elements.
  $(document.body).bind("mousedown keyup touchstart", function(event) {

    // Catch the closest surrounding link of a clicked element.
    $(event.target).closest("a,area").each(function() {

      // Is the clicked URL internal?
      if (Drupal.googleanalytics.isInternal(this.href)) {
        // Skip 'click' tracking, if custom tracking events are bound.
        if ($(this).is('.colorbox') && (Drupal.settings.googleanalytics.trackColorbox)) {
          // Do nothing here. The custom event will handle all tracking.
          //console.info("Click on .colorbox item has been detected.");
        }
        // Is download tracking activated and the file extension configured for download tracking?
        else if (Drupal.settings.googleanalytics.trackDownload && Drupal.googleanalytics.isDownload(this.href)) {
          // Download link clicked.
          ga("send", {
            "hitType": "event",
            "eventCategory": "Downloads",
            "eventAction": Drupal.googleanalytics.getDownloadExtension(this.href).toUpperCase(),
            "eventLabel": Drupal.googleanalytics.getPageUrl(this.href),
            "transport": "beacon"
          });
        }
        else if (Drupal.googleanalytics.isInternalSpecial(this.href)) {
          // Keep the internal URL for Google Analytics website overlay intact.
          ga("send", {
            "hitType": "pageview",
            "page": Drupal.googleanalytics.getPageUrl(this.href),
            "transport": "beacon"
          });
        }
      }
      else {
        if (Drupal.settings.googleanalytics.trackMailto && $(this).is("a[href^='mailto:'],area[href^='mailto:']")) {
          // Mailto link clicked.
          ga("send", {
            "hitType": "event",
            "eventCategory": "Mails",
            "eventAction": "Click",
            "eventLabel": this.href.substring(7),
            "transport": "beacon"
          });
        }
        else if (Drupal.settings.googleanalytics.trackOutbound && this.href.match(/^\w+:\/\//i)) {
          if (Drupal.settings.googleanalytics.trackDomainMode !== 2 || (Drupal.settings.googleanalytics.trackDomainMode === 2 && !Drupal.googleanalytics.isCrossDomain(this.hostname, Drupal.settings.googleanalytics.trackCrossDomains))) {
            // External link clicked / No top-level cross domain clicked.
            ga("send", {
              "hitType": "event",
              "eventCategory": "Outbound links",
              "eventAction": "Click",
              "eventLabel": this.href,
              "transport": "beacon"
            });
          }
        }
      }
    });
  });

  // Track hash changes as unique pageviews, if this option has been enabled.
  if (Drupal.settings.googleanalytics.trackUrlFragments) {
    window.onhashchange = function() {
      ga("send", {
        "hitType": "pageview",
        "page": location.pathname + location.search + location.hash
      });
    };
  }

  // Colorbox: This event triggers when the transition has completed and the
  // newly loaded content has been revealed.
  if (Drupal.settings.googleanalytics.trackColorbox) {
    $(document).bind("cbox_complete", function () {
      var href = $.colorbox.element().attr("href");
      if (href) {
        ga("send", {
          "hitType": "pageview",
          "page": Drupal.googleanalytics.getPageUrl(href)
        });
      }
    });
  }

});

/**
 * Check whether the hostname is part of the cross domains or not.
 *
 * @param string hostname
 *   The hostname of the clicked URL.
 * @param array crossDomains
 *   All cross domain hostnames as JS array.
 *
 * @return boolean
 */
Drupal.googleanalytics.isCrossDomain = function (hostname, crossDomains) {
  /**
   * jQuery < 1.6.3 bug: $.inArray crushes IE6 and Chrome if second argument is
   * `null` or `undefined`, http://bugs.jquery.com/ticket/10076,
   * https://github.com/jquery/jquery/commit/a839af034db2bd934e4d4fa6758a3fed8de74174
   *
   * @todo: Remove/Refactor in D8
   */
  if (!crossDomains) {
    return false;
  }
  else {
    return $.inArray(hostname, crossDomains) > -1 ? true : false;
  }
};

/**
 * Check whether this is a download URL or not.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isDownload = function (url) {
  var isDownload = new RegExp("\\.(" + Drupal.settings.googleanalytics.trackDownloadExtensions + ")([\?#].*)?$", "i");
  return isDownload.test(url);
};

/**
 * Check whether this is an absolute internal URL or not.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isInternal = function (url) {
  var isInternal = new RegExp("^(https?):\/\/" + window.location.host, "i");
  return isInternal.test(url);
};

/**
 * Check whether this is a special URL or not.
 *
 * URL types:
 *  - gotwo.module /go/* links.
 *
 * @param string url
 *   The web url to check.
 *
 * @return boolean
 */
Drupal.googleanalytics.isInternalSpecial = function (url) {
  var isInternalSpecial = new RegExp("(\/go\/.*)$", "i");
  return isInternalSpecial.test(url);
};

/**
 * Extract the relative internal URL from an absolute internal URL.
 *
 * Examples:
 * - http://mydomain.com/node/1 -> /node/1
 * - http://example.com/foo/bar -> http://example.com/foo/bar
 *
 * @param string url
 *   The web url to check.
 *
 * @return string
 *   Internal website URL
 */
Drupal.googleanalytics.getPageUrl = function (url) {
  var extractInternalUrl = new RegExp("^(https?):\/\/" + window.location.host, "i");
  return url.replace(extractInternalUrl, '');
};

/**
 * Extract the download file extension from the URL.
 *
 * @param string url
 *   The web url to check.
 *
 * @return string
 *   The file extension of the passed url. e.g. "zip", "txt"
 */
Drupal.googleanalytics.getDownloadExtension = function (url) {
  var extractDownloadextension = new RegExp("\\.(" + Drupal.settings.googleanalytics.trackDownloadExtensions + ")([\?#].*)?$", "i");
  var extension = extractDownloadextension.exec(url);
  return (extension === null) ? '' : extension[1];
};

})(jQuery);
;
