<?php

namespace WP_Snowberry\Controllers;

use \WP_MVC\Controllers\Abstracts\MVC_Controller_Registry;
use \FLPageData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Snowberry_Services extends MVC_Controller_Registry
{
	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'pre_get_posts', [ $this, 'set_service_pages_query' ], 10, 1 );
		add_action( 'fl_page_data_add_properties', [ $this, 'add_service_properties' ] );
	}
	
	public function set_service_pages_query( $query )
	{
		if ( WP_Snowberry()->Helpers()->is_fl_builder_loop( 'page-feed--services', $query ) ) {
			$query->set( 'post_parent', 89 );
		}
	}
	
	public function add_service_properties()
	{
		if ( ! class_exists( 'FLPageData' ) ) {
			return;
		}
		
		FLPageData::add_post_property( 'service_card', array(
			'label' => __( 'Service Card', WP_SNOWBERRY_TEXT_DOMAIN ),
			'group' => 'site',
			'type' => 'all',
			'getter' => [ $this, 'fc_service_card' ],
		) );
	}
	
	public function fc_service_card()
	{
		global $post;
		return WP_Snowberry()->view( 'service-card', [ 'post' => $post ] );
	}
}

Snowberry_Services::instance();