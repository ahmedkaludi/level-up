<?php
/**
 * levelup functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package levelup
 */

if ( ! function_exists( 'levelup_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function levelup_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on levelup, use a find and replace
		 * to change 'level-up' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'level-up', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
			add_image_size('levelup-img-1', 347, 189, true);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'header-menu' => esc_html__( 'Header Menu', 'level-up' ),
			'primary-menu' => esc_html__( 'Primary', 'level-up' ),
			'footer-menu' => esc_html__( 'Footer', 'level-up' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'levelup_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'levelup_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function levelup_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'levelup_content_width', 640 );
}
add_action( 'after_setup_theme', 'levelup_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function levelup_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget', 'level-up' ),
		'id'            => 'footer-widget',
		'description'   => esc_html__( 'Add widgets here.', 'level-up' ),
		'before_widget' => '<div class="w-bl">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'levelup_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function levelup_scripts() {
	wp_enqueue_style( 'levelup-style', get_stylesheet_uri() );

	wp_enqueue_script( 'levelup-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
     
    wp_enqueue_script( 'levelup-drawer-min', get_template_directory_uri() . '/js/drawer.min.js', array( 'jquery' ), '', false );
    wp_enqueue_script( 'levelup-iscroll', get_template_directory_uri() . '/js/iscroll.js', array( 'jquery' ), '', false );
    wp_enqueue_script( 'levelup-main', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '', false );
	wp_enqueue_script( 'levelup-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'levelup_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function levelup_custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'levelup_custom_excerpt_length', 999 );


/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';


add_action( 'tgmpa_register', 'levelup_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function levelup_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

	array(
			'name'      => 'AMP for WP',
			'slug'      => 'accelerated-mobile-pages',
			'required'  => false,
		),
	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

	);

	tgmpa( $plugins, $config );
}


add_action( 'init', 'levelup_add_editor_styles' );
/**
 * Apply theme's stylesheet to the visual editor.
  * @uses add_editor_style() Links a stylesheet to visual editor
 */
function levelup_add_editor_styles() {
	add_editor_style( 'custom-editor-style.css');
}




/*****
* Levelup theme upload
*****/
function levelup_make_html_attributes( $attrs = array() ){

    if( ! is_array( $attrs ) ){
        return '';
    }

    $attributes_string = '';

    foreach ( $attrs as $attr => $value ) {
        $value = is_array( $value ) ? join( ' ', array_unique( $value ) ) : $value;
        $attributes_string .= sprintf( '%s="%s" ', $attr, esc_attr( trim( $value ) ) );
    }

    return $attributes_string;
}
if( ! function_exists( 'levelup_is_plugin_active' ) ){
    function levelup_is_plugin_active( $plugin_basename ){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active( $plugin_basename );
    }
}
function levelup_get_plugin_install_link( $plugin_slug ){

    // sanitize the plugin slug
    $plugin_slug = esc_attr( $plugin_slug );

    $install_link  = wp_nonce_url(
        add_query_arg(
            array(
                'action' => 'install-plugin',
                'plugin' => $plugin_slug,
            ),
            network_admin_url( 'update.php' )
        ),
        'install-plugin_' . $plugin_slug
    );

    return $install_link;
}
function levelup_get_plugin_activation_link( $plugin_base_name, $slug, $plugin_filename ) {
    $activate_nonce = wp_create_nonce( 'activate-plugin_' . $slug .'/'. $plugin_filename );
    return self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin='. str_replace( '/', '%2F', $plugin_base_name ) );
}
function levelup_core_plugin_notice(){
	if(!current_user_can('install_plugins')){
		return false;
	}
    $plugin_base_name = 'levelup/levelup.php';
    $plugin_slug      = 'levelup';
    $plugin_filename  = 'levelup.php';
    $plugin_title     = __('Levelup', 'levelup');

    $links_attrs = array(
        'class'                 => array( 'button', 'button-primary', 'levelup-install-now', 'levelup-not-installed' ),
        'data-plugin-slug'      => $plugin_slug,

        'data-activating-label' => __('Activating ..', 'level-up'),
        'data-activate-url'     => levelup_get_plugin_activation_link( $plugin_base_name, $plugin_slug, $plugin_filename ),
        'data-activate-label'   => sprintf( __('Activate %s', 'level-up'), $plugin_title ),

        'data-install-url'      => levelup_get_plugin_install_link( $plugin_slug ),
        'data-install-label'    => sprintf( __('Install %s', 'level-up' ), $plugin_title ),

        'data-redirect-url'     => self_admin_url( 'admin.php?page=levelup' )
    );

    $installed_plugins  = get_plugins();

    if( ! isset( $installed_plugins[ $plugin_base_name ] ) ){
        $links_attrs['data-action'] = 'install';
        $links_attrs['href'] = $links_attrs['data-install-url'];
        $button_label = sprintf( esc_html__( 'Install %s', 'level-up' ), $plugin_title );
    } elseif( ! levelup_is_plugin_active( $plugin_base_name ) ) {
        $links_attrs['data-action'] = 'activate';
        $links_attrs['href'] = $links_attrs['data-activate-url'];
        $button_label = sprintf( esc_html__( 'Activate %s', 'level-up' ), $plugin_title );
    } else {
        return;
    }
?>
    <div class="updated levelup-message levelup-notice-wrapper levelup-notice-install-now">
        <h3 class=""><?php printf( __( 'Thanks for choosing %s', 'level-up' ), 'LevelUp' ); ?></h3>
        <p class="levelup-notice-description"><?php printf( __( 'To take full advantages of level-up theme and enabling demo importer, please install %s.', 'level-up' ), '<strong>'. $plugin_title .'</strong>' ); ?></p>
        <p class="submit">
            <a <?php echo levelup_make_html_attributes( $links_attrs ); ?> ><?php echo $button_label; ?></a>
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'levelup-hide-core-plugin-notice', 'install' ), 'levelup_hide_notices_nonce', '_notice_nonce' ) ); ?>" class="notice-dismiss levelup-close-notice"><span class="screen-reader-text"><?php _e( 'Skip', 'level-up' ); ?></span></a>
        </p>
    </div>
<?php
}
add_action( 'admin_notices', 'levelup_core_plugin_notice' );