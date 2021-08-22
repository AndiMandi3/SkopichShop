<?php

namespace WPDesk\FlexibleInvoices\Addons\Templates\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;

/**
 * Color picker field.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class ColorPickerField extends InputTextField {

	/**
	 * @return string
	 */
	public function get_template_name() {
		return 'color-picker-input';
	}

}
