<?php
require_once(ABSPATH . 'wp-admin/includes/template.php');
$rich_reviews = 'rich-reviews';
$overall_rating = __( 'Overall rating:', $rich_reviews);
$based_on = __( 'based on', $rich_reviews );
$rr_outof = __( 'out of', $rich_reviews );
$rr_review = __( 'reviews.', $rich_reviews );
$average = $data['average'];
$options = $data['options'];
$category = $data['category'];
$url_markup = $data['url_markup'];
$reviewsCount = $data['reviewsCount'];
$rating = 'rating';
$markup_street_address = isset($options['markup_street_address']) ? $options['markup_street_address'] : '';
$markup_locality_address = isset($options['markup_locality_address']) ? $options['markup_locality_address'] : '';
$markup_region_address = isset($options['markup_region_address']) ? $options['markup_region_address'] : '';
$markup_number = isset($options['markup_number']) ? $options['markup_number'] : '';
$markup_price_range_to = isset($options['markup_price_range_to']) ? $options['markup_price_range_to'] : '';
$markup_price_range_from = isset($options['markup_price_range_from']) ? $options['markup_price_range_from'] : '';
$rich_author_fallback = isset($options['rich_author_fallback']) ? $options['rich_author_fallback'] : '';
$markup_content_type = isset($data['options']['markup_content_type']) ? $data['options']['markup_content_type'] : '';

