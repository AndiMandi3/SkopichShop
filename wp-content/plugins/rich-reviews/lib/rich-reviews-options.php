<?php
/**
 * OPTIONS
 *
 * Main class for managing Rich Reviews Settings instance
 *
 * @package WordPress
 * @subpackage rich-reviews
 * @version 1.0.0
 * @author  silvercolt45 <webmaster@silvercolt.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link https://codex.wordpress.org/Settings_API
 * @since  1.7.0
 */
class RR_OPTIONS {

	private $options;

	private $admin_options;
	private $form_options;
	private $display_options;
	private $user_options;
	private $markup_options;

	private $current_options;
	private $size;

	function __construct() {
		
		$this->size = 'size = "50"';
		$this->admin_options = self::getOptions('admin');
		$this->form_options = self::getOptions('form');
		$this->display_options = self::getOptions('display');
		$this->user_options = self::getOptions('user');
		$this->markup_options = self::getOptions('markup');
		$current_admin_options = get_option( RR_OPTIONS_ADMIN );
		$current_form_options = get_option( RR_OPTIONS_FORM );

		$current_display_options = get_option( RR_OPTIONS_DISPLAY );
		$current_user_options = get_option( RR_OPTIONS_USER );
		$current_markup_options = get_option( RR_OPTIONS_MARKUP );
		$this->current_options = array_merge($current_admin_options,$current_display_options,$current_form_options,$current_markup_options);
        if(is_array($current_user_options)) {
            $this->current_options = array_merge($this->current_options, $current_user_options);
        }
		// Register Settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		// Add Sections
		add_action( 'admin_init', array( $this, 'section_settings' ) );
		// Clear permalinks
		add_action( 'shutdown', 'flush_rewrite_rules');
	}

	/**
	 * Register Settings
	 */
	function register_settings() {

		register_setting( RR_OPTIONS_ADMIN, RR_OPTIONS_ADMIN, [
			'type'              => 'string',
			'description'       => 'Rich Reviews ADMIN Options',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
			'default'           => $this->admin_options
		] );

		register_setting( RR_OPTIONS_FORM, RR_OPTIONS_FORM, [
			'type'              => 'string',
			'description'       => 'Rich Reviews FORM Options',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
			'default'           => $this->form_options
		] );

		register_setting( RR_OPTIONS_DISPLAY, RR_OPTIONS_DISPLAY, [
			'type'              => 'string',
			'description'       => 'Rich Reviews DISPLAY Options',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
			'default'           => $this->display_options
		] );

		register_setting( RR_OPTIONS_USER, RR_OPTIONS_USER, [
			'type'              => 'string',
			'description'       => 'Rich Reviews USER Options',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
			'default'           => $this->user_options
		] );

		register_setting( RR_OPTIONS_MARKUP, RR_OPTIONS_MARKUP, [
			'type'              => 'string',
			'description'       => 'Rich Reviews MARKUP Options',
			'sanitize_callback' => null,
			'show_in_rest'      => false,
			'default'           => $this->markup_options
		] );

	}

	/**
	 * Add Setting Sections
	 */
	function section_settings() {

		// Admin Section
		add_settings_section(
			'rr_admin_settings_section', // ID
			esc_html__( 'Approval Options', 'rich-reviews' ), // Title
			array( $this, 'print_admin_section_info' ), // Callback
			RR_OPTIONS_ADMIN // Page
		);

		// Form Section
		add_settings_section(
			'rr_form_settings_section', // ID
			esc_html__( 'Form', 'rich-reviews' ), // Title
			array( $this, 'print_form_section_info' ), // Callback
			RR_OPTIONS_FORM // Page
		);

		// Display Section
		add_settings_section(
			'rr_display_settings_section', // ID
			esc_html__( 'Display ', 'rich-reviews' ), // Title
			array( $this, 'print_display_section_info' ), // Callback
			RR_OPTIONS_DISPLAY // Page
		);

		// User Section
		add_settings_section(
			'rr_user_settings_section', // ID
			esc_html__( 'User ', 'rich-reviews' ), // Title
			array( $this, 'print_user_section_info' ), // Callback
			RR_OPTIONS_USER // Page
		);

		// Markup Section
		add_settings_section(
			'rr_markup_settings_section', // ID
			esc_html__( 'Markup ', 'rich-reviews' ), // Title
			array( $this, 'print_markup_section_info' ), // Callback
			RR_OPTIONS_MARKUP // Page
		);

		// Add Settings Fields by section
		$this->admin_settings_fields();
		$this->form_settings_fields();
		$this->display_settings_fields();
		$this->user_settings_fields();
		$this->markup_settings_fields();

	}

