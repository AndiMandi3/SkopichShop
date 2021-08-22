<?php

namespace WPDesk\FlexibleInvoices\Addons\Templates;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Resolver\DirResolver;
use WPDesk\FlexibleInvoices\Addons\Templates\Fields\Col;
use WPDesk\FlexibleInvoices\Addons\Templates\Fields\ColorPickerField;
use WPDesk\FlexibleInvoices\Addons\Templates\Fields\ResetField;
use WPDesk\FlexibleInvoices\Addons\Templates\Fields\Row;
use WPDesk\FlexibleInvoices\Addons\Templates\Fields\SelectImageField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;

class TemplateSettingsAddon implements Hookable {

	const TAB_NAME  = 'sending';
	const SCREEN_ID = 'inspire_invoice_page_invoices_settings';
	const TAB_ID    = 'invoice-template';

	/**
	 * @var string
	 */
	private $plugin_url;


	public function __construct() {
		$this->plugin_url = plugin_dir_url( __FILE__ );
	}

	/**
	 * Settings constructor.
	 */
	public function hooks() {
		if ( ! Plugin::is_active( 'flexible-invoices-templates/flexible-invoices-templates.php' ) ) {
			add_filter( 'fi/core/settings/settings_template_resolvers', [ $this, 'add_settings_template_resolver' ] );
			add_filter( 'fi/core/settings/tabs/template/fields', [ $this, 'add_fields' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		}

	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @internal You should not use this directly from another application.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		$tab    = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
		if ( $screen->id === self::SCREEN_ID && $tab === self::TAB_ID ) {
			wp_enqueue_style( 'fi-template-addon', $this->plugin_url . 'assets/css/addon.css', '', time() );
		}
	}

	/**
	 * @return int[]
	 */
	public function border_sizes(): array {
		for ( $i = 1; $i <= 4; $i ++ ) {
			$n[ $i ] = $i . 'px';
		}

		return $n;
	}

	/**
	 * @return int[]
	 */
	public function text_font_sizes(): array {
		for ( $i = 8; $i <= 12; $i ++ ) {
			$n[ $i ] = $i . 'px';
		}

		return $n;
	}

	/**
	 * @return int[]
	 */
	public function header_font_sizes(): array {
		for ( $i = 10; $i <= 32; $i ++ ) {
			if ( $i % 2 !== 0 ) {
				continue;
			}
			$n[ $i ] = $i . 'px';
		}

		return $n;
	}

	/**
	 * @return string[]
	 */
	public function font_families(): array {
		return [
			'dejavusans'          => 'DeJaVu Sans',
			'dejavuserif'         => 'DeJaVu Serif',
			'dejavusanscondensed' => 'DeJaVu Sans Condensed',
			'freeserif'           => 'FreeSerif',
			'montserrat'          => 'Montserrat',
			'opensans'            => 'OpenSans',
			'opensanscondensed'   => 'OpenSansCondensed',
			'roboto'              => 'Roboto',
			'robotoslab'          => 'RobotoSlab',
			'rubik'               => 'Rubik',
			'titilliumweb'        => 'TitilliumWeb',
		];
	}

	/**
	 * @return array
	 */
	private function get_layouts(): array {
		return [
			'default' => [
				'name'      => __( 'Default', 'flexible-invoices' ),
				'thumb_src' => $this->plugin_url . '/assets/images/template1_min.jpg',
				'large_src' => $this->plugin_url . '/assets/images/template1_min.jpg',
			],
			'layout1' => [
				'name'      => __( 'Layout no. 1', 'flexible-invoices' ),
				'thumb_src' => $this->plugin_url . '/assets/images/template2_min.jpg',
				'large_src' => $this->plugin_url . '/assets/images/template2_min.jpg',
			],
			'layout2' => [
				'name'      => __( 'Layout no. 2', 'flexible-invoices' ),
				'thumb_src' => $this->plugin_url . '/assets/images/template3_min.jpg',
				'large_src' => $this->plugin_url . '/assets/images/template3_min.jpg',
			],
			'layout3' => [
				'name'      => __( 'Layout no. 3', 'flexible-invoices' ),
				'thumb_src' => $this->plugin_url . '/assets/images/template4_min.jpg',
				'large_src' => $this->plugin_url . '/assets/images/template4_min.jpg',
			],
		];
	}


	/**
	 * @param array $fields
	 *
	 * @return array
	 */
	public function add_fields( array $fields ): array {
		$pro_url = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/zaawansowane-szablony-faktur-woocommerce/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-advanced-templates' : 'https://flexibleinvoices.com/products/advanced-templates-for-flexible-invoices/?utm_source=wp-admin-plugins&utm_medium=button&utm_campaign=flexible-invoices-advanced-templates';

		$submit = $fields['submit'];
		unset( $fields['submit'] );

		$fields['fiat_header'] = ( new Header() )
			->set_name( 'template_headers' )
			->set_label( __( 'Advanced Invoice Template', 'flexible-invoices' ) )
			->set_description( sprintf( '<a target="_blank" href="%1$s" >%2$s</a>', $pro_url, esc_html__( 'To customize PDF layout of your invoices, buy the Advanced Templates for Flexible Invoices add-on &rarr;', 'flexible-invoices' ) ) )
			->set_disabled();

		$fields['template_layout'] = ( new SelectImageField() )
			->set_name( 'template_layout' )
			->set_label( __( 'Layout', 'flexible-invoices' ) )
			->set_options(
				$this->get_layouts()
			)->set_default_value( 'default' )
		     ->set_disabled();

		$fields['template_row_open'] = ( new Row() )->set_name( 'row_open' );

		$fields['template_text'] = ( new Header() )
			->set_name( 'template_text' )
			->set_label( __( 'Text', 'flexible-invoices' ) )
			->set_description( __( 'Document body text.', 'flexible-invoices' ) )
			->set_header_size( '3' )->set_disabled();

		$fields['template_text_font_family'] = ( new SelectField() )
			->set_name( 'template_text_font_family' )
			//->set_label( __( 'Font family', 'flexible-invoices' ) )
			->set_default_value( 'dejavusanscondensed' )
			->set_attribute( 'data-default_value', 'dejavusanscondensed' )
			->set_options(
				$this->font_families()
			)->set_disabled();

		$fields['template_text_font_size'] = ( new SelectField() )
			->set_name( 'template_text_font_size' )
			//->set_label( __( 'Font size', 'flexible-invoices' ) )
			->set_default_value( 8 )
			->set_attribute( 'data-default_value', 8 )
			->set_options(
				$this->text_font_sizes()
			)->set_disabled();

		$fields['template_text_font_color'] = ( new ColorPickerField() )
			->set_name( 'template_text_font_color' )
			//->set_label( __( 'Font color', 'flexible-invoices' ) )
			->set_default_value( '#000000' )
			->set_attribute( 'data-default_value', '#000000' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_col_open3'] = ( new Col() )->set_name( 'col_open' );

		$fields['template_heading1'] = ( new Header() )
			->set_name( 'template_heading1' )
			->set_label( __( 'Heading 1', 'flexible-invoices' ) )
			->set_description( __( 'Invoice number.', 'flexible-invoices' ) )
			->set_header_size( '3' )->set_disabled();

		$fields['template_heading1_font_family'] = ( new SelectField() )
			->set_name( 'template_heading1_font_family' )
			//->set_label( __( 'Font family', 'flexible-invoices' ) )
			->set_default_value( 'dejavusanscondensed' )
			->set_attribute( 'data-default_value', 'dejavusanscondensed' )
			->set_options(
				$this->font_families()
			)->set_disabled();

		$fields['template_heading1_font_size'] = ( new SelectField() )
			->set_name( 'template_heading1_font_size' )
			//->set_label( __( 'Font size', 'flexible-invoices' ) )
			->set_default_value( 18 )
			->set_attribute( 'data-default_value', 18 )
			->set_options(
				$this->header_font_sizes()
			)->set_disabled();

		$fields['template_heading1_font_color'] = ( new ColorPickerField() )
			->set_name( 'template_heading1_font_color' )
			//->set_label( __( 'Font color', 'flexible-invoices' ) )
			->set_default_value( '#000000' )
			->set_attribute( 'data-default_value', '#000000' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_col_open1'] = ( new Col() )->set_name( 'col_open' );

		$fields['template_heading2'] = ( new Header() )
			->set_name( 'template_heading2' )
			->set_label( __( 'Heading 2', 'flexible-invoices' ) )
			->set_description( __( 'Section headers.', 'flexible-invoices' ) )
			->set_header_size( '3' )->set_disabled();

		$fields['template_heading2_font_family'] = ( new SelectField() )
			->set_name( 'template_heading2_font_family' )
			//->set_label( __( 'Font family', 'flexible-invoices' ) )
			->set_default_value( 'dejavusanscondensed' )
			->set_attribute( 'data-default_value', 'dejavusanscondensed' )
			->set_options(
				$this->font_families()
			)->set_disabled();

		$fields['template_heading2_font_size'] = ( new SelectField() )
			->set_name( 'template_heading2_font_size' )
			//->set_label( __( 'Font size', 'flexible-invoices' ) )
			->set_default_value( 12 )
			->set_attribute( 'data-default_value', 12 )
			->set_options(
				$this->header_font_sizes()
			)->set_disabled();

		$fields['template_heading2_font_color'] = ( new ColorPickerField() )
			->set_name( 'template_heading2_font_color' )
			//->set_label( __( 'Font color', 'flexible-invoices' ) )
			->set_default_value( '#000000' )
			->set_attribute( 'data-default_value', '#000000' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_col_open2'] = ( new Col() )->set_name( 'col_open' );


		$fields['template_heading3'] = ( new Header() )
			->set_name( 'template_heading3' )
			->set_label( __( 'Heading 3', 'flexible-invoices' ) )
			->set_description( __( 'Names of columns in the table.', 'flexible-invoices' ) )
			->set_header_size( '3' )->set_disabled();

		$fields['template_heading3_font_family'] = ( new SelectField() )
			->set_name( 'template_heading3_font_family' )
			//->set_label( __( 'Font family', 'flexible-invoices' ) )
			->set_default_value( 'dejavusanscondensed' )
			->set_attribute( 'data-default_value', 'dejavusanscondensed' )
			->set_options(
				$this->font_families()
			)->set_disabled();

		$fields['template_heading3_font_size'] = ( new SelectField() )
			->set_name( 'template_heading3_font_size' )
			//->set_label( __( 'Font size', 'flexible-invoices' ) )
			->set_default_value( 10 )
			->set_attribute( 'data-default_value', 10 )
			->set_options(
				$this->text_font_sizes()
			)->set_disabled();

		$fields['template_heading3_font_color'] = ( new ColorPickerField() )
			->set_name( 'template_heading3_font_color' )
			//->set_label( __( 'Font color', 'flexible-invoices' ) )
			->set_default_value( '#000000' )
			->set_attribute( 'data-default_value', '#000000' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_row_close'] = ( new Row( false ) )->set_name( 'row-close' );


		$fields['template_table_header'] = ( new Header() )
			->set_name( 'template_table_header' )
			->set_label( __( 'Table design', 'flexible-invoices' ) )
			->set_description( __( 'Customize table element styles.', 'flexible-invoices' ) )
			->set_header_size( '3' )->set_disabled();

		$fields['template_table_border_size'] = ( new SelectField() )
			->set_name( 'template_table_border_size' )
			->set_label( __( 'Table border thickness', 'flexible-invoices' ) )
			->set_default_value( 1 )
			->set_attribute( 'data-default_value', 1 )
			->set_options(
				$this->border_sizes()
			)->set_disabled();

		$fields['template_table_border_color'] = ( new ColorPickerField() )
			->set_name( 'template_table_border_color' )
			->set_label( __( 'Table border color', 'flexible-invoices' ) )
			->set_default_value( '#000000' )
			->set_attribute( 'data-default_value', '#000000' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_table_header_bg'] = ( new ColorPickerField() )
			->set_name( 'template_table_header_bg' )
			->set_label( __( 'Table header background', 'flexible-invoices' ) )
			->set_default_value( '#F1F1F1' )
			->set_attribute( 'data-default_value', '#F1F1F1' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_table_rows_even'] = ( new ColorPickerField() )
			->set_name( 'template_table_rows_even' )
			->set_label( __( 'Rows color (even)', 'flexible-invoices' ) )
			->set_default_value( '#FFFFFF' )
			->set_attribute( 'data-default_value', '#FFFFFF' )
			->add_class( 'color-picker' )->set_disabled();

		$fields['template_reset_settings'] = ( new ResetField() )
			->set_label( __( 'Reset appearance', 'flexible-invoices' ) )
			->set_name( 'template_reset_settings' )
			->add_class( 'reset-pdf-template button-secondary' )->set_disabled();

		$fields['submit'] = $submit;

		return $fields;
	}


	/**
	 * Add settings template resolver.
	 *
	 * @param array $resolvers Resolvers.
	 *
	 * @return array
	 */
	public function add_settings_template_resolver( array $resolvers ): array {
		$resolvers[] = new DirResolver( __DIR__ . '/Views' );

		return $resolvers;
	}

}
