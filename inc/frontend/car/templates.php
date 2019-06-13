<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Single Car Block HTML
 */
if ( ! function_exists( 'trav_car_get_car_list_sigle' ) ) {
	function trav_car_get_car_list_sigle( $car_id, $list_style, $before_article='', $after_article='', $show_badge=false, $animation='' ) {
		echo wp_kses_post( $before_article );

		$price = get_post_meta( $car_id, 'trav_car_price', true );
		$brief = get_post_meta( $car_id, 'trav_car_brief', true );
		if ( empty( $brief ) ) {
			$brief = apply_filters('the_content', get_post_field('post_content', $car_id));
			$brief = wp_trim_words( $brief, 20, '' );
		}
		$discount_rate = get_post_meta( $car_id, 'trav_car_discount_rate', true );
		$car_type = wp_get_post_terms( $car_id, 'car_type' );
		if ( ! empty( $car_type ) ) {
			$car_type = $car_type[0]->name;
		} else {
			$car_type = "";
		}
		$car_preferences = wp_get_post_terms( $car_id, 'preference' );
		$car_mileage = get_post_meta( $car_id, 'trav_car_mileage', true );
		$car_logo = get_post_meta( $car_id, 'trav_car_logo', true );

		if ( $list_style == "style1" || $list_style == "style2" ) { ?>
			<article class="box">
				<figure <?php echo wp_kses_post( $animation ) ?>>
					<a href="#" data-post_id="<?php echo esc_attr( $car_id ) ?>" class="hover-effect popup-gallery"><?php echo get_the_post_thumbnail( $car_id, 'biggallery-thumb' );  ?></a>
					<?php if ( $show_badge && ! empty( $discount_rate ) ) { ?>
						<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
					<?php } ?>
				</figure>
				<div class="details">
					<?php if ( $list_style == "style1" ) { ?>

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
                            <?php if ( isset( $car_mileage ) && is_numeric( $car_mileage ) ) { ?>
                            	<p class="mile"><span class="skin-color"><?php _e( 'Mileage:', 'trav' ); ?></span> <?php echo __( 'up to', 'trav' ) . $car_mileage . __( 'miles', 'trav' ); ?></p>
                            <?php } ?>
                            <div class="action">
                                <a class="button btn-small full-width" href="<?php echo esc_url( get_permalink( $car_id ) ); ?>"><?php _e( 'SELECT NOW', 'trav' ); ?></a>
                            </div>

					<?php } elseif ( $list_style == "style2" ) { ?>

                            <a title="View all" href="<?php echo esc_url( get_permalink( $car_id ) ); ?>" class="pull-right button btn-mini uppercase"><?php _e( 'select', 'trav' ); ?></a>
                            <h4 class="box-title"><?php echo esc_html( $car_type ) ?></h4>
                            <label class="price-wrapper">
                                <span class="price-per-unit"><?php echo esc_html( trav_get_price_field( $price ) ); ?></span><?php _e( 'per day', 'trav' ) ?>
                            </label>

					<?php } ?>
				</div>
			</article>
		<?php } elseif ( $list_style == "style3" ) { ?>
			<article class="box">
				<figure class="col-xs-3">
					<a href="#" data-post_id="<?php echo esc_attr( $car_id ) ?>" class="hover-effect popup-gallery"><?php echo get_the_post_thumbnail( $car_id, 'biggallery-thumb' );  ?></a>
					<?php if ( $show_badge && ! empty( $discount_rate ) ) { ?>
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
											$preference_html .= '<img class="custom_amenity" title="' . esc_attr( $preference->name ) . '" src="' . esc_url( $preference_icons[ $preference->term_id ]['url'] ) . '" height="29" alt="amenity-image">' . esc_html( $preference_s_name );
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
                        	<?php if ( isset( $car_mileage ) && is_numeric( $car_mileage ) ) { ?>
                            	<dt class="skin-color"><?php _e( 'mileage', 'trav' ); ?></dt><dd><?php echo $car_mileage . __( 'miles', 'trav' ); ?></dd>
                            <?php } ?>                            
                        </dl>
                    </div>
                    <div class="action col-xs-6 col-sm-2">
                        <span class="price"><small><?php _e( 'per day', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $price ) ); ?></span>
                        <a class="button btn-small full-width" href="<?php echo esc_url( get_permalink( $car_id ) ); ?>"><?php _e( 'select', 'trav' ); ?></a>
                    </div>
                </div>
            </article>
		<?php }
		echo wp_kses_post( $after_article );
	}
}