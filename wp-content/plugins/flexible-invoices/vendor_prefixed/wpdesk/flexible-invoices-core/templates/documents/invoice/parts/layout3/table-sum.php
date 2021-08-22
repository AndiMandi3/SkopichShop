<?php

namespace WPDeskFIVendor;

$col1_styles = 'width:78%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left') . ';';
$col2_styles = 'width:22%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
$table_sum_styles = 'width:300px;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
?>
<table class="table-without-margin">
	<tr>
		<td style="<?php 
echo $col1_styles;
?>">
			<?php 
echo \apply_filters('fi/core/template/invoice/exchange/vertical', '', $invoice, $products, $client);
?>
			<?php 
require \dirname(__DIR__, 2) . '/parts/footer.php';
?>
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
