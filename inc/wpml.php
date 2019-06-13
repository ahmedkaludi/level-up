<?php
/*
 * get sibling post id in default language
 */
if ( ! function_exists( 'trav_get_default_language_post_id' ) ) {
	function trav_get_default_language_post_id($id, $post_type='post') {
		if ( function_exists('icl_object_id') ) {
			//global $sitepress;
			//$default_language = $sitepress->get_default_language();
			$default_language = trav_get_default_language();
			return icl_object_id($id, $post_type, true, $default_language);
		} else {
			return $id;
		}
	}
}

/*
 * get sibling post id in current language
 */
if ( ! function_exists( 'trav_get_current_language_post_id' ) ) {
	function trav_get_current_language_post_id($id, $post_type='post') {
		if ( function_exists('icl_object_id') ) {
			return icl_object_id($id, $post_type, true);
		} else {
			return $id;
		}
	}
}

/*
 * get sibling accommodation id in original language
 */
if ( ! function_exists( 'trav_acc_org_id' ) ) {
	function trav_acc_org_id($id) {
		return trav_get_default_language_post_id( $id, 'accommodation' );
	}
}

/*
 * get sibling accommodation id in current language
 */
if ( ! function_exists( 'trav_acc_clang_id' ) ) {
	function trav_acc_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'accommodation' );
	}
}

/*
 * get sibling room id in original language
 */
if ( ! function_exists( 'trav_room_org_id' ) ) {
	function trav_room_org_id($id) {
		return trav_get_default_language_post_id( $id, 'room_type' );
	}
}

/*
 * get sibling room id in current language
 */
if ( ! function_exists( 'trav_room_clang_id' ) ) {
	function trav_room_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'room_type' );
	}
}

/*
 * get sibling tour id in original language
 */
if ( ! function_exists( 'trav_tour_org_id' ) ) {
	function trav_tour_org_id($id) {
		return trav_get_default_language_post_id( $id, 'tour' );
	}
}

/*
 * get sibling tour id in current language
 */
if ( ! function_exists( 'trav_tour_clang_id' ) ) {
	function trav_tour_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'tour' );
	}
}

/*
 * get sibling car id in original language
 */
if ( ! function_exists( 'trav_car_org_id' ) ) {
	function trav_car_org_id($id) {
		return trav_get_default_language_post_id( $id, 'car' );
	}
}

/*
 * get sibling car id in current language
 */
if ( ! function_exists( 'trav_car_clang_id' ) ) {
	function trav_car_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'car' );
	}
}

/*
 * get sibling cruise id in original language
 */
if ( ! function_exists( 'trav_cruise_org_id' ) ) {
	function trav_cruise_org_id($id) {
		return trav_get_default_language_post_id( $id, 'cruise' );
	}
}

/*
 * get sibling cruise id in current language
 */
if ( ! function_exists( 'trav_cruise_clang_id' ) ) {
	function trav_cruise_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'cruise' );
	}
}

/*
 * get sibling cabin id in original language
 */
if ( ! function_exists( 'trav_cabin_org_id' ) ) {
	function trav_cabin_org_id($id) {
		return trav_get_default_language_post_id( $id, 'cabin' );
	}
}

/*
 * get sibling cabin id in current language
 */
if ( ! function_exists( 'trav_cabin_clang_id' ) ) {
	function trav_cabin_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'cabin' );
	}
}

/*
 * get sibling food & dining id in original language
 */
if ( ! function_exists( 'trav_food_dining_org_id' ) ) {
	function trav_food_dining_org_id($id) {
		return trav_get_default_language_post_id( $id, 'food_dining' );
	}
}

/*
 * get sibling food & dining id in current language
 */
if ( ! function_exists( 'trav_food_dining_clang_id' ) ) {
	function trav_food_dining_clang_id($id) {
		return trav_get_current_language_post_id( $id, 'food_dining' );
	}
}

/*
 * get sibling post id in original language
 */
if ( ! function_exists( 'trav_post_org_id' ) ) {
	function trav_post_org_id($id) {
		return trav_get_default_language_post_id( $id, get_post_type( $id ));
	}
}

/*
 * get sibling post id in current language
 */
if ( ! function_exists( 'trav_post_clang_id' ) ) {
	function trav_post_clang_id($id) {
		return trav_get_current_language_post_id( $id, get_post_type( $id ));
	}
}

/*
 * get default language
 */
if ( ! function_exists( 'trav_get_default_language' ) ) {
	function trav_get_default_language() {
		global $sitepress;
		if ( $sitepress ) {
			return $sitepress->get_default_language();
		} elseif ( defined(WPLANG) ) {
			return WPLANG;
		} else
			return "en";
	}
}

/*
 * get default language
 */
if ( ! function_exists( 'trav_get_permalink_clang' ) ) {
	function trav_get_permalink_clang( $post_id )
	{
		$url = "";
		if ( function_exists('icl_object_id') ) {
			$language = ICL_LANGUAGE_CODE;

			$lang_post_id = icl_object_id( $post_id , 'page', true, $language );

			if($lang_post_id != 0) {
				$url = get_permalink( $lang_post_id );
			}else {
				// No page found, it's most likely the homepage
				global $sitepress;
				$url = $sitepress->language_url( $language );
			}
		} else {
			$url = get_permalink( $post_id );
		}

		return $url;
	}
}