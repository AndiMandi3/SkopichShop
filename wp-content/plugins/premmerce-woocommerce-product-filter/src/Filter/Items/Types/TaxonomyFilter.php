<?php namespace Premmerce\Filter\Filter\Items\Types;

use stdClass;
use WP_Taxonomy;
use Premmerce\Filter\FilterPlugin;

class TaxonomyFilter extends BaseFilter
{
    /**
     * @var WP_Taxonomy
     */
    protected $taxonomy;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var stdClass[]
     */
    protected $terms;

    /**
     * @var bool
     */
    protected $hideEmpty;

    /**
     * @var
     */
    protected $config;

    /**
     * TaxonomyFilter constructor.
     *
     * @param $config
     * @param $taxonomy
     */
    public function __construct($config, $taxonomy)
    {
        $this->config    = $config;
        $this->taxonomy  = $taxonomy;
        $this->hideEmpty = !empty($config['hide_empty']);

        if (in_array($this->getType(), ['radio', 'select'])) {
            $this->single = true;
        }

        $this->slug = taxonomy_is_product_attribute($this->getId()) ? substr($this->getId(), 3) : $this->getId();

        add_filter('woocommerce_product_query_tax_query', [$this, 'extendTaxQuery']);
    }

    /**
     * Unique item identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->taxonomy->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if (taxonomy_is_product_attribute($this->taxonomy->name)) {
            return wc_attribute_label($this->taxonomy->name);
        }

        return $this->taxonomy->labels->singular_name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * checkbox|radio|select|label|color
     * @return string
     */
    public function getType()
    {
        return isset($this->config['type']) ? $this->config['type'] : '';
    }

    /**
     * Default|Dropdown|Scroll|Dropdown+Scroll
     * @return string
     */
    public function getDisplay()
    {
        return isset($this->config['display_type']) ? $this->config['display_type'] : '';
    }


    /**
     * @return bool
     */
    public function isVisible()
    {
        $filters = $this->getItems();

        return !empty($filters);
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $filters = [];

        foreach ($this->getTerms() as $termKey => $term) {
            $displayCurrent = !empty($term->children) || apply_filters(
                    'premmerce_filter_display_current_term_filter',
                    false
                );

            if (!$this->hideEmpty || $term->count || $term->checked || $displayCurrent) {
                $filters[] = $term;
            }
        }

        return $filters;
    }

    /**
     * @return array
     */
    public function getTerms()
    {
        return $this->terms ?: [];
    }

    /**
     * @param $term
     *
     * @return mixed
     */
    protected function processTerm($term)
    {
        if ($this->getType() === FilterPlugin::TYPE_COLOR) {
            $term->color = null;
            if (isset($this->config['colors'][$term->term_id])) {
                $term->color = $this->config['colors'][$term->term_id];
            }
        } elseif ($this->getType() === FilterPlugin::TYPE_IMAGE) {
            $term->image = null;
            if (isset($this->config['images'][$term->term_id])) {
                $term->image = $this->config['images'][$term->term_id];
            }
        } elseif ($this->getSlug() === 'product_cat') {
            $term->children = $this->getCategoryChildren($term);
        }

        return $term;
    }

    /**
     * @param \WP_Term $term
     * @return array
     */
    protected function getCategoryChildren($term)
    {
        $taxonomyChildren = array_map('get_term', get_term_children($term->term_id, $this->getSlug()));
        $children = [];
        $settings = get_option(FilterPlugin::OPTION_SETTINGS, []);
        if (!empty($settings['enable_category_hierarchy'])) {
            foreach ($taxonomyChildren as $taxonomyChild) {
                if ($this->hideEmpty && $taxonomyChild->count === 0) {
                    continue;
                }

                if ($taxonomyChild->parent === $term->term_id) {
                    $taxonomyChild->checked = in_array($taxonomyChild->slug, $this->getSelectedValues(), true);
                    $taxonomyChild->link = $this->getValueLink($taxonomyChild->slug);
                    $taxonomyChild->isChild = true;
                    $taxonomyChild->products = [];
                    $this->processTerm($taxonomyChild);
                    $children[] = $taxonomyChild;
                }
            }
        }

        return $children;
    }

    /**
     * Active items for Active filters Widget
     * @return array
     */
    public function getActiveItems($terms = [])
    {
        $active = [];

        if ($this->isActive()) {
            $terms = !empty($terms) ? $terms : $this->getTerms();
            foreach ($terms as $term) {
                if (!empty($term->children)) {
                    $active = array_merge($active, $this->getActiveItems($term->children));
                }


                if ($term->checked) {
                    $active[] = [
                        'title' => $term->name,
                        'link' => $term->link,
                    ];
                }
            }
        }

        return $active;
    }

    /**
     * @return array
     */
    public function getActiveProducts(array $terms = [])
    {
        $products = [];
        $terms = !empty($terms) ? $terms : $this->terms;

        foreach ($terms as $term) {
            if ($term->checked) {
                $products += $term->products;
            }

            if (!empty($term->children)) {
                $products += $this->getActiveProducts($term->children);
            }
        }

        return $products;
    }

    /**
     * @param array $taxQuery
     *
     * @return mixed
     */
    public function extendTaxQuery($taxQuery)
    {
        $values = $this->getSelectedValues();

        if (!empty($values)) {
            $taxonomyQuery = [
                'taxonomy'         => $this->getId(),
                'field'            => 'slug',
                'terms'            => $values,
                'operator'         => 'IN',
                'include_children' => true,
                'hide_empty'       => false
            ];

            $taxQuery[] = $taxonomyQuery;
        }

        return $taxQuery;
    }

    /**
     * @return void
     */
    public function init()
    {

        if ($this->terms === null) {
            $terms       = $this->loadTerms();
            $activeTerms = $this->getSelectedValues();

            foreach ($terms as $key => $term) {
                $term->checked  = in_array($term->slug, $activeTerms, true);
                $term->link     = $this->getValueLink($term->slug);
                $term->products = [];
                $term->isChild  = false;
                $this->processTerm($term);
            }

            $this->terms = $terms;
        }

    }

    /**
     * @return array
     */
    protected function loadTerms()
    {
        $settings = $this->getSettings();
        $options = get_option(FilterPlugin::OPTION_SETTINGS, []);
        $termIds = array_keys($settings);

        $terms = [];

        if (count($termIds)) {
            if (!empty($options['enable_category_hierarchy'])) {
                $query['parent'] = 0;
            }

            $query['taxonomy'] = $this->taxonomy->name;
            $query['orderby'] = 'include';
            $query['include'] = $termIds;

            $terms = get_terms(
                apply_filters(
                    'premmerce_filter_get_terms_' . $this->taxonomy->name . '_query',
                    $query,
                    $this->getSelectedValues()
                )
            );

        }

        return is_array($terms) ? $terms : [];
    }

    public function getResetUrl()
    {
        return apply_filters('premmerce_filter_get_reset_url_' . $this->taxonomy->name, parent::getResetUrl());
    }

    /**
     * @return array
     */
    protected function getSettings()
    {
        return array_filter(
            get_option('premmerce_filter_tax_' . $this->taxonomy->name . '_options', []),
            static function ($item) {
                return isset($item['active']) && $item['active'];
            }
        );
    }

    public function getConfig()
    {
        return $this->config;
    }
}
