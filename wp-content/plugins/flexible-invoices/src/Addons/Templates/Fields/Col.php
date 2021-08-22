<?php

namespace WPDesk\FlexibleInvoices\Addons\Templates\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;

/**
 * Template col.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class Col extends BasicField {

	/**
	 * @return string
	 */
	public function get_template_name() {
		return 'col';
	}

}
