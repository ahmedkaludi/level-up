<?php
get_header();

if ( have_posts() ) {
	while ( have_posts() ) : the_post();

		//init variables
		$post_id = get_the_ID();

		// add to user recent activity
		trav_update_user_recent_activity( $post_id ); ?>
		<section id="content">
			<div class="container">
				<div class="row tour-temp">
					<div id="main" class="tout-left accom-left">
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php $isv_setting = get_post_meta( $post_id, 'trav_post_media_type', true ); ?>
							<div class="ttd-slider">
								<?php trav_post_gallery( $post_id ) ?>
							</div>
							<div class="details<?php echo ( empty( $isv_setting ) || ( $isv_setting == 'no' ) )?' without-featured-item':''; ?>">
								<h1 class="entry-title"><?php the_title();?></h1>
								<div class="post-content entry-content">
									<?php the_content();?>
									<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
								</div>
								
							</div>
						</div>
					</div>
					<div class="sidebar tour-right">
						<?php generated_dynamic_sidebar(); ?>
					</div>
				</div>
			</div>
		</section>
<?php endwhile;
}
get_footer();