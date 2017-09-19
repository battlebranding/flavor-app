<?php

class Mobile_Order_Taxonomy_Menu {

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Load the hooks.
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Register the taxonomy.
	 */
	public function register_taxonomy() {

		$labels = array(
			'name'              => _x( 'Menus', 'taxonomy general name', 'mobile-order' ),
			'singular_name'     => _x( 'Menu', 'taxonomy singular name', 'mobile-order' ),
			'search_items'      => __( 'Search Menus', 'mobile-order' ),
			'all_items'         => __( 'All Menus', 'mobile-order' ),
			'parent_item'       => __( 'Parent Menu', 'mobile-order' ),
			'parent_item_colon' => __( 'Parent Menu:', 'mobile-order' ),
			'edit_item'         => __( 'Edit Menu', 'mobile-order' ),
			'update_item'       => __( 'Update Menu', 'mobile-order' ),
			'add_new_item'      => __( 'Add New Menu', 'mobile-order' ),
			'new_item_name'     => __( 'New Menu Name', 'mobile-order' ),
			'menu_name'         => __( 'Menus', 'mobile-order' ),
		);

		register_taxonomy(
	        'food_menu',
	        'food',
	        array(
	            'labels' => $labels,
	            'public' => true,
				'query_var'         => true,
	            'rewrite' => array(
	            	'slug' => 'menu',
					'with_front'	=> true
	            ),
	            'hierarchical' => true,
	        )
	    );

	}

}
