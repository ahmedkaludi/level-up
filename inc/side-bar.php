<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * register side bar
 */
if ( ! function_exists( 'trav_register_sidebar' ) ) {
	function trav_register_sidebar() {

		$args = array(
			'name'          => __( 'Blog Sidebar', 'trav' ),
			'id'            => 'sidebar-post',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Blog Sidebar2', 'trav' ),
			'id'            => 'sidebar-post2',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Accommodation Sidebar', 'trav' ),
			'id'            => 'sidebar-acc-detail',
			'description'   => 'It will be shown on the accommodation detail page',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Tour Sidebar', 'trav' ),
			'id'            => 'sidebar-tour',
			'description'   => 'It will be shown on the tour detail page',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Car Sidebar', 'trav' ),
			'id'            => 'sidebar-car',
			'description'   => 'It will be shown on the car detail page',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

                $args = array(
			'name'          => __( 'Cruise Sidebar', 'trav' ),
			'id'            => 'sidebar-cruise',
			'description'   => 'It will be shown on the cruise detail page',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Things To Do Sidebar', 'trav' ),
			'id'            => 'sidebar-ttd',
			'description'   => 'Things To Do post Default Sidebar',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Travel Guide Sidebar', 'trav' ),
			'id'            => 'sidebar-tg',
			'description'   => 'Single Travel Guide Default Sidebar',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget travelo-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Footer Widget 1', 'trav' ),
			'id'            => 'sidebar-footer-1',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="small-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Footer Widget 2', 'trav' ),
			'id'            => 'sidebar-footer-2',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="small-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Footer Widget 3', 'trav' ),
			'id'            => 'sidebar-footer-3',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="small-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>' );
		register_sidebar( $args );

		$args = array(
			'name'          => __( 'Footer Widget 4', 'trav' ),
			'id'            => 'sidebar-footer-4',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="small-box %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widgettitle">',
			'after_title'   => '</h2>' );
		register_sidebar( $args );
	}
}

add_action( 'widgets_init', 'trav_register_sidebar' );
?>