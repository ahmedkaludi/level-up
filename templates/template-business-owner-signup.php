<?php
 /*
 Template Name: Business Owner SignUp Template
 */
?>
<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE   ]>    <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<html <?php language_attributes(); ?>>
<head>
	<!-- Page Title -->
	<title><?php wp_title(' - ', true, 'right'); ?></title>

	<!-- Meta Tags -->
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php global $trav_options, $logo_url; ?>
	<?php if ( ! empty( $trav_options['favicon'] ) && ! empty( $trav_options['favicon']['url'] ) ): ?>
	<link rel="shortcut icon" href="<?php echo esc_url( $trav_options['favicon']['url'] ); ?>" type="image/x-icon" />
	<?php endif; ?>

	<!-- Theme Styles -->
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,200,300,500' rel='stylesheet' type='text/css'>

	<!-- CSS for IE -->
	<!--[if lte IE 9]>
		<link rel="stylesheet" type="text/css" href="css/ie.css" />
	<![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script type='text/javascript' src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	  <script type='text/javascript' src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
	<![endif]-->
	<?php wp_head();?>
</head>
<body <?php body_class( array( 'soap-login-page', 'style1', 'body-blank' ) ); ?>>
<?php
	if ( have_posts() ) {
		while ( have_posts() ) : the_post();
?>
	<div id="page-wrapper" class="wrapper-blank">
		<header id="header" class="navbar-static-top">
			<a href="#mobile-menu-01" data-toggle="collapse" class="mobile-menu-toggle blue-bg">Mobile Menu Toggle</a>
			<div class="container"><h1 class="logo"></h1></div>
			<!-- mobile menu -->
			<?php if ( has_nav_menu( 'header-menu' ) ) {
					wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => 'nav', 'container_class' => 'mobile-menu collapse', 'container_id' => 'mobile-menu-01', 'menu_class' => 'menu', 'menu_id' => 'mobile-primary-menu' ) ); 
				} else { ?>
					<nav id="mobile-menu-01" class="mobile-menu collapse">
						<ul id="mobile-primary-menu" class="wrap">
							<li class="menu-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php _e('Home', "trav"); ?></a></li>
							<li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "trav"); ?></a></li>
						</ul>
					</nav>
			<?php } ?>
			<!-- mobile menu -->
		</header>
		<section id="content">
			<div class="container">
				<div id="main">
					<h1 class="logo block">
						<a href="<?php echo esc_url( home_url() ); ?>" title="Travelo - home">
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>" />
						</a>
					</h1>
					<div class="text-center yellow-color box" style="font-size: 4em; font-weight: 300; line-height: 1em;"><?php echo empty( $trav_options['welcome_txt'] ) ? __( 'Welcome to Travelo!', 'trav' ) : __( $trav_options['welcome_txt'], 'trav' ) ?></div>
					<p class="light-blue-color block" style="font-size: 1.3333em;">
						<?php _e( 'Register For This Site as a Business Owner', 'trav' ); ?>
					</p>
					<?php the_content(); ?>
					<div class="col-sm-8 col-md-6 col-lg-5 no-float no-padding center-block">
						<form name="registerform" class="login-form" action="<?php echo esc_url( wp_registration_url() )?>" method="post">
							<p id="reg_passmail"><?php _e( 'A password will be e-mailed to you.', 'trav') ?></p>
							<div class="form-group">
								<input type="text" name="user_login"  class="input-text input-large full-width" placeholder="<?php _e( 'User Name', 'trav' ); ?>" value="" size="20"></label>
							</div>
							<div class="form-group">
								<input type="text" name="user_email"  class="input-text input-large full-width" placeholder="<?php _e( 'Email', 'trav' ); ?>" value="" size="25"></label>
							</div>
							<br class="clear">
							<input type="hidden" name="redirect_to" value="<?php echo esc_url( add_query_arg( 'checkemail', 'registered', trav_get_current_page_url() ) ); ?>">
							<input type="hidden" name="user_role" value="business_owner">
							<button type="submit" class="btn-large full-width sky-blue1"><?php _e('Register as Business Owner', 'trav')?></button>
							<p><br />
								<?php _e( 'Already a member?', 'trav' ); ?>
								<a href="<?php echo esc_url( get_permalink( $trav_options['login_page'] ) );?>" class="underline"><?php _e( 'Login', 'trav' ); ?></a>
							</p>
						</form>
					</div>
				</div>
			</div>
		</section>
		<footer id="footer">
			<div class="footer-wrapper">
				<div class="container">
					<?php if ( has_nav_menu( 'header-menu' ) ) {
							wp_nav_menu( array( 'theme_location' => 'header-menu', 'container' => 'nav', 'container_id' => 'main-menu', 'container_class'=>'inline-block hidden-mobile', 'menu_class' => 'menu', 'walker'=>new Trav_Walker_Nav_Menu ) ); 
						} else { ?>
							<nav id="main-menu" class="inline-block hidden-mobile">
								<ul class="menu">
									<li class="menu-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php _e('Home', "trav"); ?></a></li>
									<li class="menu-item"><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>"><?php _e('Configure', "trav"); ?></a></li>
								</ul>
							</nav>
					<?php } ?>
					<div class="copyright">
						<p>&copy; <?php echo esc_html( $trav_options['copyright'] ); ?></p>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<?php
	endwhile;
}
?>
<?php wp_footer(); ?>
</body>
</html>