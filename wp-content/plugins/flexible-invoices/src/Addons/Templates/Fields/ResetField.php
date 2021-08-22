<?php

namespace WPDesk\FlexibleInvoices\Addons\Templates\Fields;;

use WPDeskFIVendor\WPDesk\Forms\Field\NoValueField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;

/**
 * Reset settings field.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class ResetField extends SubmitField {

	/**
	 * @return string
	 */
	public function get_type() {
		return 'button';
	}

}
