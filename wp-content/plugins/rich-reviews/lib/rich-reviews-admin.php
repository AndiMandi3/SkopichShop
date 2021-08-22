<?php

/*
 * This contains all the admin stuff so
 * that the main file can be nice and clean
 */

class RichReviewsAdmin {

	var $parent;
	var $db;

	function __construct($parent) {
		$this->parent = $parent;
		$this->db = $this->parent->db;
		add_action('admin_menu', array(&$this, 'init_admin_menu'));
		add_action( 'admin_enqueue_scripts', array(&$this, 'load_admin_scripts_styles'), 100);
		add_filter('plugin_action_links_rich-reviews/rich-reviews.php', array(&$this, 'add_plugin_settings_link'));
	}

	function init_admin_menu() {
		global $wpdb;
		$pendingReviewsCount = $this->db->pending_reviews_count();
		$pendingReviewsText = '';
		$menuTitle = '';
		if ($pendingReviewsCount != 0) {
			$pendingReviewsText = ' (' . $pendingReviewsCount . ')';
		}
		$required_role = $this->parent->rr_admin_options[RR_OPTION_APPROVE_AUTHORITY];
		add_menu_page(
			'Rich Reviews'. __('Settings', 'rich-reviews') ,
			'Rich Reviews' . $pendingReviewsText,
			$required_role,
			'rich_reviews_settings_main',
			array(&$this, 'render_settings_main_page'),
			'dashicons-desktop',
			'25.11'
		);
		add_submenu_page(
			'rich_reviews_settings_main', // ID of menu with which to register this submenu
			'Rich Reviews - '. __('Instructions', 'rich-reviews'), //text to display in browser when selected
			__('Instructions', 'rich-reviews'), // the text for this item
			$required_role, // which type of users can see this menu
			'rich_reviews_settings_main', // unique ID (the slug) for this menu item
			array(&$this, 'render_settings_main_page') // callback function
		);
		add_submenu_page(
			'rich_reviews_settings_main',
			'Rich Reviews - '. __('Pending Reviews', 'rich-reviews'),
			 __('Pending Reviews', 'rich-reviews') . $pendingReviewsText,
			$required_role,
			'fp_admin_pending_reviews_page',
			array(&$this, 'render_pending_reviews_page')
		);
		add_submenu_page(
			'rich_reviews_settings_main',

			'Rich Reviews - ' . __('Approved Reviews', 'rich-reviews'),
			__('Approved Reviews', 'rich-reviews'),
			$required_role,
			'fp_admin_approved_reviews_page',
			array(&$this, 'render_approved_reviews_page')
		);
		add_submenu_page(
			'rich_reviews_settings_main',
			'Rich Reviews - '. __('Options', 'rich-reviews'),
			__('Options', 'rich-reviews'),
			$required_role,
			'fp_admin_options_page',
			array(&$this, 'render_options_page')
		);
		add_submenu_page(
			'rich_reviews_settings_main',
			'Rich Reviews - ' . __('Add/Edit','rich-reviews'),
			__('Add New Review', 'rich-reviews'),
			$required_role,
			'fp_admin_add_edit',
			array(&$this, 'render_add_edit_page')
		);
	}

	function load_admin_scripts_styles() {
        wp_register_script('rich-reviews', trailingslashit($this->parent->plugin_url) . 'js/rich-reviews.js', array('jquery'));
		wp_enqueue_script('rich-reviews');
        wp_register_script('rich-reviews-dashboard', trailingslashit($this->parent->plugin_url) . 'views/admin/view-helper/js/nm-dashboard-script.js', array('jquery'));
		wp_enqueue_script('rich-reviews-dashboard');
		wp_register_style('rich-reviews', trailingslashit($this->parent->plugin_url) . 'css/rich-reviews.css');
		wp_enqueue_style('rich-reviews');
		$translation_array = array(
            'read_more' => __( 'Read More', 'rich-reviews' ),
            'less' => __( 'Less', 'rich-reviews' ));
        wp_localize_script( 'rich-reviews', 'translation', $translation_array );
	}

    function wrap_admin_page($page = null) {
	    echo '<div class="nm-admin-page wrap rr-admin-header"><img src="' . $this->parent->logo_small_url . '"> <h2>Rich Reviews</h2></div>';
        NMRichReviewsAdminHelper::render_tabs();
        NMRichReviewsAdminHelper::render_container_open('content-container');
        if ($page == 'main') {
            NMRichReviewsAdminHelper::render_postbox_open('Instructions');
            echo $this->render_settings_main_page(TRUE);
            NMRichReviewsAdminHelper::render_postbox_close();
            $this->render_shortcode_usage();
        }
        if ($page == 'pending') {
            NMRichReviewsAdminHelper::render_postbox_open('Pending Reviews');
            echo $this->render_pending_reviews_page(TRUE);
            NMRichReviewsAdminHelper::render_postbox_close();
        }
        if ($page == 'approved') {
            NMRichReviewsAdminHelper::render_postbox_open('Approved Reviews');
            echo $this->render_approved_reviews_page(TRUE);
            NMRichReviewsAdminHelper::render_postbox_close();
        }
		if ($page == 'options') {
			NMRichReviewsAdminHelper::render_postbox_open('Options');
			echo $this->render_options_page(TRUE);
			NMRichReviewsAdminHelper::render_postbox_close();
		}
		if ($page == 'add/edit') {
			NMRichReviewsAdminHelper::render_postbox_open('Add/Edit');
			echo $this->render_add_edit_page(TRUE);
			NMRichReviewsAdminHelper::render_postbox_close();
		}
        NMRichReviewsAdminHelper::render_container_close();
        NMRichReviewsAdminHelper::render_container_open('sidebar-container');
        NMRichReviewsAdminHelper::render_sidebar();
        NMRichReviewsAdminHelper::render_container_close();
        echo '<div class="clear"></div>';
    }

