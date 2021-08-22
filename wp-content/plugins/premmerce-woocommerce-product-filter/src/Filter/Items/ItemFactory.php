<?php

namespace Premmerce\Filter\Filter\Items;

use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\Filter\Filter\Query\QueryHelper ;
use  Premmerce\Filter\Filter\Items\Types\OnSaleFilter ;
use  Premmerce\Filter\Filter\Items\Types\RatingFilter ;
use  Premmerce\Filter\Filter\Items\Types\SliderFilter ;
use  Premmerce\Filter\Filter\Items\Types\InStockFilter ;
use  Premmerce\Filter\Filter\Items\Types\TaxonomyFilter ;
use  Premmerce\Filter\Filter\Items\Types\AttributeFilter ;
use  Premmerce\Filter\Filter\Items\Types\FilterInterface ;
class ItemFactory
{
    /**
     * @var array
     */
    private  $colorOptions ;
    /**
     * @var array
     */
    private  $imageOptions ;
    /**
     * @var array
     */
    private  $services = array() ;
    /**
     * @var array
     */
    private  $attributes ;
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getService( $key )
    {
        if ( isset( $this->services[$key] ) ) {
            return $this->services[$key];
        }
    }
    
    /**
     * @param string $key
     * @param mixed $service
     */
    public function addService( $key, $service )
    {
        $this->services[$key] = $service;
    }
    
    /**
     * @return QueryHelper
     */
    public function getQueryHelper()
    {
        if ( !isset( $this->services['query_helper'] ) ) {
            $this->addService( 'query_helper', new QueryHelper() );
        }
        return $this->getService( 'query_helper' );
    }
    
    public function __construct()
    {
        $this->colorOptions = get_option( FilterPlugin::OPTION_COLORS, [] );
        $this->imageOptions = get_option( FilterPlugin::OPTION_IMAGES, [] );
    }
    
    /**
     * @param string $id
     * @param array $config
     *
     * @return null|FilterInterface
     */
    public function createItem( $id, $config )
    {
        $type = $config['type'];
        $taxonomy = null;
        $attribute = null;
        $item = null;
        
        if ( $attribute = $this->getAttribute( $id ) ) {
            $taxonomy = get_taxonomy( wc_attribute_taxonomy_name( $attribute->attribute_name ) );
        } elseif ( taxonomy_exists( $id ) ) {
            $taxonomy = get_taxonomy( $id );
        }
        
        
        if ( $type === FilterPlugin::TYPE_SLIDER && $taxonomy ) {
            $item = new SliderFilter( $config, $taxonomy );
        } elseif ( $attribute && $taxonomy ) {
            $item = new AttributeFilter( $config, $attribute );
        } elseif ( $taxonomy ) {
            $item = new TaxonomyFilter( $config, $taxonomy );
        }
        
        return apply_filters( "filter_item_{$type}", $item, $config );
    }
    
    /**
     * @param $id
     *
     * @return mixed
     */
    private function getAttribute( $id )
    {
        $at = $this->getAttributes();
        if ( array_key_exists( $id, $at ) ) {
            return $at[$id];
        }
    }
    
    /**
     * @return array
     */
    private function getAttributes()
    {
        
        if ( $this->attributes === null ) {
            $this->attributes = [];
            foreach ( wc_get_attribute_taxonomies() as $item ) {
                $this->attributes[$item->attribute_id] = $item;
            }
        }
        
        return $this->attributes;
    }

}