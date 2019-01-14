jQuery(document).ajaxComplete(function() {
    // the code to be fired after the AJAX is completed	
    jQuery('.form-type-managed-file').each(function(index, el) {
    	var img = jQuery(this).find('.file > a').attr('href');
 		jQuery(this).find('.file .file-icon').css({
 		width: '80px',
 		height: '80px'
 		});
    	jQuery(this).find('.file .file-icon').attr('src', img);	
    });
    				
});
