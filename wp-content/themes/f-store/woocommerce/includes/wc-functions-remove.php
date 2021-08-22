<?php
if(! defined('ABSPATH')) {
    exit;   //Exit if accessed directly
}
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
/* function jk_dequeue_styles( $enqueue_styles ) {
	//unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
    unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
    //$enqueue_styles['woocommerce-general']['deps'] = array( 'fstore-style' );
    get_vd($enqueue_styles);
	return $enqueue_styles;
}

//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
*/