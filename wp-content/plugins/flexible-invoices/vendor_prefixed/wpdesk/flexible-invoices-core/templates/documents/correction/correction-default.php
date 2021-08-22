<?php

namespace WPDeskFIVendor;

require __DIR__ . '/correction-header.php';
require __DIR__ . '/parts/header.php';
require __DIR__ . '/parts/table-with-sum.php';
/**
 * Exchange table
 */
$exchange_table = \apply_filters('fi/core/template/invoice/exchange/vertical', '', $correction, $products, $client);
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
			<?php 
    require __DIR__ . '/parts/totals/' . $correction->get_type() . '-vertical.php';
    ?>
		</td>
	</tr>
</table>
<?php 
} else {
    require __DIR__ . '/parts/totals/' . $correction->get_type() . '-horizontal.php';
}
require __DIR__ . '/parts/signatures.php';
require __DIR__ . '/parts/footer.php';
require __DIR__ . '/correction-footer.php';
