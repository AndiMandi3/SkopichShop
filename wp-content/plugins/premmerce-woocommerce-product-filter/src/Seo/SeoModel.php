<?php

namespace Premmerce\Filter\Seo;

use  WP_Error ;
class SeoModel extends Query
{
    /**
     * @var SeoTermModel
     */
    private  $terms ;
    const  TABLE = 'premmerce_filter_seo' ;
    /**
     * SeoModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = $this->db->prefix . self::TABLE;
        $this->terms = new SeoTermModel();
    }
    
    /**
     * @param int $id
     *
     * @return array|null|object
     */
    public function find( $id )
    {
        $rule = parent::find( $id );
        
        if ( is_array( $rule ) ) {
            $rule['terms'] = $this->getTerms( $id );
            return $rule;
        }
        
        return $rule;
    }
    
    /**
     * @param int $id
     *
     * @return array
     */
    public function getTerms( $id )
    {
        return $this->terms->getTermsTaxonomiesByRule( $id );
    }
    
    //get created_at field by ID
    public static function getSeoRuleTime( $id, $timeType = 'created_at' )
    {
        global  $wpdb ;
        $prefix = $wpdb->get_blog_prefix();
        $query = "SELECT ID, `{$timeType}` FROM {$prefix}premmerce_filter_seo WHERE ID = {$id}";
        $result = $wpdb->get_results( $query );
        $time = $result[0]->{$timeType};
        //if time is 0000-00-00 - add current time.
        //it is for old fields created before.
        if ( $time === '0000-00-00 00:00:00' ) {
            $time = current_time( 'Y-m-d H:i:s' );
        }
        return $time;
    }
    
