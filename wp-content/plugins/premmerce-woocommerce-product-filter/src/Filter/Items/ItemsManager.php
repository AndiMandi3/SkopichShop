<?php namespace Premmerce\Filter\Filter\Items;

use Premmerce\Filter\Filter\Container;
use Premmerce\Filter\Filter\Items\Types\FilterInterface;
use Premmerce\Filter\Filter\Items\Types\PriceFilter;
use Premmerce\Filter\Filter\Items\Types\TaxonomyFilter;

//todo: Maybe it's better to replace 'items' with 'filters' or 'filter items' because 'items' is not descriptive enough. E.g., it would be 'FiltersManager', 'FiltersFactory' classes etc.
class ItemsManager
{
    /**
     * @var FilterInterface[]
     */
    private $items = [];


    /**
     * @var null|array
     */
    private $activeFilters;

    /**
     * @var null|array
     */
    private $activeItems = [];

    /**
     * @var bool
     */
    private $prepared = false;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var bool
     */
    private $load = true;

    /**
     * @var bool
     */
    private $hideEmpty;

    /**
     * ItemsManager constructor.
     *
     * @param Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        $settings = $this->container->getOption('settings');
        $this->hideEmpty = !empty($settings['hide_empty']);
        $this->load = empty($settings['load_deferred']) || !empty($_REQUEST['premmerce_filter_ajax_action']);
        $this->loadItems();

        add_filter('woocommerce_is_filtered', [$this, 'isFiltered']);
        add_filter('posts_search', [$this, 'onPostsSearch'], 100, 2);
    }

    /**
     * @param $searchQuery
     * @param $wpQuery
     *
     * @return mixed
     */
    public function onPostsSearch($searchQuery, $wpQuery)
    {
        if ($wpQuery->is_search) {
            $this->container->getQueryHelper()->setSearchQuery($searchQuery);
        }

        return $searchQuery;
    }

    /**
     * @param $filtered
     *
     * @return bool
     */
    public function isFiltered($filtered)
    {
        foreach ($this->getItems() as $item) {
            if ($item->isActive()) {
                return true;
            }
        }

        return $filtered;
    }


    /**
     * Get active items for 'Active filters widget'
     *
     * @return array
     */
    public function getActiveFilters()
    {
        if (!$this->load) {
            return [];
        }

        if ($this->activeFilters === null) {
            $this->activeFilters = [];

            foreach ($this->getItems() as $item) {
                $this->activeFilters = array_merge($this->activeFilters, $item->getActiveItems());
            }
        }

        return $this->activeFilters;
    }

    /**
     * @return void
     */
    private function setActiveItems()
    {
        foreach ($this->getItems() as $item) {
            $this->activeItems = array_merge($this->activeItems, $this->filterActiveItems($item->getItems()));
        }
    }

    /**
     * @return array
     */
    public function getActiveItems()
    {
        if (!$this->load) {
            return [];
        }

        return $this->activeItems;
    }

    /**
     * @param array $items
     * @return array
     */
    private function filterActiveItems($items)
    {
        $terms = [];
        foreach ($items as $item) {
            if ($item->checked) {
                $terms[] = $item;
            }

            if (!empty($item->children)) {
                $terms = array_merge($terms, $this->filterActiveItems($item->children));
            }
        }
        return $terms;
    }

    /**
     * Get filter items for 'Filter widget'
     *
     * @return array
     */
    public function getFilters()
    {
        if (!$this->load) {
            return [];
        }

        $filters = [];

        foreach ($this->getItems() as $item) {
            if ($item->isVisible()) {
                $filters[] = $item;
            }
        }

        return $filters;
    }

    /**
     * @param object $term
     * @param array $queriedProducts - products by main query
     *
     * @param array $queriedTaxonomyProducts - products by selected taxonomy terms
     *
     * @return int
     *
     */
    private function getTermCount($term, $queriedProducts, $queriedTaxonomyProducts)
    {
        if (!empty($term->children)) {
            foreach ($term->children as $child) {
                $term->products += $child->products;
            }
        }

        if (empty($term->products) || empty($queriedProducts)) {
            return 0;
        }

        $products = array_intersect_key($term->products, $queriedProducts);
        $products = $this->filterTermCounterByPrice($products);

        if (empty($products)) {
            return 0;
        }

        if (empty($queriedTaxonomyProducts)) {
            return count($products);
        }

        foreach ($queriedTaxonomyProducts as $taxonomy => $taxonomyProducts) {
            if ($taxonomy !== $term->taxonomy) {
                $products = array_intersect_key($products, $taxonomyProducts);
            }
        }
        return count($products);
    }

