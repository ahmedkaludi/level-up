<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package levelup
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); 
	ampforwp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
	<?php
	if(!function_exists('levelup_check_hf_builder') || (function_exists('levelup_check_hf_builder') && !levelup_check_hf_builder('head'))){  ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'level-up' ); ?></a>
	<header id="masthead" class="site-header">
		<div class="container">
			<div class="head">
				<div class="logo">
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
	            </div><!-- /.logo -->
                <div class="h-menu">
                	<a href="#">Mobile</a>
                </div>
		    </div><!-- /.head -->
		</div><!-- /.container -->
		<div class="desk-menu">
			<div class="container">
				<?php
					wp_nav_menu( array(
						'theme_location' => 'primary-menu',
						'menu_class'        => 'd-menu',
					) );
				 ?>
			</div><!-- /.container -->
		</div><!-- /.desk-menu -->
	</header><!-- #masthead -->
	
	<?php } ?>
	<div id="content" class="site-content">
