<?php

class Mobile_Order_Taxonomy_Food_Topping {
	
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
			'name'              => _x( 'Toppings', 'taxonomy general name', 'mobile-order' ),
			'singular_name'     => _x( 'Topping', 'taxonomy singular name', 'mobile-order' ),
			'search_items'      => __( 'Search Toppings', 'mobile-order' ),
			'all_items'         => __( 'All Toppings', 'mobile-order' ),
			'parent_item'       => __( 'Parent Topping', 'mobile-order' ),
			'parent_item_colon' => __( 'Parent Topping:', 'mobile-order' ),
			'edit_item'         => __( 'Edit Topping', 'mobile-order' ),
			'update_item'       => __( 'Update Topping', 'mobile-order' ),
			'add_new_item'      => __( 'Add New Topping', 'mobile-order' ),
			'new_item_name'     => __( 'New Topping Name', 'mobile-order' ),
			'menu_name'         => __( 'Toppings', 'mobile-order' ),
		);
	
		register_taxonomy(
	        'food_topping',
	        'food',
	        array(
	            'labels' => $labels,
	            'public' => true,
	            'rewrite' => array(
	            	'slug' => 'toppings'
	            ),
	            'hierarchical' => false,
	        )
	    );
    
	}
	
}