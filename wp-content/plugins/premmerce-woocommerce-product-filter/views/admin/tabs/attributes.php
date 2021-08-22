<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Premmerce\Filter\FilterPlugin ;
/**
 * @var array $attributes
 * @var array $attributesConfig
 * @var array $types
 * @var array $actions
 * @var array $display
 * @var array $premiumAttributes
 * @var array $paginationArgs
 */
?>

<h2><?php 
_e( 'Attributes', 'premmerce-filter' );
?></h2>


<div class="tablenav top">
    <?php 
include __DIR__ . '/actions.php';
?>
    <div class="tablenav-pages premmerce-filter-pagination"><?php 
echo  paginate_links( $paginationArgs ) ;
?></div>
</div>

<?php 

if ( $prevId ) {
    ?>
<div class="premmerce-filter-swap-container" data-swap-id="<?php 
    echo  $prevId ;
    ?>">
    <?php 
    _e( 'Move to previous page', 'premmerce-filter' );
    ?>
</div>
<?php 
}

?>
<table class="widefat striped premmerce-filter-table">
    <thead>
        <tr>
            <td width="5%" class="check-column">
                <label for="">
                    <input type="checkbox" data-select-all="attribute">
                </label>
            </td>
            <th width="20%"><?php 
_e( 'Field type', 'premmerce-filter' );
?></th>
            <th width="20%"><?php 
_e( 'Display as', 'premmerce-filter' );
?></th>
            <th width="25%"><?php 
_e( 'Attribute', 'premmerce-filter' );
?></th>
            <th width="20%" class="premmerce-filter-table__align-center">
                <?php 
_e( 'Visibility', 'premmerce-filter' );
?>
            </th>
            <?php 
foreach ( apply_filters( 'premmerce-filter-table-attributes-columns-header', [] ) as $columnArgs ) {
    ?>
            <th width="<?php 
    echo  ( isset( $columnArgs['width'] ) ? $columnArgs['width'] : '10%' ) ;
    ?>" class="premmerce-filter-table__align-<?php 
    echo  ( isset( $columnArgs['align'] ) ? $columnArgs['align'] : 'left' ) ;
    echo  ( isset( $columnArgs['class'] ) ? ' ' . $columnArgs['class'] : '' ) ;
    ?>">
                <?php 
    echo  $columnArgs['label'] ;
    ?>
            </th>
            <?php 
}
?>
            <th width="10%" class="premmerce-filter-table__align-right"></th>
        </tr>
    </thead>
    <tbody data-sortable="premmerce_filter_sort_attributes" data-prev="<?php 
echo  $prevId ;
?>"
        data-next="<?php 
echo  $nextId ;
?>" data-swap="">

        <?php 

