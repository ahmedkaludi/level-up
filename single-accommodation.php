<?php
/*
 * Single Accommodation Page Template
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

global $search_max_rooms, $search_max_adults, $search_max_kids;

get_header();

if ( have_posts() ) {
    while ( have_posts() ) : the_post();

        //init variables
        $acc_id = get_the_ID();
        $acc_meta = get_post_meta( $acc_id );
        $acc_meta['review'] = get_post_meta( trav_acc_org_id( $acc_id ), 'review', true );
        $acc_meta['review_detail'] = get_post_meta( trav_acc_org_id( $acc_id ), 'review_detail', true );
        $tm_data = get_post_meta( $acc_id, 'trav_accommodation_tm_testimonial', true );
        $accommodation_type = wp_get_post_terms( $acc_id, 'accommodation_type' );
        $args = array(
            'post_type' => 'room_type',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'trav_room_accommodation',
                    // 'value' => array( $acc_id ),
                    'value' => array( trav_acc_org_id( $acc_id ) )
                )
            ),
            'suppress_filters' => 1,
            'post_status' => 'publish',
        );
        $room_types = get_posts( $args );
        $city = trav_acc_get_city( $acc_id );
        $country = trav_acc_get_country( $acc_id );
        $facilities = wp_get_post_terms( $acc_id, 'amenity' );

        $things_to_do = empty( $acc_meta['trav_accommodation_ttd'] ) ? '' : $acc_meta['trav_accommodation_ttd'];

        // init map & gallery & calendar variables
        $gallery_imgs = array_key_exists( 'trav_gallery_imgs', $acc_meta ) ? $acc_meta['trav_gallery_imgs'] : array();
        $map = empty( $acc_meta['trav_accommodation_loc'] ) ? '' : $acc_meta['trav_accommodation_loc'][0];
        $calendar_desc = empty( $acc_meta['trav_accommodation_calendar_txt'] ) ? '' : $acc_meta['trav_accommodation_calendar_txt'][0];
        $show_gallery = 0;
        $show_map = 0;
        $show_street_view = 0;
        $show_calendar = 0;
        if ( array_key_exists( 'trav_accommodation_main_top', $acc_meta ) ) {
            $main_top_meta = $acc_meta['trav_accommodation_main_top'];
            $show_gallery = in_array( 'gallery', $main_top_meta ) ? 1 : 0;
            $show_map = in_array( 'map', $main_top_meta ) ? 1 : 0;
            $show_street_view = in_array( 'street', $main_top_meta ) ? 1 : 0;
            $show_calendar = in_array( 'calendar', $main_top_meta ) ? 1 : 0;
        }

        // init booking search variables
        $rooms = ( isset( $_GET['rooms'] ) && is_numeric( $_GET['rooms'] ) ) ? sanitize_text_field( $_GET['rooms'] ) : 1;
        $adults = ( isset( $_GET['adults'] ) && is_numeric( $_GET['adults'] ) ) ? sanitize_text_field( $_GET['adults'] ) : 1;
        $kids = ( isset( $_GET['kids'] ) && is_numeric( $_GET['kids'] ) ) ? sanitize_text_field( $_GET['kids'] ) : 0;
        $child_ages = isset( $_GET['child_ages'] ) ? $_GET['child_ages'] : '';
        $date_from = ( isset( $_GET['date_from'] ) ) ? trav_tophptime( $_GET['date_from'] ) : '';
        $date_to = ( isset( $_GET['date_to'] ) ) ? trav_tophptime( $_GET['date_to'] ) : '';
        $except_booking_no = ( isset( $_GET['edit_booking_no'] ) ) ? sanitize_text_field( $_GET['edit_booking_no'] ) : 0;
        $pin_code = ( isset( $_GET['pin_code'] ) ) ? sanitize_text_field( $_GET['pin_code'] ) : 0;

        // add to user recent activity
        trav_update_user_recent_activity( $acc_id ); ?>

        <section id="content">
            <div class="container">
                <div class="row tour-temp">
                    <div id="main" class="tout-left accom-left">
                        <div class="tab-container style1" id="hotel-main-content">
                            <ul class="tabs">

                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
                                    <li><a data-toggle="tab" href="#photos-tab"><?php echo __( 'photos', 'trav' ) ?></a></li>
                                <?php } ?>

                                <?php if ( ! empty( $map ) ) { ?>
                                    <?php if ( $show_map ) { ?>
                                        <li><a data-toggle="tab" href="#map-tab"><?php echo __( 'map', 'trav' ) ?></a></li>
                                    <?php } ?>
                                    <?php if ( $show_street_view ) { ?>
                                        <li><a data-toggle="tab" href="#steet-view-tab"><?php echo __( 'street view', 'trav' ) ?></a></li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ( $show_calendar ) { ?>
                                    <li><a data-toggle="tab" href="#calendar-tab"><?php echo __( 'calendar', 'trav' ) ?></a></li>
                                <?php } ?>

                                <?php if ( ! empty( $acc_meta['trav_accommodation_tg'] ) ) { ?>
                                    <li class="pull-right"><a class="button btn-small yellow-bg white-color" href="<?php echo esc_url( get_permalink( $acc_meta['trav_accommodation_tg'][0] ) ); ?>"><?php _e( 'TRAVEL GUIDE', 'trav' ) ?></a></li>
                                <?php } ?>

                            </ul>
                            <div class="tab-content">

                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
                                    <div id="photos-tab" class="tab-pane fade">
                                        <div class="photo-gallery flexslider style1" data-animation="slide" data-sync="#photos-tab .image-carousel">
                                            <amp-carousel id="carouselWithPreview" width="400" height="300" layout="responsive" type="slides" on="slideChange:carouselWithPreviewSelector.toggle(index=event.index, value=true)">
                                                <?php foreach ( $gallery_imgs as $gallery_img ) {   
                                                $image_attributes = wp_get_attachment_image_src( $gallery_img, 'full' );
                                                    if($image_attributes){
                                                    $amp_image = '<amp-img width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" layout="responsive" alt="a sample image" src="'.$image_attributes[0].'"  ></amp-img>';
                                                    echo  $amp_image;
                                                    }
                                                } ?>
                                            </amp-carousel>
                                        </div>
                                        <div class="image-carousel style1" data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#photos-tab .photo-gallery">
                                            <amp-selector id="carouselWithPreviewSelector"
                                                class="carousel-preview"
                                                on="select:carouselWithPreview.goToSlide(index=event.targetOption)"
                                                layout="container">
                                                <?php 
                                                $i = 0;
                                                foreach ( $gallery_imgs as $gallery_img ) {
                                                    $sm_image_attributes = wp_get_attachment_image_src( $gallery_img, 'thumbnail' );
                                                    if($sm_image_attributes){
                                                        $thumb_images = '<amp-img option="'.$i.'"
                                                        '.(($i==0)?'selected': '').'
                                                        src="'.$sm_image_attributes[0].'"
                                                        width="70"
                                                        height="70"
                                                        alt="a sample image"></amp-img>';
                                                        echo $thumb_images;
                                                    }
                                                    $i++;
                                                } ?>
                                             </amp-selector>
                                        </div>
                                    </div>
                                <?php } ?>
                                    
                                   
                                <?php if ( ! empty( $map ) ) { ?>
                                    <?php  if ( $show_map ) { ?>
                                        <div id="map-tab" class="tab-pane fade"></div>
                                    <?php } ?>
                                    <?php //if ( $show_street_view ) { ?>
                                        <div id="steet-view-tab" class="tab-pane fade" style="height: 500px;"></div>
                                    <?php //} ?>
                                <?php } ?>

                                <?php  if ( $show_calendar ) { ?>
                                    <div id="calendar-tab" class="tab-pane fade">
                                        <div class="row">

                                            <div class="col-sm-6 col-md-4 no-lpadding">
                                                <label><?php _e( 'SELECT MONTH', 'trav' );?></label>
                                                <div class="selector">
                                                    <select class="full-width" id="select-month">
                                                        <?php for ( $i = 0; $i<12; $i++ ) {
                                                            $year_month = mktime( 0, 0, 0, date_i18n("m") + $i, 1, date_i18n("Y") );
                                                            echo '<option value="' . date_i18n( 'Y-n', $year_month ) . '"> ' . __( date_i18n('F', $year_month ), 'trav' ) . date_i18n(' Y', $year_month ) . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if ( ! empty( $room_types ) ) { ?>
                                                    <div class="col-sm-6 col-md-4 no-lpadding">
                                                        <label><?php _e( 'SELECT ROOM TYPE', 'trav' );?></label>
                                                        <div class="selector">
                                                            <select class="full-width" id="select-room-type">
                                                                <option value=""><?php _e( 'All Room Types', 'trav' ); ?></option>
                                                                <?php
                                                                    foreach ( $room_types as $room_type ) {
                                                                        echo '<option value="' . esc_attr( $room_type->ID ) . '">' . get_the_title( trav_room_clang_id( $room_type->ID ) ) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            <?php } ?>

                                        </div>
                                        <div class="row">
                                            <?php if ( ! empty( $calendar_desc ) ) { ?>
                                                <div class="col-sm-8">
                                                    <div class="calendar"></div>
                                                    <div class="calendar-legend">
                                                        <label class="available"><?php echo __( 'available', 'trav' ) ?></label>
                                                        <label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
                                                        <label class="past"><?php echo __( 'past', 'trav' ) ?></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <p class="description">
                                                        <?php
                                                            echo esc_html( $calendar_desc )
                                                        ?>
                                                    </p>
                                                </div>
                                            <?php } else { ?>
                                                <div class="calendar"></div>
                                                <div class="calendar-legend">
                                                    <label class="available"><?php echo __( 'available', 'trav' ) ?></label>
                                                    <label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
                                                    <label class="past"><?php echo __( 'past', 'trav' ) ?></label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div id="hotel-features" class="tab-container">

                            <amp-selector class="tabs-with-selector" role="tablist"
                                on="select:myTabPanels.toggle(index=event.targetOption, value=true)">
                                <?php $def_tab = ( ! empty(  $acc_meta['trav_accommodation_def_tab'] ) ) ? $acc_meta['trav_accommodation_def_tab'][0] : 'desc';?>
                                <div id="sample3-tab1" class="tab-mn" role="tab" aria-controls="sample3-tabpanel1" option="0"selected>
                                    <?php _e( 'Description','trav' ); ?>
                                </div>
                                <div id="sample3-tab2" class="tab-mn" role="tab" aria-controls="sample3-tabpanel2" option="1">
                                    <?php _e( 'Amenities','trav' ); ?>
                                </div>
                                <?php if ( ! empty( $acc_meta['trav_accommodation_faq'] ) ) : ?>
                                    <div id="sample3-tab3" class="tab-mn" role="tab" aria-controls="sample3-tabpanel3" option="2">
                                            <?php _e( 'Rooms And Suites','trav' ); ?>
                                    </div>
                                <?php endif ?>
                            </amp-selector>
                            <amp-selector id="myTabPanels" class="tabpanels">
                                <div id="sample3-tabpanel1" role="tabpanel" aria-labelledby="sample3-tab1" option selected>
                                    <div class="intro table-wrapper full-width hidden-table-sms">
                                        <div class="col-sm-4 features table-cell">
                                            <table>
                                            <?php
                                                $tr = '<tr><td><label>%s:</label></td><td>%s</td></tr>';
                                                //accommodation type
                                                if ( ! empty ( $accommodation_type ) ) {
                                                    echo sprintf( $tr, __( 'Type', 'trav' ), esc_attr( $accommodation_type[0]->name ) );
                                                }

                                                $detail_fields = array( 
                                                    'star_rating' => array( 'label' => __('Rating Stars', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'star', 'trav') ),
                                                    'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'neighborhood' => array( 'label' => __('Neighborhood', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_in' => array( 'label' => __('Check In', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_out' => array( 'label' => __('Check Out', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'charge_extra_people' => array( 'label' => __('Extra people', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'minimum_stay' => array( 'label' => __('Minimum Stay', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'nights', 'trav') ),
                                                    'discount_rate' => array( 'label' => __('Discount', 'trav'), 'pre' => '', 'sur' => ' ' . __('% Off', 'trav') ),
                                                );

                                                foreach ( $detail_fields as $field => $value ) {
                                                    if ( empty( $$field ) ) $$field = empty( $acc_meta["trav_accommodation_$field"] )?'':$acc_meta["trav_accommodation_$field"][0];
                                                    if ( ! empty( $$field ) ) {
                                                        $content = $value['pre'] . $$field . $value['sur'];
                                                        echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
                                                    }
                                                }
                                            ?>
                                            </table>
                                        </div>
                                        <?php
                                            if ( ! empty( $tm_data ) ) {
                                                $tm_style = empty( $acc_meta['trav_accommodation_tm_style'] )?'':$acc_meta['trav_accommodation_tm_style'][0];
                                                $tm_title = empty( $acc_meta['trav_accommodation_tm_title'] )?'':$acc_meta['trav_accommodation_tm_title'][0];
                                                $tm_author_photo_size = empty( $acc_meta['trav_accommodation_tm_author_photo_size'] )?'':$acc_meta['trav_accommodation_tm_author_photo_size'][0];
                                                $tm_class = empty( $acc_meta['trav_accommodation_tm_class'] )?'':$acc_meta['trav_accommodation_tm_class'][0];
                                                
                                                $tm_string ='';
                                                $tm_string .= '[testimonials style="' . $tm_style . '" title="' . $tm_title . '" author_img_size="' . $tm_author_photo_size . '" class="' . $tm_class . '"]';
                                                $tm_template = '[testimonial author_name="%s" author_link="%s" author_img_url="%s" ]%s[/testimonial]';
                                                $tm_content = '';
                                                foreach ( $tm_data as $tm_id => $values ) {
                                                    if ( empty( $values[0] ) && empty( $values[1] ) && empty( $values[2] ) && empty( $values[3] ) ) continue;
                                                    $tm_content .= sprintf( $tm_template, $values[0], $values[1], $values[2], $values[3] );
                                                }
                                                $tm_string .= $tm_content;
                                                $tm_string .= '[/testimonials]';
                                                if ( ! empty( $tm_content ) ) {
                                        ?>
                                                    <div class="col-sm-8 table-cell testimonials no-rpadding no-lpadding">
                                                        <?php
                                                            echo wp_kses_post( do_shortcode( $tm_string ) );
                                                        ?>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="long-description">
                                        <div class="box entry-content">
                                            <?php the_content(); ?>
                                            <?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
                                        </div>
                                       <!-- <div class="box policies-box">
                                            <h2><?php printf( __( 'Policies of %s', 'trav' ), wp_kses_post( get_the_title( $acc_id ) ) ) ?></h2>
                                            <?php
                                                $tr = '<div class="row"><div class="col-xs-2"><label>%s:</label></div><div class="col-xs-10">%s</div></div>';

                                                $detail_fields = array( 
                                                    'check_in' => array( 'label' => __('Check-in', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_out' => array( 'label' => __('Check-out', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'cancellation' => array( 'label' => __('Cancellation / prepayment', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'security_deposit' => array( 'label' => __('Security Deposit Amount (%)', 'trav'), 'pre' => '', 'sur' => '%' ),
                                                    'extra_beds_detail' => array( 'label' => __('Children and Extra Beds', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'cards' => array( 'label' => __('Cards accepted at this property', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'pets' => array( 'label' => __('Pets', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'other_policies' => array( 'label' => __('Other Policies', 'trav'), 'pre' => '', 'sur' => '' ),
                                                );

                                                foreach ( $detail_fields as $field => $value ) {
                                                    $$field = empty( $acc_meta["trav_accommodation_$field"] )?'':$acc_meta["trav_accommodation_$field"][0];
                                                    if ( ! empty( $$field ) ) {
                                                        $content = $value['pre'] . $$field . $value['sur'];
                                                        echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
                                                    }
                                                }
                                            ?>
                                        </div> -->
                                    </div>
                                </div>

                                <div id="sample3-tabpanel2" role="tabpanel" aria-labelledby="sample3-tab2" option>
                                    <h2><?php echo __('Amenities of ', 'trav'); the_title();?></h2>
                                    <p>
                                        <?php
                                            echo esc_attr( empty( $acc_meta["trav_accommodation_other_amenity_info"] ) ? '' : $acc_meta["trav_accommodation_other_amenity_info"][0] );
                                        ?>
                                    </p>
                                    <ul class="amenities clearfix style1">
                                        <?php
                                            $amenity_icons = get_option( "amenity_icon" );
                                            $amenity_html = '';

                                            foreach ( $facilities as $facility ) {
                                                if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) ) {
                                                    $amenity_html .= '<li class="col-md-4 col-sm-6">';
                                                     if ( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
                                                         $amenity_html .= '<div class="icon-box style1"><div class="custom_amenity"><img title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="42" alt="amenity-image"></div>' . esc_html( $facility->name ) . '</div>';
                                                     } else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
                                                        $_class = $amenity_icons[ $facility->term_id ]['icon'];
                                                        $amenity_html .= '<div class="icon-box style1"><i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>' . esc_html( $facility->name ) . '</div>';
                                                    }
                                                    $amenity_html .= '</li>';
                                                }
                                                
                                            }
                                            echo wp_kses_post( $amenity_html );
                                        ?>
                                    </ul>
                                </div>
                                <?php if ( ! empty( $acc_meta['trav_accommodation_faq'] ) ) : ?>
                                    <div id="sample3-tabpanel3" role="tabpanel" aria-labelledby="sample3-tab3" option>
                                        <?php echo do_shortcode( $acc_meta['trav_accommodation_faq'][0] ); ?>
                                    </div>
                                <?php endif; ?>
                            </amp-selector>
                        </div>
                    </div>
                    <div class="sidebar tour-right">
                        <article class="detailed-logo">
                            <?php if ( isset( $acc_meta['trav_accommodation_logo'] ) ) { ?>
                                <figure>
                                    <img width="114" src="<?php echo esc_url( wp_get_attachment_url( $acc_meta['trav_accommodation_logo'][0] ) );?>" alt="Accommodation Logo">
                                </figure>
                            <?php } ?>
                            <div class="details">
                                <h2 class="box-title">
                                    <?php the_title(); ?>
                                    <?php echo trav_acc_get_star_rating( $acc_id ); ?>
                                    <small><i class="soap-icon-departure yellow-color"></i><span class="fourty-space"><?php echo esc_html( empty( $city )?'':( $city . ', ' ) ); echo esc_html( empty( $country )?'':( $country ) ); ?></span></small>
                                </h2>
                                <?php if ( isset( $acc_meta['trav_accommodation_avg_price'] ) && is_numeric( $acc_meta['trav_accommodation_avg_price'][0] ) ) { ?>
                                    <span class="price clearfix">
                                        <small class="pull-left"><?php _e( 'avg/night', 'trav' ); ?></small>
                                        <span class="pull-right"><?php echo esc_html( trav_get_price_field( $acc_meta['trav_accommodation_avg_price'][0] ) ); ?></span>
                                    </span>
                                <?php } ?>
                                <div class="feedback clearfix">
                                    <div title="<?php echo esc_attr( $acc_review . ' ' . __( 'stars', 'trav' ) );?>" class="five-stars-container" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: <?php echo esc_attr( $acc_review / 5 * 100 );?>%;"></span></div>
                                    <span class="review pull-right"><?php echo esc_html( trav_get_review_count( $acc_id ) . ' ' . __( 'reviews', 'trav' ) ) ?></span>
                                </div>
                                <p class="description">
                                    <?php
                                        if ( isset( $acc_meta['trav_accommodation_brief'] ) ) {
                                            echo esc_html( $acc_meta['trav_accommodation_brief'][0] );
                                        } else {
                                            $brief_content = apply_filters('the_content', get_post_field('post_content', $acc_id));
                                            echo wp_kses_post( wp_trim_words( $brief_content, 20, '' ) );
                                        }
                                    ?>
                                </p>
                                <?php if ( is_user_logged_in() ) {
                                    $user_id = get_current_user_id();
                                    $wishlist = get_user_meta( $user_id, 'wishlist', true );
                                    if ( empty( $wishlist ) ) $wishlist = array();
                                    if ( ! in_array( trav_acc_org_id( $acc_id ), $wishlist) ) { ?>
                                        <a class="button yellow-bg full-width uppercase btn-small btn-add-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'add to wishlist', 'trav' ); ?></a>
                                    <?php } else { ?>
                                        <a class="button yellow-bg full-width uppercase btn-small btn-remove-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'remove from wishlist', 'trav' ); ?></a>
                                    <?php } ?>
                                <?php } else { ?>
                                        <h5><?php _e( 'To save your wishlist please login.', 'trav' ); ?></h5>
                                        <a href="<?php echo $login_url ?>" class="button yellow-bg full-width uppercase btn-small <?php echo ( $login_url == '#travelo-login' )?' soap-popupbox':'' ?>"><?php _e( 'login', 'trav' ); ?></a>
                                <?php } ?>
                            </div>
                        </article>
                        <?php generated_dynamic_sidebar(); ?>
                    </div>
                </div>
            </div>
        </section><!-- #content -->
<?php endwhile;
}
get_footer();