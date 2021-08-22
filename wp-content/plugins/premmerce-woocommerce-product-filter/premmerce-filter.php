<?php

use Premmerce\Filter\FilterPlugin;

/**
 * @package           Premmerce\Filter
 *
 * @wordpress-plugin
 * Plugin Name:       Premmerce Product Filter for WooCommerce
 * Plugin URI:        https://premmerce.com/woocommerce-product-filter/
 * Description:       Premmerce Product Filter for WooCommerce plugin is a convenient and flexible tool for managing filters for WooCommerce products.
 * Version:           3.5.3
 * Author:            premmerce
 * Author URI:        https://premmerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       premmerce-filter
 * Domain Path:       /languages
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 5.3.0
 *
  */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (! function_exists('premmerce_pwpf_fs')) {
    call_user_func(function () {
        require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
        require_once plugin_dir_path(__FILE__) . '/freemius.php';

        $main = new FilterPlugin(__FILE__);

        register_activation_hook(__FILE__, [$main, 'activate']);
        register_deactivation_hook(__FILE__, [$main, 'deactivate']);

        premmerce_pwpf_fs()->add_action('after_uninstall', [FilterPlugin::class, 'uninstall']);

        $main->run();
    });
}
