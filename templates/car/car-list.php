<?php
/*
 * Car List
 */

global $car_list, $current_view, $before_article, $after_article;
foreach( $car_list as $car_id ) {
	$car_id = trav_car_clang_id( $car_id );
	$price = get_post_meta( $car_id, 'trav_car_price', true );
	$brief = get_post_meta( $car_id, 'trav_car_brief', true );
	$discount_rate = get_post_meta( $car_id, 'trav_car_discount_rate', true );
	if ( empty( $brief ) ) {
		$brief = apply_filters('the_content', get_post_field('post_content', $car_id));
		$brief = wp_trim_words( $brief, 20, '' );
	}
	
	$car_type = wp_get_post_terms( $car_id, 'car_type' );
	if ( ! empty( $car_type ) ) {
		$car_type = $car_type[0]->name;
	} else {
		$car_type = "";
	}

	$car_preferences = wp_get_post_terms( $car_id, 'preference' );
	$car_mileage = get_post_meta( $car_id, 'trav_car_mileage', true );
	$car_pick_up_time = get_post_meta( $car_id, 'trav_car_pick_up_time', true );
	$car_location = get_post_meta( $car_id, 'trav_car_location', true );
	$car_logo = get_post_meta( $car_id, 'trav_car_logo', true );

	$date_from = isset( $_REQUEST['date_from'] ) ? trav_sanitize_date( $_REQUEST['date_from'] ) : '';
	$date_to = isset( $_REQUEST['date_to'] ) ? trav_sanitize_date( $_REQUEST['date_to'] ) : '';
	if ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) {
		$date_from = '';
		$date_to = '';
	}

	$time = array( 'anytime' => __( 'Anytime', 'trav' ), 'morning' => __( 'Morning', 'trav' ), 'afternoon' => __( 'Afternoon', 'trav' ) );

	$query_args = array(
					'date_from' => $date_from,
					'date_to' => $date_to
					);
	$url = esc_url( add_query_arg( $query_args, get_permalink( $car_id ) ) );
	echo ( $before_article );

	if ( $current_view == 'block' ) { ?>
		
		<article class="box">
			<figure>
                <a href="#" title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $car_id );?>"><?php echo get_the_post_thumbnail( $car_id, 'gallery-thumb' ); ?></a>
            	<?php if ( ! empty( $discount_rate ) ) { ?>
					<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
				<?php } ?>    
            </figure>
            <div class="details">
                <a title="<?php _e( 'View Detail', 'trav' ); ?>" href="<?php echo esc_url( $url ); ?>" class="pull-right button btn-mini uppercase"><?php _e( 'SELECT', 'trav' ); ?></a>
                <h4 class="box-title"><?php echo esc_html( $car_type ) ?></h4>
                <label class="price-wrapper">
                    <span class="price-per-unit"><?php echo esc_html( trav_get_price_field( $price ) ); ?></span><?php _e( 'per day', 'trav' ) ?>
                </label>
            </div>
        </article>

	<?php } elseif ( $current_view == 'grid' ) { ?>

		<article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $car_id );?>" href="#"><?php echo get_the_post_thumbnail( $car_id, 'gallery-thumb' ); ?></a>
            	<?php if ( ! empty( $discount_rate ) ) { ?>
					<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
				<?php } ?>
            </figure>
            <div class="details">
				<?php if ( ! empty( $price ) && is_numeric( $price ) ) { ?>
					<span class="price"><small><?php _e( 'per day', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $price ) ); ?></span>
				<?php } ?>
                <h4 class="box-title"><?php echo esc_html( $car_type ) ?><small><?php echo esc_html( get_the_title( $car_id ) ); ?></small></h4>
                <div class="amenities">
                	<ul>
						<?php
						$preference_icons = get_option( "preference_icon" );
						$preference_short_name = get_option( "preference_short_name" );
						$preference_html = '';
						foreach ( $car_preferences as $preference ) {
							if ( is_array( $preference_short_name ) && ! empty( $preference_short_name[ $preference->term_id ] ) ) {
								$preference_s_name = $preference_short_name[ $preference->term_id ];
							} else {
								$preference_s_name = substr( $preference->name, 0, 1 );
							}

							if ( is_array( $preference_icons ) && isset( $preference_icons[ $preference->term_id ] ) ) {
								$preference_html .= '<li>';
								if ( isset( $preference_icons[ $preference->term_id ]['uci'] ) ) {
									//$preference_html .= '<img class="custom_amenity" title="' . esc_attr( $preference->name ) . '" src="' . esc_url( $preference_icons[ $preference->term_id ]['url'] ) . '" height="29" alt="amenity-image">' . esc_html( $preference->name );
									$preference_html .= '<img class="custom_amenity" title="' . esc_attr( $preference->name ) . '" src="' . esc_url( $preference_icons[ $preference->term_id ]['url'] ) . '" height="29" alt="amenity-image">' . esc_html( $preference_s_name );
								} else if ( isset( $preference_icons[ $preference->term_id ]['icon'] ) ) {
									$_class = $preference_icons[ $preference->term_id ]['icon'];
									//$preference_html .= '<i class="' . esc_attr( $_class ) . ' circle" title="' . esc_attr( $preference->name ) . '"></i>' . esc_html( $preference->name );
									$preference_html .= '<i class="' . esc_attr( $_class ) . ' circle" title="' . esc_attr( $preference->name ) . '"></i>' . esc_html( $preference_s_name );
								}
								$preference_html .= '</li>';
							}								
						}
						echo wp_kses_post( $preference_html );
						?>
					</ul>
                </div>
                <p class="mile"><span class="skin-color"><?php _e( 'Mileage:', 'trav' ); ?></span> <?php echo ( isset( $car_mileage ) && is_numeric( $car_mileage ) )?__( 'up to', 'trav' ) . ' ' . $car_mileage . __( 'miles', 'trav' ):__( 'unlimited', 'trav' ); ?></p>
                <div class="action">
                    <a class="button btn-small full-width" href="<?php echo esc_url( $url ); ?>"><?php _e( 'SELECT NOW', 'trav' ); ?></a>
                </div>
            </div>
        </article>

	<?php } else { ?>

		<article class="box">
			<figure class="col-xs-3">
				<span>
					<a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $car_id );?>" href="#"><?php echo get_the_post_thumbnail( $car_id, 'gallery-thumb' ); ?></a>
				</span>
				<?php if ( ! empty( $discount_rate ) ) { ?>
					<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
				<?php } ?>
			</figure>
			<div class="details col-xs-9 clearfix">
                <div class="col-sm-8">
                    <div class="clearfix">
                        <h4 class="box-title"><?php echo esc_html( $car_type ) ?><small><?php echo esc_html( get_the_title( $car_id ) ); ?></small></h4>
                        <div class="logo">
                            <?php if ( isset( $car_logo ) ) { ?>
								<img width="70" height="20" src="<?php echo esc_url( wp_get_attachment_url( $car_logo ) );?>" alt="Car Logo">
							<?php } ?>
                        </div>
                    </div>
                    <div class="amenities">
                        <ul>
                        	<?php
							$preference_icons = get_option( "preference_icon" );
							$preference_short_name = get_option( "preference_short_name" );
							$preference_html = '';
							foreach ( $car_preferences as $preference ) {
								if ( is_array( $preference_short_name ) && ! empty( $preference_short_name[ $preference->term_id ] ) ) {
									$preference_s_name = $preference_short_name[ $preference->term_id ];
								} else {
									$preference_s_name = substr( $preference->name, 0, 1 );
								}

								if ( is_array( $preference_icons ) && isset( $preference_icons[ $preference->term_id ] ) ) {
									$preference_html .= '<li>';
									if ( isset( $preference_icons[ $preference->term_id ]['uci'] ) ) {
										$preference_html .= '<img class="custom_amenity" title="' . esc_attr( $preference->name ) . '" src="' . esc_url( $preference_icons[ $preference->term_id ]['url'] ) . '" height="29" alt="amenity-image">' . esc_html( $preference_s_name );;
									} else if ( isset( $preference_icons[ $preference->term_id ]['icon'] ) ) {
										$_class = $preference_icons[ $preference->term_id ]['icon'];
										$preference_html .= '<i class="' . esc_attr( $_class ) . ' circle" title="' . esc_attr( $preference->name ) . '"></i>' . esc_html( $preference_s_name );
									}
									$preference_html .= '</li>';
								}								
							}
							echo wp_kses_post( $preference_html );
							?>
						</ul>
                    </div>
	             </div>
                <div class="col-xs-6 col-sm-2 character">
                    <dl class="">
                       	<dt class="skin-color"><?php _e( 'mileage', 'trav' ); ?></dt><dd><?php echo ( isset( $car_mileage ) && is_numeric( $car_mileage ) ) ? $car_mileage . __( 'miles', 'trav' ) : __( 'unlimited', 'trav' ); ?></dd>
                       	<dt class="skin-color"><?php _e( 'pickup time', 'trav' ); ?></dt><dd><?php echo ( isset( $car_pick_up_time ) && isset( $time[$car_pick_up_time] ) ) ? $time[$car_pick_up_time] : __( 'anytime', 'trav' ); ?></dd>
                        <?php if ( isset( $car_location ) ) { ?>
                        	<dt class="skin-color"><?php _e( 'location', 'trav' ); ?></dt><dd><?php echo $car_location; ?></dd>
                        <?php } ?> 
                    </dl>
                </div>
                <div class="action col-xs-6 col-sm-2">
                    <span class="price"><small><?php _e( 'per day', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $price ) ); ?></span>
                    <a class="button btn-small full-width" href="<?php echo esc_url( $url ); ?>"><?php _e( 'select', 'trav' ); ?></a>
                </div>
            </div>
		</article>

	<?php }
	echo ( $after_article );
}