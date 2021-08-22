<?php

namespace WPDeskFIVendor;

// Seller.php
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries;
$output_street = '';
if (!empty($client->get_street())) {
    $output_street .= '<span>' . $client->get_street() . '</span><br/>';
}
if (!empty($client->get_street2())) {
    $output_street .= '<span>' . $client->get_street2() . '</span><br/>';
}
$client_street = \apply_filters('fi/core/template/invoice/client/street', $output_street, $client);
?>
<table style="margin-bottom: 0;">
    <tr><td><h2><?php 
echo \__('Buyer', 'flexible-invoices');
?>:</h2></td></tr>
    <?php 
if (!empty($client->get_name())) {
    ?>
    <tr><td><?php 
    echo $client->get_name();
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($client_street)) {
    ?>
        <tr><td><?php 
    echo $client_street;
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($client->get_postcode()) || !empty($client->get_city())) {
    ?>
        <tr><td><?php 
    echo $client->get_postcode();
    ?> <?php 
    echo $client->get_city();
    ?></td></tr>
        <tr><td><?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_label($client->get_country());
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($client->get_city())) {
    ?>
        <tr><td></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($client->get_vat_number())) {
    ?>
        <tr><td><?php 
    echo \__('VAT Number', 'flexible-invoices');
    ?>: <?php 
    echo $client->get_vat_number();
    ?></td></tr>
    <?php 
}
?>
</table>
<?php 
