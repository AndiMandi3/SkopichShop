<?php

	function rr_do_review_snippet($data, $options) {

		global $post;
		extract($data);
		if(!isset($category) || $category == null) {
			$category = '';
		}

		if (isset($options['snippet_stars']) && $options['snippet_stars'] != "")  {
			$use_stars = TRUE;
			$decimal = $average - floor($average);
			if($decimal >= 0.5) {
				$roundedAverage = floor($average) + 1;
			} else {
				$roundedAverage = floor($average);
			}
			$stars = '';
			$star_count = 0;

			for ($i=1; $i<=5; $i++) {
				if ($i <= $roundedAverage) {
					$stars = $stars . '&#9733;';
				}
				else {
					$stars = $stars . '&#9734;';
				}
			}
		} else {
			$use_stars = FALSE;
			$stars = null;

		}

		if($category == '' || $category == 'none' || $category == 'all') {
				$category = $options['rich_itemReviewed_fallback'];
				if($options['rich_itemReviewed_fallback_case'] == 'both_missing') {
					if(isset($post->post_title) && $post->post_title != '') {
						$category = $post->post_title;
					}
				}
		} else if($category == 'page' || $category == 'post') {
			if(isset($post->post_title) && $post->post_title != '') {
				$category = $post->post_title;
			} else {
				$category = $options['rich_itemReviewed_fallback'];
			}
		}

		if($options['rich_itemReviewed_fallback_case'] == 'always') {
			$category = $options['rich_itemReviewed_fallback'];
		}

		if(isset($options['rich_include_url']) && isset($options['rich_url_value']) && $options['rich_url_value'] != '') {
			$url_markup = '<a href="http://' . $options['rich_url_value'] . '" itemprop="url"></a>';
		} else {
			$url_markup = '';
		}

		$data = array(
			'use_stars'		=> $use_stars,
			'category' 		=> $category,
			'stars'			=> $stars,
			'average'		=> $average,
			'reviewsCount'	=> $reviewsCount,
			'url_markup'	=> $url_markup,
			'options'		=> $options
		);
		include RR_PLUGIN_PATH . '/views/frontend/snippets.php';
	}