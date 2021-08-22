<?php
namespace ACFFrontend\Module\Actions;

use ACFFrontend\Plugin;
use ACFFrontend\Module;
use ACFFrontend\Module\Classes\ActionBase;
use ACFFrontend\Module\Widgets;
use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as Query_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ActionTerm extends ActionBase {
	
	public function get_name() {
		return 'term';
	}

	public function get_label() {
		return __( 'Term', 'acf-frontend-form-element' );
	}

	public function get_fields_display( $form_field, $local_field ){
		switch( $form_field[ 'field_type' ] ){
			case 'term_name':
				$local_field[ 'type' ] = 'text';
				$local_field[ 'custom_term_name' ] = true;
			break;
		}
		return $local_field;
	}
	

	public function register_settings_section( $widget ) {
						
		$widget->start_controls_section(
			'section_edit_term',
			[
				'label' => $this->get_label(),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'main_action' => [ 'edit_term', 'new_term' ],
				],
			]
		);
		$this->action_controls( $widget );
		$widget->end_controls_section();
	}

	public function action_controls( $widget, $step = false ){
		$condition = [
			'main_action' => 'edit_term',
		];
		if( $step ){
			$condition[ 'field_type' ] = 'step';
			$condition[ 'overwrite_settings' ] = 'true';
		}

		$widget->add_control(
			'term_to_edit',
			[
				'label' => __( 'Term To Edit', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'current_term',
				'options' => [
					'current_term'  => __( 'Current Term', 'acf-frontend-form-element' ),
					'select_term' => __( 'Select Term', 'acf-frontend-form-element' ),
				],
				'condition' => $condition,
			]
		);
		$condition[ 'term_to_edit' ] = 'select_term';
		if( ! class_exists( 'ElementorPro\Modules\QueryControl\Module' ) ){		
			$widget->add_control(
				'term_select',
				[
					'label' => __( 'Term', 'acf-frontend-form-element' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => __( '18', 'acf-frontend-form-element' ),
					'description' => __( 'Enter term id', 'acf-frontend-form-element' ),
					'condition' => $condition,
				]
			);		
		}else{			
			$widget->add_control(
				'term_select',
				[
					'label' => __( 'Term', 'acf-frontend-form-element' ),
					'label_block' => true,
					'type' => Query_Module::QUERY_CONTROL_ID,
					'autocomplete' => [
						'object' => Query_Module::QUERY_OBJECT_CPT_TAX,
						'display' => 'detailed',
					],				
					'condition' => $condition,
				]
			);
		}

		$condition[ 'main_action' ] = 'new_term';
		unset( $condition[ 'term_to_edit' ] );
		$widget->add_control(
			'new_term_taxonomy',
			[
				'label' => __( 'Taxonomy', 'acf-frontend-form-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'category',
				'options' => acf_get_taxonomy_labels(),
				'condition' => $condition,
			]
		);
	}
	
	public function on_submit( $post_id, $form ){	
		if( ! isset( $form[ 'term_fields' ] ) ) return $post_id;

		$wg_id = isset( $_POST[ '_acf_element_id' ] ) ? '_' . $_POST[ '_acf_element_id' ] : '';

		$term_name = '(no-name)';
		if( ! empty( $_POST[ 'acf' ][ 'acfef' . $wg_id . '_' . 'term_name' ] ) ) {	
			$term_name = acf_extract_var( $_POST[ 'acf' ], 'acfef' . $wg_id . '_' . 'term_name' );	
		}

		$term_data = wp_insert_term( $term_name, $form[ 'term_fields' ][ 'taxonomy' ] );

		if( isset( $term_data[ 'term_id' ] ) ){
			$post_id = 'term_' .$term_data[ 'term_id' ];
		}
		
		return $post_id;
	}

	public function __construct(){
		add_filter( 'acf/pre_save_post', array( $this, 'on_submit' ), 4, 2 );
	}
}
