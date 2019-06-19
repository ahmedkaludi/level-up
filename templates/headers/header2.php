<?php
/**
 * Header 2
 */
global $trav_options, $logo_url, $my_account_page, $search_max_rooms, $search_max_adults, $search_max_kids, $login_url, $signup_url, $language_count;
?>
<header id="header" class="navbar-static-top style2">
	<div class="topnav">
		<div class="container">
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

			<ul class="quick-menu pull-right clearfix">
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
				<?php if ( trav_is_multi_currency() ) { ?>
					<li class="ribbon">
						<a href="#" title=""><?php echo esc_html( trav_get_user_currency() ) ?></a>
						<ul class="menu mini">
							<?php
								$all_currencies = trav_get_all_available_currencies();
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
								
							?>
						</ul>
					</li>
				<?php } ?>
				<?php if ( ! empty( $my_account_page ) ) { ?>
					<li><a href="<?php echo esc_url( $my_account_page ); ?>"<?php echo ( $my_account_page == '#travelo-login' )?' class="soap-popupbox"':'' ?>><?php _e( 'MY ACCOUNT', 'trav' ) ?></a></li>
				<?php } ?>
				<?php if ( isset( $trav_options['woo_show_mini_cart'] ) && $trav_options['woo_show_mini_cart'] && class_exists( 'WooCommerce' ) ) { ?>
					<li> 
						<div class="mini-cart">
							<a href="<?php echo wc_get_cart_url() ?>" class="cart-contents" title="<?php _e('View Cart', 'trav') ?>"> 
								<i class="soap-icon-shopping"></i>
								<div class="item-count"><?php echo WC()->cart->get_cart_contents_count(); ?></div>
							</a>
						</div>
					</li>
				<?php }	?>
				<?php if ( is_user_logged_in() ) { ?>
					<li><a href="<?php echo esc_url( wp_logout_url( trav_get_current_page_url() ) ); ?> "><?php _e( 'LOGOUT', 'trav' ) ?></a></li>
				<?php } else { ?>
					<li><a href="<?php echo $login_url ?>"<?php echo ( $login_url == '#travelo-login' )?' class="soap-popupbox"':'' ?>><?php _e( 'LOGIN', 'trav' ) ?></a></li>
					<?php if ( get_option('users_can_register') ) { ?>
						<li><a href="<?php echo $signup_url ?>"<?php echo ( $signup_url == '#travelo-signup' )?' class="soap-popupbox"':'' ?>><?php _e( 'SIGNUP', 'trav' ) ?></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	</div>

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
			<div class="header-search col-sm-offset-4 col-md-offset-2 hidden-mobile">
				<form role="search"  method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="hidden" name="post_type" value="accommodation">
					<div class="col-xs-6">
						<input type="text" name="s" value="" placeholder="<?php _e( 'Where do you want to go?', 'trav') ?>" class="input-text white-bg full-width where" />
					</div>
					<div class="col-xs-6">
						<div class="col-xs-3">
							<div class="datepicker-wrap from-today transparent">
								<input type="text" name="date_from" class="input-text white-bg full-width check-in" placeholder="<?php _e( 'Check In','trav' ); ?>" />
							</div>
						</div>
						<div class="col-xs-3">
							<div class="datepicker-wrap from-today transparent">
								<input type="text" name="date_to" class="input-text white-bg full-width check-out" placeholder="<?php _e( 'Check Out','trav' ); ?>" />
							</div>
						</div>
						<div class="col-xs-3">
							<div class="selector style1 guest">
								<select class="full-width white-bg" name="adults">
									<?php
										$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
										for ( $i = 1; $i <= $search_max_adults; $i++ ) {
											$selected = '';
											if ( $i == $adults ) $selected = 'selected';
											$label = ( $i == 1 )?( $i . ' ' . __( 'Guest', 'trav' ) ):( $i . ' ' . __( 'Guests', 'trav' ) );

											echo '<option value="' . esc_attr( $i ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $label ) . '</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-xs-3">
							<button type="submit" class="btn-medium uppercase full-width"><?php _e( 'search', 'trav') ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>