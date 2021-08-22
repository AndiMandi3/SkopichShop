<?php
/**
 * Template name: first Page (coockie)
 */

	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 ?>
<style>
	header.site-header {
		margin-bottom: 0!important;
		height: 250px;
	}
</style>
<!-- Шапка (1 фрейм) -->
<a class="chevron_up"></a>
<div class="container-fluid div-multi">
		<div class="row justify-content-center">
			<h2 id="welcome">Добро пожаловать к нам!</h2><br>
		</div>
		<div class="row justify-content-center">
			<p id="welcome1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur hendrerit placerat velit.</p>
		</div>
		<div class="container">
			<a class="welcomebut" type="button" name="butt" href="/catalog">Перейти в каталог</a>
			<img id="Arrow" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Arrow 1.svg">
		</div>
	</div>
	<!-- 2 фрейм -->
	<section class="hero">
		<div class="container pt-4">
			<div class="row">
				<div class="col-6 image_2">
					<img src="<?php $url = wp_get_attachment_image_src(248, true); echo $url[0]; ?>" class="hero-image row">
				</div>
				<div class="col-6 des_2">
					<h1 class="h1 title mr-8 pt-4">Почему мы?</h2>
					<p class="description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br> Curabitur hendrerit placerat velit.</p>
				</div>
			</div>
		</div>
	</section>
	<!-- 3 фрейм -->
	<section class="hero">
		<div class="container pt-4">
			<div class="row">
				<div class="col-6">
					<h1 class="h1 title mr-4 row">Почему мы?</h2>
					<p class="description row">Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br> Curabitur hendrerit placerat velit.</p>
				</div>
				<div class="col-6">
				<img src="<?php $url = wp_get_attachment_image_src(249, true); echo $url[0]; ?>">
			</div>
		</div>
	</section>
	<!-- самый низ (4 фрейм) -->
	<div class="container frame4">
		<div class="row justify-content-center">
			<h2 id="easy">Видите, как все просто?</h2><br>
		</div>
		<div class="row justify-content-center">
			<p id="easy1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur hendrerit placerat velit, fermentum imperdiet massa porttitor</p>
		</div>
		<div class="container">
			<a class="endbut" type="button" name="butt" href="/catalog">Перейти в каталог</a>
		</div>
	</div>

<?php
do_action( 'storefront_sidebar' );

do_action( 'get_footer');
    locate_template(['templates/footer-custom.php'], true );
    ?>
