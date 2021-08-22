<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Recipient $recipient
 */
$recipient = $params['recipient'];
?>

<div class="form-wrap inspire-panel invoice-edit-display">
	<div class="display">
		<div class="inspire_invoices_recipient_name"><?php 
\_e('Company Name', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_name();
?></span></div>
		<div class="inspire_invoices_recipient_nip"><?php 
\_e('VAT Number', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_vat_number();
?></span></div>
		<div class="inspire_invoices_recipient_street"><?php 
\_e('Address line 1', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_street();
?></span></div>
		<div class="inspire_invoices_recipient_street2"><?php 
\_e('Address line 2', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_street2();
?></span></div>
		<div class="inspire_invoices_recipient_city"><?php 
\_e('City', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_city();
?></span></div>
		<div class="inspire_invoices_recipient_postcode"><?php 
\_e('Zip code', 'flexible-invoices');
?>: <span><?php 
echo $recipient->get_postcode();
?></span></div>
    </div>
	<div class="edit_data">
		<?php 
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>

		<div class="form-field">
			<label for="inspire_invoices_recipient_name"><?php 
\_e('Name', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_name" type="text" class="medium hs-beacon-search" name="recipient[name]" value="<?php 
echo \esc_attr($recipient->get_name());
?>" />
		</div>

		<div class="form-field">
			<label for="inspire_invoices_recipient_nip"><?php 
\_e('VAT Number', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_nip" type="text" class="medium hs-beacon-search" name="recipient[nip]" value="<?php 
echo \esc_attr($recipient->get_vat_number());
?>" />
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_recipient_street"><?php 
\_e('Address line 1', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_street" type="text" class="medium hs-beacon-search" name="recipient[street]" value="<?php 
echo \esc_attr($recipient->get_street());
?>" />
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_recipient_street2"><?php 
\_e('Address line 2', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_street2" type="text" class="medium hs-beacon-search" name="recipient[street2]" value="<?php 
echo \esc_attr($recipient->get_street2());
?>" />
				</div>
			</div>
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_recipient_city"><?php 
\_e('City', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_city" type="text" class="medium hs-beacon-search" name="recipient[city]" value="<?php 
echo \esc_attr($recipient->get_city());
?>" />
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_recipient_postcode"><?php 
\_e('Zip code', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_recipient_postcode" type="text" class="medium hs-beacon-search" name="recipient[postcode]" value="<?php 
echo \esc_attr($recipient->get_postcode());
?>" />
				</div>
			</div>
		</div>

        <?php 
$fake_option = '';
$countries = [];
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $countries = \WC()->countries->get_countries();
}
$recipient_country = $recipient->get_country();
if (!isset($countries[$recipient_country]) && !empty($recipient_country)) {
    $fake_option = '<option selected="selected" value="' . $recipient_country . '">' . $recipient_country . '</option>';
}
if (empty($recipient_country)) {
    $recipient_country = \get_option('woocommerce_default_country');
}
?>

        <div class="form-field">
            <label for="inspire_invoices_client_country"><?php 
\_e('Country', 'flexible-invoices');
?></label>
            <?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
                <select id="inspire_invoices_client_country" name="client[country]" class="country-select2 medium hs-beacon-search">
                    <?php 
    echo $fake_option;
    ?>
                    <?php 
    foreach ($countries as $country_code => $country_name) {
        ?>
                        <option <?php 
        \selected($country_code, $recipient_country);
        ?> value="<?php 
        echo $country_code;
        ?>"><?php 
        echo $country_name;
        ?></option>
                    <?php 
    }
    ?>
                </select>
            <?php 
} else {
    ?>
                <input id="inspire_invoices_client_country" type="text" class="medium" name="client[country]" value="<?php 
    echo \esc_attr($recipient_country);
    ?>" />
            <?php 
}
?>
        </div>
	</div>
</div>
<?php 
