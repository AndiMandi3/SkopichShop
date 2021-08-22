<?php

namespace WPDeskFIVendor;

/**
 * File: parts/footer.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$layout_name = isset($layout_name) ? $layout_name : 'default';
?>
<table id="footer" class="table-without-margin" style="margin-top: 10px;">
    <tr>
        <td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
			<?php 
$note = $invoice->get_notes();
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
/**
 * Fire hook after notes
 *
 * @param string   $client_country Currencies.
 * @param bool     $hideVat        Hide vat?.
 * @param bool     $hideVatNumber  Hide vat number?.
 * @param Document $invoice        Invoice.
 *
 * @deprecated
 *
 * @since 3.0.0
 */
\do_action('flexible_invoices_after_notes', $client_country, $hideVat, $hideVatNumber, $invoice);
/**
 * Fire hook after notes
 *
 * @param Document $invoice Invoice.
 *
 * @since 3.0.0
 */
\do_action('fi/core/template/invoice/after_notes', $invoice);
?>

			<?php 
if ($invoice->get_show_order_number()) {
    ?>
				<?php 
    $order = $invoice->get_order_number();
    ?>
				<p><?php 
    echo \__('Order number', 'flexible-invoices');
    ?>: <?php 
    echo $invoice->get_order_number();
    ?></p>
			<?php 
}
?>
        </td>
    </tr>
</table>
<?php 
