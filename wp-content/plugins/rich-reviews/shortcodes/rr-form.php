<?php
function rr_do_review_form($atts, $options, $sqltable) {
	global $post;
	extract(shortcode_atts(
		array(
			'category' => 'none',
		)
	,$atts));

	//initialize all data vars
	$rName  = '';
	$rEmail = '';
	$rTitle = '';
	$rText  = '';
	$output = '';
	$displayForm = true;
	$posted = false;
	$errors = array(
		'name'	=>	'',
		'email'	=>	'',
		'title'	=>	'',
		'rating'	=>	'',
		'content'=>	''
	);

	$newData = array(
		'reviewer_name'   => $rName,
		'reviewer_email'  => $rEmail,
		'review_title'    => $rTitle,
		'review_text'     => $rText,
		'errors'		  => $errors
	);

	if (isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'rr_review_submission_' . $post->ID )) {
        $posted  = true;
        
        $incomingData = $_POST;
        $incomingData = apply_filters('rr_process_form_data', $incomingData);

		if ( isset($_POST['org']) && !empty($_POST['org']) ) {
			$options['is-spam'] = true;
		}
        if (isset($_POST['rName']) && isset($options['form-name-display']) && $options['form-name-display'] == "checked") {
            $rName     = fp_sanitize($_POST['rName']);
        }
        if (isset($_POST['rEmail']) && isset($options['form-email-display']) && $options['form-email-display'] == "checked") {
            $rEmail    = fp_sanitize($_POST['rEmail']);
        }
        if (isset($_POST['rTitle']) && isset($options['form-title-display']) && $options['form-title-display'] == "checked") {
            $rTitle    = fp_sanitize($_POST['rTitle']);
        }
        $rRating   = fp_sanitize($_POST['rRating']);
        if (isset($_POST['rText']) && isset($options['form-content-display']) && $options['form-content-display'] == "checked") {
            $rText     = fp_sanitize($_POST['rText']);
        }

        $rDateTime = date('Y-m-d H:i:s');
        $incomingData['rDateTime'] = $rDateTime;
        $rStatus   = 1;
        if (isset($options['require_approval']) && $options['require_approval'] == "checked") {$rStatus   = 0;}
        $rIP       = $_SERVER['REMOTE_ADDR'];
        $rPostID   = $post->ID;
        $rCategory = fp_sanitize($category);

        $newData = array (

                'date_time'       => $rDateTime,
                'reviewer_name'   => $rName,
                'reviewer_email'  => $rEmail,
                'review_title'    => $rTitle,
                'review_rating'   => $rRating,
                'review_text'     => $rText,
                'review_status'   => $rStatus,
                'reviewer_ip'     => $rIP,
                'post_id'		  => $rPostID,
                'review_category' => $rCategory,
                'isValid'		  => true,
                'errors'		  => $errors

        );


        $newData = apply_filters('rr_check_required', $newData);
        if ($newData['isValid']) {
            $newData = apply_filters('rr_misc_validation', $newData);
        }
        if ($newData['isValid']) {
	        $displayForm   = false;
	        $newSubmission = array(
		        'date_time'       => $newData['date_time'],
		        'reviewer_name'   => $newData['reviewer_name'],
		        'reviewer_email'  => $newData['reviewer_email'],
		        'review_title'    => $newData['review_title'],
		        'review_rating'   => intval( $newData['review_rating'] ),
		        'review_text'     => $newData['review_text'],
		        'review_status'   => $newData['review_status'],
		        'reviewer_ip'     => $newData['reviewer_ip'],
		        'post_id'         => $newData['post_id'],
		        'review_category' => $newData['review_category'],
	        );

	        do_action( 'rr_on_valid_data', $newSubmission, $options, $sqltable );
        }
	} else {
		?> <span id="state"></span> <?php
	}
	if ($displayForm) {

			$errors = $newData['errors'];
			$errors = generate_error_text($errors, $options);

		?>
		<form action="" method="post" enctype="multipart/form-data" class="rr_review_form" id="fprr_review_form">
            <?php wp_nonce_field( 'rr_review_submission_' . $post->ID ); ?>
			<input type="hidden" name="rRating" id="rRating" value="0" />
			<table class="form_table">
			<?php do_action('rr_do_form_fields', $options, $newData, $errors); ?>
                <tr class="rr_form_row rr-honeydo">
                    <td>
                        <label class="rr-honeydo" for="org"></label>
                    </td>
                    <td>
                        <input class="rr-honeydo" autocomplete="off" type="text" id="org" name="org" placeholder="Your Organization">
                    </td>
                </tr>
				<tr class="rr_form_row">
					<td></td>
					<td class="rr_form_input"><input id="submitReview" name="submitButton" type="submit" value="<?php echo $options['form-submit-text']; ?>"/></td>
				</tr>
			</table>
		</form>
	<?php



	}
	do_action('rr_set_local_scripts');
}
function generate_error_text($errors, $options) {

	$processed = array();
	foreach($errors as $key => $val) {
		$option_key = 'form-' . $key . '-label';
		$label = $options[$option_key];
		if($val == 'absent required') {
			$processed[$key] = 'The ' . $label . ' field is required.';
		} else if ($val == 'invalid input') {
			$processed[$key] = 'Please enter a valid ' . $label;
		} else if ($val == 'length violation') {
			$processed[$key] = 'The ' . $label . ' that you entered is too long.';
		} else {
			$processed[$key] = '';
		}
	}
	return $processed;
}

