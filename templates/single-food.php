<?php get_header(); ?>

<div <?php post_class(); ?> style="max-width: 1000px; margin: 0 auto; padding-left: 3em; padding-right: 3em;">
	<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
		<?php $menus = wp_get_object_terms( get_the_ID(), 'food_menu' ); ?>
		<p>Back to <a href="<?php echo home_url('menus'); ?>/<?php echo esc_attr( $menus[0]->slug ); ?>"><strong><?php echo esc_attr( $menus[0]->name ); ?></strong></a></p>
		<div style="border-bottom: 1px solid #EEE; overflow: hidden; padding-bottom: 2em;">
			<div class="food-thumbnail" style="background-color: #eee; display: block; width: 300px; height: 300px; float: left; margin-right: 100px;"></div>
			<div class="food-details" style="float: left; max-width: 500px; padding-top: 1em;">
				<h1><?php the_title(); ?></h1>
				<?php do_action( 'mobile_order_display_food_description' ); ?>
				<?php do_action( 'mobile_order_display_add_to_order_button' ); ?>
			</div>
		</div>
		<div style="padding-top: 2em;">
			<?php do_action( 'mobile_order_display_included_toppings' ); ?>
			<?php do_action( 'mobile_order_display_extra_toppings' ); ?>
		</div>
	</form>
</div>

<?php get_footer(); ?>
