<?php
/**
 * Header 3
 */
global $trav_options, $logo_url;
?>
<header id="header" class="navbar-static-top style3">
	<a href="#mobile-menu-01" data-toggle="collapse" class="mobile-menu-toggle">
		Mobile Menu Toggle
	</a>
	<div class="container">
		<div class="logo navbar-brand">
			<a href="<?php echo esc_url( home_url() ); ?>">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>" />
			</a>
		</div>
		
		<?php if ( isset( $trav_options['woo_show_mini_cart'] ) && $trav_options['woo_show_mini_cart'] && class_exists( 'WooCommerce' ) ) { ?>
			<div class="mini-cart">
				<a href="<?php echo wc_get_cart_url() ?>" class="cart-contents" title="<?php _e('View Cart', 'trav') ?>"> 
					<i class="soap-icon-shopping"></i>
					<div class="item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></div>
				</a>
			</div>
		<?php }	?>
		<!--<button class="btn-small pull-right inspire-btn hidden-mobile">INSPIRE ME</button>-->
		<?php if ( has_nav_menu( 'header-menu' ) ) {
				wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => 'nav', 'container_id' => 'main-menu', 'menu_class' => 'menu', 'walker'=>new Trav_Walker_Nav_Menu ) ); 
			} else { ?>
				<nav id="main-menu" class="menu-my-menu-container">
					<ul class="menu">
						<li class="menu-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php _e('Home', "trav"); ?></a></li>
						<li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "trav"); ?></a></li>
					</ul>
				</nav>
		<?php } ?>
	</div>