<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package levelup
 */

?>

	</div><!-- #content -->
	<?php do_action( 'levelup_foot'); ?>
	<?php if(!function_exists('levelup_check_hf_builder') || (function_exists('levelup_check_hf_builder') && !levelup_check_hf_builder('foot'))) { ?>
	<footer id="colophon" class="site-footer">
		<div class="ft-wrap">
			<div class="container">
				<div class="ft-widgets-blk">
		            <div class="wdgts">
		                <?php dynamic_sidebar( 'sidebar-footer-1' );?>
		            </div>
		            <div class="wdgts">
		                <?php dynamic_sidebar( 'sidebar-footer-2' );?>
		            </div>
		            <div class="wdgts">
		                <?php dynamic_sidebar( 'sidebar-footer-3' );?>
		            </div>
		            <div class="wdgts lst">
		                <?php dynamic_sidebar( 'sidebar-footer-4' );?>
		            </div>
		        </div>
		    </div>
		</div>
		<div class="container">
			<div class="footer-2">
				<div class="f-logo">
					<a href="<?php echo esc_url( home_url() ); ?>">
		                <?php 
		                $custom_logo_id = esc_attr( get_theme_mod( 'custom_logo' ) );

		                if( $custom_logo_id ) {
		                	$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		                }
		                if ( has_custom_logo() ) {       	
		                    echo '<img src="'. esc_url( $logo[0] ) .'">';
		                } else {
		                    echo '<h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span>';
		                } ?>
	              	</a>
				</div>
				<div class="cpr">
					<span>© "ITS Viagens e Turismo Ltda. - Copyright © 1998-Present - All Rights Reserved." Custom Brazil Travel Packages, Tours & Vacations</span>
					<button id="scrollToTopButton" on="tap:top.scrollTo(duration=500)" class="scrollToTop"></button>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
<?php } ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
