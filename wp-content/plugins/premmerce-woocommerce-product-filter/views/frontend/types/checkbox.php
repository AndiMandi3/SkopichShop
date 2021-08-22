<?php

use Premmerce\Filter\Filter\ItemRenderer;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * @var \Premmerce\Filter\Filter\Items\Types\TaxonomyFilter $attribute
 */
?>

<div class="filter__properties-list">
    <?php foreach ($attribute->terms as $term): ?>
    <?php $id = 'filter-checkgroup-id-' . $attribute->attribute_name . '-' . $term->slug; ?>
    <div class="filter__properties-item <?php if ($term->checked): ?>filter__properties-item--active<?php endif ?>">
        <div class="filter__checkgroup" data-filter-control-checkgroup>
            <div class="filter__checkgroup-body">
                <div class="filter__checkgroup-link">
                    <input class="filter__checkgroup-control" <?php if ($term->checked): ?>checked<?php endif ?>
                        type="checkbox" data-filter-control id="<?php echo $id ?>"
                        <?php echo $term->count === 0 ? 'disabled' : '' ?>
                        data-premmerce-filter-link="<?php echo $term->link ?>" autocomplete="off">
                    <label class="filter__checkgroup-check" data-filter-control-label for="<?php echo $id ?>"></label>
                    <label class="filter__checkgroup-title <?php echo $term->count === 0 ? 'disabled' : '' ?>"
                        for="<?php echo $id ?>">
                        <?php echo apply_filters('premmerce_filter_render_checkbox_title', $term->name, $term); ?>
                    </label>
                </div>
            </div>
            <div class="filter__checkgroup-aside">
                <?php if($attribute->getSlug() === 'product_cat' && !empty($term->children) && is_array($term->children)): ?>
                <div class="filter__inner-hierarchy-button">
                    <a class="filter__inner-hierarchy-button-open-close" data-hierarchy-button
                        data-hierarchy-id="<?php echo $term->term_id; ?>" href="javascript:void(0);">&plus;</a>
                </div>
                <?php endif; ?>
                <span class="filter__checkgroup-count">
                    <?php echo($term->count); ?>
                </span>
            </div>

        </div>
    </div>

    <?php ItemRenderer::renderRecursiveChildren($this, $term, $attribute, $term->checked, $term->term_id); ?>
    <?php endforeach ?>
</div>
