<?php

namespace Premmerce\Filter\Widget;

use  WP_Widget ;
use  Premmerce\Filter\FilterPlugin ;
use  Premmerce\Filter\Filter\Container ;
use  Premmerce\Filter\Admin\Tabs\Settings ;
class FilterWidget extends WP_Widget
{
    const  FILTER_WIDGET_ID = 'premmerce_filter_filter_widget' ;
    private  $settings = array() ;
    /**
     * FilterWidget constructor.
     */
    public function __construct()
    {
        parent::__construct( self::FILTER_WIDGET_ID, __( 'Premmerce filter', 'premmerce-filter' ), [
            'description' => __( 'Product attributes filter', 'premmerce-filter' ),
        ] );
    }
    
    /**
     * Render widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        
        if ( apply_filters( 'premmerce_product_filter_active', false ) ) {
            $data = self::getFilterWidgetContent( $args, $instance );
            do_action( 'premmerce_product_filter_render', $data );
        }
    
    }
    
    /**
     * Get Filter Widget data
     *
     * @param array $args
     * @param array $instance
     */
    public static function getFilterWidgetContent( $args = array(), $instance = array() )
    {
        $items = Container::getInstance()->getItemsManager()->getFilters();
        $items = apply_filters( 'premmerce_product_filter_items', $items );
        $settings = get_option( FilterPlugin::OPTION_SETTINGS, [] );
        $style = ( isset( $instance['style'] ) ? $instance['style'] : 'default' );
        $showFilterButton = !empty($settings['show_filter_button']);
        //default styles
        $border = '';
        $boldTitle = '';
        $titleAppearance = '';
        //premmerce styles
        
        if ( $style !== 'default' ) {
            //border styles
            if ( isset( $instance['add_border'] ) && $instance['add_border'] === 'on' || $style === 'premmerce' ) {
                $border = ' filter__item-border';
            }
            //title styles
            if ( isset( $instance['bold_title'] ) && $instance['bold_title'] === 'on' || $style === 'premmerce' ) {
                $boldTitle = 'bold';
            }
            if ( isset( $instance['title_appearance'] ) && $instance['title_appearance'] === 'uppercase' || $style === 'premmerce' ) {
                $titleAppearance = 'uppercase';
            }
        }
        
        $data = [
            'args'             => $args,
            'style'            => $style,
            'showFilterButton' => $showFilterButton,
            'attributes'       => $items,
            'formAction'       => apply_filters( 'premmerce_product_filter_form_action', '' ),
            'instance'         => $instance,
            'border'           => $border,
            'boldTitle'        => $boldTitle,
            'titleAppearance'  => $titleAppearance,
        ];
        return $data;
    }
    
    /**
     * @param array $new_instance
     * @param array $old_instance
     *
     * @return array
     */
    public function update( $new_instance, $old_instance )
    {
        $instance = [];
        $instance['title'] = filter_var( $new_instance['title'], FILTER_SANITIZE_STRING );
        $instance['style'] = filter_var( $new_instance['style'], FILTER_SANITIZE_STRING );
        return $instance;
    }
    
