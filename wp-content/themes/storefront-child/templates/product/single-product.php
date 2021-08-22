<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
$meta_query  = WC()->query->get_meta_query();
$tax_query   = WC()->query->get_tax_query();
$tax_custom_query[] = array(
    'taxonomy' => 'product_visibility',
    'field'    => 'name',
    'terms'    => 'featured',
    'operator' => 'IN',
);
$params = array(
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'orderby' => 'post_date',
    'order' => 'DSC',
    'posts_per_page'      => 1,
    'meta_query'          => $meta_query,
    'tax_query'           => $tax_query,
);
$wc_query = new WP_Query($params); 
?>
<?php if ($wc_query->have_posts()) :  ?>
<?php while ($wc_query->have_posts()) : $wc_query->the_post();  ?>
<?php the_title();  ?>
<?php the_content();  ?>
<?php endwhile; ?>
<?php wp_reset_postdata();  ?>
<?php else:  ?>
<p><?php _e( 'No Product' );  ?></p>
<?php endif; ?>