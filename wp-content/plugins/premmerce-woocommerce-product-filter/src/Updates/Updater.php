<?php

namespace Premmerce\Filter\Updates;

use  Premmerce\Filter\Ajax\Strategy\WoocommerceStrategy ;
use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\Filter\Seo\SeoModel ;
use  Premmerce\Filter\Seo\SeoTermModel ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
class Updater
{
    const  DB_OPTION = 'premmerce_filter_db_version' ;
    /**
     * @var FileManager
     */
    private  $fileManager ;
    public function __construct( FileManager $fileManager )
    {
        $this->fileManager = $fileManager;
    }
    
    public function checkForUpdates()
    {
        return $this->compare( FilterPlugin::VERSION );
    }
    
    private function compare( $version )
    {
        $dbVersion = get_option( self::DB_OPTION, '1.1' );
        return version_compare( $dbVersion, $version, '<' );
    }
    
    public function update()
    {
        
        if ( $this->checkForUpdates() ) {
            $this->installDb();
            foreach ( $this->getUpdates() as $version => $callback ) {
                if ( $this->compare( $version ) ) {
                    call_user_func( $callback );
                }
            }
            update_option( self::DB_OPTION, FilterPlugin::VERSION );
        }
    
    }
    
    public function installDb()
    {
    }
    
    public function getUpdates()
    {
        return [
            '2.0' => [ $this, 'update2_0' ],
            '3.1' => [ $this, 'update3_1' ],
            '3.4' => [ $this, 'update3_4' ],
        ];
    }
    
    public function update2_0()
    {
        update_option( self::DB_OPTION, '2.0' );
    }
    
    public function update3_1()
    {
        $settings = get_option( FilterPlugin::OPTION_SETTINGS );
        $settings['taxonomies'] = FilterPlugin::DEFAULT_TAXONOMIES;
        $settings['style'] = 'premmerce';
        $settings['ajax_strategy'] = 'woocommerce_content';
        update_option( FilterPlugin::OPTION_SETTINGS, $settings );
        update_option( self::DB_OPTION, FilterPlugin::VERSION );
    }
    
    public function update3_4()
    {
        update_option( self::DB_OPTION, FilterPlugin::VERSION );
    }

}