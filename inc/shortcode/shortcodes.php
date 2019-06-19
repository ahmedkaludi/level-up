<?php
/*
 * Shortcodes Class
 */
if ( ! class_exists( 'TravShortcodes') ) :
class TravShortcodes {

	public $shortcodes = array(
		"row",
		"column",
		"five_column",
		"one_half",
		"one_third",
		"one_fourth",
		"two_third",
		"three_fourth",
		"container",
		"section",
		"block",
		"folded_corner_block",
		"toggles",
		"toggle",
		"button",
		"alert",
		"blockquote",
		"social_links",
		"dropcap",
		"checklist",
		"tabs",
		"tab",
		"testimonials",
		"testimonial",
		"icon_box",
		"parallax_block",
		"border_box",
		"similar_accommodations",
		"accommodation_booking",
		"accommodation_booking_confirmation",
		"accommodations",
		"tour_booking",
		"tour_booking_confirmation",
		"tours",
		"latest_tours",
		"posts",
		"blog",
		"dashboard",
		"slider",
		"slide",
		"bgslider",
		"imageframe",
		"images",
		"content_boxes",
		"content_box",
		"content_box_detail",
		"content_box_detail_row",
		"content_box_action",
		"promo_box",
		"promo_box_left",
		"promo_box_right",
		"search_form",
		"search_form_textfield",
		"search_group",
		"animation",
		"rating",
		"person",
		"team_member",
		"map",
		"pricing_table_vc",
		"pricing_table",
		"pricing_table_head",
		"pricing_table_content",
		"pricing_table_features",
		"locations",
		"car_booking",
		"car_booking_confirmation",
		"cars",
		"cruise_booking",
		"cruise_booking_confirmation",
		"cruises",
	 );

	function __construct() {
		add_action( 'init', array( $this, 'add_shortcodes' ) );
		add_filter('the_content', array( $this, 'filter_eliminate_autop' ) );
		add_filter('widget_text', array( $this, 'filter_eliminate_autop' ) );
	}

	/* ***************************************************************
	* **************** Remove AutoP tags *****************************
	* **************************************************************** */
	function filter_eliminate_autop( $content ) {
		$block = join( "|", $this->shortcodes );

		// replace opening tag
		$content = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );

