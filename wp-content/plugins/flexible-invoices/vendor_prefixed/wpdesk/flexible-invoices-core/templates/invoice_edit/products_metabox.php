<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
$params = isset($params) ? $params : [];
/**
 * @var WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$invoice = $params['invoice'];
$show_discount = $params['show_discount'];
$items = $invoice->get_items();
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>
<div class="form-wrap products_metabox">
	<table class="wp-list-table widefat fixed products">
		<thead>
			<tr>
				<th class="product-title"><?php 
\_e('Product', 'flexible-invoices');
?></th>
				<th class="sku-label"><?php 
\_e('SKU', 'flexible-invoices');
?></th>
				<th class="unit-label"><?php 
\_e('Unit', 'flexible-invoices');
?></th>
				<th class="qty-label"><?php 
\_e('Quantity', 'flexible-invoices');
?></th>
				<th class="net-price-label"><?php 
\_e('Net price', 'flexible-invoices');
?></th>
				<?php 
if ($show_discount && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
    ?>
				<th class="discount-label"><?php 
    \_e('Discount', 'flexible-invoices');
    ?></th>
				<?php 
}
?>
				<th class="net-price-label"><?php 
\_e('Net amount', 'flexible-invoices');
?></th>
				<th class="tax-rate-label"><?php 
\_e('Tax rate', 'flexible-invoices');
?></th>
				<th class="tax-price-label"><?php 
\_e('Tax amount', 'flexible-invoices');
?></th>
				<th class="gross-price-label"><?php 
\_e('Gross amount', 'flexible-invoices');
?></th>
				<th class="product-actions"></th>
			</tr>
		</thead>
		<?php 
$vat_types = $params['vat_types'];
?>
		<tbody class="products_container">
			<?php 
if (!empty($items)) {
    ?>
				<?php 
    foreach ($items as $index => $product) {
        ?>
					<?php 
        $item_name = isset($product['name']) ? $product['name'] : '';
        $item_sku = isset($product['sku']) ? \esc_attr($product['sku']) : '';
        $item_unit = isset($product['unit']) ? \esc_attr($product['unit']) : '';
        $item_qty = isset($product['quantity']) ? \esc_attr($product['quantity']) : '';
        $item_net_price = isset($product['net_price']) ? \esc_attr($product['net_price']) : '';
        $item_discount = isset($product['discount']) ? \esc_attr($product['discount']) : '';
        $item_net_price_sum = isset($product['net_price_sum']) ? \esc_attr($product['net_price_sum']) : '';
        $item_vat_sum = isset($product['vat_sum']) ? \esc_attr($product['vat_sum']) : '';
        $item_total_price = isset($product['total_price']) ? \esc_attr($product['total_price']) : '';
        ?>
					<tr class="product_row">
						<td>
							<div class="product_select_name" style="width: 90%; float: left;">
								<div class="select-product">
									<select name="product[name][]" class="refresh_product wide-input">
										<option value="<?php 
        echo \esc_attr($item_name);
        ?>"><?php 
        \esc_html_e($item_name);
        ?></option>
									</select>
								</div>
							</div>
							<div style="float:right; margin-top: 5px;"><a href="#" class="edit_item_name" title="<?php 
        \_e('Click this icon to enter item name manually', 'flexible-invoices');
        ?>"><span class="dashicons dashicons-edit"></span></a></div>
						</td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[sku][]" class="sku hs-beacon-search" value="<?php 
        echo $item_sku;
        ?>" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[unit][]" class="unit hs-beacon-search" value="<?php 
        echo $item_unit;
        ?>" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[quantity][]" value="<?php 
        echo $item_qty;
        ?>" class="quantity hs-beacon-search refresh_net_price_sum" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[net_price][]" value="<?php 
        echo $item_net_price;
        ?>" class="net_price hs-beacon-search refresh_net_price_sum" /></td>
						<?php 
        if ($show_discount && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
            ?>
						<td class="discount"><input data-beacon_search="<?php 
            echo $document_issuing;
            ?>" type="text" name="product[discount][]" class="hs-beacon-search refresh_vat_sum discount" value="<?php 
            echo $item_discount;
            ?>" /></td>
						<?php 
        }
        ?>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[net_price_sum][]" value="<?php 
        echo $item_net_price_sum;
        ?>" class="hs-beacon-search refresh_vat_sum net_price_sum" /></td>
						<td>
							<?php 
        $vat_type_options = array();
        ?>
							<?php 
        $selected_key = \false;
        ?>
							<?php 
        /* tax with same name and rate? */
        ?>
							<?php 
        foreach ($vat_types as $vat_key => $vat_type) {
            ?>
								<?php 
            $vat_type_options[\implode('|', $vat_type)] = $vat_type['name'];
            ?>
								<?php 
            if (!$selected_key && $vat_type['name'] == $product['vat_type_name'] && \floatval($vat_type['rate']) == \floatval($product['vat_type'])) {
                ?>
									<?php 
                $selected_key = \implode('|', $vat_type);
                ?>
								<?php 
            }
            ?>
							<?php 
        }
        ?>
							<?php 
        if (!$selected_key) {
            ?>
								<?php 
            $selected_key = '-1|' . $product['vat_type'] . '|' . $product['vat_type_name'];
            ?>
								<?php 
            $vat_type_options[$selected_key] = $product['vat_type_name'];
            ?>
							<?php 
        }
        ?>
							<select name="product[vat_type][]" class="refresh_vat_sum">
								<?php 
        foreach ($vat_type_options as $key => $vat_type_option) {
            ?>
									<option value="<?php 
            echo $key;
            ?>" <?php 
            if ($key == $selected_key) {
                ?>selected="selected"<?php 
            }
            ?>><?php 
            echo $vat_type_option;
            ?></option>
								<?php 
        }
        ?>
							</select>
						</td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[vat_sum][]" value="<?php 
        echo $item_vat_sum;
        ?>" class="vat_sum hs-beacon-search refresh_total_price" /></td>
						<td><input data-beacon_search="<?php 
        echo $document_issuing;
        ?>" type="text" name="product[total_price][]" value="<?php 
        echo $item_total_price;
        ?>" class=" total_price hs-beacon-search refresh_total" /></td>

						<td><a class="remove_product" href="#" title="<?php 
        \_e('Delete product', 'flexible-invoices');
        ?>"><span class="dashicons dashicons-no"></span></a></td>
					</tr>
				<?php 
    }
    ?>
			<?php 
}
?>

		</tbody>
	</table>

	<button class="button add_product"><?php 
