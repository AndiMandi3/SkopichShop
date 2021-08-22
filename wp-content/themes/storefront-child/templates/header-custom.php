<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<!-- Motivation from Hob1n, wow by lzyloyd-->
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>
<style>
	a:focus {
		border: none;
	}
</style>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>
	<header id="masthead" class="site-header" role="banner">
	<div class="d-none d-lg-block d-xl-block verh" height="250px"> 
		<nav class="navbar navbar-expand-lg navbar-light bg-light skicky-top nav-fill justify-content-center" height='250px'>
			<div class="my-nav" class="collapse navbar-collapse">
				<ul class="navbar-nav mr-auto nav-fill justify-content-center">
					<li class="nav-item col">
						<a class="nav-link" href="/catalog">Каталог <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/new-clothes" tabindex="-1" aria-disabled="true">Новинки</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/sales" tabindex="-1" aria-disabled="true">Скидки</a>
					</li>
					<li class="nav-item navbar-right col">
						<a class="nav-link" href="/collections" tabindex="-1" aria-disabled="true">Коллекции</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="navbar-brand">
						<a class="navbar-brand-cont" href="/welcome">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/logo.svg">
						</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="nav-item navbar-left col">
						<a class="nav-link" href="/accessories" tabindex="-1" aria-disabled="true">Аксессуары</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/delivery" tabindex="-1" aria-disabled="true">Доставка</a>
					</li>
					<li class="nav-item-icon col">
						<a class="nav-link" id="search-btn" href="#" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Search.svg"></a>
					</li>
					<li class="nav-item-icon col" id="profile">
						<a class="nav-link" href="/my-account" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Profile.svg"></a>
					</li>
					<li class="nav-item-icon col">
						<a class="nav-link" href="/cart" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Basket.svg"></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
	<!-- Маленькая шапка -->
	<div class="d-none d-lg-block d-xl-block sml" height="150px">
		<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top nav-fill justify-content-center small_shapka" height='250px'">
			<div id="my-nav" class="collapse navbar-collapse">
				<ul class="navbar-nav mr-auto ml-auto nav-fill justify-content-center">
					<li class="nav-item col">
						<a class="nav-link" href="/catalog">Каталог <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/new-clothes" tabindex="-1" aria-disabled="true">Новинки</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/sales" tabindex="-1" aria-disabled="true">Скидки</a>
					</li>
					<li class="nav-item navbar-right col">
						<a class="nav-link" href="/collections" tabindex="-1" aria-disabled="true">Коллекции</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="navbar-brand">
						<a class="navbar-brand-cont" href="/welcome">
							<img srcset="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/small_shapka.svg">
						</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="nav-item navbar-left col">
						<a class="nav-link" href="/accessories" tabindex="-1" aria-disabled="true">Аксессуары</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="/delivery" tabindex="-1" aria-disabled="true">Доставка</a>
					</li>
					<li class="nav-item-icon col">
					<a class="nav-link" id="search-btn-2" href="#" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Search.svg"></a>
					</li>
					<li class="nav-item-icon col">
					<a class="nav-link" href="/my-account" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Profile.svg"></a>
					</li>
					<li class="nav-item-icon col">
						<a class="nav-link" href="/cart" tabindex="-1" aria-disabled="true"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Basket.svg"></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
		<!-- Элементы нав-бара -->
	<!-- Поиск -->
	<div class="search" id="modal-search">
		<img class="search_form" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Search_icon.svg">
		<?php echo do_shortcode("[aws_search_form]"); ?>
	</div>
	<div class="search-2" id="modal-search-2">
		<img class="search_form" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Search_icon.svg">
		<?php echo do_shortcode("[aws_search_form]"); ?>
	</div>
	<!-- Вход/регистрация -->
	<!-- Вход -->
	<!-- <div id="my-modal" class="modal">
		<div class="login">
			<div class="login_green">
				<button type="button" class="close"></button>
				<img class="logo" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/logo_small.svg">
			</div> -->
			<!-- Вход -->
			<!-- <div class="register_form" id="signin">
				<form class="register_f">
					<div class="emailortel">
						<p class="email">Email или телефон</p>
						<input class="empas" type="text" name="uname" required>
					</div>
					<div class="password">
						<p class="psw">Пароль</p>
						<input class="empas" type="password" name="psw" required>
					</div>
				</form>
				<a class="forget" href="#">Забыли пароль?</a>
				<a class="voyti" type="button" name="voy" href="#">Войти</a>
			</div> -->
			<!-- Регистрация -->
			<!-- <div class="register_form" id="register">
				<form class="register_f">
					<div class="name_block name">
						<p>Имя</p>
						<input type="text" name="rname" required>
					</div>
					<div class="female_block">
						<p class="fema">Фамилия</p>
						<input class="fem" type="text" name="rfemale" required>
					</div>
					<div class="emailortel">
						<p class="email">Email или телефон</p>
						<input class="empas" type="text" name="uname" required>
					</div>
					<div class="password">
						<p class="psw">Пароль</p>
						<input class="empas" type="password" name="psw" required>
					</div>
				</form>
				<a class="voyti" type="button" name="voy" href="#">Зарегистрироваться</a>
			</div>
			<div class="lines">
				<img class="line_left" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Line.svg">
				<p class="fast_auto">быстрая авторизация</p>
				<img class="line_right" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/Line.svg">
			</div>
			<div class="sources">
				<a class="vk" href="https://vk.com" target="_blank">
					<img class="soc" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/VK.svg">
				</a> 
				<a class="inst" href="https://instagram.com" target="_blank">
					<img class="soc" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/inst.svg">
				</a>
				<a class="but_reg" href="#">
			</div>
				<div class="reg" id="toreg" sticky="bottom">
					<p>Регистрация</p>
				</div>
				<a class="but_reg" href="#">
					<div class="reg" id="tovoy" sticky="bottom">
						<p class="voy">Войти</p>
					</div>
				</a>
			</a>
		</div>
	</div>
	<script type="text/javascript">
	var modal = document.getElementById('my-modal');
	var signup = document.getElementById('register');
	var signin = document.getElementById('signin');
	var butfor = document.getElementById('toreg');
	var butpref = document.getElementById('tovoy');
    var btn = document.getElementById('profile');
	var close = document.getElementsByTagName('button')[0];
    
    btn.onclick = function () {
		modal.style.display = "block";
		signup.style.display = "none";
		butfor.style.display = "block";
		butpref.style.display = "none";
		signin.style.display = "block";

	 }
	butfor.onclick = function () {
		 signin.style.display = "none";
		 signup.style.display = "block";
		 butfor.style.display = "none";
		 butpref.style.display = "block";

	 }
	butpref.onclick = function () {
		 signup.style.display = "none";
		 signin.style.display = "block";
		 butpref.style.display = "none";
		 butfor.style.display = "block";
	 }
    close.onclick = function (event) {
		modal.style.display = "none";
	}
	window.onclick = function (event) {
        if(event.target == modal) {
			modal.style.display = "none";
        }
	}
	</script> -->
	<script type="text/javascript">
	var search = document.getElementById('search-btn');
	var search_2 = document.getElementById('search-btn-2')
	var search_form = document.getElementById('modal-search');
	var search_form_2 = document.getElementById('modal-search-2');
	search_form.style.display = "none";
	search_form_2.style.display = "none";
	search.onclick = function (e) {
		e.preventDefault();
		search_form.style.display = "block";
	}
	search_2.onclick = function (e) {
		e.preventDefault();
	 	search_form_2.style.display = "block";
	 }
	jQuery(function($){
	$(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $(".search"); // тут указываем ID элемента
		var div_2 = $(".search-2");
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			div.hide(); // скрываем его
		}
		 if (!div_2.is(e.target) // если клик был не по нашему блоку
		     && div_2.has(e.target).length === 0) { // и не по его дочерним элементам
		 	div_2.hide(); // скрываем его
		 }
	});
});
	</script>

		<!-- /**
		 * Functions hooked into storefront_header action
		 *
		 * @hooked storefront_header_container                 - 0
		 * @hooked storefront_skip_links                       - 5
		 * @hooked storefront_social_icons                     - 10
		 * @hooked storefront_site_branding                    - 20
		 * @hooked storefront_secondary_navigation             - 30
		 * @hooked storefront_product_search                   - 40
		 * @hooked storefront_header_container_close           - 41
		 * @hooked storefront_primary_navigation_wrapper       - 42
		 * @hooked storefront_primary_navigation               - 50
		 * @hooked storefront_header_cart                      - 60
		 * @hooked storefront_primary_navigation_wrapper_close - 68
		 */ -->

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'storefront_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'storefront_content_top' );
