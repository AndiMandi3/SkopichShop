<?php
/**
 * Template Name: Thank You
 */
defined( 'ABSPATH' ) || exit;
?>
<script>jQuery( document ).ready(function() {
jQuery('header').hide();
jQuery('div', '#hide').hide();
});</script>
<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>
			<div class="container suc">
        <div class="row success">
            <img class="verify_suc" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/ivents/success.svg" alt="">
        </div>
        <div class="row sucp">
            <p>Все прошло "как по маслу"! В скором времени на электронную почту должен прийти чек об оплате. Можете вернуться в <a href="/welcome">главное меню</a> или посмотреть свои <a href="/my-account/orders/">заказы</a></p>
			<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
        </div>
    </div>

		<?php endif; ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
<!-- <script type="text/javascript">
var element = document.getElementById("hide");
element.style.display = "none";
</script> -->

