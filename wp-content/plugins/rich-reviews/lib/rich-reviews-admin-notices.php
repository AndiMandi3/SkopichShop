<?php

function rr_admin_notice_new_owners() {
    $user_id = get_current_user_id();
    if(!get_user_meta($user_id,'rr_new_owner_alert')) {
        $notice = '<div class="notice notice-info rr-admin-notice">';
        $notice .= '<div class="rr-admin-notice-icon">';
        $notice .= '<img src=" '. RR_PLUGIN_URL . '/assets/icon-128x128.png" alt="">';
        $notice .= '</div>';
        $notice .= '<div class="rr-admin-notice-content">';
        $notice .= '<button onclick="location.href=\''.RR_HOME_URL.'/wp-admin/?rr-admin-notice-dismiss=new_owner\'" class="rr-admin-notice-dismiss-button button button-primary">Dismiss</button>';
        $notice .= '<h2>' . __( 'Hello, our team at Starfish recently adopted this plugin and made it secure!', 'rich-reviews' ) . '</h2>';
        $notice .= __( "To learn more about the security of Rich Reviews, read our <a href='https://starfish.reviews/rich-reviews-plugin-is-now-secure-part-of-the-starfish-family/?utm_source=rich_reviews_plugin&utm_medium=wp_admin&utm_campaign=welcome_notification' target='_blank'>blog post about it</a>. We want to welcome you to the Starfish users community and would love it if you'd join our Facebook group. Or check out our other plugin: Starfish Reviews.", "rich-reviews" );
        $notice .= '<p>';
        $notice .= '<button onclick="window.open(\'https://www.facebook.com/groups/wpreviews/\')" class="rr-admin-notice-button button button-primary">Facebook</button>';
        $notice .= '<button onclick="window.open(\'https://starfish.reviews/?utm_source=rich_reviews_plugin&utm_medium=wp_admin&utm_campaign=welcome_notification\')" class="rr-admin-notice-button button button-secondary">Starfish Reviews</button>';
        $notice .= '<a href="https://wordpress.org/support/plugin/rich-reviews/#new-post" target="_blank">Report a Problem with Rich Reviews</a>';
        $notice .= '</p>';
        $notice .= '</div>';
        $notice .= '</div>';
        print $notice;
    }
}

add_action( 'admin_notices', 'rr_admin_notice_new_owners' );

function rr_admin_notice_dismissals() {

    $user_id = get_current_user_id();
    if( isset($_GET['rr-admin-notice-dismiss']) && esc_html($_GET['rr-admin-notice-dismiss']) == 'new_owner' && current_user_can('manage_options') ) {
        add_user_meta($user_id, 'rr_new_owner_alert', false);
    }

}

add_action( 'admin_init', 'rr_admin_notice_dismissals' );