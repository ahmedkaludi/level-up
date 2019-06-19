<?php
 /*
 Blog Page Content
 */
global $cat, $ajax_paging;
$posts_per_page = get_option( 'posts_per_page' );
$arg_str = 'post_type=post&post_status=publish&posts_per_page=' . $posts_per_page . '&paged='. get_query_var('paged');
if ( ! empty( $cat ) )  $arg_str .= '&cat=' . $cat;
query_posts( $arg_str );
?>

<div class="page">
	<div class="post-content">
		<div class="blog-infinite">
			<?php while(have_posts()): the_post();
				trav_get_template( 'loop-blog.php', '/templates' );
			endwhile; ?>
		</div>
		<?php
			if ( ! empty( $ajax_paging ) ) {
				next_posts_link( __( 'LOAD MORE POSTS', 'trav' ) );
			} else {
				echo paginate_links( array( 'type' => 'list' ) );
			}
		?>
		<?php wp_reset_query(); ?>
	</div>
</div>