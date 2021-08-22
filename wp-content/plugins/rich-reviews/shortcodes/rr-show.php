<?php

	//
	// This file contains all functions specifically pertinant to the display of Reviews
	// TODO: Modify to use filters

	function rr_do_review_show($reviews, $options) {
		// Show the reviews
		if (count($reviews)) {
			$total_count = count($reviews);
			$review_count = 0;
			?> <div class="testimonial_group"> <?php
			foreach($reviews as $review) {
				display_review($review, $options);
				$review_count += 1;
				if ($review_count == 3) {

					// end the testimonial_group
					?> </div>

					<!-- clear the floats -->
					<div class="clear"></div> <?php

					// do we have more reviews to show?
					if ($review_count < $total_count) {
						?> <div class="testimonial_group"> <?php
					}

					// reset the counter
					$review_count = 0;
					$total_count = $total_count - 3;
				}
			}
			// do we need to close a testimonial_group?
			if ($review_count != 0) {
				?>
				</div>
				<div class="clear"></div>
				<?php
			}

		}
		do_action('rr_close_testimonial_group', $options);
	}

	function display_review($review, $options) {
		$date = strtotime($review->date_time);
		$data = array(
			'rID'       => $review->id,
			'rDateTime' => $review->date_time,
			'date' 		=> strtotime($review->date_time),
			'rDay'		=> date("j", $date),
			'rMonth'	=> date("F", $date),
			'rWday'		=> date("l", $date),
			'rYear'		=> date("Y", $date),
			'rDate' 	=> '',
			'rName'     => $review->reviewer_name,
			'rEmail'    => $review->reviewer_email,
			'rTitle'    => $review->review_title,
			'rRatingVal'=> max(1,intval($review->review_rating)),
			'rText'     => $review->review_text,
			'rStatus'   => $review->review_status,
			'rIP'       => $review->reviewer_ip,
			'rPostId'   => $review->post_id,
			'rRating' 	=> '',
			'rFull'		=> false,
			'rCategory' => $review->review_category,
			'using_subject_fallback' => false,
			'rich_url'  => $options['rich_url_value'],
			'markup_content_type' => 'Product'

		);
		$using_subject_fallback = false;
		$title = $data['rCategory'];
		if(!isset($data['rCategory']) || $data['rCategory'] == '' || strtolower($data['rCategory']) == 'none' || $data['rCategory'] == null ) {
			$page_title = get_the_title($data['rPostId']);
			$using_subject_fallback = true;

			if(isset($page_title) && $page_title != '' && $options['rich_itemReviewed_fallback_case'] == 'both_missing')  {
				$title = $page_title;
			} else {
				$title = $options['rich_itemReviewed_fallback'];
			}
		}

		if($options['rich_itemReviewed_fallback_case'] == 'always') {
			$title = $options['rich_itemReviewed_fallback'];
			$using_subject_fallback = true;
		}

		$data['rCategory'] = $title;
		$data['using_subject_fallback'] = $using_subject_fallback;

		if(!isset($data['rName']) || $data['rName'] == '') {
			if($options['rich_author_fallback'] != '') {
				$data['rName'] = $options['rich_author_fallback'];
			} else {
				$data['rName'] = 'Anonymous';
			}
		}

		for ($i=1; $i<=$data['rRatingVal']; $i++) {
			$data['rRating'] .= '&#9733;'; // orange star
		}
		for ($i=$data['rRatingVal']+1; $i<=5; $i++) {
			$data['rRating'] .= '&#9734;'; // white star
		}

		$data['rDate'] = $data['rWday'] . ', ' . $data['rMonth'] . ' ' . $data['rDay'] . ', ' . $data['rYear'];

		if(isset($options['display_full_width']) && $options['display_full_width'] == "checked") {
			$data['rFull'] = true;
		}
		$markup_content_type = markup_content_type();
		$is_markup_product = $markup_content_type['markup_content_type'];
		$data['is_markup_product'] = $is_markup_product;

		do_action('rr_do_review_wrapper', $data);

		do_action('rr_do_review_head', $data);

		do_action('rr_do_review_content', $data);
	}
	
