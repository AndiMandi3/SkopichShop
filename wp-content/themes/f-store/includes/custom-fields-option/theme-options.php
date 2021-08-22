<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make( 'theme_options', 'Настройки темы' )
    ->set_icon( 'dashicons-carrot' )
    ->add_tab( __( 'Шапка' ), array(
        Field::make( 'image', 'fst_header_logo', __( 'Логотип' ) )
    ->set_width( 30 ),
        Field::make( 'text', 'crb_last_name', __( 'Last Name' ) )
    ->set_width( 70 ),
    ) )
    ->add_tab( __( 'Подвал' ), array(
        Field::make( 'text', 'crb_email', __( 'Notification Email' ) ),
        Field::make( 'text', 'crb_phone', __( 'Phone Number' ) ),
    ) );
