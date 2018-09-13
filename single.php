<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package designblocks
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php if ( function_exists('yoast_breadcrumb') ) { ?>
				<div class="container">
					<div class="breadcrumbs">
						<?php yoast_breadcrumb('<p id="br-crumbs">','</p>'); ?>
					</div>
				</div>
			<?php } ?>
		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content-single', get_post_type() ); 

		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