    /**
     * @param array $products
     * @return array
     */
    private function filterTermCounterByPrice(array $products)
    {
        if (!empty($products)) {
            if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
                global $wpdb;
                $pids = implode(',', array_keys($products));
                $ids = $wpdb->get_col("SELECT post_id FROM {$wpdb->postmeta} WHERE post_id IN ({$pids}) AND meta_key = '_price'"
                    . (!empty($_GET['min_price']) ? " AND meta_value >= " . (float)$_GET['min_price'] : '')
                    . (!empty($_GET['max_price']) ? " AND meta_value <= " . (float)$_GET['max_price'] : '')
                );
                $ids = array_map('intval', $ids);
                $products = array_filter(
                    $products,
                    function ($id) use ($ids) {
                        return in_array($id, $ids, true);
                    },
                    ARRAY_FILTER_USE_KEY
                );
            }
        }

        return $products;
    }

    /**
     * Init filter items
     */
    private function loadItems()
    {
        $factory   = $this->container->getItemFactory();
        $options   = $this->container->getOption('items');
        $settings  = $this->container->getOption('settings');
        $hideEmpty = $this->hideEmpty;

        $items = [];
        foreach ($options as $key => $option) {
            if ($option['active']) {
                $option['hide_empty'] = $hideEmpty;
                $items[] = $factory->createItem($key, $option);
            }
        }

        if (!empty($settings['show_price_filter'])) {
            array_unshift($items, new PriceFilter($this->container->getPriceQuery()));
        }

        $items = apply_filters('premmerce_filters_register_items', $items);

        foreach ($items as $item) {
            if ($item instanceof FilterInterface) {
                $this->items[$item->getId()] = $item;
            }
        }
    }

    /**
     * @return FilterInterface[]
     */
    public function getItems()
    {
        if (!$this->prepared) {
            $this->prepared = true;

            if ($this->load) {
                $this->calculate();
            }
        }

        return $this->items;
    }

    /**
     * Prepare filter items
     */
    private function calculate()
    {
        $taxonomyItems = [];

        foreach ($this->items as $item) {
            $item->init();

            if ($item instanceof TaxonomyFilter) {
                $taxonomyItems[$item->getId()] = $item;
            }
        }

        $termTaxonomyIds = [];
        $productQuery = $this->container->getProductQuery();

        foreach ($taxonomyItems as $item) {
            $this->setTermTaxonomyIds($item->getTerms(), $termTaxonomyIds);
        }

        $queriedProducts = $productQuery->getProductIdsByMainQuery(array_keys($taxonomyItems));
        $termProducts = $productQuery->getTermTaxonomyProductIds($termTaxonomyIds);

        foreach ($taxonomyItems as $item) {
            $this->setTermProducts($item->getTerms(), $termProducts);
        }

        $taxonomyProducts = [];

        foreach ($taxonomyItems as $item) {
            if ($item->isActive()) {
                $taxonomyProducts[$item->getId()] = $item->getActiveProducts();
            }
        }
        $this->setActiveItems();

        foreach ($taxonomyItems as $itemKey => $item) {
            $this->setCounter($item->getTerms(), $queriedProducts, $taxonomyProducts);
        }
    }

    /**
     * @param $terms
     * @param $queriedProducts
     * @param $taxonomyProducts
     */
    private function setCounter($terms, $queriedProducts, $taxonomyProducts)
    {
        foreach ($terms as $k => $term) {
            $count = apply_filters(
                'premmerce_filter_term_count_' . $term->taxonomy,
                $this->getTermCount($term, $queriedProducts, $taxonomyProducts),
                $term,
                $queriedProducts,
                $taxonomyProducts
            );

            $terms[$k]->count = $count;

            if (!empty($term->children)) {
                $this->setCounter($term->children, $queriedProducts, $taxonomyProducts);
            }
        }
    }

    /**
     * @param array $terms
     * @param array $termTaxonomyIds
     */
    private function setTermTaxonomyIds(array $terms = [], &$termTaxonomyIds = [])
    {
        foreach ($terms as $term) {
            $termTaxonomyIds[] = $term->term_taxonomy_id;
            if (!empty($term->children)) {
                $this->setTermTaxonomyIds($term->children, $termTaxonomyIds);
            }
        }
    }

    /**
     * @param $terms
     * @param $termProducts
     * @param \WP_Term|null $parent
     * @return mixed
     */
    private function setTermProducts($terms, $termProducts, $parent = null)
    {
        foreach ($terms as $t => $term) {
            if (isset($termProducts[$term->term_taxonomy_id])) {
                $terms[$t]->products = $termProducts[$term->term_taxonomy_id];
            }

            if (!empty($term->children)) {
                $this->setTermProducts($term->children, $termProducts, $term);
            }

            do_action('premmerce_filter_add_term_products_' . $term->taxonomy, $term, $termProducts);
        }

        if ($parent) {
            $parent->children = $terms;
        }

        return $terms;
    }
}
