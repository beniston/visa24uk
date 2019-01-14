(function($) {
  "use strict";	
	   
		var slider = new MasterSlider();
		slider.setup('masterslider2' , {
		 width:1400,    // slider standard width
		 height:600,   // slider standard height
		 space:1,
		 layout:'fullwidth',
		 loop:true,
		 preload:0,
		 autoplay:true
	});

})(jQuery);

(function($) {
    "use strict";
    $(function() {
        window.prettyPrint && prettyPrint()
        $(document).on('click', '.yamm .dropdown-menu', function(e) {
            e.stopPropagation()
        })
    })
})(jQuery);

jQuery(document).ready(function($) {
	// Set background color for elements
	$('.background-color').each(function(index, el) {
		$data_color = $(this).data('bg-color');
		$(this).css('background-color', $data_color);
	});

	//Main menu
	$('nav .nav.navbar-nav li.yamm-fw').each(function(index, el) {
		$submenu_mega = '<li><div class="yamm-content"><div class="row">';
		$(this).find('> .dropdown-menu > .expanded').each(function(index, el) {
			$submenu_mega += $(this).html();
		});
		$submenu_mega += '</div></div></li>';
		$(this).find('> .dropdown-menu').html($submenu_mega);
	});
	if($('.yamm-content .list-unstyled a').hasClass('fa')) {
		$('.yamm-content .list-unstyled a').prepend(' ');	
	} else{
		$('.yamm-content .list-unstyled a').prepend('<i class="fa fa-angle-right"></i> ');
	}	
	if($('header').hasClass('menu-icon')) {
		$('nav .nav.navbar-nav >li').each(function(index, el) {
			if($(this).attr('style')) {
				var menu_icon = '<i class="'+$(this).attr('style')+'"></i>';
				$(this).find('>a i').remove();
				$(this).find('>a').prepend('<i class="'+$(this).attr('style')+'"></i> ');
			}
		});
	}
	if($('.header.headr-style-3').hasClass('shop')) {
		$('nav ul.nav.navbar-nav').append('<li class="dropdown cart"><a href="#" class="dropdown-toggle"><i class="fa fa-shopping-cart"></i> <span class="items">0</span> item(s) : <span class="font-bold">$0.00</span> <i class="fa fa-angle-down"></i></a></li>');
		Updatecart ();
		$('li.dropdown.cart').hover(function() {
			if($('.shopping-cart').hasClass('hide')) {
				$('.shopping-cart').show('fast');
				$('.shopping-cart').removeClass('hide');
			} else {
				$('.shopping-cart').slideUp("slow");
				$('.shopping-cart').addClass('hide');
			}
		}, function() {	
			if($('.shopping-cart').hasClass('hide')) {
			} else {
				$('.shopping-cart').mouseleave(function() {
					$('.shopping-cart').slideUp("slow");
					$('.shopping-cart').addClass('hide');
				});
			}		
		});
		if($('.cart-contents .line-item-total').length) {
			var price = $('.cart-contents .line-item-total').text();
			$('.dropdown.cart span.font-bold').text(price);
		}
	}

	//Shop
	$('.list-products input.form-submit').removeClass('btn black-button font-bold font18 uppercase form-submit');
	$('.list-products input[type=submit').addClass('btn btn-primary add-to-cart form-submit');
	$('.list-products input[type=submit').val(' Add to cart');

	//Team circle
	$('.team-circle .team-social.circle').each(function(index, el) {
		$(this).find('a:first-child').addClass('so-circle');
		$(this).find('a:nth-child(2)').addClass('so-circle two');
		$(this).find('a:nth-child(3)').addClass('so-circle three');
	});

    //Onepage menu 
    if ($('nav .navbar-nav').hasClass('onemenu')) {
        $('.skroll-content section').each(function(index, el) {
            if ($(this).attr('data-title') && $(this).attr('id')) {
                var title = $(this).attr('data-title');
                $('nav .navbar-nav').append('<li><a href="#">' + title + '</a></li>');
            }
        });
        if($('.skroll-content section:last-child form').hasClass('webform-client-form')) {
        	$('nav .navbar-nav').append('<li><a href="#">Contact</a></li>');
        }
    }

    //Style layout
    jQuery('.layout-style .boxed').click(function(event) {
    	var href = jQuery('#site_layout').attr('href');
    	href = href.replace('style.css','style_boxed.css');
    	jQuery('#site_layout').attr('href', href);
    });
    jQuery('.layout-style .wide').click(function(event) {
    	var href = jQuery('#site_layout').attr('href');
    	href = href.replace('style_boxed.css','style.css');
    	jQuery('#site_layout').attr('href', href);
    });

    (function($) {
  		"use strict";	
        $('#skroll').sKroll({
            fullHeightSection: true
        });
	})(jQuery);

});

(function($) {
    "use strict";
    if($('#date_time').length) {
    	var $year = $('#date_time').data('year');
    	var $month = $('#date_time').data('month');
    	var $day = $('#date_time').data('day');
    	var $hours = $('#date_time').data('hour');
    	var $minutes = $('#date_time').data('minutes');
    	var $seconds = $('#date_time').data('second');
    	simplyCountdown('#CountdownNew', {
	        year: $year, // required
	        month: $month, // required
	        day: $day, // required
	        hours: $hours, // Default is 0 [0-23] integer
	        minutes: $minutes, // Default is 0 [0-59] integer
	        seconds: $seconds, // Default is 0 [0-59] integer
	        words: { //words displayed into the countdown
	            days: 'day',
	            hours: 'hour',
	            minutes: 'minute',
	            seconds: 'second',
	            pluralLetter: 's'
	        },
	        plural: true, //use plurals
	        inline: false, //set to true to get an inline basic countdown like : 24 days, 4 hours, 2 minutes, 5 seconds
	        inlineClass: 'simply-countdown-inline', //inline css span class in case of inline = true
	        // in case of inline set to false
	        enableUtc: true,
	        onEnd: function() {
	            // your code
	            return;
	        },
	        refresh: 1000, //default refresh every 1s
	        sectionClass: 'simply-section', //section css class
	        amountClass: 'simply-amount', // amount css class
	        wordClass: 'simply-word' // word css class
    	});
    }
})(jQuery);

(function($) {
  "use strict";	
	$(".flip").hover(function(){
  $(this).find(".card").toggleClass("flipped");
  return false;
});

$(".flip").hover(function(){
  $(this).find(".cardv").toggleClass("flippedv");
  return false;
});

})(jQuery);

function Updatecart () {
	if($('.shopping-cart .line-item-quantity-raw').text()) {
		var l = $('.shopping-cart .line-item-quantity-raw').text();
	} else {
		var l = 0;
	}	
	$('.dropdown.cart span.items').text(l);
}