<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div>
	</div>

	<?php do_action( 'storefront_before_footer' ); ?>
	<!-- Motivated by MaMa -->
	<div class="d-none d-lg-block d-xl-block" id="footer">
		<div class="container-fluid sticky-bottom footer">
			<div class="container">
				<div class="row row-1">
					<div class="col-3">
						<a class="footl" href="privacy-policy">Условия использования</a>
					</div>
					<div class="col-3 foot cont">
						<a class="foot" href="/contacts">Контакты</a>
					</div>
					<div class="col-3 foot">
						<a class="foot" href="https://vk.com/svetlanaskopich" target="_blank">Блог</a>
					</div>
					<div class="col-3 footr">
						<a class="footr" href="https://vk.com/svetlanaskopich">Поддержка</a>
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
							<a href="https://vk.com/svetlanaskopick" target="_blank">
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
	</div>
	<script type="text/javascript">
	(function() {
  'use strict';

  function trackScroll() {
    var scrolled = window.pageYOffset;
    var coords = document.documentElement.clientHeight;

    if (scrolled > coords) {
      goTopBtn.classList.add('chevron_up-show');
	  smallHeader.classList.add('small_shapka');
    }
    if (scrolled < coords) {
      goTopBtn.classList.remove('chevron_up-show');
	  smallHeader.classList.remove('small_shapka');
	  search_form_2.style.display = "none";
    }
  }

  function backToTop() {
    if (window.pageYOffset > 0) {
      window.scrollBy(0, -80);
      setTimeout(backToTop, 5);
    }
  }

  var goTopBtn = document.querySelector('.chevron_up');
  var smallHeader = document.querySelector('.sml');

  window.addEventListener('scroll', trackScroll);
  goTopBtn.addEventListener('click', backToTop);
})();

	</script>

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