if ( count( $attributes ) > 0 ) {
    ?>
        <?php 
    foreach ( $attributes as $id => $label ) {
        //check if attribute is premium on free plan
        $freeDisabled = '';
        
        if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
            $freeDisabled = ( in_array( $id, $premiumAttributes ) ? 'disabled' : '' );
            $premiumLinkText = __( 'Premium', 'premmerce-filter' );
            $premiumLink = '<a class="premmerce-premium-blue" href="' . admin_url( 'admin.php?page=premmerce-filter-admin-pricing' ) . '">' . $premiumLinkText . '</a>';
        }
        
        ?>

        <tr>
            <td>
                <input data-selectable="attribute" type="checkbox" data-id="<?php 
        echo  $id ;
        ?>"
                    <?php 
        echo  $freeDisabled ;
        ?>>
            </td>

            <td>
                <?php 
        $selectTypes = $types;
        //remove image/slider/color types from select for Show on sale / in stock / rating filter (PremiumAttributes)
        if ( in_array( $id, $premiumAttributes, true ) ) {
            $selectTypes = array_diff_key( $types, array_flip( [ FilterPlugin::TYPE_COLOR, FilterPlugin::TYPE_SLIDER, FilterPlugin::TYPE_IMAGE ] ) );
        }
        ?>

                <select data-single-action="premmerce_filter_bulk_action_attributes" data-id="<?php 
        echo  $id ;
        ?>"
                    <?php 
        echo  $freeDisabled ;
        ?>>
                    <?php 
        foreach ( $selectTypes as $key => $type ) {
            ?>
                    <option <?php 
            echo  selected( $key, $attributesConfig[$id]['type'] ) ;
            ?> value="<?php 
            echo  $key ;
            ?>"
                        <?php 
            echo  ( !premmerce_pwpf_fs()->can_use_premium_code() && $type['plan'] == 'premium' ? 'disabled' : '' ) ;
            ?>>
                        <?php 
            echo  $type['text'] ;
            ?>
                    </option>
                    <?php 
        }
        ?>
                </select>

                <?php 
        ?>
            </td>
            <td>
                <select data-single-action="premmerce_filter_bulk_action_attributes" data-id="<?php 
        echo  $id ;
        ?>"
                    <?php 
        echo  $freeDisabled ;
        ?>>
                    <?php 
        foreach ( $display as $key => $type ) {
            ?>
                    <?php 
            $displayValue = substr( $key, strlen( 'display_' ) );
            ?>
                    <option <?php 
            echo  selected( $displayValue, $attributesConfig[$id]['display_type'] ) ;
            ?>
                        value="<?php 
            echo  $key ;
            ?>"
                        <?php 
            echo  ( !premmerce_pwpf_fs()->can_use_premium_code() && $type['plan'] == 'premium' ? 'disabled' : '' ) ;
            ?>>
                        <?php 
            echo  $type['text'] ;
            ?>
                    </option>
                    <?php 
        }
        ?>
                </select>

            </td>
            <td class="premmerce-filter-table__capitalize"><?php 
        echo  $label ;
        ?></td>
            <td class="premmerce-filter-table__align-center">
                <?php 
        $active = $attributesConfig[$id]['active'];
        ?>

                <?php 
        
        if ( $freeDisabled == 'disabled' ) {
            ?>
                <?php 
            echo  $premiumLink ;
            ?>
                <?php 
        } else {
            ?>
                <span data-single-action="premmerce_filter_bulk_action_attributes" data-id="<?php 
            echo  $id ;
            ?>"
                    data-value="<?php 
            echo  ( $active ? 'hide' : 'display' ) ;
            ?>" title="<?php 
            ( $active ? _e( 'Hide', 'premmerce-filter' ) : _e( 'Display', 'premmerce-filter' ) );
            ?>" class="dashicons dashicons-<?php 
            echo  ( $active ? "visibility" : "hidden" ) ;
            ?> click-action-span">
                </span>
                <?php 
        }
        
        ?>

            </td>
            <?php 
        foreach ( apply_filters(
            'premmerce-filter-table-attributes-columns-row',
            [],
            $attributesConfig,
            $id
        ) as $columnArgs ) {
            ?>
            <td class="premmerce-filter-table__align-<?php 
            echo  ( isset( $columnArgs['align'] ) ? $columnArgs['align'] : 'left' ) ;
            echo  ( isset( $columnArgs['class'] ) ? ' ' . $columnArgs['class'] : '' ) ;
            ?>">
                <?php 
            echo  $columnArgs['content'] ;
            ?>
            </td>
            <?php 
        }
        ?>
            <td class="premmerce-filter-table__align-right">
                <?php 
        if ( $freeDisabled != 'disabled' ) {
            ?>
                <span data-sortable-handle class="sortable-handle dashicons dashicons-menu"></span>
                <?php 
        }
        ?>
            </td>
        </tr>
        <?php 
    }
    ?>
        <tr>
            <input type="hidden" name="replace-next">
        </tr>
        <?php 
} else {
    ?>
        <tr>
            <td colspan="5">
                <?php 
    _e( 'No items found', 'premmerce-filter' );
    ?>
            </td>
        </tr>
        <?php 
}

?>
    </tbody>
</table>

<?php 

if ( $nextId ) {
    ?>
<div class="premmerce-filter-swap-container" data-swap-id="<?php 
    echo  $nextId ;
    ?>">
    <?php 
    _e( 'Move to next page', 'premmerce-filter' );
    ?>
</div>
<?php 
}

?>

<div class="tablenav bottom">
    <?php 
include __DIR__ . '/actions.php';
?>
    <div class="tablenav-pages premmerce-filter-pagination"><?php 
echo  paginate_links( $paginationArgs ) ;
?></div>
</div>


