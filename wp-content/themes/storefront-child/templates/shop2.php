<?php
/**
 * Template Name: shop2
 *
 */
if (is_page()) {
	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 } else {
   get_header();
 }
 ?>
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
   margin-top: 10px;
   float: left;
 }
 select.orderby {
   margin-top: 20px;
 }
 .storefront-sorting {
	 display: none;
 }
 @media (min-width: 768px) {
.content-area, .widget-area {
	margin-bottom: 0;
}
}
p.button {
   float: left;
   margin-top: 22px;
 }
 .for_button {
	 height: 100px;
 }
 </style>
 <?php do_action( 'woocommerce_before_main_content' ); ?>
<a class="chevron_up"></a>
<div class="container">
  <h1 class="h1custom"><?php the_field( 'titleh1' )?></h1>
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
    <div class="container for_button">
		<p id="filters_product" class="filters button">Показать фильтры</p>
		<p id="filters_product_1" class="filters button">Скрыть фильтры</p>
    </div>
    </div>
<?php do_action( 'woocommerce_before_shop_loop' ); ?>
<?php $product_ids_on_sale = wc_get_product_ids_on_sale();?>
<?php $loop = new WP_Query( array(
'post_type' => 'product',
'posts_per_page' => 100,
'orderby' => 'menu_order',
'order' => 'ASC',
'meta_query'   => [[
  'key' => '_price',
  'value' => 0,
  'compare' => '>',
  'type' => 'NUMERIC'
]]
));
?>
<div class="container catalog">
  <div class="row shoes">
    <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
    <div class="col-4 single"> <!-- Имеет смысл сюда добавить классы col-sm-12 col-md-6 -->
                       <!-- // картинка -->
      <a class="col-12 fash" href="<?php the_permalink(); ?>">
        <img src="<?php $id = get_post_thumbnail_id(); $url = wp_get_attachment_image_src($id, true); echo $url[0];?>">
      </a>
                      <!-- // название товара -->
      <p class="name">
        <?php the_title(); ?>
      </p>
                     <!-- // цена товара -->
      <p class="price">
        <?php _e("Цена:","examp"); ?>
        <?php woocommerce_template_loop_price(); ?>
      </p>
      <span><?php if($product->is_in_stock()){ echo'В наличии';};?></span>
			<span class="soldOut"><?php if(! $product->is_in_stock()){ echo'Нет в наличии';};?></span>
                      <!-- // кнопка добавить в корзину -->
<?php do_action('woocommerce_before_shop_loop_item', $plugin_public, 'show_product_new_badge', 1); ?>

      
<?php if ( $product->is_on_sale() ) :
          echo apply_filters( 'woocommerce_sale_flash', $plugin_public, 'show_product_sale_badge', 11, 3 ); 
          endif;
          ?>
      <?php shop_sku(); ?>
      <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</div>
<div><?php echo do_shortcode("[wrvp_recently_viewed_products]"); ?></div>
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
<?php do_action( 'woocommerce_after_shop_loop' ); ?>
<?php if (is_page()) {
	do_action( 'get_footer');
	locate_template(['templates/footer-custom.php'], true );
 } else {
   get_footer();
 } ?>