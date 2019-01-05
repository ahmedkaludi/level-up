<?php
/**
 * Level-up Theme Customizer outout for layout settings
 *
 * @package Level-up
 */


if ( ! function_exists( 'levelup_customizer_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @see levelup_custom_header_setup().
 */
function levelup_customizer_style() {

	// Get default customizer values
	$defaults = levelup_generate_defaults();
	$header_text_color = get_header_textcolor();
	
	?>
	<style type="text/css">
	<?php
	?>
	.levelup-primary-menu .customize-partial-edit-shortcut button {
		margin-left: 50px;
	}
	.site-title a,
	.site-description {
		color: #<?php echo ! empty( $header_text_color ) ? esc_attr( $header_text_color ) : ''; ?>;
	}

	body, button, input, select, optgroup, textarea {
		color: <?php echo $defaults['body_font_color']; ?>;
	}

	body {
		font-family: "<?php echo $defaults['levelup_body_font_family']; ?>", -apple-system,BlinkMacSystemFont,"Segoe UI","Roboto","Oxygen","Ubuntu","Cantarell","Fira Sans","Droid Sans","Helvetica Neue",sans-serif;
		font-size: <?php echo $defaults['body_font_size']; ?>px;
	}

	h1, h2, h3, h4, h5, h6 {
		font-family: "<?php echo $defaults['levelup_heading_font_family']; ?>", "Helvetica Neue",sans-serif;
	}

	h1 {
		font-size: <?php echo $defaults['heading1_font_size']; ?>em;
	}

	h2 {
		font-size: <?php echo $defaults['heading2_font_size']; ?>em;
	}

	h3 {
		font-size: <?php echo $defaults['heading3_font_size']; ?>em;
	}

	h4 {
		font-size: <?php echo $defaults['heading4_font_size']; ?>em;
	}

	h5 {
		font-size: <?php echo $defaults['heading5_font_size']; ?>em;
	}

	h6 {
		font-size: <?php echo $defaults['heading6_font_size']; ?>em;
	}

	a {
		color: <?php echo $defaults['site_link_color']; ?>;
	}

	a:hover, a:focus, a:active {
		color: <?php echo $defaults['site_link_hover_color']; ?>;
	}

	.levelup-container.width {
	width: <?php echo $defaults['container_width']; ?>%;
	}

	.levelup-container.max {
	max-width: <?php echo $defaults['container_max_width']; ?>px;
	}

	.single-post .entry-content-wrapper {
	width: <?php echo $defaults['post_width']; ?>%;
	max-width: <?php echo $defaults['post_max_width']; ?>px;
	}

	.levelup-sidebar-left {
	width: <?php echo $defaults['left_sidebar_width']; ?>px;
	}

	.levelup-sidebar-right {
	width: <?php echo $defaults['right_sidebar_width']; ?>px;
	}

	body.blog, body.archive, body.single-post,
	body.blog.custom-background, body.archive.custom-background, body.single-post.custom-background {
		background-color: <?php echo $defaults['blog_bg_color']; ?>;
	}

	.levelup-wrapper > .content-area, .entry-content.single-post-entry,
	body.blog .levelup-wrapper > .content-area article.post, 
	body.archive .levelup-wrapper > .content-area article.post {
		background-color: <?php echo $defaults['post_content_bg_color']; ?>;
	}

	.single-post .entry-header.single-blog-meta.single-post-meta-large {
		background-color: <?php echo $defaults['post_meta_bg_color']; ?>;
	}

	.levelup-sidebar .widget {
		background-color: <?php echo $defaults['sidebar_widget_bg_color']; ?>;
	}

	.levelup-header-logo {
		width: <?php echo $defaults['levelup_header_logo_width']; ?>px;
	}

	.levelup-sticky-navbar .levelup-header-logo {
		width: calc(<?php echo $defaults['levelup_header_logo_width']; ?>px * .65);
	}

	.header-content .levelup-blog-logo {
		width: <?php echo $defaults['blog_logo_width']; ?>px;
	}

	.blog-header .header-content > .page-title, .archive-header .header-content > .page-title {
		font-size: <?php echo $defaults['blog_title_font_size']; ?>px;
	}

	.paged .blog-header .header-content > .page-title, .paged .archive-header .header-content > .page-title {
		font-size: calc(<?php echo $defaults['blog_title_font_size']; ?>px / 1.5);
	}

	.header-content .blog-desc, .header-content .archive-description > p {
		font-size: <?php echo $defaults['blog_desc_font_size']; ?>px;
	}

	.paged .header-content .blog-desc, .paged .header-content .archive-description > p {
		font-size: calc(<?php echo $defaults['blog_desc_font_size']; ?>px / 1.5);
	}

	.page .entry-header.entry-header-large, .page .entry-header.entry-header-mini {
		background-color: <?php echo $defaults['levelup_page_title_bg']; ?>;
	}

	.page .entry-header .entry-title {
		color: <?php echo $defaults['levelup_page_title_font_color']; ?>;
		font-size: <?php echo $defaults['levelup_page_title_font_size']; ?>px;
	}

	.levelup-breadcrumb .levelup-breadcrumb-item, .levelup-breadcrumb .levelup-breadcrumb-item a, .levelup-breadcrumb .breadcrumb-delimiter{
		color: <?php echo $defaults['levelup_breadcrumb_font_color']; ?>;
		font-size: <?php echo $defaults['levelup_breadcrumb_font_size']; ?>px;
	}
	.levelup-breadcrumb-item.current span, .breadcrumb li a:hover, .breadcrumb li a:focus{
		color: <?php echo $defaults['levelup_breadcrumb_active_font_color']; ?>;
	}

	.levelup-search-overlay {
		background-color: <?php echo $defaults['levelup_overlay_search_bg_color']; ?>;
	}

	.levelup-search-overlay::before, .levelup-search-overlay::after{
		border: <?php echo $defaults['levelup_overlay_search_border_width']; ?>px solid <?php echo $defaults['levelup_overlay_search_border_color']; ?>;
	}

	.icon-search-close {
		height: <?php echo $defaults['levelup_overlay_search_close_btn_size']; ?>px;
		width: <?php echo $defaults['levelup_overlay_search_close_btn_size']; ?>px;
		fill: <?php echo $defaults['levelup_overlay_search_close_btn_color']; ?>;
	}
	.btn--search-close:hover .icon-search-close {
		fill: <?php echo $defaults['levelup_overlay_search_close_btn_hover_color']; ?>;
	}

	.search--input-wrapper .search__input, .search--input-wrapper .search__input:focus {
		color: <?php echo $defaults['levelup_overlay_search_field_font_color']; ?>;
		font-size: <?php echo $defaults['levelup_overlay_search_field_font_size']; ?>px;
	}

	.search--input-wrapper::after {
		font-size: <?php echo $defaults['levelup_overlay_search_field_font_size']; ?>px;
	}

	.search--input-wrapper::after {
		border-color: <?php echo $defaults['levelup_overlay_search_field_font_color']; ?>;
	}

	.search__info {
		color: <?php echo $defaults['levelup_overlay_search_label_font_color']; ?>;
		font-size: <?php echo $defaults['levelup_overlay_search_label_font_size']; ?>px;
	}

	@media all and (max-width: 959px) {
	  .blog-header .header-content > .page-title, .archive-header .header-content > .page-title {
	  	font-size: calc(<?php echo $defaults['blog_title_font_size']; ?>px * .75);
	  }

	  .header-content .blog-desc, .header-content .archive-description > p {
	  	font-size: calc(<?php echo $defaults['blog_desc_font_size']; ?>px * .75);
	  }
	}

	@media all and (max-width: 480px) {
	  .blog-header .header-content > .page-title, .archive-header .header-content > .page-title {
	  	font-size: calc(<?php echo $defaults['blog_title_font_size']; ?>px * .5);
	  }

	  .header-content .blog-desc, .header-content .archive-description > p {
	  	font-size: calc(<?php echo $defaults['blog_desc_font_size']; ?>px * .5);
	  }
	}

	.levelup-header-widget-area {
		background-color: <?php echo $defaults['header_widget_area_bg_color']; ?>;
	}

	.levelup-topbar {
		background-color: <?php echo $defaults['levelup_topbar_bg_color']; ?>;
	}

	.levelup-logobar {
		background-color: <?php echo $defaults['levelup_logobar_bg_color']; ?>;
	}

	.levelup-navbar {
		background-color: <?php echo $defaults['levelup_navbar_bg_color']; ?>;
	}

	.main-navigation > ul > li > a {
		color: <?php echo $defaults['levelup_nav_menu_link_color']; ?>;
	}

	.main-navigation > ul > li.has-sub::before, .main-navigation > ul > li.has-sub::after {
		background-color: <?php echo $defaults['levelup_nav_menu_link_color']; ?>;
	}

	.main-navigation > ul > li > a:hover, .main-navigation > ul > li:hover > a, .main-navigation ul li.current-menu-item a {
		color: <?php echo $defaults['levelup_nav_menu_link_hover_color']; ?>;
	}

	.main-navigation > ul > li > a::after, .main-navigation > ul > li > a:hover::after, .main-navigation .current_page_item > a::after, .main-navigation .current-menu-item > a::after, .main-navigation .current_page_ancestor > a::after, .main-navigation .current-menu-ancestor > a::after {
		background-color: <?php echo $defaults['levelup_nav_menu_link_hover_color']; ?>;
	}

	.main-navigation > ul > li.has-sub:hover::before, .main-navigation > ul > li.has-sub:hover::after {
		background-color: <?php echo $defaults['levelup_nav_menu_link_hover_color']; ?>;
	}

	.main-navigation ul.sub-menu {
		background-color: <?php echo $defaults['levelup_submenu_bg_color']; ?>;
	}

	.main-navigation ul ul li a {
		color: <?php echo $defaults['levelup_submenu_link_color']; ?>;
	}

	.main-navigation ul ul li.has-sub::before, .main-navigation ul ul li.has-sub::after {
		background-color: <?php echo $defaults['levelup_submenu_link_color']; ?>;
	}

	.main-navigation ul ul li a:hover {
		color: <?php echo $defaults['levelup_submenu_link_hover_color']; ?>;
	}

	.main-navigation ul ul li.has-sub:hover::before, .main-navigation ul ul li.has-sub:hover::after {
		background-color: <?php echo $defaults['levelup_submenu_link_hover_color']; ?>;
	}

	.levelup-footer-widget-area {
		background-color: <?php echo $defaults['footer_widget_area_bg_color']; ?>;
	}

	.levelup-site-footer {
		background-color: <?php echo $defaults['levelup_footer_bg_color']; ?>;
	}

	.levelup-site-footer .site-info {
		color: <?php echo $defaults['levelup_footer_content_color']; ?>;
	}

	.levelup-site-footer .site-info a, .levelup-footer-menu li a {
		color: <?php echo $defaults['levelup_footer_link_color']; ?>;
	}

	.levelup-site-footer .site-info a:hover, .levelup-footer-menu li a:hover {
		color: <?php echo $defaults['levelup_footer_link_hover_color']; ?>;
	}

	</style>
	<?php
}
endif;