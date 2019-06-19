<?php
$user_id = get_current_user_id();
$user_info = trav_get_current_user_info();
?>
<h1 class="no-margin skin-color"><?php printf( __( 'Hi %s, Welcome to %s', 'trav' ), $user_info['display_name'], get_bloginfo('name') ) ?></h1>
<br />
<div class="row block">
	<div class="col-md-6 notifications">
		<h2><?php printf( __( 'What\'s New On %s' ,'trav' ), get_bloginfo('name') ) ?></h2>
		<?php 
			$list_size = 8;
			$available_post_types = trav_get_available_modules();
			$available_post_types[] = 'post';
			$args = array(
					'posts_per_page' => $list_size,
					'orderby' => 'date',
					'order' => 'desc',
					'post_status' => 'publish',
					'post_type' => $available_post_types
				);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$post_type = get_post_type( get_the_id() );
		?>
					<a href="<?php the_permalink(); ?>">
						<div class="icon-box style1 fourty-space">
							<?php if ( $post_type == 'accommodation' ) { ?>

								<i class="soap-icon-hotel blue-bg"></i>
								<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
								<p class="box-title"><?php the_title(); ?> in <span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $the_query->post->ID, 'trav_accommodation_avg_price', true ) ) ) ?></span></p>

							<?php } elseif ( $post_type == 'tour' ) { ?>

								<i class="soap-icon-beach yellow-bg"></i>
								<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
								<p class="box-title"><?php the_title(); ?> in <span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $the_query->post->ID, 'trav_tour_min_price', true ) ) ) ?></span></p>

							<?php } elseif ( $post_type == 'car' ) { ?>

								<i class="soap-icon-beach dark-blue-bg"></i>
								<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
								<p class="box-title"><?php the_title(); ?> in <span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $the_query->post->ID, 'trav_car_price', true ) ) ) ?></span></p>

							<?php } elseif ( $post_type == 'cruise' ) { ?>

								<i class="soap-icon-beach red-bg"></i>
								<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
								<p class="box-title"><?php the_title(); ?> in <span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $the_query->post->ID, 'trav_cruise_avg_price', true ) ) ) ?></span></p>

							<?php } elseif ( $post_type == 'post' ) { ?>

								<i class="soap-icon-magazine green-bg"></i>
								<span class="time pull-right"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
								<p class="box-title"><?php the_title(); ?></p>

							<?php } ?>
						</div>
					</a>
		<?php
				}
			} else {
				echo __( "Nothing New.", "trav" );
			}
			wp_reset_postdata();
		?>
	</div>
	<div class="col-md-6">
		<h2><?php echo __( 'Recent Activity', 'trav' ) ?></h2>
		<div class="recent-activity">
			<ul>

				<?php
					$recent_activity = get_user_meta( $user_id, 'recent_activity', true );
					if ( ! empty( $recent_activity ) ) {
						$recent_activity_array = unserialize( $recent_activity );
						foreach ( $recent_activity_array as $post_id ) {
							$post_id = trav_acc_clang_id( $post_id );
							$post_type = get_post_type( $post_id );
							if ( ! in_array( $post_type, $available_post_types ) ) continue;
							if ( $post_type == 'accommodation' ) {
								$_country = get_post_meta( $post_id, 'trav_accommodation_country', true );
								if ( ! empty( $_country ) ) {
									if ( $country_obj = get_term_by( 'id', $_country, 'location' ) ) $_country = __( $country_obj->name, 'trav');
								}

								$_city = get_post_meta( $post_id, 'trav_accommodation_city', true );
								if ( ! empty( $_city ) ) {
									if ( $user_info['city_obj'] = get_term_by( 'id', $_city, 'location' ) ) $_city = __( $user_info['city_obj']->name, 'trav');
								} ?>

								<li>
									<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
										<i class="icon soap-icon-hotel circle blue-color"></i>
										<span class="price"><small><?php _e( 'avg/night', 'trav' ); ?></small><?php echo esc_html( trav_get_price_field( get_post_meta( $post_id, 'trav_accommodation_avg_price', true ) ) ); ?></span>
										<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo esc_html( $_city . ' ' . $_country ) ?></small></h4>
									</a>
								</li>

							<?php } elseif ( $post_type == 'tour' ) {
								$_country = get_post_meta( $post_id, 'trav_tour_country', true );
								if ( ! empty( $_country ) ) {
									if ( $country_obj = get_term_by( 'id', $_country, 'location' ) ) $_country = __( $country_obj->name, 'trav');
								}

								$_city = get_post_meta( $post_id, 'trav_tour_city', true );
								if ( ! empty( $_city ) ) {
									if ( $user_info['city_obj'] = get_term_by( 'id', $_city, 'location' ) ) $_city = __( $user_info['city_obj']->name, 'trav');
								} ?>

								<li>
									<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
										<i class="icon soap-icon-beach circle yellow-color"></i>
										<span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $post_id, 'trav_tour_min_price', true ) ) ); ?></span>
										<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo esc_html( $_city . ' ' . $_country ) ?></small></h4>
									</a>
								</li>

							<?php } elseif ( $post_type == 'car' ) {
								$car_type = wp_get_post_terms( $post_id, 'car_type' );

								if ( ! empty( $car_type ) ) {
									$car_type = $car_type[0]->name;
								} else {
									$car_type = "";
								}
								?>

								<li>
									<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
										<i class="icon soap-icon-beach circle dark-blue-color"></i>
										<span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $post_id, 'trav_car_price', true ) ) ); ?></span>
										<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo esc_html( $car_type ) ?></small></h4>
									</a>
								</li>

							<?php } elseif ( $post_type == 'cruise' ) {
								$ship_name = get_post_meta( $post_id, 'trav_cruise_ship_name', true );
								?>

								<li>
									<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
										<i class="icon soap-icon-beach circle light-blue-color"></i>
										<span class="price"><?php echo esc_html( trav_get_price_field( get_post_meta( $post_id, 'trav_cruise_avg_price', true ) ) ); ?></span>
										<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo esc_html( $ship_name ) ?></small></h4>
									</a>
								</li>

							<?php } elseif ( $post_type == 'post' ) {
								$post_author_id = get_post_field( 'post_author', $post_id ); ?>

								<li>
									<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
										<i class="icon soap-icon-magazine circle green-color"></i>
										<span class="price"><small><?php _e( 'posted by', 'trav' ); ?>: <span><?php echo esc_html( get_the_author_meta( 'display_name', $post_author_id ) ); ?></span></small></span>
										<h4 class="box-title"><?php echo esc_html( get_the_title( $post_id ) ); ?><small><?php echo wp_kses_post( get_the_date( '', $post_id ) ) ?></small></h4>
									</a>
								</li>

							<?php }
						}
					} else {
						echo __( "You don't have any recent activities.", "trav" );
					}
				?>
			</ul>
		</div>
	</div>
</div>
<hr>