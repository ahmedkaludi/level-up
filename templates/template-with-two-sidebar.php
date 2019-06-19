<?php
 /*
 Template Name: Page Template With Two Sidebar
 */
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$slider_active = get_post_meta( get_the_ID(), 'trav_page_slider', true );
		$slider        = ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
		if ( class_exists( 'RevSlider' ) && $slider != 'Deactivated' ) {
			echo '<div id="slideshow">';
			putRevSlider( $slider );
			echo '</div>';
		} ?>

		<section id="content">
			<div class="container">
				<div class="row">
					<div class="sidebar col-md-3">
						<?php generated_dynamic_sidebar(); ?>
					</div>
					<div id="main" class="col-md-6 entry-content">
						<?php if ( has_post_thumbnail() ) { ?>
							<figure class="image-container block">
								<?php the_post_thumbnail(); ?>
							</figure>
						<?php } ?>
						<?php the_content(); ?>
						<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
					</div>
					<div class="sidebar col-md-3">
						<?php dynamic_sidebar('sidebar-post2'); ?>
					</div>
				</div>
			</div>
		</section>
	<?php endwhile;
endif;
get_footer();