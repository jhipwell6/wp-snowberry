<?php

namespace WP_Snowberry\Controllers;

use \WP_MVC\Controllers\Abstracts\MVC_Controller_Registry;
use \FLPageData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Snowberry_Work extends MVC_Controller_Registry
{
	private $Work;
	private $work_properties;
	
	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'fl_page_data_add_properties', [ $this, 'add_work_properties' ] );
	}
	
	public function add_work_properties()
	{
		if ( ! class_exists( 'FLPageData' ) ) {
			return;
		}
		
		FLPageData::add_post_property( 'work_data', array(
				'label' => __( 'Work Data', WP_SNOWBERRY_TEXT_DOMAIN ),
				'group' => 'posts',
				'type' => [ 'string' ],
				'getter' => [ $this, 'fc_work_data' ],
			) );

			FLPageData::add_post_property_settings_fields( 'work_data', array(
				'property' => array(
					'type' => 'select',
					'label' => __( 'Property', WP_SNOWBERRY_TEXT_DOMAIN ),
					'options' => $this->get_work_properties(),
				),
			) );
		
		FLPageData::add_post_property( 'work_card', array(
			'label' => __( 'Work Card', WP_SNOWBERRY_TEXT_DOMAIN ),
			'group' => 'site',
			'type' => 'all',
			'getter' => [ $this, 'fc_work_card' ],
		) );
	}
	
	public function fc_work_data( $settings )
	{
		$output = '';
		$property = $settings->property;
		$Work = $this->get_Work();
		if ( $Work ) {
			if ( $Work->has_prop( $property ) ) {
				$getter = "get_{$property}";
				switch ( $property ) {
					default:
						$output = $Work->{$getter}();
				}
			}
		}
		return $output;
	}
	
	public function fc_work_card()
	{
		$Work = $this->get_Work( get_the_ID() );
		return WP_Snowberry()->view( 'work-card', [ 'Work' => $Work ] );
	}
	
	private function get_Work( $id = null )
	{
		$id = $id ?: get_the_ID();
		if ( null === $this->Work || $this->Work->get_id() !== $id || get_post_type( $id ) != 'work' ) {
			$this->Work = WP_Snowberry()->Work( $id );
		}
		return $this->Work;
	}

	private function get_work_properties()
	{
		if ( null === $this->work_properties ) {
			$Work = $this->get_Work();
			$keys = array_keys( $Work->to_array() );
			$this->work_properties = array_combine( $keys, $keys );
		}
		return $this->work_properties;
	}
}

Snowberry_Work::instance();