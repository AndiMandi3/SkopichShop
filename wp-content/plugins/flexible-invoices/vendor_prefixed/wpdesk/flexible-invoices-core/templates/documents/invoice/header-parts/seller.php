<?php

namespace WPDeskFIVendor;

// Seller.php
/**
 * @var \WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$owner = $invoice->get_seller();
?>

<table style="margin-bottom: 0;">
    <tr><td><h2><?php 
echo \__('Seller', 'flexible-invoices');
?>:</h2></td>
    </tr>
    <?php 
if (!empty($owner->get_name())) {
    ?>
    <tr><td><?php 
    echo $owner->get_name();
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_address())) {
    ?>
        <tr><td><?php 
    echo \nl2br($owner->get_address());
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_vat_number()) && !$hideVatNumber) {
    ?>
        <tr><td><?php 
    echo \__('VAT Number', 'flexible-invoices');
    ?>: <?php 
    echo $owner->get_vat_number();
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_bank_name())) {
    ?>
        <tr><td><?php 
    echo \__('Bank', 'flexible-invoices');
    ?>: <?php 
    echo $owner->get_bank_name();
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_bank_account_number())) {
    ?>
        <tr><td><?php 
    echo \__('Account number', 'flexible-invoices');
    ?>: <?php 
    echo $owner->get_bank_account_number();
    ?></td></tr>
    <?php 
}
?>
</table>
<?php 
