<?php

namespace WPDeskFIVendor;

/**
 * File: parts/table.php
 */
$price_label = $hideVat ? \__('Price', 'flexible-invoices') : \__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \__('Amount', 'flexible-invoices') : \__('Net amount', 'flexible-invoices');
?>
<table class="item-table">
	<thead>
	<tr>
		<th><h3><?php 
echo \__('#', 'flexible-invoices');
?></h3></th>
		<th class="item-title"><h3><?php 
echo \__('Name', 'flexible-invoices');
?></h3></th>
		<?php 
if (!$pkwiuEmpty) {
    ?>
			<th><h3><?php 
    echo \__('SKU', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
		<th><h3><?php 
echo \__('Quantity', 'flexible-invoices');
?></h3></th>
		<th><h3><?php 
echo \__('Unit', 'flexible-invoices');
?></h3></th>
		<th><h3><?php 
echo $price_label;
?></h3></th>
		<?php 
if (!$discountEmpty) {
    ?>
			<th><h3><?php 
    echo \__('Discount', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
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
	<?php 
$index = 0;
$total_tax_amount = 0;
$total_net_price = 0;
$total_gross_price = 0;
$total_tax_net_price = array();
$total_tax_tax_amount = array();
$total_tax_gross_price = array();
?>
	<?php 
foreach ($products as $item) {
    ?>
		<?php 
    $index++;
    ?>
		<tr>
			<td class="center"><?php 
    echo $index;
    ?></td>
			<td class="left"><?php 
    echo $item['name'];
    ?></td>
			<?php 
    if (!$pkwiuEmpty) {
        ?>
				<td><?php 
        if (isset($item['sku'])) {
            echo \wordwrap($item['sku'], 6, "\n", \true);
        }
        ?></td>
			<?php 
    }
    ?>
			<td class="quantity number"><?php 
    echo $item['quantity'];
    ?></td>
			<td class="unit center"><?php 
    echo $item['unit'];
    ?></td>
			<td class="net-price number"><?php 
    echo $helper->string_as_money($item['net_price']);
    ?></td>
			<?php 
    if (!$discountEmpty) {
        ?>
				<td><?php 
        if (isset($item['discount'])) {
            echo $helper->discount_price($item);
        }
        ?></td>
			<?php 
    }
    ?>

			<td class="total-net-price number"><?php 
    echo $helper->string_as_money($item['net_price_sum']);
    ?></td>
			<?php 
    if (!$hideVat) {
        ?>
				<td class="tax-rate number"><?php 
        echo $item['vat_type_name'];
        ?></td>
				<td class="tax-amount number"><?php 
        echo $helper->string_as_money($item['vat_sum']);
        ?></td>
				<td class="total-gross-price number"><?php 
        echo $helper->string_as_money($item['total_price']);
        ?></td>
			<?php 
    }
    ?>
		</tr>
		<?php 
    $total_net_price += $item['net_price_sum'];
    $total_tax_amount += $item['vat_sum'];
    $total_gross_price += $item['total_price'];
    if (!empty($item['vat_type_name'])) {
        $total_tax_net_price[$item['vat_type_name']] = @\floatval($total_tax_net_price[$item['vat_type_name']]) + $item['net_price_sum'];
        $total_tax_tax_amount[$item['vat_type_name']] = @\floatval($total_tax_tax_amount[$item['vat_type_name']]) + $item['vat_sum'];
        $total_tax_gross_price[$item['vat_type_name']] = @\floatval($total_tax_gross_price[$item['vat_type_name']]) + $item['total_price'];
    }
    ?>
	<?php 
}
?>

	</tbody>
</table>
<?php 
