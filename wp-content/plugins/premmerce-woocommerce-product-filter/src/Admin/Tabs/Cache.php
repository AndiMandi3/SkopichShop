<?php

namespace Premmerce\Filter\Admin\Tabs;

use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\Filter\Seo\Sitemap\GeneralSitemap ;
use  Premmerce\Filter\Admin\Tabs\Base\TabInterface ;
use  Premmerce\Filter\Filter\Items\Types\OnSaleFilter ;
class Cache implements  TabInterface 
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    public function __construct( FileManager $fileManager )
    {
        $this->fileManager = $fileManager;
    }
    
    public function init()
    {
        add_action( 'admin_post_premmerce_filter_cache_clear', function () {
            ( new \Premmerce\Filter\Cache\Cache() )->clear();
            self::clearOtherwisePluginsCache();
            wp_redirect( $_SERVER['HTTP_REFERER'] );
            die;
        } );
    }
    
    /**
     * Clear sitemap cache in Rank Math Seo plugin
     */
    public static function clearRankMathCache()
    {
        if ( defined( 'RANK_MATH_VERSION' ) ) {
            \RankMath\Sitemap\Cache::invalidate_storage();
        }
    }
    
    /**
     * Clear cache in otherwise plugins
     */
    public static function clearOtherwisePluginsCache()
    {
        //clear sitemap cache in Rank Math Seo plugin
        self::clearRankMathCache();
    }
    
    public function render()
    {
        $this->fileManager->includeTemplate( 'admin/tabs/cache.php' );
    }
    
    public function getLabel()
    {
        return __( 'Cache', 'premmerce-filter' );
    }
    
    public function getName()
    {
        return 'cache';
    }
    
    public function valid()
    {
        return true;
    }

}