<?php

namespace Premmerce\Filter\Shortcodes;

use  Premmerce\Filter\Filter\Container ;
use  Premmerce\Filter\Widget\FilterWidget ;
use  Premmerce\SDK\V2\FileManager\FileManager ;
use  Premmerce\Filter\Widget\ActiveFilterWidget ;
class FilterWidgetShortcodes
{
    /**
     * @var FileManager
     */
    private  $fileManager ;
    /**
     * Shortcode constructor
     */
    public function __construct( FileManager $fileManager )
    {
        $this->fileManager = $fileManager;
    }
    
    public static function shortcodeInstruction()
    {
        $instruction = '';
        //attributes info
        $attrList = [
            'style'                 => [
            'example_data' => 'custom',
            'description'  => __( 'Filter style. Can be <code>default</code>, <code>premmerce</code>, <code>custom</code>. ', 'premmerce-filter' ) . '<br>' . __( 'All other attributes you can use after adding <code>style="custom"</code> attribute.', 'premmerce-filter' ),
        ],
            'bg_color'              => [
            'example_data' => '#fff',
            'description'  => __( 'Filter Background color', 'premmerce-filter' ),
        ],
            'add_border'            => [
            'example_data' => 'on',
            'description'  => __( 'Filter Border', 'premmerce-filter' ),
        ],
            'border_color'          => [
            'example_data' => '#000',
            'description'  => __( 'Filter Border Color', 'premmerce-filter' ),
        ],
            'bold_title'            => [
            'example_data' => 'on',
            'description'  => __( 'Make filter title bold', 'premmerce-filter' ),
        ],
            'title_appearance'      => [
            'example_data' => 'uppercase',
            'description'  => __( 'Make title text <code>uppercase</code> or <code>default</code>', 'premmerce-filter' ),
        ],
            'price_input_bg'        => [
            'example_data' => '#fff',
            'description'  => __( 'Filter Price Input Background', 'premmerce-filter' ),
        ],
            'price_input_text'      => [
            'example_data' => '#000',
            'description'  => __( 'Filter Price Input Color', 'premmerce-filter' ),
        ],
            'price_slider_range'    => [
            'example_data' => '#000',
            'description'  => __( 'Filter Price Slider Range Color', 'premmerce-filter' ),
        ],
            'price_slider_handle'   => [
            'example_data' => '#000',
            'description'  => __( 'Filter Price Slider Handle Color', 'premmerce-filter' ),
        ],
            'checkbox_appearance'   => [
            'example_data' => '0',
            'description'  => __( 'Choose Checkbox Appearance: ', 'premmerce-filter' ) . '<br><code>0</code> : BALLOT BOX, ' . '<code>2713</code> : BALLOT BOX WITH CHECK, ' . '<code>2715</code> : BALLOT BOX WITH X',
        ],
            'title_size'            => [
            'example_data' => '14',
            'description'  => __( 'Titles Font Size', 'premmerce-filter' ),
        ],
            'title_color'           => [
            'example_data' => '#000',
            'description'  => __( 'Titles Color', 'premmerce-filter' ),
        ],
            'terms_title_size'      => [
            'example_data' => '14',
            'description'  => __( 'Terms Titles Font Size', 'premmerce-filter' ),
        ],
            'terms_title_color'     => [
            'example_data' => '#000',
            'description'  => __( 'Terms Titles Color', 'premmerce-filter' ),
        ],
            'checkbox_color'        => [
            'example_data' => '#000',
            'description'  => __( 'Checkbox/Radio Color', 'premmerce-filter' ),
        ],
            'checkbox_border_color' => [
            'example_data' => '#000',
            'description'  => __( 'Checkbox/Radio Border Color', 'premmerce-filter' ),
        ],
        ];
        //filter shortcode
        $instruction .= '<h3>' . __( 'Filter Shortcode', 'premmerce-filter' ) . '</h3>';
        $instruction .= '<div class="premmerce-shortcode-example">[premmerce_filter';
        $i = 0;
        foreach ( $attrList as $key => $attr ) {
            $i++;
            $instruction .= " {$key}=\"{$attr['example_data']}\"";
            if ( $i == 5 ) {
                break;
            }
        }
        $instruction .= ']</div>';
        //filter shortcode with all attributes
        $instruction .= '<h3>' . __( 'Filter Shortcode with all attributes', 'premmerce-filter' ) . '</h3>';
        $instruction .= '<div class="premmerce-shortcode-example premmerce-shortcode-all-attr">[premmerce_filter';
        foreach ( $attrList as $key => $attr ) {
            $instruction .= " {$key}=\"{$attr['example_data']}\"";
        }
        $instruction .= ']</div>';
        //Attributes Description
        $instruction .= '<h3>' . __( 'Filter Shortcode Attributes:', 'premmerce-filter' ) . '</h3>';
        $instruction .= '<table class="premmerce-shortcodes-attr-desc">';
        foreach ( $attrList as $key => $attr ) {
            $instruction .= "<tr><td class='premmerce-shortcode-attr'>{$key}=\"{$attr['example_data']}\"</td><td>{$attr['description']}</td><tr>";
        }
        $instruction .= '</table>';
        return $instruction;
    }

}