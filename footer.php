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
		<?php if ( is_active_sidebar( 'footer-widget' )  ) : ?>
		<div class="footer-widgets">
			<div class="container">
				<div class="f-w">
					<?php dynamic_sidebar( 'footer-widget' ); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="row">
                    <div class="col-sm-6 col-md-3">
                        <?php dynamic_sidebar( 'sidebar-footer-1' );?>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <?php dynamic_sidebar( 'sidebar-footer-2' );?>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <?php dynamic_sidebar( 'sidebar-footer-3' );?>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <?php dynamic_sidebar( 'sidebar-footer-4' );?>
                    </div>
                </div>
		<div class="site-info">
			<div class="container">
				<div class="rr">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'level-up' ) ); ?>" class="imprint">
					<?php printf( __( 'Proudly powered by %s', 'level-up' ), 'WordPress' ); ?>
				</a>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
<?php } ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