\_e('Add product', 'flexible-invoices');
?></button>
</div>

<script id="product_prototype" type="text/template">
	<tr class="product_row">
		<td>
			<div class="product_select_name" style="width: 90%; float: left;">
				<div class="select-product">
					<label>
						<select name="product[name][]" class="refresh_product wide-input">
							<option value=""/>
						</select>
					</label>
				</div>
			</div>
			<div style="float:right; margin-top: 5px;">
				<a href="#"
				   class="edit_item_name"
				   title="<?php 
\_e('Click this icon to enter item name manually', 'flexible-invoices');
?>">
					<span class="dashicons dashicons-edit"/>
				</a>
			</div>
		</td>
		<td>
			<label>
				<input
						data-beacon_search="<?php 
echo $document_issuing;
?>"
						class="hs-beacon-search"
						type="text"
						name="product[sku][]"
						value=""
				/>
			</label>
		</td>
		<td>
			<label>
				<input data-beacon_search="<?php 
echo $document_issuing;
?>"
					   class="hs-beacon-search"
					   type="text"
					   name="product[unit][]"
					   value=""
				/>
			</label>
		</td>
		<td>
			<label>
				<input
						data-beacon_search="<?php 
echo $document_issuing;
?>"
						name="product[quantity][]"
						type="text"
						value="1"
						class="refresh_net_price_sum hs-beacon-search"
				/>
			</label>
		</td>
		<td>
			<label>
				<input
						data-beacon_search="<?php 
echo $document_issuing;
?>"
						type="text"
						name="product[net_price][]"
						value="0.0"
						class="hs-beacon-search refresh_net_price_sum"
				/>
			</label>
		</td>
		<?php 
if ($show_discount) {
    ?>
		<td class="discount">
			<label>
				<input
						data-beacon_search="<?php 
    echo $document_issuing;
    ?>"
						class="hs-beacon-search refresh_vat_sum"
						type="text"
						name="product[discount][]"
						value="0.0"
				/>
			</label>
		</td>
		<?php 
}
?>
		<td>
			<label>
				<input
						data-beacon_search="<?php 
echo $document_issuing;
?>"
						type="text"
						name="product[net_price_sum][]"
						value="0.0"
						class="hs-beacon-search refresh_vat_sum"
				/>
			</label>
		</td>
		<td>
			<label>
				<select
						name="product[vat_type][]"
						class="refresh_vat_sum"
						data-beacon_search="<?php 
echo $document_issuing;
?>"
						class="hs-beacon-search"
						value="0.0"
				>
					<?php 
foreach ($vat_types as $index => $vatType) {
    ?>
					<option value="<?php 
    echo \implode('|', $vatType);
    ?>"><?php 
    echo $vatType['name'];
    ?></option>
					<?php 
}
?>
				</select>
			</label>
		</td>
		<td><label>
			<input
					data-beacon_search="<?php 
echo $document_issuing;
?>"
					type="text"
					name="product[vat_sum][]"
					value="0.0"
					class="hs-beacon-search refresh_total_price"
			/>
		</label></td>
		<td><label>
			<input
					data-beacon_search="<?php 
echo $document_issuing;
?>"
					type="text"
					name="product[total_price][]"
					value="0.0"
					class="hs-beacon-search refresh_total"
			/>
		</label></td>

		<td>
			<a class="remove_product" href="#" title="<?php 
\_e('Delete product', 'flexible-invoices');
?>">
				<span class="dashicons dashicons-no"/>
			</a>
		</td>
	</tr>
</script>
<?php 