function sanitize_incoming_data($incomingData) {

	foreach($incomingData as $field => $val) {
		$incomingData[$field] = fp_sanitize($val);
	}
	return $incomingData;
}

function rr_do_rating_field($options, $errors = null) {	
	$error = (isset($errors['rating'])) ? $errors['rating'] : null;
	$label = $options['form-rating-label'];
	@include RR_PLUGIN_PATH . 'views/frontend/form/rr-star-input.php';
}

function render_custom_styles($options) {
	?>
	<style>
		.stars, .rr_star {
			color: <?php echo $options['star_color']?>;
		}
	</style>
	<?php
}

function rr_do_name_field($options, $rData = null, $errors = null) {
	$inputId = 'Name';
	$require = false;
	$rFieldValue = '';
	$error = $errors['name'];
	$label = $options['form-name-label'];
	if(isset($options['form-name-require']) && $options['form-name-require'] == "checked") {
		$require = true;
	}
	if($rData['reviewer_name']) {
		$rFieldValue = $rData['reviewer_name'];
	}

	@include RR_PLUGIN_PATH . RR_TEXT_INPUT_PATH;
}

function rr_do_email_field($options, $rData = null, $errors = null) {
	$inputId = 'Email';
	$require = false;
	$rFieldValue = '';
	$error = $errors['email'];
	$label = $options['form-email-label'];
	if(isset($options['form-email-require']) && $options['form-email-require'] == "checked") {
		$require = true;
	}
	if($rData['reviewer_email']) {
		$rFieldValue = $rData['reviewer_email'];
	}

	@include RR_PLUGIN_PATH . RR_TEXT_INPUT_PATH;
	
}

function rr_do_title_field($options, $rData = null, $errors = null) {
	$inputId = 'Title';
	$require = false;
	$rFieldValue = '';
	$error = $errors['title'];
	$label = $options['form-title-label'];
	if(isset($options['form-title-require']) && $options['form-title-require'] == "checked") {
		$require = true;
	}
	if($rData['review_title']) {
		$rFieldValue = $rData['review_title'];
	}

	@include RR_PLUGIN_PATH . RR_TEXT_INPUT_PATH;	
}

function rr_do_content_field($options, $rData = null, $errors = null) {	
	$inputId = 'Text';
	$require = false;
	$rFieldValue = '';
	$error = $errors['content'];
	$label = $options['form-content-label'];
	if(isset($options['form-content-require']) && $options['form-content-require'] == "checked") {
		$require = true;
	}
	if($rData['review_text']) {
		$rFieldValue = $rData['review_text'];
	}
	@include RR_PLUGIN_PATH . 'views/frontend/form/rr-textarea-input.php';
}

function rr_insert_new_review($data, $options, $sqltable) {

    if(!isset($options['is-spam'])) {
	    global $wpdb;
	    $wpdb->insert( $sqltable, $data );
    }
}

function rr_output_response_message($data, $options) {

	?>
	<div class="successful">
		<span class="rr_star glyphicon glyphicon-star left" style="font-size: 34px;"></span>
		<span class="rr_star glyphicon glyphicon-star big-star right" style="font-size: 34px;"></span>
			<strong>
				<?php
				    $rr_richreviews = 'rich-reviews';
				    $form_msg = __('your review has been recorded', $rr_richreviews);
					if((isset($options['form-name-display']) && $options['form-name-display'] == "checked") && (isset($options['form-name-require']) && $options['form-name-require'] == "checked" )) {
						echo $data['reviewer_name'] . ','. $form_msg;
					} else {
						echo $form_msg;
					}
					if(isset($options['require_approval']) && $options['require_approval'] == "checked" ) {
						echo __(' and submitted for approval', $rr_richreviews);
					}
					echo  __('. Thanks!', $rr_richreviews);
				?>
			</strong>
		<div class="clear"></div>
	</div>
	<?php
}

