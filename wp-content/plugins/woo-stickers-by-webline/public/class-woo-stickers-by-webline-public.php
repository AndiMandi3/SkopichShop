<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.weblineindia.com
 * @since      1.0.0
 *
 * @package    Woo_Stickers_By_Webline
 * @subpackage Woo_Stickers_By_Webline/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Stickers_By_Webline
 * @subpackage Woo_Stickers_By_Webline/public
 * @author     Weblineindia <info@weblineindia.com>
 */
class Woo_Stickers_By_Webline_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $general_settings_key = 'general_settings';
	private $new_product_settings_key = 'new_product_settings';
	private $sale_product_settings_key = 'sale_product_settings';
	private $sold_product_settings_key = 'sold_product_settings';
	private $cust_product_settings_key = 'cust_product_settings';

	/**
	 * The Sold Out flag Identify product as sold.
	 *
	 * @since    1.1.2
	 * @access   private
	 * @var      string    $sold_out    The Sold Out flag Identify product as sold.
	 */
	private $sold_out = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->general_settings = ( array ) get_option ( $this->general_settings_key );
		$this->new_product_settings = ( array ) get_option ( $this->new_product_settings_key );
		$this->sale_product_settings = ( array ) get_option ( $this->sale_product_settings_key );
		$this->sold_product_settings = ( array ) get_option ( $this->sold_product_settings_key );
		$this->cust_product_settings = ( array ) get_option ( $this->cust_product_settings_key );

		// Merge with defaults
		$this->general_settings = array_merge ( array (
				'enable_sticker' => 'no',
				'enable_sticker_list' => 'no',
				'enable_sticker_detail' => 'no',
				'custom_css' => ''
		), $this->general_settings );
		
		$this->new_product_settings = array_merge ( array (
				'enable_new_product_sticker' => 'no',
				'new_product_sticker_days' => '10',
				'new_product_position' => 'left',
				'new_product_option' => '',
				'new_product_custom_text' => '',
				'enable_new_product_style' => 'ribbon',
				'new_product_custom_text_fontcolor' => '#ffffff',
				'new_product_custom_text_backcolor' => '#000000',
				'new_product_custom_sticker' => ''
		), $this->new_product_settings );
		
		$this->sale_product_settings = array_merge ( array (
				'enable_sale_product_sticker' => 'no',
				'sale_product_position' => 'right',
				'sale_product_option' => '',
				'sale_product_custom_text' => '',
				'enable_sale_product_style' => 'ribbon',
				'sale_product_custom_text_fontcolor' => '#ffffff',
				'sale_product_custom_text_backcolor' => '#000000',
				'sale_product_custom_sticker' => '' 
		), $this->sale_product_settings );
		
		$this->sold_product_settings = array_merge ( array (
				'enable_sold_product_sticker' => 'no',
				'sold_product_position' => 'left',
				'sold_product_option' => '',
				'sold_product_custom_text' => '',
				'enable_sold_product_style' => 'ribbon',
				'sold_product_custom_text_fontcolor' => '#ffffff',
				'sold_product_custom_text_backcolor' => '#000000',
				'sold_product_custom_sticker' => ''
		), $this->sold_product_settings );

		$this->cust_product_settings = array_merge ( array (
				'enable_cust_product_sticker' => 'no',
				'cust_product_position' => 'left',
				'cust_product_option' => '',
				'cust_product_custom_text' => '',
				'enable_cust_product_style' => 'ribbon',
				'cust_product_custom_text_fontcolor' => '#ffffff',
				'cust_product_custom_text_backcolor' => '#000000',
				'cust_product_custom_sticker' => ''
		), $this->cust_product_settings );

		//Check if custom css exists & action to load custom css on frontend head
		if( !empty( $this->general_settings['custom_css'] ) ) {
			add_action( 'wp_head', array( $this, 'load_custom_css' ), 99 );			
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Stickers_By_Webline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Stickers_By_Webline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-stickers-by-webline-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Stickers_By_Webline_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Stickers_By_Webline_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-stickers-by-webline-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Override New product stickers options level
	 */
	public function override_np_sticker_level_settings( $settings ) {

		global $post;

		$id = !empty( $post->ID ) ? $post->ID : '';

		//If empty then return AS received
		if( empty( $id ) ) return $settings;

		$enable_np_sticker 	= get_post_meta( $id, '_enable_np_sticker', true );
		if( $enable_np_sticker == 'yes' ) {
			$settings['enable_new_product_sticker'] = 'yes';
			$np_no_of_days 		= get_post_meta( $id, '_np_no_of_days', true );
			if( !empty( $np_no_of_days ) ) $settings['new_product_sticker_days'] = $np_no_of_days;
			$np_sticker_pos 	= get_post_meta( $id, '_np_sticker_pos', true );
			if( !empty( $np_sticker_pos ) ) $settings['new_product_position'] = $np_sticker_pos;
			
			$np_product_option = get_post_meta( $id, '_np_product_option', true );
			if( !empty( $np_product_option ) ) $settings['new_product_option'] = $np_product_option;
			if($np_product_option == 'text') {
				$np_product_custom_text = get_post_meta( $id, '_np_product_custom_text', true );
				if( !empty( $np_product_custom_text ) ) $settings['new_product_custom_text'] = $np_product_custom_text;
				$np_sticker_type = get_post_meta( $id, '_np_sticker_type', true );
				if( !empty( $np_sticker_type ) ) $settings['enable_new_product_style'] = $np_sticker_type;
				$np_product_custom_text_fontcolor = get_post_meta( $id, '_np_product_custom_text_fontcolor', true );
				if( !empty( $np_product_custom_text_fontcolor ) ) $settings['new_product_custom_text_fontcolor'] = $np_product_custom_text_fontcolor;
				$np_product_custom_text_backcolor = get_post_meta( $id, '_np_product_custom_text_backcolor', true );
				if( !empty( $np_product_custom_text_backcolor ) ) $settings['new_product_custom_text_backcolor'] = $np_product_custom_text_backcolor;
			} else if($np_product_option == 'image') {
				$np_sticker_custom_id = get_post_meta( $id, '_np_sticker_custom_id', true );
				if( !empty( $np_sticker_custom_id ) ) $settings['new_product_custom_sticker'] = wp_get_attachment_thumb_url( $np_sticker_custom_id );
			}

			return $settings;
		} elseif ( $enable_np_sticker == 'no' ) {
			$settings['enable_new_product_sticker'] = 'no';
			return $settings;
		}

		// Get categories
		$terms = get_the_terms( $id, 'product_cat' );
		if( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$enable_np_sticker = get_term_meta( $term->term_id, 'enable_np_sticker', true );
				if( !empty( $enable_np_sticker ) ) {
					if( $enable_np_sticker == 'yes' ) {
						$settings['enable_new_product_sticker'] = 'yes';
						$np_no_of_days 	= get_term_meta( $term->term_id, 'np_no_of_days', true );
						if( !empty( $np_no_of_days ) ) $settings['new_product_sticker_days'] = $np_no_of_days;
						$np_sticker_pos = get_term_meta( $term->term_id, 'np_sticker_pos', true );
						if( !empty( $np_sticker_pos ) ) $settings['new_product_position'] = $np_sticker_pos;
						$np_product_option = get_term_meta( $term->term_id, 'np_product_option', true );
						if( !empty( $np_product_option ) ) $settings['new_product_option'] = $np_product_option;
						if($np_product_option == 'text') {
							$np_product_custom_text = get_term_meta( $term->term_id, 'np_product_custom_text', true );
							if( !empty( $np_product_custom_text ) ) $settings['new_product_custom_text'] = $np_product_custom_text;
							$np_sticker_type = get_term_meta( $term->term_id, 'np_sticker_type', true );
							if( !empty( $np_sticker_type ) ) $settings['enable_new_product_style'] = $np_sticker_type;
							$np_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'np_product_custom_text_fontcolor', true );
							if( !empty( $np_product_custom_text_fontcolor ) ) $settings['new_product_custom_text_fontcolor'] = $np_product_custom_text_fontcolor;
							$np_product_custom_text_backcolor = get_term_meta( $term->term_id, 'np_product_custom_text_backcolor', true );
							if( !empty( $np_product_custom_text_backcolor ) ) $settings['new_product_custom_text_backcolor'] = $np_product_custom_text_backcolor;
						} else if($np_product_option == 'image') {
							$np_sticker_custom_id = get_term_meta( $term->term_id, 'np_sticker_custom_id', true );
							if( !empty( $np_sticker_custom_id ) ) $settings['new_product_custom_sticker'] = wp_get_attachment_thumb_url( $np_sticker_custom_id );
						}
					} elseif ( $enable_np_sticker == 'no' ) {
						$settings['enable_new_product_sticker'] = 'no';
					}
					break;
				}
			}
		}

		return $settings;
	}

	/**
	 * Override sale product stickers options level
	 */
	public function override_pos_sticker_level_settings( $settings ) {

		global $post;

		$id = !empty( $post->ID ) ? $post->ID : '';

		//If empty then return AS received
		if( empty( $id ) ) return $settings;

		$enable_pos_sticker 	= get_post_meta( $id, '_enable_pos_sticker', true );
		if( $enable_pos_sticker == 'yes' ) {
			$settings['enable_sale_product_sticker'] = 'yes';
			$pos_sticker_pos 	= get_post_meta( $id, '_pos_sticker_pos', true );
			if( !empty( $pos_sticker_pos ) ) $settings['sale_product_position'] = $pos_sticker_pos;
			
			$pos_product_option = get_post_meta( $id, '_pos_product_option', true );
			if( !empty( $pos_product_option ) ) $settings['sale_product_option'] = $pos_product_option;
			if($pos_product_option == 'text') {
				$pos_product_custom_text = get_post_meta( $id, '_pos_product_custom_text', true );
				if( !empty( $pos_product_custom_text ) ) $settings['sale_product_custom_text'] = $pos_product_custom_text;
				$pos_sticker_type = get_post_meta( $id, '_pos_sticker_type', true );
				if( !empty( $pos_sticker_type ) ) $settings['enable_sale_product_style'] = $pos_sticker_type;
				$pos_product_custom_text_fontcolor = get_post_meta( $id, '_pos_product_custom_text_fontcolor', true );
				if( !empty( $pos_product_custom_text_fontcolor ) ) $settings['sale_product_custom_text_fontcolor'] = $pos_product_custom_text_fontcolor;
				$pos_product_custom_text_backcolor = get_post_meta( $id, '_pos_product_custom_text_backcolor', true );
				if( !empty( $pos_product_custom_text_backcolor ) ) $settings['sale_product_custom_text_backcolor'] = $pos_product_custom_text_backcolor;
			} else if($pos_product_option == 'image') {
				$pos_sticker_custom_id = get_post_meta( $id, '_pos_sticker_custom_id', true );
				if( !empty( $pos_sticker_custom_id ) ) $settings['sale_product_custom_sticker'] = wp_get_attachment_thumb_url( $pos_sticker_custom_id );
			}

			return $settings;
		} elseif ( $enable_pos_sticker == 'no' ) {
			$settings['enable_sale_product_sticker'] = 'no';
			return $settings;
		}

		// Get categories
		$terms = get_the_terms( $id, 'product_cat' );
		if( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$enable_pos_sticker = get_term_meta( $term->term_id, 'enable_pos_sticker', true );
				if( !empty( $enable_pos_sticker ) ) {
					if( $enable_pos_sticker == 'yes' ) {
						$settings['enable_sale_product_sticker'] = 'yes';
						$pos_sticker_pos = get_term_meta( $term->term_id, 'pos_sticker_pos', true );
						if( !empty( $pos_sticker_pos ) ) $settings['sale_product_position'] = $pos_sticker_pos;
						
						$pos_product_option = get_term_meta( $term->term_id, 'pos_product_option', true );
						if( !empty( $pos_product_option ) ) $settings['sale_product_option'] = $pos_product_option;
						if($pos_product_option == 'text') {
							$pos_product_custom_text = get_term_meta( $term->term_id, 'pos_product_custom_text', true );
							if( !empty( $pos_product_custom_text ) ) $settings['sale_product_custom_text'] = $pos_product_custom_text;
							$pos_sticker_type = get_term_meta( $term->term_id, 'pos_sticker_type', true );
							if( !empty( $pos_sticker_type ) ) $settings['enable_sale_product_style'] = $pos_sticker_type;
							$pos_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'pos_product_custom_text_fontcolor', true );
							if( !empty( $pos_product_custom_text_fontcolor ) ) $settings['sale_product_custom_text_fontcolor'] = $pos_product_custom_text_fontcolor;
							$pos_product_custom_text_backcolor = get_term_meta( $term->term_id, 'pos_product_custom_text_backcolor', true );
							if( !empty( $pos_product_custom_text_backcolor ) ) $settings['sale_product_custom_text_backcolor'] = $pos_product_custom_text_backcolor;
						} else if($pos_product_option == 'image') {
							$pos_sticker_custom_id = get_term_meta( $term->term_id, 'pos_sticker_custom_id', true );
							if( !empty( $pos_sticker_custom_id ) ) $settings['sale_product_custom_sticker'] = wp_get_attachment_thumb_url( $pos_sticker_custom_id );
						}

					} elseif ( $enable_pos_sticker == 'no' ) {
						$settings['enable_sale_product_sticker'] = 'no';
					}
					break;
				}
			}
		}

		return $settings;
	}

	/**
	 * Override soldout product stickers options level
	 */
	public function override_sop_sticker_level_settings( $settings ) {

		global $post;

		$id = !empty( $post->ID ) ? $post->ID : '';

		//If empty then return AS received
		if( empty( $id ) ) return $settings;

		$enable_sop_sticker 	= get_post_meta( $id, '_enable_sop_sticker', true );
		if( $enable_sop_sticker == 'yes' ) {
			$settings['enable_sold_product_sticker'] = 'yes';
			$sop_sticker_pos 	= get_post_meta( $id, '_sop_sticker_pos', true );
			if( !empty( $sop_sticker_pos ) ) $settings['sold_product_position'] = $sop_sticker_pos;
			
			$sop_product_option = get_post_meta( $id, '_sop_product_option', true );
			if( !empty( $sop_product_option ) ) $settings['sold_product_option'] = $sop_product_option;
			if($sop_product_option == 'text') {
				$sop_product_custom_text = get_post_meta( $id, '_sop_product_custom_text', true );
				if( !empty( $sop_product_custom_text ) ) $settings['sold_product_custom_text'] = $sop_product_custom_text;
				$sop_sticker_type = get_post_meta( $id, '_sop_sticker_type', true );
				if( !empty( $sop_sticker_type ) ) $settings['enable_sold_product_style'] = $sop_sticker_type;
				$sop_product_custom_text_fontcolor = get_post_meta( $id, '_sop_product_custom_text_fontcolor', true );
				if( !empty( $sop_product_custom_text_fontcolor ) ) $settings['sold_product_custom_text_fontcolor'] = $sop_product_custom_text_fontcolor;
				$sop_product_custom_text_backcolor = get_post_meta( $id, '_sop_product_custom_text_backcolor', true );
				if( !empty( $sop_product_custom_text_backcolor ) ) $settings['sold_product_custom_text_backcolor'] = $sop_product_custom_text_backcolor;
			} else if($sop_product_option == 'image') {
				$sop_sticker_custom_id = get_post_meta( $id, '_sop_sticker_custom_id', true );
				if( !empty( $sop_sticker_custom_id ) ) $settings['sold_product_custom_sticker'] = wp_get_attachment_thumb_url( $sop_sticker_custom_id );
			}

			return $settings;
		} elseif ( $enable_sop_sticker == 'no' ) {
			$settings['enable_sold_product_sticker'] = 'no';
			return $settings;
		}

		// Get categories
		$terms = get_the_terms( $id, 'product_cat' );
		if( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$enable_sop_sticker = get_term_meta( $term->term_id, 'enable_sop_sticker', true );
				if( !empty( $enable_sop_sticker ) ) {
					if( $enable_sop_sticker == 'yes' ) {
						$settings['enable_sold_product_sticker'] = 'yes';
						$sop_sticker_pos = get_term_meta( $term->term_id, 'sop_sticker_pos', true );
						if( !empty( $sop_sticker_pos ) ) $settings['sold_product_position'] = $sop_sticker_pos;
						
						$sop_product_option = get_term_meta( $term->term_id, 'sop_product_option', true );
						if( !empty( $sop_product_option ) ) $settings['sold_product_option'] = $sop_product_option;
						if($sop_product_option == 'text') {
							$sop_product_custom_text = get_term_meta( $term->term_id, 'sop_product_custom_text', true );
							if( !empty( $sop_product_custom_text ) ) $settings['sold_product_custom_text'] = $sop_product_custom_text;
							$sop_sticker_type = get_term_meta( $term->term_id, 'sop_sticker_type', true );
							if( !empty( $sop_sticker_type ) ) $settings['enable_sold_product_style'] = $sop_sticker_type;
							$sop_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'sop_product_custom_text_fontcolor', true );
							if( !empty( $sop_product_custom_text_fontcolor ) ) $settings['sold_product_custom_text_fontcolor'] = $sop_product_custom_text_fontcolor;
							$sop_product_custom_text_backcolor = get_term_meta( $term->term_id, 'sop_product_custom_text_backcolor', true );
							if( !empty( $sop_product_custom_text_backcolor ) ) $settings['sold_product_custom_text_backcolor'] = $sop_product_custom_text_backcolor;
						} else if($sop_product_option == 'image') {
							$sop_sticker_custom_id = get_term_meta( $term->term_id, 'sop_sticker_custom_id', true );
							if( !empty( $sop_sticker_custom_id ) ) $settings['sold_product_custom_sticker'] = wp_get_attachment_thumb_url( $sop_sticker_custom_id );
						}

					} elseif ( $enable_sop_sticker == 'no' ) {
						$settings['enable_sold_product_sticker'] = 'no';
					}
					break;
				}
			}
		}

		return $settings;
	}

	/**
	 * Override Custom Product Sticker options level
	 */
	public function override_cust_sticker_level_settings( $settings ) {

		global $post;

		$id = !empty( $post->ID ) ? $post->ID : '';

		//If empty then return AS received
		if( empty( $id ) ) return $settings;

		$enable_cust_sticker 	= get_post_meta( $id, '_enable_cust_sticker', true );
		if( $enable_cust_sticker == 'yes' ) {
			$settings['enable_cust_product_sticker'] = 'yes';
			$cust_sticker_pos 	= get_post_meta( $id, '_cust_sticker_pos', true );
			if( !empty( $cust_sticker_pos ) ) $settings['cust_product_position'] = $cust_sticker_pos;

			$cust_product_option = get_post_meta( $id, '_cust_product_option', true );
			if( !empty( $cust_product_option ) ) $settings['cust_product_option'] = $cust_product_option;
			if($cust_product_option == 'text') {
				$cust_product_custom_text = get_post_meta( $id, '_cust_product_custom_text', true );
				if( !empty( $cust_product_custom_text ) ) $settings['cust_product_custom_text'] = $cust_product_custom_text;
				$cust_sticker_type = get_post_meta( $id, '_cust_sticker_type', true );
				if( !empty( $cust_sticker_type ) ) $settings['enable_cust_product_style'] = $cust_sticker_type;
				$cust_product_custom_text_fontcolor = get_post_meta( $id, '_cust_product_custom_text_fontcolor', true );
				if( !empty( $cust_product_custom_text_fontcolor ) ) $settings['cust_product_custom_text_fontcolor'] = $cust_product_custom_text_fontcolor;
				$cust_product_custom_text_backcolor = get_post_meta( $id, '_cust_product_custom_text_backcolor', true );
				if( !empty( $cust_product_custom_text_backcolor ) ) $settings['cust_product_custom_text_backcolor'] = $cust_product_custom_text_backcolor;
			} else if($cust_product_option == 'image') {
				$cust_sticker_custom_id = get_post_meta( $id, '_cust_sticker_custom_id', true );
				if( !empty( $cust_sticker_custom_id ) ) $settings['cust_product_custom_sticker'] = wp_get_attachment_thumb_url( $cust_sticker_custom_id );
			}
			
			return $settings;
		} elseif ( $enable_cust_sticker == 'no' ) {
			$settings['enable_cust_product_sticker'] = 'no';
			return $settings;
		}

		// Get categories
		$terms = get_the_terms( $id, 'product_cat' );
		if( !empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$enable_cust_sticker = get_term_meta( $term->term_id, 'enable_cust_sticker', true );
				if( !empty( $enable_cust_sticker ) ) {
					if( $enable_cust_sticker == 'yes' ) {
						$settings['enable_cust_product_sticker'] = 'yes';
						$cust_sticker_pos = get_term_meta( $term->term_id, 'cust_sticker_pos', true );
						if( !empty( $cust_sticker_pos ) ) $settings['cust_product_position'] = $cust_sticker_pos;	

						$cust_product_option = get_term_meta( $term->term_id, 'cust_product_option', true );
						if( !empty( $cust_product_option ) ) $settings['cust_product_option'] = $cust_product_option;
						if($cust_product_option == 'text') {
							$cust_product_custom_text = get_term_meta( $term->term_id, 'cust_product_custom_text', true );
							if( !empty( $cust_product_custom_text ) ) $settings['cust_product_custom_text'] = $cust_product_custom_text;
							$cust_sticker_type = get_term_meta( $term->term_id, 'cust_sticker_type', true );
							if( !empty( $cust_sticker_type ) ) $settings['enable_cust_product_style'] = $cust_sticker_type;
							$cust_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'cust_product_custom_text_fontcolor', true );
							if( !empty( $cust_product_custom_text_fontcolor ) ) $settings['cust_product_custom_text_fontcolor'] = $cust_product_custom_text_fontcolor;
							$cust_product_custom_text_backcolor = get_term_meta( $term->term_id, 'cust_product_custom_text_backcolor', true );
							if( !empty( $cust_product_custom_text_backcolor ) ) $settings['cust_product_custom_text_backcolor'] = $cust_product_custom_text_backcolor;
						} else if($cust_product_option == 'image') {
							$cust_sticker_custom_id = get_term_meta( $term->term_id, 'cust_sticker_custom_id', true );
							if( !empty( $cust_sticker_custom_id ) ) $settings['cust_product_custom_sticker'] = wp_get_attachment_thumb_url( $cust_sticker_custom_id );
						}
					} elseif ( $enable_cust_sticker == 'no' ) {
						$settings['enable_cust_product_sticker'] = 'no';
					}
					break;
				}
			}
		}

		return $settings;
	}

	/**
	 * Call back function for show new product badge.
	 *
	 * @return void
	 * @param No arguments passed
	 * @author Weblineindia
	 * @since    1.0.0
	 */
	public function show_product_new_badge() {

		//Override sticker options
		$new_product_settings = $this->override_np_sticker_level_settings( $this->new_product_settings );

		if ( ! $this->sold_out && $this->general_settings['enable_sticker'] == "yes" && $new_product_settings['enable_new_product_sticker'] == "yes") 		{

			if((!is_product() && $this->general_settings['enable_sticker_list'] == "yes" ) || (is_product() && $this->general_settings['enable_sticker_detail'] == "yes"))
			{
				$postdate = get_the_time ( 'Y-m-d' );
				$postdatestamp = strtotime ( $postdate );				
				$newness = (($new_product_settings['new_product_sticker_days']=="") ? 10 : trim($new_product_settings['new_product_sticker_days']));		
				$classPosition=(($new_product_settings['new_product_position']=='left')? ((is_product())? " pos_left_detail " : " pos_left " ) : ((is_product())? " pos_right_detail " : " pos_right "));
				$classType = (($new_product_settings['enable_new_product_style']=='ribbon') ? 'woosticker_ribbon' : 'woosticker_round');
	
				if ((time () - (60 * 60 * 24 * $newness)) < $postdatestamp) {
					//// If the product was published within the newness time frame display the new badge 
					if($new_product_settings['new_product_option'] == "text") {

						$class = "woosticker woosticker_new custom_sticker_text";
						echo '<span class="'.$class . $classPosition . $classType .'" style="background-color:' . esc_attr($new_product_settings["new_product_custom_text_backcolor"]) . '; color:' . esc_attr($new_product_settings["new_product_custom_text_fontcolor"]) . ';">'. esc_attr($new_product_settings["new_product_custom_text"]) .'</span>';

					} else if($new_product_settings['new_product_option'] == "image") {
						if($new_product_settings['new_product_custom_sticker']!='') {

							$class = "woosticker woosticker_new custom_sticker_image";
							echo '<span class="' . $class . $classPosition . $classType .'" style="background-image:url('.esc_url($new_product_settings['new_product_custom_sticker']).');"></span>';
						} else {
							$class=(($new_product_settings['new_product_custom_sticker'] =='') ? 
							(($new_product_settings['enable_new_product_style'] == "ribbon") ? 
							(($new_product_settings['new_product_position']=='left') ?
								" woosticker woosticker_new new_ribbon_left ":" woosticker woosticker_new new_ribbon_right ") : 
									(($new_product_settings['new_product_position']=='left') ?
										" woosticker woosticker_new new_round_left ":" woosticker woosticker_new new_round_right ")):"woosticker woosticker_new custom_sticker_image");
							echo '<span class="'. $class . $classPosition. '">' . __ ( 'New', 'woocommerce-new-badge' ) . '</span>';
						}
					} else {
						$class=(($new_product_settings['new_product_custom_sticker'] =='') ? 
							(($new_product_settings['enable_new_product_style'] == "ribbon") ? 
							(($new_product_settings['new_product_position']=='left') ?
								" woosticker woosticker_new new_ribbon_left ":" woosticker woosticker_new new_ribbon_right ") : 
									(($new_product_settings['new_product_position']=='left') ?
										" woosticker woosticker_new new_round_left ":" woosticker woosticker_new new_round_right ")):"woosticker woosticker_new custom_sticker_image");
						echo '<span class="'. $class . $classPosition. '">' . __ ( 'New', 'woocommerce-new-badge' ) . '</span>';
					}
				}

			}
		}
			
	}
	

	/**
	 * Call back function for show sale product badge.
	 *
	 * @return string
	 * @param string $span_class_onsale_sale_woocommerce_span The span class onsale sale woocommerce span.
	 * @param string $post The post.
	 * @param string $product The product.
	 * @author Weblineindia
	 * @since    1.0.0
	 */
	public function show_product_sale_badge($span_class_onsale_sale_woocommerce_span, $post, $product ) {

		//Override sticker options
		$sale_product_settings = $this->override_pos_sticker_level_settings( $this->sale_product_settings );

		if ($this->general_settings['enable_sticker'] == "yes" && $sale_product_settings['enable_sale_product_sticker'] == "yes") {

			if((!is_product() && $this->general_settings['enable_sticker_list'] == "yes" ) || (is_product() && $this->general_settings['enable_sticker_detail'] == "yes"))
			{
				global $product;

				$classSalePosition=(($sale_product_settings['sale_product_position']=='left') ? ((is_product())? " pos_left_detail " : " pos_left " ) : ((is_product())? " pos_right_detail " : " pos_right "));				
				
				$classSaleType = (($sale_product_settings['enable_sale_product_style']=='ribbon') ? 'woosticker_ribbon' : 'woosticker_round');
				
				if ( $product->is_in_stock ()) {
					if($sale_product_settings['sale_product_option'] == "text") {

						$classSale = "woosticker woosticker_sale custom_sticker_text";
						$span_class_onsale_sale_woocommerce_span = '<span class="'.$classSale . $classSalePosition . $classSaleType .'" style="background-color:' . esc_attr($sale_product_settings["sale_product_custom_text_backcolor"]) . '; color:' . esc_attr($sale_product_settings["sale_product_custom_text_fontcolor"]) . ';">'. esc_attr($sale_product_settings["sale_product_custom_text"]) .'</span>';

					} else if($sale_product_settings['sale_product_option'] == "image") {
						if($sale_product_settings['sale_product_custom_sticker']!='') {
							$classSale = "woosticker woosticker_sale custom_sticker_image";
							$span_class_onsale_sale_woocommerce_span = '<span class="' . $classSale . $classSalePosition . $classSaleType .'" style="background-image:url('.esc_url($sale_product_settings['sale_product_custom_sticker']).');"></span>';
						} else {
							$classSale = (($sale_product_settings['sale_product_custom_sticker']=='')?(($sale_product_settings['enable_sale_product_style'] == "ribbon") ? (($sale_product_settings['sale_product_position']=='left')?" woosticker woosticker_sale onsale_ribbon_left ":" woosticker woosticker_sale onsale_ribbon_right ") : (($sale_product_settings['sale_product_position']=='left')?" woosticker woosticker_sale onsale_round_left ":" woosticker woosticker_sale onsale_round_right ")):"woosticker woosticker_sale custom_sticker_image");
							$span_class_onsale_sale_woocommerce_span =  '<span class="' . $classSale . $classSalePosition . '"> '. __('Sale', 'woo-stickers-by-webline' ) .' </span>';
						}
					} else {
						$classSale = (($sale_product_settings['sale_product_custom_sticker']=='')?(($sale_product_settings['enable_sale_product_style'] == "ribbon") ? (($sale_product_settings['sale_product_position']=='left')?" woosticker woosticker_sale onsale_ribbon_left ":" woosticker woosticker_sale onsale_ribbon_right ") : (($sale_product_settings['sale_product_position']=='left')?" woosticker woosticker_sale onsale_round_left ":" woosticker woosticker_sale onsale_round_right ")):"woosticker woosticker_sale custom_sticker_image");
						$span_class_onsale_sale_woocommerce_span =  '<span class="' . $classSale . $classSalePosition . '"> '. __('Sale', 'woo-stickers-by-webline' ) .' </span>';
					}
				}
				else {
					$sold_product_settings = $this->override_sop_sticker_level_settings( $this->sold_product_settings );
					if($sold_product_settings['enable_sold_product_sticker']=="yes") {
						$span_class_onsale_sale_woocommerce_span='';
					}
				}
			}
		}
		
		return $span_class_onsale_sale_woocommerce_span;
	}

	/**
	 * Call back function for show sold product badge on list.
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 * @since    1.0.0
	 */
	public function show_product_soldout_badge()
	{	 

		//Override sticker options
		$sold_product_settings = $this->override_sop_sticker_level_settings( $this->sold_product_settings );

		$this->sold_out = false;//Initially set as not sold
		if ($this->general_settings['enable_sticker'] == "yes" && $sold_product_settings['enable_sold_product_sticker'] == "yes") {

			if((!is_product() && $this->general_settings['enable_sticker_list'] == "yes" ) || (is_product() && $this->general_settings['enable_sticker_detail'] == "yes"))	{
				
				global $product;
					
				$classSoldPosition=(($sold_product_settings['sold_product_position']=='left') ? ((is_product())? " pos_left_detail " : " pos_left " ) : ((is_product())? " pos_right_detail " : " pos_right "));	
				
				$classSoldType = (($sold_product_settings['enable_sold_product_style']=='ribbon') ? 'woosticker_ribbon' : 'woosticker_round');

				if( $product->get_type('product_type') == 'variable' ) {

					$total_qty=0;
					
					$available_variations = $product->get_available_variations();
				   
					foreach ($available_variations as $variation) {

						if($variation['is_in_stock']==true){
							$total_qty++;
						}
						
					}

					if($total_qty==0){
						if($sold_product_settings['enable_sold_product_sticker']=="yes") {
							if($sold_product_settings['sold_product_option'] == "text") {

								$classSold = "woosticker woosticker_sold custom_sticker_text";
								echo '<span class="'.$classSold . $classSoldPosition . $classSoldType .'" style="background-color:' . esc_attr($sold_product_settings["sold_product_custom_text_backcolor"]) . '; color:' . esc_attr($sold_product_settings["sold_product_custom_text_fontcolor"]) . ';">'. esc_attr($sold_product_settings["sold_product_custom_text"]) .'</span>';

							} else if($sold_product_settings['sold_product_option'] == "image") {
								if($sold_product_settings['sold_product_custom_sticker']!='') {
									$classSold = "woosticker woosticker_sold custom_sticker_image";
									echo '<span class="' . $classSold . $classSoldPosition . $classSoldType .'" style="background-image:url('.esc_url($sold_product_settings['sold_product_custom_sticker']).');"></span>';
								} else {
									$classSold = (($sold_product_settings['sold_product_custom_sticker']=='')?(($sold_product_settings['enable_sold_product_style'] == "ribbon") ? (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_ribbon_left ":" woosticker woosticker_sold soldout_ribbon_right ") : (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_round_left ":" woosticker woosticker_sold soldout_round_right ")):"woosticker woosticker_sold custom_sticker_image");
									echo '<span class="'.$classSold . $classSoldPosition .'">'. __('Sold Out', 'woo-stickers-by-webline' ) .'</span>';
								}
							} else {
								$classSold = (($sold_product_settings['sold_product_custom_sticker']=='')?(($sold_product_settings['enable_sold_product_style'] == "ribbon") ? (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_ribbon_left ":" woosticker woosticker_sold soldout_ribbon_right ") : (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_round_left ":" woosticker woosticker_sold soldout_round_right ")):"woosticker woosticker_sold custom_sticker_image");
								echo '<span class="'.$classSold . $classSoldPosition .'">'. __('Sold Out', 'woo-stickers-by-webline' ) .'</span>';
							}

							$this->sold_out = true;//Set as SOLD OUT
						}
					}				

				}
				else {

					if (! $product->is_in_stock ()) {
						if($sold_product_settings['enable_sold_product_sticker']=="yes") {
							if($sold_product_settings['sold_product_option'] == "text") {

								$classSold = "woosticker woosticker_sold custom_sticker_text";
								echo '<span class="'.$classSold . $classSoldPosition . $classSoldType .'" style="background-color:' . esc_attr($sold_product_settings["sold_product_custom_text_backcolor"]) . '; color:' . esc_attr($sold_product_settings["sold_product_custom_text_fontcolor"]) . ';">'. esc_attr($sold_product_settings["sold_product_custom_text"]) .'</span>';
							} else if($sold_product_settings['sold_product_option'] == "image") {
								if($sold_product_settings['sold_product_custom_sticker']!='') {

									$classSold = "woosticker woosticker_sold custom_sticker_image";
									echo '<span class="' . $classSold . $classSoldPosition . $classSoldType .'" style="background-image:url('.esc_url($sold_product_settings['sold_product_custom_sticker']).');"></span>';
								} else {
									$classSold = (($sold_product_settings['sold_product_custom_sticker']=='')?(($sold_product_settings['enable_sold_product_style'] == "ribbon") ? (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_ribbon_left ":" woosticker woosticker_sold soldout_ribbon_right ") : (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_round_left ":" woosticker woosticker_sold soldout_round_right ")):"woosticker woosticker_sold custom_sticker_image");
									echo '<span class="'.$classSold . $classSoldPosition .'">'. __('Sold Out', 'woo-stickers-by-webline' ) .'</span>';
								}
							} else {
								$classSold = (($sold_product_settings['sold_product_custom_sticker']=='')?(($sold_product_settings['enable_sold_product_style'] == "ribbon") ? (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_ribbon_left ":" woosticker woosticker_sold soldout_ribbon_right ") : (($sold_product_settings['sold_product_position']=='left')?" woosticker woosticker_sold soldout_round_left ":" woosticker woosticker_sold soldout_round_right ")):"woosticker woosticker_sold custom_sticker_image");
								echo '<span class="'.$classSold . $classSoldPosition .'">'. __('Sold Out', 'woo-stickers-by-webline' ) .'</span>';
							}

							$this->sold_out = true;//Set as SOLD OUT
						}
					}
				}
			}
		}
	}

	/**
	 * Call back function for show Custom Product Sticker badge.
	 *
	 * @return string
	 * @param string $span_class_onsale_sale_woocommerce_span The span class onsale sale woocommerce span.
	 * @param string $post The post.
	 * @param string $product The product.
	 * @author Weblineindia
	 * @since    1.0.0
	 */
	public function show_product_cust_badge( $span_class_custom_woocommerce_span ) {

		//Override sticker options
		$cust_product_settings = $this->override_cust_sticker_level_settings( $this->cust_product_settings );

		if ($this->general_settings['enable_sticker'] == "yes" && $cust_product_settings['enable_cust_product_sticker'] == "yes") {

			if((!is_product() && $this->general_settings['enable_sticker_list'] == "yes" ) || (is_product() && $this->general_settings['enable_sticker_detail'] == "yes"))
			{
				global $product;

				$classCustomPosition=(($cust_product_settings['cust_product_position']=='left') ? ((is_product())? " pos_left_detail " : " pos_left " ) : ((is_product())? " pos_right_detail " : " pos_right "));
				$classCustomType = (($cust_product_settings['enable_cust_product_style']=='ribbon') ? 'woosticker_ribbon' : 'woosticker_round');	

				if($cust_product_settings['cust_product_option'] == "text") {

					$classCustom = "woosticker woosticker_custom custom_sticker_text";
					echo $span_class_custom_woocommerce_span = '<span class="'.$classCustom . $classCustomPosition . $classCustomType . '" style="background-color:' . esc_attr($cust_product_settings["cust_product_custom_text_backcolor"]) . '; color:' . esc_attr($cust_product_settings["cust_product_custom_text_fontcolor"]) . ';">'. esc_attr($cust_product_settings["cust_product_custom_text"]) .'</span>';

				} else if($cust_product_settings['cust_product_option'] == "image" && $cust_product_settings['cust_product_custom_sticker']!='') {

					$classCustom = "woosticker woosticker_custom custom_sticker_image";
					echo $span_class_custom_woocommerce_span =  '<span class="' . $classCustom . $classCustomPosition . $classCustomType . '" style="background-image:url('.esc_url($cust_product_settings['cust_product_custom_sticker']).');"></span>';
				}
			}
		}

		return $span_class_custom_woocommerce_span;
	}

	/**
	 * Display category badge on bases of sticker settings.
	 *
	 * @author Weblineindia
	 * @since    1.1.5
	 */
	public function show_category_badge( $category ) {

		//Check if category exists and sticker enabled
		if( $this->general_settings['enable_sticker'] == "yes" && !empty( $category->term_id ) ) {

			//Get & category sticker enabled?
			$enable_category_sticker = get_term_meta( $category->term_id, 'enable_category_sticker', true );
			if( $enable_category_sticker == 'yes' ) {

				//Get category options
				$sticker_pos 	= get_term_meta( $category->term_id, 'category_sticker_pos', true );
				$sticker_option = get_term_meta( $category->term_id, 'category_sticker_option', true );
				$sticker_text 	= get_term_meta( $category->term_id, 'category_sticker_text', true );
				$sticker_type 	= get_term_meta( $category->term_id, 'category_sticker_type', true );
				$sticker_text_fontcolor = get_term_meta( $category->term_id, 'category_sticker_text_fontcolor', true );
				$sticker_text_backcolor = get_term_meta( $category->term_id, 'category_sticker_text_backcolor', true );
				$sticker_image_id = get_term_meta( $category->term_id, 'category_sticker_image_id', true );
				$sticker_image 	  = wp_get_attachment_image_src( $sticker_image_id, 'thumbnail' );

				$sticker_class = 'woosticker category_sticker ';
				$sticker_class .= $sticker_pos == 'left' ? 'pos_left ' : 'pos_right ';
				$sticker_class .= $sticker_type == 'ribbon' ? 'woosticker_ribbon ' : 'woosticker_round ';

				//Check if sticker text exists
				if( $sticker_option == 'text' && !empty( $sticker_text ) ) {

					echo '<span class="'. $sticker_class .'custom_sticker_text" style="background-color:'. esc_attr($sticker_text_backcolor) .'; color:'. esc_attr($sticker_text_fontcolor) .';">'. esc_attr( $sticker_text ) .'</span>';

				} elseif ( !empty( $sticker_image[0] ) ) {//Check if sticker image exists

					echo '<span class="'. $sticker_class .'custom_sticker_image" style="background-image:url('. esc_url($sticker_image[0]) .');"></span>';
				}
			}
		}
	}

	/**
	 * Load Custom CSS on frontend header
	 *
	 * @author Weblineindia
	 * @since    1.1.5
	 */
	public function load_custom_css() {

		//Check screen where custom css requred
		$display = false;
		if( is_shop() || is_product() || is_product_category() ) $display = true;

		//Check if load custom CSS where needed
		if( apply_filters( 'woosticker_display_custom_css', $display ) ) {
			echo '<style type="text/css">'. apply_filters( 'woosticker_load_custom_css', $this->general_settings['custom_css'] ) .'</style>';
		}
	}
}
