<?php

namespace WPDeskFIVendor;

/**
 * Scoper fix
 */
/**
 * @var \WPDesk\Forms\Form\FormWithFields $form
 */
?>
<form class="wrap woocommerce" method="<?php 
echo \esc_attr($form->get_method());
?>" action="<?php 
echo \esc_attr($form->get_action());
?>">
	<h2 style="display:none;"></h2><?php 
// All admin notices will be moved here by WP js
?>
<div class="inspire-settings">
<div class="inspire-main-content">
	<table class="form-table">
		<tbody>
<?php 
