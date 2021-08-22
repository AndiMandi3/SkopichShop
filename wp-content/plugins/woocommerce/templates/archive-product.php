<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
do_action('get_header');
locate_template(['templates/header-custom.php'], true );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title h1custom"><?php woocommerce_page_title(); ?></h1>
		<a class="chevron_up"></a>
	<?php endif; ?>

	<?php
	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<div class="container">
        <!-- быстрая навигация -->
        <div class="row bread">
            <div class="col-2 fast">
                <p>Быстрая навигация:</p>
            </div>
            <div class="col-2 fast1">
                <a href="/new-clothes">НОВИНКИ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/sales">СКИДКИ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/collections">КОЛЛЕКЦИИ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/accessories">АКСЕССУАРЫ</a>
            </div>
		</div>
	</div>
<style>
 .variations_form {
  margin-top: 20px;
 }
 .variations {
    display: flex;
    justify-content: center;
    flex-direction: column;
    flex-wrap: nowrap;
    align-content: stretch;
    align-items: center;
 }
li.woo-variation-items-wrapper {
   width: auto!important;
   margin: 0!important;
 }
 .woof_reset_search_form, .woof_submit_search_form {
   margin-top: 0!important;
 }
 p.button {
   float: left;
   margin-top: 22px;
 }
 select.orderby {
   margin-top: 20px;
 }
 .for_button {
	 height: 100px;
 }
 @media (min-width: 768px) {
	header.woocommerce-products-header {
	 padding: 0!important;
 }
 }
 .page-description {
	 display: none;
 }
 .storefront-sorting {
	 display: none;
 }
 @media (min-width: 768px) {
.content-area, .widget-area {
	margin-bottom: 0;
}
}
 </style>
 <div class="container for_button">
		<p id="filters_product" class="filters button">Показать фильтры</p>
		<p id="filters_product_1" class="filters button">Скрыть фильтры</p>
</div>
<div class="container">
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}
?> </div> <?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

do_action( 'get_footer');
locate_template(['templates/footer-custom.php'], true ); ?>
<script type="text/javascript">
  var filters = document.getElementById('filters_product');
  var filters_window = document.getElementsByClassName('woof')[0];
  var filters_hide = document.getElementById('filters_product_1')
  filters_window.style.display = "none";
  filters_hide.style.display = "none";
  filters.onclick = function() {
    filters_window.style.display = "block";
    filters.style.display = "none";
    filters_hide.style.display = "block";
  }
  filters_hide.onclick = function() {
    filters_window.style.display = "none";
    filters.style.display = "block";
    filters_hide.style.display = "none";
  }
</script>
