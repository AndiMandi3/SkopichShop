<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>
	<!-- Motivated by MaMa -->
	<footer class="d-none d-lg-block d-xl-block" id = "hide">
		<div class="container-fluid sticky-bottom footer">
			<div class="container">
				<div class="row row-1">
					<div class="col-3">
						<a class="footl" href="privacy-policy">Условия использования</a>
					</div>
					<div class="col-3 foot cont">
						<a class="foot" href="contacts">Контакты</a>
					</div>
					<div class="col-3 foot">
						<a class="foot" href="#">Прайс-лист</a>
					</div>
					<div class="col-3 footr">
						<a class="footr" href="#">Поддержка</a>
					</div>
					<div class="row w-100">
						<div class="col-6 con">
							<p>Контактный телефон: 7-XXX-XXX-XX-XX</p>
							<p class="num">Aдрес: г.Новосибрск, ул. Lorem Ipsum, д. Lor, 000000</p>
						</div>
						<div class="col-6 imges">
							<a href="https://flump.ru" target="_blank" >
								<img class="images" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/logo-flamp-512x400 1.svg">
							</a>
							<a href="https://vk.com" target="_blank">
								<img class="images" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/VK.svg">
							</a>
							<a href="https://instagram.com" target="_blank" class="last">
								<img class="images" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/inst.svg">
							</a>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</footer>

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			?>

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
