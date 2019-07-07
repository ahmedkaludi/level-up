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
    if(function_exists('ampforwp_head')) {
        ampforwp_head();
    } ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
	<?php
	if(!function_exists('levelup_check_hf_builder') || (function_exists('levelup_check_hf_builder') && !levelup_check_hf_builder('head'))){  ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'level-up' ); ?></a>
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
		                    if ( has_nav_menu( 'header-menu' ) ) {
		                      wp_nav_menu(array(
		                    	'theme_location' => 'header-menu',
								'menu_class'        => 'mob-menu',
		                    ));
		                    } ?>

					</nav><!--end slide-menu -->
	            </div><!-- /m-scrl -->
			</aside>
			<label for="offcanvas-menu" class="fsc"></label>
			<div class="container">
				<div class="head">
					<div class="h-nav">
	                    <label for="offcanvas-menu" class="t-btn"></label>
	                </div><!--end menu-->
					<div class="logo">
		              <?php 
		               	if ( has_custom_logo() ) {       	
		                     echo get_custom_logo();
		                 } else {
		                     echo '<a href="'. esc_url( home_url() ).'"><h1>'. esc_attr( get_bloginfo( 'name' ) ) .'</h1><span>'. esc_attr( get_bloginfo( 'description', 'display' ) ) .'</span> </a>';
		                 } ?>
		            </div><!-- /.logo -->
	                <div class="h-srch h-ic">
	                    <a class="lb icon-search2" href="#search"></a>
	                    <div class="lb-btn"> 
	                        <div class="lb-t" id="search">
	                            <?php get_search_form(); ?>
	                        </div> 
	                    </div>
	                </div><!-- /.search -->
			    </div><!-- /.head -->
			</div><!-- /.container -->
		</div>
	</header><!-- #masthead -->
	<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>
	<div class="p-m-fl">
		<div class="p-menu">
			<?php
				wp_nav_menu( array(
					'theme_location' => 'primary-menu',
					'menu_id'        => 'primary-menu',
				) );
			 ?>
		</div><!-- /.p-menu -->
	</div><!-- /.p-m-fl -->
	<?php } ?>
	<?php } ?>
	<div id="content" class="site-content">
