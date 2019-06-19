<?php
$post_id = get_the_ID();
$isv_setting = get_post_meta( $post_id, 'trav_post_media_type', true ); ?>

<div id="post-<?php echo esc_attr( $post_id ); ?>" <?php post_class(); ?>>
	<div class="post-content-wrapper">
		<?php trav_post_gallery( $post_id ) ?>

		<div class="details<?php echo ( empty( $isv_setting ) || ( $isv_setting == 'no' ) )?' without-featured-item':''; ?>">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="excerpt-container entry-content">
				<p><?php the_excerpt(); ?></p>
			</div>
			<div class="post-meta">
				<div class="entry-date">
					<label class="date"><?php echo get_the_date( 'd' , $post_id ); ?></label>
					<label class="month"><?php echo get_the_date( 'M' , $post_id ); ?></label>
				</div>
				<div class="entry-author fn">
					<i class="icon soap-icon-user"></i> <?php _e( 'Posted By', 'trav' ); ?>:
					<?php the_author_posts_link(); ?>
				</div>
				<div class="entry-action">
					<a href="<?php echo esc_url( get_comments_link( $post_id ) ); ?>" class="button entry-comment btn-small"><i class="soap-icon-comment"></i>
						<span><?php comments_number();?></span>
					</a>
					<?php $posttags = get_the_tags(); ?>
					<?php if ( ! empty( $posttags ) ) { ?>
						<span class="entry-tags"><i class="soap-icon-features"></i><span><?php the_tags( '' ); ?></span></span>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>