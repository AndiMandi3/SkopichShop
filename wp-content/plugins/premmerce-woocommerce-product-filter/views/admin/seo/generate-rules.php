<?php

if ( ! defined('ABSPATH')) {
    exit;
}

/** @var array $categories */
/** @var array $attributes */
/** @var array $brands */
?>

<div class="wrap">
    <div>
        <a href="<?php echo menu_page_url('premmerce-filter-admin', false) . '&tab=seo' ?>">
            ← <?php _e('Back', 'premmerce-filter') ?>
        </a>
    </div>
    <form data-generation-form method="post">

        <input type="hidden" name="action" value="generation_progress">

        <!--        TODO: REMOVE[DEBUG] fot GET REQUEST-->
        <!--        <input type="hidden" name="tab" value="seo">-->
        <!--        <input type="hidden" name="page" value="premmerce-filter-admin">-->

        <div class="form-wrap">
            <h3><?php _e('Generate rules', 'premmerce-filter'); ?></h3>
            <div class="form-field form-required">
                <label><?php _e('Categories', 'premmerce-filter'); ?></label>
                <select multiple
                        data-generate-select-two
                        placeholder="<?php _e('Select category', 'premmerce-filter'); ?>"
                        name="filter_category[]"
                        style="width: 200px"
                >
                    <?php foreach ($categories as $id => $category): ?>
                        <option value="<?php echo $id ?>"><?php echo $category ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div data-taxonomies-wrapper>
                <div class="premmerce-flex-form-fields">
                    <div class="premmerce-flex-form-field">
                        <label><?php _e('Taxonomies', 'premmerce-filter'); ?></label>
                        <select
                                class="premmerce-filer-bulk-taxonomy"
                                data-generate-select-two
                                data-generate-rule-taxonomy
                                data-select-taxonomy
                                placeholder="<?php _e('Select taxonomy', 'premmerce-filter') ?>"
                                name="filter_taxonomy[1]"
                                style="width: 200px"
                        >
                            <option></option>
                            <?php foreach ($attributes as $taxonomy => $attribute): ?>
                                <option value="<?php echo $taxonomy ?>"><?php echo $attribute ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="premmerce-flex-form-field">
                        <label><?php _e('Terms', 'premmerce-filter'); ?></label>
                        <select multiple
                                name="filter_term[1][]"
                                data-generate-select-two
                                data-select-term
                                style="width: 200px"
                                data-allow-clear="true"
                                data-placeholder="<?php _e('Select term', 'premmerce-filter') ?>"
                                data-selected-value="<?php echo isset($dataTermIds) ? htmlspecialchars(
                                    json_encode($dataTermIds),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) : ''; ?>">
                            <option value="">
                                <?php _e('Select term', 'premmerce-filter') ?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" class="button" data-add-taxonomy-button>
                <?php _e('Add taxonomy', 'premmerce-filter') ?>
            </button>

            <?php premmerce_filter_admin_seo_variable_inputs() ?>
            <button data-generate-button type="submit" class="button"><?php _e(
                    'Generate',
                    'premmerce-filter'
                ); ?>
            </button>
        </div>
    </form>
</div>

<div class="generation-taxonomy" data-taxonomy-prototype data-select-field hidden>
    <div class="premmerce-flex-form-field">
        <label><?php _e('Taxonomies', 'premmerce-filter'); ?></label>
        <div class="generation-taxonomy__select-wrapper">
            <select
                    class="premmerce-filer-bulk-taxonomy"
                    placeholder="<?php _e('Select taxonomy', 'premmerce-filter') ?>"
                    data-select-taxonomy
                    data-generate-rule-taxonomy
                    style="width: 200px"
            >
                <option></option>
                <?php foreach ($attributes as $taxonomy => $attribute): ?>
                    <option value="<?php echo $taxonomy ?>"><?php echo $attribute ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="premmerce-flex-form-field">
        <label><?php _e('Terms', 'premmerce-filter'); ?></label>
        <select multiple
                data-select-term
                style="width: 200px"
                data-allow-clear="true"
                data-placeholder="<?php _e('Select terms', 'premmerce-filter') ?>"
                data-selected-value="">
            <option value="">
                <?php _e('Select term', 'premmerce-filter') ?>
            </option>
        </select>
    </div>
    <div class="premmerce-flex-form-field">
    <span style="margin-top: 35px;" class="remove-icon dashicons dashicons-no-alt"
          data-remove-element="[data-select-field]"></span>
    </div>
</div>

