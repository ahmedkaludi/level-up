<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package designblocks
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'designblocks' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="head">
				<div class="hamburger-menu drawer drawer--left">
	                <button type="button" class="drawer-toggle drawer-hamburger">
	                  <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'designblocks' ); ?></span>
	                  <span class="drawer-hamburger-icon"></span>
	                </button>
	                <nav class="drawer-nav" role="navigation">
	                  <div class="drawer-menu">
	                    <?php
	                    if ( has_nav_menu( 'header-menu' ) ) {
	                      wp_nav_menu(array(
	                    	'theme_location' => 'header-menu',
							'menu_class'        => 'header-menu',
	                    ));
	                    } ?>
	                  </div>
	                  <span class="icon-close drawer-toggle"></span>
	                </nav>
                </div><!-- /.mobile-menu -->
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
	<div id="content" class="site-content">
