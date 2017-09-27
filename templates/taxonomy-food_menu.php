<?php get_header( 'restaurant' ); ?>

<?php
	/**
	 * Get all of the top level menus
	 *
	 * @since 0.1.0
	 */
	function get_parent_menus() {
		return get_terms( 'food_menu', array( 'hide_empty' => false, 'parent' => 0, ) );
	}

	function get_food_in_menu( $menu ) {

		$food_menu[ $menu->slug ]['menu_title'] = $menu->name;
		$food_menu[ $menu->slug ]['menu_pdf'] = get_term_meta( $menu->term_id, 'pdf', true );
		$sub_menus = get_terms( 'food_menu', array( 'hide_empty' => false, 'parent' => $menu->term_id ) );

		foreach ( $sub_menus as $sub_menu ) {

			$args = array(
				'post_type' 		=> 'food',
				'posts_per_page'	=> 30,
				'tax_query' => array(
					array(
						'taxonomy' => 'food_menu',
						'field'    => 'slug',
						'terms'    => $sub_menu->slug,
					),
				),
			);

			$query = new WP_Query( $args );

			if ( ! $query->have_posts() ) {
				continue;
			}

			$food = array();

			foreach ( $query->get_posts() as $post ) {

				$food[] = array(
					'title'			=> $post->post_title,
					'description'	=> $post->post_content,
					'price'			=> get_post_meta( $post->ID, 'food_price', true ),
					'tags'			=> wp_get_object_terms( $post->ID, 'food_tag', array( 'fields' => 'names' ) ),
				);

			}

			$food_menu[ $menu->slug ]['items'][] = array(
				'menu_title'		=> $sub_menu->name,
				'menu_description'	=> $sub_menu->description,
				'items'				=> $food,
			);

		}

		return $food_menu;

	}

	/**
	 * Get all of the menus and their items
	 */
	function get_food_menus() {

		if ( $food_menu = get_transient('food_menu') ) {
			return $food_menu;
		}

		$menus = get_terms( 'food_menu', array( 'hide_empty' => false, 'parent' => 0, ) );

		foreach ( $menus as $menu ) {

			$food_menu[ $menu->slug ]['menu_title'] = $menu->name;
			$sub_menus = get_terms( 'food_menu', array( 'hide_empty' => false, 'parent' => $menu->term_id ) );

			foreach ( $sub_menus as $sub_menu ) {

				$args = array(
					'post_type' => 'food',
					'tax_query' => array(
						array(
							'taxonomy' => 'food_menu',
							'field'    => 'slug',
							'terms'    => $sub_menu->slug,
						),
					),
				);

				$query = new WP_Query( $args );

				if ( ! $query->have_posts() ) {
					continue;
				}

				$food = array();

				foreach ( $query->get_posts() as $post ) {

					$food[] = array(
						'title'			=> $post->post_title,
						'description'	=> $post->post_content,
						'price'			=> get_post_meta( $post->ID, 'food_price', true ),
					);

				}

				$food_menu[ $menu->slug ]['items'][] = array(
					'menu_title'	=> $sub_menu->name,
					'items'			=> $food,
				);

			}

		}

		set_transient( 'food_menu', $food_menu, 12 * HOUR_IN_SECONDS );

		return $food_menu;

	}

?>

<?php if ( is_tax( 'food_menu' ) ): ?>
	<section class="restaurant">
		<div class="section-body">
			<div class="wrapper">
				<?php $food_menu = get_term_by( 'slug', get_query_var( 'food_menu' ), 'food_menu' ); ?>
				<h2 class="has-text-centered">Menu: <?php echo esc_attr( $food_menu->name, 'bb-blueprint' ); ?></h2>
				<hr class="is-small" />
				<?php foreach ( get_food_in_menu( $food_menu ) as $menu ): ?>
					<?php if ( $menu['menu_pdf'] ): ?>
						<p class="has-text-centered"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> <a href="<?php echo esc_url( $menu['menu_pdf'] ); ?>">View PDF version</a></p>
					<?php endif; ?>
					<?php foreach ( $menu['items'] as $sub_menu ): ?>
						<h3 class="has-text-centered"><?php esc_html_e( $sub_menu['menu_title'], 'bb-blueprint' ); ?></h3>
						<?php if ( $sub_menu['menu_description'] ): ?>
							<p class="has-text-centered"><?php echo esc_attr( $sub_menu['menu_description'], 'bb-blueprint' ); ?></p>
						<?php endif; ?>
						<ul class="food-menu" style="clear: both; list-style: none; padding-left: 0; overflow: hidden;">
							<?php foreach ( $sub_menu['items'] as $item ): ?>
								<li class="food-menu-item">
									<span class="food-label food-title">
										<?php echo $item['title']; ?>
										<?php if ( $item['tags'] ): ?>
											<span class="food-label food-tags">
												<?php foreach ( $item['tags'] as $tag ): ?>
													<span class="food-tag food-tag-<?php echo sanitize_title( $tag ); ?>"><?php echo $tag; ?></span>
												<?php endforeach; ?>
											</span>
										<?php endif; ?>
									</span>
									<span class="food-label food-price"><?php echo '$' . $item['price']; ?></span>
									<span class="food-label food-description"><?php echo html_entity_decode( $item['description'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php else: ?>
	<section class="restaurant">
		<div class="section-body has-text-centered">
			<div class="wrapper">
				<h2>Menus</h2>
				<hr class="is-small" />
				<div class="columns is-fullwidth-on-mobile">
					<?php foreach( get_parent_menus() as $menu ): ?>
						<div class="column"><h3><a href="<?php echo home_url( 'menu/' . $menu->slug ); ?>"><?php echo esc_html( $menu->name, 'bb-blueprint' ); ?> <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></a></h3></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php get_footer('restaurant'); ?>
