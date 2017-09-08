<?php

/**
 * The Mobile_Order_CPT_Food class.
 */
class Mobile_Order_CPT_Food {

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
		add_action( 'init', array( $this, 'register_cpt' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 10 );
		add_filter( 'manage_food_posts_columns', array( $this, 'add_meta_to_admin_columns' ), 10, 1 );
		add_filter( 'manage_food_posts_custom_column', array( $this, 'add_column_content_for_meta' ), 10, 2 );

		// add_action( 'save_post_food', array( $this, 'save_toppings' ), 99, 2 );
		add_action( 'updated_post_meta', array( $this, 'save_extra_toppings' ), 20, 4 );
		// add_filter( 'the_content', array( $this, 'show_food' ), 10, 1 );
	}

	/**
	 * Register the post type.
	 *
	 * @since 0.1.0
	 */
	public function register_cpt() {

		$args = array(
			'public' 		=> true,
      		'label'  		=> __( 'Food', 'mobile-order' ),
      		'menu_icon' 	=> 'dashicons-carrot',
			'hierarchical'	=> true,
			'supports'		=> array( 'title', 'editor', 'page-attributes' )
    	);

    	register_post_type( 'food', $args );

	}

	/**
	 * Add the meta box for food details.
	 *
	 * @since 0.1.0
	 */
	public function add_meta_box() {
		add_meta_box( 'mo-food-details', __( 'Food Details', 'mobile-order' ), array( $this, 'display_meta_box' ), 'food' );
	}

	/**
	 * Display the 'Food Details' metabox
	 *
	 * @since 0.1.0
	 */
	public function display_meta_box() {
		?>
			<table class="form-table editcomment">
				<tbody>
					<tr>
						<td class="first"><label for="price">Price:</label></td>
						<td><input type="text" id="mo-meta-food-price" name="meta_input[food_price]" size="16" autocomplete="off" value="<?php echo get_post_meta( get_the_ID(), 'food_price', true ); ?>" /></td>
					</tr>
					<tr>
						<td class="first"><label for="types">Includes:</label></td>
						<td>
							<p>Please select the toppings this includes</span>:</p>
							<input size="32" /> <input type="button" class="button add-type" value="Add Type">
						</td>
					</tr>
					<!-- <tr>
						<td class="first"><label for="types">Types:</label></td>
						<td>
							<p>Please list the types of <span class="food-name">Wings</span>:</p>
							<input size="32" /> <input type="button" class="button add-type" value="Add Type">
						</td>
					</tr> -->
				</tbody>
			</table>
		<?php
	}

	/**
	 * Create custom columns for the 'Food' meta
	 *
	 * @since 0.1.0
	 *
	 * @param array $columns
	 */
	public function add_meta_to_admin_columns( $columns = array() ) {

		unset( $columns['date'] );

		$columns['price'] 		= __( 'Price', 'mobile-order' );
		$columns['menu'] 		= __( 'Menu', 'mobile-order' );
		$columns['toppings']	= __( 'Toppings', 'mobile-order' );
		$columns['date'] 		= __( 'Date', 'mobile-order' );

		return $columns;

	}

	/**
	 * Displays the content for the custom 'Food' columns
	 *
	 * @since 0.1.0
	 *
	 * @param string $column 	Name of column.
	 * @param integer $post_id 		ID of current post.
	 *
	 * @return string $content 		Term value
	 */
	public function add_column_content_for_meta( $column, $post_id ) {

		switch ( $column ) {

			case 'price':

				$food_price = get_post_meta( $post_id, 'food_price', true );
				echo ( $food_price ) ? '$' . esc_attr( $food_price ) : __( 'Free', 'mobile-order' );

				break;

			case 'menu':

				$menus 		= array();
				$menu_terms = wp_get_object_terms( $post_id, 'food_menu' );

				foreach ( $menu_terms as $menu_term ) {
					$menus[] = "<a href=\"/wp-admin/edit.php?food_menu={$menu_term->slug}&post_type=food\">" . $menu_term->name . "</a>";
				}

				echo implode( ', ', $menus );
				break;

		}

	}

	/**
	 * Retrieves the topping names
	 *
	 * @since 0.1.0
	 *
	 * @param integer $post_id 		The WP Post ID
	 */
	public function save_toppings( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$included_toppings 	= get_post_meta( $post_id, 'food_toppings', true );
		$toppings 			= array();

		foreach ( $included_toppings as $topping_term_id ) {

			$topping_term 	= get_term( $topping_term_id, 'food_topping' );
			$toppings[]	 	= isset( $topping_term->name ) ? $topping_term->name : '';

		}

		update_post_meta( $post_id, 'food_toppings_list', $toppings );

	}

	/**
	 * Retrieves the extra topping names
	 *
	 * @since 0.1.0
	 *
	 * @param integer $post_id 		The WP Post ID
	 */
	public function save_extra_toppings( $meta_id, $post_id, $meta_key = '', $meta_value = '' ) {

		if ( 'food_extra_toppings' == $meta_key ) {

			$toppings = array();

			foreach ( $meta_value as $topping_term_id ) {

				$topping_term 	= get_term( $topping_term_id, 'food_topping' );
				$toppings[]	 	= isset( $topping_term->name ) ? $topping_term->name : '';

			}

			update_post_meta( $post_id, 'food_extra_toppings_list', $toppings );

		}

	}

	public function show_food() {
		do_action( 'mobile_order_display_food_price' );
		do_action( 'mobile_order_display_food_price' );
	}

	/**
	 * Displays the toppings menu on the food page
	 */
	public function show_toppings( $content = '' ) {

		if ( is_single() && ( 'food' == get_post_type() ) ) {

			$price 			= get_post_meta( get_the_ID(), 'food_price', true );
			$price 			= ( $price ) ? '$' . $price : __( 'Free', 'mobile-order' );


			$extra_toppings = get_post_meta( get_the_ID(), 'food_extra_toppings_list', true );

			ob_start();

			echo $price; ?>

			<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

			<?php if ( $types ): ?>

				<div class="types">
					<h4>Other Flavors</h4>
					<ul>
						<?php foreach( $types as $type ): ?>
							<li><a href="<?php echo get_the_permalink( $type->ID ); ?>"><?php echo esc_attr( $type->post_title ); ?> <?php echo $price; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

			<?php endif; if ( $toppings ): ?>
				<div class="toppings">
					<h3>Includes</h3>
					<span class="is-help-text">Click a topping to remove.</span>
					<ul class="toppings-list">
						<?php foreach( $toppings as $topping ): ?>
							<li><a href="#"><?php echo esc_attr( $topping ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; if ( $extra_toppings ): ?>
				<div class="extra-toppings">
					<h3>Extras</h3>
					<span class="is-help-text">Click a topping to add.</span>
					<ul class="extra-toppings-list">
						<?php foreach( $extra_toppings as $extra_topping ): ?>
							<li data-max-count="1"><a href="#"><?php echo esc_attr( $extra_topping ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

				<input type="text" name="food_id" value="<?php echo get_the_ID(); ?>" />
				<input type="text" name="food_toppings" value="<?php print_r( $toppings ); ?>" />
				<input type="hidden" name="action" value="add_to_cart" />
				<button type="submit"><?php echo $price; ?> - Add to Checkout</button>
			</form>

			<?php
			$content .= ob_get_contents();
			ob_end_clean();

		}

		return $content;

	}

}
