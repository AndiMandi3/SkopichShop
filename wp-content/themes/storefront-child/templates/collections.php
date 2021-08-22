<?php
/**
 * Template Name: collections
 *
 */

if (is_page()) {
	do_action( 'get_header');
	locate_template(['templates/header-custom.php'], true );
 } else {
   get_header();
 } ?>
 <a class="chevron_up"></a>
 <div class="container">
 <h1 class="h1custom"><?php the_field( 'titleh1' )?></h1>
        <!-- быстрая навигация -->
        <div class="row bread">
            <div class="col-2 fast">
                <p>Быстрая навигация:</p>
            </div>
            <div class="col-2 fast1">
                <a href="/catalog">КАТАЛОГ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/news-clothes">НОВИНКИ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/sales">CКИДКИ</a>
            </div>
            <div class="col-2 fast1">
                <a href="/collections">КОЛЛЕКЦИИ</a>
            </div>
		</div>
 <div class="container collections">
        <div class="row">
        <?php $image = get_field('collection_1');
				if( !empty( $image ) ): ?>
    				<a class="narod" style="background: url('<?php echo esc_url($image['url']); ?>')" href="<?php echo $image['alt']; ?>">
				<div class="dark_multi">
					<p class="collection_h1"><?php the_field('zagolovok_1'); ?></p>
					<p class="descript"><?php the_field('desc_1'); ?></p>
				</div>
			</a>
		<?php endif; ?>
        </div>
        <div class="row">
        <?php $image = get_field('collection_2');
				if( !empty( $image ) ): ?>
            <a class="nice" style="background: url('<?php echo esc_url($image['url']); ?>')" href="<?php echo $image['alt']; ?>">
				<div class="dark_multi">
					<p class="collection_h1"><?php the_field('zagolovok_2'); ?></p>
					<p class="descript"><?php the_field('desc_2'); ?></p>
				</div>		
			</a>
            <?php endif; ?>
        </div>
        <div class="row">
        <?php $image = get_field('collection_3');
				if( !empty( $image ) ): ?>
            <a class="kanye" style="background: url('<?php echo esc_url($image['url']); ?>')" href="<?php echo $image['alt']; ?>">
				<div class="dark_multi">
					<p class="collection_h1"><?php the_field('zagolovok_3'); ?></p>
					<p class="descript"><?php the_field('desc_3'); ?></p>
				</div>
			</a>
            <?php endif; ?>
        </div>
</div>
<?php
do_action( 'get_footer');
locate_template(['templates/footer-custom.php'], true );
?>
