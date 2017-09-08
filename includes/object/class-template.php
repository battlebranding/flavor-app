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
	}

	public function load_template( $template = '' ) {

		if ( is_singular( 'food' ) ) {
			return $this->plugin->templates_folder . 'single-food.php';
		}

		return $template;

	}

}
