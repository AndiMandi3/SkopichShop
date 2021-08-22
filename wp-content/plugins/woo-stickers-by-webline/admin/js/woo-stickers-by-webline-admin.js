(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function(){
		jQuery('.wli_color_picker').wpColorPicker();

		jQuery('.wsbw_upload_img_id').each(function(){
			var id = $(this).val();
			if( id == '' || id == 0 ) $(this).siblings('.wsbw_remove_image_button').hide();
		});
		jQuery('input:radio[class="wli-woosticker-radio"]').click(function(e){
			var val = $(this).attr("value");
			$(this).attr("checked", true);
			$(this).parent(".woo_opt").find(".wli_product_option").attr("value", val);
			//$('.custom_option').hide();
	
			if(val == 'text') {
				$(this).parents('.wli-form-general').find('.custom_optimage').css('display', 'none');
				$(this).parents('.wli-form-general').find('tr.custom_opttext').css('display', 'table-row');
				$(this).parents('.wli-form-general').find('div.custom_opttext, p.custom_opttext').css('display', 'block');

				$(this).parents('.wsbw_tab_content').find('.custom_optimage').css('display', 'none');
				$(this).parents('.wsbw_tab_content').find('tr.custom_opttext').css('display', 'table-row');
				$(this).parents('.wsbw_tab_content').find('div.custom_opttext, p.custom_opttext').css('display', 'block');
			} else if(val == 'image') {
				$(this).parents('.wli-form-general').find('.custom_opttext').css('display', 'none');
				$(this).parents('.wli-form-general').find('tr.custom_optimage').css('display', 'table-row');
				$(this).parents('.wli-form-general').find('div.custom_optimage').css('display', 'block');

				$(this).parents('.wsbw_tab_content').find('.custom_opttext').css('display', 'none');
				$(this).parents('.wsbw_tab_content').find('tr.custom_optimage').css('display', 'table-row');
				$(this).parents('.wsbw_tab_content').find('div.custom_optimage').css('display', 'block');
			}
		});
	});

	jQuery( document ).on( 'click', '.wsbw-sticker-options-wrap .nav-tab-wrapper .nav-tab', function( event ) {
		event.preventDefault();
		var $this = $(this);
		$('.nav-tab').removeClass( 'nav-tab-active' );
		$this.addClass( 'nav-tab-active' );
		jQuery( '.wsbw_tab_content' ).hide();
		jQuery( $this.attr('href') ).show();
	});

	 // Uploading files
	var file_frame;
	var $upload_btn;

	jQuery( document ).on( 'click', '.wsbw_upload_image_button', function( event ) {

		event.preventDefault();

		$upload_btn = $(this);

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.downloadable_file = wp.media({
			title: scriptsData.choose_image_title,
			button: {
				text: scriptsData.use_image_btn_text
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
			var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;
			$upload_btn.siblings( 'input.wsbw_upload_img_id' ).val( attachment.id );
			$upload_btn.parent().siblings( '.wsbw_upload_img_preview' ).find('img').attr( 'src', attachment_thumbnail.url );
			$upload_btn.siblings( '.wsbw_remove_image_button' ).show();
		});

		// Finally, open the modal.
		file_frame.open();
	});

	jQuery( document ).on( 'click', '.wsbw_remove_image_button', function() {
		var $this = $(this);
		$this.parent().siblings( '.wsbw_upload_img_preview' ).find( 'img' ).attr( 'src', scriptsData.placeholder_img_src );
		$this.siblings( '.wsbw_upload_img_id' ).val( '' );
		$this.hide();
		return false;
	});
})( jQuery );
