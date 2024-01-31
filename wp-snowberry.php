<?php

/**
 * Plugin Name: WP Snowberry
 * Plugin URI: https://snowberrymedia.com/
 * Description: WP Snowberry Plugin
 * Version: 0.1
 * Author: The Snowberry Team
 * Author URI: https://snowberrymedia.com/
 *
 * Text Domain: wp-snowberry
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_Snowberry' ) ) :

	final class WP_Snowberry
	{
		/**
		 * @var string
		 */
		public $version = '0.1';

		/**
		 * @var string
		 */
		public $text_domain = 'wp-snowberry';

		/**
		 * Site data
		 * @var null
		 */
		private $site = null;
		
		/**
		 * Factory for returning work items
		 * @var null
		 */
		private $work_factory = null;

		/**
		 * @var WP_Snowberry The single instance of the class
		 * @since 0.1
		 */
		protected static $instance = null;

		/**
		 * Main Instance
		 *
		 * Ensures only one instance is loaded or can be loaded.
		 *
		 * @since 0.1
		 * @static
		 * @return WP_Snowberry - Main instance
		 */
		public static function instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct()
		{
			$this->define_constants();

			/**
			 * Once plugins are loaded, initialize
			 */
			add_action( 'plugins_loaded', array( $this, 'setup' ), -15 );
		}

		/**
		 * Define WC Constants
		 */
		private function define_constants()
		{
			global $wpdb;
			$upload_dir = wp_upload_dir();
			$this->define( 'WP_SNOWBERRY_PLUGIN_FILE', __FILE__ );
			$this->define( 'WP_SNOWBERRY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			$this->define( 'WP_SNOWBERRY_TEXT_DOMAIN', $this->text_domain );
			$this->define( 'WP_SNOWBERRY_VERSION', $this->version );

			// Optional
			$this->define( 'WPMVC_ACTION_SCHEDULER_IS_ENABLED', false );
		}

		/**
		 * Setup needed includes and actions for plugin
		 * @hooked plugins_loaded -15
		 */
		public function setup()
		{
			$this->includes();
			$this->init_factories();
		}

		/**
		 * Define constant if not already set
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value )
		{
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * What type of request is this?
		 * string $type ajax, frontend or admin
		 * @return bool
		 */
		public function is_request( $type )
		{
			switch ( $type ) {
				case 'admin' :
					return is_admin();
				case 'ajax' :
					return defined( 'DOING_AJAX' );
				case 'cron' :
					return defined( 'DOING_CRON' );
				case 'frontend' :
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}
		}

		/**
		 * Include required files used in admin and on the frontend.
		 */
		public function includes()
		{
			// Models
			// WP_Snowberry\Models\Site
			include_once $this->plugin_path() . '/includes/models/Site.php';
			// WP_Snowberry\Models\Work
			include_once $this->plugin_path() . '/includes/models/Work.php';

			// Core
			include_once $this->plugin_path() . '/includes/core/helpers.php';
			include_once $this->plugin_path() . '/includes/core/media.php';
			include_once $this->plugin_path() . '/includes/core/post-types.php';
			include_once $this->plugin_path() . '/includes/core/custom-fields.php';
			include_once $this->plugin_path() . '/includes/core/form-handler.php';
			include_once $this->plugin_path() . '/includes/core/work-factory.php';

			// IMPORTANT - Controllers must be included after Models.
			// This is because cron actions hooked/defined in Controllers will fire as soon as the Controller is included and the add_action() with cron hook name is called.
			// If the Controller uses a Model in the Cron action callback, that Model will NOT have been included yet.
			// Controllers
			include_once $this->plugin_path() . '/includes/controllers/snowberry-site.php';
			include_once $this->plugin_path() . '/includes/controllers/snowberry-template.php';
			include_once $this->plugin_path() . '/includes/controllers/snowberry-services.php';
			include_once $this->plugin_path() . '/includes/controllers/snowberry-work.php';
			include_once $this->plugin_path() . '/includes/controllers/admin/snowberry-work-admin.php';
		}

		/**
		 * Create factories to create new class instances
		 */
		public function init_factories()
		{
			$this->work_factory = new \WP_Snowberry\Core\Work_Factory;
		}

		public function Site()
		{
			if ( null === $this->site ) {
				$this->site = new \WP_Snowberry\Models\Site();
			}
			return $this->site;
		}
		
		/**
		 * Return the collection of Work items
		 */
		public function Work_Collection()
		{
			return $this->work_factory;
		}

		/**
		 * Return the Model of an work item
		 * @param  mixed $work_item    item
		 */
		public function Work( $work_item = 0, $by_unique_id = null )
		{
			return $this->work_factory->get( $work_item, $by_unique_id );
		}
		
		/**
		 * Get queue instance.
		 *
		 * @return Action_Queue
		 */
		public function queue()
		{
			return WP_MVC()->queue();
		}

		/**
		 * Get helpers instance.
		 *
		 * @return Helpers
		 */
		public function Helpers()
		{
			return \WP_Snowberry\Core\Helpers::instance();
		}

		/**
		 * Get media instance.
		 *
		 * @return Helpers
		 */
		public function Media()
		{
			return \WP_Snowberry\Core\Media::instance();
		}
		
		/**
		 * Load the view
		 */
		public function view( $template, $data = array() )
		{
			if ( ! empty( $data ) ) {
				extract( $data );
			}

			ob_start();
			include $this->plugin_path() . '/includes/views/' . $template . '.php';
			return ob_get_clean();
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url()
		{
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path()
		{
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Get the log path.
		 * @return string
		 */
		public function log_path()
		{
			return $this->plugin_path() . '/logs';
		}

		/**
		 * Get Ajax URL.
		 * @return string
		 */
		public function ajax_url()
		{
			return admin_url( 'admin-ajax.php', 'relative' );
		}

		/**
		 * log information to the debug log
		 * @param  string|array $log [description]
		 * @return void
		 */
		public function debug_log()
		{
			$log_location = $this->log_path() . '/wp-snowberry-debug.log';
			$args = func_get_args();
			$log = $this->log( $args );
			error_log( $log, 3, $log_location );
		}

		public function inspect()
		{
			$args = func_get_args();
			$log = $this->log( $args );
			echo '<pre>';
			echo $log;
			echo '</pre>';
		}

		private function log( $args )
		{
			$datetime = new \DateTime( 'NOW' );
			$timestamp = $datetime->format( 'Y-m-d H:i:s' );
			$formatted = array_map( function ( $item ) {
				return print_r( $item, true );
			}, $args );
			array_unshift( $formatted, $timestamp );
			return implode( ' ', $formatted ) . "\n";
		}

	}

	endif;

/**
 * Returns the main instance of WP_Snowberry to prevent the need to use globals.
 *
 * @since  0.1
 * @return WP_Snowberry
 */
function WP_Snowberry()
{
	return WP_Snowberry::instance();
}

WP_Snowberry();
