<?php

/*
 * Database functions for Rich Reviews
 */

class RichReviewsDB extends NMDB {

	var $parent;
	var $debug_queries = FALSE;

	function __construct($parent) {
		global $wpdb;
		$this->parent = $parent;
		$this->sqltable = $this->parent->sqltable;
        $tableSearch = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $this->sqltable));        
        if ($tableSearch != $this->sqltable) {
            $this->create_update_database();
        }
	}

	function column_update_database() {
		global $wpdb;
		$qry = $wpdb->query('ALTER TABLE '.$this->sqltable.' MODIFY reviewer_ip VARCHAR(250)');
	}

	function create_update_database() {
		global $wpdb;

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$sql = "CREATE TABLE $this->sqltable (
				 id int(11) NOT NULL AUTO_INCREMENT,
				 date_time datetime NOT NULL,
				 reviewer_name varchar(100) DEFAULT NULL,
				 reviewer_email varchar(150) DEFAULT NULL,
				 review_title varchar(100) DEFAULT NULL,
				 review_rating tinyint(2) DEFAULT '0',
				 review_text text,
				 review_status tinyint(1) DEFAULT '0',
				 reviewer_ip varchar(250) DEFAULT NULL,
				 post_id int(11) DEFAULT '0',
				 review_category varchar(100) DEFAULT 'none',
				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;";
		dbDelta($sql);
	}

	function pending_reviews_count() {
		$this->select('COUNT(*)');
		$this->where('review_status', 0);
		return $this->get_var();
	}

	function approved_reviews_count() {
		$this->select('COUNT(*)');
		$this->where('review_status', 1);
		return $this->get_var();
	}

	function get_reviews($category, $num, $post) {

		$this->where('review_status', 1);
		if (($category == 'post') || ($category == 'page')) {
			$this->where('post_id', $post->ID);
		} else if ($category == 'none') {
			$this->where('review_category', 'none');
			$this->or_where('review_category', '');
		} else if($category != 'all' && $category != '') {
			$this->where('review_category', $category);
		}
		if ($num != 'all') {
			if ($num < 1) { $num = 1; }
			$this->limit($num);
		}

		// Set up the Order BY
		if ($this->parent->rr_display_options['reviews_order'] === 'random') {
			$this->order_by('random');
		}
		else {
			$this->order_by('date_time', $this->parent->rr_display_options['reviews_order']);
		}

		$results = $this->get();

		return $results;
	}

	function get_average_rating($category) {
		global $wpdb;
		global $post;

		switch (true) {
			case $category == 'none':
				$whereStatement = "review_status=%s";
				$review_status = array('1');	
				break;
			case $category == 'post':
			case $category == 'page':
				$whereStatement = "review_status=%s and post_id=%d";
				$review_status = array('1', $post->ID);	
				break;			
			case $category != 'all':
				$whereStatement = "review_status=%s and review_category=%s";
				$review_status = array('1', $category);
				break;
			case $category == 'all':
				$whereStatement = "review_status=%s and review_category!=%s";
				$review_status = array('1', '');
				break;
			default:
				$whereStatement = "review_status=%s";
				$review_status = array('1');
		}
		$approvedReviewsCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$this->sqltable." Where ". $whereStatement, $review_status));
		
		$averageRating = 0;
		if ($approvedReviewsCount != 0) {
			$averageRating = $wpdb->get_var($wpdb->prepare("SELECT AVG(review_rating) FROM ".$this->sqltable." Where ". $whereStatement, $review_status));
			$averageRating = floor(10*floatval($averageRating))/10;
		}		
		$return = array('average' => $averageRating, 'reviewsCount' => $approvedReviewsCount, 'category' => $category);

		return $return;
	}

}
