<?php
 /*
 Template Name: Page Template With Bgslider
 */
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<div class="slideshow-bg">
			<?php $bg_imgs = get_post_meta( get_the_ID(), 'trav_gallery_imgs' );
			$bg_content = get_post_meta( get_the_ID(), 'trav_page_bg_content', true );
			if ( ! empty( $bg_imgs ) ) : ?>
				<div class="flexslider">
					<ul class="slides">
						<?php foreach ( $bg_imgs as $bg_img ) {
							$image_attributes = wp_get_attachment_image_src( $bg_img, 'full' );
							if ( $image_attributes ) { ?>
								<li><div class="slidebg" style="background-image: url(<?php echo $image_attributes[0] ?>);"></div></li>
							<?php }
						} ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $bg_content ) ) : ?>
				<div class="container">
					<div class="center-block-wrapper full-width">
						<div class="center-block">
							<?php echo do_shortcode( $bg_content ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<section id="content">
			<div id="main" class="entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
			</div>
		</section>
	<?php endwhile;
endif;
get_footer();