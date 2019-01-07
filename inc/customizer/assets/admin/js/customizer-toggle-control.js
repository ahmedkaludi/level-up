/*
 * Script run inside a Customizer control sidebar
 *
 * Enable / disable the control title by toggeling its .disabled-control-title style class on or off.
 */
( function( $ ) {
	wp.customize.bind( 'ready', function() { // Ready?

		var customize = this; // Customize object alias.
		// Array with the control names
		// TODO: Replace #CONTROLNAME01#, #CONTROLNAME02# etc with the real control names.
		var toggleControls = [
			'#CONTROLNAME01#',
			'#CONTROLNAME02#'
		];
		$.each( toggleControls, function( index, control_name ) {
			customize( control_name, function( value ) {
				var controlTitle = customize.control( control_name ).container.find( '.customize-control-title' ); // Get control  title.
				// 1. On loading.
				controlTitle.toggleClass('disabled-control-title', !value.get() );
				// 2. Binding to value change.
				value.bind( function( to ) {
					controlTitle.toggleClass( 'disabled-control-title', !value.get() );
				} );
			} );
		} );
	} );

	// Blog Content Display Excerpt Show/Hide
    wp.customize.bind( 'ready', function() {
        if( 'levelup_blog_content_display_full' === levelup_settings.levelup_blog_content_display ) {
            levelup_blog_content_hide_controls();
        }else {
            levelup_blog_content_show_controls();
        }

        wp.customize( 'levelup_blog_content_display', function( value ) {
            value.bind( function( to ) {
                if('levelup_blog_content_display_full' === to) {
                    levelup_blog_content_hide_controls();
                }else {
                    levelup_blog_content_show_controls();
                }
            } );
        } );

        function levelup_blog_content_hide_controls() {
            $('#customize-control-levelup_blog_excerpt_count').hide();
        }
        function levelup_blog_content_show_controls() {
            $('#customize-control-levelup_blog_excerpt_count').show();
        }
    });

    //Toggle Nav Bar option in customiZer
    wp.customize.bind( 'ready', function() {
        if( 1 == levelup_settings.levelup_navbar ) {
            levelup_blog_content_hide_controls();
        }else {
            levelup_blog_content_show_controls();
        }

        wp.customize( 'levelup_navbar', function( value ) {
            value.bind( function( to ) {
                if(1 == to) {
                    levelup_blog_content_hide_controls();
                }else {
                    levelup_blog_content_show_controls();
                }
            } );
        } );

        function levelup_blog_content_hide_controls() {
            $('#customize-control-levelup_navbar_position').hide();
        
        }

        function levelup_blog_content_show_controls() {
            $('#customize-control-levelup_navbar_position').show();
            
        }
    });

    //Toggle Nav Bar option in customiZer
    // Navbar Settings Show/Hide
    wp.customize.bind( 'ready', function() {
        if( 1 == levelup_settings.levelup_enable_topbar ) {
            levelup_blog_cotnent_show_controls();
        }else {
            levelup_blog_cotnent_hide_controls();
        }

        wp.customize( 'levelup_enable_topbar', function( value ) {
            value.bind( function( to ) {
                if(1 == to) {
                    levelup_blog_cotnent_show_controls();
                }else {
                    levelup_blog_cotnent_hide_controls();
                }
            } );
        } );

        function levelup_blog_cotnent_hide_controls() {
            $('#customize-control-levelup_enable_topbar_menu').hide();
            $('#customize-control-levelup_topbar_bg_color').hide();
            $('#customize-control-levelup_topbar_content').hide();
        }

        function levelup_blog_cotnent_show_controls() {
            $('#customize-control-levelup_enable_topbar_menu').show();
            $('#customize-control-levelup_topbar_bg_color').show();
            $('#customize-control-levelup_topbar_content').show();
        }
    });
	// Topbar Settings Show/Hide
	wp.customize.bind( 'ready', function() {
        if( 1 == levelup_settings.levelup_enable_topbar ) {
            levelup_blog_cotnent_show_controls();
        }else {
            levelup_blog_cotnent_hide_controls();
        }

        wp.customize( 'levelup_enable_topbar', function( value ) {
            value.bind( function( to ) {
                if(1 == to) {
                    levelup_blog_cotnent_show_controls();
                }else {
                    levelup_blog_cotnent_hide_controls();
                }
            } );
        } );

        function levelup_blog_cotnent_hide_controls() {
            $('#customize-control-levelup_enable_topbar_menu').hide();
            $('#customize-control-levelup_topbar_bg_color').hide();
            $('#customize-control-levelup_topbar_content').hide();
        }

        function levelup_blog_cotnent_show_controls() {
            $('#customize-control-levelup_enable_topbar_menu').show();
            $('#customize-control-levelup_topbar_bg_color').show();
            $('#customize-control-levelup_topbar_content').show();
        }
    });

    // Level-up Google Fonts Controllers
    wp.customize.bind( 'ready', function() {
        levelup_customizer_font_variants_generator( 'body_font_family', '#customize-control-body_font_variants', '#customize-control-body_font_subsets', 'body_google_font', 'body_font_variants', 'body_font_subsets' );

       /* levelup_customizer_font_variants_generator( 'heading_font_family', '#customize-control-heading_font_variants', '#customize-control-heading_font_subsets', 'heading_google_font', 'heading_font_variants', 'heading_font_subsets' );*/
        //Custom Font Options
        levelup_customizer_font_variants_generator( 'levelup_body_font_family', '#customize-control-levelup_body_font_variants', '#customize-control-levelup_body_font_subsets', 'levelup_google_font', 'levelup_font_variants', 'levelup_font_subsets' );

        /*levelup_customizer_font_variants_generator( 'levelup_heading_font_family', '#customize-control-levelup_heading_font_variants', '#customize-control-levelup_heading_font_subsets', 'levelup_google_font', 'levelup_heading_font_variants', 'levelup_heading_font_subsets' );*/
    });
    function levelup_customizer_font_variants_generator( font_field_name, variant_field_id, subset_field_id, font_field_localize_name, font_variant_field_localize_name, font_subset_field_localize_name ) {
        wp.customize( font_field_name, function( value ) {
            $.ajax({
                url: levelup_settings.ajax_url,
                data: {
                    action: 'load_google_font_variants',
                    postType: 'post',
                    fontFamily: levelup_settings[font_field_localize_name]
                },
                type: 'POST',
                success: function( data ) {
                    var data = $.parseJSON(data);
                    $(data.variants).each(function(i,val) {
                        $.each(val,function(key,value) {
                            if(-1!=$.inArray( key, levelup_settings[font_variant_field_localize_name] ) ) {
                                var selected = 'selected';
                            }else {
                                var selected = '';
                            }
                            $(variant_field_id+' select').append('<option value="'+key+'" '+selected+'>'+value+'</option>')
                        });
                    });

                    $(data.subsets).each(function(i,val) {
                        $.each(val,function(key,val) {
                            if( key == levelup_settings[font_subset_field_localize_name] ) {
                                var selected = 'selected';
                            }else {
                                var selected = '';
                            }
                            $(subset_field_id+' select').append('<option value="'+key+'" '+selected+'>'+val+'</option>')
                        });
                    });
                }
            });
            value.bind( function( to ) {
                $(variant_field_id+' select').html('');
                $(subset_field_id+' select').html('');
                $.ajax({
                    url: levelup_settings.ajax_url,
                    data: {
                        action: 'load_google_font_variants',
                        postType: 'post',
                        fontFamily: to
                    },
                    type: 'POST',
                    success: function( data ) {
                        var data = $.parseJSON(data);
                        $(data.variants).each(function(i,val) {
                            $.each(val,function(key,val) {
                                  $(variant_field_id+' select').append($('<option>',
                                    {
                                        value: key,
                                        text : val
                                    }
                                ));
                            });
                        });
                        $(data.subsets).each(function(i,val) {
                            $.each(val,function(key,val) {
                                  $(subset_field_id+' select').append($('<option>',
                                    {
                                        value: key,
                                        text : val
                                    }
                                ));
                            });
                        });
                    }
                });
            });
        });
    }
} )( jQuery );
