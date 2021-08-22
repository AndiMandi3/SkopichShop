jQuery(function(){
	jQuery('.rr_review_text').each(function(event){
		var max_length = 150;

		if ( typeof php_vars == 'object') {
			if (typeof php_vars.excerpt_length == 'string') {
				custom_length = parseInt(php_vars.excerpt_length);
				max_length = custom_length;
			}
		}

		if (jQuery(this).html().length > max_length){
			var short_content 	= jQuery(this).html().substr(0,max_length);
			var long_content	= jQuery(this).html().substr(max_length);
			jQuery(this).html(short_content+'<span class="ellipses">... </span><a href="#" class="read_more"><br />'+translation.read_more+'</a>'+'<span class="more_text" style="display:none;">'+long_content+' <br /><a href="#" class="show_less" style="display:none;">'+translation.less+'</a></span>');
			jQuery(this).find('a.read_more').click(function(event){
				event.preventDefault();
				jQuery(this).hide();
				jQuery(this).parents('.rr_review_text').find('span.ellipses').hide();
				jQuery(this).parents('.rr_review_text').find('.more_text').show();
				jQuery(this).parents('.rr_review_text').find('a.show_less').show();
				console.log("culprit");
			});
			jQuery(this).find('a.show_less').click(function(event){
				event.preventDefault();
				jQuery(this).hide();
				jQuery(this).parents('.rr_review_text').find('.ellipses').show();
				jQuery(this).parents('.rr_review_text').find('.more_text').hide();
				jQuery(this).parents('.rr_review_text').find('a.read_more').show();
			});
		}
	});

	jQuery('.rr_star').hover(function() {
		renderStarRating(parseInt(jQuery(this).attr('id').charAt(8)));
	}, function() {
		renderStarRating(parseInt(jQuery('#rRating').val()));
	});

	jQuery('.rr_star').click(function() {
		jQuery('#rRating').val(jQuery(this).attr('id').charAt(8));
	});
	jQuery('select[id=markup_content_type]').change(function() {
        
    jQuery("select[id=markup_content_type] option:selected").each(function() {
	     var value = jQuery(this).val();
	     if (value == "Organization") {
	     	jQuery('tr:nth-last-child(6)').hide();
	        jQuery('tr:nth-last-child(5)').hide();
	        jQuery('tr:nth-last-child(4)').hide();
	        jQuery('tr:nth-last-child(3)').hide();
	        jQuery('tr:nth-last-child(2)').hide();
	        jQuery('tr:nth-last-child(1)').hide();
	        jQuery('#submit').attr('disabled', false);
	     
	     } else if (value == "Local business") {
	     	jQuery('tr:nth-last-child(6)').show();
	        jQuery('tr:nth-last-child(5)').show();
	        jQuery('tr:nth-last-child(4)').show();
	        jQuery('tr:nth-last-child(3)').show();
	        jQuery('tr:nth-last-child(2)').show();
	        jQuery('tr:nth-last-child(1)').hide();
	        jQuery('#submit').attr('disabled', false);
	        
	     } else if (value == "Product") {
	     	jQuery('tr:nth-last-child(6)').hide();
	        jQuery('tr:nth-last-child(5)').hide();
	        jQuery('tr:nth-last-child(4)').hide();
	        jQuery('tr:nth-last-child(3)').hide();
	        jQuery('tr:nth-last-child(2)').hide();
	        jQuery('tr:nth-last-child(1)').show();
	        jQuery("tr:nth-last-child(1) p:last").addClass("markup_price_product");
	        jQuery('#submit').attr('disabled', 'disabled');
	        if(jQuery('#markup_price_product').is(":checked")){
               jQuery('#submit').attr('disabled', false); 
            }
            else if(jQuery('#markup_price_product').is(":not(:checked)")){
                jQuery('#submit').attr('disabled', 'disabled'); 
            }
	     }
	   });
    }).change();
});
jQuery(document).ready(function(){
        jQuery('#markup_price_product').click(function(){
            if(jQuery(this).is(":checked")){
               jQuery('#submit').attr('disabled', false); 
            }
            else if(jQuery(this).is(":not(:checked)")){
                jQuery('#submit').attr('disabled', 'disabled'); 
            }
        });
    });

function renderStarRating(rating) {
	for (var i=1; i<=5; i++) {
		jQuery('#rr_star_'+i).removeClass('glyphicon-star');
		jQuery('#rr_star_'+i).removeClass('glyphicon-star-empty');
		if (i<=rating) {
			jQuery('#rr_star_'+i).addClass('glyphicon-star');
		} else {
			jQuery('#rr_star_'+i).addClass('glyphicon-star-empty');
		}
	}
}