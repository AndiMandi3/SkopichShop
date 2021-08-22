<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

if (is_page()) {
	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 } else {
   get_header();
 } 
?>
<script>jQuery( document ).ready(function() {
jQuery('header').hide();
jQuery('div', '#hide').hide();
});</script>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">

				<div class="container err">
        <div class="row error"></div>
        <div class="row">
            <p>Что-то пошло не так. Попробуйте зайти сюда через некоторое время. Если проблема повторится, <a href="https://vk.com/svetlanaskopich">сообщите нам</a>. А пока, посмотрите нашу <a href="/catalog">одежду</a> :)</p>
        </div>
    </div>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

