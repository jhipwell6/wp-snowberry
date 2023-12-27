<?php

namespace WP_Snowberry\Core;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Custom_Fields
{
	protected static $instance;

	/**
	 * Initializes variables and sets up WordPress hooks/actions.
	 * @return void
	 */
	protected function __construct()
	{
		add_action( 'admin_menu', [ $this, 'add_options_page' ], 98 );
		add_action( 'acf/init', [ $this, 'add_fields' ], 10 );
	}

	/**
	 * Static Singleton Factory Method
	 * @return self
	 */
	public static function instance()
	{
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function add_options_page()
	{
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page( [
				'page_title' => 'Site Configuration',
				'menu_title' => 'Site Config',
				'redirect' => false
			] );
		}
	}

	public function add_fields()
	{
		$fields_path = WP_Snowberry()->plugin_path() . '/includes/core/acf-fields/';
		foreach ( glob( $fields_path . '*.php' ) as $field ) {
			include_once( $field );
		}
	}

}

Custom_Fields::instance();
