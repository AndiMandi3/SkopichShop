<?php
if (! defined('ABSPATH')) {
    exit;   //Exit if accessed directly
}

add_action( 'wp_enqueue_scripts', 'fstore_scripts' );
function fstore_scripts() {
    wp_enqueue_style( 'fstore-style', get_stylesheet_uri(), array( 'bootstrap-style' ), _S_VERSION );
    wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), null, 'all' );
    wp_enqueue_style( 'fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), null, 'all' );
    wp_enqueue_style( 'owl-min', get_template_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css', array(), null, 'all' );
    wp_enqueue_style( 'owl-default', get_template_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css', array(), null, 'all' );
}

add_action( 'wp_enqueue_scripts', 'fstore_scripts' );

function fstore_styles() {

    wp_enqueue_script( 'fstore-navigation', get_template_directory_uri() . 'assets/js/navigation.js', array(), _S_VERSION, true);
    wp_enqueue_script( 'fstore-skip-link-focus-fix', get_template_directory_uri() . 'assets/js/skip-link-focus-fix.js', array(), _S_VERSION, true);
    wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'owl-script', get_template_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), null); 
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}