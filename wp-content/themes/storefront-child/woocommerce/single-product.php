<?php
/**
 * Template Name: Card Product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
do_action( 'get_header');
locate_template(['templates/header-custom.php'], true );
the_post();
global $post;
$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) );
global $product;
$terms = get_the_terms( $product->get_id(), 'product_cat' );
$upsells = $product->get_upsell_ids();
$id = $product->get_id();
// $ProductQueryFilters = new ProductQueryFilters();
// $ProductQueryFilters -> get_rating_counts( $request );
?>
<style>
p.stars.selected a.active:before, p.stars:hover a:before, p.stars.selected a:not(.active):before, p.stars.selected a.active:before {
	color: #2aaa8b;
}
h1.product_title {
	text-align: start;
}

</style>
<body>
	<a class="chevron_up"></a>
	<main class="wrap">
	<section class="main">
        <div class="container info_1">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-4">
                    <div class="main_img row" style="background: url('<?php $id = get_post_thumbnail_id(); $url = wp_get_attachment_image_src($id, true); echo $url[0];?>'); width: 360px; height: 400px; background-size: 100%; background-position-y: top; background-position-x: center;"></div>
                    <div class="w-100 small row" id="small_thumbs"><?php
                    	    $id = get_post_thumbnail_id();
							$url = wp_get_attachment_image_src($id, true);
                    	    $attachment_ids = $product->get_gallery_attachment_ids();
                    	    foreach( $attachment_ids as $attachment_id ):
                    	        if($url[0] == wp_get_attachment_url( $attachment_id )){
                    	            echo '<div class="border_img bordered small_img" href="#" style="background: url('.wp_get_attachment_url( $attachment_id ).'); background-size: 100%; background-position-y: top; background-position-x: center;"></div>';
                    	        }
								else {
									echo '<div class="border_img bordered small_img" href="#" style="background: url('.wp_get_attachment_url( $attachment_id ).'); background-size: 100%; background-position-y: top; background-position-x: center; "></div>';
								}
                    	    endforeach; ?>
                    </div>
					<div class="video_obs">
						<?php the_field("video"); ?>
					</div>
                </div>
                <div class="col-7">
					<?php
					do_action('woocommerce_single_product_summary'); ?>
					<br>
					<a id="delivery" href="/delivery" target="_blank">Условия доставки</a>
					<br>
					<a id="refund" href="/refund" target="_blank">Условия возврата денежных средств</a>
            	</div>
				
        </div>
		<!-- для доставки -->
		<!-- <div class="container container_delivery">
			<div id="close"></div>
			<h1 class="h1custom">Условия доставки</h1>
			<div class="text">
				<p class="chto-to">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus rutrum lectus quis ullamcorper fermentum. Morbi ex neque, iaculis finibus mi eu, efficitur dapibus nisl. Vivamus at sapien libero. Maecenas efficitur nulla molestie purus congue ornare. Proin suscipit ante sed tellus convallis sollicitudin. Integer dictum tincidunt risus sed ultrices. Maecenas quis lectus ex. Suspendisse egestas magna ex, id tempor metus fringilla eu. Aliquam nec ultrices nulla. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer nec maximus arcu. Aenean ac luctus lacus. Suspendisse efficitur nec felis et ultricies. Curabitur quis dictum augue, sit amet scelerisque purus. Etiam justo felis, facilisis non risus a, luctus commodo turpis. Ut quis massa dui.

<br>Cras laoreet tristique molestie. Praesent nec lorem nisi. Mauris id consectetur leo, ac suscipit felis. Vestibulum feugiat enim ut gravida laoreet. Nulla vitae bibendum neque, ut pharetra diam. Etiam efficitur, diam eu efficitur porta, magna leo laoreet dolor, ut semper sem quam vel tortor. Fusce sem metus, ornare sit amet lobortis eget, lobortis eu erat. Maecenas euismod pulvinar turpis, et maximus nisi ullamcorper vel. Maecenas scelerisque vulputate nunc, et sollicitudin magna scelerisque sed. Cras sed lectus eget sapien egestas volutpat. Aliquam tincidunt erat nec tempor molestie. Donec faucibus rutrum eleifend. In tristique diam enim, rhoncus mollis lectus consequat id.</p>
			</div>
		</div>
		<script type="text/javascript">
			var delivery = document.getElementsByClassName("container_delivery");
		</script> -->
		<!-- конец условия -->
	<!-- Отзывы. Форма написания -->
	<div class="container otzivi">
                <div class="row pr" id="review_form_wrapper">
					<div id="review_form">
						<?php
						$commenter    = wp_get_current_commenter();
						$comment_form = array(
							'title_reply'       => 'Ваш отзыв',
							'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
							'title_reply_after'    => '</h3>',
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'label_submit'         => esc_html__( 'Submit', 'woocommerce' ),
							'logged_in_as'         => '',
							'comment_field'        => '',
							'format' 			   => 'xhtml',
						);

						$name_required = (bool) get_option( 'require_name', 1 );
						$fields              = array(
							'author' => array(
								'label'    => __( 'Name', 'woocommerce' ),
								'type'     => 'text',
								'value'    => $commenter['comment_author'],
								'required' => $name_required,
							),
						);

						$comment_form['fields'] = array();

						foreach ( $fields as $key => $field ) {
							$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
							$field_html .= '<label for="' . esc_attr( $key ) . '">';

							//if ( $field['required'] ) {
							//	$field_html .= '&nbsp;<span class="required">*</span>';
							//}

							$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="'.$field['label'].'" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

							$comment_form['fields'][ $key ] = $field_html;
						}

						$account_page_url = wc_get_page_permalink( 'myaccount' );
						if ( $account_page_url ) {
							/* translators: %s opening and closing link tags respectively */
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
						}

						if ( wc_review_ratings_enabled() ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating star"><P>' . esc_html__( 'Оцените данный товар', 'woocommerce' ) . '</p><select name="rating" id="rating" required>
								<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
								<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
								<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
								<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
								<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
								<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
							</select></div>';
						}

						$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment"></label><textarea id="comment" class="text_otz" name="comment" placeholder="Понравился товар? Пусть все об этом узнают!" cols="45" rows="8" required></textarea></p>';
						add_filter('comment_form_fields', 'kama_reorder_comment_fields' );
						function kama_reorder_comment_fields( $fields ){
							// die(print_r( $fields )); // посмотрим какие поля есть

							$new_fields = array(); // сюда соберем поля в новом порядке

							$myorder = array('author','comment'); // нужный порядок

							foreach( $myorder as $key ){
								$new_fields[ $key ] = $fields[ $key ];
								unset( $fields[ $key ] );
							}

							// если остались еще какие-то поля добавим их в конец
							if( $fields )
								foreach( $fields as $key => $val )
									$new_fields[ $key ] = $val;

							return $new_fields;
						}
						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>
            </div>
	<!-- Важный отзыв -->
	<!-- Вывод отзывов -->
	<?php
    $args = array ('post_id' => $product->get_id());
    $comments = get_comments($args);
    wp_list_comments( array( 'callback' => 'woocommerce_comments' ), $comments);
?>
<!-- конец вывода -->
	
<?php 
do_action( 'get_footer');
locate_template(['templates/footer-custom.php'], true );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
?>
<script type="text/javascript">
	var img = document.getElementById('small_thumbs');
	const first_img = document.querySelector('#small_thumbs > .bordered:first-child');
	const sec_img = document.querySelector('#small_thumbs > .bordered:nth-child(2)')
	const third_img = document.querySelector('#small_thumbs > .bordered:nth-child(3)')
	const last_img = document.querySelector('#small_thumbs > .bordered:last-child');
	var video = document.getElementsByClassName("video_obs")[0];
	video.style.display = "none";
	first_img.onclick = function() {
		video.style.display = "none";
	}
	sec_img.onclick = function() {
		video.style.display = "none";
	}
	third_img.onclick = function() {
		video.style.display = "none";
	}
	last_img.onclick = function() {
		if (last_img === last_img) { 
		video.style.display = "block";
		}
	}
	

</script>