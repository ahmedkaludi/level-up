/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );


    // Typography

    wp.customize( 'body_font_color', function( value ) {
        value.bind( function( to ) {
            $( 'body, button, input, select, optgroup, textarea' ).css( 'color', to );
        } );
    });


	// Container width

    wp.customize( 'container_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-container.width' ).css( 'width', to + '%' );
        } );
    });
    //Amp Flexi Options
    wp.customize( 'container_max_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-container.max' ).css( 'max-width', to + 'px' );
        } );
    });

    wp.customize( 'theme_feild_type_range', function( value ) {
        value.bind( function( to ) {
            $( '.dummy_class' ).css( 'max-width', to + 'px' );
        } );
    });
    wp.customize( 'levelup_container_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup_width' ).css( 'max-width', to + '%' );
            
        } );
    });
    wp.customize( 'amp_page_title_bg', function( value ) {
        value.bind( function( to ) {
            console.log(to);
            $( '.levelup_width' ).css( 'background-color', to );
        } );
    });
    // Body Font Family
    wp.customize( 'levelup_body_font_family', function( value ) {
        value.bind( function( to ) {
        console.log(to);
          //  $( '.levelup_width' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_body_font_variants', function( value ) {
        value.bind( function( to ) {
        console.log(to);
          //  $( '.levelup_width' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_body_font_subsets', function( value ) {
        value.bind( function( to ) {
        console.log(to);
          //  $( '.levelup_width' ).css( 'background-color', to );
        } );
    });
    // Body Font Family
    wp.customize( 'levelup_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.levelup_width' ).css( 'font-size', to + 'em' );
        } );
    });

    wp.customize( 'levelup_text_field', function( value ) {
        value.bind( function( to ) {
            $( '.field_integer' ).text( to );
        } );
    });

    wp.customize( 'theme_feild_type_radio', function( value ) {
        value.bind( function( to ) {
            if (to == 'theme_feild_type_radio_one') {
                $( '.heading_one' ).css( 'display', 'block' );
                $( '.heading_two' ).css( 'display', 'none' );
                $( '.heading_three' ).css( 'display', 'none' );
            }else if( to == 'theme_feild_type_radio_two') {
                $( '.heading_one' ).css( 'display', 'none' );
                $( '.heading_two' ).css( 'display', 'block' );
                $( '.heading_three' ).css( 'display', 'none' );
            }else if( to == 'theme_feild_type_radio_two'){
                $( '.heading_one' ).css( 'display', 'none' );
                $( '.heading_two' ).css( 'display', 'none' );
                $( '.heading_three' ).css( 'display', 'block' );
            }
            $( '.levelup_width' ).css( 'font-size', to + 'em' );
        } );
    });
    //Amp Flexi Options
	
    // Post Container width

    wp.customize( 'post_width', function( value ) {
        value.bind( function( to ) {
            $( '.single-post .entry-content-wrapper' ).css( 'width', to + '%' );
        } );
    });

    wp.customize( 'post_max_width', function( value ) {
        value.bind( function( to ) {
            $( '.single-post .entry-content-wrapper' ).css( 'max-width', to + 'px' );
        } );
    });

    wp.customize( 'blog_bg_color', function( value ) {
        value.bind( function( to ) {
            $( 'body.blog, body.archive, body.single-post' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'post_content_bg_color', function( value ) {
        value.bind( function( to ) {
            $( 'body:not(.single-post) .levelup-wrapper > .content-area' ).css( 'background-color', to );
            $( '.entry-content.single-post-entry' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'post_meta_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.single-post .entry-header.single-blog-meta.single-blog-meta-large' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'sidebar_widget_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-sidebar .widget' ).css( 'background-color', to );
        } );
    });

    // Header logo width

    wp.customize( 'levelup_header_logo_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-header-logo' ).css( 'width', to + 'px' );
        } );
    });

    // Blog logo width

    wp.customize( 'blog_logo_width', function( value ) {
        value.bind( function( to ) {
            $( '.header-content .levelup-blog-logo' ).css( 'width', to + 'px' );
        } );
    });

    // Page title and font size

    wp.customize( 'levelup_page_title_bg', function( value ) {
        value.bind( function( to ) {
            $( '.page .entry-header.entry-header-large, .page .entry-header.entry-header-mini' ).css( 'background-color', to );

        } );
    });

    wp.customize( 'levelup_page_title_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.page .entry-header .entry-title' ).css( 'color', to);

        } );
    });

    wp.customize( 'levelup_page_title_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.page .entry-header .entry-title' ).css( 'font-size', to + 'px' );

        } );
    });

    // Breadcrumbs

    wp.customize( 'levelup_breadcrumb_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-breadcrumb .levelup-breadcrumb-item, .levelup-breadcrumb .levelup-breadcrumb-item a, .levelup-breadcrumb .breadcrumb-delimiter' ).css( 'font-size', to + 'px' );

        } );
    });

    wp.customize( 'levelup_breadcrumb_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-breadcrumb .levelup-breadcrumb-item, .levelup-breadcrumb .levelup-breadcrumb-item a, .levelup-breadcrumb .breadcrumb-delimiter' ).css( 'color', to);

        } );
    });

    wp.customize( 'levelup_breadcrumb_active_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-breadcrumb-item.current span, .breadcrumb li a:hover, .breadcrumb li a:focus' ).css( 'color', to);

        } );
    });

    // Blog title and font size

    wp.customize( 'blog_title', function( value ) {
        value.bind( function( to ) {
            $( '.blog-header .header-content > .page-title' ).text( to );

        } );
    });

    wp.customize( 'blog_title_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.blog-header .header-content > .page-title' ).css( 'font-size', to + 'px' );

        } );
    });

    // Blog description and font size

    wp.customize( 'blog_desc', function( value ) {
        value.bind( function( to ) {
            $( '.header-content .blog-desc' ).text( to );

        } );
    });

    wp.customize( 'blog_desc_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.header-content .blog-desc' ).css( 'font-size', to + 'px' );
            $( '.header-content .archive-description > p' ).css( 'font-size', to + 'px' );
        } );
    });

    // Sidebars width

    wp.customize( 'left_sidebar_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-sidebar-left' ).css( 'width', to + 'px' );
        } );
    });

    wp.customize( 'right_sidebar_width', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-sidebar-right' ).css( 'width', to + 'px' );
        } );
    });

    // Header Area

    wp.customize( 'header_widget_area_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-header-widget-area' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_topbar_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-topbar' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_logobar_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-logobar' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_navbar_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-navbar' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_nav_menu_link_color', function( value ) {
        value.bind( function( to ) {
            $( '.main-navigation > ul > li > a' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_nav_menu_link_hover_color', function( value ) {
        value.bind( function( to ) {
            var menuLinkColor=$(".main-navigation > ul > li > a").css('color');
            $(".main-navigation > ul > li > a").hover(
            function() {
                //mouse over
                $(this).css('color', to)
            }, function() {
                //mouse out
                $(this).css('color', menuLinkColor)
            });

        } );
    });

    wp.customize( 'levelup_submenu_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.main-navigation ul.sub-menu' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_submenu_link_color', function( value ) {
        value.bind( function( to ) {
            $( '.main-navigation ul ul li a' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_submenu_link_hover_color', function( value ) {
        value.bind( function( to ) {
            var menuLinkColor=$(".main-navigation ul ul li a").css('color');
            $(".main-navigation ul ul li a").hover(
            function() {
                //mouse over
                $(this).css('color', to)
            }, function() {
                //mouse out
                $(this).css('color', menuLinkColor)
            });

        } );
    });

    // Search Overlay

    wp.customize( 'levelup_overlay_search_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-search-overlay' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_overlay_search_close_btn_size', function( value ) {
        value.bind( function( to ) {
            $( '.icon-search-close' ).css( 'width', to + 'px' );
            $( '.icon-search-close' ).css( 'height', to + 'px' );
        } );
    });

    wp.customize( 'levelup_overlay_search_close_btn_color', function( value ) {
        value.bind( function( to ) {
            $( '.icon-search-close' ).css( 'fill', to );
        } );
    });

    wp.customize( 'levelup_overlay_search_field_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.search--input-wrapper .search__input, .search--input-wrapper .search__input:focus' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_overlay_search_field_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.search--input-wrapper .search__input, .search--input-wrapper .search__input:focus' ).css( 'font-size', to + 'px' );
        } );
    });

    wp.customize( 'levelup_overlay_search_label_font_color', function( value ) {
        value.bind( function( to ) {
            $( '.search__info' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_overlay_search_label_font_size', function( value ) {
        value.bind( function( to ) {
            $( '.search__info' ).css( 'font-size', to + 'px' );
        } );
    });


    // Footer Area

    wp.customize( 'footer_widget_area_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-footer-widget-area' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_footer_bg_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-site-footer' ).css( 'background-color', to );
        } );
    });

    wp.customize( 'levelup_footer_content_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-site-footer .site-info' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_footer_link_color', function( value ) {
        value.bind( function( to ) {
            $( '.levelup-site-footer .site-info a, .levelup-footer-menu li a' ).css( 'color', to );
        } );
    });

    wp.customize( 'levelup_footer_link_hover_color', function( value ) {
        value.bind( function( to ) {
		    var colorLink=$(".levelup-site-footer .site-info a, .levelup-footer-menu li a").css('color');
		    $(".levelup-site-footer .site-info a, .levelup-footer-menu li a").hover(
		    function() {
		        //mouse over
		        $(this).css('color', to)
		    }, function() {
		        //mouse out
		        $(this).css('color', colorLink)
		    });

        } );
    });





} )( jQuery );
