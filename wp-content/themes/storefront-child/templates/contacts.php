<?php
/**
 * Template name: contacts
 */

do_action( 'get_header');
locate_template(['templates/header-custom.php'], true );
?>
<h1 class="h1custom"><?php the_field( 'titleh1' )?></h1>
<div class="mapouter container">
<div class="row form_cont">
			</div>
			<div class="row icons">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/geo.svg" >
				<span class="street">г. Новосибирск, ул. Даргомыжского 8а к1</span>
			</div>
			<div class="row icons">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/phone.svg">
				<span class="street-2">+7 (***) *** ** **</span> 
                <span class="street-2">+3 (383) *** ** **</span>
			</div>
			<div class="row icons">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/mail.svg">
				<span class="street-2">Example@example.com</span>
			</div>
			<a href="https://vk.com/svetlanaskopich" target="_blank">
				<img class="icons_cont" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/VK.svg">
			</a>
			<a href="https://instagram.com/svetlanaskopich" target="_blank">
				<img class="icons_cont" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/inst.svg">
			</a>
    <div class="gmap_canvas map">
        <iframe width="877" height="513" id="gmap_canvas" src="https://maps.google.com/maps?q=%D0%94%D0%B0%D1%80%D0%B3%D0%BE%D0%BC%D1%8B%D0%B6%D1%81%D0%BA%D0%BE%D0%B3%D0%BE%208%D0%B0%20%D0%BA1&t=&z=17&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            <a href="https://123movies-to.org">123movies</a>
            <br>
            <style>
            .mapouter{position:relative;text-align:right;height:513px;width:877px;}
            </style>
            <a href="https://www.embedgooglemap.net">google maps widget html</a>
            <style>
            .gmap_canvas {overflow:hidden;background:none!important;height:513px;width:877px;}
            </style>
            </div>
        </div>
 <?php
 do_action( 'get_footer');
 locate_template(['templates/footer-custom.php'], true );
 ?>