	/*
	 * Admin Settings Fields
	 */
	function admin_settings_fields() {

		$option_configs = self::getOptions('admin');
		foreach( $option_configs as $option => $configs ) {
			add_settings_field(
				$option, // ID
				$configs['title'], // Title
				array( $this, $configs['field_callback'] ), // Callback
				RR_OPTIONS_ADMIN, // Page
				'rr_admin_settings_section', // Section ID
				array(
					'label_for'   => $option,
					'options'     => $configs['options'],
					'description' => $configs['description'],
					'option_name' => RR_OPTIONS_ADMIN
				)
			);
		}

	}

	/*
	 * Form Settings Fields
	 */
	function form_settings_fields() {

		$option_configs = self::getOptions('form');
		foreach( $option_configs as $option => $configs ) {
			add_settings_field(
				$option, // ID
				$configs['title'], // Title
				array( $this, $configs['field_callback'] ), // Callback
				RR_OPTIONS_FORM, // Page
				'rr_form_settings_section', // Section ID
				array(
					'label_for'   => $option,
					'options'     => $configs['options'],
					'description' => $configs['description'],
					'option_name' => RR_OPTIONS_FORM
				)
			);
		}

	}

	/*
	 * Display Settings Fields
	 */
	function display_settings_fields() {

		$option_configs = self::getOptions('display');
		foreach( $option_configs as $option => $configs ) {
			add_settings_field(
				$option, // ID
				$configs['title'], // Title
				array( $this, $configs['field_callback'] ), // Callback
				RR_OPTIONS_DISPLAY, // Page
				'rr_display_settings_section', // Section ID
				array(
					'label_for'   => $option,
					'options'     => $configs['options'],
					'description' => $configs['description'],
					'option_name' => RR_OPTIONS_DISPLAY
				)
			);
		}

	}

	/*
	 * User Settings Fields
	 */
	function user_settings_fields() {

		$option_configs = self::getOptions('user');
		foreach( $option_configs as $option => $configs ) {
			add_settings_field(
				$option, // ID
				$configs['title'], // Title
				array( $this, $configs['field_callback'] ), // Callback
				RR_OPTIONS_USER, // Page
				'rr_user_settings_section', // Section ID
				array(
					'label_for'   => $option,
					'options'     => $configs['options'],
					'description' => $configs['description'],
					'option_name' => RR_OPTIONS_USER
				)
			);
		}

	}

	/*
	 * Markup Settings Fields
	 */
	function markup_settings_fields() {

		$option_configs = self::getOptions('markup');
		
		foreach( $option_configs as $option => $configs ) {
			add_settings_field(
				$option, // ID
				$configs['title'], // Title
				array( $this, $configs['field_callback'] ), // Callback
				RR_OPTIONS_MARKUP, // Page
				'rr_markup_settings_section', // Section ID
				array(
					'label_for'   => $option,
					'options'     => $configs['options'],
					'description' => $configs['description'],
					'option_name' => RR_OPTIONS_MARKUP
				)
			);
		}

	}

	/**
	 * SECTION INFO: Print the Admin Section text
	 */
	function print_admin_section_info( $args )
	{
		print sprintf( __('Specify %1$s settings below', 'rich-reviews'), $args['title'] );
	}

