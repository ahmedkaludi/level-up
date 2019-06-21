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
	<h3 class="target"><a class="target-anchor" id="top"></a><amp-position-observer layout="nodisplay"></amp-position-observer></h3>
	<header id="masthead" class="site-header">
		<input type="checkbox" id="offcanvas-menu" class="tg" />
		<div class="hamb-mnu">
			<aside class="m-ctr">
	            <div class="m-scrl">
	                <div class="menu-heading clearfix">
	                    <label for="offcanvas-menu" class="c-btn"></label>
	                </div><!--end menu-heading-->
	                <nav class="m-menu">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'primary-menu',
								'menu_class'        => 'mob-menu',
								'walker' => new Ampforwp_Walker_Nav_Menu()
							) );
						 ?>
					</nav><!--end slide-menu -->
	            </div><!-- /m-scrl -->
			</aside>
			<div class="dsk-nav">
				<div class="container">
					<div class="head">
						<div class="logo">
			              <?php 
			               	if ( has_custom_logo() ) {       	
			                     echo get_custom_logo();
			                 } else {
			                     echo '<a href="'. esc_url( home_url() ).'"><h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span> </a>';
			                 } ?>
			            </div><!-- /.logo -->
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
			</div>
			<label for="offcanvas-menu" class="fsc"></label>
			<div class="container mbl">
				<div class="mbl-menu">
					<div class="m-logo">
						<?php 
		               	if ( has_custom_logo() ) {       	
		                     echo get_custom_logo();
		                 } else {
		                     echo '<a href="'. esc_url( home_url() ).'"><h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span> </a>';
		                } ?>
					</div>
					<div class="h-nav">
	                    <label for="offcanvas-menu" class="t-btn"></label>
	                </div><!--end menu-->
		        </div>
			</div>
		</div><!-- /.hamb-mnu -->
	</header><!-- #masthead -->
	<div id="navbar">
		<div class="container">
			<div class="ios-menu">
				<div class="ios-logo">
					<?php 
	               	if ( has_custom_logo() ) {       	
	                     echo get_custom_logo();
	                 } else {
	                     echo '<a href="'. esc_url( home_url() ).'"><h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span> </a>';
	                 } ?>
				</div>
				<div class="ios-nav">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'primary-menu',
							'menu_class'        => 'd-menu',
						) );
					 ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php 
$inner_disable = '';

if ( is_page() ) {
    $page_object = get_queried_object();
    $post_id     = get_queried_object_id();
    $inner_disable = get_post_meta( $post_id, 'trav_page_inner', true );
}
if ( is_home() || is_front_page() || is_page_template('templates/template-home.php') ) {
    //
} else {
    if ( is_page() && $inner_disable == 'disable' ) {
        //
    } else {
        trav_get_template( 'inner-1.php', '/templates/inners' );
    }
} ?>
	<div id="content" class="site-content">
