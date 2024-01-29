<?php

namespace WP_Snowberry\Controllers\Admin;

use \WP_MVC\Controllers\Abstracts\MVC_Controller_Registry;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Snowberry_Work_Admin extends MVC_Controller_Registry
{
	const WORK_TEMPLATE = "<!-- wp:group {\"className\":\"mw-container\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group mw-container\"><!-- wp:heading -->\n<h2 class=\"wp-block-heading\">the challenge</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>description of goals client wanted/needed for project.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->\n\n<!-- wp:image {\"id\":257,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"https://snowberrydev.wpenginepowered.com/wp-content/uploads/2024/01/Dutchware-hero.png\" alt=\"\" class=\"wp-image-257\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:group {\"className\":\"mw-container\",\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group mw-container\"><!-- wp:heading -->\n<h2 class=\"wp-block-heading\">the solution</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>description of work we did to solve client's issues and achieve goals.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:group -->";
	
	/**
	 * constructor
	 */
	public function __construct()
	{
		add_filter( 'default_content', [ $this, 'set_default_work_item_content' ], 10, 2 );
	}

	public function set_default_work_item_content( $content, $post )
	{
		if ( get_post_type( $post ) == 'work' ) {
			return stripslashes( self::WORK_TEMPLATE );
		}
		return $content;
	}
}

Snowberry_Work_Admin::instance();
