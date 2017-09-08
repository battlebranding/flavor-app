<?php

class Mobile_Order_Taxonomy_Category {
	
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
			'name'              => _x( 'Categories', 'taxonomy general name', 'mobile-order' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'mobile-order' ),
			'search_items'      => __( 'Search Categories', 'mobile-order' ),
			'all_items'         => __( 'All Categories', 'mobile-order' ),
			'parent_item'       => __( 'Parent Category', 'mobile-order' ),
			'parent_item_colon' => __( 'Parent Category:', 'mobile-order' ),
			'edit_item'         => __( 'Edit Category', 'mobile-order' ),
			'update_item'       => __( 'Update Category', 'mobile-order' ),
			'add_new_item'      => __( 'Add New Category', 'mobile-order' ),
			'new_item_name'     => __( 'New Category Name', 'mobile-order' ),
			'menu_name'         => __( 'Categories', 'mobile-order' ),
		);
	
		register_taxonomy(
	        'food_category',
	        'food',
	        array(
	            'labels' => $labels,
	            'public' => true,
	            'rewrite' => array(
	            	'slug' => 'categories'
	            ),
	            'hierarchical' => false,
	        )
	    );
    
	}
	
}