	/**
	 * SECTION INFO: Print the Form Section text
	 */
	function print_form_section_info( $args )
	{
		print sprintf( __('Specify %1$s settings below', 'rich-reviews'), $args['title'] );
	}

	/**
	 * SECTION INFO: Print the Display Section text
	 */
	function print_display_section_info( $args )
	{
		print sprintf( __('Specify %1$s settings below', 'rich-reviews'), $args['title'] );
	}

	/**
	 * SECTION INFO: Print the User Section text
	 */
	function print_user_section_info( $args )
	{
		print sprintf( __('Specify %1$s settings below', 'rich-reviews'), $args['title'] );
	}

	/**
	 * SECTION INFO: Print the Markup Section text
	 */
	function print_markup_section_info( $args )
	{
		$title = esc_html__( 'Instructions', 'rich-reviews' );
		$review_title = esc_html__( 'Review', 'rich-reviews' );
		$review_instructions = esc_html__('In order to output complete markup for for recognizable "Review" rich schema, there must be a value set for the item reviewed itemprop. Rich Reviews does this by using the category for which reviews are set. If this is not set, Rich Reviews will use the Page Title of the page from which the review was submited. However, if neither of these items are set, there needs to be a fallback set. You can do this, and adjust it\'s use case below. (category="page" or category="post" is considered a category being set, and will use the page title if available, or the fallback if not)', 'rich-reviews');
		$author_title = esc_html__( 'Author', 'rich-reviews' );
		$author_instructions = esc_html__( 'The Author field is an optional data value in rich formatting for a "Review", however the more information provided, the better one\'s reviews will appear. For this reason Rich Reviews has an Author fallback as well for the case that the "Name" field is either not used or not required. You can set that below.', 'rich-reviews');
		print <<<MARKUP_INSTRUCTIONS
<div class="rr-settings-info"></div>
<div class="rr-settings-sidebar">
	<div class="rr-settings-sidebar-title"><span class="fas fa-info"></span> $title</div>
		<h4>$review_title</h4>
			$review_instructions
		<h4>$author_title</h4>
			$author_instructions
	</div>
MARKUP_INSTRUCTIONS;
	}

