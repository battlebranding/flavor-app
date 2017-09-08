<?php

/**
 * The Mobile_Order_Template_Food class.
 */
class Mobile_Order_Template_Food {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

		add_action( 'mobile_order_display_food_description', array( $this, 'display_food_description' ), 10 );
		add_action( 'mobile_order_display_food_price', array( $this, 'display_food_price' ), 10 );
		add_action( 'mobile_order_display_included_toppings', array( $this, 'display_included_toppings' ), 10 );
		add_action( 'mobile_order_display_extra_toppings', array( $this, 'display_extra_toppings' ), 10 );
		add_action( 'mobile_order_display_other_flavors', array( $this, 'display_other_flavors' ), 10 );
		add_action( 'mobile_order_display_add_to_order_button', array( $this, 'display_add_to_order_button' ), 10 );
	}

	public function load_scripts() {
		wp_enqueue_script( 'food', $this->plugin->assets_folder . 'js/food.js', array(), '0.1.0', true );
	}

	public function display_food_description() {

		global $post;
		?>
			<div class="food-detail food-description"><?php echo wpautop( $post->post_content ); ?></div>
		<?php
	}

	public function display_food_price() {

		$price 	= get_post_meta( get_the_ID(), 'food_price', true );
		$price 	= ( $price ) ? '$' . $price : __( 'Free', 'mobile-order' );

		?>
			<div class="food-detail food-price" style="margin-bottom: 20px;"><?php echo $price; ?></div>
		<?php
	}

	public function display_included_toppings() {

		$toppings = get_post_meta( get_the_ID(), 'food_toppings_list', true );

		if ( empty( $toppings ) ) {
			return;
		}

		?>
			<div class="toppings">
				<h4><?php echo __( 'Includes', 'mobile-order' ); ?></h4>
				<ul id="toppings-list" data-alternate-list="extra-toppings-list">
					<?php foreach( $toppings as $topping ): ?>
						<li style="display: inline-block; text-align: center; font-size: 12px;">
							<a href="#" class="topping" style="display: block;">
								<span style="background-color: #eee; width: 100px; height: 100px; display: block; margin-bottom: 1em;"></span>
								<span class="label"><?php echo esc_attr( $topping ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
	}

	public function display_extra_toppings() {

		$extra_toppings = get_post_meta( get_the_ID(), 'food_extra_toppings_list', true );

		if ( empty( $extra_toppings ) ) {
			return;
		}

		?>
			<div class="extra-toppings">
				<h4><?php echo __( 'Extras', 'mobile-order' ); ?></h4>
				<ul id="extra-toppings-list" data-alternate-list="toppings-list">
					<?php foreach( $extra_toppings as $topping ): ?>
						<li style="display: inline-block; text-align: center; font-size: 12px;">
							<a href="#" class="topping" style="display: block;">
								<span style="background-color: #eee; width: 100px; height: 100px; display: block; margin-bottom: 1em;"></span>
								<span class="label"><?php echo esc_attr( $topping ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
	}

	public function display_other_flavors() {

		$types = get_children( array( 'post_parent' => get_the_ID() ) );

		?>
			<div class="types">
				<h4>Other Flavors</h4>
				<ul>
					<?php foreach( $types as $type ): ?>
						<li style="display: inline-block; text-align: center;">
							<a href="<?php echo get_the_permalink( $type->ID ); ?>"><?php echo esc_attr( $type->post_title ); ?> <?php echo $price; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
	}

	public function display_add_to_order_button() {

		$price 	= get_post_meta( get_the_ID(), 'food_price', true );
		$price 	= ( $price ) ? '$' . $price : __( 'Free', 'mobile-order' );

		?>
			<button type="submit"><?php echo $price ?> - <?php echo __( 'Add Item', 'mobile-order' ); ?></button>
		<?php
	}

}
