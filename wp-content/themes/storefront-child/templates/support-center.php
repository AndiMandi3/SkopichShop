<?php
/**
 * Template Name: sup-center
 *
 */
if (is_page()) {
	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 } else {
   get_header();
 }
 ?>
 <div class="container">
  <h1 class="h1custom"><?php the_field( 'titleh1' )?></h1>
  <?php
 echo do_shortcode('[ticket-submit]');
?>
</div>
<?php if (is_page()) {
	do_action( 'get_footer');
	locate_template(['templates/footer-custom.php'], true );
 } else {
   get_footer();
 } ?>