	function add_plugin_settings_link($links) {
		$settings_link = '<a href="admin.php?page=rich_reviews_settings_main">' . __('Settings', 'rich-reviews') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	function render_settings_main_page($wrapped = false) {
        if (!$wrapped) {
            $this->wrap_admin_page('main');
            return;
        }

        ob_start();
        	include RR_PLUGIN_PATH . 'views/admin/dashboard/instructions.php';
        return ob_get_clean();

	}

    function render_shortcode_usage() {
        NMRichReviewsAdminHelper::render_postbox_open('[RICH_REVIEWS_SHOW]');
        $this->render_rr_show_content();
        NMRichReviewsAdminHelper::render_postbox_close();

        NMRichReviewsAdminHelper::render_postbox_open('[RICH_REVIEWS_FORM]');
        $this->render_rr_form_content();
        NMRichReviewsAdminHelper::render_postbox_close();

        NMRichReviewsAdminHelper::render_postbox_open('[RICH_REVIEWS_SNIPPET]');
        $this->render_rr_snippet_content();
        NMRichReviewsAdminHelper::render_postbox_close();
    }

    function render_rr_show_content() {
    		include RR_PLUGIN_PATH . 'views/admin/dashboard/rr_show.php';
    }

    function render_rr_form_content() {
    		include RR_PLUGIN_PATH . 'views/admin/dashboard/rr_form.php';
    }

    function render_rr_snippet_content() {
      		include RR_PLUGIN_PATH . 'views/admin/dashboard/rr_snippet.php';
    }

	function render_pending_reviews_page($wrapped = null) {
        if (!$wrapped) {
            $this->wrap_admin_page('pending');
            return;
        }
		require_once('rich-reviews-admin-tables.php');
		$rich_review_admin_table = new Rich_Reviews_Table();
		$rich_review_admin_table->prepare_items('pending');
		echo '<form id="form" method="POST">';
		$rich_review_admin_table->display();
		echo '</form>';
	}

	function render_approved_reviews_page($wrapped) {
        if (!$wrapped) {
            $this->wrap_admin_page('approved');
            return;
        }
		require_once('rich-reviews-admin-tables.php');
		$rich_review_admin_table = new Rich_Reviews_Table();
		$rich_review_admin_table->prepare_items('approved');
		echo '<form id="form" method="POST">';
		$rich_review_admin_table->display();
		echo '</form>';
	}

	function render_options_page()
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Rich Reviews Options', 'rich-reviews'); ?></h1>
            <?php settings_errors(); ?>
            <div id="description"><?php esc_html_e('Options required for the basic operation of Rich Reviews', 'rich-reviews'); ?></div>
            <?php $active_tab = isset($_GET['tab']) ? esc_html($_GET['tab']) : 'admin_options'; ?>
            <h2 class="nav-tab-wrapper">
                <a href="admin.php?page=fp_admin_options_page&tab=admin_options"
                   class="nav-tab <?php echo $active_tab == 'admin_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Admin', 'rich-reviews'); ?></a>
                <a href="admin.php?page=fp_admin_options_page&tab=form_options"
                   class="nav-tab <?php echo $active_tab == 'form_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Form', 'rich-reviews'); ?></a>
                <a href="admin.php?page=fp_admin_options_page&tab=display_options"
                   class="nav-tab <?php echo $active_tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Display', 'rich-reviews'); ?></a>
                <a href="admin.php?page=fp_admin_options_page&tab=user_options"
                   class="nav-tab <?php echo $active_tab == 'user_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('User', 'rich-reviews'); ?></a>
                <a href="admin.php?page=fp_admin_options_page&tab=markup_options"
                   class="nav-tab <?php echo $active_tab == 'markup_options' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Markup', 'rich-reviews'); ?></a>
            </h2>
            <form method="POST" action="options.php">
                <?php
                // This prints out all hidden setting fields
                if ($active_tab == 'admin_options') {
                    settings_fields(RR_OPTIONS_ADMIN);
                    do_settings_sections(RR_OPTIONS_ADMIN);
                } elseif ($active_tab == 'form_options') {
                    settings_fields(RR_OPTIONS_FORM);
                    do_settings_sections(RR_OPTIONS_FORM);
                } elseif ($active_tab == 'display_options') {
                    settings_fields(RR_OPTIONS_DISPLAY);
                    do_settings_sections(RR_OPTIONS_DISPLAY);
                } elseif ($active_tab == 'user_options') {
                    settings_fields(RR_OPTIONS_USER);
                    do_settings_sections(RR_OPTIONS_USER);
                } elseif ($active_tab == 'markup_options') {
                    settings_fields(RR_OPTIONS_MARKUP);
                    do_settings_sections(RR_OPTIONS_MARKUP);
                } else {
                    settings_fields(RR_OPTIONS_ADMIN);
                    do_settings_sections(RR_OPTIONS_ADMIN);
                } // end if/else
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

	function render_add_edit_page($wrapped) {
		if (!$wrapped) {
			$this->wrap_admin_page('add/edit');
			return;
		}
		$view = new RRAdminAddEdit($this->parent);
	}

}
