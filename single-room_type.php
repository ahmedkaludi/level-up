<?php
get_header();

if ( have_posts() ) {
    while ( have_posts() ) : the_post();
        $post_id = get_the_ID(); ?>

        <section id="content">
            <div class="container">
                <div class="row">
                    <div id="main" class="col-sm-8 col-md-9">
                        <div class="post" id="post-<?php echo esc_attr( $post_id ); ?>">
                            <?php $isv_setting = get_post_meta( $post_id, 'trav_post_media_type', true ); ?>
                            <?php trav_post_gallery( $post_id ) ?>

                            <div class="details<?php echo ( empty( $isv_setting ) || ( $isv_setting == 'no' ) ) ? ' without-featured-item' : ''; ?>">
                                <h1 class="entry-title"><?php the_title() ?></h1>
                                <div class="post-content entry-content">
                                    <?php the_content();?>
                                </div>

                                <?php
                                    $facilities = wp_get_post_terms( $post_id, 'amenity' );
                                    $amenity_icons = get_option( "amenity_icon" );
                                        
                                    if ( ! empty( $facilities ) ) { 
                                    ?>
                                        <h3><?php echo __('Amenities', 'trav'); ?></h3>
                                        <ul class="amenities clearfix style1">

                                        <?php
                                        $amenity_html = '';

                                        foreach ( $facilities as $facility ) {
                                            $amenity_html .= '<li class="col-md-4 col-sm-6">';
                                            $amenity_html .= '<div class="icon-box style1">';

                                            if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) && ! empty( $amenity_icons[ $facility->term_id ] ) ) {

                                                if ( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
                                                    $amenity_html .= '<div class="custom_amenity"><img title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="42" alt="amenity-image"></div>';
                                                } else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
                                                    $_class = $amenity_icons[ $facility->term_id ]['icon'];
                                                    $amenity_html .= '<i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>';
                                                }

                                            }
                                            
                                            $amenity_html .= esc_html( $facility->name );
                                            $amenity_html .= '</div>';
                                            $amenity_html .= '</li>';
                                        }

                                        echo wp_kses_post( $amenity_html );
                                        ?>

                                        </ul>

                                    <?php
                                    } else { 
                                        echo '<h4>' . __( 'This Room has no amenities.', 'trav' ) . '</h4>';
                                    }
                                ?>
                                <?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar col-sm-4 col-md-3">
                        <?php generated_dynamic_sidebar(); ?>
                    </div>
                </div>
            </div>
        </section>
    <?php 
    endwhile;
}

get_footer();