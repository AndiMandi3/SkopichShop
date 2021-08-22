<?php
?>

	<tr class="rr_form_row">
		<td class="rr_form_heading <?php if($require){ echo 'rr_required'; } ?>" >
			<?php echo __($label, 'rich-reviews'); ?>
		</td>
		<td class="rr_form_input">
			<?php echo '<span class="form-err">' . __($error, 'rich-reviews') . '</span>'; ?>
			<input class="rr_small_input" type="text" name="r<?php echo esc_html($inputId); ?>" value="<?php echo esc_html($rFieldValue) ; ?>" />
		</td>
	</tr>