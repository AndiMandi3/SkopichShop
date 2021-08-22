<?php namespace Premmerce\Filter\Filter\Items\Types;

use WP_Term;
use WC_Query;

abstract class BaseFilter implements FilterInterface
{

    /**
     * @var string
     */
    protected $queryType = 'or';

    /**
     * @var bool
     */
    protected $single = false;

    /**
     * @var string
     */
    protected $prefix = 'filter_';

    /**
     * @var string
     */
    protected $queryTypePrefix = 'query_type_';

    /**
     * @var array<string>
     */
    protected $selectedValues;

    /**
     * checkbox|radio|select|label|color
     * @return string
     */
    public function getType()
    {
        return 'checkbox';
    }

    /**
     * @return string
     */
    public function getParamName()
    {
        return $this->prefix . $this->getSlug();
    }

    /**
     * @return string
     */
    public function getQueryTypeName()
    {
        return $this->queryTypePrefix . $this->getSlug();
    }


    /**
     * Default|Dropdown|Scroll|Dropdown+Scroll
     * @return string
     */
    public function getDisplay()
    {
        return '';
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getActiveItems()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isSingle()
    {
        return $this->single;
    }


    /**
     * @return bool
     */
    public function isActive()
    {
        return count($this->getSelectedValues()) > 0;
    }

    /**
     * Term values in url get parameters
     *
     * @return array
     */
    protected function getSelectedValues()
    {
        /**
         * L1 Cache for recently used value
         */
        if ($this->selectedValues === null) {
            $filterParam = $this->getParamName();

            $values = isset($_GET[$filterParam]) ? explode(',', wc_clean($_GET[$filterParam])) : [];
            $values = array_map('sanitize_title', $values);
            $values = array_filter($values, function ($item) {
                return $item !== '';
            });

            $values = array_unique($values);
            $this->selectedValues = apply_filters('premmerce_filter_get_selected_values', $values, $filterParam);
        }

        return $this->selectedValues;
    }

    /**
     * Link for term to select or deselect term
     *
     * @param $slug
     *
     * @param null $parentLink
     * @return string
     */
    protected function getValueLink($slug, $parentLink = null)
    {
        $selectedValues = $this->getSelectedValues();
        $checked = in_array($slug, $selectedValues, false);
        $termKey = array_search($slug, $selectedValues, false);

        if ($this->isSingle()) {
            $selectedValues = $checked ? [] : [$slug];
        } elseif ($checked) {
            unset($selectedValues[$termKey]);
        } else {
            $selectedValues[] = $slug;
        }

        $link = $parentLink ?: $this->getResetUrl();


        if (count($selectedValues) > 0) {
            $link = add_query_arg($this->getParamName(), implode(',', $selectedValues), $link);
            $link = add_query_arg($this->getQueryTypeName(), $this->queryType, $link);
        }

        return apply_filters('premmerce_filter_term_link', $link, $slug);
    }

    /**
     * Get current page url except $taxonomy args (filter_$taxonomy || query_type_$taxonomy)
     *
     * @return string
     */
    public function getResetUrl()
    {
        global $wp;

        $link = home_url($wp->request);

        $pos = strpos($link, '/page');

        if ($pos !== false) {
            $link = substr($link, 0, $pos);
        }

        $currentParams = [
            $this->getParamName(),
            $this->getQueryTypeName()
        ];
        foreach ($_GET as $key => $value) {
            $isCurrent = in_array($key, $currentParams);

            if (! $isCurrent) {
                $link = add_query_arg($key, wc_clean($value), $link);
            }
        }

        return $link;
    }

    /**
     * Generate transient ID (key) from taxonomy and meta query
     */
    public function generateQueryTransientID($taxQuery, $metaQuery, $postIdIds, $unsetTaxQueryKey = null)
    {
        $transientQuery = '';

        foreach ($taxQuery as $key => $tQuery) {
            if (!empty($unsetTaxQueryKey) && !empty($tQuery[$unsetTaxQueryKey])) {
                unset($taxQuery[ $key ]);
            }
            if ($key !== 'relation') {
                $transientQuery .= '_' . $tQuery['taxonomy'] . '_' . implode('_', $tQuery['terms']);
            }
        }

        foreach ($metaQuery as $key => $mQuery) {
            if ($key !== 'relation') {
                $transientQuery .= '_' . $key . '_' . implode('_', $mQuery['value']);
            }
        }

        $transientQuery .= (!empty($postIdIds)) ? '_' . str_replace(',', '', $postIdIds) : '' ;

        return $transientQuery;
    }


    /**
     * Backward compat for templates
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'has_checked':
                return $this->isActive();
            case 'display_type':
                return $this->getDisplay();
            case 'attribute_label':
                return $this->getLabel();
            case 'attribute_name':
                return $this->getSlug();
            case 'terms':
                return $this->getItems();
            case 'html_type':
                return $this->getType();
            case 'reset_url':
                return $this->getResetUrl();
            case 'values':
                return $this->getOptions();
        }
    }

    /**
     * Backward compat for templates
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return in_array(
            $name,
            [
                'has_checked',
                'display_type',
                'attribute_label',
                'attribute_name',
                'terms',
                'html_type',
                'reset_url',
                'values'
            ]
        );
    }
}
