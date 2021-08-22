<?php if ( ! defined('ABSPATH')) {
    exit;
}
/** @var string $primary_color */
?>
<style type="text/css">
    .filter .filter__checkgroup-control:checked + .filter__label-button,
    .filter .filter__checkgroup-control:checked + .filter__checkgroup-check,
    .filter .pc-range-slider__control .ui-slider-range,
    .filter .pc-range-slider__control .ui-slider-handle,
    .filter .filter__properties-item--active .filter__checkgroup-count {
        background: <?php echo $primary_color; ?>;
    }

    .filter .filter__checkgroup-control:checked + .filter__label-button {
        border-color: <?php echo $primary_color; ?>;
    }

    .filter__properties-item:not(.filter__properties-item--active):hover .filter__checkgroup-count {
        background-color: transparent;
        border-color: <?php echo $primary_color; ?>;
    }

    .filter__properties-item:not(.filter__properties-item--active):hover .filter__checkgroup-title {
        color: <?php echo $primary_color; ?>;
    }

    .pc-active-filter .pc-active-filter__item-delete {
        color: <?php echo $primary_color; ?>;
        border-color: <?php echo $primary_color; ?>;
    }
</style>