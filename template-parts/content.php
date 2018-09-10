<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bridge
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('fsp'); ?>>
	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<span class="sticky-post"><?php _e( 'Featured', 'bridge' ); ?></span>
	<?php endif; ?>
	<div class="fsp-img">
		<a href="<?php the_permalink();?>"><?php the_post_thumbnail('bridge-img-1'); ?></a>
	</div>
	<div class="fsp-cnt">
		<div class="category-lists">
           <?php the_category( ' ' ); ?>
        </div><!-- /.category-lists -->
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php the_excerpt(); ?>
		<span class="posted-dt"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></span>
	</div>
</article><!-- #post-## -->