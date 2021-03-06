jQuery("body").on('click','span.close-msg',function(a){
	jQuery(this).parents('.acfef-message').remove()
});

jQuery("body").on('input',function(a){jQuery('.acfef-message').remove()});

acf.add_action('ready_field/type=relationship', function( $el ){	
	$el.find('.acf-button.button-primary').on('click', function(){
        relField = $el;
        
        // popup
        container = showModal($el.data('key'),$el.data('form_width'));

        getForm( $el, 'add_post' );     

    });

    jQuery('body').find($el).on('click','a.edit-post', function(event){
        event.preventDefault();
        event.stopPropagation();

        relField = $el;

        // popup
        container = showModal($el.data('key'),$el.data('form_width'));
        var post = jQuery(this).parent('span.acf-rel-item').data('id');

        getForm( $el, post );     
      
    });
    
});

jQuery('.post-slug-field input').on('input', function() {
    var c = this.selectionStart,
        r = /[`~!@#$%^&*()|+=?;:..’“'"<>,€£¥•،٫؟»«\s\{\}\[\]\\\/]+/gi,
        v = jQuery(this).val();
    if(r.test(v)) {
      jQuery(this).val(v.replace(r,'').toLowerCase());
      c--;
    }
    this.setSelectionRange(c, c);
  }); 

jQuery('body').on('click', 'button.edit-password', function(){
    jQuery(this).addClass('acfef-hidden').siblings('.pass-strength-result').removeClass('acfef-hidden').parents('.acf-field-password').removeClass('edit_password').addClass('editing_password').next('.acf-field-password').removeClass('edit_password');
    jQuery(this).after('<input type="hidden" name="edit_user_password" value="1"/>');
});
jQuery('body').on('click', 'button.cancel-edit', function(){
	jQuery(this).siblings('button.edit-password').removeClass('acfef-hidden').siblings('.pass-strength-result').addClass('acfef-hidden').parents('.acf-field-password').addClass('edit_password').removeClass('editing_password').next('.acf-field-password').addClass('edit_password');
	jQuery(this).parents('acf-input-wrap').siblings('acf-notice');
    jQuery(this).siblings('input[name=edit_user_password]').remove();
});

function showModal( $key, $width ){
    var modal = jQuery('#modal_'+$key);
    if(modal.length){
        modal.removeClass('hide').addClass('show');
    }else{
        modal = jQuery('<div id="modal_' + $key + '" class="modal edit-modal show"><div class="modal-content" style="width:' + $width + 'px;max-width:80%"><div class="modal-inner"><span onClick="closeModal(\'' + $key + '\',\'clear\')" class="acf-icon -cancel close"></span><div class="content-container"><div class="loading"><span class="acf-loading"></span></div></div></div></div></div>');
        jQuery('body').append(modal);
    }
    return modal;
}

function getForm( $el, $form_action ){
    var ajaxData = {
        action:		'acfef/fields/relationship/add_form',
        field_key:	$el.data('key'),
        parent_form: $el.parents('form').attr('id'),
        form_action: $form_action,
    };
    // get HTML
    jQuery.ajax({
        url: acf.get('ajaxurl'),
        data: acf.prepareForAjax(ajaxData),
        type: 'post',
        dataType: 'html',
        success: showForm
    });
}

function showForm( html ){	
    
    // update popup
    container.find('.content-container').html(html);  
    acf.do_action('append',container);  
};
var steps = [];
jQuery('body').on('submit','form.acfef-form', function (event) {
    event.preventDefault();
    $form = jQuery(this);
    this.blur();
    $form.find('.acf-spinner').css('display','block');

    args = {
        form: $form,
        reset: false,
        success: function ($form) {
            let $fileInputs = jQuery('input[type="file"]:not([disabled])', $form)
            $fileInputs.each(function (i, input) {
                if (input.files.length > 0) {
                    return;
                }
                jQuery(input).prop('disabled', true);
            })

            var formData = new FormData($form[0]);          
            formData.append('action','acfef/form_submit');

            // Re-enable empty file $fileInputs
            $fileInputs.prop('disabled', false);

            acf.lockForm($form);

           jQuery.ajax({
              url: acf.get('ajaxurl'),
              type: 'post',
              data: formData,
              cache: false,
              processData: false,
			  contentType: false,
			  error: function(response){
				console.log(response);
			  },
              success: function(response){
                if(response.success) {
                  if( response.data.redirect ){
                    window.location=response.data.redirect;
                  }else{
                    acf.unlockForm($form);

                    successMessage='<div class="acfef-message"><div class="acf-notice -success acf-success-message"><p class="success-msg">'+response.data.update_message+'</p><span class="acfef-dismiss close-msg acf-notice-dismiss acf-icon -cancel small"></span></div></div>';
					if(response.data.reload_form){
						var widget = $form.data('widget');
						$form.replaceWith(response.data.reload_form);
						acf.do_action('append',jQuery('.elementor-element-' + widget));
					}
                    if(response.data.append){
                      var postData = response.data.append; 
                      if(postData.action == 'edit'){
                          relField.find('div.values').find('span[data-id='+postData.id+']').html(postData.text+'<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>');
                          relField.find('div.choices').find('span[data-id='+postData.id+']').html(postData.text);
                      }else{
                          relField.find('div.values ul').append('<li><input type="hidden" name="acf[' + relField.data('key') + '][]" value="' + postData.id + '" /><span data-id="' + postData.id + '" class="acf-rel-item">' + postData.text + '<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a></span></li>');
                      }                    
                      $form.replaceWith(successMessage);
                      jQuery('body').find('#modal_' + response.data.field_key).delay(400).remove();                    
                    }else{
                      $form.find('.acf-spinner').css('display','none');
					  $form.before(successMessage).find('.acfef-submit-button').attr('disabled',false).removeClass('acf-hidden'); 
					  if(response.data.clear_form){
						jQuery('body, html').animate({scrollTop:$form.offset({top:50})},2000);
						var widget=$form.data('widget');
						$form.replaceWith(response.data.clear_form);
						acf.do_action('append',jQuery('.elementor-element-' + widget))
					  }
                    } 
				  }
                }
              }, 
            });  

        }
    }

    acf.validateForm(args);
});
  

  jQuery(document).ready(function(){
    var dynamicValueFields = jQuery('div[data-default]');
    jQuery.each( dynamicValueFields, function( key, value ){
        var fieldElement = jQuery(value);
        var fieldSources = fieldElement.data('default');
        var fieldDynamicValue = fieldElement.data('dynamic_value');
        var fieldInput = fieldElement.find('input[type=text]');
        if( fieldSources.length > 0 ){
            var inputValue = fieldDynamicValue;

            jQuery.each( fieldSources, function( index, fieldName ){
                var fieldData = acfef_get_field_data(fieldName);               
                var sourceInput = acfef_get_field_element(fieldData[0], false);
                inputValue = acfef_get_field_input_value(inputValue, fieldData, sourceInput); 
                var sourceInput = acfef_get_field_element(fieldData[0], true);  
                sourceInput.on('input', function(){
                  var returnValue = fieldDynamicValue;
                  jQuery.each( fieldSources, function( index, fieldName ){
                    var fieldData = acfef_get_field_data(fieldName);               
                    var sourceInput = acfef_get_field_element(fieldData[0], false);
                    returnValue = acfef_get_field_input_value(returnValue, fieldData, sourceInput);
                  });
                  fieldInput.val(returnValue);
                });      
                
            });
            fieldInput.val(inputValue);
            
            function acfef_get_field_input_value(returnValue, fieldData, sourceInput){
              var shortcode = '['+fieldData[0]+']';
              if( sourceInput.val() != '' ){
                var display = sourceInput.val();
                if(fieldData[1] == 'text'){
                  var display = acfef_get_field_text(fieldData[0]);
                }
                returnValue = returnValue.replace(shortcode, display);
              }
              return returnValue;
            }
  
            function acfef_get_field_element(fieldName, all){
                var sourceField = fieldElement.siblings('div[data-name=' + fieldName + ']');
                var sourceInput = sourceField.find('input');
                if(sourceField.data('type') == 'radio'){
                    if(all == true){
                      sourceInput = sourceField.find('input');
                    }else{
                      sourceInput = sourceField.find('input:selected');
                    }
                }
                if(sourceField.data('type') == 'select'){
                    sourceInput = sourceField.find('select');
                }    
                return sourceInput;
            }
            function acfef_get_field_data(fieldName){
              var fieldData = [ fieldName, 'value' ];
              if (~fieldName.indexOf(':')){
                var fieldData = fieldName.split(':');
              }

              return fieldData;
            }
            function acfef_get_field_text(fieldName){
              var sourceField = fieldElement.siblings('div[data-name=' + fieldName + ']');
              if(sourceField.data('type') == 'radio'){
                  sourceInput = sourceField.find('.selected').text();
              }
              if(sourceField.data('type') == 'select'){
                  sourceInput = sourceField.find(':selected').text();
              }    
              return sourceInput;
            }
        }
     });
});

(function($){

	var Field = acf.Field.extend({
		
		type: 'upload_images',
		
		events: {
			'click .acf-gallery-add':			'onClickAdd',
			'click div.acf-gallery-upload':		'onClickUpload',
			'click .acf-gallery-edit':			'onClickEdit',
			'click .acf-gallery-remove':		'onClickRemove',
			'click .acf-gallery-attachment': 	'onClickSelect',
			'click .acf-gallery-close': 		'onClickClose',
			'change .acf-gallery-sort': 		'onChangeSort',
			'click .acf-gallery-update': 		'onUpdate',
			'mouseover': 						'onHover',
			'showField': 						'render',
			'input .images-preview': 			'imagesPreview',
		},
		
		actions: {
			'validation_begin': 	'onValidationBegin',
			'validation_failure': 	'onValidationFailure',
			'resize':				'onResize'
		},
		
		onValidationBegin: function(){
			acf.disable( this.$sideData(), this.cid );
		},
		
		onValidationFailure: function(){
			acf.enable( this.$sideData(), this.cid );
		},
		
		$control: function(){
			return this.$('.acf-gallery');
		},
		
		$collection: function(){
			return this.$('.acf-gallery-attachments');
		},
		
		$attachments: function(){
			return this.$('.acf-gallery-attachment:not(.not-valid)');
		},

		$clone: function(){
			return this.$('.image-preview-clone');
		},
		
		$attachment: function( id ){
			return this.$('.acf-gallery-attachment[data-id="' + id + '"]');
		},
		
		$active: function(){
			return this.$('.acf-gallery-attachment.active');
		},

		$inValid: function(){
			return this.$('.acf-gallery-attachment.not-valid');
		},
		
		$main: function(){
			return this.$('.acf-gallery-main');
		},
		
		$side: function(){
			return this.$('.acf-gallery-side');
		},
		
		$sideData: function(){
			return this.$('.acf-gallery-side-data');
		},
		
		isFull: function(){
			var max = parseInt( this.get('max') );
			var count = this.$attachments().length;
			return ( max && count >= max );
		},
		
		getValue: function(){
			
			// vars
			var val = [];
			
			// loop
			this.$attachments().each(function(){
				val.push( $(this).data('id') );
			});
			
			// return
			return val.length ? val : false;
		},
		
		addUnscopedEvents: function( self ){
			
			// invalidField
			this.on('change', '.acf-gallery-side', function(e){
				self.onUpdate( e, $(this) );
			});
		},
		
		addSortable: function( self ){
			
			// add sortable
			this.$collection().sortable({
				items: '.acf-gallery-attachment',
				forceHelperSize: true,
				forcePlaceholderSize: true,
				scroll: true,
				start: function (event, ui) {
					ui.placeholder.html( ui.item.html() );
					ui.placeholder.removeAttr('style');
	   			},
	   			update: function(event, ui) {
					self.$input().trigger('change');
		   		}
			});
			
			// resizable
			this.$control().resizable({
				handles: 's',
				minHeight: 200,
				stop: function(event, ui){
					acf.update_user_setting('gallery_height', ui.size.height);
				}
			});
		},
		
		initialize: function(){
			
			// add unscoped events
			this.addUnscopedEvents( this );
			
			// render
			this.render();
		},
		
		render: function(){
			
			// vars
			var $sort = this.$('.acf-gallery-sort');
			var $add = this.$('.acf-gallery-add');
			var count = this.$attachments().length;
			
			// disable add
			if( this.isFull() ) {
				$add.addClass('disabled');
			} else {
				$add.removeClass('disabled');
			}
			
			// disable select
			if( !count ) {
				$sort.addClass('disabled');
			} else {
				$sort.removeClass('disabled');
			}
			
			// resize
			this.resize();
		},
		
		resize: function(){
			
			// vars
			var width = this.$control().width();
			var target = 150;
			var columns = Math.round( width / target );
						
			// max columns = 8
			columns = Math.min(columns, 8);
			
			// update data
			this.$control().attr('data-columns', columns);
		},
		
		onResize: function(){
			this.resize();
		},
		
		openSidebar: function(){
			
			// add class
			this.$control().addClass('-open');
			
			// hide bulk actions
			// should be done with CSS
			//this.$main().find('.acf-gallery-sort').hide();
			
			// vars
			var width = this.$control().width() / 3;
			width = parseInt( width );
			width = Math.max( width, 350 );
			
			// animate
			this.$('.acf-gallery-side-inner').css({ 'width' : width-1 });
			this.$side().animate({ 'width' : width-1 }, 250);
			this.$main().animate({ 'right' : width }, 250);
		},
		
		closeSidebar: function(){
			
			// remove class
			this.$control().removeClass('-open');
			
			// clear selection
			this.$active().removeClass('active');
			
			// disable sidebar
			acf.disable( this.$side() );
			
			// animate
			var $sideData = this.$('.acf-gallery-side-data');
			this.$main().animate({ right: 0 }, 250);
			this.$side().animate({ width: 0 }, 250, function(){
				$sideData.html('');
			});
		},
		
		onClickAdd: function( e, $el ){

			this.$control().css('height','400');

			// validate
			if( this.isFull() ) {
				this.showNotice({
					text: acf.__('Maximum selection reached'),
					type: 'warning'
				});
				return;
			}
			
			// new frame
			var frame = acf.newMediaPopup({
				mode:			'select',
				title:			acf.__('Add Image to Gallery'),
				field:			this.get('key'),
				multiple:		'add',
				library:		this.get('library'),
				allowedTypes:	this.get('mime_types'),
				selected:		this.val(),
				select:			$.proxy(function( attachment, i ) {
					this.appendAttachment( attachment, i );
				}, this)
			});
		},
		imagesPreview: function( e, $el ){
			this.$control().css('height','400');
	
			var numAttachments = this.$attachments().length;
			var maxNum = this.$control().data('max');
	
			const files = e.currentTarget.files;
			Object.keys(files).forEach(i=>{
				if(maxNum>0 && numAttachments>=maxNum){
					return false;
				}
				const file = files[i];
				const reader = new FileReader();
				reader.onload = (e) => {
					var container = this.$clone().clone();
					container.removeClass('acf-hidden image-preview-clone').addClass('acf-gallery-attachment acf-uploading').find('img').attr('src',reader.result);
					container.appendTo(this.$collection());
					this.uploadImage(file,container);
				}
				numAttachments++;
				reader.readAsDataURL(e.target.files[i]);
			});
			if(numAttachments>=maxNum && maxNum>0){
				this.$('input.images-preview').prop('disabled',true);
			}
		},

		uploadImage: function(file,container){
			var progPrc = container.find('.uploads-progress .percent');
			var progBar = container.find('.uploads-progress .bar');
			progPrc.text('33%');
			progBar.css('width','33%');
			var nonce = this.$el.parents('form').find('input[name=_acf_nonce]').val();
			var fieldKey = this.get('key');
			var fileData = new FormData();
			fileData.append('action','acf/fields/upload_images/add_attachment');
			fileData.append('file',file);
			fileData.append('field_key',fieldKey);
			fileData.append('nonce',nonce);
			
			$.ajax({
				url: acf.get('ajaxurl'),
				data: acf.prepareForAjax(fileData),
				type: 'post',
				processData: false, 
				contentType: false, 
				cache: false
			}).done(function(response){
				if(response.success){
					progPrc.text('100%');
					progBar.css('width','100%');
					container.attr('data-id',response.data.id).find('.acf-gallery-remove').attr('data-id',response.data.id);
					var idInput = $('<input>').attr({
						type:"hidden",
						name:"acf["+fieldKey+"][]",
						value:response.data.id
					});
					container.prepend(idInput).removeClass('acf-uploading');
					setTimeout(function(){
						container.find('.uploads-progress').remove();
					  }, 5000);
				}else{
					container.find('.uploads-progress').remove();
					container.addClass('not-valid').append('<p class="errors">'+response.data+'</p>').find('.margin').append('<p class="upload-fail">x</p>');
				}
			  } 
			);
		},

		onClickUpload: function( e, $el ){
						
			// validate
			if( this.isFull() ) {
				this.showNotice({
					text: acf.__('Maximum selection reached: '+this.$control().data('max')),
					type: 'warning'
				});
				return;
			}
			this.$inValid().remove();

			
		},
		
		appendAttachment: function( attachment, i ){
			
			// vars
			attachment = this.validateAttachment( attachment );
			
			// bail early if is full
			if( this.isFull() ) {
				return;
			}
			
			// bail early if already exists
			if( this.$attachment( attachment.id ).length ) {
				return;
			}
			
			// html
			var html = [
			'<div class="acf-gallery-attachment" data-id="' + attachment.id + '">',
				'<input type="hidden" value="' + attachment.id + '" name="' + this.getInputName() + '[]">',
				'<div class="margin" title="">',
					'<div class="thumbnail">',
						'<img src="" alt="">',
					'</div>',
					'<div class="filename"></div>',
				'</div>',
				'<div class="actions">',
					'<a href="#" class="acf-icon -cancel dark acf-gallery-remove" data-id="' + attachment.id + '"></a>',
				'</div>',
			'</div>'].join('');
			var $html = $(html);
			
			// append
			this.$collection().append( $html );
			
			// move to beginning
			if( this.get('insert') === 'prepend' ) {
				var $before = this.$attachments().eq( i );
				if( $before.length ) {
					$before.before( $html );
				}
			}
			
			// render attachment
			this.renderAttachment( attachment );
			
			// render
			this.render();
			
			// trigger change
			this.$input().trigger('change');
		},
		
		validateAttachment: function( attachment ){
			
			// defaults
			attachment = acf.parseArgs(attachment, {
				id: '',
				url: '',
				alt: '',
				title: '',
				filename: '',
				type: 'image'
			});
			
			// WP attachment
			if( attachment.attributes ) {
				attachment = attachment.attributes;
				
				// preview size
				var url = acf.isget(attachment, 'sizes', this.get('preview_size'), 'url');
				if( url !== null ) {
					attachment.url = url;
				}
			}
			
			// return
			return attachment;
		},
		
		renderAttachment: function( attachment ){
			
			// vars
			attachment = this.validateAttachment( attachment );
			
			// vars
			var $el = this.$attachment( attachment.id );
			
			// Image type.
			if( attachment.type == 'image' ) {
				
				// Remove filename.
				$el.find('.filename').remove();
			
			// Other file type.	
			} else {	
				
				// Check for attachment featured image.
				var image = acf.isget(attachment, 'image', 'src');
				if( image !== null ) {
					attachment.url = image;
				}
				
				// Update filename text.
				$el.find('.filename').text( attachment.filename );
			}
			
			// Default to mimetype icon.
			if( !attachment.url ) {
				attachment.url = acf.get('mimeTypeIcon');
				$el.addClass('-icon');
			}
			
			// update els
		 	$el.find('img').attr({
			 	src:	attachment.url,
			 	alt:	attachment.alt,
			 	title:	attachment.title
			});
		 	
			// update val
		 	acf.val( $el.find('input'), attachment.id );
		},
		
		editAttachment: function( id ){
			
			// new frame
			var frame = acf.newMediaPopup({
				mode:		'edit',
				title:		acf.__('Edit Image'),
				button:		acf.__('Update Image'),
				attachment:	id,
				field:		this.get('key'),
				select:		$.proxy(function( attachment, i ) {
					this.renderAttachment( attachment );
					// todo - render sidebar
				}, this)
			});
		},
		
		onClickEdit: function( e, $el ){
			var id = $el.data('id');
			if( id ) {
				this.editAttachment( id );
			}
		},
		
		removeAttachment: function( id ){
			
			// close sidebar (if open)
			this.closeSidebar();
			
			// remove attachment
			this.$attachment( id ).remove();
			
			// render
			this.render();
			
			// trigger change
			this.$input().trigger('change');
		},
		
		onClickRemove: function( e, $el ){
			
			// prevent event from triggering click on attachment
			e.preventDefault();
			e.stopPropagation();
			
			//remove
			var id = $el.data('id');
			if( id ) {
				this.removeAttachment( id );
			}else{
				$el.parents('.acf-gallery-attachment').remove();     
			}
			var numAttachments = this.$attachments().length;
			var maxNum = this.$control().data('max');
			if(numAttachments<maxNum){
				this.$('input.images-preview').prop('disabled',false);
			}
		},
		
		selectAttachment: function( id ){
			
			// vars
			var $el = this.$attachment( id );
			
			// bail early if already active
			if( $el.hasClass('active') ) {
				return;
			}
			
			// step 1
			var step1 = this.proxy(function(){
				
				// save any changes in sidebar
				this.$side().find(':focus').trigger('blur');
				
				// clear selection
				this.$active().removeClass('active');
				
				// add selection
				$el.addClass('active');
				
				// open sidebar
				this.openSidebar();
				
				// call step 2
				step2();
			});
			
			// step 2
			var step2 = this.proxy(function(){
				
				// ajax
				var ajaxData = {
					action: 'acf/fields/gallery/get_attachment',
					field_key: this.get('key'),
					id: id
				};
				
				// abort prev ajax call
				if( this.has('xhr') ) {
					this.get('xhr').abort();
				}
				
				// loading
				acf.showLoading( this.$sideData() );
				
				// get HTML
				var xhr = $.ajax({
					url: acf.get('ajaxurl'),
					data: acf.prepareForAjax(ajaxData),
					type: 'post',
					dataType: 'html',
					cache: false,
					success: step3
				});
				
				// update
				this.set('xhr', xhr);
			});
			
			// step 3
			var step3 = this.proxy(function( html ){
				
				// bail early if no html
				if( !html ) {
					return;
				}
				
				// vars
				var $side = this.$sideData();
				
				// render
				$side.html( html );
				
				// remove acf form data
				$side.find('.compat-field-acf-form-data').remove();
				
				// merge tables
				$side.find('> table.form-table > tbody').append( $side.find('> .compat-attachment-fields > tbody > tr') );	
								
				// setup fields
				acf.doAction('append', $side);
			});
			
			// run step 1
			step1();
		},
		
		onClickSelect: function( e, $el ){
			var id = $el.data('id');
			if( id ) {
				this.selectAttachment( id );
			}
		},
		
		onClickClose: function( e, $el ){
			this.closeSidebar();
		},
		
		onChangeSort: function( e, $el ){
			
			// Bail early if is disabled.
			if( $el.hasClass('disabled') ) {
				return;
			}
			
			// Get sort val.
			var val = $el.val();
			if( !val ) {
				return;
			}
			
			// find ids
			var ids = [];
			this.$attachments().each(function(){
				ids.push( $(this).data('id') );
			});
			
			
			// step 1
			var step1 = this.proxy(function(){
				
				// vars
				var ajaxData = {
					action: 'acf/fields/gallery/get_sort_order',
					field_key: this.get('key'),
					ids: ids,
					sort: val
				};
				
				
				// get results
			    var xhr = $.ajax({
			    	url:		acf.get('ajaxurl'),
					dataType:	'json',
					type:		'post',
					cache:		false,
					data:		acf.prepareForAjax(ajaxData),
					success:	step2
				});
			});
			
			// step 2
			var step2 = this.proxy(function( json ){
				
				// validate
				if( !acf.isAjaxSuccess(json) ) {
					return;
				}
				
				// reverse order
				json.data.reverse();
				
				// loop
				json.data.map(function(id){
					this.$collection().prepend( this.$attachment(id) );
				}, this);
			});
			
			// call step 1
			step1();
		},
		
		onUpdate: function( e, $el ){
			
			// vars
			var $submit = this.$('.acf-gallery-update');
			
			// validate
			if( $submit.hasClass('disabled') ) {
				return;
			}
			
			// serialize data
			var ajaxData = acf.serialize( this.$sideData() );
			
			// loading
			$submit.addClass('disabled');
			$submit.before('<i class="acf-loading"></i> ');
			
			// append AJAX action		
			ajaxData.action = 'acf/fields/gallery/update_attachment';
			
			// ajax
			$.ajax({
				url: acf.get('ajaxurl'),
				data: acf.prepareForAjax(ajaxData),
				type: 'post',
				dataType: 'json',
				complete: function(){
					$submit.removeClass('disabled');
					$submit.prev('.acf-loading').remove();
				}
			});
		},
		
		onHover: function(){
			
			// add sortable
			this.addSortable( this );
			
			// remove event
			this.off('mouseover');
		}
	});
	
	acf.registerFieldType( Field );
	
	// register existing conditions
	acf.registerConditionForFieldType('hasValue', 'gallery');
	acf.registerConditionForFieldType('hasNoValue', 'gallery');
	acf.registerConditionForFieldType('selectionLessThan', 'gallery');
	acf.registerConditionForFieldType('selectionGreaterThan', 'gallery');
	
})(jQuery);

 jQuery('body').on('input', 'input.image-preview', function(e){
	var reader = new FileReader();
	var file_input = jQuery(this);
	reader.onload = function()
	{
	file_input.parents('.hide-if-value').addClass('acfef-hidden').siblings('.show-if-value').addClass('show').find('img').attr('src',reader.result);
	}
	imagePreview = true;
	reader.readAsDataURL(e.target.files[0]);
   });
   
   jQuery('body').on('click','a[data-name=remove]',function(e){
	   if( typeof imagePreview != undefined ){
		   jQuery(this).parents('.show-if-value').removeClass('show').siblings('.hide-if-value').removeClass('acfef-hidden').find('input.image-preview').val('');
	   }
   });   
   
   (function($, undefined){
	   
	   var Field = acf.models.ImageField.extend({
		   
		   type: 'upload_image',
	   })
	   acf.registerFieldType( Field );
	   
   })(jQuery);

jQuery("body").on('click','.acfef-prev-button',function(e){
	var form =jQuery(this).parents('form');
	var widget = form.data('widget');
	var formData = form.find('input[name=_acf_form]').val();
	var ajaxData = {
        action:		'acfef/forms/multi_step/change_step',
        form_data:	formData,
    };
    // get HTML
    jQuery.ajax({
        url: acf.get('ajaxurl'),
        data: acf.prepareForAjax(ajaxData),
        type: 'post',
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.data.reload_form){
				form.replaceWith(response.data.reload_form);
				acf.do_action('append',jQuery('.elementor-element-'+widget))
			}
		}
    });
});
jQuery("body").on('click','.change-step',function(e){
	var form =jQuery(this).parents('form');
	var widget = form.data('widget');
	var formData = form.find('input[name=_acf_form]').val();
	var ajaxData = {
		action:		'acfef/forms/multi_step/change_step',
		form_data:	formData,
		step: jQuery(this).data('step'),
    };
    // get HTML
    jQuery.ajax({
        url: acf.get('ajaxurl'),
        data: acf.prepareForAjax(ajaxData),
        type: 'post',
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.data.reload_form){
				form.replaceWith(response.data.reload_form);
				acf.do_action('append',jQuery('.elementor-element-'+widget))
			}
		}
    });
});


jQuery(document).on('elementor/popup/show',(event, id, instance)=>{
	acf.do_action('append',jQuery('#elementor-popup-modal-' + id))
});
	
jQuery(".acfef-draft-button").click(function(a){
	window.acf.validation.active=!1
});


