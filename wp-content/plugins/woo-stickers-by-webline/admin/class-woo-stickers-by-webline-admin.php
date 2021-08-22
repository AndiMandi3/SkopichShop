<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.weblineindia.com
 * @since      1.0.0
 *
 * @package    Woo_Stickers_By_Webline
 * @subpackage Woo_Stickers_By_Webline/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Stickers_By_Webline
 * @subpackage Woo_Stickers_By_Webline/admin
 * @author     Weblineindia <info@weblineindia.com>
 */
class Woo_Stickers_By_Webline_Admin {

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
	private $plugin_options_key = 'wli-stickers';
	private $plugin_settings_tabs = array ();


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_settings ();
		$widget_ops = array (
				'classname' => 'wli_woo_stickers',
				'description' => __( "WLI Woocommerce Stickers", 'woo-stickers-by-webline' ) 
		);

		// Add form
		add_action( 'product_cat_add_form_fields', array( $this, 'add_category_fields' ), 11 );
		add_action( 'product_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 11 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 10, 3 );

		add_filter( 'woocommerce_product_data_tabs', array( $this, 'sticker_settings_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_sticker_panels' ) );
		add_action( 'woocommerce_process_product_meta_simple', array( $this, 'save_sticker_option_fields'));
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'save_sticker_option_fields'));

		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
	}

	public function sticker_settings_tabs( $tabs ) {

		$tabs['woo_stickers'] = array(
			'label'    => __( 'Stickers', 'woo-stickers-by-webline' ),
			'target'   => 'woo_stickers_data',
			'class'    => array('show_if_virtual1'),
			'priority' => 99,
		);
		return $tabs;
	}

	public function product_sticker_panels() {

		global $post;

		//Get placeholder image
		$placeholder_img = wc_placeholder_img_src();

		//Get new product sticker
		$np_sticker_custom_id = get_post_meta( $post->ID, '_np_sticker_custom_id', true );
		if ( $np_sticker_custom_id ) {
			$np_image = wp_get_attachment_thumb_url( $np_sticker_custom_id );
		} else {
			$np_image = $placeholder_img;
		}

		//Get on sale product sticker
		$pos_sticker_custom_id = get_post_meta( $post->ID, '_pos_sticker_custom_id', true );
		if ( $pos_sticker_custom_id ) {
			$pos_image = wp_get_attachment_thumb_url( $pos_sticker_custom_id );
		} else {
			$pos_image = $placeholder_img;
		}

		//Get soldout product sticker
		$sop_sticker_custom_id = get_post_meta( $post->ID, '_sop_sticker_custom_id', true );
		if ( $sop_sticker_custom_id ) {
			$sop_image = wp_get_attachment_thumb_url( $sop_sticker_custom_id );
		} else {
			$sop_image = $placeholder_img;
		}

		//Get custom sticker for products
		$cust_sticker_custom_id = get_post_meta( $post->ID, '_cust_sticker_custom_id', true );
		if ( $cust_sticker_custom_id ) {
			$cust_image = wp_get_attachment_thumb_url( $cust_sticker_custom_id );
		} else {
			$cust_image = $placeholder_img;
		}

		echo '<div id="woo_stickers_data" class="panel woocommerce_options_panel hidden wsbw-sticker-options-wrap">';
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#wsbw_new_products"><?php _e( "New Products", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_products_sale"><?php _e( "Products On Sale", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_soldout_products"><?php _e( "Soldout Products", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_cust_products"><?php _e( "Custom Product Sticker", 'woo-stickers-by-webline' );?></a>
		</h2>
		<div id="wsbw_new_products" class="wsbw_tab_content">
			<?php
			$np_product_option = get_post_meta( $post->ID, '_np_product_option', true ); 
			$np_product_custom_text_fontcolor = get_post_meta( $post->ID, '_np_product_custom_text_fontcolor', true ); 
			$np_product_custom_text_backcolor = get_post_meta( $post->ID, '_np_product_custom_text_backcolor', true ); 
			if($np_product_option == "image" || $np_product_option == "") {
				$wliclass = 'wli_none';
			} else {
				$wliclass = 'wli_block';
			}

			woocommerce_wp_select( array(
				'id'          => 'enable_np_sticker',
				'value'       => get_post_meta( $post->ID, '_enable_np_sticker', true ),
				'wrapper_class' => '',
				'label'       => __( 'Enable Sticker:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'yes' => __( 'Yes', 'woo-stickers-by-webline' ), 'no' => __( 'No', 'woo-stickers-by-webline' ) ),
			) );

			woocommerce_wp_text_input( array(
				'id'                => 'np_no_of_days',
				'value'             => get_post_meta( $post->ID, '_np_no_of_days', true ),
				'label'             => __( 'Number of Days for New Product:', 'woo-stickers-by-webline' ),
				'description'       => '<br/><br/>'. __( 'Specify the No of days before to be display product as New, Leave empty or 0 if you want to take from global settings.', 'woo-stickers-by-webline' )
			) );

			woocommerce_wp_select( array(
				'id'          => 'np_sticker_pos',
				'value'       => get_post_meta( $post->ID, '_np_sticker_pos', true ),
				'wrapper_class' => '',
				'label'       => __( 'Sticker Position:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'left' => __( 'Left', 'woo-stickers-by-webline' ), 'right' => __( 'Right', 'woo-stickers-by-webline' ) ),
			) );
			?>

			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt np_product_option">
					<label for="np_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($np_product_option == 'image' || $np_product_option == '') { echo "checked"; } ?> <?php checked( $np_product_option, 'image'); ?>/>
					<label for="image" class="radio-label"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($np_product_option == 'text') { echo "checked"; } ?> <?php checked( $np_product_option, 'text'); ?>/>
					<label for="text" class="radio-label"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" id="np_product_option" class="wli_product_option" name="np_product_option" value="<?php if($np_product_option == '') { echo "image"; } else { echo esc_attr( $np_product_option ); } ?>"/>
				</div>
			</div>

		    <?php woocommerce_wp_text_input( array(
				'id'                => 'np_product_custom_text',
				'value'             => get_post_meta( $post->ID, '_np_product_custom_text', true ),
				'wrapper_class' 	=> 'custom_option custom_opttext ' . $wliclass,
				'label'             => __( 'Custom Sticker Text:', 'woo-stickers-by-webline' ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'np_sticker_type',
				'value'       => get_post_meta( $post->ID, '_np_sticker_type', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclass,
				'label'       => __( 'Custom Sticker Type:', 'woo-stickers-by-webline' ),
				'options'     => array( 'ribbon' => __( 'Ribbon', 'woo-stickers-by-webline' ), 'round' => __( 'Round', 'woo-stickers-by-webline' ) ),
			) );

			?>
			<p class="form-field custom_option custom_opttext" <?php if($np_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="np_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="np_product_custom_text_fontcolor" class="wli_color_picker" name="np_product_custom_text_fontcolor" value="<?php echo ($np_product_custom_text_fontcolor) ? esc_attr( $np_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
			</p>
			<p class="form-field custom_option custom_opttext"<?php if($np_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="np_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="np_product_custom_text_backcolor" class="wli_color_picker" name="np_product_custom_text_backcolor" value="<?php echo esc_attr( $np_product_custom_text_backcolor ); ?>"/>
			</p>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" <?php if($np_product_option == 'image' || $np_product_option == '') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="np_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="np_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $np_image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="np_sticker_custom_id" class="wsbw_upload_img_id" name="np_sticker_custom_id" value="<?php echo absint( $np_sticker_custom_id ); ?>" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>
		<div id="wsbw_products_sale" class="wsbw_tab_content" style="display: none;">
			<?php $pos_product_option = get_post_meta( $post->ID, '_pos_product_option', true ); 
			$pos_product_custom_text_fontcolor = get_post_meta( $post->ID, '_pos_product_custom_text_fontcolor', true ); 
			$pos_product_custom_text_backcolor = get_post_meta( $post->ID, '_pos_product_custom_text_backcolor', true );
			if($pos_product_option == "image" || $pos_product_option == "") {
				$wliclassSale = 'wli_none';
			} else {
				$wliclassSale = 'wli_block';
			}
			
			woocommerce_wp_select( array(
				'id'          => 'enable_pos_sticker',
				'value'       => get_post_meta( $post->ID, '_enable_pos_sticker', true ),
				'wrapper_class' => '',
				'label'       => __( 'Enable Sticker:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'yes' => __( 'Yes', 'woo-stickers-by-webline' ), 'no' => __( 'No', 'woo-stickers-by-webline' ) ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'pos_sticker_pos',
				'value'       => get_post_meta( $post->ID, '_pos_sticker_pos', true ),
				'wrapper_class' => '',
				'label'       => __( 'Sticker Position:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'left' => __( 'Left', 'woo-stickers-by-webline' ), 'right' => __( 'Right', 'woo-stickers-by-webline' ) ),
			) );

			?>

			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt pos_product_option">
					<label><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="image1" value="image" <?php if($pos_product_option == 'image' || $pos_product_option == '') { echo "checked"; } ?> <?php checked( $pos_product_option, 'image'); ?>/>
					<label for="image1" class="radio-label"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="text1" value="text" <?php if($pos_product_option == 'text') { echo "checked"; } ?> <?php checked( $pos_product_option, 'text'); ?>/>
					<label for="text1" class="radio-label"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" id="pos_product_option" class="wli_product_option" name="pos_product_option" value="<?php if($pos_product_option == '') { echo "image"; } else { echo esc_attr( $pos_product_option ); } ?>"/>
				</div>
			</div>

		    <?php woocommerce_wp_text_input( array(
				'id'                => 'pos_product_custom_text',
				'value'             => get_post_meta( $post->ID, '_pos_product_custom_text', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassSale,
				'label'             => __( 'Custom Sticker Text:', 'woo-stickers-by-webline' ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'pos_sticker_type',
				'value'       => get_post_meta( $post->ID, '_pos_sticker_type', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassSale,
				'label'       => __( 'Custom Sticker Type:', 'woo-stickers-by-webline' ),
				'options'     => array( 'ribbon' => __( 'Ribbon', 'woo-stickers-by-webline' ), 'round' => __( 'Round', 'woo-stickers-by-webline' ) ),
			) );

			?>
			<p class="form-field custom_option custom_opttext" <?php if($pos_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="pos_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="pos_product_custom_text_fontcolor" class="wli_color_picker" name="pos_product_custom_text_fontcolor" value="<?php echo ($pos_product_custom_text_fontcolor) ? esc_attr( $pos_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
			</p>
			<p class="form-field custom_option custom_opttext" <?php if($pos_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="pos_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="pos_product_custom_text_backcolor" class="wli_color_picker" name="pos_product_custom_text_backcolor" value="<?php echo esc_attr( $pos_product_custom_text_backcolor ); ?>"/>
			</p>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" <?php if($pos_product_option == 'image' || $pos_product_option == '') { echo 'style="display:block"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="pos_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="pos_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $pos_image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="pos_sticker_custom_id" class="wsbw_upload_img_id" name="pos_sticker_custom_id" value="<?php echo absint( $pos_sticker_custom_id ); ?>" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>
		<div id="wsbw_soldout_products" class="wsbw_tab_content" style="display: none;">
			<?php $sop_product_option = get_post_meta( $post->ID, '_sop_product_option', true ); 
			$sop_product_custom_text_fontcolor = get_post_meta( $post->ID, '_sop_product_custom_text_fontcolor', true ); 
			$sop_product_custom_text_backcolor = get_post_meta( $post->ID, '_sop_product_custom_text_backcolor', true );
			if($sop_product_option == "image" || $sop_product_option == "") {
				$wliclassSold = 'wli_none';
			} else {
				$wliclassSold = 'wli_block';
			}
			
			woocommerce_wp_select( array(
				'id'          => 'enable_sop_sticker',
				'value'       => get_post_meta( $post->ID, '_enable_sop_sticker', true ),
				'wrapper_class' => '',
				'label'       => __( 'Enable Sticker:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'yes' => __( 'Yes', 'woo-stickers-by-webline' ), 'no' => __( 'No', 'woo-stickers-by-webline' ) ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'sop_sticker_pos',
				'value'       => get_post_meta( $post->ID, '_sop_sticker_pos', true ),
				'wrapper_class' => '',
				'label'       => __( 'Sticker Position:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'left' => __( 'Left', 'woo-stickers-by-webline' ), 'right' => __( 'Right', 'woo-stickers-by-webline' ) ),
			) );
			?>

			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt sop_product_option">
					<label for="sop_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="image2" value="image" <?php if($sop_product_option == 'image' || $sop_product_option == '') { echo 'checked="checked"'; } ?> <?php checked( $sop_product_option, 'image'); ?>/>
					<label for="image2" class="radio-label"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="text2" value="text" <?php if($sop_product_option == 'text') { echo "checked"; } ?> <?php checked( $sop_product_option, 'text'); ?>/>
					<label for="text2" class="radio-label"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" id="sop_product_option" class="wli_product_option" name="sop_product_option" value="<?php if($sop_product_option == '') { echo "image"; } else { echo esc_attr( $sop_product_option ); } ?>"/>
				</div>
			</div>

		    <?php woocommerce_wp_text_input( array(
				'id'                => 'sop_product_custom_text',
				'value'             => get_post_meta( $post->ID, '_sop_product_custom_text', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassSold,
				'label'             => __( 'Custom Sticker Text:', 'woo-stickers-by-webline' ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'sop_sticker_type',
				'value'       => get_post_meta( $post->ID, '_sop_sticker_type', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassSold,
				'label'       => __( 'Custom Sticker Type:', 'woo-stickers-by-webline' ),
				'options'     => array( 'ribbon' => __( 'Ribbon', 'woo-stickers-by-webline' ), 'round' => __( 'Round', 'woo-stickers-by-webline' ) ),
			) );

			?>
			<p class="form-field custom_option custom_opttext" <?php if($sop_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="sop_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="sop_product_custom_text_fontcolor" class="wli_color_picker" name="sop_product_custom_text_fontcolor" value="<?php echo ($sop_product_custom_text_fontcolor) ? esc_attr( $sop_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
			</p>
			<p class="form-field custom_option custom_opttext" <?php if($sop_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="sop_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="sop_product_custom_text_backcolor" class="wli_color_picker" name="sop_product_custom_text_backcolor" value="<?php echo esc_attr( $sop_product_custom_text_backcolor ); ?>"/>
			</p>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" <?php if($sop_product_option == 'image' || $sop_product_option == '') { echo 'style="display:block"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="sop_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="sop_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $sop_image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="sop_sticker_custom_id" class="wsbw_upload_img_id" name="sop_sticker_custom_id" value="<?php echo absint( $sop_sticker_custom_id ); ?>" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>
		<div id="wsbw_cust_products" class="wsbw_tab_content" style="display: none;">
			<?php $cust_product_option = get_post_meta( $post->ID, '_cust_product_option', true ); 
			$cust_product_custom_text_fontcolor = get_post_meta( $post->ID, '_cust_product_custom_text_fontcolor', true ); 
			$cust_product_custom_text_backcolor = get_post_meta( $post->ID, '_cust_product_custom_text_backcolor', true );
			if($cust_product_option == "image" || $cust_product_option == "") {
				$wliclassCustom = 'wli_none';
			} else {
				$wliclassCustom = 'wli_block';
			}
			
			woocommerce_wp_select( array(
				'id'          => 'enable_cust_sticker',
				'value'       => get_post_meta( $post->ID, '_enable_cust_sticker', true ),
				'wrapper_class' => '',
				'label'       => __( 'Enable Custom Sticker:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'yes' => __( 'Yes', 'woo-stickers-by-webline' ), 'no' => __( 'No', 'woo-stickers-by-webline' ) ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'cust_sticker_pos',
				'value'       => get_post_meta( $post->ID, '_cust_sticker_pos', true ),
				'wrapper_class' => '',
				'label'       => __( 'Custom Sticker Position:', 'woo-stickers-by-webline' ),
				'options'     => array( '' => __( 'Default', 'woo-stickers-by-webline' ), 'left' => __( 'Left', 'woo-stickers-by-webline' ), 'right' => __( 'Right', 'woo-stickers-by-webline' ) ),
			) ); ?>

		    <div class="form-field term-thumbnail-wrap">
				<div class="woo_opt cust_product_option">
					<label for="cust_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="image3" value="image" <?php if($cust_product_option == 'image' || $cust_product_option == '') { echo "checked"; } ?> <?php checked( $cust_product_option, 'image'); ?>/>
					<label for="image3" class="radio-label"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="text3" value="text" <?php if($cust_product_option == 'text') { echo "checked"; } ?> <?php checked( $cust_product_option, 'text'); ?>/>
					<label for="text3" class="radio-label"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" id="cust_product_option" class="wli_product_option" name="cust_product_option" value="<?php if($cust_product_option == '') { echo "image"; } else { echo esc_attr( $cust_product_option ); } ?>"/>
				</div>
			</div>

		    <?php woocommerce_wp_text_input( array(
				'id'                => 'cust_product_custom_text',
				'value'             => get_post_meta( $post->ID, '_cust_product_custom_text', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassCustom,
				'label'             => __( 'Custom Sticker Text:', 'woo-stickers-by-webline' ),
			) );

			woocommerce_wp_select( array(
				'id'          => 'cust_sticker_type',
				'value'       => get_post_meta( $post->ID, '_cust_sticker_type', true ),
				'wrapper_class' => 'custom_option custom_opttext ' . $wliclassCustom,
				'label'       => __( 'Custom Sticker Type:', 'woo-stickers-by-webline' ),
				'options'     => array( 'ribbon' => __( 'Ribbon', 'woo-stickers-by-webline' ), 'round' => __( 'Round', 'woo-stickers-by-webline' ) ),
			) );

			?>
			<p class="form-field custom_option custom_opttext" <?php if($cust_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="cust_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="cust_product_custom_text_fontcolor" class="wli_color_picker" name="cust_product_custom_text_fontcolor" value="<?php echo ($cust_product_custom_text_fontcolor) ? esc_attr( $cust_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
			</p>
			<p class="form-field custom_option custom_opttext" <?php if($cust_product_option == 'text') { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="cust_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="cust_product_custom_text_backcolor" class="wli_color_picker" name="cust_product_custom_text_backcolor" value="<?php echo esc_attr( $cust_product_custom_text_backcolor ); ?>"/>
			</p>
			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" <?php if($cust_product_option == 'image' || $cust_product_option == '') { echo 'style="display:block"'; } else { echo 'style="display: none;"'; } ?>>
				<label for="cust_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="cust_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $cust_image ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="cust_sticker_custom_id" class="wsbw_upload_img_id" name="cust_sticker_custom_id" value="<?php echo absint( $cust_sticker_custom_id ); ?>" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>
		<?php
		echo '</div>';
	}

	/**
	 * Save the custom fields.
	 */
	function save_sticker_option_fields( $post_id ) {

		//Save all new product options
		$enable_np_sticker = isset( $_POST['enable_np_sticker'] ) ? sanitize_text_field( $_POST['enable_np_sticker'] ) : '';
		update_post_meta( $post_id, '_enable_np_sticker', $enable_np_sticker );
		$np_no_of_days = isset( $_POST['np_no_of_days'] ) ? absint( $_POST['np_no_of_days'] ) : '';
		update_post_meta( $post_id, '_np_no_of_days', $np_no_of_days );
		$np_sticker_pos = isset( $_POST['np_sticker_pos'] ) ? sanitize_text_field( $_POST['np_sticker_pos'] ) : '';
		update_post_meta( $post_id, '_np_sticker_pos', $np_sticker_pos );

		$np_product_option = isset( $_POST['np_product_option'] ) ? sanitize_key( $_POST['np_product_option'] ) : '';
		update_post_meta( $post_id, '_np_product_option', $np_product_option );

		$np_product_custom_text = isset( $_POST['np_product_custom_text'] ) ? sanitize_text_field( $_POST['np_product_custom_text'] ) : '';
		update_post_meta( $post_id, '_np_product_custom_text', $np_product_custom_text );
		$np_sticker_type = isset( $_POST['np_sticker_type'] ) ? sanitize_text_field( $_POST['np_sticker_type'] ) : '';
		update_post_meta( $post_id, '_np_sticker_type', $np_sticker_type );
		$np_product_custom_text_fontcolor = isset( $_POST['np_product_custom_text_fontcolor'] ) ? sanitize_hex_color( $_POST['np_product_custom_text_fontcolor'] ) : '';
		update_post_meta( $post_id, '_np_product_custom_text_fontcolor', $np_product_custom_text_fontcolor );
		$np_product_custom_text_backcolor = isset( $_POST['np_product_custom_text_backcolor'] ) ? sanitize_hex_color( $_POST['np_product_custom_text_backcolor'] ) : '';
		update_post_meta( $post_id, '_np_product_custom_text_backcolor', $np_product_custom_text_backcolor );

		$np_sticker_custom_id = isset( $_POST['np_sticker_custom_id'] ) ? absint( $_POST['np_sticker_custom_id'] ) : '';
		update_post_meta( $post_id, '_np_sticker_custom_id', $np_sticker_custom_id );

		//Save on sale product options
		$enable_pos_sticker = isset( $_POST['enable_pos_sticker'] ) ? sanitize_text_field( $_POST['enable_pos_sticker'] ) : '';
		update_post_meta( $post_id, '_enable_pos_sticker', $enable_pos_sticker );
		$pos_sticker_pos = isset( $_POST['pos_sticker_pos'] ) ? sanitize_text_field( $_POST['pos_sticker_pos'] ) : '';
		update_post_meta( $post_id, '_pos_sticker_pos', $pos_sticker_pos );

		$pos_product_option = isset( $_POST['pos_product_option'] ) ? sanitize_key( $_POST['pos_product_option'] ) : '';
		update_post_meta( $post_id, '_pos_product_option', $pos_product_option );

		$pos_product_custom_text = isset( $_POST['pos_product_custom_text'] ) ? sanitize_text_field( $_POST['pos_product_custom_text'] ) : '';
		update_post_meta( $post_id, '_pos_product_custom_text', $pos_product_custom_text );
		$pos_sticker_type = isset( $_POST['pos_sticker_type'] ) ? sanitize_text_field( $_POST['pos_sticker_type'] ) : '';
		update_post_meta( $post_id, '_pos_sticker_type', $pos_sticker_type );
		$pos_product_custom_text_fontcolor = isset( $_POST['pos_product_custom_text_fontcolor'] ) ? sanitize_hex_color( $_POST['pos_product_custom_text_fontcolor'] ) : '';
		update_post_meta( $post_id, '_pos_product_custom_text_fontcolor', $pos_product_custom_text_fontcolor );
		$pos_product_custom_text_backcolor = isset( $_POST['pos_product_custom_text_backcolor'] ) ? sanitize_hex_color( $_POST['pos_product_custom_text_backcolor'] ) : '';
		update_post_meta( $post_id, '_pos_product_custom_text_backcolor', $pos_product_custom_text_backcolor );

		$pos_sticker_custom_id = isset( $_POST['pos_sticker_custom_id'] ) ? absint( $_POST['pos_sticker_custom_id'] ) : '';
		update_post_meta( $post_id, '_pos_sticker_custom_id', $pos_sticker_custom_id );

		//Save all new product options
		$enable_sop_sticker = isset( $_POST['enable_sop_sticker'] ) ? sanitize_text_field( $_POST['enable_sop_sticker'] ) : '';
		update_post_meta( $post_id, '_enable_sop_sticker', $enable_sop_sticker );
		$sop_sticker_pos = isset( $_POST['sop_sticker_pos'] ) ? sanitize_text_field( $_POST['sop_sticker_pos'] ) : '';
		update_post_meta( $post_id, '_sop_sticker_pos', $sop_sticker_pos );

		$sop_product_option = isset( $_POST['sop_product_option'] ) ? sanitize_key( $_POST['sop_product_option'] ) : '';
		update_post_meta( $post_id, '_sop_product_option', $sop_product_option );

		$sop_product_custom_text = isset( $_POST['sop_product_custom_text'] ) ? sanitize_text_field( $_POST['sop_product_custom_text'] ) : '';
		update_post_meta( $post_id, '_sop_product_custom_text', $sop_product_custom_text );
		$sop_sticker_type = isset( $_POST['sop_sticker_type'] ) ? sanitize_text_field( $_POST['sop_sticker_type'] ) : '';
		update_post_meta( $post_id, '_sop_sticker_type', $sop_sticker_type );
		$sop_product_custom_text_fontcolor = isset( $_POST['sop_product_custom_text_fontcolor'] ) ? sanitize_hex_color( $_POST['sop_product_custom_text_fontcolor'] ) : '';
		update_post_meta( $post_id, '_sop_product_custom_text_fontcolor', $sop_product_custom_text_fontcolor );
		$sop_product_custom_text_backcolor = isset( $_POST['sop_product_custom_text_backcolor'] ) ? sanitize_hex_color( $_POST['sop_product_custom_text_backcolor'] ) : '';
		update_post_meta( $post_id, '_sop_product_custom_text_backcolor', $sop_product_custom_text_backcolor );

		$sop_sticker_custom_id = isset( $_POST['sop_sticker_custom_id'] ) ? absint( $_POST['sop_sticker_custom_id'] ) : '';
		update_post_meta( $post_id, '_sop_sticker_custom_id', $sop_sticker_custom_id );

		//Save custom product sticker options
		$enable_cust_sticker = isset( $_POST['enable_cust_sticker'] ) ? sanitize_text_field( $_POST['enable_cust_sticker'] ) : '';
		update_post_meta( $post_id, '_enable_cust_sticker', $enable_cust_sticker );
		$cust_sticker_pos = isset( $_POST['cust_sticker_pos'] ) ? sanitize_text_field( $_POST['cust_sticker_pos'] ) : '';
		update_post_meta( $post_id, '_cust_sticker_pos', $cust_sticker_pos );
		$cust_product_option = isset( $_POST['cust_product_option'] ) ? sanitize_key( $_POST['cust_product_option'] ) : '';
		update_post_meta( $post_id, '_cust_product_option', $cust_product_option );

		$cust_product_custom_text = isset( $_POST['cust_product_custom_text'] ) ? sanitize_text_field( $_POST['cust_product_custom_text'] ) : '';
		update_post_meta( $post_id, '_cust_product_custom_text', $cust_product_custom_text );
		$cust_sticker_type = isset( $_POST['cust_sticker_type'] ) ? sanitize_text_field( $_POST['cust_sticker_type'] ) : '';
		update_post_meta( $post_id, '_cust_sticker_type', $cust_sticker_type );
		$cust_product_custom_text_fontcolor = isset( $_POST['cust_product_custom_text_fontcolor'] ) ? sanitize_hex_color( $_POST['cust_product_custom_text_fontcolor'] ) : '';
		update_post_meta( $post_id, '_cust_product_custom_text_fontcolor', $cust_product_custom_text_fontcolor );
		$cust_product_custom_text_backcolor = isset( $_POST['cust_product_custom_text_backcolor'] ) ? sanitize_hex_color( $_POST['cust_product_custom_text_backcolor'] ) : '';
		update_post_meta( $post_id, '_cust_product_custom_text_backcolor', $cust_product_custom_text_backcolor );

		$cust_sticker_custom_id = isset( $_POST['cust_sticker_custom_id'] ) ? absint( $_POST['cust_sticker_custom_id'] ) : '';
		update_post_meta( $post_id, '_cust_sticker_custom_id', $cust_sticker_custom_id );
	}

	/**
	 * Category sticker fields.
	 */
	public function add_category_fields() {
		?>
	<div class="wsbw-sticker-options-wrap">
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active" href="#wsbw_new_products"><?php _e( "New Products", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_products_sale"><?php _e( "Products On Sale", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_soldout_products"><?php _e( "Soldout Products", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_cust_products"><?php _e( "Custom Product Sticker", 'woo-stickers-by-webline' );?></a>
			<a class="nav-tab" href="#wsbw_category_sticker"><?php _e( "Category Sticker", 'woo-stickers-by-webline' );?></a>
		</h2>

		<div id="wsbw_new_products" class="wsbw_tab_content">
			<div class="form-field term-display-type-wrap">
				<label for="enable_np_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label>
				<select id="enable_np_sticker" name="enable_np_sticker" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="yes"><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
					<option value="no"><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="np_no_of_days"><?php _e( 'Number of Days for New Product:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" name="np_no_of_days" value="" class="small-text">
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="np_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
				<select id="np_sticker_pos" name="np_sticker_pos" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="left"><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
					<option value="right"><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>

			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt np_product_option">
					<label for="np_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" checked="checked"/>
					<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text"/>
					<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" class="wli_product_option" id="np_product_option" name="np_product_option" value="image"/>
				</div>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="np_product_custom_text"><?php _e( 'Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="np_product_custom_text" name="np_product_custom_text" value=""/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="np_sticker_type"><?php _e( 'Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
				<select id="np_sticker_type" name="np_sticker_type">
					<option value="ribbon"><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
					<option value="round"><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="np_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="np_product_custom_text_fontcolor" class="wli_color_picker" name="np_product_custom_text_fontcolor" value="#ffffff"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="np_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="np_product_custom_text_backcolor" class="wli_color_picker" name="np_product_custom_text_backcolor" value="#000000"/>
			</div>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" style="display: block;">
				<label><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="np_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="np_sticker_custom_id" class="wsbw_upload_img_id" name="np_sticker_custom_id" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>

		<div id="wsbw_products_sale" class="wsbw_tab_content" style="display: none;">
			<div class="form-field term-display-type-wrap">
				<label for="enable_pos_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label>
				<select id="enable_pos_sticker" name="enable_pos_sticker" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="yes"><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
					<option value="no"><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="pos_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
				<select id="pos_sticker_pos" name="pos_sticker_pos" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="left"><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
					<option value="right"><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			
			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt pos_product_option">
					<label for="pos_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="image1" value="image" checked="checked"/>
					<label for="image1"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="text1" value="text"/>
					<label for="text1"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" class="wli_product_option" id="pos_product_option" name="pos_product_option" value="image"/>
				</div>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="pos_product_custom_text"><?php _e( 'Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="pos_product_custom_text" name="pos_product_custom_text" value=""/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="pos_sticker_type"><?php _e( 'Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
				<select id="pos_sticker_type" name="pos_sticker_type">
					<option value="ribbon"><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
					<option value="round"><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="pos_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="pos_product_custom_text_fontcolor" class="wli_color_picker" name="pos_product_custom_text_fontcolor" value="#ffffff"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="pos_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="pos_product_custom_text_backcolor" class="wli_color_picker" name="pos_product_custom_text_backcolor" value="#000000"/>
			</div>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" style="display: block;">
				<label><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="pos_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="pos_sticker_custom_id" class="wsbw_upload_img_id" name="pos_sticker_custom_id" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>

		<div id="wsbw_soldout_products" class="wsbw_tab_content" style="display: none;">
			<div class="form-field term-display-type-wrap">
				<label for="enable_sop_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label>
				<select id="enable_sop_sticker" name="enable_sop_sticker" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="yes"><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
					<option value="no"><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="sop_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
				<select id="sop_sticker_pos" name="sop_sticker_pos" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="left"><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
					<option value="right"><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
	
			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt sop_product_option">
					<label for="sop_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="image2" value="image" checked="checked"/>
					<label for="image2"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="text2" value="text"/>
					<label for="text2"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" class="wli_product_option" id="sop_product_option" name="sop_product_option" value="image"/>
				</div>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="sop_product_custom_text"><?php _e( 'Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="sop_product_custom_text" name="sop_product_custom_text" value=""/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="sop_sticker_type"><?php _e( 'Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
				<select id="sop_sticker_type" name="sop_sticker_type">
					<option value="ribbon"><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
					<option value="round"><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="sop_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="sop_product_custom_text_fontcolor" class="wli_color_picker" name="sop_product_custom_text_fontcolor" value="#ffffff"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="sop_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="sop_product_custom_text_backcolor" class="wli_color_picker" name="sop_product_custom_text_backcolor" value="#000000"/>
			</div>

			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" style="display: block;">
				<label><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="sop_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="sop_sticker_custom_id" class="wsbw_upload_img_id" name="sop_sticker_custom_id" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>

		<div id="wsbw_cust_products" class="wsbw_tab_content" style="display: none;">
			<div class="form-field term-display-type-wrap">
				<label for="enable_cust_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label>
				<select id="enable_cust_sticker" name="enable_cust_sticker" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="yes"><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
					<option value="no"><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="cust_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
				<select id="cust_sticker_pos" name="cust_sticker_pos" class="postform">
					<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
					<option value="left"><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
					<option value="right"><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt cust_product_option">
					<label for="cust_product_option"><?php _e( 'Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="image3" value="image" checked="checked"/>
					<label for="image3"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="text3" value="text"/>
					<label for="text3"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" class="wli_product_option" id="cust_product_option" name="cust_product_option" value="image"/>
				</div>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="cust_product_custom_text"><?php _e( 'Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="cust_product_custom_text" name="cust_product_custom_text" value=""/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="cust_sticker_type"><?php _e( 'Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
				<select id="cust_sticker_type" name="cust_sticker_type">
					<option value="ribbon"><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
					<option value="round"><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="cust_product_custom_text_fontcolor"><?php _e( 'Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="cust_product_custom_text_fontcolor" class="wli_color_picker" name="cust_product_custom_text_fontcolor" value="#ffffff"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="cust_product_custom_text_backcolor"><?php _e( 'Custom Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="cust_product_custom_text_backcolor" class="wli_color_picker" name="cust_product_custom_text_backcolor" value="#000000"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" style="display: block;">
				<label><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
				<div id="cust_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="cust_sticker_custom_id" class="wsbw_upload_img_id" name="cust_sticker_custom_id" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
			</div>
		</div>
		<div id="wsbw_category_sticker" class="wsbw_tab_content" style="display: none;">
			<div class="form-field term-display-type-wrap">
				<label for="enable_category_sticker"><?php _e( 'Enable Category Sticker:', 'woo-stickers-by-webline' ); ?></label>
				<select id="enable_category_sticker" name="enable_category_sticker" class="postform">
					<option value=""><?php _e( 'Please select', 'woo-stickers-by-webline' ); ?></option>
					<option value="yes"><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
					<option value="no"><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
				</select>
				<p class="description"><?php _e( 'Enable sticker on this category', 'woo-stickers-by-webline' ); ?></p>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<label for="category_sticker_pos"><?php _e( 'Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
				<select id="category_sticker_pos" name="category_sticker_pos" class="postform">
					<option value="left"><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
					<option value="right"><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap">
				<div class="woo_opt category_sticker_option">
					<label for="category_sticker_option"><?php _e( 'Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
					<input type="radio" name="stickeroption4" class="wli-woosticker-radio" id="image4" value="image" checked="checked"/>
					<label for="image4"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
					<input type="radio" name="stickeroption4" class="wli-woosticker-radio" id="text4" value="text"/>
					<label for="text4"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
					<input type="hidden" class="wli_product_option" id="category_sticker_option" name="category_sticker_option" value="image"/>
				</div>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="category_sticker_text"><?php _e( 'Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="category_sticker_text" name="category_sticker_text" value=""/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="category_sticker_type"><?php _e( 'Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
				<select id="category_sticker_type" name="category_sticker_type">
					<option value="ribbon"><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
					<option value="round"><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
				</select>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="category_sticker_text_fontcolor"><?php _e( 'Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="category_sticker_text_fontcolor" class="wli_color_picker" name="category_sticker_text_fontcolor" value="#ffffff"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_opttext">
				<label for="category_sticker_text_backcolor"><?php _e( 'Sticker Text Background Color:', 'woo-stickers-by-webline' ); ?></label>
				<input type="text" id="category_sticker_text_backcolor" class="wli_color_picker" name="category_sticker_text_backcolor" value="#000000"/>
			</div>
			<div class="form-field term-thumbnail-wrap custom_option custom_optimage" style="display: block;">
				<label><?php _e( 'Add your sticker image:', 'woo-stickers-by-webline' ); ?></label>
				<div id="category_sticker_image" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 70px;">
					<input type="hidden" id="category_sticker_image_id" class="wsbw_upload_img_id" name="category_sticker_image_id" />
					<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
					<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
				</div>
				<p class="description"><?php _e( 'Upload your sticker image which you want to display on this category.', 'woo-stickers-by-webline' ); ?></p>
			</div>
		</div>
	</div>
		<?php
	}

	/**
	 * Category sticker fields.
	 */
	public function edit_category_fields( $term ) {

		//Get WC placeholder image
		$placeholder_img = wc_placeholder_img_src();

		//Get new product sticker options
		$enable_np_sticker = get_term_meta( $term->term_id, 'enable_np_sticker', true );
		$np_no_of_days = get_term_meta( $term->term_id, 'np_no_of_days', true );
		$np_sticker_pos = get_term_meta( $term->term_id, 'np_sticker_pos', true );
		$np_product_option = get_term_meta( $term->term_id, 'np_product_option', true );
		$np_product_custom_text = get_term_meta( $term->term_id, 'np_product_custom_text', true );
		$np_sticker_type = get_term_meta( $term->term_id, 'np_sticker_type', true );
		$np_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'np_product_custom_text_fontcolor', true );
		$np_product_custom_text_backcolor = get_term_meta( $term->term_id, 'np_product_custom_text_backcolor', true );
		$np_sticker_custom_id = get_term_meta( $term->term_id, 'np_sticker_custom_id', true );
		if ( !empty( $np_sticker_custom_id ) ) {
			$np_image = wp_get_attachment_thumb_url( $np_sticker_custom_id );
		} else {
			$np_image = $placeholder_img;
		}
		$show_text_np_product  = ($np_product_option == "text") ? 'style="display: table-row;"' : '';
		$show_image_np_product = ( empty( $np_product_option ) || $np_product_option == "image" ) ? 'style="display: table-row;"' : '';

		//Get product sale sticker options
		$enable_pos_sticker = get_term_meta( $term->term_id, 'enable_pos_sticker', true );
		$pos_sticker_pos = get_term_meta( $term->term_id, 'pos_sticker_pos', true );
		$pos_product_option = get_term_meta( $term->term_id, 'pos_product_option', true );
		$pos_product_custom_text = get_term_meta( $term->term_id, 'pos_product_custom_text', true );
		$pos_sticker_type = get_term_meta( $term->term_id, 'pos_sticker_type', true );
		$pos_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'pos_product_custom_text_fontcolor', true );
		$pos_product_custom_text_backcolor = get_term_meta( $term->term_id, 'pos_product_custom_text_backcolor', true );
		$pos_sticker_custom_id = get_term_meta( $term->term_id, 'pos_sticker_custom_id', true );
		if ( !empty( $pos_sticker_custom_id ) ) {
			$pos_image = wp_get_attachment_thumb_url( $pos_sticker_custom_id );
		} else {
			$pos_image = $placeholder_img;
		}
		$show_text_pos_sticker  = ($pos_product_option == "text") ? 'style="display: table-row;"' : '';
		$show_image_pos_sticker = ( empty( $pos_product_option) || $pos_product_option == "image" ) ? 'style="display: table-row;"' : '';

		//Get soldout product sticker options
		$enable_sop_sticker = get_term_meta( $term->term_id, 'enable_sop_sticker', true );
		$sop_sticker_pos = get_term_meta( $term->term_id, 'sop_sticker_pos', true );
		$sop_product_option = get_term_meta( $term->term_id, 'sop_product_option', true );
		$sop_product_custom_text = get_term_meta( $term->term_id, 'sop_product_custom_text', true );
		$sop_sticker_type = get_term_meta( $term->term_id, 'sop_sticker_type', true );
		$sop_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'sop_product_custom_text_fontcolor', true );
		$sop_product_custom_text_backcolor = get_term_meta( $term->term_id, 'sop_product_custom_text_backcolor', true );
		$sop_sticker_custom_id = get_term_meta( $term->term_id, 'sop_sticker_custom_id', true );
		if ( !empty( $sop_sticker_custom_id ) ) {
			$sop_image = wp_get_attachment_thumb_url( $sop_sticker_custom_id );
		} else {
			$sop_image = $placeholder_img;
		}
		$show_text_sop_sticker  = ($sop_product_option == "text") ? 'style="display: table-row;"' : '';
		$show_image_sop_sticker = ( empty( $sop_product_option ) || $sop_product_option == "image" ) ? 'style="display: table-row;"' : '';

		//Get custom product sticker options
		$enable_cust_sticker = get_term_meta( $term->term_id, 'enable_cust_sticker', true );
		$cust_sticker_pos = get_term_meta( $term->term_id, 'cust_sticker_pos', true );
		$cust_product_option = get_term_meta( $term->term_id, 'cust_product_option', true );
		$cust_product_custom_text = get_term_meta( $term->term_id, 'cust_product_custom_text', true );
		$cust_sticker_type = get_term_meta( $term->term_id, 'cust_sticker_type', true );
		$cust_product_custom_text_fontcolor = get_term_meta( $term->term_id, 'cust_product_custom_text_fontcolor', true );
		$cust_product_custom_text_backcolor = get_term_meta( $term->term_id, 'cust_product_custom_text_backcolor', true );
		$cust_sticker_custom_id = get_term_meta( $term->term_id, 'cust_sticker_custom_id', true );
		if ( !empty( $cust_sticker_custom_id ) ) {
			$cust_image = wp_get_attachment_thumb_url( $cust_sticker_custom_id );
		} else {
			$cust_image = $placeholder_img;
		}
		$show_text_cust_product  = ($cust_product_option == "text") ? 'style="display: table-row;"' : '';
		$show_image_cust_product = ( empty( $cust_product_option ) || $cust_product_option == "image" ) ? 'style="display: table-row;"' : '';

		//Get category sticker options
		$enable_category_sticker = get_term_meta( $term->term_id, 'enable_category_sticker', true );
		$category_sticker_pos 	 = get_term_meta( $term->term_id, 'category_sticker_pos', true );
		$category_sticker_option = get_term_meta( $term->term_id, 'category_sticker_option', true );
		$category_sticker_text 	 = get_term_meta( $term->term_id, 'category_sticker_text', true );
		$category_sticker_type 	 = get_term_meta( $term->term_id, 'category_sticker_type', true );
		$category_sticker_text_fontcolor = get_term_meta( $term->term_id, 'category_sticker_text_fontcolor', true );
		$category_sticker_text_backcolor = get_term_meta( $term->term_id, 'category_sticker_text_backcolor', true );
		$category_sticker_image_id = get_term_meta( $term->term_id, 'category_sticker_image_id', true );
		if ( !empty( $category_sticker_image_id ) ) {
			$category_image = wp_get_attachment_thumb_url( $category_sticker_image_id );
		} else {
			$category_image = $placeholder_img;
		}
		$show_text_sticker 	= ($category_sticker_option == "text") ? 'style="display: table-row;"' : '';
		$show_image_sticker = ( empty( $category_sticker_option ) || $category_sticker_option == "image" ) ? 'style="display: table-row;"' : '';
		?>
		<tr class="form-field wsbw-sticker-options-wrap">
			<th scope="row" valign="top"><label><?php _e( 'Sticker Options', 'woocommerce' ); ?></label></th>
			<td>
				<h2 class="nav-tab-wrapper">
					<a class="nav-tab nav-tab-active" href="#wsbw_new_products"><?php _e( "New Products", 'woo-stickers-by-webline' );?></a>
					<a class="nav-tab" href="#wsbw_products_sale"><?php _e( "Products On Sale", 'woo-stickers-by-webline' );?></a>
					<a class="nav-tab" href="#wsbw_soldout_products"><?php _e( "Soldout Products", 'woo-stickers-by-webline' );?></a>
					<a class="nav-tab" href="#wsbw_cust_products"><?php _e( "Custom Product Sticker", 'woo-stickers-by-webline' );?></a>
					<a class="nav-tab" href="#wsbw_category_sticker"><?php _e( "Category Sticker", 'woo-stickers-by-webline' );?></a>
				</h2>
				<table id="wsbw_new_products" class="wsbw_tab_content">
					<tr>
						<th scope="row" valign="top"><label for="enable_np_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label></th>
						<td>
							<select id="enable_np_sticker" name="enable_np_sticker" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="yes" <?php selected( $enable_np_sticker, 'yes');?>><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
								<option value="no" <?php selected( $enable_np_sticker, 'no');?>><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top"><label for="np_no_of_days"><?php _e( 'Number of Days for New Product:', 'woo-stickers-by-webline' ); ?></label></th>
						<td>
							<input type="text" name="np_no_of_days" value="<?php echo absint( $np_no_of_days ); ?>" class="small-text">
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="np_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="np_sticker_pos" name="np_sticker_pos" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="left" <?php selected( $np_sticker_pos, 'left');?>><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
								<option value="right" <?php selected( $np_sticker_pos, 'right');?>><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
		
					<tr>
						<th scope="row" valign="top">
							<label for="np_product_option"><?php _e( 'Product Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div class="woo_opt np_product_option">
								<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($np_product_option == 'image' || $np_product_option == '') { echo 'checked'; } ?>/>
								<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
								<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($np_product_option == 'text') { echo 'checked'; } ?>/>
								<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
								<input type="hidden" class="wli_product_option" id="np_product_option" name="np_product_option" value="<?php if($np_product_option == '') { echo "image"; } else { echo esc_attr( $np_product_option ); } ?>"/>
							</div>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_np_product;?>>
						<th scope="row" valign="top">
							<label for="np_product_custom_text"><?php _e( 'Product Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="np_product_custom_text" name="np_product_custom_text" value="<?php echo esc_attr( $np_product_custom_text ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_np_product;?>>
						<th scope="row" valign="top">
							<label for="np_sticker_type"><?php _e( 'Product Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id='np_sticker_type'
								name="np_sticker_type">
								<option value='ribbon'
									<?php selected( $np_sticker_type, 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
								<option value='round'
									<?php selected( $np_sticker_type, 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
							</select>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_np_product;?>>
						<th scope="row" valign="top">
							<label for="np_product_custom_text_fontcolor"><?php _e( 'Product Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="np_product_custom_text_fontcolor" class="wli_color_picker" name="np_product_custom_text_fontcolor" value="<?php echo ($np_product_custom_text_fontcolor) ? esc_attr( $np_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_np_product;?>>
						<th scope="row" valign="top">
							<label for="np_product_custom_text_backcolor"><?php _e( 'Product Custom Sticker Text Back Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="np_product_custom_text_backcolor" class="wli_color_picker" name="np_product_custom_text_backcolor" value="<?php echo esc_attr( $np_product_custom_text_backcolor ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_optimage" <?php echo $show_image_np_product;?>>
						<th scope="row" valign="top">
							<label for="np_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div id="np_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url($np_image); ?>" width="60px" height="60px" /></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="np_sticker_custom_id" class="wsbw_upload_img_id" name="np_sticker_custom_id" value="<?php echo absint( $np_sticker_custom_id ); ?>" />
								<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
								<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
				<table id="wsbw_products_sale" class="wsbw_tab_content" style="display: none;">
					<tr>
						<th scope="row" valign="top"><label for="enable_pos_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label></th>
						<td>
							<select id="enable_pos_sticker" name="enable_pos_sticker" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="yes" <?php selected( $enable_pos_sticker, 'yes');?>><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
								<option value="no" <?php selected( $enable_pos_sticker, 'no');?>><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="pos_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="pos_sticker_pos" name="pos_sticker_pos" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="left" <?php selected( $pos_sticker_pos, 'left');?>><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
								<option value="right" <?php selected( $pos_sticker_pos, 'right');?>><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
					
					<tr>
						<th scope="row" valign="top">
							<label for="pos_product_option"><?php _e( 'Product Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div class="woo_opt pos_product_option">
								<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="image1" value="image" <?php if($pos_product_option == 'image' || $pos_product_option == '') { echo 'checked'; } ?>/>
								<label for="image1"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
								<input type="radio" name="stickeroption1" class="wli-woosticker-radio" id="text1" value="text" <?php if($pos_product_option == 'text') { echo 'checked'; } ?>/>
								<label for="text1"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
								<input type="hidden" class="wli_product_option" id="pos_product_option" name="pos_product_option" value="<?php if($pos_product_option == '') { echo "image"; } else { echo esc_attr( $pos_product_option ); } ?>"/>
							</div>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_pos_sticker;?>>
						<th scope="row" valign="top">
							<label for="pos_product_custom_text"><?php _e( 'Product Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="pos_product_custom_text" name="pos_product_custom_text" value="<?php echo esc_attr( $pos_product_custom_text ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_pos_sticker;?>>
						<th scope="row" valign="top">
							<label for="pos_sticker_type"><?php _e( 'Product Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id='pos_sticker_type'
								name="pos_sticker_type">
								<option value='ribbon'
									<?php selected( $pos_sticker_type, 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
								<option value='round'
									<?php selected( $pos_sticker_type, 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
							</select>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_pos_sticker;?>>
						<th scope="row" valign="top">
							<label for="pos_product_custom_text_fontcolor"><?php _e( 'Product Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="pos_product_custom_text_fontcolor" class="wli_color_picker" name="pos_product_custom_text_fontcolor" value="<?php echo ($pos_product_custom_text_fontcolor) ? esc_attr( $pos_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_pos_sticker;?>>
						<th scope="row" valign="top">
							<label for="pos_product_custom_text_backcolor"><?php _e( 'Product Custom Sticker Text Back Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="pos_product_custom_text_backcolor" class="wli_color_picker" name="pos_product_custom_text_backcolor" value="<?php echo esc_attr( $pos_product_custom_text_backcolor ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_optimage" <?php echo $show_image_pos_sticker;?>>
						<th scope="row" valign="top">
							<label for="pos_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div id="pos_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $pos_image ); ?>" width="60px" height="60px" /></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="pos_sticker_custom_id" class="wsbw_upload_img_id" name="pos_sticker_custom_id" value="<?php echo absint( $pos_sticker_custom_id ); ?>" />
								<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
								<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
				<table id="wsbw_soldout_products" class="wsbw_tab_content" style="display: none;">
					<tr>
						<th scope="row" valign="top"><label for="enable_sop_sticker"><?php _e( 'Enable Product Sticker:', 'woo-stickers-by-webline' ); ?></label></th>
						<td>
							<select id="enable_sop_sticker" name="enable_sop_sticker" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="yes" <?php selected( $enable_sop_sticker, 'yes');?>><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
								<option value="no" <?php selected( $enable_sop_sticker, 'no');?>><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="sop_sticker_pos"><?php _e( 'Product Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="sop_sticker_pos" name="sop_sticker_pos" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="left" <?php selected( $sop_sticker_pos, 'left');?>><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
								<option value="right" <?php selected( $sop_sticker_pos, 'right');?>><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
				
					<tr>
						<th scope="row" valign="top">
							<label for="sop_product_option"><?php _e( 'Product Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div class="woo_opt sop_product_option">
								<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="image2" value="image" <?php if($sop_product_option == 'image' || $sop_product_option == '') { echo 'checked'; } ?>/>
								<label for="image2"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
								<input type="radio" name="stickeroption2" class="wli-woosticker-radio" id="text2" value="text" <?php if($sop_product_option == 'text') { echo 'checked'; } ?>/>
								<label for="text2"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
								<input type="hidden" class="wli_product_option" id="sop_product_option" name="sop_product_option" value="<?php if($sop_product_option == '') { echo "image"; } else { echo esc_attr( $sop_product_option ); } ?>"/>
							</div>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sop_sticker;?>>
						<th scope="row" valign="top">
							<label for="sop_product_custom_text"><?php _e( 'Product Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="sop_product_custom_text" name="sop_product_custom_text" value="<?php echo esc_attr( $sop_product_custom_text ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sop_sticker;?>>
						<th scope="row" valign="top">
							<label for="sop_sticker_type"><?php _e( 'Product Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id='sop_sticker_type'
								name="sop_sticker_type">
								<option value='ribbon'
									<?php selected( $sop_sticker_type, 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
								<option value='round'
									<?php selected( $sop_sticker_type, 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
							</select>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sop_sticker;?>>
						<th scope="row" valign="top">
							<label for="sop_product_custom_text_fontcolor"><?php _e( 'Product Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="sop_product_custom_text_fontcolor" class="wli_color_picker" name="sop_product_custom_text_fontcolor" value="<?php echo ($sop_product_custom_text_fontcolor) ? esc_attr( $sop_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sop_sticker;?>>
						<th scope="row" valign="top">
							<label for="sop_product_custom_text_backcolor"><?php _e( 'Product Custom Sticker Text Back Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="sop_product_custom_text_backcolor" class="wli_color_picker" name="sop_product_custom_text_backcolor" value="<?php echo esc_attr( $sop_product_custom_text_backcolor ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_optimage" <?php echo $show_image_sop_sticker;?>>
						<th scope="row" valign="top">
							<label for="sop_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div id="sop_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $sop_image ); ?>" width="60px" height="60px" /></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="sop_sticker_custom_id" class="wsbw_upload_img_id" name="sop_sticker_custom_id" value="<?php echo absint( $sop_sticker_custom_id ); ?>" />
								<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
								<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
				<table id="wsbw_cust_products" class="wsbw_tab_content" style="display: none;">
					<tr>
						<th scope="row" valign="top"><label for="enable_cust_sticker"><?php _e( 'Enable Product Custom Sticker:', 'woo-stickers-by-webline' ); ?></label></th>
						<td>
							<select id="enable_cust_sticker" name="enable_cust_sticker" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="yes" <?php selected( $enable_cust_sticker, 'yes');?>><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
								<option value="no" <?php selected( $enable_cust_sticker, 'no');?>><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="cust_sticker_pos"><?php _e( 'Product Custom Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="cust_sticker_pos" name="cust_sticker_pos" class="postform">
								<option value=""><?php _e( 'Default', 'woo-stickers-by-webline' ); ?></option>
								<option value="left" <?php selected( $cust_sticker_pos, 'left');?>><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
								<option value="right" <?php selected( $cust_sticker_pos, 'right');?>><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row" valign="top">
							<label for="cust_product_option"><?php _e( 'Product Custom Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div class="woo_opt cust_product_option">
								<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="image3" value="image" <?php if($cust_product_option == 'image' || $cust_product_option == '') { echo 'checked'; } ?>/>
								<label for="image3"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
								<input type="radio" name="stickeroption3" class="wli-woosticker-radio" id="text3" value="text" <?php if($cust_product_option == 'text') { echo 'checked'; } ?>/>
								<label for="text3"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
								<input type="hidden" class="wli_product_option" id="cust_product_option" name="cust_product_option" value="<?php if($cust_product_option == '') { echo "image"; } else { echo esc_attr( $cust_product_option ); } ?>"/>
							</div>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_cust_product;?>>
						<th scope="row" valign="top">
							<label for="cust_product_custom_text"><?php _e( 'Product Custom Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="cust_product_custom_text" name="cust_product_custom_text" value="<?php echo esc_attr( $cust_product_custom_text ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_cust_product;?>>
						<th scope="row" valign="top">
							<label for="cust_sticker_type"><?php _e( 'Product Custom Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id='cust_sticker_type'
								name="cust_sticker_type">
								<option value='ribbon'
									<?php selected( $cust_sticker_type, 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
								<option value='round'
									<?php selected( $cust_sticker_type, 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
							</select>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_cust_product;?>>
						<th scope="row" valign="top">
							<label for="cust_product_custom_text_fontcolor"><?php _e( 'Product Custom Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="cust_product_custom_text_fontcolor" class="wli_color_picker" name="cust_product_custom_text_fontcolor" value="<?php echo ($cust_product_custom_text_fontcolor) ? esc_attr( $cust_product_custom_text_fontcolor ) : '#ffffff'; ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_cust_product;?>>
						<th scope="row" valign="top">
							<label for="cust_product_custom_text_backcolor"><?php _e( 'Product Custom Sticker Text Back Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="cust_product_custom_text_backcolor" class="wli_color_picker" name="cust_product_custom_text_backcolor" value="<?php echo esc_attr( $cust_product_custom_text_backcolor ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_optimage" <?php echo $show_image_cust_product;?>>
						<th scope="row" valign="top">
							<label for="cust_sticker_custom"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div id="cust_sticker_custom" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $cust_image ); ?>" width="60px" height="60px" /></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="cust_sticker_custom_id" class="wsbw_upload_img_id" name="cust_sticker_custom_id" value="<?php echo absint( $cust_sticker_custom_id ); ?>" />
								<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
								<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
							</div>
						</td>
					</tr>
				</table>
				<table id="wsbw_category_sticker" class="wsbw_tab_content" style="display: none;">
					<tr>
						<th scope="row" valign="top">
							<label for="enable_category_sticker"><?php _e( 'Enable Category Sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="enable_category_sticker" name="enable_category_sticker" class="postform">
								<option value=""><?php _e( 'Please select', 'woo-stickers-by-webline' ); ?></option>
								<option value="yes" <?php selected( $enable_category_sticker, 'yes');?>><?php _e( 'Yes', 'woo-stickers-by-webline' ); ?></option>
								<option value="no" <?php selected( $enable_category_sticker, 'no');?>><?php _e( 'No', 'woo-stickers-by-webline' ); ?></option>
							</select>
							<p class="description"><?php _e( 'Enable sticker on this category', 'woo-stickers-by-webline' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="category_sticker_pos"><?php _e( 'Sticker Position:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id="category_sticker_pos" name="category_sticker_pos" class="postform">
								<option value="left" <?php selected( $category_sticker_pos, 'left');?>><?php _e( 'Left', 'woo-stickers-by-webline' ); ?></option>
								<option value="right" <?php selected( $category_sticker_pos, 'right');?>><?php _e( 'Right', 'woo-stickers-by-webline' ); ?></option>
							</select>
						</td>
					</tr>

					<tr>
						<th scope="row" valign="top">
							<label for="category_sticker_option"><?php _e( 'Sticker Options:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div class="woo_opt category_sticker_option">
								<input type="radio" name="stickeroption4" class="wli-woosticker-radio" id="image4" value="image" <?php if($category_sticker_option == 'image' || $category_sticker_option == '') { echo 'checked'; } ?>/>
								<label for="image4"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
								<input type="radio" name="stickeroption4" class="wli-woosticker-radio" id="text4" value="text" <?php if($category_sticker_option == 'text') { echo 'checked'; } ?>/>
								<label for="text4"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
								<input type="hidden" class="wli_product_option" id="category_sticker_option" name="category_sticker_option" value="<?php echo $category_sticker_option == '' ? "image" : esc_attr( $category_sticker_option );?>"/>
							</div>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sticker;?>>
						<th scope="row" valign="top">
							<label for="category_sticker_text"><?php _e( 'Sticker Text:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="category_sticker_text" name="category_sticker_text" value="<?php echo esc_attr( $category_sticker_text ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sticker;?>>
						<th scope="row" valign="top">
							<label for="category_sticker_type"><?php _e( 'Sticker Type:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<select id='category_sticker_type'
								name="category_sticker_type">
								<option value='ribbon'
									<?php selected( $category_sticker_type, 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
								<option value='round'
									<?php selected( $category_sticker_type, 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
							</select>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sticker;?>>
						<th scope="row" valign="top">
							<label for="category_sticker_text_fontcolor"><?php _e( 'Sticker Text Font Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="category_sticker_text_fontcolor" class="wli_color_picker" name="category_sticker_text_fontcolor" value="<?php echo ($category_sticker_text_fontcolor) ? esc_attr( $category_sticker_text_fontcolor ) : '#ffffff'; ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_opttext" <?php echo $show_text_sticker;?>>
						<th scope="row" valign="top">
							<label for="category_sticker_text_backcolor"><?php _e( 'Sticker Text Back Color:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<input type="text" id="category_sticker_text_backcolor" class="wli_color_picker" name="category_sticker_text_backcolor" value="<?php echo esc_attr( $category_sticker_text_backcolor ); ?>"/>
						</td>
					</tr>
					<tr class="custom_option custom_optimage" <?php echo $show_image_sticker;?>>
						<th scope="row" valign="top">
							<label for="category_sticker_image"><?php _e( 'Add your custom sticker:', 'woo-stickers-by-webline' ); ?></label>
						</th>
						<td>
							<div id="category_sticker_image" class="wsbw_upload_img_preview" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $category_image ); ?>" width="60px" height="60px" /></div>
							<div style="line-height: 60px;">
								<input type="hidden" id="category_sticker_image_id" class="wsbw_upload_img_id" name="category_sticker_image_id" value="<?php echo absint( $category_sticker_image_id ); ?>" />
								<button type="button" class="wsbw_upload_image_button button"><?php _e( 'Upload/Add image', 'woo-stickers-by-webline' ); ?></button>
								<button type="button" class="wsbw_remove_image_button button"><?php _e( 'Remove image', 'woo-stickers-by-webline' ); ?></button>
							</div>
							<p class="description"><?php _e( 'Upload your sticker image which you want to display on this category.', 'woo-stickers-by-webline' ); ?></p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @param mixed  $term_id Term ID being saved
	 * @param mixed  $tt_id
	 * @param string $taxonomy
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {

		//Save all new product sticker fields
		if ( isset( $_POST['enable_np_sticker'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'enable_np_sticker', sanitize_text_field( $_POST['enable_np_sticker'] ) );
		}
		if ( isset( $_POST['np_no_of_days'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_no_of_days', absint( $_POST['np_no_of_days'] ) );
		}
		if ( isset( $_POST['np_sticker_pos'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_sticker_pos', sanitize_text_field( $_POST['np_sticker_pos'] ) );
		}
		if ( isset( $_POST['np_product_option'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_product_option', sanitize_key( $_POST['np_product_option'] ) );
		}
		if ( isset( $_POST['np_product_custom_text'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_product_custom_text',sanitize_text_field( $_POST['np_product_custom_text'] ) );
		}
		if ( isset( $_POST['np_sticker_type'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_sticker_type', sanitize_text_field( $_POST['np_sticker_type'] ) );
		}
		if ( isset( $_POST['np_product_custom_text_fontcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_product_custom_text_fontcolor', sanitize_hex_color( $_POST['np_product_custom_text_fontcolor'] ) );
		}
		if ( isset( $_POST['np_product_custom_text_backcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_product_custom_text_backcolor', sanitize_hex_color( $_POST['np_product_custom_text_backcolor'] ) );
		}
		if ( isset( $_POST['np_sticker_custom_id'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'np_sticker_custom_id', absint( $_POST['np_sticker_custom_id'] ) );
		}

		//Save all product on sale sticker fields
		if ( isset( $_POST['enable_pos_sticker'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'enable_pos_sticker', sanitize_text_field( $_POST['enable_pos_sticker'] ) );
		}
		if ( isset( $_POST['pos_sticker_pos'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_sticker_pos', sanitize_text_field( $_POST['pos_sticker_pos'] ) );
		}
		if ( isset( $_POST['pos_product_option'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_product_option', sanitize_key( $_POST['pos_product_option'] ) );
		}
		if ( isset( $_POST['pos_product_custom_text'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_product_custom_text',sanitize_text_field( $_POST['pos_product_custom_text'] ) );
		}
		if ( isset( $_POST['pos_sticker_type'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_sticker_type', sanitize_text_field( $_POST['pos_sticker_type'] ) );
		}
		if ( isset( $_POST['pos_product_custom_text_fontcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_product_custom_text_fontcolor', sanitize_hex_color( $_POST['pos_product_custom_text_fontcolor'] ) );
		}
		if ( isset( $_POST['pos_product_custom_text_backcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_product_custom_text_backcolor', sanitize_hex_color( $_POST['pos_product_custom_text_backcolor'] ) );
		}
		if ( isset( $_POST['pos_sticker_custom_id'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'pos_sticker_custom_id', absint( $_POST['pos_sticker_custom_id'] ) );
		}

		//Save all soldout product sticker fields
		if ( isset( $_POST['enable_sop_sticker'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'enable_sop_sticker', sanitize_text_field( $_POST['enable_sop_sticker'] ) );
		}
		if ( isset( $_POST['sop_sticker_pos'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_sticker_pos', sanitize_text_field( $_POST['sop_sticker_pos'] ) );
		}
		if ( isset( $_POST['sop_product_option'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_product_option', sanitize_key( $_POST['sop_product_option'] ) );
		}
		if ( isset( $_POST['sop_product_custom_text'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_product_custom_text',sanitize_text_field( $_POST['sop_product_custom_text'] ) );
		}
		if ( isset( $_POST['sop_sticker_type'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_sticker_type', sanitize_text_field( $_POST['sop_sticker_type'] ) );
		}
		if ( isset( $_POST['sop_product_custom_text_fontcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_product_custom_text_fontcolor', sanitize_hex_color( $_POST['sop_product_custom_text_fontcolor'] ) );
		}
		if ( isset( $_POST['sop_product_custom_text_backcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_product_custom_text_backcolor', sanitize_hex_color( $_POST['sop_product_custom_text_backcolor'] ) );
		}
		if ( isset( $_POST['sop_sticker_custom_id'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'sop_sticker_custom_id', absint( $_POST['sop_sticker_custom_id'] ) );
		}

		//Save Custom Product Sticker fields
		if ( isset( $_POST['enable_cust_sticker'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'enable_cust_sticker', sanitize_text_field( $_POST['enable_cust_sticker'] ) );
		}
		if ( isset( $_POST['cust_sticker_pos'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_sticker_pos', sanitize_text_field( $_POST['cust_sticker_pos'] ) );
		}
		if ( isset( $_POST['cust_product_option'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_product_option', sanitize_key( $_POST['cust_product_option'] ) );
		}
		if ( isset( $_POST['cust_product_custom_text'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_product_custom_text',sanitize_text_field( $_POST['cust_product_custom_text'] ) );
		}
		if ( isset( $_POST['cust_sticker_type'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_sticker_type', sanitize_text_field( $_POST['cust_sticker_type'] ) );
		}
		if ( isset( $_POST['cust_product_custom_text_fontcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_product_custom_text_fontcolor', sanitize_hex_color( $_POST['cust_product_custom_text_fontcolor'] ) );
		}
		if ( isset( $_POST['cust_product_custom_text_backcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_product_custom_text_backcolor', sanitize_hex_color( $_POST['cust_product_custom_text_backcolor'] ) );
		}
		if ( isset( $_POST['cust_sticker_custom_id'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'cust_sticker_custom_id', absint( $_POST['cust_sticker_custom_id'] ) );
		}

		//Save Category Sticker fields
		if ( isset( $_POST['enable_category_sticker'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'enable_category_sticker', sanitize_text_field( $_POST['enable_category_sticker'] ) );
		}
		if ( isset( $_POST['category_sticker_pos'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_pos', sanitize_text_field( $_POST['category_sticker_pos'] ) );
		}
		if ( isset( $_POST['category_sticker_option'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_option', sanitize_key( $_POST['category_sticker_option'] ) );
		}
		if ( isset( $_POST['category_sticker_text'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_text',sanitize_text_field( $_POST['category_sticker_text'] ) );
		}
		if ( isset( $_POST['category_sticker_type'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_type', sanitize_text_field( $_POST['category_sticker_type'] ) );
		}
		if ( isset( $_POST['category_sticker_text_fontcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_text_fontcolor', sanitize_hex_color( $_POST['category_sticker_text_fontcolor'] ) );
		}
		if ( isset( $_POST['category_sticker_text_backcolor'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_text_backcolor', sanitize_hex_color( $_POST['category_sticker_text_backcolor'] ) );
		}
		if ( isset( $_POST['category_sticker_image_id'] ) && 'product_cat' === $taxonomy ) {
			update_term_meta( $term_id, 'category_sticker_image_id', absint( $_POST['category_sticker_image_id'] ) );
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		global $typenow;

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

		//Check if woosticker pages
		if( $hook == 'settings_page_wli-stickers' ||
			( $typenow == 'product' && ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit-tags.php' || $hook == 'term.php' ) ) ) {


			// Add the color picker CSS file       
	        wp_enqueue_style( 'wp-color-picker' );

	        // Add the color picker JS file       
	        wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-stickers-by-webline-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		global $typenow;

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

		//Check if woosticker pages
		if( $hook == 'settings_page_wli-stickers' ||
			( $typenow == 'product' && ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'edit-tags.php' || $hook == 'term.php' ) ) ) {


			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-stickers-by-webline-admin.js', array( 'jquery' ), $this->version, false );
	        wp_localize_script( $this->plugin_name, 'scriptsData', array(
	        	'ajaxurl'=>admin_url( 'admin-ajax.php' ),
	        	'choose_image_title' => __( 'Choose an image', 'woo-stickers-by-webline' ),
	        	'use_image_btn_text' => __( 'Use image', 'woo-stickers-by-webline' ),
	        	'placeholder_img_src' => esc_js( wc_placeholder_img_src() ),
	        ));
		}
	}

	/**
	 * Register settings link on plugin page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_link($links, $file)
    {   
    	$wooStickerFile = WS_PLUGIN_FILE;    	 
        if (basename($file) == $wooStickerFile) {
        	
            $linkSettings = '<a href="' . admin_url("options-general.php?page=wli-stickers") . '">'. __('Settings', 'woo-stickers-by-webline' ) .'</a>';
            array_unshift($links, $linkSettings);
        }
        return $links;
    }

	/**
	 * Loads settings from
	 * the database into their respective arrays.
	 * Uses
	 * array_merge to merge with default values if they're
	 * missing.
	 *
	 * @since 1.0.0
	 * @var No arguments passed
	 * @return void
	 * @author Weblineindia
	 */
	public function load_settings() {
		$this->general_settings = ( array ) get_option ( $this->general_settings_key );
		$this->new_product_settings = ( array ) get_option ( $this->new_product_settings_key );
		$this->sale_product_settings = ( array ) get_option ( $this->sale_product_settings_key );
		$this->sold_product_settings = ( array ) get_option ( $this->sold_product_settings_key );
		$this->cust_product_settings = ( array ) get_option ( $this->cust_product_settings_key );
		// Merge with defaults
		$this->general_settings = array_merge ( array (
				'enable_sticker' => 'no',
				'enable_sticker_list' => 'no',
				'enable_sticker_detail' => 'no' 
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
	}
	/**
	 * Registers the general settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 * Tab Name will defined here.
	 *
	 * @since 1.0.0
	 * @var No arguments passed
	 * @return void
	 * @author Weblineindia
	 */
	public function register_general_settings() {
		$this->plugin_settings_tabs [$this->general_settings_key] = __( 'General', 'woo-stickers-by-webline' );
		
		register_setting ( $this->general_settings_key, $this->general_settings_key );
		add_settings_section ( 'section_general', __( 'General Plugin Settings', 'woo-stickers-by-webline' ), array (
				&$this,
				'section_general_desc' 
		), $this->general_settings_key );
		
		add_settings_field ( 'enable_sticker', __( 'Enable Product Sticker:', 'woo-stickers-by-webline' ), array (
				&$this,
				'enable_sticker' 
		), $this->general_settings_key, 'section_general' );
		
		add_settings_field ( 'enable_sticker_list', __( 'Enable Sticker On Product Listing Page:', 'woo-stickers-by-webline' ), array (
				&$this,
				'enable_sticker_list' 
		), $this->general_settings_key, 'section_general' );
		
		add_settings_field ( 'enable_sticker_detail', __( 'Enable Sticker On Product Details Page:', 'woo-stickers-by-webline' ), array (
				&$this,
				'enable_sticker_detail' 
		), $this->general_settings_key, 'section_general' );
		
		add_settings_field ( 'sticker_custom_css', __( 'Custom CSS:', 'woo-stickers-by-webline' ), array (
				&$this,
				'sticker_custom_css' 
		), $this->general_settings_key, 'section_general' );
	}
	
	/**
	 * Registers the New Product settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 * Tab Name will defined here.
	 *
	 * @since 1.0.0
	 * @var No arguments passed
	 * @return void
	 * @author Weblineindia
	 */
	public function register_new_product_settings() {
		$this->plugin_settings_tabs [$this->new_product_settings_key] = __( 'New Products', 'woo-stickers-by-webline' );
		
		register_setting ( $this->new_product_settings_key, $this->new_product_settings_key );
		
		add_settings_section ( 'section_new_product', __( 'Sticker Configurations for New Products', 'woo-stickers-by-webline' ), array (
				&$this,
				'section_new_product_desc' 
		), $this->new_product_settings_key );
		
		add_settings_field ( 'enable_new_product_sticker', __( 'Enable Product Sticker:', 'woo-stickers-by-webline' ), array (
				&$this,
				'enable_new_product_sticker' 
		), $this->new_product_settings_key, 'section_new_product' );
		
		add_settings_field ( 'new_product_sticker_days', __( 'Number of Days for New Product:', 'woo-stickers-by-webline' ), array (
		&$this,
		'new_product_sticker_days'
			), $this->new_product_settings_key, 'section_new_product' );
		

		add_settings_field ( 'new_product_position', __( 'Product Sticker Position:', 'woo-stickers-by-webline' ), array (
		&$this,
		'new_product_position'
			), $this->new_product_settings_key, 'section_new_product' );

		add_settings_field ( 'new_product_option', __( 'New Product Sticker Options:', 'woo-stickers-by-webline' ), array (
				&$this,
				'new_product_option' 
		), $this->new_product_settings_key, 'section_new_product' );

		add_settings_field ( 'new_product_custom_text', __( 'Add your custom text:', 'woo-stickers-by-webline' ), array (
		&$this,
		'new_product_custom_text'
			), $this->new_product_settings_key, 'section_new_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'enable_new_product_style', __( 'Select layout:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_new_product_style'
			), $this->new_product_settings_key, 'section_new_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'new_product_custom_text_fontcolor', __( 'Choose font color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'new_product_custom_text_fontcolor'
			), $this->new_product_settings_key, 'section_new_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'new_product_custom_text_backcolor', __( 'Choose background color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'new_product_custom_text_backcolor'
			), $this->new_product_settings_key, 'section_new_product', array( 'class' => 'custom_option custom_opttext' ) );
		
		add_settings_field ( 'new_product_custom_sticker', __( 'Add your custom sticker:', 'woo-stickers-by-webline' ), array (
				&$this,
				'new_product_custom_sticker'
		), $this->new_product_settings_key, 'section_new_product', array( 'class' => 'custom_option custom_optimage' ) );
	}
	
	/**
	 * Registers the Sale Product settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 * Tab Name will defined here.
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function register_sale_product_settings() {
		$this->plugin_settings_tabs [$this->sale_product_settings_key] = __( 'Products On Sale', 'woo-stickers-by-webline' );
		
		register_setting ( $this->sale_product_settings_key, $this->sale_product_settings_key );
		add_settings_section ( 'section_sale_product', __( 'Sticker Configurations for Products On Sale', 'woo-stickers-by-webline' ), array (
				&$this,
				'section_sale_product_desc' 
		), $this->sale_product_settings_key );
		
		add_settings_field ( 'enable_sale_product_sticker', __( 'Enable Product Sticker:', 'woo-stickers-by-webline' ), array (
				&$this,
				'enable_sale_product_sticker' 
		), $this->sale_product_settings_key, 'section_sale_product' );
		
		add_settings_field ( 'sale_product_position', __( 'Product Sticker Position:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_position'
			), $this->sale_product_settings_key, 'section_sale_product' );
	
		add_settings_field ( 'sale_product_option', __( 'Sale Product Sticker Options:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_option'
			), $this->sale_product_settings_key, 'section_sale_product' );

		add_settings_field ( 'sale_product_custom_text', __( 'Add your custom text:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_custom_text'
			), $this->sale_product_settings_key, 'section_sale_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'enable_sale_product_style', __( 'Select layout:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_sale_product_style'
			), $this->sale_product_settings_key, 'section_sale_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'sale_product_custom_text_fontcolor', __( 'Choose font color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_custom_text_fontcolor'
			), $this->sale_product_settings_key, 'section_sale_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'sale_product_custom_text_backcolor', __( 'Choose background color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_custom_text_backcolor'
			), $this->sale_product_settings_key, 'section_sale_product', array( 'class' => 'custom_option custom_opttext' ) );
		
		add_settings_field ( 'sale_product_custom_sticker', __( 'Add your custom sticker:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sale_product_custom_sticker'
			), $this->sale_product_settings_key, 'section_sale_product', array( 'class' => 'custom_option custom_optimage' ) );
	}
	
	/**
	 * Registers the Sold Product settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 * Tab Name will defined here.
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function register_sold_product_settings() {
		$this->plugin_settings_tabs [$this->sold_product_settings_key] = __( 'Soldout Products', 'woo-stickers-by-webline' );
	
		register_setting ( $this->sold_product_settings_key, $this->sold_product_settings_key );
		add_settings_section ( 'section_sold_product', __( 'Sticker Configurations for Soldout Products', 'woo-stickers-by-webline' ), array (
		&$this,
		'section_sold_product_desc'
				), $this->sold_product_settings_key );
	
		add_settings_field ( 'enable_sold_product_sticker', __( 'Enable Product Sticker:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_sold_product_sticker'
				), $this->sold_product_settings_key, 'section_sold_product' );
		
		add_settings_field ( 'sold_product_position', __( 'Product Sticker Position:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_position'
			), $this->sold_product_settings_key, 'section_sold_product' );
		
		add_settings_field ( 'sold_product_option', __( 'Sold Product Sticker Options:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_option'
			), $this->sold_product_settings_key, 'section_sold_product' );

		add_settings_field ( 'sold_product_custom_text', __( 'Add your custom text:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_custom_text'
			), $this->sold_product_settings_key, 'section_sold_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'enable_sold_product_style', __( 'Select layout:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_sold_product_style'
			), $this->sold_product_settings_key, 'section_sold_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'sold_product_custom_text_fontcolor', __( 'Choose font color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_custom_text_fontcolor'
			), $this->sold_product_settings_key, 'section_sold_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'sold_product_custom_text_backcolor', __( 'Choose background color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_custom_text_backcolor'
			), $this->sold_product_settings_key, 'section_sold_product', array( 'class' => 'custom_option custom_opttext' ) );
		
		add_settings_field ( 'sold_product_custom_sticker', __( 'Add your custom sticker:', 'woo-stickers-by-webline' ), array (
		&$this,
		'sold_product_custom_sticker'
			), $this->sold_product_settings_key, 'section_sold_product', array( 'class' => 'custom_option custom_optimage' ) );
	}

	/**
	 * Registers Custom Product Sticker settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 * Tab Name will defined here.
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function register_cust_product_settings() {
		$this->plugin_settings_tabs [$this->cust_product_settings_key] = __( 'Custom Product Sticker', 'woo-stickers-by-webline' );
	
		register_setting ( $this->cust_product_settings_key, $this->cust_product_settings_key );
		add_settings_section ( 'section_cust_product', __( 'Custom Sticker Configurations for Products', 'woo-stickers-by-webline' ), array (
		&$this,
		'section_cust_product_desc'
				), $this->cust_product_settings_key );
	
		add_settings_field ( 'enable_cust_product_sticker', __( 'Enable Product Custom Sticker:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_cust_product_sticker'
				), $this->cust_product_settings_key, 'section_cust_product' );
		
		add_settings_field ( 'cust_product_position', __( 'Custom Product Sticker Position:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_position'
			), $this->cust_product_settings_key, 'section_cust_product' );

		add_settings_field ( 'cust_product_option', __( 'Custom Sticker Option:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_option'
			), $this->cust_product_settings_key, 'section_cust_product' );

		add_settings_field ( 'cust_product_custom_text', __( 'Add your custom text:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_custom_text'
			), $this->cust_product_settings_key, 'section_cust_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'enable_cust_product_style', __( 'Select layout:', 'woo-stickers-by-webline' ), array (
		&$this,
		'enable_cust_product_style'
			), $this->cust_product_settings_key, 'section_cust_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'cust_product_custom_text_fontcolor', __( 'Choose font color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_custom_text_fontcolor'
			), $this->cust_product_settings_key, 'section_cust_product', array( 'class' => 'custom_option custom_opttext' ) );

		add_settings_field ( 'cust_product_custom_text_backcolor', __( 'Choose background color:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_custom_text_backcolor'
			), $this->cust_product_settings_key, 'section_cust_product', array( 'class' => 'custom_option custom_opttext' ) );
		
		add_settings_field ( 'cust_product_custom_sticker', __( 'Add your custom sticker:', 'woo-stickers-by-webline' ), array (
		&$this,
		'cust_product_custom_sticker'
			), $this->cust_product_settings_key, 'section_cust_product', array( 'class' => 'custom_option custom_optimage' ) );
	}

	/**
	 * The following methods provide descriptions
	 * for their respective sections, used as callbacks
	 * with add_settings_section
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function section_general_desc() {		
	}
	public function section_new_product_desc() {				
	}
	public function section_sale_product_desc() {		
	}
	public function section_sold_product_desc() {		
	}
	public function section_cust_product_desc() {		
	}

	/**
	 * General Settings :: Enable Stickers
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sticker() {
		?>
		<select id='enable_sticker'
			name="<?php echo $this->general_settings_key; ?>[enable_sticker]">
			<option value='yes'
				<?php selected( $this->general_settings['enable_sticker'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->general_settings['enable_sticker'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select wether you want to enable sticker feature or not.', 'woo-stickers-by-webline' );?></p>
		<?php
	}
	/**
	 * General Settings :: Enable Sticker On Product Listing Page
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sticker_list() {
		?>
		<select id='enable_sticker_list'
			name="<?php echo $this->general_settings_key; ?>[enable_sticker_list]">
			<option value='yes'
				<?php selected( $this->general_settings['enable_sticker_list'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->general_settings['enable_sticker_list'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select wether you want to enable sticker feature on product listing page or not.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * General Settings :: Enable Sticker On Product Listing Page
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sticker_detail() {
		?>
		<select id='enable_sticker_list'
			name="<?php echo $this->general_settings_key; ?>[enable_sticker_detail]">
			<option value='yes'
				<?php selected( $this->general_settings['enable_sticker_detail'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->general_settings['enable_sticker_detail'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select wether you want to enable sticker feature on product detail page or not.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * General Settings :: Custom CSS
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sticker_custom_css() {
		?>
		<textarea id="sticker_custom_css" name="<?php echo $this->general_settings_key; ?>[custom_css]" rows="4" cols="50"><?php echo !empty( $this->general_settings['custom_css'] ) ? $this->general_settings['custom_css'] : '';?></textarea>
		<p class="description"><?php _e( 'Add your custom css here to load on frontend.', 'woo-stickers-by-webline' );?></p>
		<?php
	}
	
	/**
	 * New Product Settings :: Enable Stickers
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_new_product_sticker() {
		?>
		<select id='enable_new_product_sticker'
			name="<?php echo $this->new_product_settings_key; ?>[enable_new_product_sticker]">
			<option value='yes'
				<?php selected( $this->new_product_settings['enable_new_product_sticker'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->new_product_settings['enable_new_product_sticker'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Control sticker display for products which are marked as NEW in wooCommerce.', 'woo-stickers-by-webline' );?></p>
		<?php
	}
	
	/**
	 * New Product Settings :: Days to New Products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_sticker_days() {
		
		?>
		<input type="text" id="new_product_sticker_days" name="<?php echo $this->new_product_settings_key;?>[new_product_sticker_days]" value="<?php echo absint( $this->new_product_settings['new_product_sticker_days']); ?>" />
		<p class="description"><?php _e( 'Specify the No of days before to be display product as New (Default 10 days).', 'woo-stickers-by-webline' );?></p>
		<?php
	}
	
	/**
	 * New Product Settings :: Sticker Position
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_position() {
		?>
		<select id='new_product_position'
			name="<?php echo $this->new_product_settings_key; ?>[new_product_position]">
			<option value='left'
				<?php selected( $this->new_product_settings['new_product_position'], 'left',true );?>><?php _e( 'Left', 'woo-stickers-by-webline' );?></option>
			<option value='right'
				<?php selected( $this->new_product_settings['new_product_position'], 'right',true );?>><?php _e( 'Right', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select the position of the sticker.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * New Product Sticker Settings :: Sticker Options
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_option() {
		?>
		<div class="woo_opt new_product_option">
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($this->new_product_settings['new_product_option'] == 'image' || $this->new_product_settings['new_product_option'] == '') { echo "checked"; } ?> <?php checked($this->new_product_settings['new_product_option'] ); ?>/>
			<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($this->new_product_settings['new_product_option'] == 'text') { echo "checked"; } ?> <?php checked( $this->new_product_settings['new_product_option'] ); ?>/>
			<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
			<input type="hidden" class="wli_product_option" id="new_product_option" name="<?php echo $this->new_product_settings_key; ?>[new_product_option]" value="<?php if($this->new_product_settings['new_product_option'] == '') { echo 'image'; } else { echo esc_attr( $this->new_product_settings['new_product_option'] ); } ?>"/>
			<p class="description"><?php _e( 'Select any of option for the custom sticker.', 'woo-stickers-by-webline' );?></p>
		</div>
		<?php
		if($this->new_product_settings['new_product_option'] == "text") {
			echo '<style type="text/css">
				.custom_option.custom_opttext { display: table-row; }
			</style>';
		}
		if($this->new_product_settings['new_product_option'] == "image") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
		if($this->new_product_settings['new_product_option'] == "") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
	}

	/**
	 * New Product Sticker Settings :: Custom text for New products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_custom_text() {
		?>
		<input type="text" id="new_product_custom_text" name="<?php echo $this->new_product_settings_key;?>[new_product_custom_text]" value="<?php echo esc_attr( $this->new_product_settings['new_product_custom_text'] ); ?>"/>
		<p class="description"><?php _e( 'Specify the text to show as custom sticker on new products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * New Product Sticker Settings :: Custom sticker type for New products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_new_product_style() {
		?>
		<select id='enable_new_product_style'
			name="<?php echo $this->new_product_settings_key; ?>[enable_new_product_style]">
			<option value='ribbon'
				<?php selected( $this->new_product_settings['enable_new_product_style'], 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
			<option value='round'
				<?php selected( $this->new_product_settings['enable_new_product_style'], 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select custom sticker type to show on New Products.', 'woo-stickers-by-webline' );?></p>
	<?php
	}

	/**
	 * New Product Sticker Settings :: Custom text font color for New products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_custom_text_fontcolor() {
		?>
		<input type="text" id="new_product_custom_text_fontcolor" class="wli_color_picker" name="<?php echo $this->new_product_settings_key;?>[new_product_custom_text_fontcolor]" value="<?php echo ($this->new_product_settings['new_product_custom_text_fontcolor']) ? esc_attr( $this->new_product_settings['new_product_custom_text_fontcolor'] ) : '#ffffff' ?>"/>
		<p class="description"><?php _e( 'Specify font color for text to show as custom sticker on new products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * New Product Sticker Settings :: Custom text font color for New products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_custom_text_backcolor() {
		?>
		<input type="text" id="new_product_custom_text_backcolor" class="wli_color_picker" name="<?php echo $this->new_product_settings_key;?>[new_product_custom_text_backcolor]" value="<?php echo esc_attr( $this->new_product_settings['new_product_custom_text_backcolor'] ); ?>"/>
		<p class="description"><?php _e( 'Specify background color for text to show as custom sticker on new products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * New Product Settings :: Custom Stickers for New Products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function new_product_custom_sticker() {
	
		?>
		
	<?php
	if (get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	if ($this->new_product_settings ['new_product_custom_sticker'] == '')
	{				
		$image_url = "";
		echo '<img class="new_product_custom_sticker" width="125px" height="auto" />';
	}
	else
	{
		$image_url = $this->new_product_settings ['new_product_custom_sticker'];
		echo '<img class="new_product_custom_sticker" src="'.$image_url.'" width="125px" height="auto" />';
	}
	
	
	echo '		<br/>
				<input type="hidden" name="'.$this->new_product_settings_key .'[new_product_custom_sticker]" id="new_product_custom_sticker" value="'. esc_url( $image_url ) .'" />
				<button class="upload_img_btn button">'. __( 'Upload Image', 'woo-stickers-by-webline' ) .'</button>
				<button class="remove_img_btn button">'. __( 'Remove Image', 'woo-stickers-by-webline' ) .'</button>								
			'.$this->custom_sticker_script('new_product_custom_sticker'); ?>

	<p class="description"><?php _e( 'Add your own custom new product image instead of WooStickers default.', 'woo-stickers-by-webline' );?></p>
	<?php
	}

	/**
	 * Sale Product Settings :: Enable Stickers
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sale_product_sticker() {
		?>
		<select id='enable_sale_product_sticker'
			name="<?php echo $this->sale_product_settings_key; ?>[enable_sale_product_sticker]">
			<option value='yes'
				<?php selected( $this->sale_product_settings['enable_sale_product_sticker'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->sale_product_settings['enable_sale_product_sticker'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Control sticker display for products which are marked as under sale in wooCommerce.', 'woo-stickers-by-webline' );?></p>
		<?php
	}
	
	/**
	 * Sale Product Settings :: Sticker Position
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_position() {
		?>
		<select id='sale_product_position'
			name="<?php echo $this->sale_product_settings_key; ?>[sale_product_position]">
			<option value='left'
				<?php selected( $this->sale_product_settings['sale_product_position'], 'left',true );?>><?php _e( 'Left', 'woo-stickers-by-webline' );?></option>
			<option value='right'
				<?php selected( $this->sale_product_settings['sale_product_position'], 'right',true );?>><?php _e( 'Right', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select the position of the sticker.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sale Product Sticker Settings :: Sticker Options
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_option() {
		?>
		<div class="woo_opt sale_product_option">
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($this->sale_product_settings['sale_product_option'] == 'image' || $this->sale_product_settings['sale_product_option'] == '') { echo "checked"; } ?> <?php checked($this->sale_product_settings['sale_product_option'] ); ?>/>
			<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($this->sale_product_settings['sale_product_option'] == 'text') { echo "checked"; } ?> <?php checked( $this->sale_product_settings['sale_product_option'] ); ?>/>
			<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
			<input type="hidden" class="wli_product_option" id="sale_product_option" name="<?php echo $this->sale_product_settings_key; ?>[sale_product_option]" value="<?php if($this->sale_product_settings['sale_product_option'] == '') { echo 'image'; } else { echo esc_attr( $this->sale_product_settings['sale_product_option'] ); } ?>"/>
			<p class="description"><?php _e( 'Select any of option for the custom sticker.', 'woo-stickers-by-webline' );?></p>
		</div>
		<?php
		if($this->sale_product_settings['sale_product_option'] == "text") {
			echo '<style type="text/css">
				.custom_option.custom_opttext { display: table-row; }
			</style>';
		}
		if($this->sale_product_settings['sale_product_option'] == "image") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
		if($this->sale_product_settings['sale_product_option'] == "") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
	}

	/**
	 * Sale Product Sticker Settings :: Custom text for Sale products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_custom_text() {
		?>
		<input type="text" id="sale_product_custom_text" name="<?php echo $this->sale_product_settings_key;?>[sale_product_custom_text]" value="<?php echo esc_attr( $this->sale_product_settings['sale_product_custom_text']); ?>"/>
		<p class="description"><?php _e( 'Specify the text to show as custom sticker on sale products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sale Product Sticker Settings :: Custom sticker type for Sale products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sale_product_style() {
		?>
		<select id='enable_sale_product_style'
			name="<?php echo $this->sale_product_settings_key; ?>[enable_sale_product_style]">
			<option value='ribbon'
				<?php selected( $this->sale_product_settings['enable_sale_product_style'], 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
			<option value='round'
				<?php selected( $this->sale_product_settings['enable_sale_product_style'], 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select custom sticker type to show on Sale Products.', 'woo-stickers-by-webline' );?></p>
	<?php
	}

	/**
	 * Sale Product Sticker Settings :: Custom text font color for Sale products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_custom_text_fontcolor() {
		?>
		<input type="text" id="sale_product_custom_text_fontcolor" class="wli_color_picker" name="<?php echo $this->sale_product_settings_key;?>[sale_product_custom_text_fontcolor]" value="<?php echo ($this->sale_product_settings['sale_product_custom_text_fontcolor']) ? esc_attr( $this->sale_product_settings['sale_product_custom_text_fontcolor'] ) : '#ffffff' ?>"/>
		<p class="description"><?php _e( 'Specify font color for text to show as custom sticker on sale products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sale Product Sticker Settings :: Custom text font color for Sale products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_custom_text_backcolor() {
		?>
		<input type="text" id="sale_product_custom_text_backcolor" class="wli_color_picker" name="<?php echo $this->sale_product_settings_key;?>[sale_product_custom_text_backcolor]" value="<?php echo esc_attr( $this->sale_product_settings['sale_product_custom_text_backcolor'] ); ?>"/>
		<p class="description"><?php _e( 'Specify background color for text to show as custom sticker on sale products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sale Product Settings :: Custom Stickers for Sale Products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sale_product_custom_sticker() {
	
		?>
			
		<?php
		if (get_bloginfo('version') >= 3.5)
			wp_enqueue_media();
		else {
			wp_enqueue_style('thickbox');
			wp_enqueue_script('thickbox');
		}
		if ($this->sale_product_settings ['sale_product_custom_sticker'] == '' )
		{
			$image_url = "";
			echo '<img class="sale_product_custom_sticker" width="125px" height="auto" />';
		}
		else
		{
			$image_url = $this->sale_product_settings ['sale_product_custom_sticker'];
			echo '<img class="sale_product_custom_sticker" src="'.$image_url.'" width="125px" height="auto" />';
		}
		echo '		<br/>
					<input type="hidden" name="'.$this->sale_product_settings_key .'[sale_product_custom_sticker]" id="sale_product_custom_sticker" value="'. esc_url( $image_url ) .'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'woo-stickers-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'woo-stickers-by-webline' ) .'</button>								
				'.$this->custom_sticker_script('sale_product_custom_sticker'); ?>		
		<p class="description"><?php _e( 'Add your own custom sale product image instead of WooStickers default.', 'woo-stickers-by-webline' );?></p>
		<?php
			}
	/**
	 * Sold Product Settings :: Enable Stickers
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sold_product_sticker() {
		?>
		<select id='enable_sold_product_sticker'
			name="<?php echo $this->sold_product_settings_key; ?>[enable_sold_product_sticker]">
			<option value='yes'
				<?php selected( $this->sold_product_settings['enable_sold_product_sticker'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->sold_product_settings['enable_sold_product_sticker'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Control sticker display for products which are marked as under sold in wooCommerce.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sold Product Settings :: Sticker Position
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_position() {
		?>
		<select id='sold_product_position'
			name="<?php echo $this->sold_product_settings_key; ?>[sold_product_position]">
			<option value='left'
				<?php selected( $this->sold_product_settings['sold_product_position'], 'left',true );?>><?php _e( 'Left', 'woo-stickers-by-webline' );?></option>
			<option value='right'
				<?php selected( $this->sold_product_settings['sold_product_position'], 'right',true );?>><?php _e( 'Right', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select the position of the sticker.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sold Product Sticker Settings :: Sticker Options
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_option() {
		?>
		<div class="woo_opt sold_product_option">
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($this->sold_product_settings['sold_product_option'] == 'image' || $this->sold_product_settings['sold_product_option'] == '') { echo "checked"; } ?> <?php checked($this->sold_product_settings['sold_product_option'] ); ?>/>
			<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($this->sold_product_settings['sold_product_option'] == 'text') { echo "checked"; } ?> <?php checked( $this->sold_product_settings['sold_product_option'] ); ?>/>
			<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
			<input type="hidden" class="wli_product_option" id="sold_product_option" name="<?php echo $this->sold_product_settings_key; ?>[sold_product_option]" value="<?php if($this->sold_product_settings['sold_product_option'] == '') { echo 'image'; } else { echo esc_attr( $this->sold_product_settings['sold_product_option'] ); } ?>"/>
			<p class="description"><?php _e( 'Select any of option for the custom sticker.', 'woo-stickers-by-webline' );?></p>
		</div>
		<?php
		if($this->sold_product_settings['sold_product_option'] == "text") {
			echo '<style type="text/css">
				.custom_option.custom_opttext { display: table-row; }
			</style>';
		}
		if($this->sold_product_settings['sold_product_option'] == "image") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
		if($this->sold_product_settings['sold_product_option'] == "") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
	}

	/**
	 * Sold Product Sticker Settings :: Custom text for Sold products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_custom_text() {
		?>
		<input type="text" id="sold_product_custom_text" name="<?php echo $this->sold_product_settings_key;?>[sold_product_custom_text]" value="<?php echo esc_attr( $this->sold_product_settings['sold_product_custom_text'] ); ?>"/>
		<p class="description"><?php _e( 'Specify the text to show as custom sticker on products, Leave it blank if you use WooStickers default.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sold Product Sticker Settings :: Custom sticker type for Sold products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_sold_product_style() {
		?>
		<select id='enable_sold_product_style'
			name="<?php echo $this->sold_product_settings_key; ?>[enable_sold_product_style]">
			<option value='ribbon'
				<?php selected( $this->sold_product_settings['enable_sold_product_style'], 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
			<option value='round'
				<?php selected( $this->sold_product_settings['enable_sold_product_style'], 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select custom sticker type to show on Sold Products.', 'woo-stickers-by-webline' );?></p>
	<?php
	}

	/**
	 * Sold Product Sticker Settings :: Custom text font color for Sold products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_custom_text_fontcolor() {
		?>
		<input type="text" id="sold_product_custom_text_fontcolor" class="wli_color_picker" name="<?php echo $this->sold_product_settings_key;?>[sold_product_custom_text_fontcolor]" value="<?php echo ($this->sold_product_settings['sold_product_custom_text_fontcolor']) ? esc_attr( $this->sold_product_settings['sold_product_custom_text_fontcolor'] ) : '#ffffff' ?>"/>
		<p class="description"><?php _e( 'Specify font color for text to show as custom sticker on sold products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sold Product Sticker Settings :: Custom text font color for Sold products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_custom_text_backcolor() {
		?>
		<input type="text" id="sold_product_custom_text_backcolor" class="wli_color_picker" name="<?php echo $this->sold_product_settings_key;?>[sold_product_custom_text_backcolor]" value="<?php echo esc_attr( $this->sold_product_settings['sold_product_custom_text_backcolor'] ); ?>"/>
		<p class="description"><?php _e( 'Specify background color for text to show as custom sticker on sold products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Sold Product Settings :: Custom Stickers for Sold Products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function sold_product_custom_sticker() {

		if (get_bloginfo('version') >= 3.5)
			wp_enqueue_media();
		else {
			wp_enqueue_style('thickbox');
			wp_enqueue_script('thickbox');
		}
		//print_r(CV_DEFAULT_IMAGE); die;	
		if ($this->sold_product_settings ['sold_product_custom_sticker'] == '')
		{
			$image_url = "";
			echo '<img class="sold_product_custom_sticker" width="125px" height="auto" />';
		}
		else
		{
			$image_url = $this->sold_product_settings ['sold_product_custom_sticker'];
			echo '<img class="sold_product_custom_sticker" src="'.$image_url.'" width="125px" height="auto" />';
		}
		echo '		<br/>
					<input type="hidden" name="'.$this->sold_product_settings_key .'[sold_product_custom_sticker]" id="sold_product_custom_sticker" value="'. esc_url( $image_url ) .'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'woo-stickers-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'woo-stickers-by-webline' ) .'</button>								
				'.$this->custom_sticker_script('sold_product_custom_sticker'); ?>			
		<p class="description"><?php _e( 'Add your own custom sold product image instead of WooStickers default.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Enable Stickers
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_cust_product_sticker() {
		?>
		<select id='enable_cust_product_sticker'
			name="<?php echo $this->cust_product_settings_key; ?>[enable_cust_product_sticker]">
			<option value='yes'
				<?php selected( $this->cust_product_settings['enable_cust_product_sticker'], 'yes',true );?>><?php _e( 'Yes', 'woo-stickers-by-webline' );?></option>
			<option value='no'
				<?php selected( $this->cust_product_settings['enable_cust_product_sticker'], 'no',true );?>><?php _e( 'No', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Control custom sticker display for all products in wooCommerce.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Sticker Position
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_position() {
		?>
		<select id='cust_product_position'
			name="<?php echo $this->cust_product_settings_key; ?>[cust_product_position]">
			<option value='left'
				<?php selected( $this->cust_product_settings['cust_product_position'], 'left',true );?>><?php _e( 'Left', 'woo-stickers-by-webline' );?></option>
			<option value='right'
				<?php selected( $this->cust_product_settings['cust_product_position'], 'right',true );?>><?php _e( 'Right', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select the position of the custom sticker.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Sticker Options
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_option() {
		?>
		<div class="woo_opt cust_product_option">
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="image" value="image" <?php if($this->cust_product_settings['cust_product_option'] == 'image' || $this->cust_product_settings['cust_product_option'] == '') { echo "checked"; } ?> <?php checked($this->cust_product_settings['cust_product_option'] ); ?>/>
			<label for="image"><?php _e( 'Image', 'woo-stickers-by-webline' );?></label>
			<input type="radio" name="stickeroption" class="wli-woosticker-radio" id="text" value="text" <?php if($this->cust_product_settings['cust_product_option'] == 'text') { echo "checked"; } ?> <?php checked( $this->cust_product_settings['cust_product_option'] ); ?>/>
			<label for="text"><?php _e( 'Text', 'woo-stickers-by-webline' );?></label>
			<input type="hidden" class="wli_product_option" id="cust_product_option" name="<?php echo $this->cust_product_settings_key; ?>[cust_product_option]" value="<?php if($this->cust_product_settings['cust_product_option'] == '') { echo 'image'; } else { echo esc_attr( $this->cust_product_settings['cust_product_option'] ); } ?>"/>
			<p class="description"><?php _e( 'Select any of option for the custom sticker.', 'woo-stickers-by-webline' );?></p>
		</div>
		<?php
		if($this->cust_product_settings['cust_product_option'] == "text") {
			echo '<style type="text/css">
				.custom_option.custom_opttext { display: table-row; }
			</style>';
		}
		if($this->cust_product_settings['cust_product_option'] == "image") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
		if($this->cust_product_settings['cust_product_option'] == "") {
			echo '<style type="text/css">
				.custom_option.custom_optimage { display: table-row; }
			</style>';
		}
	}

	/**
	 * Custom Product Sticker Settings :: Custom text for all products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_custom_text() {
		?>
		<input type="text" id="cust_product_custom_text" name="<?php echo $this->cust_product_settings_key;?>[cust_product_custom_text]" value="<?php echo esc_attr( $this->cust_product_settings['cust_product_custom_text'] ); ?>"/>
		<p class="description"><?php _e( 'Specify the text to show as custom sticker on products, Leave it blank if you use WooStickers default.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Custom sticker type for all products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function enable_cust_product_style() {
		?>
		<select id='enable_cust_product_style'
			name="<?php echo $this->cust_product_settings_key; ?>[enable_cust_product_style]">
			<option value='ribbon'
				<?php selected( $this->cust_product_settings['enable_cust_product_style'], 'ribbon',true );?>><?php _e( 'Ribbon', 'woo-stickers-by-webline' );?></option>
			<option value='round'
				<?php selected( $this->cust_product_settings['enable_cust_product_style'], 'round',true );?>><?php _e( 'Round', 'woo-stickers-by-webline' );?></option>
		</select>
		<p class="description"><?php _e( 'Select custom sticker layout to show on products.', 'woo-stickers-by-webline' );?></p>
	<?php
	}

	/**
	 * Custom Product Sticker Settings :: Custom text font color for all products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_custom_text_fontcolor() {
		?>
		<input type="text" id="cust_product_custom_text_fontcolor" class="wli_color_picker" name="<?php echo $this->cust_product_settings_key;?>[cust_product_custom_text_fontcolor]" value="<?php echo ($this->cust_product_settings['cust_product_custom_text_fontcolor']) ? esc_attr( $this->cust_product_settings['cust_product_custom_text_fontcolor'] ) : '#ffffff' ?>"/>
		<p class="description"><?php _e( 'Specify font color for text to show as custom sticker on products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Custom text font color for all products 
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_custom_text_backcolor() {
		?>
		<input type="text" id="cust_product_custom_text_backcolor" class="wli_color_picker" name="<?php echo $this->cust_product_settings_key;?>[cust_product_custom_text_backcolor]" value="<?php echo esc_attr( $this->cust_product_settings['cust_product_custom_text_backcolor'] ); ?>"/>
		<p class="description"><?php _e( 'Specify background color for text to show as custom sticker on products.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Custom Product Sticker Settings :: Custom Stickers for all Products
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function cust_product_custom_sticker() {

		if (get_bloginfo('version') >= 3.5)
			wp_enqueue_media();
		else {
			wp_enqueue_style('thickbox');
			wp_enqueue_script('thickbox');
		}
		//print_r(CV_DEFAULT_IMAGE); die;	
		if ($this->cust_product_settings ['cust_product_custom_sticker'] == '')
		{
			$image_url = "";
			echo '<img class="cust_product_custom_sticker" width="125px" height="auto" />';
		}
		else
		{
			$image_url = $this->cust_product_settings ['cust_product_custom_sticker'];
			echo '<img class="cust_product_custom_sticker" src="'.$image_url.'" width="125px" height="auto" />';
		}
		echo '		<br/>
					<input type="hidden" name="'.$this->cust_product_settings_key .'[cust_product_custom_sticker]" id="cust_product_custom_sticker" value="'. esc_url( $image_url ) .'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'woo-stickers-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'woo-stickers-by-webline' ) .'</button>								
				'.$this->custom_sticker_script('cust_product_custom_sticker'); ?>			
		<p class="description"><?php _e( 'Add your own custom product image instead of WooStickers default.', 'woo-stickers-by-webline' );?></p>
		<?php
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menus() {

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

		add_options_page ( __( 'WLI Woocommerce Stickers', 'woo-stickers-by-webline' ), __( 'WOO Stickers', 'woo-stickers-by-webline' ), 'manage_options', $this->plugin_options_key, array (
				&$this,
				'plugin_options_page' 
		) );
	}

	public function plugin_options_page(){
		$tab = isset ( $_GET ['tab'] ) ? $_GET ['tab'] : $this->general_settings_key;
		?>
		<div class="wrap">
		<h2><?php _e( 'WOO Stickers by Webline - Configuration Settings', 'woo-stickers-by-webline' );?></h2>
			<?php $this->cta_section_callback(); ?>
			<?php $this->plugin_options_tabs(); ?>
			<form class="wli-form-general" method="post" action="options.php">
				<?php wp_nonce_field( 'update-options' ); ?>
				<?php settings_fields( $tab ); ?>
				<?php do_settings_sections( $tab ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one.
	 * Provides the heading for the
	 * plugin_options_page method.
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function plugin_options_tabs() {
		$current_tab = isset ( $_GET ['tab'] ) ? $_GET ['tab'] : $this->general_settings_key;
		//screen_icon ();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
		}
		echo '</h2>';
	}
	
	/**
	 *   custom_sticker_script() is used to upload using wordpress upload.
	 *
	 *  @since    			1.0.0
	 *
	 *  @return             script
	 *  @var                No arguments passed
	 *  @author             Weblineindia
	 *
	 */
	public function custom_sticker_script($obj_url) {
		return '<script type="text/javascript">
	    jQuery(document).ready(function() {
			var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
			jQuery(".upload_img_btn").click(function(event) {
				upload_button = jQuery(this);
				var frame;
				jQuery(this).parent().children("img").attr("src","").show();					
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {					
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("cat_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
						{
							jQuery("#'.$obj_url.'").val(attachment.attributes.url);
							jQuery(".'.$obj_url.'").attr("src",attachment.attributes.url);
						}
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
	
			jQuery(".remove_img_btn").click(function() {
				jQuery("#'.$obj_url.'").val("");
				if(jQuery(this).parent().children("img").attr("src")!="undefined")	
				{ 
					jQuery(this).parent().children("img").attr("src","").hide();
					jQuery(this).parent().siblings(".title").children("img").attr("src"," ");
					jQuery(".inline-edit-col :input[name=\''.$obj_url.'\']").val(""); 
				}	
				else
				{
					jQuery(this).parent().children("img").attr("src","").hide();
				}						
				return false;
			});
	
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = jQuery("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("cat_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
					{
						jQuery("#'.$obj_url.'").val(imgurl);
						jQuery(".'.$obj_url.'").attr("src",imgurl);
					}
					tb_remove();
				}
			}
	
			jQuery(".editinline").click(function(){
			    var tax_id = jQuery(this).parents("tr").attr("id").substr(4);
			    var thumb = jQuery("#tag-"+tax_id+" .thumb img").attr("src");
				if (thumb != "") {
					jQuery(".inline-edit-col :input[name=\''.$obj_url.'\']").val(thumb);
				} else {
					jQuery(".inline-edit-col :input[name=\''.$obj_url.'\']").val("");
				}
				jQuery(".inline-edit-col .title img").attr("src",thumb);
			    return true;
			});
	    });
	</script>';
	}

	/**
	 * CTA section callback function.
	 *
	 * @since    1.0.0
	 */
	public function cta_section_callback() {
		?>
		<div class="wliplugin-cta-wrap">
			<h1 class="head">We're here to help !</h1>
			<p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Woo%20Stickers&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Woo%20Stickers&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
		</div>
		<div class="wliplugin-cta-upgrade">
			<p class="note">Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Woo%20Stickers&utm_campaign=Hire%20WP%20Developer" target="_blank" class="button button-primary">Hire now</a>
		</div>
		<?php
	}

	/**
	 * Display footer text that graciously asks them to rate us.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {

		global $current_screen;

		//Check of relatd screen match
		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, $this->plugin_options_key ) !== false ) {
			
			$url  = 'https://wordpress.org/support/plugin/woo-stickers-by-webline/reviews/?filter=5#new-post';
			$wpdev_url  = 'https://www.weblineindia.com/wordpress-development.html?utm_source=WP-Plugin&utm_medium=Woo%20Stickers&utm_campaign=Footer%20CTA';
			$text = sprintf(
				wp_kses(
					'Please rate our plugin %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the <a href="%4$s" target="_blank" rel="noopener noreferrer">WordPress development</a> team at WeblineIndia.',
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>"WOO Stickers by Webline"</strong>',
				$url,
				$url,
				$wpdev_url
			);
		}

		return $text;
	}
}

