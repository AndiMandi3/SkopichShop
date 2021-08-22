<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fstore
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header id="masthead" class="site-header">
		<!-- Декстопная версия -->
	<div class="d-none d-lg-block d-xl-block verh" height="250px"> 
		<nav class="navbar navbar-expand-lg navbar-light bg-light skicky-top nav-fill justify-content-center" height='250px'>
			<div class="my-nav" class="collapse navbar-collapse">
				<ul class="navbar-nav mr-auto nav-fill justify-content-center">
					<li class="nav-item col">
						<a class="nav-link" href="catalog.html">Каталог <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Новинки</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Скидки</a>
					</li>
					<li class="nav-item navbar-right col">
						<a class="nav-link" href="collections.html" tabindex="-1" aria-disabled="true">Коллекции</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="navbar-brand">
						<a class="navbar-brand-cont" href="welcome.html">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/logo.svg">
						</a>
					</li>
					<li class="navbar-rightt col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"></a>
					</li>
					<li class="nav-item navbar-left col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Аксессуары</a>
					</li>
					<li class="nav-item col">
						<a class="nav-link" href="delivery.html" tabindex="-1" aria-disabled="true">Доставка</a>
					</li>
					<li class="nav-item-icon col">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Search.svg"></a>
					</li>
					<li class="nav-item-icon col" id="profile">
						<a class="nav-link" href="#" tabindex="-1" aria-disabled="true"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Profile.svg"></a>
					</li>
					<li class="nav-item-icon col">
						<a class="nav-link" href="card.html" tabindex="-1" aria-disabled="true"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Basket.svg"></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
		<!-- Элементы нав-бара -->
	<!-- Поиск -->
	<div class="search">
		<img class="search_form" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Search_icon.svg">
		<form action="/search/">
			<input type="hidden" name="searchid" value="808327">
			<input type="search" name="text" placeholder="Ищу...">
		</form>
	</div>
	<!-- Вход/регистрация -->
	<!-- Вход -->
	<div id="my-modal" class="modal">
		<div class="login">
			<div class="login_green">
				<button type="button" class="close"></button>
				<img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/logo_small.svg">
			</div>
			<!-- Вход -->
			<div class="register_form" id="signin">
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
			</div>
			<!-- Регистрация -->
			<div class="register_form" id="register">
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
				<img class="line_left" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Line.svg">
				<p class="fast_auto">быстрая авторизация</p>
				<img class="line_right" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/Line.svg">
			</div>
			<div class="sources">
				<a class="vk" href="https://vk.com" target="_blank">
					<img class="soc" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/VK.svg">
				</a> 
				<a class="inst" href="https://instagram.com" target="_blank">
					<img class="soc" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/inst.svg">
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
	</script>
</header>
