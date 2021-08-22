<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
$price_label = $hideVat ? \__('Price', 'flexible-invoices') : \__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \__('Amount', 'flexible-invoices') : \__('Net amount', 'flexible-invoices');
$table_sum_width = '300px';
$exchange_table = \apply_filters('fi/core/template/invoice/exchange/vertical', '', $invoice, $products, $client);
if (empty($exchange_table)) {
    $table_sum_width = 'auto';
}
$col1_styles = 'width:78%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left') . ';';
$col2_styles = 'width:22%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
$table_sum_styles = 'width:' . $table_sum_width . ';text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
?>
<table class="table-without-margin">
	<tr>
		<td style="<?php 
echo $col1_styles;
?>">
			<table class="item-table table-without-margin" style="<?php 
echo isset($table_sum_styles) ? $table_sum_styles : '';
?>">
				<thead>
				<tr>
					<th></th>
					<th><h3><?php 
echo $amount_label;
?></h3></th>
					<?php 
if (!$hideVat) {
    ?>
						<th><h3><?php 
    echo \__('Tax rate', 'flexible-invoices');
    ?></h3></th>
						<th><h3><?php 
    echo \__('Tax amount', 'flexible-invoices');
    ?></h3></th>
						<th><h3><?php 
    echo \__('Gross amount', 'flexible-invoices');
    ?></h3></th>
					<?php 
}
?>
				</tr>
				</thead>

				<tbody>
				<tr>
					<td class="sum-title"><?php 
echo \__('Total', 'flexible-invoices');
?></td>
					<td id="total_sum_net_price"
						class="number"><?php 
echo $helper->string_as_money($total_net_price);
?></td>
					<?php 
if (!$hideVat) {
    ?>
						<td class="number">X</td>
						<td id="total_sum_tax_price"
							class="number"><?php 
    echo $helper->string_as_money($total_tax_amount);
    ?></td>
						<td id="total_sum_gross_price"
							class="number"><?php 
    echo $helper->string_as_money($total_gross_price);
    ?></td>
					<?php 
}
?>
				</tr>

				<?php 
if (!$hideVat) {
    ?>
					<?php 
    foreach ($total_tax_net_price as $taxType => $price) {
        ?>
						<tr>
							<td class="sum-title"><?php 
        echo \__('Including', 'flexible-invoices');
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($price);
        ?></td>
							<td class="number"><?php 
        echo $taxType;
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($total_tax_tax_amount[$taxType]);
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($total_tax_gross_price[$taxType]);
        ?></td>
						</tr>
					<?php 
    }
    ?>

				<?php 
}
?>

				</tbody>
			</table>
		</td>
		<td style="<?php 
echo $col2_styles;
?>">
			<?php 
require \dirname(__DIR__, 2) . '/parts/totals/vertical.php';
?>
		</td>
	</tr>
</table>
<?php 
/**
 * Exchange table
 */
if (!empty($exchange_table)) {
    ?>
	<table class="table-without-margin" style="margin-top: 10px;">
		<tr>
			<td style="width:70%">
				<?php 
    echo $exchange_table;
    ?>
			</td>
			<td style="width:30%; padding-left: 10px;">
				&nbsp;
			</td>
		</tr>
	</table>
<?php 
}
?>
<table class="table-without-margin" style="margin-top: 10px;">
	<tr>
		<td>
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
 * @param string                                                       $client_country Currencies.
 * @param bool                                                         $hideVat        Hide vat?.
 * @param bool                                                         $hideVatNumber  Hide vat number?.
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
