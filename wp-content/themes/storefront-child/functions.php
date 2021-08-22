<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
// Стили
function my_styles() {
    wp_enqueue_style( 'bootstrap-style', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', array(), null, 'all' );
    wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/assets/fonts/fonts.css', array(), null, 'all' );
    wp_enqueue_style( 'owl-min', get_stylesheet_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css', array(), null, 'all' );
    wp_enqueue_style( 'owl-default', get_stylesheet_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css', array(), null, 'all' );
}
add_action( 'wp_enqueue_scripts', 'my_styles' );

// Скрипты
function my_scripts() {
    wp_enqueue_script ( 'jQuery' );
    wp_enqueue_script( 'fstore-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array(), true);
    wp_enqueue_script( 'fstore-skip-link-focus-fix', get_stylesheet_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), null, true);
    wp_enqueue_script( 'bootstrap-script', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'owl-script', get_stylesheet_directory_uri() . '/assets/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/assets/js/script.js', array('jquery'), null, true );
    wp_enqueue_script( 'front-script', get_stylesheet_directory_uri() . '/assets/js/front-script.js', array('jquery'), null, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action ( 'wp_enqueue_scripts', 'my_scripts' );
// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_add_parent_dep' ) ):
function chld_thm_cfg_add_parent_dep() {
    global $wp_styles;
    array_unshift( $wp_styles->registered[ 'storefront-child-style' ]->deps, 'storefront-style' );
}
endif;
add_action( 'wp_head', 'chld_thm_cfg_add_parent_dep', 2 );

// END ENQUEUE PARENT ACTION
add_action( 'init', 'true_woo_no_breadcrumbs_storefront' );
function true_woo_no_breadcrumbs_storefront() {
	remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
}
add_action( 'init', 'true_woo_no_loops_storefront' );
function true_woo_no_loops_storefront() {
	remove_action( 'storefront_before_shop_loop', 'woocommerce_result_count', 10 );
}


function no_primary() {
    remove_action ( 'storefront_header', 'storefront_primary_navigation', 50  );
}
add_action ( 'storefront_header', 'no_primary' );

function no_header_cart() {
    remove_action ( 'storefront_header', 'storefront_header_cart', 60 );
}
add_action ( 'storefront_header', 'no_header_cart' );

function no_branding() {
    remove_action ( 'storefront_header', 'storefront_site_branding', 20 );
}
add_action ( 'storefront_header', 'no_branding' );

function no_search() {
    remove_action ( 'storefront_header', 'storefront_product_search', 40 );
}
add_action ( 'storefront-search', 'no_search' );
// Артикул
add_action( 'woocommerce_after_shop_loop_item_title', 'shop_sku' );
function shop_sku(){
global $product;
echo '<p class="articul" itemprop="productID" class="sku">Артикул: ' . $product->sku . '</p>';
}
function carolinaspa_remove_tab2($tabs2) {
    echo '<pre>';
    var_dump ($tabs2);
    echo '</pre>';}
add_filter('woocommerce_product_tabs', 'carolinaspa_remove_tab2', 20);
add_filter( 'woocommerce_enqueue_styles', '__return_false' );


function filter_woocommerce_post_class( $classes, $product ) {
    global $woocommerce_loop;
    
    // is_product() - Returns true on a single product page
    // NOT single product page, so return
    if ( ! is_product() ) return $classes;
    
    // The related products section, so return
    if ( $woocommerce_loop[''] == 'related' ) return $classes;
    
    // Add new class
    $classes[] = 'my-product-class';
    
    return $classes;
}
add_filter( 'woocommerce_post_class', 'filter_woocommerce_post_class', 10, 2 );

function remove_comment_fields($fields) {
    unset($fields['email']);
    return $fields;
}
add_filter('comment_form_default_fields', 'remove_comment_fields');

function custom_validate_comment_author() {
    if( empty( $_POST['author'] ) || ( !preg_match( '/[^\s]/', $_POST['author'] ) ) )
        wp_die( __('Ошибка! Пожалуйста, заполните поле Имя') );
}
add_action( 'pre_comment_on_post', 'custom_validate_comment_author' );
?>
<?php
/**
* Change Proceed To Checkout Text in WooCommerce
* Place this in your Functions.php file
**/
function woocommerce_button_proceed_to_checkout() {
 $checkout_url = WC()->cart->get_checkout_url();
 ?>
 <a href="<?php echo $checkout_url; ?>" class="checkout-button button wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
 <?php
 }

// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
 
// // Все $fields в этой функции будут пропущены через фильтр
// function custom_override_checkout_fields( $fields ) {
// unset($fields["billing"]["billing_company"]);
// unset($fields["billing"]["billing_country"]);
// unset($fields["billing"]["billing_state"]);
// unset($fields["billing"]["billing_city"]);
// return $fields;
// }

// function change_woocommerce_field_markup($field, $key, $args, $value) {
//     if( $key === 'billing_first_name') {
//         $field = '<div class="zagalovok">
//         <p class="wrtadres form-row-first for-row" id="'.esc_attr($key).'">
//         <label for="'.esc_attr($key).'">'. wp_kses_post( $args['label'] ).'</label></p>
//         <input class="wrtadres_form" type="text" name = "'.esc_attr($key).'" id="'.esc_attr($key).'" name="adress" value="'.esc_attr($value).'">
//         </div>';
//     }
//     return $field;
// } 
// add_filter("woocommerce_form_field","change_woocommerce_field_markup", 10, 4);

// function change_woocommerce_field_phone($field, $key, $args, $value) {
//     if( $key === 'billing_email_field' or $key === 'billing_phone_field') {
//         $field = '<div class="register_form">
//             <div class="emailortel">
//                 <p class="email">E-mail</p>
//                 <input class="empas" type="text" name="uname">
//             </div>

//         <p class="wrtadres form-row-first for-row" id="'.esc_attr($key).'">E-mail</p>
//         <input class="wrtadres_form" type="text" name = "'.esc_attr($key).'" id="'.esc_attr($key).'" name="adress" value="'.esc_attr($value).'">';
//     }
//     return $field;
// }
// add_filter("woocommerce_form_field","change_woocommerce_field_phone", 10, 5);
add_action( 'woocommerce_before_shop_loop_item_title', 'show_new_label_before_title', 20 );
function show_new_label_before_title() {
  if ( get_field('is_new_product') ) {
  ?>
  <div class="product-new-label">NEW!</div>
  <?php }
}

function ebanaya_ssilka($url) {
  $url = 'https://wp/catalog/'; // Add your custom link here
  return $url;
  }
  add_filter('woocommerce_return_to_shop_redirect', 'ebanaya_ssilka');

  function twentyseventeen_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'twentyseventeen' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentyseventeen' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h6 class="widget-title">',
        'after_title'   => '</h6>',
    ) );   
    register_sidebar( array(
        'name' => __( 'Шапка.справа', '' ),
        'id' => 'top-area',
        'description' => __( 'Шапка', '' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
  }
add_action('widgets_init', 'twentyseventeen_widgets_init');

function register_my_widgets(){
	register_sidebar( array(
		'name' => "Правая боковая панель сайта",
		'id' => 'right-sidebar',
		'description' => 'Эти виджеты будут показаны в правой колонке сайта',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	) );
}
add_action( 'widgets_init', 'register_my_widgets' );

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = 100;
  return $cols;
}
function yourtheme_woocommerce_image_dimensions() {
    global $pagenow;

    if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
        return;
    }
    $catalog = array(
        'width'     => '350',   // px
        'height'    => '467',   // px
        'crop'      => 0 // Disabling Hard crop option.
    );
    $single = array(
        'width'     => '150',   // px
        'height'    => '150',   // px
        'crop'      => 0 // Disabling Hard crop option.
    );
    $thumbnail = array(
        'width'     => '90',   // px
        'height'    => '90',   // px
        'crop'      => 0 // Disabling Hard crop option.
    );
    // Image sizes
    update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
    update_option( 'shop_single_image_size', $single );      // Single product image
    update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
}
add_action( 'after_switch_theme', 'yourtheme_woocommerce_image_dimensions', 1 );

function my_get_the_product_thumbnail_url( $size = 'shop_catalog' ) {
    global $post;
    $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );
    return get_the_post_thumbnail_url( $post->ID, $image_size );
  }
add_action( 'woocommerce_template_loop_product_thumbnail', 'my_get_the_product_thumbnail_url');

function custom_my_account_menu_items( $items ) {
    unset($items['downloads']);
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );