<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !function_exists( 'premmerce_filter_admin_variables' ) ) {
    function premmerce_filter_admin_variables( $field, $includeIndividualAttributes = false, $disabled = '' )
    {
        $buttonsInfo = [
            'name'        => __( 'Category name', 'premmerce-filter' ),
            'description' => __( 'Category description', 'premmerce-filter' ),
            'attributes'  => __( 'Attributes', 'premmerce-filter' ),
            'brands'      => __( 'Brands', 'premmerce-filter' ),
            'min_price'   => __( 'Min price', 'premmerce-filter' ),
            'max_price'   => __( 'Max price', 'premmerce-filter' ),
            'count'       => __( 'Number of products', 'premmerce-filter' ),
        ];
        foreach ( $buttonsInfo as $key => $buttonName ) {
            ?>
<button class="button" type="button" data-var="{<?php 
            echo  $key ;
            ?>}" data-field="<?php 
            echo  $field ;
            ?>"
    <?php 
            echo  $disabled ;
            ?>>
    <?php 
            echo  $buttonName ;
            ?>
</button>
<?php 
        }
        ?>

<?php 
        
        if ( $includeIndividualAttributes ) {
            ?>
<select data-var="" data-field="<?php 
            echo  $field ;
            ?>" data-attribute-name-select="" <?php 
            echo  $disabled ;
            ?>>
    <option value=""><?php 
            _e( 'Add attribute name', 'premmerce-filter' );
            ?></option>
</select>

<?php 
        }
    
    }

}
if ( !function_exists( 'premmerce_filter_admin_term_table_row' ) ) {
    function premmerce_filter_admin_term_table_row(
        $attributes,
        $selectedTaxonomy = null,
        $dataTermIds = null,
        $disabled = ''
    )
    {
        ?>
<tr>
    <td>
        <select data-select-taxonomy data-select-two <?php 
        echo  $disabled ;
        ?>>
            <option value=""><?php 
        _e( 'Select taxonomy', 'premmerce-filter' );
        ?></option>
            <?php 
        foreach ( $attributes as $taxonomy => $label ) {
            ?>
            <?php 
            $selected = ( $selectedTaxonomy === $taxonomy ? 'selected' : '' );
            ?>
            <option <?php 
            echo  $selected ;
            ?> value="<?php 
            echo  $taxonomy ;
            ?>"><?php 
            echo  $label ;
            ?></option>
            <?php 
        }
        ?>
        </select>
    </td>
    <td>
        <select <?php 
        echo  $disabled ;
        ?> data-select-term data-select-two multiple data-allow-clear="true"
            data-placeholder="<?php 
        _e( 'Select term', 'premmerce-filter' );
        ?>" data-selected-value="<?php 
        echo  ( $dataTermIds ? htmlspecialchars( json_encode( $dataTermIds ), ENT_QUOTES, 'UTF-8' ) : '' ) ;
        ?>">
            <option value="">
                <?php 
        _e( 'Select term', 'premmerce-filter' );
        ?>
            </option>
        </select>
    </td>
    <td>
        <span class="dashicons dashicons-no-alt remove-icon" data-remove-row></span>
    </td>
</tr>

<?php 
    }

}
if ( !function_exists( 'premmerce_filter_admin_seo_variable_inputs' ) ) {
    function premmerce_filter_admin_seo_variable_inputs( $rule = array(), $disabled = '' )
    {
        $rule = array_merge( [
            'h1'                => '',
            'title'             => '',
            'meta_description'  => '',
            'description'       => '',
            'discourage_search' => false,
            'enabled'           => true,
        ], $rule );
        $endOfTheWord = ( !empty($rule['id']) ? '' : 's' );
        ?>
<div class="premmerce-filter-form">
    <div class="form-field">
        <label for="h1">
            <?php 
        _e( 'H1', 'premmerce-filter' );
        ?>
        </label>
        <input type="text" name="h1" id="rule-h1" value="<?php 
        echo  $rule['h1'] ;
        ?>" <?php 
        echo  $disabled ;
        ?>>
        <?php 
        premmerce_filter_admin_variables( '#rule-h1', true, $disabled );
        ?>
    </div>
    <div class="form-field">
        <label for="title">
            <?php 
        _e( 'Title', 'premmerce-filter' );
        ?>
        </label>
        <input name="title" type="text" id="rule-title" value="<?php 
        echo  $rule['title'] ;
        ?>" <?php 
        echo  $disabled ;
        ?>>
        <?php 
        premmerce_filter_admin_variables( '#rule-title', true, $disabled );
        ?>
    </div>
    <div class="form-field">
        <label for="meta_description">
            <?php 
        _e( 'Meta description', 'premmerce-filter' );
        ?>
        </label>
        <textarea name="meta_description" id="rule-meta-description" cols="30" rows="5"
            <?php 
        echo  $disabled ;
        ?>><?php 
        echo  $rule['meta_description'] ;
        ?></textarea>
        <?php 
        premmerce_filter_admin_variables( '#rule-meta-description', true, $disabled );
        ?>

    </div>
    <div class="form-field">
        <label for="description">
            <?php 
        _e( 'Description', 'premmerce-filter' );
        ?>
        </label>
        <?php 
        //show in free version.
        
        if ( !premmerce_pwpf_fs()->can_use_premium_code() ) {
            ?>
        <textarea name="meta_description" id="rule-description" cols="30" rows="5" <?php 
            echo  $disabled ;
            ?>>
            <?php 
            echo  $rule['description'] ;
            ?>
        </textarea>
        <?php 
        }
        
        ?>
        <?php 
        premmerce_filter_admin_variables( '#rule-description', true, $disabled );
        ?>
    </div>
    <div class="form-field">
        <label>
            <input type="checkbox" name="discourage_search" <?php 
        checked( 1, $rule['discourage_search'] );
        ?>
                <?php 
        echo  $disabled ;
        ?>>
            <?php 
        printf( __( 'Discourage search engines from indexing this page%s', 'premmerce-filter' ), $endOfTheWord );
        ?>
        </label>
        <p class="description">
            <?php 
        _e( 'Enable this rule, if you want to hide this page from Google Search Index and from XML sitemap.', 'premmerce-filter' );
        ?>
        </p>
    </div>
    <div class="form-field">
        <label>
            <input type="checkbox" name="enabled" <?php 
        checked( 1, $rule['enabled'] );
        ?> <?php 
        echo  $disabled ;
        ?>>
            <?php 
        _e( 'Enable', 'premmerce-filter' );
        ?>
        </label>
        <p class="description">
            <?php 
        _e( 'Enable this rule.', 'premmerce-filter' );
        ?>
        </p>
    </div>
</div>

<?php 
    }

}