<?php
/*
 * Tour List
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

global $tour_list, $current_view, $before_article, $after_article, $date_from, $date_to;

foreach( $tour_list as $tour ) {
    $tour_id = ( is_numeric( $tour ) ) ? $tour : $tour->tour_id;
    $tour_id = trav_tour_clang_id( $tour_id );

    /*$duration = date( 'd M Y', strtotime( $tour->min_date ) );
    if ( $tour->min_date != $tour->max_date ) $duration .= ' - ' . date( 'd M Y', strtotime( $tour->max_date ) );*/
    $discount_rate = get_post_meta( $tour_id, 'trav_tour_discount_rate', true );
    $duration = trav_tour_get_tour_duration( $tour_id );
    $min_price = get_post_meta( $tour_id, 'trav_tour_min_price', true );
    $brief = get_post_meta( $tour_id, 'trav_tour_brief', true );
    if ( empty( $brief ) ) {
        $brief = apply_filters('the_content', get_post_field('post_content', $tour_id));
        $brief = wp_trim_words( $brief, 20, '' );
    }

    $query_args = array(
        'date_from' => $date_from,
        'date_to' => $date_to
    );

    $url = esc_url( add_query_arg( $query_args, get_permalink( $tour_id ) ) );

    echo ( $before_article );

    if ( $current_view == 'grid' ) { ?>

        <article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $tour_id );?>" href="#"><?php echo get_the_post_thumbnail( $tour_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>

            <div class="details">
                <span class="price"><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $tour_id ) );?></a></h4>
                <hr>

                <p class="description"><?php echo wp_kses_post( $brief ); ?></p>
                <hr>

                <div class="text-center">
                    <div class="time">
                        <i class="soap-icon-clock yellow-color"></i>
                        <span><?php echo esc_html( $duration ) ?></span>
                    </div>
                </div>
                <div class="action">
                    <a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width" href="<?php echo esc_url( $url ); ?>"><?php _e( 'BOOK NOW', 'trav' ); ?></a>
                </div>
            </div>
        </article>
        
    <?php } elseif ( $current_view == "block" ) { ?>
        
        <article class="box">
            <figure>
                <a href="<?php echo esc_url( $url ); ?>"><?php echo get_the_post_thumbnail( $tour_id, 'biggallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>

                <figcaption>
                    <?php if ( ! empty( $min_price ) && is_numeric( $min_price ) ) { ?>
                        <span class="price"><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
                    <?php } ?>

                    <h2 class="caption-title"><?php echo esc_html( get_the_title( $tour_id ) ) ?></h2>
                </figcaption>
            </figure>
        </article>

    <?php } else { ?>

        <article class="box">
            <figure class="col-sm-5 col-md-4">
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $tour_id );?>" href="#"><?php echo get_the_post_thumbnail( $tour_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details col-sm-7 col-md-8">
                <div>
                    <div>
                        <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $tour_id ) );?></a><small><i class="soap-icon-clock yellow-color"></i> <?php echo esc_html( $duration ) ?></small></h4>
                    </div>
                    <div>
                        <span class="price"><small><?php _e( 'per person', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
                    </div>
                </div>
                <div>
                    <?php echo wp_kses_post( $brief ); ?>
                    <div>
                        <a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width text-center" href="<?php echo esc_url( $url ); ?>"><?php _e( 'BOOK NOW', 'trav' ); ?></a>
                    </div>
                </div>
            </div>
        </article>
    <?php }
    
    echo ( $after_article );
}