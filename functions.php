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
		 * Enable AMP Theme Support
		 */
        
        add_theme_support( 'amp-template-mode' );

        /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on levelup, use a find and replace
		 * to change 'level-up' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'level-up', get_template_directory() . '/languages' );
        
        // Enable AMP Support
        add_theme_support( "amp-template-mode" );
        
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
	$GLOBALS['content_width'] = apply_filters( 'levelup_content_width', 960 );
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
require get_template_directory() . '/inc/customizer/customizer.php';
require get_template_directory() . '/inc/customizer/defaults.php';


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

add_action( 'init', 'levelup_add_editor_styles' );
/**
 * Apply theme's stylesheet to the visual editor.
  * @uses add_editor_style() Links a stylesheet to visual editor
 */
function levelup_add_editor_styles() {
	add_editor_style( 'custom-editor-style.css');
}


/**
* Add Admin styles
*
* @return void
*/
function levelup_custom_wp_admin_style() {
        wp_register_style( 'levelup_admin_style', get_template_directory_uri() . '/admin-style.css', false, '1.0.0' );
        wp_enqueue_style( 'levelup_admin_style' );
}
add_action( 'admin_enqueue_scripts', 'levelup_custom_wp_admin_style' );

/*****
* Levelup required plugin
* Start
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
    $plugin_base_name = 'accelerated-mobile-pages/accelerated-moblie-pages.php';
    $plugin_slug      = 'accelerated-mobile-pages';
    $plugin_filename  = 'accelerated-moblie-pages.php';
    $plugin_title     = __('Accelerated Mobile Pages', 'level-up');
    $classArray = array( 'button', 'button-primary' );
    if(!file_exists( WP_PLUGIN_DIR."/".$plugin_base_name )){
        $classArray[] = 'level-up-recommended-plugin';
    }
    $links_attrs = array(
        'class'                 => $classArray,
        'data-plugin-slug'      => $plugin_slug,

        'data-activating-label' => __('Activating ..', 'level-up'),
        'data-activate-url'     => levelup_get_plugin_activation_link( $plugin_base_name, $plugin_slug, $plugin_filename ),
        'data-activate-label'   => sprintf( __('Activate %s', 'level-up'), $plugin_title ),

        'data-install-url'      => levelup_get_plugin_install_link( $plugin_slug ),
        'data-install-label'    => sprintf( __('Install %s', 'level-up' ), $plugin_title ),

        'data-redirect-url'     => self_admin_url( 'admin.php?page=amp_options' )
    );

    $installed_plugins  = get_plugins();
    $anyShow = $show = false;

    if( ! isset( $installed_plugins[ $plugin_base_name ] ) ){
        $links_attrs['data-action'] = 'install';
        $links_attrs['href'] = $links_attrs['data-install-url'];
        $button_label = sprintf( esc_html__( 'Install %s', 'level-up' ), $plugin_title );
        $anyShow = $show = true;
    } elseif( ! levelup_is_plugin_active( $plugin_base_name ) ) {
        $links_attrs['data-action'] = 'activate';
        $links_attrs['href'] = $links_attrs['data-activate-url'];
        $button_label = sprintf( esc_html__( 'Activate %s Core Plugin', 'level-up' ), $plugin_title );
        $anyShow = $show = true;
    } /*else {
        return;
    }*/
    if($show){
?>
    <div class="updated levelup-message levelup-notice-wrapper levelup-notice-install-now">
        <h3 class=""><?php printf( __( 'Thanks for choosing %s', 'level-up' ), 'Level UP' ); ?></h3>
        <p class="levelup-notice-description"><?php printf( __( 'To take full advantages of LevelUP theme, please install %s plugin.', 'level-up' ), '<strong>'. $plugin_title .'</strong>' ); ?></p>
        <p class="submit">
            <a <?php echo levelup_make_html_attributes( $links_attrs ); ?> ><?php echo $button_label; ?></a>
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'levelup-hide-core-plugin-notice', 'install' ), 'levelup_hide_notices_nonce', '_notice_nonce' ) ); ?>" class="notice-dismiss levelup-close-notice"><span class="screen-reader-text"><?php _e( 'Skip', 'level-up' ); ?></span></a>
        </p>
    </div>
<?php } ?>
    <?php 
	if($anyShow){
	?>
    <script>
            jQuery(document).ready(function(){

               jQuery(".level-up-recommended-plugin").click(function(e){
                    e.preventDefault();
                    var url = jQuery(this).attr("href");
                    var redirect_url = jQuery(this).attr("data-redirect-url");
                    var slug = jQuery(this).attr("data-plugin-slug");
                    jQuery(this).text("Please wait Downloading...");
                    var self = jQuery(this); 
                    wp.updates.installPlugin(
                        {
                            slug: slug,
                            success: function(pluginresponse){
                                //wp.updates.installPluginSuccess(pluginresponse);
                                //wpActivateModulesUpgrage(, self, response, nonce)
                                self.text("Activating...")
                                var url = pluginresponse.activateUrl;
                                jQuery.ajax({
                                    async: true,
                                    type: 'GET',
                                    url: url,
                                    success: function () {
                                        self.removeClass('updating-message');
                                        self.text('Activated Successfully..');
                                        window.location.href = redirect_url;
                                    }
                                });
                            },
                            error : function(response){
                                if(response.errorCode=="folder_exists"){
                                     self.text("Activating...");
                                     jQuery.ajax({
                                        async: true,
                                        type: 'GET',
                                        url: url,
                                        success: function () {
                                            self.removeClass('updating-message');
                                            self.text('Activated Successfully..');
                                            window.location.href = redirect_url;
                                        }
                                    });
                                }
                            }
                        }
                    );

                    
                    
                    return false;
                })
            });

            </script>
<?php
	}
}
add_action( 'admin_notices', 'levelup_core_plugin_notice' );
/*****
* END Levelup required plugin
*****/

/*****
* Start Levelup theme AMP
*****/

add_action( 'amp_post_template_css', 'levelup_body_font_amp_design_styling' );
function levelup_body_font_amp_design_styling(){
	$defaults = levelup_generate_defaults();
	echo 'body {
		font-family: "'. $defaults['levelup_body_font_family'] .'", sans-serif;
	
	}';
}
/*****
* END Levelup theme AMP
*****/

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     * @since Twenty Nineteen 1.4
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         * @since Twenty Nineteen 1.4
         */
        do_action( 'wp_body_open' );
    }
endif;