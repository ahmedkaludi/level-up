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

	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar( 'footer-widget' )  ) : ?>
		<div class="footer-widgets">
			<div class="container">
				<div class="f-w">
					<?php dynamic_sidebar( 'footer-widget' ); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="site-info">
			<div class="container">
				<?php if ( has_nav_menu( 'footer-menu' ) ) { ?>
				<div class="footer-menu">
					<?php
	                      wp_nav_menu(array(
	                    	'theme_location' 	=> 'footer-menu',
							'menu_class'        => 'header-menu',
	                    ));
	                     ?>
				</div>
				<?php } ?>
				<div class="rr">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'level-up' ) ); ?>" class="imprint">
					<?php printf( __( 'Proudly powered by %s', 'level-up' ), 'WordPress' ); ?>
				</a>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
