<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
$price_label = $hideVat ? \__('Price', 'flexible-invoices') : \__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \__('Amount', 'flexible-invoices') : \__('Net amount', 'flexible-invoices');
$table_sum_width = '300px';
$exchange_table = \apply_filters('fi/core/template/invoice/exchange/vertical', '', $correction, $products, $client);
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
require __DIR__ . '/totals/' . $correction->get_type() . '-vertical.php';
?>
		</td>
	</tr>
</table>
<?php 
/**
 * Exchange table
 */
$exchange_table = \apply_filters('fi/core/template/invoice/exchange/vertical', '', $correction, $products, $client);
if (!empty($exchange_table)) {
    ?>
	<table class="table-without-margin" style="margin-top: 10px;">
		<tr>
			<td style="<?php 
    echo $col1_styles;
    ?>">
				<?php 
    echo $exchange_table;
    ?>
			</td>
			<td style="<?php 
    echo $col2_styles;
    ?>">
				&nbsp;&nbsp;
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
\do_action('flexible_invoices_after_notes', $client_country, $hideVat, $hideVatNumber, $correction);
?>
			<?php 
\do_action('fi/core/template/correction/after_notes', $correction);
?>
		</td>
	</tr>
</table>

<?php 
