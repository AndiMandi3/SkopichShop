<?php
/**
 * Template Name: welcome
 *
 */

if (is_page()) {
	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 } else {
   get_header();
 } ?>
  <style>
 .variations_form {
  margin-top: 20px;
  display: flex;
  justify-content: center;
 }
 .variations {
    display: flex;
    justify-content: center;
    flex-direction: column;
    flex-wrap: nowrap;
    align-content: stretch;
    align-items: center;
 }
 a:focus {
	 border: none;
 }
 </style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<a class="chevron_up"></a>
		<h1 class="h1custom"><?php the_field('titleh1'); ?></h1>
		<div class="container owl owl-carousel owl-theme">
		<?php
			$images = get_field('slide');
			foreach ($images as $image):?>
				<a href="<?php echo $image['alt'] ?>">
					<div class="item owl1">
					<div class="dark"></div>
						<img class="img_slider" src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
					</div>
				</a>    
    		<?php endforeach;?>
			</div>
		<a href="new-clothes" class="welcome">
		<h1 class="h1custom"><?php the_field('title_new'); ?></h1>
		</a>
		<div class="container new_shoes">
			<div class="row first_pos">
				<a href="#">
				<?php 
				$image = get_field('new_shoe_up');
				if( !empty( $image ) ): ?>
    				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php endif; ?>
				</a>
				<a class="welcome_shoe" href="<?php the_permalink(); ?>">
				<?php 
				$image = get_field('new_shoe_up_left');
				if( !empty( $image ) ): ?>
    				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php endif; ?>
				</a>
			</div>
			<div class="row second_pos">
			<a href="#">
				<?php 
				$image = get_field('new_shoe_down');
				if( !empty( $image ) ): ?>
    				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php endif; ?>
				</a>
				<a class="welcome_shoe" href="<?php the_permalink(); ?>">
				<?php 
				$image = get_field('new_shoe_down_left');
				if( !empty( $image ) ): ?>
    				<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php endif; ?>
				</a>
		</div>
		</div>
		<a href="catalog">
			<h1 class="h1custom"><?php the_field('title_dress') ?></h1>
		</a>
		<div class="container new_shoes">
		<div class="row div_shoe">
		<?php
			$images = get_field('three_dress');
			foreach ($images as $image):?>
			<div class="col-4 fash">
				<a href="<?php the_permalink(); ?>">
					<img class="three_dress" src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>">
				</a>
			</div>
			<?php endforeach;?>
		</div>
		</div>
		<div><?php echo do_shortcode("[wrvp_recently_viewed_products]"); ?></div>
<?php
do_action( 'storefront_sidebar' );?>

<script>
	jQuery(function($){
	    $(document).ready(function(){
	        var owl = $(".owl-carousel").owlCarousel({
autoplay:true,
autoplayTimeout:3000,
autoplayHoverPause:true,
smartSpeed:800,
loop:true,
margin:10,
nav:false,
responsive:{
	0:{
		items:1
	},
	600:{
		items:1
	},
	1000:{
		items:1
	}
}
});
		})
	})
</script>
<?php if (is_page()) {
	do_action( 'get_footer');
	locate_template(['templates/footer-custom.php'], true );
 } else {
   get_footer();
 } ?>