    /**
     * @param array $instance
     *
     * @return string|void
     */
    public function form( $instance )
    {
        $settings = get_option( FilterPlugin::OPTION_SETTINGS, [] );
        //check plan
        $premiumOnly = ( !premmerce_pwpf_fs()->can_use_premium_code() ? __( ' (Premium)', 'premmerce-filter' ) : '' );
        $currentPlan = ( !premmerce_pwpf_fs()->can_use_premium_code() ? FilterPlugin::PLAN_FREE : FilterPlugin::PLAN_PREMIUM );
        //options from settings page
        $filterStyles = [
            'default'   => __( 'Default', 'premmerce-filter' ),
            'premmerce' => 'Premmerce',
        ];
        //add custom option
        $filterStyles['custom'] = 'Custom' . $premiumOnly;
        //default variables
        $checkboxAppVariables = [
            '0'    => 'BALLOT BOX',
            '2713' => 'BALLOT BOX WITH CHECK',
            '2715' => 'BALLOT BOX WITH X',
        ];
        $titleAppVariables = [
            'default'   => 'Default',
            'uppercase' => 'Uppercase',
        ];
        do_action( 'premmerce_product_filter_widget_form_render', [
            'settings'             => $settings,
            'title'                => $instance['title'] ?? '',
            'filterStyles'         => $filterStyles,
            'style'                => $instance['style'] ?? '',
            'addBorder'            => $instance['add_border'] ?? 'on',
            'borderColor'          => $instance['border_color'] ?? '',
            'priceInputBg'         => $instance['price_input_bg'] ?? '',
            'priceInputText'       => $instance['price_input_text'] ?? '',
            'priceSliderRange'     => $instance['price_slider_range'] ?? '',
            'priceSliderHandle'    => $instance['price_slider_handle'] ?? '',
            'checkboxAppVariables' => $checkboxAppVariables,
            'checkboxAppearance'   => $instance['checkbox_appearance'] ?? '',
            'titleAppVariables'    => $titleAppVariables,
            'titleAppearance'      => $instance['title_appearance'] ?? 'default',
            'boldTitle'            => $instance['bold_title'] ?? '',
            'titleSize'            => $instance['title_size'] ?? '',
            'titleColor'           => $instance['title_color'] ?? '',
            'termsTitleSize'       => $instance['terms_title_size'] ?? '',
            'termsTitleColor'      => $instance['terms_title_color'] ?? '',
            'bgColor'              => $instance['bg_color'] ?? '',
            'checkboxColor'        => $instance['checkbox_color'] ?? '',
            'checkboxBorderColor'  => $instance['checkbox_border_color'] ?? '',
            'currentPlan'          => $currentPlan,
            'widget'               => $this,
        ] );
    }
    
    /**
     * Render ColorPicker for widget
     */
    public static function renderWidgetInput(
        $widget,
        $id,
        $title,
        $value,
        $class,
        $type = 'text',
        $plan = 'premium'
    )
    {
        $checkbox = '<p><label for="%1$s">%2$s</label><input class="widefat %3$s %4$s" type="%5$s" name="%6$s" id="%7$s" value="%8$s" %9$s></p>';
        $fieldID = esc_attr( $widget->get_field_id( $id ) );
        $disabled = '';
        //if it is not premium plan - disable input
        if ( $plan === FilterPlugin::PLAN_FREE ) {
            $disabled = 'disabled';
        }
        printf(
            $checkbox,
            $fieldID,
            esc_attr( $title ),
            $class,
            $disabled,
            $type,
            esc_attr( $widget->get_field_name( $id ) ),
            $fieldID,
            esc_attr( $value ),
            $disabled
        );
    }
    
    /**
     * Render checkbox for widget
     */
    public static function renderWidgetCheckbox(
        $widget,
        $id,
        $title,
        $value,
        $plan
    )
    {
        $checkbox = '<p><input class="widefat" type="checkbox" name="%1$s" id="%2$s" %3$s %4$s><label for="%5$s">%6$s</label></p>';
        $checked = checked( $value, 'on', false );
        $fieldID = esc_attr( $widget->get_field_id( $id ) );
        $disabled = '';
        //if it is not premium plan - disable checkbox
        if ( $plan === FilterPlugin::PLAN_FREE ) {
            $disabled = 'disabled';
        }
        printf(
            $checkbox,
            esc_attr( $widget->get_field_name( $id ) ),
            $fieldID,
            $checked,
            $disabled,
            $fieldID,
            esc_attr( $title )
        );
    }
    
    /**
     * Render select for widget
     */
    public static function renderWidgetSelect(
        $widget,
        $id,
        $title,
        $value,
        $options,
        $class = '',
        $plan = 'premium'
    )
    {
        $select = '<p><label for="%1$s">%2$s</label><select name="%3$s" class="widefat %4$s" id="%5$s" %6$s></p>';
        $fieldID = esc_attr( $widget->get_field_id( $id ) );
        $disabled = '';
        //if it is not premium plan - disable select
        if ( $plan === FilterPlugin::PLAN_FREE ) {
            $disabled = 'disabled';
        }
        printf(
            $select,
            $fieldID,
            esc_attr( $title ),
            esc_attr( $widget->get_field_name( $id ) ),
            $class,
            $fieldID,
            $disabled
        );
        foreach ( $options as $key => $option ) {
            $selected = ( $key == $value ? 'selected' : '' );
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                $key,
                $selected,
                $option
            );
        }
        print '</select>';
    }

}