<?php

namespace WP_Snowberry\Core;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Work_Factory extends \WP_MVC\Core\Abstracts\Factory
{
	private $found = array();

	/**
	 * Get an item from the collection by key.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $by_unique_id
	 * @return mixed
	 */
	public function get( $the_work = false, $by_unique_id = null )
	{
		if ( $by_unique_id ) {
			return $this->get_work_by_unique_id( $the_work );
		}

		$work_id = $this->get_work_id( $the_work );
		if ( $work_id && $this->contains( 'id', $work_id ) && $work_id != 0 && ! in_array( $work_id, $this->found ) ) {
			$this->found[] = $work_id;
			return $this->where( 'id', $work_id );
		}

		$Work = new \WP_Snowberry\Models\Work( $work_id );
		$this->add( $Work );

		return $this->last();
	}

	public function get_work_by_unique_id( $the_work )
	{
		$Work = $this->where( '_dayforce_id', $the_work );
		if ( $Work ) {
			return $Work;
		}

		$EmptyWork = new \WP_Snowberry\Models\Work();
		$Work = $EmptyWork->get_by_unique_key( $the_work );
		$this->add( $Work );
		return $this->last();
	}

	/**
	 * Get the work ID depending on what was passed.
	 *
	 * @return int|bool false on failure
	 */
	private function get_work_id( $Work )
	{
		global $post;

		if ( false === $Work && isset( $post, $post->ID ) && 'work' === get_post_type( $post->ID ) ) {
			return absint( $post->ID );
		} elseif ( is_numeric( $Work ) ) {
			return $Work;
		} elseif ( $Work instanceof \WP_Snowberry\Models\Work ) {
			return $Work->get_id();
		} elseif ( ! empty( $Work->ID ) ) {
			return $Work->ID;
		} else {
			return false;
		}
	}

}
