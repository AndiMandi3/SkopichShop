<?php namespace Premmerce\Filter\Admin\Tabs\Base;

abstract class SortableListTab implements TabInterface
{

    /**
     * How to handle bulk actions
     * @var array
     */
    protected $bulkActions = [];

    public function __construct()
    {
        $this->bulkActions['display']  = ['active' => 1];
        $this->bulkActions['hide']     = ['active' => 0];
        $this->bulkActions['checkbox'] = ['type' => 'checkbox'];
        $this->bulkActions['select']   = ['type' => 'select'];
        $this->bulkActions['radio']    = ['type' => 'radio'];


        //this variables is for premium plan
        $this->bulkActions['color']  = ['type' => 'color'];
        $this->bulkActions['image']  = ['type' => 'image'];
        $this->bulkActions['label']  = ['type' => 'label'];
        $this->bulkActions['slider'] = ['type' => 'slider'];


        $this->bulkActions['display_']                = ['display_type' => ''];
        $this->bulkActions['display_dropdown']        = ['display_type' => 'dropdown'];
        $this->bulkActions['display_scroll']          = ['display_type' => 'scroll'];
        $this->bulkActions['display_scroll_dropdown'] = ['display_type' => 'scroll_dropdown'];
        $this->bulkActions['display_dropdown_hover']  = ['display_type' => 'dropdown_hover'];
    }

    /**
     * Ajax order by ids handler
     *
     * @param string $key - options key to update
     * @param array $actual - actual data
     *
     */
    protected function sortHandler($key, $actual)
    {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : null;

        if (is_array($ids)) {
            $ids = array_combine($ids, $ids);

            $config = array_replace($ids, $actual);

            update_option($key, $config);
        }

        wp_die();
    }

    /**
     * Bulk update entities
     *
     * @param string $key - config key
     * @param array $config - initial config
     */
    protected function bulkActionsHandler($key, $config)
    {
        $action = isset($_POST['value']) ? $_POST['value'] : null;
        $ids    = isset($_POST['ids']) ? $_POST['ids'] : [];

        if (array_key_exists($action, $this->bulkActions)) {
            $update = $this->bulkActions[$action];

            foreach ($ids as $id) {
                if (array_key_exists($id, $config)) {
                    do_action('premmerce_filter_item_updated', $id, $config[$id], $update);
                    $config[$id] = array_merge($config[$id], $update);
                }
            }
            update_option($key, $config);
        }

        wp_die();
    }

    /**
     * Get config with actual values
     *
     * @param $name
     * @param $actual
     * @param $default
     *
     * @return array
     */
    protected function getConfig($name, $actual, $default)
    {
        $config = get_option($name, []);

        if (! is_array($config)) {
            $config = [];
        }

        $ids       = array_keys($actual);
        $configIds = array_keys($config);

        $removed = array_diff($configIds, $ids);

        foreach ($removed as $id) {
            unset($config[$id]);
        }


        $new = array_diff($ids, $configIds);

        foreach ($config as &$item) {
            if (! is_array($item)) {
                $item = [];
            }
            $item = array_merge($default, $item);
        }

        foreach ($new as $id) {
            $config[$id] = $default;
        }

        return $config;
    }

    /**
     * Get pagination attributes and args
     *
     * @param $attributes
     *
     * @return array
     */
    public function paginationDataForTabs($attributes)
    {
        $screen_option = get_current_screen()->get_option('per_page', 'option');
        $itemsPerPage  = get_user_meta(get_current_user_id(), $screen_option, true);

        if (! $itemsPerPage) {
            $itemsPerPage = 100;
        }

        $page = $_GET['p'] ?? 1;

        $offset = ($page - 1) * $itemsPerPage;

        $total = ceil(count($attributes) / $itemsPerPage);

        $keys = array_keys($attributes);

        $prevId = $keys[$offset - 1] ?? null;
        $nextId = $keys[$offset + $itemsPerPage] ?? null;

        $attributes = array_slice($attributes, $offset, $itemsPerPage, true);

        $paginationArgs = [
            'base'               => '%_%',
            'format'             => '?p=%#%', // : %#% is replaced by the page number
            'total'              => $total,
            'current'            => $page,
            'aria_current'       => 'page',
            'show_all'           => false,
            'prev_next'          => true,
            'prev_text'          => '&larr;',
            'next_text'          => '&rarr;',
            'end_size'           => 1,
            'mid_size'           => 10,
            'add_args'           => array(), // array of query args to add
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        ];

        $paginationReturn = [
            'attr'   => $attributes,
            'args'   => $paginationArgs,
            'prevId' => $prevId,
            'nextId' => $nextId
        ];

        return $paginationReturn;
    }
}
