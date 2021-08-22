<?php

namespace WPDeskFIVendor;

/**
 * File: dates.php
 */
?>
<table style="float: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
	<?php 
if ($invoice->get_type() !== 'proforma') {
    ?>
    <tr>
        <td>
            <?php 
    echo \trim($translator::translate_meta('inspire_invoices_invoice_date_of_sale_label', \__('Date of sale', 'flexible-invoices')));
    ?>:
        </td>
        <td style="padding-<?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
    ?>: 10px; text-align: <?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
    ?>;">
            <strong><?php 
    echo $invoice->get_date_of_sale();
    ?></strong>
        </td>
    </tr>
	<?php 
}
?>
    <tr>
        <td>
            <?php 
echo \__('Issue date', 'flexible-invoices');
?>:
        </td>
        <td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
            <strong><?php 
echo $invoice->get_date_of_issue();
?></strong>
        </td>
    </tr>
	<tr>
		<td>
			<?php 
echo \__('Due date', 'flexible-invoices');
?>:
		</td>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<strong><?php 
echo $invoice->get_date_of_pay();
?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
echo \__('Payment method', 'flexible-invoices');
?>:
		</td>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<strong><?php 
echo $invoice->get_payment_method_name();
?></strong>
		</td>
	</tr>
</table>
<?php 
