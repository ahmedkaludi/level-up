<?php
/*
 * Accommodation List
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $acc_list, $current_view, $before_article, $after_article;

foreach( $acc_list as $acc_id ) {
    $acc_id = trav_acc_clang_id( $acc_id );
    $avg_price = get_post_meta( $acc_id, 'trav_accommodation_avg_price', true );
    $review = get_post_meta( trav_acc_org_id( $acc_id ), 'review', true );
    $review = ( ! empty( $review ) )?round( $review, 1 ):0;
    $discount_rate = get_post_meta( $acc_id, 'trav_accommodation_discount_rate', true );
    $brief = get_post_meta( $acc_id, 'trav_accommodation_brief', true );
    if ( empty( $brief ) ) {
        $brief = apply_filters('the_content', get_post_field('post_content', $acc_id));
        $brief = wp_trim_words( $brief, 20, '' );
    }
    $loc = get_post_meta( $acc_id, 'trav_accommodation_loc', true );

    $rooms = ( isset( $_REQUEST['rooms'] ) && is_numeric( $_REQUEST['rooms'] ) ) ? sanitize_text_field( $_REQUEST['rooms'] ) : 1;
    $adults = ( isset( $_REQUEST['adults'] ) && is_numeric( $_REQUEST['adults'] ) ) ? sanitize_text_field( $_REQUEST['adults'] ) : 1;
    $kids = ( isset( $_REQUEST['kids'] ) && is_numeric( $_REQUEST['kids'] ) ) ? sanitize_text_field( $_REQUEST['kids'] ) : 0;
    $child_ages = ( isset( $_REQUEST['child_ages'] ) && is_array( $_REQUEST['child_ages'] ) ) ? $_REQUEST['child_ages'] : array();

    $date_from = isset( $_REQUEST['date_from'] ) ? trav_sanitize_date( $_REQUEST['date_from'] ) : '';
    $date_to = isset( $_REQUEST['date_to'] ) ? trav_sanitize_date( $_REQUEST['date_to'] ) : '';
    if ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) {
        $date_from = '';
        $date_to = '';
    }

    $query_args = array(
        'adults'=> $adults,
        'kids' => $kids,
        'rooms' => $rooms,
        'date_from' => $date_from,
        'date_to' => $date_to
    );
    if ( ! empty( $child_ages ) ) $query_args['child_ages'] = $child_ages;
    $url = esc_url( add_query_arg( $query_args, get_permalink( $acc_id ) ) );

    echo ( $before_article );

    if ( $current_view == 'block' ) { ?>

        <article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $acc_id );?>" href="#"><?php echo get_the_post_thumbnail( $acc_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details">
                <a title="<?php _e( 'View Detail', 'trav' ); ?>" href="<?php echo esc_url( $url ); ?>" class="pull-right button uppercase"><?php _e( 'SELECT', 'trav' ); ?></a>
                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $acc_id ) );?></a><?php echo trav_acc_get_star_rating( $acc_id ); ?><small><?php echo esc_html( trav_acc_get_city( $acc_id ) . ' ' . trav_acc_get_country( $acc_id ) ); ?></small></h4>
                <label class="price-wrapper"><span class="price-per-unit"><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?></span><?php _e( 'avg/night', 'trav' ) ?></label>
            </div>
        </article>

    <?php } elseif ( $current_view == 'grid' ) { ?>

        <article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $acc_id );?>" href="#"><?php echo get_the_post_thumbnail( $acc_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details">
                <span class="price">
                    <small><?php _e( 'avg/night', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?>
                </span>
                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $acc_id ) );?></a><?php echo trav_acc_get_star_rating( $acc_id ); ?><small><?php echo esc_html( trav_acc_get_city( $acc_id ) . ' ' . trav_acc_get_country( $acc_id ) ); ?></small></h4>
                <div class="feedback">
                    <div data-placement="bottom" data-toggle="tooltip" class="five-stars-container" title="<?php echo esc_attr( $review . ' ' . __( 'stars', 'trav' ) ) ?>"><span style="width: <?php echo esc_html( $review / 5 * 100 ) ?>%;" class="five-stars"></span></div>
                    <span class="review"><?php echo esc_html( trav_get_review_count( $acc_id ) . ' ' .  __('reviews', 'trav') ); ?></span>
                </div>
                <p class="description"><?php echo wp_kses_post( $brief ); ?></p>
                <div class="action">
                    <a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small" href="<?php echo esc_url( $url ); ?>"><?php _e( 'SELECT', 'trav' ); ?></a>
                    <a title="<?php _e( 'View On Map', 'trav' ); ?>" onclick="onHtmlClick('<?php echo $acc_id; ?>')" class="button btn-small yellow" href="#"><?php _e( 'VIEW ON MAP', 'trav' ); ?></a>
                </div>
            </div>
        </article>


    <?php } else { ?>

        <article class="box">
            <figure class="col-sm-5 col-md-4">
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $acc_id );?>" href="#"><?php echo get_the_post_thumbnail( $acc_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details col-sm-7 col-md-8">
                <div>
                    <div>
                        <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $acc_id ) );?></a><?php echo trav_acc_get_star_rating( $acc_id ); ?><small><i class="soap-icon-departure yellow-color"></i> <?php echo esc_html( trav_acc_get_city( $acc_id ) . ' ' . trav_acc_get_country( $acc_id ) ); ?></small></h4>
                        <div class="amenities">
                            <?php
                                $facilities = wp_get_post_terms( $acc_id, 'amenity' );
                                $amenity_icons = get_option( "amenity_icon" );
                                $i = 0; $max_amenities = 5;
                                foreach ( $facilities as $facility ) {
                                    if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) ) {
                                        if( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
                                            echo '<img class="custom_amenity" title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="28" alt="amenity-image">';
                                        } else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
                                            $_class = " circle";
                                            $_class = $amenity_icons[ $facility->term_id ]['icon'] . $_class;
                                            echo '<i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>';
                                        }
                                    }
                                    $i++;
                                    if ( $i >= $max_amenities ) break;
                                }
                            ?>
                        </div>
                    </div>
                    <div>
                        <div class="five-stars-container">
                            <span class="five-stars" style="width: <?php echo esc_attr( $review / 5 * 100 ) ?>%;"></span>
                        </div>
                        <span class="review"><?php echo esc_html( trav_get_review_count( $acc_id ) . ' ' .  __('reviews', 'trav') ); ?></span>
                    </div>
                </div>
                <div>
                    <p><?php echo wp_kses_post( $brief ); ?></p>
                    <div>
                        <span class="price"><small><?php _e( 'avg/night', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?></span>
                        <a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width text-center" href="<?php echo esc_url( $url ); ?>"><?php _e( 'SELECT', 'trav' ); ?></a>
                    </div>
                </div>
            </div>
        </article>

    <?php }
    
    echo ( $after_article );
}