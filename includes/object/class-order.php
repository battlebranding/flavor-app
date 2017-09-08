<?php

/**
 * The Mobile_Order_Object_Order class.
 */
class Mobile_Order_Object_Order {

	/**
	 * The constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Load the hooks.
	 *
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_post_nopriv_add_to_cart', array( $this, 'add_food_to_order' ) );
		add_action( 'admin_post_add_to_cart', array( $this, 'add_food_to_order' ) );
	}

	public function add_food_to_order() {
		print_r( $_POST['food_toppings'] ); exit;

		$this->get_order();
	}

	public function get_order() {

	}

}