function full_width_wrapper($data) {
	#TODO: Rework output for rich data, image, and up/down vote
	
	if($data['is_markup_product'] == $data['markup_content_type'])
	{
	?>
	<div class="full-testimonial" itemscope itemtype="http://schema.org/Product">
		<div itemprop="aggregateRating"	itemscope itemtype="http://schema.org/AggregateRating">
			<span itemprop="ratingValue" style="display:none;"><?php echo $data['rRatingVal']; ?></span>
			<span itemprop="bestRating" style="display:none;">5</span>
			<span itemprop="ratingCount" style="display:none;"><?php echo $data['rRatingVal']; ?></span> 
		</div>
		<h3 class="rr_title" itemprop="name" style="display: none;">
		<?php if(isset($data['rTitle']) && $data['rTitle'] != "") { echo $data['rTitle']; }else{ echo get_bloginfo('name');} ?></h3>
		<div class="review-head">
		<div class="review-info">
		<h3 class="rr_title"><?php echo $data['rTitle']; ?></h3>
		<div class="clear"></div>
	<?php } else { ?>
	<div class="full-testimonial">
		<div class="review-head">
		<div class="review-info">
		<h3 class="rr_title"><?php echo $data['rTitle']; ?></h3>
		<div class="clear"></div>
	<?php
  }
}

function column_wrapper ($data) {
	if($data['is_markup_product'] == $data['markup_content_type'])
	{
	?>
	<div class="testimonial" itemscope itemtype="http://schema.org/Product">
		<div itemprop="aggregateRating"	itemscope itemtype="http://schema.org/AggregateRating">
			<span itemprop="ratingValue" style="display:none;"><?php echo $data['rRatingVal']; ?></span>
			<span itemprop="bestRating" style="display:none;">5</span>
			<span itemprop="ratingCount" style="display:none;"><?php echo $data['rRatingVal']; ?></span> 
		</div>
		<h3 class="rr_title"><?php echo $data['rTitle']; ?></h3>
		
		<h3 class="rr_title" itemprop="name" style="display: none;">
		<?php if(isset($data['rTitle']) && $data['rTitle'] != "") { echo $data['rTitle']; }else{ echo get_bloginfo('name');} ?></h3>
		<div class="clear"></div>
	
    <?php } else { ?>
	<div class="testimonial">
		<h3 class="rr_title"><?php echo $data['rTitle']; ?></h3>
		<div class="clear"></div>
	<?php
  } 
}

function do_post_title ($data) {
	hide_show_post_title($data,'show');
}
function do_hidden_post_title ($data) {
	hide_show_post_title($data,'hide');
}
function hide_show_post_title($data,$action)
{
	$hide = '';
	if($action == 'hide')
	{
		$hide = 'style="display:none"';
	}
	if($data['is_markup_product'] == $data['markup_content_type'])
	{
	?>
	<span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Review">
		<div class="rr_review_post_id" itemprop="name" <?php echo $hide;?>>
			<span itemprop="author" style="display:none;"> <?php if(isset($data['rName']) && $data['rName'] != "") { echo $data['rName']; }else{ echo 'Anonymous';}?></span>
				<div itemprop="itemReviewed">
					<span itemprop="name" style="display:none;"><?php if(isset($data['rCategory']) && $data['rCategory'] != "") {  echo $data['rCategory']; }else{ echo 'none';}?></span>
				</div>
			<a href="<?php echo get_permalink($data['rPostId']); ?>">
				<?php echo $data['rCategory']; ?>
			</a>
		</div>
    <?php } else { ?>
	<span>
		<div class="rr_review_post_id" <?php echo $hide;?>>
			<a href="<?php echo get_permalink($data['rPostId']); ?>">
				<?php echo $data['rCategory']; ?>
			</a>
		</div>
	<?php
    }
}

function do_url_schema($data) {
	if($data['is_markup_product'] == $data['markup_content_type'])
	{
	?>
	<a href="http://<?php echo $data['rich_url']; ?>" itemprop="url"></a>
			<div class="clear"></div>
	       </span>
    <?php } else { ?>
			<a href="http://<?php echo $data['rich_url']; ?>"></a>
			<div class="clear"></div>
		</span>
	<?php
    }
}

