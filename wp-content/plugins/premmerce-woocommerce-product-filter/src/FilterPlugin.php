<?php namespace Premmerce\Filter;

use Premmerce\Filter\Admin\Admin;
use Premmerce\Filter\Cache\Cache;
use Premmerce\Filter\Filter\Container;
use Premmerce\Filter\Filter\Filter;
use Premmerce\Filter\Frontend\Frontend;
use Premmerce\Filter\Updates\Updater;
use Premmerce\SDK\V2\FileManager\FileManager;
use Premmerce\SDK\V2\Notifications\AdminNotifier;
use Premmerce\SDK\V2\Plugin\PluginInterface;

/**
 * Class FilterPlugin
 *
 * @package Premmerce\Filter
 */
class FilterPlugin implements PluginInterface
{
    const VERSION = '3.5.3';

    const DOMAIN = 'premmerce-filter';

    const OPTION_ = 'premmerce_filter_';

    const OPTION_ATTRIBUTES = 'premmerce_filter_attributes';

    const OPTION_COLORS = 'premmerce_filter_colors';

    const OPTION_IMAGES = 'premmerce_filter_images';

    const OPTION_SETTINGS = 'premmerce_filter_settings';

    const OPTION_PERMALINKS_SETTINGS = 'premmerce_filter_permalink_settings';

    const DEFAULT_TAXONOMIES = ['product_cat', 'product_tag', 'product_brand'];

    const PLAN_FREE = 'free';

    const PLAN_PREMIUM = 'premium';

    const TYPE_COLOR = 'color';

    const TYPE_IMAGE = 'image';

    const TYPE_SLIDER = 'slider';

    const TYPE_LABEL  = 'label';

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AdminNotifier
     */
    private $notifier;

    /**
     * PluginManager constructor.
     *
     * @param string $mainFile
     */
    public function __construct($mainFile)
    {
        $this->fileManager = new FileManager($mainFile, 'premmerce-woocommerce-product-filter');
        $this->notifier = new AdminNotifier();

        Container::getInstance()->addService('file_manager', $this->fileManager);

        $this->registerHooks();
    }

    /**
     * Run plugin part
     */
    public function run()
    {
        $valid = count($this->validateRequiredPlugins()) === 0;

        if ($valid) {
            (new Updater($this->fileManager))->update();
            $filter = new Filter(Container::getInstance());

            do_action('premmerce_filter_core_loaded', $filter);

            if (is_admin()) {
                new Admin($this->fileManager);
            } else {
                new Frontend(Container::getInstance());
            }
        }
    }

    public function registerHooks()
    {
        add_action('plugins_loaded', [$this, 'loadTextDomain']);
        add_action('admin_init', [$this, 'checkRequirePlugins']);
        add_action('comment_post', [$this, 'clearRatingCountTransients']);
        premmerce_pwpf_fs()->add_filter('freemius_pricing_js_path', [$this, 'cutomFreemiusPricingPage']);
    }

    /**
     * Add custom Freemius Pricing Page js file.
     */
    public function cutomFreemiusPricingPage($default_pricing_js_path)
    {
        $pluginDir = $this->fileManager->getPluginDirectory();
        $pricing_js_path = $pluginDir . '/assets/admin/js/pricing-page/freemius-pricing.js';

        return $pricing_js_path;
    }

    /**
     * Fired when the plugin is activated
     */
    public function activate()
    {
        flush_rewrite_rules();

        if (!get_option(self::OPTION_SETTINGS)) {
            $defaultOptions = [
                'show_price_filter' => 'on',
                'hide_empty'        => 'on',
                'product_cat'       => 'on',
                'tag'               => 'on',
                'product_brand'     => 'on',
                'search'            => 'on',
                'shop'              => 'on',
                'attribute'         => 'on',
            ];
            add_option(self::OPTION_SETTINGS, $defaultOptions);
        }

        (new Updater($this->fileManager))->installDb();
    }

    /**
     * Fired when the plugin is deactivated
     */
    public function deactivate()
    {
        $cache = new Cache();
        $cache->clear();
        rmdir($cache->getCacheDir());
    }

    /**
     * Fired during plugin uninstall
     */
    public static function uninstall()
    {
        delete_option(self::OPTION_ATTRIBUTES);
        delete_option(self::OPTION_COLORS);
        delete_option(self::OPTION_IMAGES);
        delete_option(self::OPTION_);
        delete_option(self::OPTION_SETTINGS);
        delete_option(self::OPTION_PERMALINKS_SETTINGS);
        delete_option(Updater::DB_OPTION);
    }

    /**
     * Check required plugins and push notifications
     */
    public function checkRequirePlugins()
    {
        $message = __('The %s plugin requires %s plugin to be active!', 'premmerce-filter');

        $plugins = $this->validateRequiredPlugins();

        if (count($plugins)) {
            foreach ($plugins as $plugin) {
                $error = sprintf($message, 'Premmerce Product Filter for WooCommerce', $plugin);
                $this->notifier->push($error, AdminNotifier::ERROR, false);
            }
        }
    }

    /**
     * Validate required plugins
     *
     * @return array
     */
    private function validateRequiredPlugins()
    {
        $plugins = [];

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        /**
         * Check if WooCommerce is active
         **/
        if (!(is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php'))) {
            $plugins[] = '<a target="_blank" href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a>';
        }

        return $plugins;
    }

    /**
     * Load plugin translations
     */
    public function loadTextDomain()
    {
        $name = $this->fileManager->getPluginName();
        load_plugin_textdomain('premmerce-filter', false, $name . '/languages/');
    }

    public static function clearRatingCountTransients()
    {
        for ($rating = 5; $rating >= 1; $rating--) {
            delete_transient("premmerce_filtered_product_count_{$rating}");
        }
    }
}
