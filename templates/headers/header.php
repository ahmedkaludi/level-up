<?php
/**
 * Default Header
 */
global $trav_options, $logo_url, $my_account_page, $login_url, $signup_url, $language_count;
?>
<header id="header" class="navbar-static-top">
	<div class="topnav hidden-xs">
		<div class="container">
			<ul class="quick-menu pull-left">
				<?php if ( ! empty( $my_account_page ) ) { ?>
					<li><a href="<?php echo esc_url( $my_account_page ); ?>"<?php echo ( $my_account_page == '#travelo-login' )?' class="soap-popupbox"':'' ?>><?php _e( 'MY ACCOUNT', 'trav' ) ?></a></li>
				<?php } ?>
				<?php if ( trav_is_multi_currency() ) { ?>
					<li class="ribbon">
						<a href="#" title=""><?php echo esc_html( trav_get_user_currency() ); ?></a>
						<ul class="menu mini">
							<?php
								$all_currencies = trav_get_all_available_currencies();
								if ( ! empty( $all_currencies ) ) :
								foreach ( array_filter( $trav_options['site_currencies'] ) as $key => $content) {
									if ( isset( $all_currencies[$key] ) ) {
										$class = "";
										if ( trav_get_user_currency() == $key ) $class = ' class="active"';
										$params = $_GET;
										$params['selected_currency'] = $key;

										$paramString = http_build_query($params, '', '&amp;');
										echo '<li' . wp_kses_post( $class ) . '><a href="' . esc_url( strtok( $_SERVER['REQUEST_URI'], '?' ) . '?' . $paramString ) . '" title="' . esc_attr( $all_currencies[$key] ) . '">' . esc_html( strtoupper( $key ) ) . '</a></li>';
									}
								}
								endif;
							?>
						</ul>
					</li>
				<?php } ?>
				<?php if ( $language_count > 1 ) {
					$languages = icl_get_languages('skip_missing=1'); ?>

					<li class="ribbon">
						<?php
						$langs = '<ul class="menu mini">';
						foreach ( $languages as $l ) {
							if ( $l['active'] ) {
								echo '<a href="#">' . $l['translated_name'] . '</a>';
								$langs .= '<li class="active"><a href="' . $l['url'] . '" title="' . $l['translated_name'] . '">' . $l['translated_name'] . '</a>';
							} else {
								$langs .= '<li><a href="' . $l['url'] . '" title="' . $l['translated_name'] . '">' . $l['translated_name'] . '</a>';
							}
						}
						$langs .= '</ul>';
						echo $langs; ?>
					</li>

				<?php } ?>
			</ul>
			<ul class="quick-menu pull-right">
				<!--<li><a href="https://themeforest.net/item/travelo-traveltour-booking-wordpress-theme/9806696?license=regular&open_purchase_for_item_id=9806696&purchasable=source&ref=SoapTheme" class="button yellow btn-small" style="height: 30px; font-size: 1em;"><i class="soap-icon-shopping"></i> PURCHASE</a></li>-->
				<?php if ( is_user_logged_in() ) { ?>
					<li><a href="<?php echo esc_url( wp_logout_url( trav_get_current_page_url() ) ); ?>"><?php _e( 'LOGOUT', 'trav' ) ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo $login_url ?>"<?php echo ( $login_url == '#travelo-login' )?' class="soap-popupbox"':'' ?>><?php _e( 'LOGIN', 'trav' ) ?></a></li>
					<?php if ( get_option('users_can_register') ) { ?>
						<li><a href="<?php echo $signup_url ?>"<?php echo ( $signup_url == '#travelo-signup' )?' class="soap-popupbox"':'' ?>><?php _e( 'SIGNUP', 'trav' ) ?></a></li>
					<?php } ?>
				<?php } ?>

			</ul>
		</div>
	</div>
	<div id="main-header">

		<div class="main-header">
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
			</div><!-- .container -->

		</div><!-- .main-header -->
	</div><!-- #main-header -->