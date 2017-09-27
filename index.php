<?php
/**
 * Plugin Name: Remote Order
 */

/**
 * Autoload the classes for Mobile Order.
 *
 * @since 0.1.0
 *
 * @param string	$class_name 	The class name of the file being initialized.
 */
function mobile_order_autoloader( $class_name ) {

	if ( false !== strpos( $class_name, 'Mobile_Order_' ) ) {

		$class_name = str_replace( 'Mobile_Order_', '', $class_name );
		$class_parts = explode( '_', strtolower( $class_name ) );
		$class_folder = $class_parts[0];

		unset( $class_parts[0] );
		$class_filename = $class_folder . '/class-' . implode( '-', $class_parts ) . '.php';

		require_once( dirname( __FILE__ ) . '/includes/' . $class_filename );

	}

}

spl_autoload_register( 'mobile_order_autoloader' );

class Mobile_Order {

	/**
	 * The unique instance of the plugin.
	 *
	 * @since 0.1.0
	 * @var Mobile_Order
	 */
	private static $instance;

	/**
	 * Assets folder.
	 *
	 * @since 0.1.0
	 */
	public $assets_folder;

	/**
	 * Templates folder.
	 *
	 * @since 0.1.0
	 */
	public $templates_folder;

	/**
	 * Get an instance of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @return Mobile_Order
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	public function __construct() {
		$this->assets_folder	 	= plugin_dir_url( __FILE__ ) . 'assets/';
		$this->templates_folder 	= apply_filters( 'mobile_order_templates_folder', plugin_dir_path( __FILE__ ) . 'templates/' );
	}

	/**
	 * Initialize the plugin.
	 */
	public function load() {
		$this->classes();
		$this->hooks();
	}

	/**
	 * Load all classes.
	 */
	public function classes() {

		$food_cpt	 	= new Mobile_Order_CPT_Food();
		$food_menu 		= new Mobile_Order_Taxonomy_Menu();
		$food_topping 	= new Mobile_Order_Taxonomy_Food_Topping();
		$order			= new Mobile_Order_Object_Order();
		$template		= new Mobile_Order_Object_Template( $this );
		$food_template	= new Mobile_Order_Template_Food( $this );
		$food_tag		= new Mobile_Order_Taxonomy_Tag();

	}

	/**
	 * Load all hooks.
	 */
	public function hooks() {

	}

}

add_action( 'plugins_loaded', array( Mobile_Order::get_instance(), 'load' ) );
