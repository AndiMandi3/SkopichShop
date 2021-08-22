<?php

if (! defined('ABSPATH')) {
    exit;
}

/** @var array $attributes */
/** @var array $categoriesDropDownArgs */

$select   = wp_dropdown_categories($categoriesDropDownArgs);
$disabled = '';

//disable in free
if (!premmerce_pwpf_fs()->can_use_premium_code()) {
    $select   = str_replace('<select ', '<select disabled ', $select);
    $disabled = 'disabled';
}

?>
<div class="wrap">
    <div class="form-wrap">
        <form method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <?php if (empty($rule['id'])): ?>
            <input type="hidden" name="action" value="premmerce_filter_seo_create">
            <h3><?php _e('Add new rule', 'premmerce-filter'); ?></h3>
            <?php else: ?>
            <div>
                <a href="<?php echo menu_page_url('premmerce-filter-admin', false) . '&tab=seo' ?>">
                    ← <?php _e('Back', 'premmerce-filter') ?>
                </a>
                <a class="rule-edit-visit" target="_blank"
                    href="<?php echo apply_filters('wpml_permalink', home_url($rule['path'])) ?>">
                    <?php _e('Visit page', 'premmerce-filter') ?>
                </a>
            </div>
            <input type="hidden" name="action" value="premmerce_filter_seo_update">
            <input type="hidden" name="id" value="<?php echo $rule['id'] ?>">
            <h3><?php _e('Update rule', 'premmerce-filter'); ?></h3>
            <?php endif; ?>

            <div class="form-field form-required">
                <label><?php _e('Category', 'premmerce-filter'); ?></label>
                <?php echo $select; ?>
            </div>

            <table class="widefat rule-term-table" data-term-table>
                <thead>
                    <tr>
                        <th><?php echo __('Taxonomy', 'premmerce-filter') ?></th>
                        <th><?php echo __('Term', 'premmerce-filter') ?></th>
                        <th></th>
                    </tr>

                </thead>
                <tbody data-row-container>
                    <?php if (empty($rule['terms'])): ?>
                    <?php premmerce_filter_admin_term_table_row($attributes, null, null, $disabled) ?>
                    <?php else: ?>
                    <?php foreach ($rule['terms'] as $selectedTaxonomy => $dataTermIds): ?>
                    <?php premmerce_filter_admin_term_table_row($attributes, $selectedTaxonomy, $dataTermIds, $disabled) ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3">
                            <button data-add-row type="button" class="button" <?php echo $disabled; ?>>
                                <?php _e('Add', 'premmerce-filter'); ?>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php premmerce_filter_admin_seo_variable_inputs($rule, $disabled) ?>

            <?php if (empty($rule['id'])): ?>
            <?php submit_button(__('Add new rule', 'premmerce-filter'), 'primary', 'submit', true, $disabled); ?>
            <?php else: ?>
            <?php submit_button(__('Update rule', 'premmerce-filter'), 'primary', 'submit', true, $disabled); ?>
            <?php endif; ?>
        </form>
    </div>


    <div class="hidden">
        <table data-prototype-table>
            <?php premmerce_filter_admin_term_table_row($attributes, null, null, $disabled) ?>
        </table>
    </div>
</div>
