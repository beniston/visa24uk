(function ($) {

Drupal.behaviors.viewsextras = {
  attach: function (context, settings) {	
	  //this makes the ctools autosubmit work for checkboxes and radios - not just selects
	  //borrowed from patch - http://drupal.org/node/1181692
	  //should be in ctools module, but isn't yet
	  $('*[type!=input].ctools-auto-submit:not(.ctools-auto-submit-processed),.ctools-auto-submit-full-form *[type!=input]:not(.ctools-auto-submit-processed)')
		.addClass('.ctools-auto-submit-processed')
		.change(function() {
		  $(this.form).find('.ctools-auto-submit-click').click();
	  });  
	
	  //make collapsed fieldset inline if inline option is set
	  $('.veft-collapsed legend').each(function(){
		  $widget = $(this).parent().parent().parent();
		  if ($(this).parent().hasClass('collapsed')) {
			  $widget.addClass('veft-collapsed');
			  $widget.removeClass('veft-expanded');
		  }
		  else {
			  $widget.addClass('veft-expanded');
			  $widget.removeClass('veft-collapsed');
		  }
		  
		  $(this).mouseup(function(){
			  if ($(this).parent().hasClass('collapsed')) {
				  $widget.addClass('veft-expanded');
				  $widget.removeClass('veft-collapsed');
			  }
			  else {
				  $widget.addClass('veft-collapsed');
				  $widget.removeClass('veft-expanded');
			  }
		  });
	  });
  }
}
	
}(jQuery));