		// replace closing tag
		$content = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)/", "[/$2]", $content );
		return $content;
	}

	/* ***************************************************************
	* **************** Add Shortcodes ********************************
	* **************************************************************** */
	function add_shortcodes() {
		foreach ( $this->shortcodes as $shortcode ) {
			$function_name = 'shortcode_' . $shortcode ;
			add_shortcode( $shortcode, array( $this, $function_name ) );
		}
		// to avoid nested shortcode issue for block
		for ( $i = 1; $i < 10; $i++ ) {
			add_shortcode( 'block' . $i, array( $this,'shortcode_block' ) );
		}
		add_shortcode( 'box', array( $this,'shortcode_block' ) );
	}

	/* ***************************************************************
	* *************** Grid System ************************************
	* **************************************************************** */
	//shortcode row
	function shortcode_row( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '<div class="row' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	//shortcode column
	function shortcode_column( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'lg'        => '',
			'md'        => '',
			'sms'        => '',
			'sm'        => '',
			'xs'        => '',
			'lgoff'        => '',
			'mdoff'        => '',
			'smsoff'        => '',
			'smoff'        => '',
			'xsoff'        => '',
			'lghide'    => '',
			'mdhide'    => '',
			'smshide'    => '',
			'smhide'    => '',
			'xshide'    => '',
			'lgclear'    => '',
			'mdclear'    => '',
			'smsclear'    => '',
			'smclear'    => '',
			'xsclear'    => '',
			'class'        => ''
		), $atts ) );

		$devices = array( 'lg', 'md', 'sm', 'sms', 'xs' );
		$classes = array();
		foreach ( $devices as $device ) {

			//grid column class
			if ( ${$device} != '' ) $classes[] = 'col-' . $device . '-' . ${$device};

			//grid offset class
			$device_off = $device . 'off';
			if ( ${$device_off} != '' ) $classes[] = 'col-' . $device . '-offset-' . ${$device_off};

			//grid hide class
			$device_hide = $device . 'hide';
			if ( ${$device_hide} == 'yes' ) $classes[] =  'hidden-' . $device;

			//grid clear class
			$device_clear = $device . 'clear';
			if ( ${$device_clear} == 'yes' ) $classes[] = 'clear-' . $device;

		}
		if ( ! empty( $class ) ) $classes[] = $class;

		$result = '<div class="' . esc_attr(  implode(' ', $classes) ) . '">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode one_half
	function shortcode_one_half( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-6' . esc_attr( $class ) . ' one-half">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode one_third
	function shortcode_one_third( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-4' . esc_attr( $class ) . ' one-third">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode two_third
	function shortcode_two_third( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-8' . esc_attr( $class ) . ' two-third">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode one_fourth
	function shortcode_one_fourth( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-3 ' . esc_attr( $class ) . ' one-fourth">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode three_fourth
	function shortcode_three_fourth( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'offset' => 0,
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $offset != 0 ) $class .= ' col-sm-offset-' . $offset;
		
		$result = '<div class="col-sm-9 ' . esc_attr( $class ) . ' three-fourth">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	//shortcode five_column
	function shortcode_five_column( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'no_margin' => 'no'
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $no_margin == 'false' || $no_margin == 'no' ) {
			$class = 'column-5' . $class;
		} else {
			$class = 'column-5-no-margin' . $class;
		}
		$result = '<div class="' . esc_attr( $class ) . '">';
		$result .= do_shortcode($content);
		$result .= '</div>';

		return $result;
	}

	/* ***************************************************************
	* ******************** Container Shortcode ***********************
	* **************************************************************** */
	function shortcode_container( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '<div class="container' . esc_attr( $class ) . '">';
		$result .= do_shortcode($content);
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ********************* Section Shortcode ************************
	* **************************************************************** */
	function shortcode_section( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$class = 'section' . $class;

		$result = '<div class="' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ********************** Block Shortcode *************************
	* **************************************************************** */
	function shortcode_block( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => '',
			'class' => '',
			'background' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$background = empty( $background )?'':( ' style="background:' . esc_attr( $background ) . '"' );
		$result = '';
		switch ( $type ) {
			case "small": // margin-bottom : 20
				$class = 'small-box' . $class;
				break;
			case "medium": // margin-bottom : 30
				$class = 'box' . $class;
				break;
			case "large": // margin-bottom : 70
				$class = 'large-block' . $class;
				break;
			case "whitebox": // white background
				$class = 'travelo-box' . $class;
				break;
			case "borderbox": // white background
				$class = 'border-box' . $class;
				break;
			case "section": // margin-bottom : 70
				$class = 'section' . $class;
				break;
			default: // margin-bottom : 40
				$class = 'block' . $class;
				break;
		}

		$result = '<div class="' . esc_attr( $class ) . '"' . $background . '>';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* *************** Folded Corner Block Shortcode ******************
	* **************************************************************** */
	function shortcode_folded_corner_block( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
			'background' => '#fff',
			'fold_color' => '#d9d9d9',
			'fold_size' => '60'
		), $atts ) );
		static $folded_corner_id = 1;
		$class = empty( $class )?'':( ' ' . $class );
		$fc_block_id = 'f-corner-' . $folded_corner_id;
		if ( ! is_numeric( $fold_size ) ) $fold_size = 60;
		$result = '';
		$result .= "<div><style scoped>#{$fc_block_id} { background:{$background};margin-right:{$fold_size}px} #{$fc_block_id}:before{background:{$background};width:{$fold_size}px;right:-{$fold_size}px;bottom:{$fold_size}px;top:0;}#{$fc_block_id}:after{right: -{$fold_size}px;bottom:0;border-top:{$fold_size}px solid {$fold_color}; border-right: {$fold_size}px solid transparent;}</style>";
		$result .= '<div id="' . esc_attr( $fc_block_id ) . '" class="fc-block' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '<div class="clearfix"></div>';
		$result .= '</div></div>';
		$folded_corner_id++;
		return $result;
	}

	/* ***************************************************************
	* **************** Toggle Container Shortcode ********************
	* **************************************************************** */
	public $accordion_id = 1;    //to generate unique accordion id
	// public $toggle_style = 'style1';    //toggle style ( style1|style2 )
	public $toggle_type = 'toggle';    //toggle type ( accordion|toggle )
	public $toggle_with_image = false;

	function shortcode_toggles( $atts, $content = null ) {
		extract( shortcode_atts( array(
				'title' => '',
				'type' => '', //available values (''|accordion)
				'style' => 'style1', //available values 2( style1|style2 )
				'class' => '',
				'with_image' => 'no',
				'image_animation_type' => 'fadeIn', //available values 62 ( bounce|flash|pulse|rubberBand|shake|swing|tada|wobble|bounceIn|bounceInDown|bounceInLeft|bounceInRight|bounceInUp|bounceOut|bounceOutDown|bounceOutLeft|bounceOutRight|bounceOutUp|fadeIn|fadeInDown|fadeInDownBig|fadeInLeft|fadeInLeftBig|fadeInRight|fadeInRightBig|fadeInUp|fadeInUpBig|fadeOut|fadeOutDown|fadeOutDownBig|fadeOutLeft|fadeOutLeftBig|fadeOutRight|fadeOutRightBig|fadeOutUp|fadeOutUpBig|flip|flipInX|flipInY|flipOutX|flipOutY|lightSpeedIn|lightSpeedOut|rotateIn|rotateInDownLeft|rotateInDownRight|rotateInUpLeft|rotateInUpRight|rotateOut|rotateOutDownLeft|rotateOutDownRight|rotateOutUpLeft|rotateOutUpRight|slideInDown|slideInLeft|slideInRight|slideOutLeft|slideOutRight|slideOutUp|hinge|rollIn|rollOut )
				'image_animation_duration' => 1
		), $atts ) );

		// $this->toggle_style = $style;
		$this->toggle_type = $type;
		$this->toggle_with_image = $with_image;

		$class = empty( $class )?'':( ' ' . $class );
		$img_atts = '';
		if ( $with_image == 'yes' || $with_image == 'true' ) {
			$class .= ' with-image';
			$img_atts .= 'data-image-animation-type="'. esc_attr( $image_animation_type ) .'" data-image-animation-duration="' . esc_attr( $image_animation_duration ) . '"';
		}
		$result = '';
		if ( ! empty( $title ) ) { $result .= '<h2>' . esc_html( $title ) . '</h2>'; }
		$result .= '<div class="toggle-container ' . esc_attr( $style ) . esc_attr( $class ) . '" id="accordion' . esc_attr( $this->accordion_id ) . '" ' . $img_atts . ' >';
		$result .= do_shortcode( $content );
		$result .= '</div>';

		$this->accordion_id++;
		return $result;
	}

	/* ***************************************************************
	* **************** Toggle Shortcode ******************************
	* **************************************************************** */
	function shortcode_toggle( $atts , $content = null ) {
		extract( shortcode_atts( array(
				'title' => '',
				'collapsed' => 'yes',
				'class' => '',
				'img_src' => '',
				'img_alt' => 'toggle-image',
				'img_width' => '',
				'img_height' => '',
		), $atts ) );

		static $toggle_id = 1;

		$data_parent = '';
		if ( $this->toggle_type == "accordion" ) {
			$data_parent = ' data-parent="#accordion' . esc_attr( $this->accordion_id ) . '"';
		}

		$class = empty( $class )?'':( ' ' . $class );
		$class_in = (( $collapsed == 'false')||( $collapsed == 'no'))?' in':'';
		$class_collapsed = (( $collapsed == 'false')||( $collapsed == 'no'))?'':' class="collapsed"';
		$result = '';
		$result .= '<div class="panel ' . esc_attr( $class ) . '">';
		if ( ( $this->toggle_with_image == 'yes' || $this->toggle_with_image == 'true' ) && ( ! empty( $img_src ) ) ) {
			$img_alt = ( $img_alt != '')?" alt='" . esc_attr( $img_alt ) . "'":'';
			$img_width = ( $img_width != '')?" width='" . esc_attr( $img_width ) . "'":'';
			$img_height = ( $img_height != '')?" height='" . esc_attr( $img_height ) . "'":'';
			$result .= '<img src="' . esc_url( $img_src ) . '"' . $img_alt . $img_width . $img_height . '/>';
		}
		$result .= '<h4 class="panel-title"><a href="#acc' . esc_attr( $toggle_id ) . '" data-toggle="collapse"' . $data_parent . $class_collapsed . '>' . $title . '</a></h4>';
		$result .= '<div class="panel-collapse collapse' . $class_in . '" id="acc' . esc_attr( $toggle_id ) . '"><div class="panel-content"><p>';
		$result .= do_shortcode( $content );
		$result .= '</p></div></div></div>';

		$toggle_id++;

		return $result;
	}

	/* ***************************************************************
	* **************** Button Shortcode ******************************
	* **************************************************************** */
	function shortcode_button( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'link' => '#',
			'color' => '', //available values 18 ( white|silver|sky-blue1|yellow|dark-blue1|green|red|light-brown|orange|dull-blue|light-orange|light-purple|sea-blue|sky-blue2|dark-blue2|dark-orange|purple|light-yellow )
			'type' => '', //available values 4 ( large|medium|small|mini|extra )
			'target' => '_self', //available values 5 ( _blank|_self|_parent|_top|framename )
			'tag' => 'a', // available values 2 ( a|button )
			'icon' => '',
			'class' => ''
		), $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$color = empty( $color )?'':( ' ' . $color );
		$type = empty( $type )?'':( ' btn-' . $type );
		$result = '';
		if ( $type == " btn-extra" ) {
			if ( empty( $icon ) ) $icon = 'fa fa-cog';
			$result .= '<a href="' . esc_url( $link ) . '" class="button' . esc_attr( $type . $color . $class ) . '" target="' . esc_attr( $target ) . '"><div class="icon-wrap"><i class="' . esc_attr( $icon ) . '"></i></div><span>';
			$result .= do_shortcode( $content );
			$result .= '</span></a>';
		} else {
			if ( $tag == 'button' ) {
				$result .= '<button class="' . esc_attr( $type . $color . $class ) . '">';
				$result .= do_shortcode( $content );
				$result .= '</button>';
			} else {
				$result .= '<a href="' . esc_url( $link ) . '" class="button' . esc_attr( $type . $color . $class ) . '" target="' . esc_attr( $target ) . '">';
				$result .= do_shortcode( $content );
				$result .= '</a>';
			}
		}
		return $result;
	}

	/* ***************************************************************
	* **************** Alert Shortcode *******************************
	* **************************************************************** */
	function shortcode_alert( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'type' => 'general', // general,error,help,notice,success,info
			'class' => ''
		), $atts) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<div class="alert alert-' . esc_attr( $type . $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '<span class="close"></span></div>';

		return $result;
	}

	/* ***************************************************************
	* **************** Blockquote Shortcode **************************
	* **************************************************************** */
	function shortcode_blockquote( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'style' => 'style1', //available values ( style1|style2 )
			'class' => ''
		), $atts) );

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$triangle = ( $style == 'style1' )?'<span class="triangle"></span>':''; //if style1 then add triangle
		$result .= '<blockquote class="' . esc_attr( $style . $class ) . '">' . $triangle;
		$result .= do_shortcode( $content );
		$result .= '</blockquote>';

		return $result;
	}

	/* ***************************************************************
	* **************** Social_links Shortcode ************************
	* **************************************************************** */
	function shortcode_social_links($atts, $content = null) {

		$variables = array( 'style'=>'style1', 'linktarget'=>'_blank', 'twitter' => '', 'googleplus' => '', 'facebook' => '', 'linkedin' => '', 'vimeo' => '', 'flickr' => '', 'skype' => '', 'class'=>'' ); //available style values ( style1|style2 )
		extract( shortcode_atts( $variables, $atts ) );
		$social_links = array( 'twitter' => '', 'googleplus' => '', 'facebook' => '', 'linkedin' => '', 'vimeo' => '', 'flickr' => '', 'skype' => '' );
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<ul class="social-icons clearfix ' . esc_attr( $style . $class ) . '">';

		//available keys ( twitter, googleplus, facebook, linkedin, vimeo, dribble, flickr )
		foreach ( $atts as $key => $link ) {
			if ( ( array_key_exists( $key, $social_links ) && ! empty( $link ) ) || ! array_key_exists( $key, $variables ) ) {
				$result .= '<li class="' . esc_attr( $key ) . '">';
				if ( 'skype' == $key ) {
					$result .= '<a title="' . esc_attr( $key ) . '" href="' . ( $link ) . '" data-toggle="tooltip"><i class="soap-icon-' . esc_attr( $key ) . '"></i></a></li>';
				} else {
					$result .= '<a title="' . esc_attr( $key ) . '" target="' . esc_attr( $linktarget ) . '" href="' . esc_url( $link ) . '" data-toggle="tooltip"><i class="soap-icon-' . esc_attr( $key ) . '"></i></a></li>';
				}
			}
		}

		$result .= '</ul>';

		return $result;
	}

	/* ***************************************************************
	* **************** DropCap Shortcode *****************************
	* **************************************************************** */
	function shortcode_dropcap($atts, $content = null) {

		$variables = array( 'style'=>'style1', 'class'=>'' ); //available style values ( style1|style2 )
		extract( shortcode_atts( $variables, $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		if ( $style == 'style2' ) $class=' colored' . $class;
		$result = '';
		$result .= '<p class="dropcap ' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</p>';

		return $result;
	}

	/* ***************************************************************
	* **************** Check List Shortcode *****************************
	* **************************************************************** */
	function shortcode_checklist($atts, $content = null) {

		$variables = array( 'icon'=>'triangle', 'class'=>'' ); //available icon values ( arrow,triangle,circle,check,chevron,arrow-square,decimal,upper-roman,lower-latin,upper-latin,check-circle )
		extract( shortcode_atts( $variables, $atts ) );

		$class = empty( $class )?'':( ' ' . $class );
		$class = $icon . $class;
		$result = str_replace( '<ul>', '<ul class="' . esc_attr( $class ) . '">', $content);
		$result = str_replace( '<li>', '<li>', $result);
		$result = do_shortcode( $result );

		return $result;
	}

	/* ***************************************************************
	* **************** Tabs Shortcode ********************************
	* **************************************************************** */
	public $tabs_first_tab = true;
	function shortcode_tabs($atts, $content = null) {
		$variables = array( 'title'=>'', 'style'=>'', 'bg_color'=>'', 'class'=>'', 'img_src'=>'', 'img_height'=>'', 'img_width'=>'', 'img_alt'=>'tab-image' ); //available style values ( ''|style1|trans-style|full-width-style )
		extract( shortcode_atts( $variables, $atts ) );

		$this->tabs_first_tab = true;
		$result = '';
		if ( ! empty( $title ) ) { $result .= '<h2>' . esc_html( $title ) . '</h2>'; }

		if ( ( $style == 'trans-style' ) && ( ! empty( $img_src ) ) ) {
			$img_alt = ( $img_alt != '')?" alt='" . esc_attr( $img_alt ) . "'":'';
			$img_width = ( $img_width != '')?" width='" . esc_attr( $img_width ) . "'":'';
			$img_height = ( $img_height != '')?" height='" . esc_attr( $img_height ) . "'":'';
			$result .= '<img class="full-width" src="' . esc_url( $img_src ) . '"' . $img_alt . $img_width . $img_height . '/>';
		}

		$class = empty( $class )?'':( ' ' . $class );
		$bg_color = empty( $bg_color )?'':( ' ' . $bg_color );
		$result .= '<div class="tab-container ' . esc_attr( $style . $class . $bg_color ) . '">';
		$result .= '<ul class="tabs">';
		$active = ' class="active"';
		foreach ( $atts as $key => $tab ) {
			if ( ! array_key_exists( $key, $variables ) ) {
				$result .= '<li' . $active . '><a href="#' . esc_attr( $key ) . '" data-toggle="tab">' . balancetags( htmlspecialchars_decode( $tab ), true ) . '</a></li>';
				$active = '';
			}
		}
		$result .= '</ul>';
		$result .= '<div class="tab-content">';
		$result .= do_shortcode( $content );
		$result .= '</div></div>';

		return $result;
	}

	/* ***************************************************************
	* **************** Tab Shortcode ********************************
	* **************************************************************** */
	function shortcode_tab($atts, $content = null) {

		extract( shortcode_atts( array(
			'id' => '',
			'class' => ''
		), $atts) );

		$active = '';
		if ( $this->tabs_first_tab ) {
			$this->tabs_first_tab = false;
			$active = ' active in';
		}

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<div class="tab-pane fade' . esc_attr( $active . $class ) . '" id="' . esc_attr( $id ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Testimonial Container Shortcode ***************
	* **************************************************************** */
	public $testimonial_style = 'style1';
	public $author_img_size = '';
	function shortcode_testimonials($atts, $content = null) {

		extract( shortcode_atts( array(
			'style' => 'style1',
			'title' => '',
			'class' => '',
			'author_img_size' => '74'
		), $atts) );

		$all_style = array( 'style1', 'style2', 'style3' );
		if ( ! in_array( $style, $all_style ) ) $style = 'style1';
		$this->testimonial_style = $style;
		if ( empty( $author_img_size ) ) $author_img_size = '74';
		$this->author_img_size = $author_img_size;

		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		if ( $style != "style3" ) {
			if ( ! empty( $title ) ) { $result .= '<h2>' . esc_html( $title ) . '</h2>'; }
			$result .= '<div class="testimonial ' . esc_attr( $style . $class ) . '">';
			$result .= '<ul class="slides ">';
			$result .= do_shortcode( $content );
			$result .= '</ul></div>';
		} else {
			//$result .= '<div class="global-map-area section" style="margin-top: 100px;"><div class="container">';
			$result .= empty($title)?'':"<h1 class='text-center white-color'>" . esc_html( $title ) . "</h1>";
			$result .= '<div class="testimonial ' . esc_attr( $style . $class ) . '">';
			$result .= '<ul class="slides ">';
			$result .= do_shortcode( $content );
			$result .= '</ul></div>';
			//$result .= '</div></div>';
		}
		return $result;
	}

	/* ***************************************************************
	* **************** Testimonial Shortcode *************************
	* **************************************************************** */
	function shortcode_testimonial($atts, $content = null) {

		extract( shortcode_atts( array(
			'author_name' => '',
			'author_link' => '#',
			'author_img_url' => '',
			'author_img_id' => '',
			'author_img_alt' => 'author-image',
			'class' => ''
		), $atts) );

		$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
		$result = '';
		if ( $this->testimonial_style != "style3" ) {
			$result .= '<li' . $class . '><p class="description">';
			$result .= do_shortcode( $content );
			$result .= '</p>';
			$result .= '<div class="author-section clearfix">';
			//$result .= '<a href="' . esc_url( $author_link ) . '"><img src="' . esc_url( $author_img_url ) . '" alt="' . esc_attr( $author_img_alt ) . '" width="' . esc_attr( $this->author_img_size ) . '" height="' . esc_attr( $this->author_img_size ) . '" /></a>';
			$result .= '<a href="' . esc_url( $author_link ) . '">';
			if ( ! empty( $author_img_id ) ) {
				$result .=  wp_get_attachment_image( $author_img_id, 'full' );
			} else {
				$result .= '<img src="' . esc_url( $author_img_url ) . '" alt="' . esc_attr( $author_img_alt ) . '" width="' . esc_attr( $this->author_img_size ) . '" height="' . esc_attr( $this->author_img_size ) . '" />';
			}
			$result .= '</a>';
			$result .= '<h5 class="name">' . esc_html( $author_name ) . '</h5>';
			$result .= '</div></li>';
		} else {
			$result .= '<li' . $class . '>';
			$result .= '<div class="author-section">';
			$result .= '<a href="' . esc_url( $author_link ) . '">';
			if ( ! empty( $author_img_id ) ) {
				$result .=  wp_get_attachment_image( $author_img_id, 'full' );
			} else {
				$result .= '<img src="' . esc_url( $author_img_url ) . '" alt="' . esc_attr( $author_img_alt ) . '" width="' . esc_attr( $this->author_img_size ) . '" height="' . esc_attr( $this->author_img_size ) . '" />';
			}
			$result .= '</a>';
			$result .= '</div>';
			$result .= '<blockquote class="description">';
			$result .= do_shortcode( $content );
			$result .= '</blockquote>';
			$result .= '<h2 class="name">' . esc_html( $author_name ) . '</h2>';
			$result .= '</li>';

		}
		return $result;
	}

	/* ***************************************************************
	* **************** Icon Box Shortcode ****************************
	* **************************************************************** */
	function shortcode_icon_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon' => '',
			'icon_class' => '', // depreciated
			'style' => '',
			'number' => 0, //only need in style3 case
			'class' => '',
		), $atts) );

		$class = empty( $class )? '' : ( ' ' . $class );
		$icon_class = empty( $icon_class )? '' : ( ' ' . $icon_class );
		$icon = $icon . $icon_class;

		$result = '';
		if ( empty( $style ) ) {
			$result = '<i class="' . esc_attr( $icon ) . '"></i>';
			$result .= do_shortcode( $content );
		} elseif ( $style == 'style3' ) {
			$result = '<div class="icon-box ' . esc_attr( $style ) . ' counters-box' . esc_attr( $class ) . '"><div class="numbers"><i class="' . esc_attr( $icon ) . '"></i>';
			$result .= '<span class="display-counter" data-value="' . esc_attr( $number ) . '">' . esc_attr( $number ) . '</span></div><div class="description">';
			$result .= do_shortcode( $content );
			$result .= '</div></div>';
		} elseif ( $style == 'style11' ) {
			$result = '<div class="icon-box ' . esc_attr( $style . $class ) . '"><div class="icon-wrapper"><i class="' . esc_attr( $icon ) . '"></i></div>';
			$result .= '<div class="details"><h4 class="m-title">';
			$result .= do_shortcode( $content );
			$result .= '</h4></div></div>';
		} elseif ( ( $style == 'style5' ) || ( $style == 'style6' ) || ( $style == 'style7' ) ) {
			$result = '<div class="icon-box ' . esc_attr( $style . $class ) . '"><i class="' . esc_attr( $icon ) . '"></i><div class="description">';
			$result .= do_shortcode( $content );
			$result .= '</div></div>';
		} else {
			$result = '<div class="icon-box ' . esc_attr( $style . $class ) . '"><i class="' . esc_attr( $icon );
			$result .= ( $style == 'style2')?' circle':'';
			$result .= '"></i>';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		}

		return $result;
	}

	/* ***************************************************************
	* **************** Parallax Section Shortcode ********************
	* **************************************************************** */
	function shortcode_parallax_block( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'ratio' => '0.5',
			'bg_image' => '',
			'class' => '',
		), $atts) );

		$class = empty( $class )?'':(' ' . $class );

		if ( ! is_numeric( $ratio ) ) $ratio = 0.5;

		$style = '';
		if ( empty( $bg_image ) ) {
			$class .= ' global-map-area';
		} else {
			$style = ' style="background-image:url(' . esc_url( $bg_image ) . '); background-repeat: no-repeat;"';
		}

		$result = '';
		$result .= '<div class="parallax' . esc_attr( $class ) . '" data-stellar-background-ratio="' . esc_attr( $ratio ) . '"' . $style . '>';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Border Box Shortcode ********************
	* **************************************************************** */
	function shortcode_border_box( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'class' => '',
		), $atts) );

		$class = empty( $class )?'':(' ' . $class );

		$result = '';
		$result .= '<div class="border-box' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Accommodation Booking Page Shortcode **********
	* **************************************************************** */
	function shortcode_accommodation_booking( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'accommodation-booking.php', '/templates/accommodation' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ******* Accommodation Booking Confirmation Page Shortcode ******
	* **************************************************************** */
	function shortcode_accommodation_booking_confirmation( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'accommodation-booking-confirmation.php', '/templates/accommodation' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ****************** Accommodation List Shortcode ****************
	* **************************************************************** */
	function shortcode_accommodations( $atts ) {
		extract( shortcode_atts( array(
			'title' => '',
			'type' => 'latest',
			'style' => 'style1',
			'count' => 10,
			'count_per_row' => 4,
			'city' => '',
			'state' => '',
			'country' => '',
			'acc_type' => '',
			'post_ids' => '',
			'slide' => 'true',
			'before_list' => '',
			'after_list' => '',
			'before_item' => '',
			'after_item' => '',
			'show_badge' => 'no',
			'animation_type' => '',
			'animation_duration' => '',
			'animation_delay' => '',
			'item_width' => '270',
			'item_margin' => '30',
		), $atts) );
		if ( $slide == 'no' || $slide == 'false' ) { $slide = 'false'; }
		// if ( $type == 'hot' && empty( $show_badge ) ) $show_badge = true;
		if ( $show_badge == 'no' || $show_badge == 'false' ) { $show_badge = false; }
		$styles = array( 'style1', 'style2', 'style3', 'style4' );
		$types = array( 'latest', 'featured', 'popular', 'hot', 'selected' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $type, $types ) ) $type = 'latest';
		$post_ids = explode( ',', $post_ids );
		$acc_type = ( ! empty( $acc_type ) ) ? explode( ',', $acc_type ) : array();
		$count = is_numeric( $count )?$count:10;
		$count_per_row = is_numeric( $count_per_row )?$count_per_row:4;
		$item_width = is_numeric( $item_width ) ? $item_width : 270;
		$item_margin = is_numeric( $item_margin ) ? $item_margin : 270;

		$def_before_list = '';
		$def_after_list = '';
		$def_before_item = '';
		$def_after_item = '';

		if ( $style == 'style4' ) {
			$def_before_list = '<div class="listing-style4">';
			$def_after_list = '</div>';
		} elseif ( $style == 'style3' ) {
			$def_before_list = '<div class="listing-style3 hotel">';
			$def_after_list = '</div>';
		} else {
			if ( $slide == 'false' ) {
				$def_before_list = '<div class="row hotel image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</div>';
				if ( ( 2 == $count_per_row ) ) {
					$def_before_list = '<div class="row hotel image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6'>";
					$def_after_item = "</div>";
				} elseif ( 3 == $count_per_row ) {
					$def_before_list = '<div class="row hotel image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-4'>";
					$def_after_item = "</div>";
				} else {
					$def_before_list = '<div class="row hotel image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6 col-sms-6 col-md-3'>";
					$def_after_item = "</div>";
				}
			} else {
				$def_before_list = '<div class="block image-carousel style2 flexslider" data-animation="slide" data-item-width="' . esc_attr( $item_width ) . '" data-item-margin="' . esc_attr( $item_margin ) . '"><ul class="slides hotel image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</ul></div>';
				$def_before_item = '<li>';
				$def_after_item = '</li>';
			}
		}
		if ( empty( $before_list ) ) $before_list = $def_before_list;
		if ( empty( $after_list ) ) $after_list = $def_after_list;
		if ( empty( $before_item ) ) $before_item = $def_before_item;
		if ( empty( $after_item ) ) $after_item = $def_after_item;

		$accs = array();
		if ( $type == 'selected' ) {
			$accs = trav_acc_get_accs_from_id( $post_ids );
		} elseif ( $type == 'hot' ) {
			$accs = trav_acc_get_hot_accs( $count, $country, $state, $city, $acc_type );
		} else {
			$accs = trav_acc_get_special_accs( $type, $count, array(), $country, $state, $city, $acc_type );
		}

		ob_start();
		if ( ! empty( $title ) ) { echo '<h2>' . esc_html( $title ) . '</h2>'; }
		echo ( $before_list );
		$i = 0;
		foreach ( $accs as $acc ) {
			$animation = '';
			if ( ! empty( $animation_type ) ) {
				$animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( intval( $animation_delay ) * $i ) . '" ';
			}
			trav_acc_get_acc_list_sigle( $acc->ID, $style, $before_item, $after_item, $show_badge, $animation );
			$i++;
		}
		echo ( $after_list );

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* **************** Similar Accommodations Shortcode **************
	* **************************************************************** */
	function shortcode_similar_accommodations( $atts ) {
		extract( shortcode_atts( array(
			// 'title' => __( 'Similar Accommodations', 'trav' ),
			'count' => 3,
			'class' => '',
			'thumb_width' => 64,
			'thumb_height' => 64,
		), $atts) );

		global $wp_query;
		$acc_id = $wp_query->post->ID;
		if ( get_post_type( $acc_id ) != 'accommodation' ) return false;
		$result = '';
		$result .= '<div class="image-box style14">';
		$similar_accs = trav_acc_get_similar( $acc_id, $count );
		foreach ( $similar_accs as $acc ) {
			$avg_price = get_post_meta( $acc, 'trav_accommodation_avg_price', true );
			$avg_price = empty($avg_price)?0:$avg_price;
			$result .= '<article class="box"><figure>';
			$result .= '<a href="' . esc_url( get_permalink( $acc ) ) . '">' . get_the_post_thumbnail( $acc, array( $thumb_width, $thumb_height ) ) . '</a>';
			$result .= '</figure>';
			$result .= '<div class="details">';
			$result .= '<h5 class="title"><a href="' . esc_url( get_permalink( $acc ) ) . '">' . esc_html( get_the_title( $acc ) ) . '</a></h5>';
			$result .= '<label class="price-wrapper"><span class="price-per-unit">' . esc_html( trav_get_price_field( $avg_price ) ) . '</span> ' . __( 'avg/night', 'trav' ) . '</label>';
			$result .= '</div>';
			$result .= '</article>';
		}
		$result .= '</div>';
		return $result;
	}


	/* ***************************************************************
	* **************** Tour Booking Page Shortcode **********
	* **************************************************************** */
	function shortcode_tour_booking( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'tour-booking.php', '/templates/tour' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ******* Tour Booking Confirmation Page Shortcode ******
	* **************************************************************** */
	function shortcode_tour_booking_confirmation( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'tour-booking-confirmation.php', '/templates/tour' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ****************** Tour List Shortcode ****************
	* **************************************************************** */
	function shortcode_tours( $atts ) {
		extract( shortcode_atts( array(
			'title' => '',
			'type' => 'latest',
			'style' => 'style1',
			'count' => 10,
			'count_per_row' => 3,
			'city' => '',
			'state' => '',
			'country' => '',
			'tour_type' => '',
			'post_ids' => '',
			'slide' => 'true',
			'before_list' => '',
			'after_list' => '',
			'before_item' => '',
			'after_item' => '',
			'show_badge' => '',
			'animation_type' => '',
			'animation_duration' => '',
			'animation_delay' => '',
			'item_width' => '270',
			'item_margin' => '30',
		), $atts) );
		if ( $slide == 'no' || $slide == 'false' ) { $slide = 'false'; }
		if ( $type == 'hot' && empty( $show_badge ) ) $show_badge = true;
		if ( $show_badge == 'no' || $show_badge == 'false' ) { $show_badge = false; }
		$styles = array( 'style1', 'style2', 'style3' );
		$types = array( 'latest', 'featured', 'popular', 'hot', 'selected' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $type, $types ) ) $type = 'latest';
		$post_ids = explode( ',', $post_ids );
		$count = is_numeric( $count )?$count:10;
		$count_per_row = is_numeric( $count_per_row )?$count_per_row:3;
		$tour_type = ( ! empty( $tour_type ) ) ? explode( ',', $tour_type ) : array();
		$item_width = is_numeric( $item_width ) ? $item_width : 270;
		$item_margin = is_numeric( $item_margin ) ? $item_margin : 270;

		$def_before_list = '';
		$def_after_list = '';
		$def_before_item = '';
		$def_after_item = '';
		if ( $slide == 'false' ) {
			$def_before_list = '<div class="tour-packages row add-clearfix image-box listing-' . esc_attr( $style ) . '">';
			$def_after_list = '</div>';
			if ( ( 2 == $count_per_row ) ) {
				$def_before_list = '<div class="tour-packages row add-clearfix image-box listing-' . esc_attr( $style ) . '">';
				$def_before_item = "<div class='col-sm-6'>";
				$def_after_item = "</div>";
			} elseif ( 3 == $count_per_row ) {
				$def_before_list = '<div class="tour-packages row add-clearfix image-box listing-' . esc_attr( $style ) . '">';
				$def_before_item = '<div class="col-sm-6 col-md-4">';
				$def_after_item = "</div>";
			} else {
				$def_before_list = '<div class="tour-packages row add-clearfix image-box listing-' . esc_attr( $style ) . '">';
				$def_before_item = "<div class='col-sm-6 col-sms-6 col-md-3'>";
				$def_after_item = "</div>";
			}
			$def_after_item = '</div>';
		} else {
			$def_before_list = '<div class="block image-carousel style2 flexslider" data-animation="slide" data-item-width="' . esc_attr( $item_width ) . '" data-item-margin="' . esc_attr( $item_margin ) . '">
			<amp-carousel height="358" layout="fixed-height" type="carousel" class="carousel1 tour-packages image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</amp-carousel>
			</div>';
			$def_before_item = '<li>';
			$def_after_item = '</li>';
		}

		if ( empty( $before_list ) ) $before_list = $def_before_list;
		if ( empty( $after_list ) ) $after_list = $def_after_list;
		if ( empty( $before_item ) ) $before_item = $def_before_item;
		if ( empty( $after_item ) ) $after_item = $def_after_item;
		$tours = array();
		if ( $type == 'selected' ) {
			$tours = trav_tour_get_tours_from_id( $post_ids );
		} elseif ( $type == 'hot' ) {
			$tours = trav_tour_get_hot_tours( $count, $country, $state, $city, $tour_type );
		} else {
			$tours = trav_tour_get_special_tours( $type, $count, array(), $country, $state, $city, $tour_type );
		}

		ob_start();
		if ( ! empty( $title ) ) { echo '<h2>' . esc_html( $title ) . '</h2>'; }
		echo ( $before_list );
		$i = 0;
		foreach ( $tours as $tour ) {
			$animation = '';
			if ( ! empty( $animation_type ) ) { $animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( $animation_delay * $i ) . '" '; }
			trav_tour_get_tour_list_sigle( $tour->ID, $style, $before_item, $after_item, $show_badge, $animation );
			$i++;
		}
		echo ( $after_list );

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* **************** Similar Tours Shortcode **************
	* **************************************************************** */
	function shortcode_latest_tours( $atts ) {
		extract( shortcode_atts( array(
			// 'title' => __( 'Similar Accommodations', 'trav' ),
			'count' => 3,
			'class' => '',
			'thumb_width' => 64,
			'thumb_height' => 64,
		), $atts) );

		$tours = trav_tour_get_special_tours( 'latest', $count );
		$result = '';
		$result .= '<div class="image-box style14">';
		foreach ( $tours as $tour ) {
			$tour_id = $tour->ID;
			$min_price = get_post_meta( $tour_id, 'trav_tour_min_price', true );
			$min_price = empty($min_price)?0:$min_price;
			$result .= '<article class="box"><figure>';
			$result .= '<a href="' . esc_url( get_permalink( $tour_id ) ) . '">' . get_the_post_thumbnail( $tour_id, array( $thumb_width, $thumb_height ) ) . '</a>';
			$result .= '</figure>';
			$result .= '<div class="details">';
			$result .= '<h5 class="title"><a href="' . esc_url( get_permalink( $tour_id ) ) . '">' . esc_html( get_the_title( $tour_id ) ) . '</a></h5>';
			$result .= '<label class="price-wrapper"><span class="price-per-unit">' . esc_html( trav_get_price_field( $min_price ) ) . '</span> ' . __( 'per person', 'trav' ) . '</label>';
			$result .= '</div>';
			$result .= '</article>';
		}
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ****************** Post List Shortcode ****************
	* **************************************************************** */
	function shortcode_posts( $atts ) {
		extract( shortcode_atts( array(
			'title' => '',
			'type' => 'latest',
			'style' => 'style1',
			'count' => 10,
			'count_per_row' => 4,
			'post_ids' => '',
			'slide' => 'true',
			'before_list' => '',
			'after_list' => '',
			'before_item' => '',
			'after_item' => '',
			'animation_type' => '',
			'animation_duration' => '',
			'animation_delay' => '',
		), $atts) );

		if ( $slide == 'no' || $slide == 'false' ) { $slide = 'false'; }
		$styles = array( 'style1', 'style2', 'style3', 'style4' );
		$types = array( 'latest', 'popular', 'selected' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $type, $types ) ) $type = 'latest';
		$post_ids = explode( ',', $post_ids );
		$count = is_numeric( $count )?$count:10;
		$count_per_row = is_numeric( $count_per_row )?$count_per_row:4;

		$def_before_list = '';
		$def_after_list = '';
		$def_before_item = '';
		$def_after_item = '';

		if ( $style == 'style3' ) {
			$def_before_list = '<div>';
			$def_after_list = '</div>';
		} else {
			if ( $slide == 'false' ) {
				$def_before_list = '<div class="row image-box style10">';
				$def_after_list = '</div>';
				if ( ( 2 == $count_per_row ) ) {
					$def_before_list = '<div class="row image-box style10">';
					$def_before_item = "<div class='col-sm-6'>";
					$def_after_item = "</div>";
				} elseif ( 3 == $count_per_row ) {
					$def_before_list = '<div class="row image-box style10">';
					$def_before_item = "<div class='col-sm-4'>";
					$def_after_item = "</div>";
				} else {
					$def_before_list = '<div class="row image-box style10">';
					$def_before_item = "<div class='col-sm-6 col-sms-6 col-md-3'>";
					$def_after_item = "</div>";
				}
			} else {
				$def_before_list = '<div class="block image-carousel style2" data-animation="slide" data-item-width="370" data-item-margin="30"><ul class="slides image-box style10 listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</ul></div>';
				$def_before_item = '<li>';
				$def_after_item = '</li>';
			}
		}
		if ( empty( $before_list ) ) $before_list = $def_before_list;
		if ( empty( $after_list ) ) $after_list = $def_after_list;
		if ( empty( $before_item ) ) $before_item = $def_before_item;
		if ( empty( $after_item ) ) $after_item = $def_after_item;

		$posts = array();
		if ( $type == 'selected' ) {
			if ( is_array( $post_ids ) ) {
				foreach( $post_ids as $post_id ) {
					$post = get_post( $post_id );
					if ( ! empty( $post ) && ! is_wp_error( $post ) ) {
						$posts[] = $post;
					}
				}
			}
		} elseif ( $type == 'latest' ) {
			$args = array(
				'posts_per_page' => $count,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'suppress_filters' => 0,
				'post_status' => 'publish',
			);
			$posts = get_posts( $args );
		} elseif ( $type == 'popular' ) {
			$posts = get_posts('posts_per_page=' . $count . '&meta_key=trav_count_post_views&orderby=meta_value_num&order=DESC&suppress_filters=0&post_status=publish');
		}

		ob_start();
		if ( ! empty( $title ) ) { echo '<h2>' . esc_html( $title ) . '</h2>'; }
		echo ( $before_list );
		$i = 0;
		foreach ( $posts as $post ) {
			$animation = '';
			if ( ! empty( $animation_type ) ) { $animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( $animation_delay * $i ) . '" '; }
			trav_get_post_list_sigle( $post->ID, $style, $before_item, $after_item, $animation );
			$i++;
		}
		echo ( $after_list );

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ************************* Blog Shortcode ***********************
	* **************************************************************** */
	function shortcode_blog( $atts, $content = null ) {
		global $ajax_paging, $cat, $trav_options;
		$variables = array( 'ajax_pagination' => '', 'cat' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		if ( ! empty( $ajax_pagination ) ) {
			$ajax_paging = ( $ajax_pagination == 'yes' || $ajax_pagination == 'true' )?1:0;
		} else {
			$ajax_paging = $trav_options['ajax_pagination'];
		}

		ob_start();
		trav_get_template( 'content-blog.php', '/templates' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* *********************** Dashboard Shortcode ********************
	* **************************************************************** */
	function shortcode_dashboard( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'main.php', '/templates/user' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ************************ Slider Shortcode **********************
	* **************************************************************** */
	function shortcode_slider( $atts, $content = null ) {
		$variables = array( 
			'type'		=> 'gallery2', //available values ( gallery1|gallery2|gallery3|gallery4|carousel1|carousel2 )
			'id'		=> '', 
			'class' 	=> '', 
			'ul_class' 	=> '' 
		); 
		extract( shortcode_atts( $variables, $atts ) );

		$id = empty( $id )? '' : ( ' id="' . esc_attr( $id ) . '"' );
		$class = empty( $class )? '' : ( ' ' . $class );
		$ul_class = empty( $ul_class )? '' : ( ' ' . $ul_class );

		$slider_atts = '';
		if ( isset( $atts ) ) {
			foreach ( $atts as $key => $value ) {
				if ( ! array_key_exists( $key, $variables ) ) {
					$key = str_replace( '_', '-', $key );
					$slider_atts .= (' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"');
				}
			}
		}

		$result = '';
		if ( $type == 'gallery1' ) {
			$class = 'flexslider photo-gallery style1' . $class;
			$ul_class = 'slides' . $ul_class;
		} elseif ( $type == 'gallery2' ) {
			$class = 'flexslider photo-gallery style2' . $class;
			$ul_class = 'slides image-box style9' . $ul_class;
			$slider_atts .= ' data-fix-control-nav-pos="1"';
		} elseif ( $type == 'gallery3' ) {
			$class = 'flexslider photo-gallery style3' . $class;
			$ul_class = 'slides' . $ul_class;
		} elseif ( $type == 'gallery4' ) {
			$class = 'flexslider photo-gallery style4' . $class;
			$ul_class = 'slides' . $ul_class;
		} elseif ( $type == 'carousel' ) {
			$class = 'flexslider image-carousel style1' . $class;
			$ul_class = 'slides' . $ul_class;
		} elseif ( $type == 'carousel1' ) {
			$class = 'flexslider image-carousel style2' . $class;
			$ul_class = 'slides' . $ul_class;
		} elseif ( $type == 'carousel2' ) {
			$class = 'image-carousel style3 flex-slider' . $class;
			$ul_class = 'slides image-box style9' . $ul_class;
		/*} elseif ( $type == 'bg' ) {
			$class = 'flexslider' . $class;
			$ul_class = 'slides' . $ul_class;*/
		}

		$result .= '<div' . $id . ' class="' . esc_attr( $class ) . '"' . $slider_atts . '>';
		$result .= '<ul class="' . esc_attr( $ul_class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</ul>';
		$result .= '</div>';

		return $result;
	}

	/* ***************************************************************
	* ************************* Slide Shortcode **********************
	* **************************************************************** */
	function shortcode_slide( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
		$result = '';
		$result .= '<li' . $class . '>';
		$result .= do_shortcode( $content );
		$result .= '</li>';
		return $result;
	}

	/* ***************************************************************
	* ************************* bgslider Shortcode **********************
	* **************************************************************** */
	function shortcode_bgslider( $atts, $content = null ) {
		$variables = array( 'class' => '', 'full_screen' => 'true', 'img_urls' => '', 'id' => '', 'ul_class' => '', );
		extract( shortcode_atts( $variables, $atts ) );
		$id = empty( $id )?'':( ' id="' . esc_attr( $id ) . '"' );
		$class = empty( $class )?'':( ' ' . $class );
		$ul_class = empty( $ul_class )?'':( ' ' . $ul_class );
		$full_screen = ( ( $full_screen == 'yes' ) || ( $full_screen == 'true' ) ) ? 'full-screen' : '';
		$imgs = empty( $img_urls ) ? array() : explode( ',', $img_urls );
		$result = '';
		$result .= '<div' . $id . ' class="slideshow-bg ' . esc_attr( $full_screen ) .' ' . esc_attr( $class ) . '">';
		$result .= '<div class="flexslider"><ul class="slides' .  esc_attr( $ul_class ) . '">';
		foreach ( $imgs as $img ) {
			$result .= '<li>';
			$result .= '<div class="slidebg" style="background-image: url(' . $img . ');"></div>';
			$result .= '</li>';
		}
		$result .= '</ul></div>';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* *********************** Image Box Shortcode ********************
	* **************************************************************** */
	function shortcode_imageframe( $atts, $content = null ) {
		$variables = array( 'src'=>'', 'link'=>'#', 'alt'=>'imageframe-image', 'title'=>'', 'hover' => '', 'class' => '', 'width' => '', 'height' => '', 'label' => '', 'label_content' => '', 'position' => '', 'animation_type'=>'', 'animation_duration'=>'', 'animation_delay'=>'' ); // hover values = (hover-effect|hover-effect yellow|opacity)
		extract( shortcode_atts( $variables, $atts ) );
                if ( empty( $hover ) ) {
			$hover = 'opacity';
		}
		//$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
		if ( empty( $hover ) || ( $hover == 'opacity' ) ) {
			$class .= " " . $hover;
		}
		$a_class = ' class="' . esc_attr( $class ) . '"';
		$label = empty( $label )?'':( '<h3 class="caption-title">' . esc_html( $label ) . '</h3>' );
		$label_content = empty( $label_content )?'':( '<span>' . esc_html( $label_content ) . '</span>' );
		$label_content = $label . $label_content;
		$width = empty( $width )?'':( ' width="' . esc_attr( $width ) . '"' );
		$height = empty( $height )?'':( ' height="' . esc_attr( $height ) . '"' );
		$img_class = '';

		$figure_class = ( $position != 'middle' )?'':( ' class="middle-block"' );
		$alt = empty( $alt )?'':' alt="' . esc_attr( $alt ) . '"';
		$animation = '';
		if ( ! empty( $animation_type ) ) {
			$img_class = ( $position != 'middle' )?' class="animated"':( ' class="middle-item animated"' );
			$animation .= ' data-animation-type="' . $animation_type . '" data-animation-duration="' . $animation_duration . '" data-animation-delay="' . $animation_delay . '" ';
		} else {
			$img_class = ( $position != 'middle' )?'':( ' class="middle-item"' );
		}

		$result = '';
		$result .= '<figure' . $figure_class . '>';
		if ( $link != "no" ) $result .= '<a href="' . esc_url( $link ) . '" title="' . esc_attr( $title ) . '"' . $a_class . '>';
		$result .= '<img src="' . esc_url( $src ) . '"' . $alt . $width . $height . $img_class . $animation . ' />';
		$result .= do_shortcode( $content );
		if ( $hover == 'opacity' ) $result .= '<span class="opacity-wrapper"></span>';
		if ( $link != "no" ) $result .= '</a>';
		if ( ! empty( $label_content ) ) $result .= '<figcaption>' . $label_content . '</figcaption>';
		$result .= '</figure>';
		return $result;
	}

	/* ***************************************************************
	* ****************** Content Box Wrapper Shortcode ***************
	* **************************************************************** */
	function shortcode_content_boxes( $atts, $content = null ) {
		$variables = array( 'class' => '', 'style' => 'style1' ); // hover values = (style1|style2|...|style12)
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':' ' . $class;
		$result = '<div class="image-box ' . esc_attr( $style . $class ) .'">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ********************** Content Box Shortcode *******************
	* **************************************************************** */
	function shortcode_content_box( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':' ' . $class;
		$result = '<article class="box' . esc_attr( $class ) .'">';
		$result .= do_shortcode( $content );
		$result .= '</article>';
		return $result;
	}

	/* ***************************************************************
	* ****************** Content Box Detail Shortcode ****************
	* **************************************************************** */
	function shortcode_content_box_detail( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':' ' . $class;
		$result = '<div class="details' . esc_attr( $class ) .'">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Content Box Detail Row Shortcode **************
	* **************************************************************** */
	function shortcode_content_box_detail_row( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':' ' . $class;
		$result = '<div class="detail' . esc_attr( $class ) .'">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ****************** Content Box Action Shortcode ****************
	* **************************************************************** */
	function shortcode_content_box_action( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':' ' . $class;
		$result = '<div class="action' . esc_attr( $class ) .'">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* *********************** Promo Box Shortcode ********************
	* **************************************************************** */
	function shortcode_promo_box( $atts, $content = null ) {
		$variables = array( 'class' => '', 'img_id' => '', 'img_src' => '', 'img_alt' => 'promobox-image', 'img_height' => '', 'img_width' => '', 'content_section_width' => '', 'img_section_width' => '', 'type' => 'type1', 'animation_type' => '', 'animation_duration' => '', 'animation_delay' => '', 'img_class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$class = 'promo-box' . $class;
		if ( ! empty( $img_id ) ) {
			$img_info = wp_get_attachment_image_src( $img_id, 'full' );
			$img_src = $img_info[0];
			if ( empty( $img_alt ) ) $img_alt = get_post_meta( $img_id,'_wp_attachment_image_alt', true );
		}
		if ( empty( $img_alt ) ) $img_alt = 'author-image';

		$img_width = ( ! empty( $img_width ) && is_numeric( $img_width ) )?(' width="' . esc_attr( $img_width ) . '"'):'';
		$img_height = ( ! empty( $img_height ) && is_numeric( $img_height ) )?(' height="' . esc_attr( $img_height ) . '"'):'';
		if ( ! is_numeric( $content_section_width ) ) $content_section_width = 8;
		if ( ! is_numeric( $img_section_width ) ) $img_section_width = 12 - $content_section_width;

		$result = '';

		$result .= '<div class="' . esc_attr( $class ) . '"><div class="container">';
		$result .= '<div class="content-section description' . ( ( $type == 'type2' )?'':' pull-right' ) . ' col-sm-' . esc_attr( $content_section_width ) . '">';
		$result .= '<div class="table-wrapper hidden-table-sm">';
		$result .= do_shortcode( $content );
		$result .= '</div></div>';

		if ( ! empty( $img_src ) ) {
			$result .= '<div class="image-container' . ( ( $type == 'type2' )?' pull-right':'' ) . ' col-sm-' . esc_attr( $img_section_width . ' ' . $img_class ) . '">';
			$animation = '';
			if ( ! empty( $animation_type ) ) { $animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( $animation_delay ) . '" '; }
			$result .= '<img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $img_alt ) . '"' . $img_width . $img_height . $animation . ' /></div>';
		}

		$result .= '</div></div>';

		return $result;
	}

	/* ***************************************************************
	* ************** Promo Box Left Content Area Shortcode ***********
	* **************************************************************** */
	function shortcode_promo_box_left( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$class = 'table-cell' . $class;
		$result = '<div class="' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ************* Promo Box Right Content Area Shortcode ***********
	* **************************************************************** */
	function shortcode_promo_box_right( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$class = 'table-cell action-section' . $class;
		$result = '<div class="' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ********************** Search Form Shortcode *******************
	* **************************************************************** */
	function shortcode_search_form( $atts, $content = null ) {
		$variables = array( 'class' => '', 'method' => 'get', 'post_type' => 'post', 'style' => 'style1' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
		$method = ( $method == 'get')?'get':'post';
		$def_post_types = array( 'post', 'accommodation', 'tour', 'car', 'cruise' );
		if ( ! in_array( $post_type, $def_post_types ) ) $def_post_types = 'post';
		global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $def_currency, $search_max_passengers;

		ob_start();
		if ( $post_type == 'accommodation' && empty( $content ) ) { ?>
			<?php if ( $style == 'style2' ) : ?>
				<div class="search-box">
					<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>">
						<input type="hidden" name="post_type" value="accommodation">
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
							</div>
							<div class="form-group col-sm-6 col-md-4">
								<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date From', 'trav' ); ?>" />
										</div>
									</div>
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date To', 'trav' ); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<div class="row">
									<div class="col-xs-6">
										<div class="selector">
											<select name="rooms" class="full-width">
												<option value="" disabled selected><?php echo __( 'Rooms', 'trav' ) ?></option>
												<?php
													$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:0;
													for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
														$selected = '';
														if ( $i == $rooms ) $selected = 'selected';
														echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="selector">
											<select name="adults" class="full-width">
												<option value="" disabled selected><?php echo __( 'Adults', 'trav' ) ?></option>
												<?php
													$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:0;
													for ( $i = 1; $i <= $search_max_adults; $i++ ) {
														$selected = '';
														if ( $i == $adults ) $selected = 'selected';
														echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-2">
								<button class="button btn-medium full-width uppercase sky-blue1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
							</div>
						</div>
					</form>
				</div>
			<?php else : ?>
				<div class="search-box-wrapper no-padding <?php echo esc_attr( $style );?>">
					<div class="search-box container">
						<div class="search-tab-content">
							<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'accommodation' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									</div>
									
									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'CHECK IN','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'CHECK OUT','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'Budget', 'trav' ); ?></h4>
										<label><?php _e( 'Your Budget', 'trav' ); ?></label>
										<input type="text" name="max_price" class="input-text full-width" placeholder="<?php _e( 'Enter your budget', 'trav') ?>" />
									</div>
									
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-4">
												<label><?php _e( 'Rooms','trav' ); ?></label>
												<div class="selector">
													<select name="rooms" class="full-width">
														<?php
															$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
															for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
																$selected = '';
																if ( $i == $rooms ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Adults','trav' ); ?></label>
												<div class="selector">
													<select name="adults" class="full-width">
														<?php
															$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
															for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																$selected = '';
																if ( $i == $adults ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Kids','trav' ); ?></label>
												<div class="selector">
													<select name="kids" class="full-width">
														<?php
															$kids = ( isset( $_GET['kids'] ) && is_numeric( (int) $_GET['kids'] ) )?(int) $_GET['kids']:0;
															for ( $i = 0; $i <= $search_max_kids; $i++ ) {
																$selected = '';
																if ( $i == $kids ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="age-of-children no-display">
											<h5><?php _e( 'Age of Children','trav' ); ?></h5>
											<div class="row">
												<div class="col-xs-4 child-age-field">
													<label><?php echo __( 'Child ', 'trav' ) . '1' ?></label>
													<div class="selector validation-field">
														<select name="child_ages[]" class="full-width">
															<?php
																$max_kid_age = 17;
																for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																	echo '<option value="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</option>';
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php } elseif ( $post_type == 'tour' && empty( $content ) ) { ?>
			<?php if ( $style == 'style2' ) : ?>
				<div class="search-box">
					<form role="search" method="get" class="tour-searchform2" action="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">
						<input type="hidden" name="post_type" value="tour">
						<div class="row">
							<div class="form-group col-sm-4 col-md-3">
								<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
							</div>
							<div class="form-group col-sm-8 col-md-4">
								<div class="row">
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Date From', 'trav' ); ?>" />
										</div>
									</div>
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Date To', 'trav' ); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<?php $trip_types = get_terms( 'tour_type' ); ?>
								<div class="row">
									<?php if ( ! empty( $trip_types ) ) : ?>
										<div class="col-xs-6">
											<div class="selector">
												<select name="tour_types" class="full-width">
													<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
													<?php foreach ( $trip_types as $trip_type ) : ?>
														<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									<?php endif; ?>
									<div class="col-xs-6">
										<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-2">
								<button class="button btn-medium full-width uppercase sky-blue1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
							</div>
						</div>
					</form>
				</div>
			<?php else : ?>
				<div class="search-box-wrapper no-padding <?php echo esc_attr( $style );?>">
					<div class="search-box container">
						<div class="search-tab-content">
							<form role="search" method="get" class="tour-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'tour' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Destination ', 'trav' ) ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<label><?php _e( 'From', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3 fixheight">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Trip Type', 'trav' ) ?></label>
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<label><?php _e( 'Budget', 'trav' ) ?></label>
												<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php } elseif ( $post_type == 'car' && empty( $content ) ) { ?>
			<?php if ( $style == 'style2' ) : ?>
				<div class="search-box">
					<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'car' ) ); ?>">
						<input type="hidden" name="post_type" value="car">
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
							</div>
							<div class="form-group col-sm-6 col-md-4">
								<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date From', 'trav' ); ?>" />
										</div>
									</div>
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date To', 'trav' ); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<div class="row">
									<div class="col-xs-6">
										<div class="selector">
											<select name="passengers" class="full-width">
												<?php
													$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
													for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
														$selected = '';
														if ( $i == $passengers ) $selected = 'selected';
														echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . __('Passengers', 'trav') . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="selector">
											<select class="full-width" name="car_types">
                                            	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
                                            	<?php
                                            	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
												foreach ( $all_car_types as $each_car_type ) {
													echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
												}
												?>
                                            </select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-2">
								<button class="button btn-medium full-width uppercase sky-blue1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
							</div>
						</div>
					</form>
				</div>
			<?php else : ?>
				<div class="search-box-wrapper no-padding <?php echo esc_attr( $style );?>">
					<div class="search-box container">
						<div class="search-tab-content">
							<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'car' ) ); ?>">
								<input type="hidden" name="post_type" value="car">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'pick-up from','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
									</div>
									
									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'From','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-5">
												<label><?php _e( 'Passengers','trav' ); ?></label>
												<div class="selector">
													<select name="passengers" class="full-width">
														<?php
															$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
															for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
																$selected = '';
																if ( $i == $passengers ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>	
											<div class="col-xs-7">
	                                            <label><?php _e('Car Type', 'trav'); ?></label>
	                                            <div class="selector">
	                                                <select class="full-width" name="car_types">
	                                                	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
	                                                	<?php
	                                                	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
														foreach ( $all_car_types as $each_car_type ) {
															echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
														}
														?>
	                                                </select>
	                                            </div>
	                                        </div>
	                                    </div>
									</div>
									
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php } elseif ( $post_type == 'cruise' && empty( $content ) ) { ?>
			<?php if ( $style == 'style2' ) : ?>
				<div class="search-box">
					<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'cruise' ) ); ?>">
						<input type="hidden" name="post_type" value="cruise">
						<div class="row">
							<div class="form-group col-sm-6 col-md-3">
								<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
							</div>
							<div class="form-group col-sm-6 col-md-4">
								<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date From', 'trav' ); ?>" />
										</div>
									</div>
									<div class="col-xs-6">
										<div class="datepicker-wrap from-today">
											<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo __( 'Date To', 'trav' ); ?>" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-3">
								<div class="row">
									<?php $cruise_types = get_terms( 'cruise_type' ); ?>
									<?php if ( ! empty( $cruise_types ) ) : ?>
										<div class="col-xs-6">
											<div class="selector">
												<select name="cruise_types" class="full-width">
													<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
													<?php foreach ( $cruise_types as $cruise_type ) : ?>
														<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									<?php endif; ?>

									<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
									<?php if ( ! empty( $cruise_lines ) ) : ?>
										<div class="col-xs-6">
											<div class="selector">
												<select name="cruise_lines" class="full-width">
													<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
													<?php foreach ( $cruise_lines as $cruise_line ) : ?>
														<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="form-group col-sm-6 col-md-2">
								<button class="button btn-medium full-width uppercase sky-blue1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
							</div>
						</div>
					</form>
				</div>
			<?php else : ?>
				<div class="search-box-wrapper no-padding <?php echo esc_attr( $style );?>">
					<div class="search-box container">
						<div class="search-tab-content">
							<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( get_post_type_archive_link( 'cruise' ) ); ?>">
								<input type="hidden" name="post_type" value="cruise">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
									</div>
									
									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'DATE FROM','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'DATE TO','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'What','trav' ); ?></h4>
										<div class="row">
											<?php $cruise_types = get_terms( 'cruise_type' ); ?>
											<?php if ( ! empty( $cruise_types ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Cruise Type','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_types" class="full-width">
															<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
															<?php foreach ( $cruise_types as $cruise_type ) : ?>
																<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>

											<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
											<?php if ( ! empty( $cruise_lines ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Cruise Line','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_lines" class="full-width">
															<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
															<?php foreach ( $cruise_lines as $cruise_line ) : ?>
																<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>

										</div>
									</div>
									
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php } else { ?>
			<form action="<?php echo esc_url( home_url( '/' ) ) ?>" method="<?php echo esc_attr( $method ) ?>"<?php echo $class?>><input type="hidden" value="<?php echo esc_attr( $post_type ) ?>" name="post_type">
				<?php echo do_shortcode( $content ); ?>
			</form>
		<?php }
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ***************** Search Form Textfield Shortcode **************
	* **************************************************************** */
	function shortcode_search_form_textfield( $atts, $content = null ) {
		$variables = array( 'class' => 'input-large full-width', 'placeholder' => __('Enter destination or hotel name', 'trav') );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$class = 'input-text' . $class;
		$result = '<input name="s" type="text" class="' . esc_attr( $class ) . '" value="" placeholder="' . esc_attr( $placeholder ) . '" />';
		return $result;
	}

	/* ***************************************************************
	* ********************** Search Group Shortcode ******************
	* **************************************************************** */
	function shortcode_search_group( $atts, $content = null ) {
		$variables = array( 'class' => '', 'style' => 'style2' );
		extract( shortcode_atts( $variables, $atts ) );
		global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $def_currency, $search_max_passengers;
		$all_features = array( 'acc', 'tour', 'car', 'cruise' );
		$enabled_features = array();
		foreach( $all_features as $feature ) {
			if ( empty( $trav_options['disable_' . $feature ] ) ) $enabled_features[] = $feature;
		}

		ob_start(); ?>

		<div class="search-box-wrapper <?php echo esc_attr( $style . ' ' . $class ) ?>">
			<div class="search-box">
				<?php if ( count( $enabled_features ) > 1 ) : ?>
					<ul class="search-tabs clearfix">
						<?php if ( in_array( 'acc', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><i class="soap-icon-hotel"></i> <span><?php _e( 'HOTELS', 'trav' ) ?></span></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'tour', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><i class="soap-icon-beach"></i> <span><?php _e( 'TOURS', 'trav' ) ?></span></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'car', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'car' ) echo 'class="active"' ?> ><a href="#cars-tab" data-toggle="tab"><i class="soap-icon-car"></i> <span><?php _e( 'CARS', 'trav' ) ?></span></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'cruise', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'cruise' ) echo 'class="active"' ?> ><a href="#cruises-tab" data-toggle="tab"><i class="soap-icon-cruise"></i> <span><?php _e( 'CRUISE', 'trav' ) ?></span></a></li>
						<?php endif; ?>
					</ul>
					<div class="visible-mobile">
					<ul id="mobile-search-tabs" class="search-tabs clearfix">
						<?php if ( in_array( 'acc', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><?php _e( 'HOTELS', 'trav' ) ?></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'tour', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><?php _e( 'TOURS', 'trav' ) ?></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'car', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'car' ) echo 'class="active"' ?> ><a href="#cars-tab" data-toggle="tab"><?php _e( 'CARS', 'trav' ) ?></a></li>
						<?php endif; ?>
						<?php if ( in_array( 'cruise', $enabled_features ) ) : ?>
							<li <?php if ( $enabled_features[0] == 'cruise' ) echo 'class="active"' ?> ><a href="#cruise-tab" data-toggle="tab"><?php _e( 'CRUISES', 'trav' ) ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>
				<?php if ( $style == 'style2' || $style == 'style4' ) { ?>
					<div class="search-tab-content">
						<?php if ( in_array( 'acc', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'acc' ) echo ' active in' ?>" id="hotels-tab">
							<?php endif; ?>
							<h4 class="title"><?php _e( 'Where do you want to go?' ,'trav' ) ?></h4>
							<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Check In', 'trav' ); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Check Out', 'trav' ); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-md-5">
										<div class="row">
											<div class="col-xs-4">
												<div class="selector">
													<select name="rooms" class="full-width">
														<?php
															$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
															for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
																$selected = '';
																if ( $i == $rooms ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . (( $i == 1 ) ? __( 'Room', 'trav' ) : __( 'Rooms', 'trav' )) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<div class="selector">
													<select name="adults" class="full-width">
														<?php
															$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
															for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																$selected = '';
																if ( $i == $adults ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . ( ( $i == 1 ) ? __( 'Guest', 'trav' ) : __( 'Guests', 'trav' ) ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<button type="submit" class="full-width"><?php _e( 'SEARCH NOW', 'trav') ?></button>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'tour', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'tour' ) echo ' active in' ?>" id="tours-tab">
							<?php endif; ?>
							<h4 class="title"><?php _e( 'Where do you want to go?' ,'trav' ) ?></h4>
							<form role="search" method="get" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<div class="row">
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'From', 'trav' ); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'To', 'trav' ) ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2">
										<button type="submit" class="full-width"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'car', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'car' ) echo ' active in' ?>" id="cars-tab">
							<?php endif; ?>
							<h4 class="title"><?php _e( 'Where do you want to go?' ,'trav' ) ?></h4>
							<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="car">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'From', 'trav' ) ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'To', 'trav' ) ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<div class="row">
											<div class="col-xs-5">
												<div class="selector">
													<select name="passengers" class="full-width">
														<?php
															$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
															for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
																$selected = '';
																if ( $i == $passengers ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . __('Passengers', 'trav') .  '</option>';
															}
														?>
													</select>
												</div>
											</div>	
											<div class="col-xs-7">
	                                            <div class="selector">
	                                                <select class="full-width" name="car_types">
	                                                	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
	                                                	<?php
	                                                	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
														foreach ( $all_car_types as $each_car_type ) {
															echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
														}
														?>
	                                                </select>
	                                            </div>
	                                        </div>
	                                    </div>
									</div>

									<div class="form-group col-sm-6 col-md-2">
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>							
						<?php endif; ?>

						<?php if ( in_array( 'cruise', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'cruise' ) echo ' active in' ?>" id="cruises-tab">
							<?php endif; ?>
							<h4 class="title"><?php _e( 'Where do you want to go?' ,'trav' ) ?></h4>
							<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="cruise">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Date From', 'trav' ); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Date to', 'trav' ); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-md-5">
										<div class="row">
											<?php $cruise_types = get_terms( 'cruise_type' ); ?>
											<?php if ( ! empty( $cruise_types ) ) : ?>
												<div class="col-xs-4">
													<div class="selector">
														<select name="cruise_types" class="full-width">
															<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
															<?php foreach ( $cruise_types as $cruise_type ) : ?>
																<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>

											<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
											<?php if ( ! empty( $cruise_lines ) ) : ?>
												<div class="col-xs-4">
													<div class="selector">
														<select name="cruise_lines" class="full-width">
															<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
															<?php foreach ( $cruise_lines as $cruise_line ) : ?>
																<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											
											<div class="col-xs-4">
												<button type="submit" class="full-width"><?php _e( 'SEARCH NOW', 'trav') ?></button>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php } elseif ( $style == 'style5' ) { ?>
					<div class="search-tab-content">
						<?php if ( in_array( 'acc', $enabled_features  ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'acc' ) echo ' active in' ?>" id="hotels-tab">
							<?php endif; ?>
							<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="title-container">
									<h2 class="search-title"><?php _e( 'Search and Book Hotels', 'trav' ) ?></h2>
									<p><?php _e( "We're bringing you a new level of comfort.", 'trav' ) ?></p>
									<i class="soap-icon-hotel"></i>
								</div>
								<div class="search-content">
									<h5 class="title"><?php _e( 'Where','trav' ); ?></h5>
									<label><?php _e( 'Your Destination','trav' ); ?></label>
									<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									<hr>
									<h5 class="title"><?php _e( 'When','trav' ); ?></h5>
									<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
										<div class="col-xs-6">
											<div class="datepicker-wrap from-today">
												<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Check In', 'trav' ); ?>" />
											</div>
										</div>
										<div class="col-xs-6">
											<div class="datepicker-wrap from-today">
												<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Check Out', 'trav' ); ?>" />
											</div>
										</div>
									</div>
									<hr>
									<h5 class="title"><?php _e( 'Who','trav' ); ?></h5>
									<div class="row">
										<div class="col-xs-4">
											<label><?php _e( 'Rooms','trav' ); ?></label>
											<div class="selector">
												<select name="rooms" class="full-width">
													<?php
														$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
														for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
															$selected = '';
															if ( $i == $rooms ) $selected = 'selected';
															echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . (( $i == 1 ) ? __( 'Room', 'trav' ) : __( 'Rooms', 'trav' )) . '</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-xs-4">
											<label><?php _e( 'Adults','trav' ); ?></label>
											<div class="selector">
												<select name="adults" class="full-width">
													<?php
														$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
														for ( $i = 1; $i <= $search_max_adults; $i++ ) {
															$selected = '';
															if ( $i == $adults ) $selected = 'selected';
															echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . ' ' . ( ( $i == 1 ) ? __( 'Guest', 'trav' ) : __( 'Guests', 'trav' ) ) . '</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-xs-4">
											<label><?php _e( 'Kids','trav' ); ?></label>
											<div class="selector">
												<select name="kids" class="full-width">
													<?php
														$kids = ( isset( $_GET['kids'] ) && is_numeric( (int) $_GET['kids'] ) )?(int) $_GET['kids']:0;
														for ( $i = 0; $i <= $search_max_kids; $i++ ) {
															$selected = '';
															if ( $i == $kids ) $selected = 'selected';
															echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="age-of-children <?php if ( $kids == 0) echo 'no-display'?>">
										<h5><?php _e( 'Age of Children','trav' ); ?></h5>
										<div class="row">
											<?php $kid_nums = ( $kids > 0 )?$kids:1;
											for ( $kid_num = 1; $kid_num <= $kid_nums; $kid_num++ ) { ?>

												<div class="col-xs-4 child-age-field">
													<label><?php echo esc_html( __( 'Child ', 'trav' ) . $kid_num ) ?></label>
													<div class="selector validation-field">
														<select name="child_ages[]" class="full-width">
															<?php
																$max_kid_age = 17;
																$child_ages = ( isset( $_GET['child_ages'][ $kid_num -1 ] ) && is_numeric( (int) $_GET['child_ages'][ $kid_num -1 ] ) )?(int) $_GET['child_ages'][ $kid_num -1 ]:0;
																for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																	$selected = '';
																	if ( $i == $child_ages ) $selected = 'selected';
																	echo '<option value="' . esc_attr( $i ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $i ) . '</option>';
																}
															?>
														</select>
													</div>
												</div>

											<?php } ?>
										</div>
									</div>
									<hr>
									<button type="submit" class="full-width uppercase"><?php _e( 'Proceed To Result','trav' ); ?></button>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( in_array( 'tour', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'tour' ) echo ' active in' ?>" id="tours-tab">
							<?php endif; ?>
							<form role="search" method="get" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="title-container">
									<h2 class="search-title"><?php _e( 'Search and Book Tours', 'trav' ) ?></h2>
									<p><?php _e( "We're bringing you a new level of comfort.", 'trav' ) ?></p>
									<i class="soap-icon-beach"></i>
								</div>
								<div class="search-content">
									<h5 class="title"><?php _e( 'Where','trav' ); ?></h5>
									<label><?php _e( 'Destination','trav' ); ?></label>
									<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									<hr>
									<h5 class="title"><?php _e( 'When','trav' ); ?></h5>
									<div class="row">
										<div class="col-xs-6">
											<label><?php _e( 'From','trav' ); ?></label>
											<div class="datepicker-wrap from-today">
												<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'From', 'trav' ); ?>" />
											</div>
										</div>
										<div class="col-xs-6">
											<label><?php _e( 'To','trav' ); ?></label>
											<div class="datepicker-wrap from-today">
												<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'To', 'trav' ) ?>" />
											</div>
										</div>
									</div>
									<hr>
									<!-- <h5 class="title"><?php _e( 'Trip Type','trav' ); ?></h5> -->
									<div class="row">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<?php if ( ! empty( $trip_types ) ) : ?>
										<div class="col-xs-6">
											<label><?php _e( 'Trip Type', 'trav' ) ?></label>
											<div class="selector">
												<select name="tour_types" class="full-width">
													<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
													<?php foreach ( $trip_types as $trip_type ) : ?>
														<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<?php endif; ?>
										<div class="col-xs-6">
											<label><?php _e( 'Budget', 'trav' ) ?></label>
											<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
										</div>
									</div>
									<hr>
									<button type="submit" class="full-width uppercase"><?php _e( 'Proceed To Result','trav' ); ?></button>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( in_array( 'car', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'car' ) echo ' active in' ?>" id="cars-tab">
							<?php endif; ?>
							<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="car">
								<div class="title-container">
									<h2 class="search-title"><?php _e( 'Search and Book Cars', 'trav' ) ?></h2>
									<p><?php _e( "We're bringing you a new level of comfort.", 'trav' ) ?></p>
									<i class="soap-icon-car"></i>
								</div>
								<div class="search-content">
									<h5 class="title"><?php _e( 'Where','trav' ); ?></h5>
									<label><?php _e( 'pick-up from','trav' ); ?></label>
									<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
									<hr>
									<h5 class="title"><?php _e( 'When','trav' ); ?></h5>
									<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
										<div class="col-xs-6">
											<label><?php _e( 'From','trav' ); ?></label>
											<div class="datepicker-wrap from-today">
												<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Pick-Up', 'trav' ); ?>" />
											</div>
										</div>
										<div class="col-xs-6">
											<label><?php _e( 'To','trav' ); ?></label>
											<div class="datepicker-wrap from-today">
												<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Drop-Off', 'trav' ); ?>" />
											</div>
										</div>
									</div>
									<hr>
									<h5 class="title"><?php _e( 'Who','trav' ); ?></h5>
									<div class="row">
										<div class="col-xs-6">
											<label><?php _e( 'Passengers','trav' ); ?></label>
											<div class="selector">
												<select name="passengers" class="full-width">
													<?php
														$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
														for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
															$selected = '';
															if ( $i == $passengers ) $selected = 'selected';
															echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="col-xs-6">
											<label><?php _e( 'Car Types','trav' ); ?></label>
											<div class="selector">
												<select class="full-width" name="car_types">
                                                	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
                                                	<?php
                                                	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
													foreach ( $all_car_types as $each_car_type ) {
														echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
													}
													?>
                                                </select>
											</div>
										</div>										
									</div>									
									<hr>
									<button type="submit" class="full-width uppercase"><?php _e( 'Proceed To Result','trav' ); ?></button>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ( in_array( 'cruise', $enabled_features  ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'cruise' ) echo ' active in' ?>" id="cruises-tab">
							<?php endif; ?>
							<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="cruise">
								<div class="title-container">
									<h2 class="search-title"><?php _e( 'Search and Book Cruises', 'trav' ) ?></h2>
									<p><?php _e( "We're bringing you a new level of comfort.", 'trav' ) ?></p>
									<i class="soap-icon-cruise"></i>
								</div>
								<div class="search-content">
									<h5 class="title"><?php _e( 'Where','trav' ); ?></h5>
									<label><?php _e( 'Your Destination','trav' ); ?></label>
									<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
									<hr>
									<h5 class="title"><?php _e( 'When','trav' ); ?></h5>
									<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
										<div class="col-xs-6">
											<div class="datepicker-wrap from-today">
												<input name="date_from" type="text" class="input-text full-width" placeholder="<?php _e( 'Check In', 'trav' ); ?>" />
											</div>
										</div>
										<div class="col-xs-6">
											<div class="datepicker-wrap from-today">
												<input name="date_to" type="text" class="input-text full-width" placeholder="<?php _e( 'Check Out', 'trav' ); ?>" />
											</div>
										</div>
									</div>
									<hr>
									<h5 class="title"><?php _e( 'What','trav' ); ?></h5>
									<div class="row">
										<div class="col-xs-6">
											<?php $cruise_types = get_terms( 'cruise_type' ); ?>
											<?php if ( ! empty( $cruise_types ) ) : ?>
												<label><?php _e( 'Cruise Types','trav' ); ?></label>
												<div class="selector">
													<select name="cruise_types" class="full-width">
														<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
														<?php foreach ( $cruise_types as $cruise_type ) : ?>
															<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											<?php endif; ?>
										</div>
										
										<div class="col-xs-6">
											<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
											<?php if ( ! empty( $cruise_lines ) ) : ?>
												<label><?php _e( 'Cruise Lines','trav' ); ?></label>
												<div class="selector">
													<select name="cruise_lines" class="full-width">
														<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
														<?php foreach ( $cruise_lines as $cruise_line ) : ?>
															<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											<?php endif; ?>
										</div>

									</div>
									<hr>
									<button type="submit" class="full-width uppercase"><?php _e( 'Proceed To Result','trav' ); ?></button>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php } else { ?>
					<div class="search-tab-content">
						<?php if ( in_array( 'acc', $enabled_features  ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'acc' ) echo ' active in' ?>" id="hotels-tab">
							<?php endif; ?>
							<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'CHECK IN','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'CHECK OUT','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-4">
												<label><?php _e( 'Rooms','trav' ); ?></label>
												<div class="selector">
													<select name="rooms" class="full-width">
														<?php
															$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
															for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
																$selected = '';
																if ( $i == $rooms ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Adults','trav' ); ?></label>
												<div class="selector">
													<select name="adults" class="full-width">
														<?php
															$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
															for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																$selected = '';
																if ( $i == $adults ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Kids','trav' ); ?></label>
												<div class="selector">
													<select name="kids" class="full-width">
														<?php
															$kids = ( isset( $_GET['kids'] ) && is_numeric( (int) $_GET['kids'] ) )?(int) $_GET['kids']:0;
															for ( $i = 0; $i <= $search_max_kids; $i++ ) {
																$selected = '';
																if ( $i == $kids ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="age-of-children no-display">
											<h5><?php _e( 'Age of Children','trav' ); ?></h5>
											<div class="row">
												<div class="col-xs-4 child-age-field">
													<label><?php echo __( 'Child ', 'trav' ) . '1' ?></label>
													<div class="selector validation-field">
														<select name="child_ages[]" class="full-width">
															<?php
																$max_kid_age = 17;
																for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																	echo '<option value="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</option>';
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'tour', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'tour' ) echo ' active in' ?>" id="tours-tab">
							<?php endif; ?>
							<form role="search" method="get" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Destination ', 'trav' ) ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<label><?php _e( 'From', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3 fixheight">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Trip Type', 'trav' ) ?></label>
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<label><?php _e( 'Budget', 'trav' ) ?></label>
												<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'car', $enabled_features ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'car' ) echo ' active in' ?>" id="cars-tab">
							<?php endif; ?>
							<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="car">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Pick-Up from','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'From','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<label><?php _e( 'Passengers','trav' ); ?></label>
												<div class="selector">
													<select name="passengers" class="full-width">
														<?php
															$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
															for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
																$selected = '';
																if ( $i == $passengers ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e('Car Type', 'trav'); ?></label>
	                                            <div class="selector">
	                                                <select class="full-width" name="car_types">
	                                                	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
	                                                	<?php
	                                                	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
														foreach ( $all_car_types as $each_car_type ) {
															echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
														}
														?>
	                                                </select>
	                                            </div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'cruise', $enabled_features  ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'cruise' ) echo ' active in' ?>" id="cruises-tab">
							<?php endif; ?>
							<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="cruise">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'DATE FROM','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'DATE TO','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'What','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<?php $cruise_types = get_terms( 'cruise_type' ); ?>
												<?php if ( ! empty( $cruise_types ) ) : ?>
													<label><?php _e( 'Cruise Types','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_types" class="full-width">
															<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
															<?php foreach ( $cruise_types as $cruise_type ) : ?>
																<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												<?php endif; ?>
											</div>
											
											<div class="col-xs-6">
												<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
												<?php if ( ! empty( $cruise_lines ) ) : ?>
													<label><?php _e( 'Cruise Lines','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_lines" class="full-width">
															<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
															<?php foreach ( $cruise_lines as $cruise_line ) : ?>
																<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												<?php endif; ?>
											</div>

										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

					</div>
				<?php } ?>
			</div>
		</div>

		<?php $output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* *********************** Animation Shortcode ********************
	* **************************************************************** */
	function shortcode_animation( $atts, $content = null ) {
		$variables = array( 
			'type' => 'fadeInUp', 
			'duration' => '2', 
			'delay' => '0', 
			'class' => '' 
		);
		extract( shortcode_atts( $variables, $atts ) );

		$class = empty( $class )? '' : ( ' ' . $class );

		$result = '';
		$result .= '<div class="animated' . esc_attr( $class ) . '" data-animation-type="' . esc_attr( $type ) . '" data-animation-duration="' . esc_attr( $duration ) . '" data-animation-delay="' . esc_attr( $delay ) . '" >';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		
		return $result;
	}

	/* ***************************************************************
	* *********************** Five Stars Shortcode *******************
	* **************************************************************** */
	function shortcode_rating( $atts, $content = null ) {
		$variables = array( 'value' => '0', 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$value = is_numeric( $value )?$value:0;
		$result = '<div class="five-stars-container' . esc_attr( $class ) . '" title="' . esc_attr( $value . ' ' . __( 'stars', 'trav') ) . '" data-toggle="tooltip" data-placement="bottom">';
		$result .= '<span class="five-stars" style="width: ' . esc_attr( $value * 20 ) . '%;"></span></div>';
		return $result;
	}

	/* ***************************************************************
	* ************************ Person Shortcode **********************
	* **************************************************************** */
	function shortcode_person( $atts, $content = null ) {
		$variables = array( 'style' => 'style1', 'img_src' => '', 'img_alt' => 'person-photo', 'img_width' => '', 'img_height' => '', 'link' => '#', 'class' => '', 'twitter' => '', 'googleplus' => '', 'facebook' => '', 'linkedin' => '', 'vimeo' => '', 'flickr' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$social_atts = array( 'twitter' => '', 'googleplus' => '', 'facebook' => '', 'linkedin' => '', 'vimeo' => '', 'flickr' => '' );
		$class = empty( $class )?'':( ' ' . $class );
		$social_links = '<ul class="social-icons clearfix' . $class . '">';
		//available keys ( twitter, googleplus, facebook, linkedin, vimeo, dribble, flickr )
		foreach ( $atts as $key => $value ) {
			// if ( ! array_key_exists( $key, $variables ) ) {
			if ( array_key_exists( $key, $social_atts ) && ! empty( $value ) ) {
				$social_links .= '<li class="'.esc_attr( $key ).'">';
				$social_links .= '<a title="' . esc_attr( $key ) . '" href="' . esc_url( $value ) . '" data-toggle="tooltip" target="_blank"><i class="soap-icon-' . esc_attr( $key ) . '"></i></a></li>';
			}
		}
		$social_links .= '</ul>';
		$image = '';
		$image = '<a href="' . esc_url( $link ) . '"><img src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $img_alt ) . '" width="' . esc_attr( $img_width ) . '" height="' . esc_attr( $img_height ) . '" /></a>';

		$result = '<article class="image-box box team">';

		if ( $style == "style1" ) {
			$result .= '<figure>' . $image;
			$result .= '<figcaption>';
			$result .= $social_links;
			$result .='</figcaption>';
			$result .='</figure>';
			$result .= '<div class="details">';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		} else {
			$result .= '<figure>' . $image . '</figure>';
			$result .= '<div class="details">';
			$result .= do_shortcode( $content );
			$result .= $social_links;
			$result .= '</div>';
		}

		$result .= '</article>';
		return $result;
	}

	/* ***************************************************************
	* ************************ Person Shortcode **********************
	* **************************************************************** */
	function shortcode_team_member( $atts, $content = null ) {
		$variables = array( 'style' => 'style1', 'name' => '', 'job' => '', 'description' => '', 'photo_id' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$image = wp_get_attachment_image( $photo_id, 'full' );

		$result = '<article class="image-box box team">';

		if ( $style == "style1" ) {
			$result .= '<figure>' . $image;
			$result .= '<figcaption>';
			$result .= do_shortcode( $content );
			$result .='</figcaption>';
			$result .='</figure>';
			$result .= '<div class="details">';
			$result .= '<h4 class="box-title">' . $name . '<small>' . $job . '</small></a></h4>';
			$result .= '<p class="description">' . do_shortcode( $description ) . '</p>';
			$result .= '</div>';
		} else {
			$result .= '<figure>' . $image . '</figure>';
			$result .= '<div class="details">';
			$result .= '<h4 class="box-title">' . $name . '<small>' . $job . '</small></a></h4>';
			$result .= '<p class="description">' . do_shortcode( $description ) . '</p>';
			$result .= do_shortcode( $content );
			$result .= '</div>';
		}

		$result .= '</article>';
		return $result;
	}

	/* ***************************************************************
	* ************************* Map Shortcode ************************
	* **************************************************************** */
	function shortcode_map( $atts, $content = null ) {
		$variables = array( 
			'class'=>'',
			'center' => '',
			'zoom' => '12',
			'type' => 'ROADMAP',
			'type_control'=>'true',
			'nav_control'=>'true',
			'scrollwheel' => 'true',
			'street_view_control' => 'true',
			'draggable' => 'true',
			'width' => '100%',
			'height' => '300px',
			'markers' => '', // marker = "48.9,2.35-paris:23.2,12-london"
			'acc_ids' => ''
		);
		extract( shortcode_atts( $variables, $atts ) );
		if ( empty( $acc_ids ) ) {
		if ( ! empty( $zoom ) && is_numeric( $zoom ) ) { $zoom = 'zoom: ' . esc_attr( $zoom ) . ','; } else { $zoom = ''; }
		if ( ( $type_control == 'yes' ) || ( $type_control == 'true' ) ) { $type_control = 'mapTypeControl: true,'; } else { $type_control = 'mapTypeControl: false,'; }
		if ( ( $nav_control == 'yes' ) || ( $nav_control == 'true' ) ) { $nav_control = 'navigationControl: true,'; } else { $nav_control = 'navigationControl: false,'; }
		if ( ( $street_view_control == 'yes' ) || ( $street_view_control == 'true' ) ) { $street_view_control = 'streetViewControl: true,'; } else { $street_view_control = 'streetViewControl: false,'; }
		if ( ( $scrollwheel == 'yes' ) || ( $scrollwheel == 'true' ) ) { $scrollwheel = 'scrollwheel: true,'; } else { $scrollwheel = 'scrollwheel: false,'; }
		if ( ( $draggable == 'yes' ) || ( $draggable == 'true' ) ) { $draggable = 'draggable: true,'; } else { $draggable = 'draggable: false,'; }

		$map_types = array( 'ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN' );
		$type = strtoupper( $type );
		if ( empty( $type) || ! in_array( $type, $map_types ) ) $type = 'ROADMAP';
		$type = 'mapTypeId: google.maps.MapTypeId.' . $type . ',';


		static $map_id = 1;
		$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
		$center_str = '';
		if ( ! empty( $center ) ) $center_str = 'center: [' . esc_attr( $center ) . '],';

		$marker_str = '';
		if ( ! empty( $markers ) ) {
			$marker_str = 'marker:{values: [';
			$markers =  explode( ':', $markers );
			$marker_values  =array();
			foreach ( $markers as $marker ) {
				$marker = explode( '--', $marker );
				$marker_val_str = '{latLng:[' . esc_js( $marker[0] ) . ']';
				if ( isset( $marker[1] ) ) $marker_val_str .= ', data:"' . esc_js( $marker[1] ) . '"';
				$marker_val_str .= '}';
				$marker_values[] = $marker_val_str;
			}
			$marker_str .= implode( ',', $marker_values );
			$marker_str .= '], options: {draggable: false }, }';
		}

		$result = '';
		$result .= '<div id="map' . esc_attr( $map_id ) . '"' . $class . ' style="width:' . esc_attr( $width ) . ';height:' . esc_attr( $height ) . '"></div>';
				
		$result .=
		'<script type="text/javascript">
			jQuery(document).ready( function() {
				jQuery("#map' . $map_id . '").gmap3({
					map: {options: {' . $center_str . $zoom . $type . $type_control . $nav_control . $scrollwheel . $street_view_control . $draggable . '}},'
					. $marker_str
				. '});
			});
		</script>';
		} else {
			if ( empty( $zoom ) || ! is_numeric( $zoom ) ) { $zoom = 12; }
			if ( ( $type_control == 'yes' ) || ( $type_control == 'true' ) ) { $type_control = 'true'; } else { $type_control = 'false'; }
			
			static $map_id = 1;
			$class = empty( $class )?'':( ' class="' . esc_attr( $class ) . '"' );
			$acc_ids = explode( ',', $acc_ids );

			ob_start();
		?>
			<div id="map<?php echo esc_attr( $map_id );?>" <?php echo $class; ?> style="width: <?php echo esc_attr( $width ); ?>; height: <?php echo esc_attr( $height ); ?>"></div>
			<script type="text/javascript">
			    jQuery(document).ready(function(){
			        var zoom = <?php echo $zoom ?>;
		            var markersData = {
		                <?php foreach ( $acc_ids as $acc_id ) { 
		                    $acc_pos = get_post_meta( $acc_id, 'trav_accommodation_loc', true );
		                    if ( ! empty( $trav_options['map_marker_img'] ) && ! empty( $trav_options['map_marker_img']['url'] ) ) {
	                        	$marker_img_url = $trav_options['map_marker_img']['url'];
	                        } else {
	                        	$marker_img_url = TRAV_TEMPLATE_DIRECTORY_URI . "/images/pins/Accommodation.png";
	                        }

		                    if ( ! empty( $acc_pos ) ) { 
		                        $acc_pos = explode( ',', $acc_pos );
		                        $brief = get_post_meta( $acc_id, 'trav_accommodation_brief', true );
		                        if ( empty( $brief ) ) {
		                            $brief = apply_filters('the_content', get_post_field('post_content', $acc_id));
		                            $brief = wp_trim_words( $brief, 20, '' );
		                        }
		                        
		                     ?>
		                        '<?php echo $acc_id; ?>' :  [{
		                            name: '<?php echo get_the_title( $acc_id ); ?>',
		                            type: 'Accommodation',
		                            location_latitude: <?php echo $acc_pos[0]; ?>,
		                            location_longitude: <?php echo $acc_pos[1]; ?>,
		                            map_image: '<?php echo get_the_post_thumbnail( $acc_id, 'gallery-thumb' ); ?>',
		                            name_point: '<?php echo get_the_title( $acc_id ); ?>',
		                            description_point: '<?php echo wp_kses_post( $brief ); ?>',
		                            url_point: '<?php echo esc_url( add_query_arg( $query_args, get_permalink( $acc_id ) ) ); ?>'
		                        }],
		                    <?php
		                    }
		                } ?>
		            };
		            <?php 
		            $acc_pos = array();
		            if ( ! empty( $acc_ids ) ) { 
		                foreach ( $acc_ids as $acc_id ) {
		                    $acc_pos = get_post_meta( $acc_id, 'trav_accommodation_loc', true );

		                    if ( ! empty( $acc_pos ) ) { 
		                        $acc_pos = explode( ',', $acc_pos );
		                        break;
		                    }
		                }
		            }
		            
		            if ( ! empty( $acc_pos ) ) {
		            ?>
		            var lati = <?php echo $acc_pos[0] ?>;
		            var long = <?php echo $acc_pos[1] ?>;
		            var _center = [lati, long];
		            renderMap( _center, markersData, zoom, google.maps.MapTypeId.ROADMAP, false, '<?php echo $marker_img_url; ?>',"map<?php echo esc_attr( $map_id );?>" );
		            <?php } ?>
		        
			    });
			</script>
		<?php			
			$result = ob_get_contents();
			ob_end_clean();
		}
		
		$map_id++;
		return $result;
	}

	/* ***************************************************************
	* ******************** Pricing Table Shortcode *******************
	* **************************************************************** */
	function shortcode_pricing_table_vc( $atts, $content = null ) {
		$variables = array( 'class' => '',
						'color' => 'white',
						'price' => '',
						'unit_text' => '',
						'title' => '',
						'sub_title' => '',
						'icon_class' => '',
						'description' => '',
						'btn_title' => '',
						'btn_url' => '',
						'btn_target' => '',
						'btn_color' => '',
						'btn_class' => '',
					);
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<div class="pricing-table box ' . esc_attr( $color . $class ) . '">';
		$result .= '<div class="header clearfix"><i class="' . esc_attr( $icon_class ) . '"></i><h4 class="box-title"><span>' . esc_html( $title ) . '<small>' . esc_html( $sub_title ) . '</small></span></h4><span class="price"><small>' . esc_html( $unit_text ) . '</small>' . esc_html( $price ) . '</span></div>';
		$result .= '<p class="description">' . esc_html( $description ) . '</p>';
		$result .= do_shortcode( $content );
		$result .= '<a href="' . esc_url( $btn_url ) . '" class="button btn-small full-width ' . esc_attr( $btn_color . $btn_class ) . '" target="' . esc_html( $btn_target ) . '">' . esc_html( $btn_title ) . '</a>';
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* ******************** Pricing Table Shortcode *******************
	* **************************************************************** */
	function shortcode_pricing_table( $atts, $content = null ) {
		$variables = array( 'class' => '', 'color' => 'white' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<div class="pricing-table box ' . esc_attr( $color . $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Pricing Table Header Shortcode ****************
	* **************************************************************** */
	function shortcode_pricing_table_head( $atts, $content = null ) {
		$variables = array( 'class' => '', 'icon' => '', 'icon_class' => '', 'title' => 'Pricing Title', 'price' => '0' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$icon_class = empty( $icon_class )?'':( ' ' . $icon_class );
		$result = '';
		$result .= '<div class="header clearfix' . esc_attr( $class ) . '">';
		if ( ! empty( $icon ) ) $result .= '<i class="' . esc_attr( $icon . $icon_class ) . '"></i>';
		$result .= '<h4 class="box-title"><span>' . $title . '</span></h4>';
		$result .= '<span class="price">' . $price . '</span>';
		$result .= '</div>';
		return $result;
	}

	/* ***************************************************************
	* **************** Pricing Table Content Shortcode ***************
	* **************************************************************** */
	function shortcode_pricing_table_content( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<p class="description' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</p>';
		return $result;
	}

	/* ***************************************************************
	* *************** Pricing Table features Shortcode ***************
	* **************************************************************** */
	function shortcode_pricing_table_features( $atts, $content = null ) {
		$variables = array( 'class' => '' );
		extract( shortcode_atts( $variables, $atts ) );
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		$result .= '<ul class="check-square features' . esc_attr( $class ) . '">';
		$result .= do_shortcode( $content );
		$result .= '</ul>';
		return $result;
	}

	/* ***************************************************************
	* *********************** Images Shortcode ***********************
	* **************************************************************** */
	function shortcode_images( $atts, $content = null ) {
		$variables = array( 'class' => '', 'style' => 'style1', 'column' => 3, 'position' => 'left' );
		extract( shortcode_atts( $variables, $atts ) );
		$styles = array( 'style1', 'style2' );
		$columns = array( 2, 3, 4, 5 );
		$positions = array( 'left', 'right' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $column, $columns ) ) $column = 3;
		if ( ! in_array( $position, $positions ) ) $position = 'left';
		$class = empty( $class )?'':( ' ' . $class );
		$result = '';
		if ( $style == 'style1' ) {
			$result .= '<ul class="image-block style1 column-' . esc_attr( $column ) . ' pull-' . esc_attr( $position ) . ' clearfix' . esc_attr( $class ) . '">';
			$result .= do_shortcode( $content );
			$result .= '</ul>';
		} else {
			$result .= '<ul class="image-block style2 clearfix' . esc_attr( $class ) . '">';
			$result .= do_shortcode( $content );
			$result .= '</ul>';
		}

		return $result;
	}

	/* ***************************************************************
	* ************************ Location List *************************
	* **************************************************************** */
	function shortcode_locations( $atts, $content = null ) {
		$variables = array( 'parent' => '', 'column' => 5, 'image_size' => 'thumbnail' ); // image_size = array( 'thumbnail', 'medium', 'large', 'full' )
		extract( shortcode_atts( $variables, $atts ) );

		if ( empty( $parent ) ) $parent = 0;
		if ( ! is_numeric( $column ) ) $column = 5;

		$child_terms = array();
		if ( is_numeric( $parent ) ) {
			$child_terms = get_terms( 'location', array( 'parent' => $parent, 'hide_empty' => 0 ) );
		} else {
			$parent_obj = get_term_by( 'name', $parent, 'location' );
			
			if ( isset( $parent_obj ) ) {
				$child_terms = get_terms( 'location', array( 'parent' => $parent_obj->term_id, 'hide_empty' => 0 ) );
			}
		}

		$result = '';
		$result .= '<div class="image-box style9 column-' . esc_attr( $column ) . '">';
		if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ){
			foreach ( $child_terms as $child_term ) {
				$img_src = get_tax_meta( $child_term->term_id, 'lc_image' );
				$img = '';
				if ( is_array( $img_src ) && isset( $img_src['id'] ) ) {
					$img = wp_get_attachment_image( $img_src['id'], $image_size );
				} else {
					$img = '<img src="' . TRAV_IMAGE_URL . '/blank.jpg" alt="blank-img">';
				}

				$count = trav_count_posts_in_taxonomy( $child_term->term_id, 'location' );
				
				$result .= '<article class="box">';
				$result .= '<figure><a href="' . esc_url( get_term_link( $child_term, 'location' ) ) . '" title="' . esc_attr( $child_term->name ) . '" class="hover-effect yellow">' . $img . '</a></figure>';
				$result .= '<div class="details"><h4 class="box-title">' . $child_term->name . '<small>' . sprintf( __( '%d Activities', 'trav'), $count ) . '</small></h4>';
				$result .= '<a href="' . esc_url( get_term_link( $child_term, 'location' ) ) . '" title="" class="button">' . __( 'SEE ALL', 'trav' ) . '</a></div></article>';
			}
		}
		$result .='</div>';

		return $result;
	}

	/* ***************************************************************
	* **************** Car Booking Page Shortcode **********
	* **************************************************************** */
	function shortcode_car_booking( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'car-booking.php', '/templates/car' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ******* Car Booking Confirmation Page Shortcode ******
	* **************************************************************** */
	function shortcode_car_booking_confirmation( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'car-booking-confirmation.php', '/templates/car' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ****************** Car List Shortcode ****************
	* **************************************************************** */
	function shortcode_cars( $atts ) {
		extract( shortcode_atts( array(
			'title' => '',
			'type' => 'latest',
			'style' => 'style1',
			'count' => 10,
			'count_per_row' => 4,
			'car_type' => '',
			'car_agent' => '',
			'post_ids' => '',
			'slide' => 'true',
			'before_list' => '',
			'after_list' => '',
			'before_item' => '',
			'after_item' => '',
			'show_badge' => '',
			'animation_type' => '',
			'animation_duration' => '',
			'animation_delay' => '',
			'item_width' => '270',
			'item_margin' => '30',
		), $atts) );
		if ( $slide == 'no' || $slide == 'false' ) { $slide = 'false'; }
		if ( $type == 'hot' && empty( $show_badge ) ) $show_badge = true;
		if ( $show_badge == 'no' || $show_badge == 'false' ) { $show_badge = false; }
		$styles = array( 'style1', 'style2', 'style3' );
		$types = array( 'latest', 'featured', 'popular', 'hot', 'selected' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $type, $types ) ) $type = 'latest';
		$post_ids = explode( ',', $post_ids );
		$car_type = ( ! empty( $car_type ) ) ? explode( ',', $car_type ) : array();
		$car_agent = ( ! empty( $car_agent ) ) ? explode( ',', $car_agent ) : array();
		$count = is_numeric( $count )?$count:10;
		$count_per_row = is_numeric( $count_per_row )?$count_per_row:4;
		$item_width = is_numeric( $item_width ) ? $item_width : 270;
		$item_margin = is_numeric( $item_margin ) ? $item_margin : 270;

		$def_before_list = '';
		$def_after_list = '';
		$def_before_item = '';
		$def_after_item = '';

		if ( $style == 'style3' ) {
			$def_before_list = '<div class="listing-style3 car image-box">';
			$def_after_list = '</div>';
		} else {
			if ( $slide == 'false' ) {
				$def_before_list = '<div class="row car image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</div>';
				if ( ( 2 == $count_per_row ) ) {
					$def_before_list = '<div class="row car image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6'>";
					$def_after_item = "</div>";
				} elseif ( 3 == $count_per_row ) {
					$def_before_list = '<div class="row car image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-4'>";
					$def_after_item = "</div>";
				} else {
					$def_before_list = '<div class="row car image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6 col-sms-6 col-md-3'>";
					$def_after_item = "</div>";
				}
			} else {
				$def_before_list = '<div class="block image-carousel style2 flexslider" data-animation="slide" data-item-width="' . esc_attr( $item_width ) . '" data-item-margin="' . esc_attr( $item_margin ) . '"><ul class="slides car image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</ul></div>';
				$def_before_item = '<li>';
				$def_after_item = '</li>';
			}
		}
		if ( empty( $before_list ) ) $before_list = $def_before_list;
		if ( empty( $after_list ) ) $after_list = $def_after_list;
		if ( empty( $before_item ) ) $before_item = $def_before_item;
		if ( empty( $after_item ) ) $after_item = $def_after_item;

		$cars = array();
		if ( $type == 'selected' ) {
			$cars = trav_car_get_cars_from_id( $post_ids );
		} elseif ( $type == 'hot' ) {
			$cars = trav_car_get_hot_cars( $count, $car_type, $car_agent );
		} else {
			$cars = trav_car_get_special_cars( $type, $count, array(), $car_type, $car_agent );
		}

		ob_start();
		if ( ! empty( $title ) ) { echo '<h2>' . esc_html( $title ) . '</h2>'; }
		echo ( $before_list );
		$i = 0;
		foreach ( $cars as $car ) {
			$animation = '';
			if ( ! empty( $animation_type ) ) { $animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( $animation_delay * $i ) . '" '; }
			trav_car_get_car_list_sigle( $car->ID, $style, $before_item, $after_item, $show_badge, $animation );
			$i++;
		}
		echo ( $after_list );

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* **************** Cruise Booking Page Shortcode **********
	* **************************************************************** */
	function shortcode_cruise_booking( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'cruise-booking.php', '/templates/cruise' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ******* Cruise Booking Confirmation Page Shortcode ******
	* **************************************************************** */
	function shortcode_cruise_booking_confirmation( $atts, $content = null ) {
		ob_start();
		trav_get_template( 'cruise-booking-confirmation.php', '/templates/cruise' );
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/* ***************************************************************
	* ****************** Cruise List Shortcode ****************
	* **************************************************************** */
	function shortcode_cruises( $atts ) {
		extract( shortcode_atts( array(
			'title' => '',
			'type' => 'latest',
			'style' => 'style1',
			'count' => 10,
			'count_per_row' => 4,
			'cruise_type' => '',
			'cruise_line' => '',
			'post_ids' => '',
			'slide' => 'true',
			'before_list' => '',
			'after_list' => '',
			'before_item' => '',
			'after_item' => '',
			'show_badge' => 'no',
			'animation_type' => '',
			'animation_duration' => '',
			'animation_delay' => '',
			'item_width' => '270',
			'item_margin' => '30',
		), $atts) );
		if ( $slide == 'no' || $slide == 'false' ) { $slide = 'false'; }
		// if ( $type == 'hot' && empty( $show_badge ) ) $show_badge = true;
		if ( $show_badge == 'no' || $show_badge == 'false' ) { $show_badge = false; }
		$styles = array( 'style1', 'style2', 'style3', 'style4' );
		$types = array( 'latest', 'featured', 'popular', 'hot', 'selected' );
		if ( ! in_array( $style, $styles ) ) $style = 'style1';
		if ( ! in_array( $type, $types ) ) $type = 'latest';
		$post_ids = explode( ',', $post_ids );
		$cruise_type = ( ! empty( $cruise_type ) ) ? explode( ',', $cruise_type ) : array();
		$cruise_line = ( ! empty( $cruise_line ) ) ? explode( ',', $cruise_line ) : array();
		$count = is_numeric( $count )?$count:10;
		$count_per_row = is_numeric( $count_per_row )?$count_per_row:4;
		$item_width = is_numeric( $item_width ) ? $item_width : 270;
		$item_margin = is_numeric( $item_margin ) ? $item_margin : 270;

		$def_before_list = '';
		$def_after_list = '';
		$def_before_item = '';
		$def_after_item = '';

		if ( $style == 'style4' ) {
			$def_before_list = '<div class="listing-style4">';
			$def_after_list = '</div>';
		} elseif ( $style == 'style3' ) {
			$def_before_list = '<div class="listing-style3 cruise">';
			$def_after_list = '</div>';
		} else {
			if ( $slide == 'false' ) {
				$def_before_list = '<div class="row cruise image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</div>';
				if ( ( 2 == $count_per_row ) ) {
					$def_before_list = '<div class="row cruise image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6'>";
					$def_after_item = "</div>";
				} elseif ( 3 == $count_per_row ) {
					$def_before_list = '<div class="row cruise image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-4'>";
					$def_after_item = "</div>";
				} else {
					$def_before_list = '<div class="row cruise image-box listing-' . esc_attr( $style ) . '">';
					$def_before_item = "<div class='col-sm-6 col-sms-6 col-md-3'>";
					$def_after_item = "</div>";
				}
			} else {
				$def_before_list = '<div class="block image-carousel style2 flexslider" data-animation="slide" data-item-width="' . esc_attr( $item_width ) . '" data-item-margin="' . esc_attr( $item_margin ) . '"><ul class="slides cruise image-box listing-' . esc_attr( $style ) . '">';
				$def_after_list = '</ul></div>';
				$def_before_item = '<li>';
				$def_after_item = '</li>';
			}
		}
		if ( empty( $before_list ) ) $before_list = $def_before_list;
		if ( empty( $after_list ) ) $after_list = $def_after_list;
		if ( empty( $before_item ) ) $before_item = $def_before_item;
		if ( empty( $after_item ) ) $after_item = $def_after_item;

		$cruises = array();
		if ( $type == 'selected' ) {
			$cruises = trav_cruise_get_cruises_from_id( $post_ids );
		} elseif ( $type == 'hot' ) {
			$cruises = trav_cruise_get_hot_cruises( $count, $cruise_type, $cruise_line );
		} else {
			$cruises = trav_cruise_get_special_cruises( $type, $count, array(), $cruise_type, $cruise_line );
		}

		ob_start();
		if ( ! empty( $title ) ) { echo '<h2>' . esc_html( $title ) . '</h2>'; }
		echo ( $before_list );
		$i = 0;
		foreach ( $cruises as $cruise ) {
			$animation = '';
			if ( ! empty( $animation_type ) ) { $animation .= ' class="animated" data-animation-type="' . esc_attr( $animation_type ) . '" data-animation-duration="' . esc_attr( $animation_duration ) . '" data-animation-delay="' . esc_attr( $animation_delay * $i ) . '" '; }
			trav_cruise_get_cruise_list_sigle( $cruise->ID, $style, $before_item, $after_item, $show_badge, $animation );
			$i++;
		}
		echo ( $after_list );

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
}
endif;