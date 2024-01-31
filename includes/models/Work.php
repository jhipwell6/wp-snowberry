<?php

namespace WP_Snowberry\Models;

use \WP_MVC\Models\Abstracts\Post_Model;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Work extends Post_Model
{
	const POST_TYPE = 'work';
	const UNIQUE_KEY = null;
	const WP_PROPS = array(
		'post_title' => 'title',
		'post_content' => 'content',
		'post_date' => 'date',
	);
	const ALIASES = array(
		'description' => 'work_description',
		'external_url' => 'work_url',
	);
	const HIDDEN = array(
	);

	// Stored
	protected $title;
	protected $content;
	protected $date;
	protected $image;
	protected $work_description;
	protected $work_url;
	private $permalink;

	/*
	 * Getters
	 */

	public function get_title()
	{
		return $this->get_post_title();
	}

	public function get_content( $apply_filters = false )
	{
		return $this->get_post_content( $apply_filters );
	}

	public function get_date( $format = 'Y-m-d h:i:s' )
	{
		return $this->get_post_date( $format );
	}
	
	public function get_image( $size = 'full' )
	{
		$post_image_id = get_post_thumbnail_id( $this->get_id() );
		$this->image = wp_get_attachment_image_url( $post_image_id, $size );
		return $this->image;
	}

	public function has_image()
	{
		return (bool) $this->get_image();
	}
	
	public function get_work_description()
	{
		return $this->get_prop( 'work_description' );
	}
	
	public function get_description()
	{
		return $this->get_work_description();
	}
	
	public function get_work_url()
	{
		return $this->get_prop( 'work_url' );
	}
	
	public function get_external_url()
	{
		return $this->get_work_url();
	}
	
	public function get_permalink()
	{
		if ( null === $this->permalink ) {
			$this->permalink = get_permalink( $this->get_id() );
		}
		return $this->permalink;
	}
	
	/*
	 * Setters
	 */

	public function set_title( $value )
	{
		return $this->set_prop( 'title', $value );
	}

	public function set_content( $value )
	{
		return $this->set_prop( 'content', $value );
	}

	public function set_date( $value, $format = 'Y-m-d h:i:s' )
	{
		return $this->set_prop( 'date', $this->to_datetime( $value, $format ) );
	}
	
	public function set_image( $value )
	{
		return $this->set_prop( 'image', $value );
	}
	
	public function set_work_description( $value )
	{
		return $this->set_prop( 'work_description', $value );
	}
	
	public function set_work_url( $value )
	{
		return $this->set_prop( 'work_url', $value );
	}

	/*
	 * Savers
	 */

	public function save_title_meta( $value )
	{
		return $this->save_post_title( $value );
	}

	public function save_content_meta( $value )
	{
		if ( is_array( $value ) || ! $value ) {
			$value = ' ';
		}
		return $this->save_post_content( $value );
	}

	public function save_date_meta( $value, $return_format = '' )
	{
		return $this->save_post_date( $this->to_datetime( $value ), $return_format );
	}
	
	/*
	 * Helpers
	 */

	public function has( $prop )
	{
		$getter = "get_{$prop}";
		return $this->has_prop( $prop ) && is_callable( array( $this, $getter ) ) && (bool) $this->{$getter}();
	}
}
