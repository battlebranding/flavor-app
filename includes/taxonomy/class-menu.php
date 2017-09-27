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
		add_action( 'food_menu_add_form_fields', array( $this, 'add_order_field' ), 10 );
		add_action( 'food_menu_add_form_fields', array( $this, 'add_pdf_field' ), 10 );
		add_action( 'food_menu_edit_form_fields', array( $this, 'edit_order_field' ), 10, 2 );
		add_action( 'food_menu_edit_form_fields', array( $this, 'edit_pdf_field' ), 10, 2 );
		add_action( 'created_food_menu', array( $this, 'save_order_meta' ), 10, 2 );
		add_action( 'edited_food_menu', array( $this, 'save_order_meta' ), 10, 2 );
		add_action( 'created_food_menu', array( $this, 'save_pdf_meta' ), 10, 2 );
		add_action( 'edited_food_menu', array( $this, 'save_pdf_meta' ), 10, 2 );
		add_filter( 'manage_edit-food_menu_columns', array( $this, 'add_order_column' ) );
		add_filter( 'manage_food_menu_custom_column', array( $this, 'add_order_column_content' ), 10, 3 );
		add_action( 'pre_get_terms', array( $this, 'sort_food_menus' ) );
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

	public function add_order_field() {
	    ?><div class="form-field term-group">
	        <label for="tag-order"><?php _e( 'Display Order', 'flavor-app' ); ?></label>
	        <input name="tag-order" id="tag-order" type="text" value="" size="20" aria-required="true" />
	    </div><?php
	}

	public function add_pdf_field() {
	    ?><div class="form-field term-group">
	        <label for="tag-pdf"><?php _e( 'PDF Version (URL)', 'flavor-app' ); ?></label>
	        <input name="tag-pdf" id="tag-pdf" type="text" value="" size="20" aria-required="true" />
	    </div><?php
	}

	function edit_order_field( $term, $taxonomy ) {

		// get current order
	    $order = get_term_meta( $term->term_id, 'order', true );

	    ?><tr class="form-field term-group-wrap">
	        <th scope="row"><label for="tag-order"><?php _e( 'Display Order', 'flavor-app' ); ?></label></th>
	        <td><input name="tag-order" id="tag-order" type="text" value="<?php echo esc_attr( $order ); ?>" size="20" aria-required="true" /></td>
	    </tr><?php
	}

	function edit_pdf_field( $term, $taxonomy ) {

		// get current order
	    $pdf = get_term_meta( $term->term_id, 'pdf', true );

	    ?><tr class="form-field term-group-wrap">
	        <th scope="row"><label for="tag-pdf"><?php _e( 'PDF Version (URL)', 'flavor-app' ); ?></label></th>
	        <td><input name="tag-pdf" id="tag-pdf" type="text" value="<?php echo esc_attr( $pdf ); ?>" size="20" aria-required="true" /></td>
	    </tr><?php
	}

	public function save_order_meta( $term_id, $tt_id ){
	    if( isset( $_POST['tag-order'] ) && '' !== $_POST['tag-order'] ){
	        $order = sanitize_title( $_POST['tag-order'] );
	        update_term_meta( $term_id, 'order', $order, true );
	    }
	}

	public function save_pdf_meta( $term_id, $tt_id ){
	    if( isset( $_POST['tag-pdf'] ) && '' !== $_POST['tag-pdf'] ){
	        $pdf = sanitize_url( $_POST['tag-pdf'] );
	        update_term_meta( $term_id, 'pdf', $pdf, true );
	    }
	}

	function add_order_column( $columns ){
	    $columns['order'] = __( 'Display Order', 'flavor-app' );
	    return $columns;
	}

	function add_order_column_content( $content, $column_name, $term_id ){

	    if ( $column_name !== 'order' ) {
	        return $content;
	    }

		$content = 0;

	    $term_id = absint( $term_id );
	    $order = get_term_meta( $term_id, 'order', true );

	    if ( ! empty( $order ) ) {
	        $content = esc_attr( $order );
	    }

	    return $content;
	}

	public function sort_food_menus( $query ) {

		if ( in_array( 'food_menu', $query->query_vars['taxonomy'] ) ) {

			$args = array(
				'relation' => 'OR',
	          	'order_clause' => array(
	            	'key' => 'order',
	            	'type' => 'NUMERIC'
	          	),
	          	array(
	            	'key' => 'order',
	            	'compare' => 'NOT EXISTS'
	          	)
	        );

	        $query->meta_query = new WP_Meta_Query( $args );

			$query->query_vars['orderby'] 	= 'meta_value_num';
			// $query->query_vars['meta_key']	= 'order';
			// print_r( $query ); exit;

		}

		return $query;

	}

}
