<?php
add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	load_template(get_template_directory() . '/includes/carbon-fields/vendor/autoload.php');
	\Carbon_Fields\Carbon_Fields::boot();
}

add_action( 'carbon_fields_register_fields', 'fstore_register_custom_fields' );
function fstore_register_custom_fields() {
	require get_template_directory() . '/includes/custom-fields-option/metabox.php';
	require get_template_directory() . '/includes/custom-fields-option/theme-options.php';
}
/* 
подключение настроек темы
*/
require get_template_directory() . '/includes/theme-settings.php';
/* 
подключение области виджетов
*/
require get_template_directory() . '/includes/widget-areas.php';
/* 
подключение скриптов и стилей
*/
require get_template_directory() . '/includes/enqueue-script-style.php';
/* 
подключение вспомогательных функицй
*/
require get_template_directory() . '/includes/helpers.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/includes/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/includes/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/includes/woocommerce.php';
	require get_template_directory() . '/woocommerce/includes/wc-functions.php';
	require get_template_directory() . '/woocommerce/includes/wc-functions.php';
}
