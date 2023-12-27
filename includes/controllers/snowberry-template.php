<?php

namespace WP_Snowberry\Controllers;

use \WP_MVC\Controllers\Abstracts\MVC_Controller_Registry;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Snowberry_Template extends MVC_Controller_Registry
{

	/**
	 * constructor
	 */
	public function __construct()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ), 9999 );
		add_filter( 'script_loader_tag', array( $this, 'load_data_as_module' ), 10, 3 );
		add_action( 'wp_footer', array( $this, 'load_js_templates' ), 10 );
	}

	public function enqueue_frontend_scripts()
	{
		wp_enqueue_style(
			WP_SNOWBERRY_TEXT_DOMAIN . '-styles',
			WP_Snowberry()->plugin_url() . '/assets/css/wp-snowberry-frontend.css',
			array(),
		);

		wp_register_script(
			WP_SNOWBERRY_TEXT_DOMAIN . '-scripts',
			WP_Snowberry()->plugin_url() . '/assets/js/wp-snowberry-frontend.js',
			array(),
			'',
			true
		);

		$config = array();
		$config['ajaxurl'] = admin_url( 'admin-ajax.php' );

		wp_localize_script( WP_SNOWBERRY_TEXT_DOMAIN . '-scripts', 'SnowberryConfig', $config );
		wp_enqueue_script( WP_SNOWBERRY_TEXT_DOMAIN . '-scripts' );
	}

	public function load_js_templates()
	{
		echo WP_Snowberry()->view( 'js-templates' );
	}

	public function load_data_as_module( $tag, $handle, $src )
	{
		if ( strpos( $handle, WP_SNOWBERRY_TEXT_DOMAIN ) !== 0 ) {
			return $tag;
		}

		$tag = "<script src='" . esc_url( $src ) . "' id='" . $handle . "-js' type='module'></script>";
		return $tag;
	}
}

Snowberry_Template::instance();