if($markup_content_type == ""){
	$markup_content_type = 'Organization';
}
if($markup_content_type == 'Organization')
{
	if($data["use_stars"]) {
		?>
		<div itemscope itemtype="http://schema.org/Organization">
			<span itemprop="name" style="display:none"><?php echo esc_html($category); ?></span>
			<?php echo esc_js($url_markup); ?>
			<?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span class="stars">
					<?php echo esc_html($data['stars']); ?>
				</span>
				<span class="rating" itemprop="ratingValue" style="display: none !important;">
					<?php echo esc_html($average); ?>
				</span> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($reviewsCount); ?>
				</span> <?php echo $rr_review;?>
				<div style="display:none">
					<span itemprop="bestRating">5</span>
					<span itemprop="worstRating">1</span>
				</div>
			</span>
			<div itemprop="review" itemscope itemtype="http://schema.org/Review">
				<span itemprop="author" itemscope itemtype="https://schema.org/Person" style="display: none;">
					<span itemprop="name"><?php echo esc_html($rich_author_fallback); ?></span>
				</span>
			</div>
		</div>
		<?php render_custom_styles($options);
	} else {
		?>
		<div itemscope itemtype="http://schema.org/Organization">
			<span itemprop="name" style="display:none">
				<?php echo esc_html($category); ?>
			</span>
			<?php 			
			$args = array(
			   $rating => $average,
			   'type' => $rating,
			   'number' => 12345,
			);
			wp_star_rating( $args );
			echo esc_js($url_markup); ?>
		    <?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<strong>
					<span class="value" itemprop="ratingValue"><?php echo esc_html($average); ?></span>
				</strong> <?php echo $rr_outof;?>
				<strong><span itemprop="bestRating">5</span></strong> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($reviewsCount); ?>
				</span> <?php echo $rr_review;?>
			</span>
			<div itemprop="review" itemscope itemtype="http://schema.org/Review">
				<span itemprop="author" itemscope itemtype="https://schema.org/Person" style="display: none;">
					<span itemprop="name"><?php echo esc_html($rich_author_fallback); ?></span>
				</span>
			</div>
		</div>
	<?php
	}
}
if($markup_content_type == 'Local business')
{
	if(get_site_icon_url()){
		$site_icon = get_site_icon_url();
	}
	else{
		$site_icon = RR_PLUGIN_URL . '/assets/icon-128x128.png';
	}
	?>
	<div itemscope itemtype="http://schema.org/LocalBusiness">
		<span itemprop="name" style="display: none;"><?php echo esc_html($category); ?></span>
        <a itemprop="image" href="<?php echo $site_icon; ?>"></a>
		<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" style="display: none;">
		<?php
			if(isset($markup_street_address) &&  $markup_street_address != '')
			{
				?><span itemprop="streetAddress"><?php echo $markup_street_address;?></span><?php 
			}
			if(isset($markup_locality_address) &&  $markup_locality_address != '')
			{
				?><span itemprop="addressLocality"><?php echo $markup_locality_address.',';?></span><?php 
			}
			if(isset($markup_region_address) &&  $markup_region_address != '')
			{
				?><span itemprop="addressRegion"><?php echo $markup_region_address;?></span><?php 
			}
		?></div><?php
		if(isset($markup_number) &&  $markup_number != '')
		{
			?><span itemprop="telephone" style="display: none;"><?php echo $markup_number;?></span><?php
		}
		if(isset($markup_price_range_from) &&  $markup_price_range_from != '' && isset($markup_price_range_to) &&  $markup_price_range_to != '')
		{
			?><span itemprop="priceRange" style="display: none;"><?php echo '$'.$markup_price_range_from.' - $'.$markup_price_range_to;?></span><?php 
		}
		
		if($data["use_stars"]) {
	
			echo esc_js($url_markup);
			?>
		    <?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span class="stars">
					<?php echo esc_html($data['stars']); ?>
				</span>
				<span class="rating" itemprop="ratingValue" style="display: none !important;">
					<?php echo esc_html($average); ?>
				</span> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($reviewsCount); ?>
				</span> <?php echo $rr_review;?>
				<div style="display:none">
					<span itemprop="bestRating">5</span>
					<span itemprop="worstRating">1</span>
				</div>
			</span>
			<div itemprop="review" itemscope itemtype="http://schema.org/Review">
				<span itemprop="author" itemscope itemtype="https://schema.org/Person" style="display: none;">
					<span itemprop="name"><?php echo esc_html($rich_author_fallback); ?></span>
				</span>
			</div>
		<?php render_custom_styles($options);
		} else {
			$args = array(
			   $rating => $average,
			   'type' => $rating,
			   'number' => 12345,
			);
			wp_star_rating( $args );
			echo esc_js($url_markup); ?>
			<?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<strong>
					<span class="value" itemprop="ratingValue"><?php echo esc_html($average); ?></span>
				</strong> <?php echo $rr_outof;?>
				<strong><span itemprop="bestRating">5</span></strong> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($reviewsCount); ?>
				</span> <?php echo $rr_review;?>
			</span>
			<div itemprop="review" itemscope itemtype="http://schema.org/Review">
				<span itemprop="author" itemscope itemtype="https://schema.org/Person" style="display: none;">
					<span itemprop="name"><?php echo esc_html($rich_author_fallback); ?></span>
				</span>
			</div>
		</div>	
		<?php
		} 
}
if($markup_content_type == 'Product')
{	
	if($data["use_stars"]) {
		?>
		<div itemscope itemtype="http://schema.org/Product">
			<span itemprop="name" style="display:none">
				<?php if(isset($data['category']) && $data['category'] != "") {  echo esc_html($data['category']); }else{ echo 'none';}?>
			</span>
			<?php echo esc_js($data['url_markup']); ?>
			<?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span class="stars">
					<?php echo esc_html($data['stars']); ?>
				</span>
				<span class="rating" itemprop="ratingValue" style="display: none !important;">
					<?php echo esc_html($data['average']); ?>
				</span> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($data['reviewsCount']); ?>
				</span> <?php echo $rr_review;?>
				<div style="display:none">
					<span itemprop="bestRating">5</span>
					<span itemprop="worstRating">1</span>
				</div>
			</span>
		</div>
		<?php render_custom_styles($data['options']);
	} else {
		?>
		<div itemscope itemtype="http://schema.org/Product">
			<span itemprop="name" style="display:none">
				<?php if(isset($data['category']) && $data['category'] != "") {  echo esc_html($data['category']); }else{ echo 'none';}?>
			</span>
			<?php 
			$args = array(
			   $rating => $average,
			   'type' => $rating,
			   'number' => 12345,
			);
			wp_star_rating( $args );
			echo esc_js($data['url_markup']); ?>
			<?php echo $overall_rating;?>
			<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<strong><span class="value" itemprop="ratingValue">
					<?php echo esc_html($data['average']); ?>
				</span></strong> <?php echo $rr_outof;?>
				<strong><span itemprop="bestRating">5</span></strong> <?php echo $based_on;?>
				<span class="votes" itemprop="reviewCount">
					<?php echo esc_html($data['reviewsCount']); ?>
				</span> <?php echo $rr_review;?>
			</span>
		</div>
		<?php
	}
}
?>