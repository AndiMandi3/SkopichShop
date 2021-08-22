<?php namespace Premmerce\Filter\Ajax\Strategy;

use Premmerce\Filter\Frontend\Frontend;


class WoocommerceStrategy extends WidgetsStrategy
{

    public function __construct()
    {

        add_action('woocommerce_before_shop_loop', [$this, 'openContainer'], 0);

        add_action('woocommerce_after_shop_loop', [$this, 'closeContainer'], 999);

        add_action('woocommerce_no_products_found', [$this, 'openContainer'], 0);

        add_action('woocommerce_no_products_found', [$this, 'closeContainer'], 999);
    }

    public function updateResponse(array $response, array $instance = [])
    {
        $instance = Frontend::getInstanceByRequest();

        return parent::updateResponse($this->loadContent($response), $instance);
    }


    /**
     * @param $response
     *
     * @return array
     */
    public function loadContent($response)
    {
        add_filter('woocommerce_show_page_title', '__return_false');
        remove_all_actions('woocommerce_archive_description');

        ob_start();
        echo '<div>';
        woocommerce_content();
        echo '</div>';
        $html = ob_get_clean();

        $response[] = [
            'selector' => '.premmerce-filter-ajax-container',
            'callback' => 'replacePart',
            'html'     => $html
        ];

        return $response;
    }


    public function openContainer()
    {
        echo '<div class="premmerce-filter-ajax-container">';
    }

    public function closeContainer()
    {

        echo '</div>';
    }

}
