<?php

namespace WP_Snowberry\Controllers;

use \WP_MVC\Controllers\Abstracts\MVC_Controller_Registry;
use \FLPageData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Snowberry_Site extends MVC_Controller_Registry
{
	private $site_properties;

	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'fl_page_data_add_properties', [ $this, 'add_site_properties' ] );
	}

	public function add_site_properties()
	{
		if ( class_exists( 'FLPageData' ) ) {
			FLPageData::add_site_property( 'site_data', array(
				'label' => __( 'Site Data', WP_SNOWBERRY_TEXT_DOMAIN ),
				'group' => 'site',
				'type' => 'all',
				'getter' => [ $this, 'fc_site_data' ],
			) );

			FLPageData::add_site_property_settings_fields( 'site_data', array(
				'property' => array(
					'type' => 'select',
					'label' => __( 'Property', WP_SNOWBERRY_TEXT_DOMAIN ),
					'options' => $this->get_site_properties(),
				),
			) );
		}
	}
	
	public function fc_site_data( $settings )
	{
		$output = '';
		$property = $settings->property;
		$Site = WP_Snowberry()->Site();
		if ( $Site ) {
			if ( $Site->has_prop( $property ) ) {
				$getter = "get_{$property}";
				switch ( $property ) {
					default:
						$output = $Site->{$getter}();
				}
			}
		}
		return $output;
	}
	
	/*
	 * Private methods
	 */

	private function get_site_properties()
	{
		if ( null === $this->site_properties ) {
			$Site = WP_Snowberry()->Site();
			$keys = array_keys( $Site->to_array() );
			$this->site_properties = array_combine( $keys, $keys );
		}
		return $this->site_properties;
	}
}

Snowberry_Site::instance();