function rr_output_scroll_script() {

	?>
		<script>
			jQuery(function(){
				if(jQuery(".successful").is(":visible")) {
					console.log('success visible');
					offset = jQuery(".successful").offset();
					jQuery("html, body").animate({
						scrollTop: (offset.top - 400)
					});
				} else {
					errorPresent = false;
					jQuery(".form-err").each(function () {
						if(this.innerHTML != ''){
							console.log("errororororor");
							errorPresent = true;
						}
					});
					if(errorPresent) {
						console.log('error visible');
						offset = jQuery(".form-err").offset();
						jQuery("html, body").animate({
							scrollTop: (offset.top + 200)
						});
					}
				}
			});
		</script>
	<?php

}

function rr_send_admin_email($data, $options) {

	extract($data);
	$richreviews = 'rich-reviews';
	$message = "";
	$message .= "RichReviews User,\r\n";
	$message .= "\r\n";
	$message .= __("You have received a new review which is now pending your approval. The information from the review is listed below.", $richreviews) . "\r\n";
	$message .= "\r\n";
	$message .= __("Review Date: ", $richreviews) .$date_time."\r\n";
	if( $reviewer_name != "" ) {
		$message .= $options["form-name-label"].": ".$reviewer_name."\r\n";
	}
	if ( $reviewer_email != "" ) {
		$message .= $options["form-email-label"].": ".$reviewer_email."\r\n";
	}
	if ( $review_title != "" ) {
		$message .= $options["form-title-label"].": ".$review_title."\r\n";
	}
	$message .= __("Review Rating: ", $richreviews). $review_rating ."\r\n";
	if ($review_text != "" ) {
		$message .= $options["form-content-label"].": ".$review_text."\r\n";
	}
	$message .= __("Review Category: ", $richreviews) . $review_category ."\r\n\r\n";

	$message .= __("Click the link below to review and approve your new review.", $richreviews). "\r\n";
	$message .= admin_url() . "/admin.php?page=fp_admin_pending_reviews_page\r\n\r\n";
	$message .= __("Thanks for choosing Rich Reviews,", $richreviews). "\r\n";
	$message .= __("The Starfish Team", $richreviews);

	$mail_subject = __('New Pending Review', $richreviews);

	wp_mail($options['admin-email'], $mail_subject, $message);
}

// Validation for the existence of required fields.

function rr_require_name_field($incomingData) {

	if ($incomingData['reviewer_name'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['name'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_title_field($incomingData) {

	if ($incomingData['review_title'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['title'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_email_field($incomingData) {

	if ($incomingData['reviewer_email'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['email'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_content_field($incomingData) {
	if ($incomingData['review_text'] == '') {
		$incomingData['isValid'] = false;
		$incomingData['errors']['content'] = 'absent required';
	}
	return $incomingData;
}

function rr_require_rating_field($incomingData) {
	if ($incomingData['review_rating'] == 0) {
		$incomingData['isValid'] = false;
		$incomingData['errors']['rating'] = 'absent required';
	}
	return $incomingData;
}

function rr_validate_name_length($incomingData) {
	if (strlen($incomingData['reviewer_name']) > 40) {
		$incomingData['isValid'] = false;
		$incomingData['errors']['name'] = 'length violation';
	}
	return $incomingData;
}

function rr_validate_email($incomingData) {

	if ($incomingData['reviewer_email'] != '') {
		if (strlen($incomingData['reviewer_email']) > 150 ) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['email'] = 'length violation';
		} else {
			$firstAtPos = strpos($incomingData['reviewer_email'],'@');
			$periodPos  = strpos($incomingData['reviewer_email'],'.');
			$lastAtPos  = strpos($incomingData['reviewer_email'],'@');
			if (($firstAtPos === false) || ($firstAtPos != $lastAtPos) || ($periodPos === false)) {
					$incomingData['isValid'] = false;
					$incomingData['errors']['email'] = 'invalid input';
			}
		}
	}
	return $incomingData;
}

function rr_validate_title_length($incomingData) {
	if ($incomingData['review_title'] != '' ) {
		if (strlen($incomingData['review_title']) > 40) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['title'] = 'length violation';
		}
	}
	return $incomingData;
}

function rr_validate_content_length($incomingData) {
	if ($incomingData['review_text'] != '' ) {
		if (strlen($incomingData['review_title']) > 300) {
			$incomingData['isValid'] = false;
			$incomingData['errors']['content'] = 'length violation';
		}
	}
	return $incomingData;
}




function fp_sanitize($input) {
	if (is_array($input)) {
		foreach($input as $var=>$val) {
			$output[$var] = fp_sanitize($val);
		}
	}
	else {
		$input  = clean_input($input);
		$output = $input;
	}
	return $output;
}

function clean_input($input) {
		$handling = $input;
		$handling = sanitize_text_field($handling);
		$handling = stripslashes($handling);
		$output = $handling;
		return $output;
	}
