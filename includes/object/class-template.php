<?php

/**
 * The Mobile_Order_Object_Order class.
 */
class Mobile_Order_Object_Template {

	/**
	 * Parent plugin class.
	 *
	 * @var Mobile_Order
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * The constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Load the hooks.
	 *
	 * @since 0.1.0
	 */
	public function hooks() {
		add_filter( 'template_include', array( $this, 'load_template' ), 20, 1 );
		add_action( 'pre_get_posts', array( $this, 'set_menu_archive' ) );
	}

	/**
	 * Force the menu archive to display
	 *
	 * @since 0.1.0
	 */
	public function set_menu_archive( $query ) {

		if ( 'menu' == get_query_var( 'pagename' ) && ! get_page_by_path( 'menu' ) ) {

			status_header( 200 );
        	$query->is_404 = false;

		}

	}

	/**
	 * Load the correct template from the plugin
	 *
	 * @since 0.1.0
	 */
	public function load_template( $template = '' ) {

		global $wp;

		// Return the menu archive page
		if ( 'menu' == $wp->request ) {
			return $this->locate_template( 'taxonomy-food_menu.php', $template );
		}

		// Return the taxonomy menu
		if ( is_tax( 'food_menu' ) ) {
			return $this->locate_template( 'taxonomy-food_menu.php', $template );
		}

		// Return the individual food page
		if ( is_singular( 'food' ) ) {
			return $this->locate_template( 'single-food.php', $template );
		}

		return $template;

	}

	/**
	 * Check to see if the template exists in the plugin first or return the default
	 *
	 * @since 0.1.0
	 */
	public function locate_template( $plugin_template, $default_template ) {

		$plugin_template = $this->plugin->templates_folder . $plugin_template;

		if ( ! file_exists( $plugin_template ) ) {
			return $default_template;
		}

		return $plugin_template;

	}

}
