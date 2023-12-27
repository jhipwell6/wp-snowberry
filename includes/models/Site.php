<?php

namespace WP_Snowberry\Models;

use \WP_MVC\Models\Abstracts\Abstract_Model;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Site extends Abstract_Model
{
	protected $name;
	protected $url;
	protected $phone;
	protected $email;
	protected $address;

	public function __construct()
	{
		// fill properties
		$this->get_props();
		
		return $this;
	}

	/* 
	 * Computed
	 */
	
	public function get_name()
	{
		if ( null === $this->name ) {
			$this->name = get_bloginfo('name');
		}
		return $this->name;
	}
	
	public function get_url()
	{
		if ( null === $this->url ) {
			$this->url = get_site_url();
		}
		return $this->url;
	}
	
	public function get_phone()
	{
		return $this->get_prop( 'phone' );
	}
	
	public function get_email()
	{
		return $this->get_prop( 'email' );
	}
	
	public function get_address()
	{
		return $this->get_prop( 'address' );
	}
	
	/*
	 * Helpers
	 */

	public function get_hidden()
	{
		return array();
	}

	protected function get_meta( $prop )
	{
		if ( function_exists( 'get_field' ) ) {
			return get_field( 'site_' . $prop, 'option' );
		} else {
			return get_option( 'site_' . $prop );
		}
	}

}