function omit_url_schema($data) {
	?>
		<div class="clear"></div>
	</span>
	<?php
}

function do_the_date ($data) {
	if($data['rDateTime'] != "0000-00-00 00:00:00") {
		// ob_start();
		if($data['is_markup_product'] == $data['markup_content_type'])
		{
		?>
		<span class="rr_date"><meta itemprop="datePublished" content="<?php echo $data['rDateTime']; ?>">
			<time datetime="<?php echo $data['rDate']; ?>">
				<?php echo $data['rDate']; ?>
			</time>
		</span>
        <?php } else { ?>
		<span class="rr_date"><meta content="<?php echo $data['rDateTime']; ?>">
			<time datetime="<?php echo $data['rDate']; ?>">
				<?php echo $data['rDate']; ?>
			</time>
		</span>
  <?php } 	
	} else {
		if(current_user_can('edit_posts')) { ?>
		<span class="date-err rr_date">
			<?php echo __('Date improperly formatted, correct in ', 'rich-reviews'); ?>
			<a href="/wp-admin/admin.php?page=fp_admin_approved_reviews_page">
				<?php echo __('Dashboard', 'rich-reviews'); ?>
			</a>
		</span>

	<?php	}
	}
	// return ob_get_clean();
}

function do_the_date_hidden ($data) {
		if($data['rDateTime'] != "0000-00-00 00:00:00") {
		if($data['is_markup_product'] == $data['markup_content_type'])
		{
		?>
		<span class="rr_date" style="display:none;">
		<meta itemprop="datePublished" content="<?php echo $data['rDateTime']; ?>">
			<time datetime="<?php echo $data['rDate']; ?>">
				<?php echo $data['rDate']; ?>
			</time>
		</span>
	    <?php } else { ?>
		<span class="rr_date" style="display:none;"><meta content="<?php echo $data['rDateTime']; ?>">
			<time datetime="<?php echo $data['rDate']; ?>">
				<?php echo $data['rDate']; ?>
			</time>
		</span>
	<?php
        }
	}
}

function do_review_body ($data) {
	if($data['is_markup_product'] == $data['markup_content_type'])
	{
	?>

	<div class="stars">
			<?php echo $data['rRating']; ?>
		</div>
		<div style="display:none;" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			<span itemprop="ratingValue">
				<?php echo $data['rRatingVal']; ?>

			</span>
			<span itemprop="bestRating">
				5
			</span>
			<span itemprop="worstRating">
				1
			</span>
		</div>


		<?php if($data['rFull']) {
			?>
				</div> <!-- close .review-info -->
			</div> <!-- close .review-head -->
		
		<?php } ?>


		<div class="clear"></div>

		<div class="rr_review_text"  ><span class="drop_cap">“</span><span itemprop="reviewBody"><?php echo $data['rText']; ?></span>”</div>
			<div class="rr_review_name" itemprop="author" itemscope itemtype="http://schema.org/Person"> - <span itemprop="name">
			<?php
				echo $data['rName'];
			?>
			</span></div>
			<div class="clear"></div>
		</div>
		<?php } else { ?>
		<div class="stars">
			<?php echo $data['rRating']; ?>
		</div>

		<?php if($data['rFull']) {
			?>
				</div> <!-- close .review-info -->
			</div> <!-- close .review-head -->

		<?php } ?>


		<div class="clear"></div>

		<div class="rr_review_text"  >
			<span class="drop_cap">“</span>
			<span><?php echo $data['rText']; ?></span>”
		</div>
			<div class="rr_review_name"> - <span>
			<?php
				echo $data['rName'];
			?>
			</span>
			</div>
			<div class="clear"></div>
		</div>
	<?php
    }
}

function print_credit() {
	?>
		<div class="credit-line">
			<?php echo __('Supported By: ', 'rich-reviews'); ?>
			<a href="https://starfish.reviews/" rel="nofollow">
				<?php echo 'Starfish Reviews'; ?>
			</a>
		</div>
		<div class="clear"></div>
	<?php
}

function markup_content_type(){
	return get_option(RR_OPTIONS_MARKUP);
}