    /**
     * @param array $data
     * @param bool $validate
     *
     * @return array|int|WP_Error|null
     */
    public function save( array $data, $validate = true )
    {
        
        if ( $validate ) {
            $terms = $data['terms'];
            $id = ( empty($data['id']) ? null : $data['id'] );
            $data = $this->validate( $data );
            if ( $data === null ) {
                return null;
            }
            if ( $data instanceof WP_Error ) {
                return $data;
            }
            //if it is not premium plan - return null
            if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
                return null;
            }
        }
    
    }
    
    /**
     * @param array $data
     *
     * @return int|null
     */
    public function create( array $data )
    {
        $result = $this->db->insert( $this->table, $this->sanitize( $data, true ) );
        
        if ( false !== $result ) {
            $id = $this->db->insert_id;
            do_action( 'premmerce_filter_seo_rule_created', [
                'id' => $result,
            ] );
            return $id;
        }
    
    }
    
    /**
     * @param int $id
     * @param array $data
     *
     * @return int|null
     */
    public function update( $id, array $data )
    {
        $result = $this->db->update( $this->table, $this->sanitize( $data, true ), [
            'id' => $id,
        ] );
        
        if ( false !== $result ) {
            do_action( 'premmerce_filter_seo_rule_updated', [
                'id' => $id,
            ] );
            return $id;
        }
    
    }
    
    /**
     * @param array $ids
     *
     * @return false|int
     */
    public function enable( $ids )
    {
        return $this->updateBulk( $ids, [
            'enabled' => 1,
        ] );
    }
    
    /**
     * @param array $ids
     *
     * @return false|int
     */
    public function disable( $ids )
    {
        return $this->updateBulk( $ids, [
            'enabled' => 0,
        ] );
    }
    
    /**
     * @param $array
     *
     * @return array|WP_Error|null
     */
    private function validate( $array )
    {
        $counter = $this->getCountFromRule( $array );
        if ( $counter === 0 ) {
            return null;
        }
        $path = $this->generatePath( $array );
        $rule = $this->where( [
            'path' => $path,
        ] )->returnType( SeoModel::TYPE_VAR )->get( [ 'id' ] );
        $idIsSame = isset( $array['id'] ) && (int) $array['id'] === (int) $rule;
        if ( $rule && !$idIsSame ) {
            return new WP_Error( 'unique_rule', __( 'The same rule already exists', 'premmerce-filter' ) );
        }
        if ( empty($array['terms']) ) {
            return new WP_Error( 'terms_required', __( 'Terms are required', 'premmerce-filter' ) );
        }
        if ( empty($array['term_id']) ) {
            return new WP_Error( 'category_required', __( 'Category is required', 'premmerce-filter' ) );
        }
        return $array;
    }
    
    /**
     * @param array $rule
     * @return int
     */
    private function getCountFromRule( $rule )
    {
        $taxQuery = [
            'relation' => 'AND',
        ];
        $taxQuery[] = [
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => (int) $rule['term_id'],
        ];
        foreach ( $rule['terms'] as $term => $ids ) {
            $taxQuery[] = [
                'taxonomy' => $term,
                'field'    => 'term_id',
                'terms'    => $ids,
            ];
        }
        $products = new \WP_Query( [
            'post_type'      => [ 'product' ],
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => $taxQuery,
        ] );
        return $products->post_count;
    }
    
    /**
     * Sanitize rule and fill generated fields
     *
     * @param $array
     * @param bool $addDefaults
     *
     * @return array
     */
    private function sanitize( $array, $addDefaults = true )
    {
        $data = [];
        
        if ( isset( $array['terms'] ) && isset( $array['term_id'] ) ) {
            $array['path'] = $this->generatePath( $array );
            $array['label'] = $this->generateLabel( $array );
        }
        
        $defaults = [
            'term_id'           => '',
            'h1'                => '',
            'title'             => '',
            'meta_description'  => '',
            'description'       => '',
            'enabled'           => 0,
            'discourage_search' => 0,
            'data'              => '',
            'label'             => '',
            'path'              => '',
            'created_at'        => '',
            'modified_at'       => '',
        ];
        foreach ( $defaults as $key => $default ) {
            
            if ( array_key_exists( $key, $array ) ) {
                $data[$key] = $this->sanitizeField( $key, $array[$key] );
            } elseif ( $addDefaults ) {
                $data[$key] = $default;
            }
        
        }
        return $data;
    }
    
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool|string
     */
    private function sanitizeField( $key, $value )
    {
        switch ( $key ) {
            case 'enabled':
                return !empty($value);
            case 'discourage_search':
                return !empty($value);
            case 'title':
            case 'meta_description':
                return esc_html( $value );
            case 'description':
                return wp_unslash( $value );
            default:
                return $value;
        }
    }
    
    /**
     * Generate path for rule
     *
     * @param $ruleData
     *
     * @return mixed
     */
    public function generatePath( $ruleData )
    {
        $link = get_term_link( (int) $ruleData['term_id'] );
        $terms = $ruleData['terms'];
        foreach ( $terms as $tax => $termIds ) {
            $filterName = $tax;
            if ( taxonomy_is_product_attribute( $tax ) ) {
                $filterName = substr( $tax, 3 );
            }
            $taxonomyFilter = 'filter_' . $filterName;
            $taxonomyQueryType = 'query_type_' . $filterName;
            $slugs = $this->getTermSlugsByIds( $tax, $termIds );
            $link = add_query_arg( $taxonomyFilter, implode( ',', $slugs ), $link );
            $link = add_query_arg( $taxonomyQueryType, 'or', $link );
        }
        $link = apply_filters( 'premmerce_filter_term_link', $link );
        return trim( parse_url( $link )['path'], '/' );
    }
    
    /**
     * Generate text from rule attributes
     *
     * @param $data
     *
     * @return string
     */
    public function generateLabel( $data )
    {
        $category = get_term( $data['term_id'] );
        $text = $category->name . ': ';
        foreach ( $data['terms'] as $taxonomyName => $items ) {
            $taxonomy = get_taxonomy( $taxonomyName );
            $terms = get_terms( [
                'include'    => $items,
                'taxonomy'   => $taxonomyName,
                'hide_empty' => false,
            ] );
            $terms = array_map( function ( $term ) {
                return $term->name;
            }, $terms );
            $text .= sprintf( '<b>%s:</b> (%s) ', $taxonomy->labels->singular_name, implode( ', ', $terms ) );
        }
        return trim( $text );
    }
    
    public function remove( $ids )
    {
        parent::remove( $ids );
        $this->terms->removeByRuleIds( $ids );
        do_action( 'premmerce_filter_seo_bulk_rules_removed', [
            'ids' => $ids,
        ] );
    }
    
    public function updateBulk( $ids, $values )
    {
        $result = parent::updateBulk( $ids, $values );
        do_action( 'premmerce_filter_seo_bulk_rules_updated', [
            'ids'    => $ids,
            'values' => $values,
        ] );
        return $result;
    }
    
    /**
     * @param $taxonomy
     * @param $termIds
     *
     * @return array|int|WP_Error
     */
    private function getTermSlugsByIds( $taxonomy, $termIds )
    {
        $termIds = array_map( 'intval', $termIds );
        $slugs = get_terms( [
            'taxonomy'   => $taxonomy,
            'fields'     => 'id=>slug',
            'include'    => $termIds,
            'hide_empty' => false,
        ] );
        return $slugs;
    }
    
    /**
     * Create table
     *
     * @return array
     */
    public function install()
    {
        $charsetCollate = $this->db->get_charset_collate();
        $sql = "CREATE TABLE {$this->table} (\n                `id` INT(11) NOT NULL AUTO_INCREMENT,\n                `term_id` INT(11) NOT NULL,\n                `path` varchar(2083) NOT NULL,\n                `h1` text ,\n                `title` text ,\n                `meta_description` text,\n                `description` text,\n                `label` text,\n                `enabled` int(1) DEFAULT 0,\n                `discourage_search` int(1) DEFAULT 0,\n                `created_at` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,\n                `modified_at` DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,\n                `data` text,\n                PRIMARY KEY  (id)\n            ) {$charsetCollate};";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        return dbDelta( $sql );
    }
    
    /**
     * Drop table
     *
     * @return false|int
     */
    public function uninstall()
    {
        return $this->drop();
    }

}