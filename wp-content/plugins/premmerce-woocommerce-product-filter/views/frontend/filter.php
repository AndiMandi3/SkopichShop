<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Premmerce\Filter\Widget\FilterWidget ;
use  Premmerce\Filter\Shortcodes\FilterWidgetShortcodes ;
$dropdownList = [ 'dropdown', 'scroll_dropdown', 'dropdown_hover' ];
$scrollList = [ 'scroll', 'scroll_dropdown' ];
?>

<?php 
echo  ( !empty($args['before_widget']) ? $args['before_widget'] : '' ) ;
?>

<?php 
if ( !empty($instance['title']) ) {
    echo  $args['before_title'] . $instance['title'] . $args['after_title'] ;
}
?>

<div class="filter filter--style-<?php 
echo  $style ;
?> premmerce-filter-body" data-premmerce-filter>
    <?php 
foreach ( $attributes as $attribute ) {
    do_action_ref_array( 'premmerce_filter_render_item_before', [ &$attribute ] );
    $filterItemAdditionalClasses = '';
    $filterItemAdditionalClasses .= ' filter__item-' . $attribute->display_type;
    $filterItemAdditionalClasses .= ' filter__item--type-' . $attribute->html_type . $border;
    $filterItemAdditionalClasses .= ( $attribute->has_checked ? ' filter__item--has-checked' : '' );
    ?>

    <div class="filter__item <?php 
    echo  $filterItemAdditionalClasses ;
    ?>" data-premmerce-filter-drop-scope>
        <?php 
    $dropdown = in_array( $attribute->display_type, $dropdownList );
    $scroll = in_array( $attribute->display_type, $scrollList );
    ?>

        <div class="filter__header filter__header-<?php 
    echo  $attribute->display_type ;
    ?>" <?php 
    echo  ( $dropdown ? 'data-premmerce-filter-drop-handle' : '' ) ;
    ?>>
            <div class="filter__title <?php 
    echo  $boldTitle . ' ' . $titleAppearance ;
    ?>">
                <?php 
    echo  apply_filters( 'premmerce_filter_render_item_title', $attribute->attribute_label, $attribute ) ;
    ?>
            </div>
            <?php 
    do_action( 'premmerce_filter_render_item_after_title', $attribute );
    ?>
        </div>
        <?php 
    $filterInnerAdditionalClasses = '';
    $filterInnerAdditionalClasses .= 'filter__inner-' . $attribute->display_type;
    $filterInnerAdditionalClasses .= ( $dropdown && !$attribute->has_checked ? ' filter__inner--js-hidden' : '' );
    $filterInnerAdditionalClasses .= ( $scroll ? ' filter__inner--scroll' : '' );
    ?>
        <div class="filter__inner <?php 
    echo  $filterInnerAdditionalClasses ;
    ?>" data-premmerce-filter-inner <?php 
    echo  ( $scroll ? 'data-filter-scroll' : '' ) ;
    ?>>
            <?php 
    do_action( 'premmerce_filter_render_item_' . $attribute->html_type, $attribute );
    ?>
        </div>
    </div>
    <?php 
    do_action_ref_array( 'premmerce_filter_render_item_after', [ &$attribute ] );
    ?>
    <?php 
}
?>
    <?php 

if ( $showFilterButton ) {
    ?>
    <div class="filter__item filter__item--type-submit-button">
        <?php 
    do_action( 'premmerce_filter_submit_button_before' );
    ?>
        <button data-filter-button data-filter-url="" type="button" class="button button-filter-submit">
            <?php 
    echo  apply_filters( 'premmerce_filter_submit_button_label', __( 'Filter', 'premmerce-filter' ) ) ;
    ?>
        </button>
        <?php 
    do_action( 'premmerce_filter_submit_button_after' );
    ?>
    </div>
    <?php 
}

?>
</div>

<?php 
echo  ( !empty($args['after_widget']) ? $args['after_widget'] : '' ) ;
?>

