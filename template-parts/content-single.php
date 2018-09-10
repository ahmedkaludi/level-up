<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header container">
		<div class="cat-list">
			<?php the_category( ' ' ); ?>
		</div>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
    <?php if ( has_post_thumbnail() ) { ?>
	<div class="post-img">
		<?php 
		$get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		the_post_thumbnail();
		  if(!empty($get_description)){//If description is not empty show the div
		  echo '<div class="featured_caption">' . $get_description . '</div>';
		  } 
		?>
	</div>
    <?php } ?>
	<div class="entry-content container">
		<div class="right-part">
			<div class="content-pt">
				<?php
					the_content();

					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bridge' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'bridge' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					) );

					if ( '' !== get_the_author_meta( 'description' ) ) {
						get_template_part( 'template-parts/biography' );
					}
				?>
			</div>
			<div class="post-author-info">
				<div class="post-aurhor-image">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?>
				</div><!-- /.post-author-image -->
				<div class="post-author-desc">
					<span><?php the_author_link(); ?></span>
					<p><?php the_author_meta('description'); ?></p>
				</div><!-- /.post-author-desc -->
			</div>
			<?php the_post_navigation(); ?>
			<div class="cmts">
				<span class="view-cmts"><?php esc_attr_e( 'View Comments', 'bridge' ); ?></span>
				<?php if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif; ?>
			</div>
		</div>
		<div class="left-part">
			<div class="post-athr">
				<span class="pb-txt"><?php esc_attr_e( 'Published by', 'bridge' ); ?></span>
				<span class="pb-athr"><?php the_author_posts_link(); ?></span>
			</div>
            <?php if(has_tag()) { ?>
            <div class="tgs">
				<span class="pb-txt"><?php esc_attr_e( 'Tags', 'bridge' ); ?></span>
				<?php the_tags('',''); ?> 
			</div>
            <?php } ?>
			<div class="pt-dt">
				<span class="posted-dt"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
				<?php edit_post_link(); ?>
			</div>
			
	   		<?php if(has_post_thumbnail()){?>
			   <div class="related-posts">
					<h3><?php echo esc_attr(get_theme_mod( 'releated-article-text', 'Related Posts' ));?></h3> 
			    	<?php $categories = get_the_category($post->ID);
		            if ($categories) { $category_ids = array();
		            foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
		            $args=array(
		            'category__in' => $category_ids,
		            'post__not_in' => array($post->ID), 
		            'showposts'=> esc_attr( get_theme_mod( 'number-of-posts' , '3') ) ,
		            'ignore_sticky_posts'=>1,
		             );
		            $my_query = new WP_Query($args); if( $my_query->have_posts() ) { 
					 while ($my_query->have_posts()) : $my_query->the_post(); ?>
						<div class="rp-list">
							<?php if ( has_post_thumbnail()) { ?>
							<div class="rp-img">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('bridge-img-1'); ?></a>
							</div><!-- / latest-post -->
							<?php } ?>
							<div class="rp-tlt">				
								<a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
							</div>
						</div><!-- /.latest-posts -->
				   <?php  endwhile;
				} wp_reset_postdata(); } ?>
		</div><!-- /.related-posts -->
		<?php }  ?>
		</div>
	</div><!-- .entry-content -->

	<footer class="entry-footer container">
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
