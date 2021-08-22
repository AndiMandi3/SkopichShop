<?php

namespace WPDeskFIVendor;

/**
 * File: dates.php
 */
/**
 * @var Document $corrected_invoice
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$corrected_invoice = isset($params['corrected_invoice']) ? $params['corrected_invoice'] : \false;
?>
<table style="float: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
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
echo $correction->get_date_of_sale();
?></strong>
        </td>
    </tr>
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
echo $correction->get_date_of_issue();
?></strong>
        </td>
    </tr>
    <?php 
if ($correction->get_date_of_pay() > 0) {
    ?>
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
    echo $correction->get_date_of_pay();
    ?></strong>
            </td>
        </tr>
    <?php 
}
?>
    <?php 
$paymentMethod = $correction->get_payment_method_name();
?>
    <?php 
if (!empty($paymentMethod)) {
    ?>
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
    echo $paymentMethod;
    ?></strong>
            </td>
        </tr>
    <?php 
}
?>
	<tr>
		<td>
			<?php 
echo \__('Related to invoice:', 'flexible-invoices');
?>
		</td>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<strong><?php 
echo $corrected_invoice->get_formatted_number();
?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
echo \__('Invoice issue date:', 'flexible-invoices');
?>
		</td>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<strong><?php 
echo $corrected_invoice->get_date_of_issue();
?></strong>
		</td>
	</tr>
</table>

<?php 
