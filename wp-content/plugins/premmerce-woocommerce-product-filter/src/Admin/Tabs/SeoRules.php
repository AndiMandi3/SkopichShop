<?php

namespace Premmerce\Filter\Admin\Tabs;

use  Premmerce\Filter\Seo\SeoModel ;
use  Premmerce\Filter\Seo\RulesTable ;
use  Premmerce\Filter\Seo\WPMLHelper ;
use  Premmerce\Filter\Admin\Tabs\Cache ;
use  Premmerce\Filter\Seo\RulesGenerator ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\SDK\V2\Notifications\AdminNotifier ;
use  Premmerce\Filter\Admin\Tabs\Base\BaseSettings ;
use  Premmerce\Filter\Admin\Tabs\Base\TabInterface ;
class SeoRules implements  TabInterface 
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * @var SeoModel
     */
    private  $model ;
    /**
     * @var AdminNotifier
     */
    private  $notifier ;
    /**
     * @var RulesGenerator
     */
    private  $generator ;
    const  KEY_UPDATE_PATHS = 'premmerce_filter_update_paths' ;
    /**
     * SeoRules constructor.
     *
     * @param FileManager $fileManager
     * @param AdminNotifier $notifier
     */
    public function __construct( FileManager $fileManager, AdminNotifier $notifier )
    {
        $this->fileManager = $fileManager;
        $this->model = new SeoModel();
        $this->notifier = $notifier;
    }
    
    /**
     * Register hooks
     */
    public function init()
    {
        add_action( 'wp_ajax_get_taxonomy_terms', [ $this, 'getTaxonomyTerms' ] );
    }
    
    /**
     * Ajax get terms
     */
    public function getTaxonomyTerms()
    {
        $terms = get_terms( [
            'taxonomy'   => $_POST['taxonomy'],
            'hide_empty' => false,
        ] );
        if ( $terms instanceof \WP_Error ) {
            $terms = [];
        }
        $output = [
            'results' => [],
        ];
        foreach ( $terms as $term ) {
            list( $id, $text, $slug ) = array_values( (array) $term );
            $output['results'][] = [
                'id'       => $id,
                'text'     => $text,
                'slug'     => $slug,
                'taxonomy' => $term->taxonomy,
            ];
        }
        echo  json_encode( $output ) ;
        wp_die();
    }
    
    /**
     * Render tab content
     */
    public function render()
    {
        $action = $_REQUEST['action'] ?? null;
        switch ( $action ) {
            case 'edit':
                $this->renderEdit__premium_only();
                break;
            case 'generate_rules':
                $this->renderGenerate__premium_only();
                break;
            case 'update_paths':
                $this->startUpdatePathsProgress__premium_only();
                break;
            case 'generation_progress':
                $this->startGenerationProgress__premium_only();
                break;
            default:
                $this->renderList();
                break;
        }
    }
    
    /**
     * Render rules list
     */
    public function renderList()
    {
        $categoriesDropDownArgs = $this->getCategoryDropdownArgs();
        $attributes = $this->getAttributes();
        $table = new RulesTable( $this->fileManager, $this->model );
        $rule = [
            'id'                => '',
            'term_id'           => '',
            'path'              => '',
            'h1'                => '',
            'title'             => '',
            'meta_description'  => '',
            'description'       => '',
            'enabled'           => 1,
            'discourage_search' => 0,
            'data'              => null,
        ];
        $this->fileManager->includeTemplate( 'admin/tabs/seo.php', [
            'categoriesDropDownArgs' => $categoriesDropDownArgs,
            'attributes'             => $attributes,
            'rulesTable'             => $table,
            'rule'                   => $rule,
            'fm'                     => $this->fileManager,
        ] );
    }
    
    /**
     * Tab label
     *
     * @return string
     */
    public function getLabel()
    {
        $text = __( 'SEO Rules', 'premmerce-filter' );
        $seoLabel = BaseSettings::premiumForTabLabel( $text );
        return $seoLabel;
    }
    
    /**
     * Tab name
     *
     * @return string
     */
    public function getName()
    {
        return 'seo';
    }
    
    /**
     * Is tab valid
     *
     * @return bool
     */
    public function valid()
    {
        return true;
    }
    
    /**
     * Arguments for category select
     * @return array
     */
    private function getCategoryDropdownArgs()
    {
        $categoriesDropDownArgs = [
            'hide_empty'       => 0,
            'hide_if_empty'    => false,
            'taxonomy'         => 'product_cat',
            'name'             => 'term_id',
            'orderby'          => 'name',
            'hierarchical'     => true,
            'show_option_none' => false,
            'echo'             => 0,
        ];
        $categoriesDropDownArgs = apply_filters(
            'taxonomy_parent_dropdown_args',
            $categoriesDropDownArgs,
            'product_cat',
            'new'
        );
        return $categoriesDropDownArgs;
    }
    
    /**
     * Get attributes for term selects
     *
     * @return array
     */
    private function getAttributes()
    {
        $wcAttributes = wc_get_attribute_taxonomies();
        $attributes = [];
        foreach ( $wcAttributes as $attribute ) {
            $attributes['pa_' . $attribute->attribute_name] = $attribute->attribute_label;
        }
        
        if ( taxonomy_exists( 'product_brand' ) ) {
            $brandTaxonomy = get_taxonomy( 'product_brand' );
            $attributes[$brandTaxonomy->name] = $brandTaxonomy->label;
        }
        
        return $attributes;
    }
    
    /**
     * Redirect to previous page
     */
    private function redirectBack()
    {
        wp_redirect( $_SERVER['HTTP_REFERER'] );
        die;
    }

}