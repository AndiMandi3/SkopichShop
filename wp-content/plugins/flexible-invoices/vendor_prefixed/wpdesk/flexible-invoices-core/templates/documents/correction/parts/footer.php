<?php

namespace WPDeskFIVendor;

/**
 * File: parts/footer.php
 */
$layout_name = isset($layout_name) ? $layout_name : 'default';
?>
<table id="footer" class="table-without-margin" style="margin-top: 10px;">
    <tr>
        <td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
			<?php 
$note = $correction->get_notes();
?>
			<?php 
if (!empty($note)) {
    ?>
				<p><strong><?php 
    echo \__('Notes', 'flexible-invoices');
    ?></strong></p>
				<p><?php 
    echo \str_replace(\PHP_EOL, '<br/>', $note);
    ?></p>
			<?php 
}
?>
			<?php 
if ($layout_name !== 'default') {
    ?>
			<p><?php 
    echo \__('Related to invoice:', 'flexible-invoices');
    ?> <strong><?php 
    echo $corrected_invoice->get_formatted_number();
    ?></strong></p>
			<p><?php 
    echo \__('Invoice issue date:', 'flexible-invoices');
    ?> <strong><?php 
    echo $corrected_invoice->get_date_of_issue();
    ?></strong></p>
			<?php 
}
?>
			<?php 
\do_action('flexible_invoices_after_notes', $client_country, $hideVat, $hideVatNumber, $correction);
?>
			<?php 
\do_action('fi/core/template/correction/after_notes', $correction);
?>
        </td>
    </tr>
</table>
<?php 
