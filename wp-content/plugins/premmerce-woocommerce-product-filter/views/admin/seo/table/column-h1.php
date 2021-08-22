<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/** @var array $item */
$term = get_term( $item['term_id'] );
$editLink = $url . '&action=edit&id=' . $item['id'];
if ( empty($url) ) {
    $editLink = get_edit_term_link( $item['term_id'] );
}
$path = apply_filters( 'wpml_permalink', home_url( $item['path'] ) );
?>
<strong><a href="<?php 
echo  $editLink ;
?>"><?php 
echo  $term->name ;
?></a></strong>

