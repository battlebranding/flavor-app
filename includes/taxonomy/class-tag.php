<?php

class Mobile_Order_Taxonomy_Tag {

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
			'name'              => _x( 'Tags', 'taxonomy general name', 'mobile-order' ),
			'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'mobile-order' ),
			'search_items'      => __( 'Search Tags', 'mobile-order' ),
			'all_items'         => __( 'All Tags', 'mobile-order' ),
			'parent_item'       => __( 'Parent Tag', 'mobile-order' ),
			'parent_item_colon' => __( 'Parent Tag:', 'mobile-order' ),
			'edit_item'         => __( 'Edit Tag', 'mobile-order' ),
			'update_item'       => __( 'Update Tag', 'mobile-order' ),
			'add_new_item'      => __( 'Add New Tag', 'mobile-order' ),
			'new_item_name'     => __( 'New Tag Name', 'mobile-order' ),
			'menu_name'         => __( 'Tags', 'mobile-order' ),
		);

		register_taxonomy(
	        'food_tag',
	        'food',
	        array(
	            'labels' 		=> $labels,
	            'public' 		=> true,
				'query_var'		=> true,
	            'rewrite'	=> array(
	            	'slug' 			=> 'tag',
					'with_front'	=> true
	            ),
	            'hierarchical' 	=> true,
	        )
	    );

	}

}
