<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
/**
 * Invoice Template Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class InvoiceTemplate extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const EDITOR_ROLE = 'editor';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @return string
     */
    private function get_pro_class()
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_pro()) {
            return 'pro-version';
        }
        return '';
    }
    /**
     * @return array
     */
    private function get_signature_users()
    {
        $users = [];
        $site_users = \get_users(['role__in' => [self::ADMIN_ROLE, self::EDITOR_ROLE, self::SHOP_MANAGER_ROLE]]);
        foreach ($site_users as $user) {
            $users[$user->ID] = $user->display_name ? $user->display_name : $user->user_login;
        }
        /**
         * Filters the default signature users passed to select in general settings.
         *
         * @param array $users      An array of prepared users.
         * @param array $site_users An array of site users.
         *
         * @since 1.3.5
         */
        return \apply_filters('fi/core/settings/general/signature_users', $users, $site_users);
    }
    /**
     * @return string[]
     */
    private function get_beacon_translations() : array
    {
        return ['company' => 'Company', 'main' => 'Main Settings', 'woocommerce' => 'Main Settings for WooCommerce'];
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        $beacon = $this->get_beacon_translations();
        $fields['template_header'] = (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Invoice Template', 'flexible-invoices'));
        $fields['hide_vat_number'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat_number')->set_label(\__('Seller\'s VAT Number on Invoices', 'flexible-invoices'))->set_sublabel(\__('If tax is 0 hide seller\'s VAT Number on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search');
        $fields['hide_vat'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('hide_vat')->set_label(\__('Tax Cells on Invoices', 'flexible-invoices'))->set_sublabel(\__('If tax is 0 hide all tax cells on PDF invoices.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search');
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $fields['woocommerce_shipping_address'] = (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('woocommerce_shipping_address')->set_label(\__('Shipping Address', 'flexible-invoices'))->set_description(\__('Enable if you want to show the customer\'s shipping address on the invoice.', 'flexible-invoices'))->set_options(['none' => \__('Do not show', 'flexible-invoices'), 'always' => \__('Show customer\'s address', 'flexible-invoices'), 'ifempty' => \__('Show customer\'s address if different from billing', 'flexible-invoices')])->set_attribute('data-beacon_search', $beacon['woocommerce'])->add_class('hs-beacon-search ' . $this->get_pro_class());
        }
        $fields['woocommerce_get_sku'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_get_sku')->set_label(\__('SKU', 'flexible-invoices'))->set_sublabel(\__('Use SKU numbers on invoices', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['woocommerce'])->add_class('hs-beacon-search');
        $fields['show_discount'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('show_discount')->set_label(\__('Discounts', 'flexible-invoices'))->set_sublabel(\__('Enable to show column with discounts on the invoice.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search ' . $this->get_pro_class());
        $fields['show_signatures'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('show_signatures')->set_label(\__('Show Signatures', 'flexible-invoices'))->set_sublabel(\__('Enable if you want to display place for signatures.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search');
        $fields['signature_user'] = (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('signature_user')->set_label(\__('Seller signature', 'flexible-invoices'))->set_description(\__('Choose a user whose display name will be visible on the invoice in the signature section.', 'flexible-invoices'))->set_options($this->get_signature_users())->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search');
        $fields['pdf_numbering'] = (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('pdf_numbering')->set_label(\__('PDF Numbering', 'flexible-invoices'))->set_sublabel(\__('Enable page numbering.', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search');
        $fields['submit'] = (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\__('Save changes', 'flexible-invoices'))->add_class('button-primary');
        /**
         * Filters invoice template settings fields.
         *
         * @param array $fields Collection of fields.
         * @param array $beacon Beacon strings.
         *
         * @since 1.6.0
         */
        return \apply_filters('fi/core/settings/tabs/template/fields', $fields, $beacon);
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'invoice-template';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('Invoice Template', 'flexible-invoices');
    }
}
