jQuery(document).ready(function($){

    jQuery.fn.exists = function () { return this.length > 0 };
    if(jQuery('#edit-field-template-und').exists()) {
		$template = jQuery('select#edit-field-template-und');

		switch($template.val()) {
			case 'video':
				jQuery('.field-name-field-slider-block').hide();
			break;
			case 'image':
				jQuery('.field-name-field-slider-block').hide();
				jQuery('.field-name-field-video').hide();
			break;
			case 'slider':
				jQuery('.field-name-field-slider-block').show();
				jQuery('.field-name-field-video').hide();
			break;
		}
		//template change
		$template.change(function () {
			switch(jQuery(this).val()) {
				case 'video':
					jQuery('.field-name-field-video').show();
					jQuery('.field-name-field-slider-block').hide();
				break;
				case 'image':
					jQuery('.field-name-field-slider-block').hide();
					jQuery('.field-name-field-video').hide();
				break;
				case 'slider':
					jQuery('.field-name-field-slider-block').show();
					jQuery('.field-name-field-video').hide();
				break;
			}
		});
    }
	//Add class to vertical tab button
    if(jQuery('body.page-admin-appearance').exists()) {


		if(jQuery('#edit-custom div.form-wrapper').exists() == false) {
			jQuery('#edit-custom').remove();
		};

		//Settings color picker

		//Change text btn submit
		//jQuery('.colorpicker_submit').text('OK');

		//Settings background image
		jQuery('input:checked').parent().addClass('form-item-active');
		jQuery('input:radio').click(function() {
			jQuery('input:radio[name='+jQuery(this).attr('name')+']').parent().removeClass('form-item-active');
	        jQuery(this).parent().addClass('form-item-active');
	        
		});


    }



});