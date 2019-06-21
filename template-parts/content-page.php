<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package levelup
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php levelup_post_thumbnail(); ?>

	<div class="entry-content cstm-pgs">
		<div class="container">
			<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'level-up' ),
					'after'  => '</div>',
				) );
			?>
		</div>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<div class="container">
				<?php
					// edit_post_link(
					// 	sprintf(
					// 		wp_kses(
					// 			/* translators: %s: Name of current post. Only visible to screen readers */
					// 			__( 'Edit <span class="screen-reader-text">%s</span>', 'level-up' ),
					// 			array(
					// 				'span' => array(
					// 					'class' => array(),
					// 				),
					// 			)
					// 		),
					// 		get_the_title()
					// 	),
					// 	'<span class="edit-link">',
					// 	'</span>'
					// );
				?>
			</div>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
