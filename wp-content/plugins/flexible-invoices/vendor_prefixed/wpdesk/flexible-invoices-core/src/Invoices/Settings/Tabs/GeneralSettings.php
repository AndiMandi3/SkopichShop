<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ImageInputField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField;
/**
 * General Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class GeneralSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const CUSTOMER_ROLE = 'customer';
    const SUBSCRIBER_ROLE = 'subscriber';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @return array
     */
    public function get_roles()
    {
        $roles = \wp_roles()->get_names();
        unset($roles[self::ADMIN_ROLE]);
        unset($roles[self::CUSTOMER_ROLE]);
        unset($roles[self::SUBSCRIBER_ROLE]);
        return (array) $roles;
    }
    /**
     * @return string
     */
    private function get_default_payment_methods()
    {
        return \implode("\n", array('bank-transfer' => \__('Bank transfer', 'flexible-invoices'), 'cash' => \__('Cash', 'flexible-invoices'), 'other' => \__('Other', 'flexible-invoices')));
    }
    /**
     * @return string[]
     */
    private function get_beacon_translations() : array
    {
        return ['company' => 'Company', 'main' => 'Main Settings'];
    }
    /**
     * @return array|\WPDesk\Forms\Field[]
     */
    protected function get_fields()
    {
        $docs_link = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/' : 'https://docs.flexibleinvoices.com/';
        $docs_link .= '?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link';
        $beacon = $this->get_beacon_translations();
        return ['company_header' => (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('Company', 'flexible-invoices'))->set_description(\sprintf('<a href="%s" target="_blank">' . \__('Read user\'s manual →', 'flexible-invoices') . '</a>', $docs_link)), 'company_name' => (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_name')->set_label(\__('Company Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), 'company_address' => (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('company_address')->set_label(\__('Company Address', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('large-text hs-beacon-search'), 'company_nip' => (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_nip')->set_label(\__('VAT Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), 'bank_name' => (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('bank_name')->set_label(\__('Bank Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), 'account_number' => (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('account_number')->set_label(\__('Bank Account Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), 'company_logo' => (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\ImageInputField())->set_name('company_logo')->set_label(\__('Logo', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), 'general_header' => (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\__('General Settings', 'flexible-invoices')), 'payment_methods' => (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('payment_methods')->set_label(\__('Payment Methods', 'flexible-invoices'))->set_default_value($this->get_default_payment_methods())->add_class('input-text wide-input hs-beacon-search')->set_attribute('data-beacon_search', $beacon['main']), 'roles' => (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('roles')->set_label(\__('Roles', 'flexible-invoices'))->set_description(\__('Select the User Roles that will be given permission to manage Invoices. The administrator has unlimited permissions.', 'flexible-invoices'))->set_options($this->get_roles())->add_class('select2')->set_multiple()->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), 'submit' => (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\__('Save changes', 'flexible-invoices'))->add_class('button-primary')];
    }
    /**
     * @return string
     */
    public static function get_tab_slug()
    {
        return 'general';
    }
    /**
     * @return string
     */
    public function get_tab_name()
    {
        return \__('General', 'flexible-invoices');
    }
}