	/**
	 * PAGE: Admin
	 * SECTION: Admin
	 * FIELD CALLBACK: Option Select
	 */
	function rr_input_select_callback( $args )
	{
		$current = (isset($this->current_options[$args['label_for']]) ? esc_html( $this->current_options[$args['label_for']] ) : '' );
		echo '<select id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . ']">';
		foreach( $args['options'] as $option => $title ) {
			$selected = ($current == $option) ? 'selected="selected"' : '';
			echo '<option value="' . $option . '" ' . $selected . '>' . $title . '</option>';
		}
		echo '</select>';
		echo '<p class="description">' . $args['description'] . '</p>';
	}

	/**
	 * PAGE: Admin
	 * SECTION: Admin
	 * FIELD CALLBACK: Checkbox
	 */
	function rr_input_checkbox_callback( $args )
	{
		$checked = (isset($this->current_options[$args['label_for']]) ? esc_html( $this->current_options[$args['label_for']] ) : '' );
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . ']" type="checkbox" value="checked" ' . $checked . '>';
		echo '<p class="description">' . $args['description'] . '</p>';
	}

	/**
	 * PAGE: Admin
	 * SECTION: Admin
	 * FIELD CALLBACK: Text Field
	 */
	function rr_input_text_callback( $args )
	{
		$text = (isset($this->current_options[$args['label_for']]) ? esc_html( $this->current_options[$args['label_for']] ) : '');
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . ']" type="text" value="' . $text .'" '.$this->size.'>';
		echo '<p class="description">' . $args['description'] . '</p>';
	}
		
	function rr_input_range_callback( $args )
	{
		$from_price = (isset($this->current_options[$args['label_for'].'_from']) ? esc_html( $this->current_options[$args['label_for'].'_from'] ) : '');
		$to_price = (isset($this->current_options[$args['label_for'].'_to']) ? esc_html( $this->current_options[$args['label_for'].'_to'] ) : '');
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . '_from]" type="number" value="'.$from_price.'" '.$this->size.'>';
		echo "<span class='range'>to</span>";
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . '_to]" type="number" value="' . $to_price .'" '.$this->size.'>';
		echo '<p class="description">' . $args['description'] . '</p>';
	}
		
	function rr_input_text_callback_hidden( $args )
	{		
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . ']" type="hidden" value="' . esc_html( $this->current_options['version'] ) .'" '.$this->size.'>';
		echo '<p class="description">' . $args['description'] . '</p>';
	}

	/**
	 * PAGE: Admin
	 * SECTION: Admin
	 * FIELD CALLBACK: Number Field
	 */
	function rr_input_number_callback( $args )
	{
		$number = (isset($this->current_options[$args['label_for']]) ? esc_html( $this->current_options[$args['label_for']] ) : '');
		echo '<input id="' . $args['label_for'] . '" name="' . $args['option_name'] . '[' . $args['label_for'] . ']" type="number" min="0" value="' .$number.'">';
		echo '<p class="description">' . $args['description'] . '</p>';
	}

	/**
	 * Get Options
	 */
	public static function getOptions( $type ) {
		$organization = 'Organization';
		$number = 'number';
		switch($type) {	
			case 'admin':
				return [
					RR_OPTION_REQUIRE_APPROVAL => [
						'title'             => esc_html__( 'Require Approval', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Require Approval - this sends all new reviews to the pending review page. Unchecking this will automatically publish all reviews as they are submitted.', 'rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_APPROVE_AUTHORITY => [
						'title'             => esc_html__( 'Approve Authority', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Authority level required to Approve Pending Posts','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_select_callback',
						'show_in_rest'      => false,
						'default'           => 'manage_options',
						'options'           => [
							'manage_options'        => 'Admin',
							'moderate_comments'     => 'Editor',
							'edit_published_posts'  => 'Author',
							'edit_posts'            => 'Contributor',
							'read'                  => 'Subscriber'
						]
					],
					RR_OPTION_SEND_EMAIL_NOTIFICATION => [
						'title'             => esc_html__( 'Send Email Notifications', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Send Notification Emails - This will send an automatic email to the admin every time a new pending review is submitted.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_ADMIN_EMAIL => [
						'title'             => esc_html__( 'Admin Email', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Admin Email - The email to which notifications are sent.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_VERSION_HIDDEN => [
						'title'             => '',
						'type'              => 'string',
						'description'       => '',
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback_hidden',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					]
				];
				break;
			case 'form':
				return [
					RR_OPTION_FORM_NAME_LABEL => [
						'title'             => esc_html__( 'Name Label', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The Name field label','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html_x('Name', 'The Name field label','rich-reviews'),
						'options'           => null
					],
					RR_OPTION_FORM_NAME_DISPLAY    => [
						'title'             => esc_html__( 'Display Name', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display the Name field?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_NAME_REQUIRE => [
						'title'             => esc_html__( 'Name Required', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Make the Name field required?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_EMAIL_LABEL => [
						'title'             => esc_html__( 'Email Label', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The Email field label','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html_x('Email', 'The Email field label','rich-reviews'),
						'options'           => null
					],
					RR_OPTION_FORM_EMAIL_DISPLAY => [
						'title'             => esc_html__( 'Display Email', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display the Email field?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_EMAIL_REQUIRE => [
						'title'             => esc_html__( 'Email Required', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Make the Email field required?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_TITLE_LABEL => [
						'title'             => esc_html__( 'Title', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The title of the Review form','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('Review Title', 'rich-reviews' ),
						'options'           => null
					],
					RR_OPTION_FORM_TITLE_DISPLAY => [
						'title'             => esc_html__( 'Display Title', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display the Review form title?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_TITLE_REQUIRE => [
						'title'             => esc_html__( 'Title Required', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Make the Review form title required?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_RATING_LABEL => [
						'title'             => esc_html__( 'Rating Label', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Form Rating Label','rich-reviews' ),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('Rating','rich-reviews' ),
						'options'           => null
					],
					RR_OPTION_FORM_CONTENT_LABEL => [
						'title'             => esc_html__( 'Content Label', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Content field label" Link','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('Review Content', 'rich-reviews' ),
						'options'           => null
					],
					RR_OPTION_FORM_CONTENT_REQUIRE => [
						'title'             => esc_html__( 'Content Required', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Make the Content field required?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_CONTENT_DISPLAY => [
						'title'             => esc_html__( 'Display Content', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display the content field?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_SUBMIT_TEXT => [
						'title'             => esc_html__( 'Submit Button Text', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The button text to submit the form','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('Submit', 'rich-reviews' ),
						'options'           => null
					],
					RR_OPTION_RETURN_TO_FORM => [
						'title'             => esc_html__( 'Return to Form', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Upon submission of the review form, the page will automatically scroll back to the location of the form.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					]
				];
				break;
			case 'display':
				return [
					RR_OPTION_SHOW_FORM_POST_TITLE => [
						'title'             => esc_html__( 'Title', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Include Post Titles - this will include the title and a link to the form page for every review.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_SNIPPET_STARS           => [
						'title'             => esc_html__( 'Rating Stars', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Star Snippets - this will change the averge rating displayed in the snippet shortcode to be stars instead of numerical values.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_STAR_COLOR => [
						'title'             => esc_html__( 'Star Color', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Star Color - the color of the stars on reviews','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('#ffaf00', 'rich-reviews'),
						'options'           => null
					],
					RR_OPTION_DISPLAY_FULL_WIDTH      => [
						'title'             => esc_html__( 'Full Width', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display Full width - This option will display the reviews in full width block format. Default will display the reviews in blocks of three.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_SHOW_DATE   => [
						'title'             => esc_html__( 'Show Date', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Display the date that the review was submitted inside the review.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_EXCERPT_LENGTH      => [
						'title'             => esc_html__( 'Except Length', 'rich-reviews' ),
						'type'              => $number,
						'description'       => esc_html__('Character Length for Excerpt','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_number_callback',
						'show_in_rest'      => false,
						'default'           => 150,
						'options'           => null
					],
					RR_OPTION_CREDIT_PERMISSION        => [
						'title'             => esc_html__( 'Credit Permission', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Give Credit to Starfish - this option will add a small credit line and a link to Starfish\'s website to the bottom of your reviews page','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => '',
						'options'           => null
					],
					RR_OPTION_REVIEWS_ORDER       => [
						'title'             => esc_html__( 'Review Order', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Review Display Order','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_select_callback',
						'show_in_rest'      => false,
						'default'           => esc_html__('asc', 'rich-reviews'),
						'options'           => [
							'asc' => 'Oldest First',
							'desc' => 'Newest First',
							'random' => 'Randomize'
						]
					]
				];
				break;
			case 'user':
				return [
					RR_OPTION_INTEGRATE_USER_INFO => [
						'title'             => esc_html__( 'Integrate User Accounts', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('checking this will enable the remaining options','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => false,
						'options'           => null
					],
					RR_OPTION_FORM_NAME_USE_USERNAMES           => [
						'title'             => esc_html__( 'Autofill Name', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Autofill Name from User Accounts?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_FORM_NAME_USER_AVATAR => [
						'title'             => esc_html__( 'User Avatars', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Use user avatars?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => 'checked',
						'options'           => null
					],
					RR_OPTION_REQUIRE_LOGIN      => [
						'title'             => esc_html__( 'Require Login', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Require Users to be logged in to submit reviews?','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => false,
						'options'           => null
					]
				];
				break;
			case 'markup':
				return [
					RR_OPTION_RICH_ITEM_REVIEWED_FALLBACK => [
						'title'             => esc_html__( 'Review Fallback', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The fallback value for Review rich schema.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html('Service'),
						'options'           => null
					],
					RR_OPTION_RICH_ITEM_REVIEWED_FALLBACK_CASE           => [
						'title'             => esc_html__( 'Fallback Use-case', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('When to use the Fallback value.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_select_callback',
						'show_in_rest'      => false,
						'default'           => esc_html('both_missing'),
						'options'           => [
							'always' => 'Always, regardless of category',
							'category_missing' => 'When no category is specified',
							'both_missing' => 'When no category is specified, and parent page has no title'
						]
					],
					RR_OPTION_RICH_AUTHOR_FALLBACK => [
						'title'             => esc_html__( 'Author Fallback', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The fallback value for Author rich schema','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html('Anonymous'),
						'options'           => null
					],
					RR_OPTION_RICH_INCLUDE_URL      => [
						'title'             => esc_html__( 'Include URL', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Include URL rich data - This will add a block of markup to the reviews output to communicate a URL value for rich schema','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => false,
						'options'           => null
					],
					RR_OPTION_RICH_URL_VALUE   => [
						'title'             => esc_html__( 'URL', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('The URL to use in your Rich Markup (without the "http://")','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html(''),
						'options'           => null
					],
					RR_OPTION_RICH_MARKUP_CONTENT_TYPE_VALUE   => [
						'title'             => esc_html__( 'Markup Content Type', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Content type to use snippet review','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_select_callback',
						'show_in_rest'      => false,
						'default'           => esc_html($organization),
						'options'           => [
							'Organization' => $organization,
							'Local business' => 'Local business',
							'Product' => 'Product'
						]
					],
					RR_OPTION_RICH_MARKUP_STREET_ADDRESS   => [
						'title'             => esc_html__( 'Street Address', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Street Address'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html(''),
						'options'           => null
					],RR_OPTION_RICH_MARKUP_LOCALITY_ADDRESS   => [
						'title'             => esc_html__( 'Locality', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Locality'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html(''),
						'options'           => null
					],
					RR_OPTION_RICH_MARKUP_REGION_ADDRESS   => [
						'title'             => esc_html__( 'Region', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('Region'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_text_callback',
						'show_in_rest'      => false,
						'default'           => esc_html(''),
						'options'           => null
					],
					RR_OPTION_RICH_MARKUP_TELEPHONE   => [
						'title'             => esc_html__( 'Telephone', 'rich-reviews' ),
						'type'              => $number,
						'description'       => esc_html__('Telephone Number','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_number_callback',
						'show_in_rest'      => false,
						'options'           => null
					],
					RR_OPTION_RICH_MARKUP_PRICE_RANGE   => [
						'title'             => esc_html__( 'Price Range', 'rich-reviews' ),
						'type'              => $number,
						'description'       => esc_html__('Price Range','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_range_callback',
						'show_in_rest'      => false,
						'options'           => null
					],
					RR_OPTION_RICH_PRODUCT      => [
						'title'             => esc_html__( 'I Agree', 'rich-reviews' ),
						'type'              => 'string',
						'description'       => esc_html__('This will result in warnings in schema testers. But it also provides individual product schema, which may be beneficial if you have multiple products or services with reviews on your site.','rich-reviews'),
						'sanitize_callback' => null,
						'field_callback'    => 'rr_input_checkbox_callback',
						'show_in_rest'      => false,
						'default'           => false,
						'options'           => null
					]
				];
				break;				
			default:
				return [];
		}

	}
}

if( is_admin() ) {
	if (class_exists('RR_OPTIONS', true)) {
		return new RR_OPTIONS;
	}
}
