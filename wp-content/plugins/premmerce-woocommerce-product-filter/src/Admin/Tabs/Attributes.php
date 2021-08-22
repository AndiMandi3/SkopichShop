<?php

namespace Premmerce\Filter\Admin\Tabs;

use  Premmerce\Filter\Admin\Tabs\Base\SortableListTab ;
use  Premmerce\Filter\Filter\Filter ;
use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
class Attributes extends SortableListTab
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var array
     */
    private  $defaultAttribute = array(
        'active'       => false,
        'type'         => 'checkbox',
        'display_type' => '',
    ) ;
    /**
     * @var array
     */
    private  $premiumAttributes = array( 'show_on_sale', 'show_in_stock', 'show_rating_filter' ) ;
    /**
     * @var array
     */
    private  $premiumTypes = array(
        FilterPlugin::TYPE_COLOR,
        FilterPlugin::TYPE_IMAGE,
        FilterPlugin::TYPE_SLIDER,
        FilterPlugin::TYPE_LABEL
    ) ;
    /**
     * @var array
     */
    private  $premiumDisplay = array( 'display_dropdown_hover' ) ;
    /**
     * Attributes constructor.
     *
     * @param FileManager $fileManager
     */
    public function __construct( FileManager $fileManager )
    {
        parent::__construct();
        $this->fileManager = $fileManager;
        $this->bulkActions = apply_filters( 'premmerce_filter_bulk_actions_attributes', $this->bulkActions );
    }
    
    /**
     * Register action handlers
     */
    public function init()
    {
        add_action( 'wp_ajax_premmerce_filter_bulk_action_attributes', [ $this, 'bulkActionAttributes' ] );
        add_action( 'wp_ajax_premmerce_filter_sort_attributes', [ $this, 'sortAttributes' ] );
        add_filter( 'premmerce_filter_item_types', [ $this, 'extendItemTypes' ] );
        add_filter( 'premmerce_filter_display', [ $this, 'extendItemDisplay' ] );
        add_action( 'pre_update_option_' . FilterPlugin::OPTION_ATTRIBUTES, [ $this, 'checkBeforeSaveOption' ] );
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'attributes';
    }
    
    /**
     * @return string
     */
    public function getLabel()
    {
        return __( 'Attributes', 'premmerce-filter' );
    }
    
    /**
     * @return bool
     */
    public function valid()
    {
        return function_exists( 'wc_get_attribute_taxonomies' );
    }
    
    public function checkBeforeSaveOption( $newValue )
    {
        //check if it isn't premium - change premium type to default (checkbox)
        if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
            foreach ( $newValue as $key => $value ) {
                if ( in_array( $value['type'], $this->premiumTypes ) ) {
                    $newValue[$key]['type'] = 'checkbox';
                }
            }
        }
        return $newValue;
    }
    
    public function swapItems( $swap, $actual )
    {
        $actualKeys = array_keys( $actual );
        $place = $swap[0];
        $target = $swap[1];
        $placePos = array_search( $place, $actualKeys );
        $targetPos = array_search( $target, $actualKeys );
        
        if ( $placePos < $targetPos ) {
            //        if place is before move target before place
            $items = [
                $target => $actual[$target],
                $place  => $actual[$place],
            ];
        } else {
            //        if place is after move target after place
            $items = [
                $place  => $actual[$place],
                $target => $actual[$target],
            ];
        }
        
        unset( $actual[$swap[1]] );
        $before = array_slice(
            $actual,
            0,
            $placePos,
            true
        );
        $after = array_slice(
            $actual,
            $placePos,
            null,
            true
        );
        return $before + $items + $after;
    }
    
    public function sortItems( $ids, $actual )
    {
        $actualKeys = array_keys( $actual );
        $prevKeyPosition = 0;
        $before = [];
        $after = [];
        
        if ( !empty($_POST['prev']) ) {
            $prev = $_POST['prev'];
            $prevKeyPosition = array_search( $prev, $actualKeys );
            $before = array_slice(
                $actual,
                0,
                ( $prevKeyPosition ? $prevKeyPosition + 1 : 0 ),
                true
            );
        }
        
        
        if ( !empty($_POST['next']) ) {
            $next = $_POST['next'];
            $nextKeyPosition = array_search( $next, $actualKeys );
            $after = array_slice(
                $actual,
                $nextKeyPosition,
                null,
                true
            );
        }
        
        $sorted = array_slice(
            $actual,
            ( $prevKeyPosition ? $prevKeyPosition + 1 : 0 ),
            count( $ids ),
            true
        );
        $ids = array_combine( $ids, $ids );
        $sorted = array_replace( $ids, $sorted );
        return $before + $sorted + $after;
    }
    
    /**
     * Ajax update attributes ordering
     */
    public function sortAttributes()
    {
        $actual = $this->getAttributesConfig();
        $items = [];
        
        if ( !empty($_POST['swap']) ) {
            $swap = explode( ',', $_POST['swap'] );
            $swap = array_filter( $swap );
            if ( count( $swap ) === 2 ) {
                $items = $this->swapItems( $swap, $actual );
            }
        } elseif ( !empty($_POST['ids']) ) {
            $ids = $_POST['ids'];
            if ( is_array( $ids ) ) {
                $items = $this->sortItems( $_POST['ids'], $actual );
            }
        }
        
        if ( count( $items ) === count( $actual ) ) {
            update_option( FilterPlugin::OPTION_ATTRIBUTES, $items );
        }
        wp_die();
    }
    
    /**
     * Ajax bulk update attributes
     */
    public function bulkActionAttributes()
    {
        $this->bulkActionsHandler( FilterPlugin::OPTION_ATTRIBUTES, $this->getAttributesConfig() );
    }
    
    public function render()
    {
        $attributesConfig = $this->getAttributesConfig();
        $attributes = array_replace( $attributesConfig, $this->getAttributes() );
        $premiumAttributes = $this->premiumAttributes;
        //pagination data
        $paginationData = $this->paginationDataForTabs( $attributes );
        //new data from paginationArgsForTabs()
        $attributes = $paginationData['attr'];
        $paginationArgs = $paginationData['args'];
        $prevId = $paginationData['prevId'];
        $nextId = $paginationData['nextId'];
        $visibility = [
            "display" => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Display', 'premmerce-filter' ),
        ],
            "hide"    => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Hide', 'premmerce-filter' ),
        ],
        ];
        $types = [
            'checkbox' => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Checkbox', 'premmerce-filter' ),
        ],
            'radio'    => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Radio', 'premmerce-filter' ),
        ],
            'select'   => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Select', 'premmerce-filter' ),
        ],
        ];
        //add premium types from extendItemTypes()
        $types = apply_filters( 'premmerce_filter_item_types', $types );
        $display = [
            'display_'                => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Default', 'premmerce-filter' ),
        ],
            'display_dropdown'        => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Dropdown', 'premmerce-filter' ),
        ],
            'display_scroll'          => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Scroll', 'premmerce-filter' ),
        ],
            'display_scroll_dropdown' => [
            'plan' => FilterPlugin::PLAN_FREE,
            'text' => __( 'Scroll + Dropdown', 'premmerce-filter' ),
        ],
            'display_dropdown_hover'  => [
            'plan' => FilterPlugin::PLAN_PREMIUM,
            'text' => __( 'Hoverable Dropdown', 'premmerce-filter' ),
        ],
        ];
        $display = apply_filters( 'premmerce_filter_display', $display );
        $actions = [
            "-1"                                   => __( 'Bulk Actions', 'premmerce-filter' ),
            __( 'Visibility', 'premmerce-filter' ) => $visibility,
            __( 'Field type', 'premmerce-filter' ) => $types,
            __( 'Display as', 'premmerce-filter' ) => $display,
        ];
        $actions = apply_filters( 'premmerce_filter_item_actions', $actions );
        $dataAction = 'premmerce_filter_bulk_action_attributes';
        $this->fileManager->includeTemplate( 'admin/tabs/attributes.php', compact(
            'attributes',
            'premiumAttributes',
            'attributesConfig',
            'types',
            'actions',
            'dataAction',
            'display',
            'paginationArgs',
            'prevId',
            'nextId'
        ) );
    }
    
    //general function for images and colors
    public static function getTaxonomyData( string $type )
    {
        //if id - is number - take taxonomy slug, else take id (it is already slug)
        $taxonomy = ( is_numeric( $_GET['id'] ) ? self::getTaxonomyById( (int) $_GET['id'] ) : $_GET['id'] );
        
        if ( $taxonomy = get_taxonomy( $taxonomy ) ) {
            $terms = get_terms( [
                'taxonomy'   => $taxonomy->name,
                'hide_empty' => false,
                'fields'     => 'id=>name',
            ] );
            if ( $terms instanceof \WP_Error ) {
                $terms = [];
            }
            $output = [
                'taxonomyName'  => $taxonomy->name,
                'taxonomyLabel' => $taxonomy->label,
                'fieldType'     => $type,
                'results'       => [],
            ];
            //get data from wp_option by type name
            $typeData = get_option( FilterPlugin::OPTION_ . $type, [] );
            //if isset
            $data = $typeData[$taxonomy->name] ?? [];
            foreach ( $terms as $id => $text ) {
                $value = $data[$id] ?? null;
                $term_result = [
                    'id'    => $id,
                    'text'  => $text,
                    'value' => $value,
                ];
                if ( 'images' === $type ) {
                    $term_result['img_url'] = wp_get_attachment_image_url( $value, 'thumbnail' );
                }
                $output['results'][] = $term_result;
            }
            echo  json_encode( $output ) ;
            wp_die();
        }
    
    }
    
    //extend items types
    public function extendItemTypes( $types )
    {
        $premiumText = '';
        //if it is not premium plan - show text Premium (mean only in Premium)
        if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
            $premiumText = ' (' . __( 'Premium', 'premmerce-filter' ) . ')';
        }
        //premium types list
        $premiumTypes = $this->premiumTypes;
        //add to main types array
        foreach ( $premiumTypes as $premType ) {
            $types[$premType] = [
                'plan' => FilterPlugin::PLAN_PREMIUM,
                'text' => __( ucfirst( $premType ), 'premmerce-filter' ) . $premiumText,
            ];
        }
        return $types;
    }
    
    //extend items types
    public function extendItemDisplay( $display )
    {
        $premiumText = '';
        //if it is not premium plan - show text Premium (mean only in Premium)
        if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
            $premiumText = ' (' . __( 'Premium', 'premmerce-filter' ) . ')';
        }
        //premium types list
        $premiumDisplay = $this->premiumDisplay;
        //add to main types array
        foreach ( $premiumDisplay as $premDisplay ) {
            $display[$premDisplay] = [
                'plan' => FilterPlugin::PLAN_PREMIUM,
                'text' => __( ucfirst( $display[$premDisplay]['text'] ), 'premmerce-filter' ) . $premiumText,
            ];
        }
        return $display;
    }
    
    /**
     * @param $id
     *
     * @return mixed
     */
    private static function getTaxonomyById( $id )
    {
        if ( $attribute = wc_get_attribute( $id ) ) {
            return $attribute->slug;
        }
        return $id;
    }
    
    /**
     * Get attributes configuration
     *
     * @return mixed
     */
    private function getAttributesConfig()
    {
        return $this->getConfig( FilterPlugin::OPTION_ATTRIBUTES, $this->getAttributes(), $this->defaultAttribute );
    }
    
    /**
     * Woocommerce attributes id=>title array and custom taxonomies if exist
     *
     * @return array
     */
    private function getAttributes()
    {
        $wcAttributes = wc_get_attribute_taxonomies();
        $settings = get_option( FilterPlugin::OPTION_SETTINGS, [] );
        $attributes = [];
        //get Show in stock or Show on sale attributes from setting
        foreach ( $settings as $key => $setting ) {
            if ( in_array( $key, $this->premiumAttributes, true ) ) {
                $attributes[$key] = str_replace( '_', ' ', $key );
            }
        }
        foreach ( $wcAttributes as $attribute ) {
            $attributes[$attribute->attribute_id] = $attribute->attribute_label;
        }
        foreach ( Filter::$taxonomies as $taxonomy ) {
            
            if ( taxonomy_exists( $taxonomy ) ) {
                $taxonomy = get_taxonomy( $taxonomy );
                $attributes[$taxonomy->name] = $taxonomy->labels->menu_name;
            }
        
        }
        return $attributes;
    }

}