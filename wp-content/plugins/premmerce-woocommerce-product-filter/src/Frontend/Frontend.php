<?php

namespace Premmerce\Filter\Frontend;

use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\Filter\Filter\Container ;
use  Premmerce\Filter\Widget\FilterWidget ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\Filter\Ajax\Strategy\WidgetsStrategy ;
use  Premmerce\Filter\Integration\OceanWpIntegration ;
use  Premmerce\Filter\Ajax\Strategy\ThemeStrategyInterface ;
class Frontend
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var Container
     */
    private  $container ;
    /**
     * Frontend constructor.
     *
     * @param Container $container
     */
    public function __construct( Container $container )
    {
        $this->fileManager = $container->getFileManager();
        $this->container = $container;
        $settings = $this->container->getOption( 'settings' );
        add_action( 'init', [ $this, 'checkIntegration' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'registerAssets' ] );
        if ( !empty($settings['use_ajax']) || !empty($settings['load_deferred']) || !empty($settings['show_filter_button']) ) {
            add_action( 'template_redirect', [ $this, 'filterResponse' ] );
        }
    }
    
    static function getWidgetInstanceByIdAttr( $widgetIdAttr )
    {
        $widgetId = preg_replace( '/\\D/', '', $widgetIdAttr );
        $allWidgetsInstances = get_option( 'widget_' . FilterWidget::FILTER_WIDGET_ID );
        $instance = $allWidgetsInstances[$widgetId];
        return $instance;
    }
    
    public function filterResponse()
    {
        
        if ( apply_filters( 'premmerce_product_filter_active', false ) ) {
            /**
             * Strategy can add own hooks, so it should be instantiated independently
             */
            $this->loadStrategy();
            
            if ( !empty($_REQUEST['premmerce_filter_ajax_action']) ) {
                $action = $_REQUEST['premmerce_filter_ajax_action'];
                $instance = self::getInstanceByRequest();
                switch ( $action ) {
                    case 'reload':
                        $response = apply_filters( 'premmerce_filter_ajax_response_reload', [], $instance );
                        wp_send_json( $response );
                        break;
                    case 'filterButton':
                    case 'deferred':
                        $response = apply_filters( 'premmerce_filter_ajax_response_deferred', ( new WidgetsStrategy() )->updateResponse( [], $instance ) );
                        wp_send_json( $response );
                        break;
                }
            }
        
        }
    
    }
    
    /**
     * Get instance by Requset.
     * If it is widget - take from Widget, if shortcode - add instance['style'].
     */
    public static function getInstanceByRequest()
    {
        $instance = [];
        
        if ( !empty($_REQUEST['widget_id'] && $_REQUEST['widget_type'] != 'shortcode') ) {
            $instance = self::getWidgetInstanceByIdAttr( $_REQUEST['widget_id'] );
        } elseif ( $_REQUEST['widget_type'] == 'shortcode' ) {
            $instance['style'] = $_REQUEST['widget_style'] ?? 'default';
        }
        
        return $instance;
    }
    
    /**
     * Instantiate current ajax strategy
     */
    public function loadStrategy()
    {
        $strategy = apply_filters( 'premmerce_filter_ajax_current_strategy', null );
        if ( is_string( $strategy ) && class_exists( $strategy ) ) {
            $strategy = new $strategy();
        }
        if ( $strategy instanceof ThemeStrategyInterface ) {
            add_filter(
                'premmerce_filter_ajax_response_reload',
                [ $strategy, 'updateResponse' ],
                10,
                2
            );
        }
    }
    
    /**
     * Register assets
     */
    public function registerAssets()
    {
        
        if ( apply_filters( 'premmerce_product_filter_active', false ) ) {
            $settings = $this->container->getOption( 'settings' );
            wp_enqueue_script(
                'premmerce_filter_script',
                $this->fileManager->locateAsset( 'front/js/script.js' ),
                [ 'jquery', 'jquery-ui-slider' ],
                FilterPlugin::VERSION,
                true
            );
            wp_enqueue_style(
                'premmerce_filter_style',
                $this->fileManager->locateAsset( 'front/css/style.css' ),
                [],
                FilterPlugin::VERSION
            );
            //add custom css from Settings tab
            if ( !empty($settings['custom_style_css']) ) {
                wp_add_inline_style( 'premmerce_filter_style', $settings['custom_style_css'] );
            }
            $localizeOptions = [];
            $localizeOptions['useAjax'] = !empty($settings['use_ajax']);
            $localizeOptions['loadDeferred'] = !empty($settings['load_deferred']);
            $localizeOptions['showFilterButton'] = !empty($settings['show_filter_button']);
            $localizeOptions['currentUrl'] = home_url( $GLOBALS['wp']->request );
            wp_localize_script( 'premmerce_filter_script', FilterPlugin::OPTION_SETTINGS, $localizeOptions );
        }
    
    }
    
    public function checkIntegration()
    {
        $theme = wp_get_theme();
        if ( 'oceanwp' === $theme->get_template() ) {
            new OceanWpIntegration( $this->fileManager );
        }
    }

}