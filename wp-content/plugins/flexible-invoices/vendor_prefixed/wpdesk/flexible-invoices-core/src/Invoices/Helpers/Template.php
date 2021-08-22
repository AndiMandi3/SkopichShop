<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Template
{
    /**
     * @param string $direction
     *
     * @return mixed|string
     */
    public static function rtl_align(string $direction) : string
    {
        if (\is_rtl() && $direction === 'left') {
            return 'right';
        }
        if (\is_rtl() && $direction === 'right') {
            return 'left';
        }
        return $direction;
    }
}
