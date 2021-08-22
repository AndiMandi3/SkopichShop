<?php
/**
 * Plugin Name: Rich Reviews by Starfish
 * Plugin URI:  https://starfish.reviews/?utm_source=rich_reviews_plugin&utm_medium=wordpress_org&utm_campaign=plugin_author_uri
 * Description: Rich Reviews empowers you to easily capture user reviews and display them on your wordpress page or post and in Google Search Results as a Google Rich Snippet.
 * Author: Starfish Reviews
 * Version: 1.9.5
 * Author URI: https://starfish.reviews/?utm_source=rich_reviews_plugin&utm_medium=wordpress_org&utm_campaign=plugin_author_uri
 * Copyright: 2017 - 2020 Starfish
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rich-reviews
 *
 * @package WordPress
 * @subpackage rich-reviews
 */

/**
 * --------------------------------------------
 * Shortcodes
 * --------------------------------------------
 */
require_once 'shortcodes/rr-form.php';
require_once 'shortcodes/rr-show.php';
require_once 'shortcodes/rr-snippet.php';

/**
 * --------------------------------------------
 * Plugin Constants
 * --------------------------------------------
 */
define( 'RR_VERSION', '1.9.5' );
define( 'RR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'RR_PLUGIN_URL', plugins_url(basename(dirname(__FILE__))) );
define( 'RR_HOME_URL', home_url() );

// Plugin Option Groups!
define( 'RR_OPTIONS_FORM', 'rr_form_options' );
define( 'RR_OPTIONS_ADMIN', 'rr_admin_options' );
define( 'RR_OPTIONS_DISPLAY', 'rr_display_options' );
define( 'RR_OPTIONS_USER', 'rr_user_options' );
define( 'RR_OPTIONS_MARKUP', 'rr_markup_options' );

// Plugin Options!
define( 'RR_OPTION_VERSION', 'version' );
define( 'RR_OPTION_STAR_COLOR', 'star_color' );
define( 'RR_OPTION_SNIPPET_STARS', 'snippet_stars' );
define( 'RR_OPTION_EXCERPT_LENGTH', 'excerpt_length' );
define( 'RR_OPTION_REVIEWS_ORDER', 'reviews_order' );
define( 'RR_OPTION_APPROVE_AUTHORITY', 'approve_authority' );
define( 'RR_OPTION_REQUIRE_APPROVAL', 'require_approval' );
define( 'RR_OPTION_SHOW_FORM_POST_TITLE', 'show_form_post_title' );
define( 'RR_OPTION_DISPLAY_FULL_WIDTH', 'display_full_width' );
define( 'RR_OPTION_CREDIT_PERMISSION', 'credit_permission' );
define( 'RR_OPTION_SHOW_DATE', 'show_date' );
define( 'RR_OPTION_RICH_ITEM_REVIEWED_FALLBACK', 'rich_itemReviewed_fallback' );
define( 'RR_OPTION_RICH_ITEM_REVIEWED_FALLBACK_CASE', 'rich_itemReviewed_fallback_case' );
define( 'RR_OPTION_RICH_AUTHOR_FALLBACK', 'rich_author_fallback' );
define( 'RR_OPTION_RICH_INCLUDE_URL', 'rich_include_url' );
define( 'RR_OPTION_RICH_MARKUP_CONTENT_TYPE_VALUE', 'markup_content_type' );
define( 'RR_OPTION_RICH_MARKUP_STREET_ADDRESS', 'markup_street_address' );
define( 'RR_OPTION_RICH_MARKUP_LOCALITY_ADDRESS', 'markup_locality_address' );
define( 'RR_OPTION_RICH_MARKUP_REGION_ADDRESS', 'markup_region_address' );
define( 'RR_OPTION_RICH_MARKUP_TELEPHONE', 'markup_number' );
define( 'RR_OPTION_RICH_MARKUP_PRICE_RANGE','markup_price_range' );
define( 'RR_OPTION_RICH_PRODUCT','markup_price_product' );
define( 'RR_OPTION_RICH_URL_VALUE', 'rich_url_value' );
define( 'RR_OPTION_FORM_NAME_LABEL', 'form-name-label' );
define( 'RR_OPTION_FORM_NAME_DISPLAY', 'form-name-display' );
define( 'RR_OPTION_FORM_NAME_REQUIRE', 'form-name-require' );
define( 'RR_OPTION_FORM_NAME_USE_USERNAMES', 'form-name-use-usernames' );
define( 'RR_OPTION_FORM_NAME_USER_AVATAR', 'form-name-use-avatar' );
define( 'RR_OPTION_UNREGISTERED_ALLOW_AVATAR_UPLOAD', 'unregistered-allow-avatar-upload' );
define( 'RR_OPTION_FORM_EMAIL_LABEL', 'form-email-label' );
define( 'RR_OPTION_FORM_EMAIL_DISPLAY', 'form-email-display' );
define( 'RR_OPTION_FORM_EMAIL_REQUIRE', 'form-email-require' );
define( 'RR_OPTION_FORM_TITLE_LABEL', 'form-title-label' );
define( 'RR_OPTION_FORM_TITLE_DISPLAY', 'form-title-display' );
define( 'RR_OPTION_FORM_TITLE_REQUIRE', 'form-title-require' );
define( 'RR_OPTION_FORM_RATING_LABEL', 'form-rating-label' );
define( 'RR_OPTION_FORM_CONTENT_LABEL', 'form-content-label' );
define( 'RR_OPTION_FORM_CONTENT_DISPLAY', 'form-content-display' );
define( 'RR_OPTION_FORM_CONTENT_REQUIRE', 'form-content-require' );
define( 'RR_OPTION_FORM_SUBMIT_TEXT', 'form-submit-text' );
define( 'RR_OPTION_INTEGRATE_USER_INFO', 'integrate-user-info' );
define( 'RR_OPTION_REQUIRE_LOGIN', 'require-login' );
define( 'RR_OPTION_RETURN_TO_FORM', 'return-to-form' );
define( 'RR_OPTION_SEND_EMAIL_NOTIFICATION', 'send-email-notifications' );
define( 'RR_OPTION_ADMIN_EMAIL', 'admin-email' );
define( 'RR_OPTION_VERSION_HIDDEN', 'version' );
define('RR_TEXT_INPUT_PATH', 'views/frontend/form/rr-text-input.php');
/**
 * --------------------------------------------
 * Plugin Includes
 * --------------------------------------------
 */
include_once RR_PLUGIN_PATH . '/lib/rich-reviews-options.php';

class RichReviews {

	var $sqltable = 'richreviews';

	var $admin;
	var $db;

	/**
	* The variable that stores all current options
	*/
	var $rr_admin_options;
	var $rr_form_options;
	var $rr_display_options;
	var $rr_user_options;
	var $rr_markup_options;
	var $rr_all_options;
	var $plugin_url;
	var $logo_url;
	var $logo_small_url;
	var $starfish_logo;
	var $starfish_ratings_anim;

	function __construct() {
		global $wpdb;
		$this->sqltable = $wpdb->prefix . $this->sqltable;
		$this->plugin_url = trailingslashit(plugins_url(basename(dirname(__FILE__))));
		$this->logo_url = $this->plugin_url . '/assets/icon-256x256.png';
		$this->logo_small_url = $this->plugin_url . '/assets/icon-128x128.png';
		$this->starfish_ratings_anim = RR_PLUGIN_URL . '/assets/rating-star-increase-reviews-animation-wide.svg';
		$this->starfish_logo = RR_PLUGIN_URL . '/assets/starfish-reviews-wide-logo.svg';

		// check if options exist, if not create defaults.
		$this->rr_admin_options = get_option(RR_OPTIONS_ADMIN);
		$this->rr_form_options = get_option(RR_OPTIONS_FORM);
		$this->rr_display_options = get_option(RR_OPTIONS_DISPLAY);
		$this->rr_user_options = get_option(RR_OPTIONS_USER);
		$this->rr_markup_options = get_option(RR_OPTIONS_MARKUP);
		$this->rr_all_options = array_merge($this->rr_admin_options,$this->rr_form_options,$this->rr_display_options,$this->rr_markup_options);
        if(is_array($this->rr_user_options)) {
            $this->rr_all_options = array_merge($this->rr_all_options, $this->rr_user_options);
        }
        if(!$this->rr_admin_options) {
	        foreach ( RR_OPTIONS::getOptions( 'admin' ) as $option => $args ) {
		        $this->rr_admin_options[ $option ] = $args['default'];
	        }
	        add_option( RR_OPTIONS_ADMIN, $this->rr_admin_options );
        }
        if(!$this->rr_form_options) {
	        foreach ( RR_OPTIONS::getOptions( 'form' ) as $option => $args ) {
		        $this->rr_form_options[$option] = $args['default'];
	        }
	        add_option( RR_OPTIONS_FORM, $this->rr_form_options );
        }
		if(!$this->rr_display_options) {
	        foreach ( RR_OPTIONS::getOptions( 'display' ) as $option => $args ) {
		        $this->rr_display_options[$option] = $args['default'];
	        }
			add_option( RR_OPTIONS_DISPLAY, $this->rr_display_options );
		}
		if(!$this->rr_user_options) {
		    if(!is_array($this->rr_user_options)) {
		        $this->rr_user_options = array();
            }
	        foreach ( RR_OPTIONS::getOptions( 'user' ) as $option => $args ) {
		        $this->rr_user_options[$option] = $args['default'];
	        }
			add_option( RR_OPTIONS_USER, $this->rr_user_options );
		}
		if(!$this->rr_markup_options) {
	        foreach ( RR_OPTIONS::getOptions( 'markup' ) as $option => $args ) {
		        $this->rr_markup_options[$option] = $args['default'];
	        }
			add_option( RR_OPTIONS_MARKUP, $this->rr_markup_options );
        }

		$this->db = new RichReviewsDB($this);
		$this->admin = new RichReviewsAdmin($this);

		add_action('plugins_loaded', array(&$this, 'on_load'));
		add_action('init', array(&$this, 'init'));
		add_action('wp_enqueue_scripts', array(&$this, 'load_scripts_styles'), 100);

		add_shortcode('RICH_REVIEWS_FORM', array(&$this, 'shortcode_reviews_form_control'));
		add_shortcode('RICH_REVIEWS_SHOW', array(&$this, 'shortcode_reviews_show_control'));
		add_shortcode('RICH_REVIEWS_SNIPPET', array(&$this, 'shortcode_reviews_snippets_control'));

		add_filter('widget_text', 'do_shortcode');

		add_action( 'widgets_init', array(&$this, 'register_rr_widget') );
		add_action( 'wpml_loaded', array(&$this, 'my_theme_setup' ));

		add_action( 'wp_enqueue_scripts', function() {
		    wp_enqueue_style( 'dashicons' );
		} );
	}

	
	function my_theme_setup(){
	  load_theme_textdomain( 'wpml_plugin', get_template_directory() . '/languages' );
	}

	function init() {
		$this->process_plugin_updates();
		$this->set_display_filters();
		$this->set_form_filters();
	}
	
	function process_plugin_updates() {
		global $wpdb;
		$update_sqltable = $this->db->sqltable;
		require_once( ABSPATH . 'wp-admin/includes/plugin.php');
		$plugin_data    = get_plugin_data( __FILE__ );
        $updated_version = $plugin_data['Version'];
		if (isset($this->rr_admin_options['version'])) {
			$current_version = $this->rr_admin_options['version'];
		} else { //we were in version 1.0, now we updated
			$current_version = '1.0';
		}
		// Legacy checks
		if ($current_version == '1.0') {
			$wpdb->query($wpdb->prepare("ALTER TABLE ".$update_sqltable." CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci"));
		}
		if ($current_version == '1.0' || $current_version == '1.1' || $current_version == '1.2') {
			$this->db->create_update_database();
		}
		if ($current_version < '1.9.0') {
			$this->db->column_update_database();
		}
        if(version_compare($current_version, '1.8.0', '<=')) {
            delete_option(RR_OPTIONS_ADMIN);
            delete_option('rr_options');
            delete_option('shopAppOptions');
            foreach ( RR_OPTIONS::getOptions( 'admin' ) as $option => $args ) {
                $this->rr_admin_options[ $option ] = $args['default'];
            }
            add_option( RR_OPTIONS_ADMIN, $this->rr_admin_options );
        }
		if ($current_version != $updated_version) {
			// Update version
            $this->rr_admin_options['version'] = $updated_version;
			update_option(RR_OPTIONS_ADMIN, $this->rr_admin_options);
		}

	}

	function load_scripts_styles() {
		$pluginDirectory = trailingslashit(plugins_url(basename(dirname(__FILE__))));
		wp_register_script('rich-reviews', $pluginDirectory . 'js/rich-reviews.js', array('jquery'));
		wp_enqueue_script('rich-reviews');
		wp_register_style('rich-reviews', $pluginDirectory . 'css/rich-reviews.css');
		wp_enqueue_style('rich-reviews');

		$javascriptData = array(
		    'excerpt_length'            => $this->rr_display_options['excerpt_length'],
		    'maybe_some_other_stuff'	=> 'Probably Not'
		);
		wp_localize_script( 'rich-reviews', 'php_vars', $javascriptData);
		$translation_array = array(
            'read_more' => __( 'Read More', 'rich-reviews' ),
            'less' => __( 'Less', 'rich-reviews' ));
        wp_localize_script( 'rich-reviews', 'translation', $translation_array );

	}

	function set_display_filters() {
		add_action('rr_do_review_content', 'do_review_body', 4);
		if(isset($this->rr_display_options['display_full_width']) && $this->rr_display_options['display_full_width'] == 'checked') {
			add_action('rr_do_review_wrapper', 'full_width_wrapper');
		}	else {
			add_action('rr_do_review_wrapper', 'column_wrapper');
		}
		if(isset($this->rr_display_options['show_form_post_title']) && $this->rr_display_options['show_form_post_title'] == 'checked') {
			add_action('rr_do_review_content', 'do_post_title', 1);
		} else {
			add_action('rr_do_review_content', 'do_hidden_post_title', 1);
		}
		if(isset($this->rr_display_options['rich_include_url']) && $this->rr_display_options['rich_include_url'] == 'checked') {
			add_action('rr_do_review_content', 'do_url_schema', 2);
		} else {
			add_action('rr_do_review_content', 'omit_url_schema', 2);
		}
		if(isset($this->rr_display_options['show_date']) && $this->rr_display_options['show_date'] == 'checked') {
			add_action('rr_do_review_content', 'do_the_date', 3);
		} else {
			add_action('rr_do_review_content', 'do_the_date_hidden', 3);
		}
		if(isset($this->rr_display_options['credit_permission']) && $this->rr_display_options['credit_permission'] == 'checked') {
			add_action('rr_close_testimonial_group', 'print_credit');
		}
		add_action('rr_close_testimonial_group', 'render_custom_styles');
	}

	function set_form_filters() {

		add_filter('rr_process_form_data', 'sanitize_incoming_data');
		add_filter('rr_check_required', 'rr_require_rating_field');

		add_action('rr_on_valid_data', 'rr_insert_new_review', 1, 3);
		if(isset($this->rr_admin_options['send-email-notifications']) && $this->rr_admin_options['send-email-notifications'] == 'checked')
		{
			add_action('rr_on_valid_data', 'rr_send_admin_email', 1, 3);
		}
		add_action('rr_on_valid_data', 'rr_output_response_message', 1, 3);
		if(isset($this->rr_form_options['form-name-display']) && $this->rr_form_options['form-name-display'] == 'checked') {
			add_action('rr_do_form_fields', 'rr_do_name_field', 1, 4);
			add_filter('rr_misc_validation', 'rr_validate_name_length');
			if($this->rr_form_options['form-name-require']) {
				add_filter('rr_check_required', 'rr_require_name_field');
			}
		}
		if(isset($this->rr_form_options['form-email-display']) && $this->rr_form_options['form-email-display'] == 'checked') {
			add_action('rr_do_form_fields', 'rr_do_email_field', 2, 4);
			add_filter('rr_misc_validation', 'rr_validate_email');

			if(isset($this->rr_form_options['form-email-require']) && $this->rr_form_options['form-email-require'] == 'checked') {
				add_filter('rr_check_required', 'rr_require_email_field');
			}
		}
		if(isset($this->rr_form_options['form-title-display']) && $this->rr_form_options['form-title-display'] == 'checked') {
			add_action('rr_do_form_fields', 'rr_do_title_field', 3, 4);
			add_filter('rr_misc_validation', 'rr_validate_title_length');

			if(isset($this->rr_form_options['form-title-require']) && $this->rr_form_options['form-title-require'] == 'checked') {
				add_filter('rr_check_required', 'rr_require_title_field');
			}
		}
		//TODO: Maybe add min/max rating validation
		add_action('rr_do_form_fields', 'rr_do_rating_field', 4, 4);
		if(isset($this->rr_form_options['form-content-display']) && $this->rr_form_options['form-content-display'] == 'checked') {
			add_action('rr_do_form_fields', 'rr_do_content_field', 5, 4);
			add_filter('rr_misc_validation', 'rr_validate_content_length');

			if(isset($this->rr_form_options['form-content-require']) && $this->rr_form_options['form-content-require'] == 'checked') {
				add_filter('rr_check_required', 'rr_require_content_field');
			}
		}
		if(isset($this->rr_display_options['return-to-form']) && $this->rr_display_options['return-to-form'] == 'checked') {
			add_action('rr_set_local_scripts','rr_output_scroll_script');
		}
	}

	function update_review_status($result, $status) {
		global $wpdb;
		$idid = $result['idid'];
		$rName = $result['reviewername'];
		$rIP = $result['reviewerip'];

		$output = __('Something went wrong! Please report this error.', 'rich-reviews');
		switch ($status) {
			case 'approve':
				$output = 'Review with internal ID ' . $idid . ' from the reviewer ' . $this->nice_output($rName) . ', whose IP is ' . $rIP . ' has been approved.<br>';
				$wpdb->update($this->sqltable, array('review_status' => '1'), array('id' => $idid));
				break;
			case 'limbo':
				$output = 'Review with internal ID ' . $idid . ' from the reviewer ' . $this->nice_output($rName) . ', whose IP is ' . $rIP . ' has been set as a pending review.<br>';
				$wpdb->update($this->sqltable, array('review_status' => '0'), array('id' => $idid));
				break;
			case 'delete':
				$output = 'Review with internal ID ' . $idid . ' from the reviewer ' . $this->nice_output($rName) . ', whose IP is ' . $rIP . ' has been deleted.<br>';
				$wpdb->query($wpdb->prepare("DELETE FROM ".$this->sqltable." WHERE id=%d", $idid));
				break;
		}
		return __($output, 'rich-reviews');
	}

	function shortcode_reviews_form_control($atts) {
		ob_start();

		rr_do_review_form($atts, $this->rr_all_options, $this->sqltable);

		return ob_get_clean();
	}

	function shortcode_reviews_show_control($atts) {
		global $post;
		extract(shortcode_atts(
			array(
				'category' => '',
				'num' => '3',
			)
		, $atts));
		$reviews = $this->db->get_reviews($category, $num, $post);
		ob_start();
			rr_do_review_show($reviews, $this->rr_all_options);

		return ob_get_clean();
	}

	function shortcode_reviews_snippets_control($atts) {		
		global $wpdb, $post;
		$output = '';
		extract(shortcode_atts(
			array(
				'category' => 'none',
			)
		,$atts));
		$data = $this->db->get_average_rating($category);
		ob_start();
			rr_do_review_snippet($data, $this->rr_all_options);
		return ob_get_clean();
	}

	function display_admin_review($review, $status = 'limbo') {

		$rID        = $review['idid'];
		$rDateTime  = $review['datetime'];
		$rName      = $this->nice_output($review['reviewername']);
		$rEmail     = $this->nice_output($review['revieweremail']);
		$rTitle     = $this->nice_output($review['reviewtitle']);
		$rRatingVal = max(1,intval($review['reviewrating']));
		$rText      = $this->nice_output($review['reviewtext']);
		$rStatus    = $review['reviewstatus'];
		$rIP        = $review['reviewerip'];
		$rPostID    = $review['postid'];
		$rCategory  = $review['reviewcategory'];
		$rRating = '';
		for ($i=1; $i<=$rRatingVal; $i++) {
			$rRating .= '&#9733;'; // black star
		}
		for ($i=$rRatingVal+1; $i<=5; $i++) {
			$rRating .= '&#9734;'; // white star
		}
		$approveChecked = '';
		$limboChecked   = '';
		$deleteChecked  = '';
		switch ($status) {
			case 'approve':
				$approveChecked = ' checked';
				break;
			case 'limbo':
				$limboChecked = ' checked';
				break;
			case 'delete':
				$deleteChecked = ' checked';
				break;
			default:
				$approveChecked = '';
				$limboChecked   = '';
				$deleteChecked  = '';
		}
		$output = '';
		$output .= '<tr class="rr_admin_review_container">
				<td class="rr_admin_review_actions_container">
					<div class="rr_admin_review_action"><input class="radio" type="radio" name="updateStatus_' . $rID . '" value="approve"' . $approveChecked . '/> Approve</div>
					<div class="rr_admin_review_action"><input class="radio" type="radio" name="updateStatus_' . $rID . '" value="limbo"' . $limboChecked . '/> Pending</div>
					<div class="rr_admin_review_action"><input class="radio" type="radio" name="updateStatus_' . $rID . '" value="delete"' . $deleteChecked . '/> Delete</div>
				</td>
				<td class="rr_admin_review_info_container">
					<div class="rr_reviewer">' . $rName . '</div>
					<div>' . $rEmail . '</div>
					<div>' . $rIP . '</div>
					<div>Category: ' . $rCategory . '</div>
					<div>Page/Post ID: ' . $rPostID . '</div>
				</td>
				<td class="rr_admin_review_content_container">
					<div class="rr_title">' . $rTitle . '</div>
					<div class="rr_admin_review_stars">' . $rRating . '</div>
					<div class="rr_review_text">' . $rText . '</div>
				</td>
			</tr>';
		return $output;
	}

	function nice_output($input, $keep_breaks = TRUE) {
		return $input;
	}

	function clean_input($input) {
		$handling = $input;
		$handling = sanitize_text_field($handling);
		$handling = stripslashes($handling);
		$output = $handling;
		return $output;
	}

	function fp_sanitize($input) {
		if (is_array($input)) {
			foreach($input as $var=>$val) {
				$output[$var] = $this->fp_sanitize($val);
			}
		}
		else {
			$input  = $this->clean_input($input);
			$output = $input;
		}
		return $output;
	}

	function render_custom_styles() {
		?>
			<style>
				.stars, .rr_star {
					color: <?php echo $this->rr_display_options['star_color']?>;
				}
			</style>
		<?php
	}

	function print_credit() {
		$permission = $this->rr_display_options['credit_permission'];
		$output = "";
		if ($permission) {
			$output = '<div class="credit-line">' . __('Supported By: ', 'rich-reviews') . '<a href="https://starfish.reviews/" rel="nofollow">' . 'Starfish'. '</a>';
			$output .= '</div>' . PHP_EOL;
			$output .= '<div class="clear"></div>' . PHP_EOL;
		}
		return __($output, 'rich-reviews');
	}

	function on_load() {
		$plugin_dir = basename(dirname(__FILE__)). '/languages/';
		load_plugin_textdomain( 'rich-reviews', false, $plugin_dir );
	}

	function register_rr_widget() {
		register_widget( 'RichReviewsShowWidget' );
	}
}

if (!class_exists('NMRichReviewsAdminHelper')) {
	require_once('views/admin/view-helper/admin-view-helper-functions.php');
}

if (!class_exists('NMDB')) {
	require_once('lib/nmdb.php');
}
if (!class_exists('RROptions')) {
	require_once('lib/rich-reviews-options.php');
}
require_once('lib/rich-reviews-admin.php');
require_once('lib/rich-reviews-db.php');
require_once('lib/rich-reviews-widget.php');
require_once('lib/rich-reviews-admin-notices.php');
require_once("views/admin/admin-add-edit-view.php");

global $richReviews;
$richReviews = new RichReviews();