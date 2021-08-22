<?php

namespace WPDeskFIVendor;

/**
 * File: parts/table.php
 */
$price_label = $hideVat ? \__('Price', 'flexible-invoices') : \__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \__('Amount', 'flexible-invoices') : \__('Net amount', 'flexible-invoices');
$correction_colspan = 6;
?>
<table class="item-table">
	<thead>
	<tr>
		<th><?php 
echo \__('#', 'flexible-invoices');
?></th>
		<th class="item-title"><?php 
echo \__('Name', 'flexible-invoices');
?></th>
		<?php 
if (!$pkwiuEmpty) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 1;
    ?>
			<th><?php 
    echo \__('SKU', 'flexible-invoices');
    ?></th>
		<?php 
}
?>
		<th><?php 
echo \__('Quantity', 'flexible-invoices');
?></th>
		<th><?php 
echo \__('Unit', 'flexible-invoices');
?></th>
		<th><?php 
echo \__('Net price', 'flexible-invoices');
?></th>
		<?php 
if (!$discountEmpty) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 1;
    ?>
			<th><h3><?php 
    echo \__('Discount', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
		<th><?php 
echo \__('Net amount', 'flexible-invoices');
?></th>
		<?php 
if (!$hideVat) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 3;
    ?>
			<th><?php 
    echo \__('Tax rate', 'flexible-invoices');
    ?></th>
			<th><?php 
    echo \__('Tax amount', 'flexible-invoices');
    ?></th>
			<th><?php 
    echo \__('Gross amount', 'flexible-invoices');
    ?></th>
		<?php 
}
?>
	</tr>
	</thead>

	<tbody>
	<tr>
		<td colspan="<?php 
echo $correction_colspan;
?>"><?php 
echo \__('Before correction', 'flexible-invoices');
?></td>
	</tr>
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
    if (isset($item['before_correction']) && $item['before_correction'] == '1') {
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
        echo -1 * $item['quantity'];
        ?></td>
				<td class="unit center"><?php 
        echo $item['unit'];
        ?></td>
				<td class="net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price']);
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
        echo $currency_helper->string_as_money(-1 * $item['net_price_sum']);
        ?></td>
				<?php 
        if (!$hideVat) {
            ?>
					<td class="tax-rate number"><?php 
            echo $item['vat_type_name'];
            ?></td>
					<td class="tax-amount number"><?php 
            echo $currency_helper->string_as_money(-1 * $item['vat_sum']);
            ?></td>
					<td class="total-gross-price number"><?php 
            echo $currency_helper->string_as_money(-1 * $item['total_price']);
            ?></td>
				<?php 
        }
        ?>

				<?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @(float) $total_tax_net_price[$item['vat_type_name']] + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @(float) $total_tax_tax_amount[$item['vat_type_name']] + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @(float) $total_tax_gross_price[$item['vat_type_name']] + $item['total_price'];
        }
        ?>
			</tr>
			<?php 
    }
    ?>
	<?php 
}
?>
	<tr>
		<td colspan="<?php 
echo $correction_colspan;
?>"><?php 
echo \__('After correction', 'flexible-invoices');
?></td>
	</tr>
	<?php 
$index = 0;
?>
	<?php 
foreach ($products as $item) {
    ?>
		<?php 
    if (!isset($item['before_correction'])) {
        $index++;
        ?>
			<tr>
				<td class="center"><?php 
        echo $index;
        ?></td>
				<td><?php 
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
        echo $currency_helper->string_as_money($item['net_price']);
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
        echo $currency_helper->string_as_money($item['net_price_sum']);
        ?></td>
				<?php 
        if (!$hideVat) {
            ?>
					<td class="tax-rate number"><?php 
            echo $item['vat_type_name'];
            ?></td>
					<td class="tax-amount number"><?php 
            echo $currency_helper->string_as_money($item['vat_sum']);
            ?></td>
					<td class="total-gross-price number"><?php 
            echo $currency_helper->string_as_money($item['total_price']);
            ?></td>
				<?php 
        }
        ?>


				<?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @(float) $total_tax_net_price[$item['vat_type_name']] + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @(float) $total_tax_tax_amount[$item['vat_type_name']] + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @(float) $total_tax_gross_price[$item['vat_type_name']] + $item['total_price'];
        }
        ?>
			</tr>
			<?php 
    }
    ?>
	<?php 
}
?>

	</tbody>

	<tfoot>
	<tr class="total">
		<td class="empty">&nbsp;</td>
		<td class="empty">&nbsp;</td>
		<td class="empty">&nbsp;</td>
		<td class="empty">&nbsp;</td>
		<?php 
if (!$pkwiuEmpty) {
    ?>
			<td class="empty">&nbsp;</td>
		<?php 
}
?>
		<?php 
if (!$discountEmpty) {
    ?>
			<td class="empty">&nbsp;</td>
		<?php 
}
?>

		<td class="sum-title"><?php 
echo \__('Total', 'flexible-invoices');
?></td>
		<td class="number"><?php 
echo $currency_helper->string_as_money($total_net_price);
?></td><?php 
// suma "Total net price"
?>
		<?php 
if (!$hideVat) {
    ?>
			<td class="number">X</td><?php 
    // tu zawsze X
    ?>
			<td class="number"><?php 
    echo $currency_helper->string_as_money($total_tax_amount);
    ?></td><?php 
    // suma "Tax amount"
    ?>
			<td class="number"><?php 
    echo $currency_helper->string_as_money($total_gross_price);
    ?></td><?php 
    // suma "Total gross price"
    ?>
		<?php 
}
?>
	</tr>

	<?php 
// poniższe sekcje to rozbicie podatków wg stawek
?>

	<?php 
if (!$hideVat) {
    ?>

		<?php 
    foreach ($total_tax_net_price as $taxType => $price) {
        ?>
			<tr>
				<td class="empty">&nbsp;</td>
				<td class="empty">&nbsp;</td>
				<td class="empty">&nbsp;</td>
				<td class="empty">&nbsp;</td>
				<?php 
        if (!$pkwiuEmpty) {
            ?>
					<td class="empty">&nbsp;</td>
				<?php 
        }
        ?>
				<?php 
        if (!$discountEmpty) {
            ?>
					<td class="empty">&nbsp;</td>
				<?php 
        }
        ?>
				<td class="sum-title"><?php 
        echo \__('Including', 'flexible-invoices');
        ?></td>
				<td class="number"><?php 
        echo $currency_helper->string_as_money($price);
        ?></td><?php 
        // suma "Total net price" dla danej stawki podatkowej
        ?>
				<td class="number"><?php 
        echo $taxType;
        ?></td><?php 
        //tu stawka podatkowa
        ?>
				<td class="number"><?php 
        echo $currency_helper->string_as_money($total_tax_tax_amount[$taxType]);
        ?></td><?php 
        // suma "Tax amount" dla danej stawki podatkowej
        ?>
				<td class="number"><?php 
        echo $currency_helper->string_as_money($total_tax_gross_price[$taxType]);
        ?></td><?php 
        // suma "Total gross price" dla danej stawki podatkowej
        ?>
			</tr>
		<?php 
    }
    ?>

	<?php 
}
?>

	</tfoot>
</table>
<?php 
