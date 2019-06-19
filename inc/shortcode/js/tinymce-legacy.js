/**
* v3.X TinyMCE specific functions. (before wordpress 3.9)
*/

(function() {
  tinymce.create('tinymce.plugins.travShortcode', {

    init : function(ed, url){
      tinymce.plugins.travShortcode.theurl = url;
    },

    createControl : function(btn, e) {
      if ( btn == 'trav_shortcode_button' ) {
        var a   = this;
        var btn = e.createSplitButton('trav_shortcode_button', {
          title: 'Trav Shortcode',
          image: tinymce.plugins.travShortcode.theurl + '/trav.png',
          icons: false,
        });

        btn.onRenderMenu.add(function (c, b) {

          // Layouts
          c = b.addMenu({title:'Layouts'});

          a.render( c, 'Row', 'row' );
          a.render( c, 'One Half', 'one-half' );
          a.render( c, 'One Third', 'one-third' );
          a.render( c, 'One Fourth', 'one-fourth' );
          a.render( c, 'Two Third', 'two-third' );
          a.render( c, 'Three Fourth', 'three-fourth' );
          a.render( c, 'Column', 'column' );
          a.render( c, 'Block', 'block' );
          a.render( c, 'Container', 'container' );
          a.render( c, 'Border Box', 'border-box' );

          // Elements
          c = b.addMenu({title:'Elements'});

          a.render( c, 'Button', 'button' );
          a.render( c, 'Alert', 'alert' );
          a.render( c, 'Blockquote', 'blockquote' );
          a.render( c, 'Social Links', 'social-links' );
          a.render( c, 'Person', 'person' );
          a.render( c, 'Icon Box', 'icon-box' );
          a.render( c, 'Parallax Block', 'parallax' );

          // Group
          c = b.addMenu({title:'Group'});

          a.render( c, 'Toggle/Accordion Container', 'toggles' );
          a.render( c, 'Toggle', 'toggle' );
          a.render( c, 'Tabs', 'tabs' );
          a.render( c, 'Tab', 'tab' );
          a.render( c, 'Testimonials', 'testimonials' );
          a.render( c, 'Testimonial', 'testimonial' );
          a.render( c, 'Slider', 'slider' );
          a.render( c, 'Slide', 'slide' );

        });
        return btn;
      }
      return null;
    },

    render : function(ed, title, id) {
      ed.add({
        title: title,
        onclick: function () {

          if( id === 'row' ) {
            tinyMCE.activeEditor.selection.setContent('[row]...[/row]');
          }

          if( id === 'one-half' ) {
            tinyMCE.activeEditor.selection.setContent('[one_half (offset="{0-6}") ]...[/one_half]');
          }

          if( id === 'one-third' ) {
            tinyMCE.activeEditor.selection.setContent('[one_third (offset="{0-8}") ]...[/one_third]');
          }

          if( id === 'one-fourth' ) {
            tinyMCE.activeEditor.selection.setContent('[one_fourth (offset="{0-9}") ]...[/one_fourth]');
          }

          if( id === 'two-third' ) {
            tinyMCE.activeEditor.selection.setContent('[two_third (offset="{0-4}") ]...[/two_third]');
          }

          if( id === 'three-fourth' ) {
            tinyMCE.activeEditor.selection.setContent('[three_fourth (offset="{0-3}") ]...[/three_fourth]');
          }

          if( id === 'column' ) {
            tinyMCE.activeEditor.selection.setContent('[column (lg = "{1-12}") (md = "{1-12}") (sm = "{1-12}") (xs = "{1-12}") (lgoff = "{0-12}") (mdoff = "{0-12}") (smoff = "{0-12}") (xsoff = "{0-12}") (lghide = "yes|no") (mdhide = "yes|no") (smhide = "yes|no") (xshide = "yes|no") (lgclear = "yes|no") (mdclear = "yes|no") (smclear = "yes|no") (xsclear = "yes|no") ]...[/column]');
          }

          if( id === 'block' ) {
            tinyMCE.activeEditor.selection.setContent('[block (type="small|medium|large") ]...[/block]');
          }

          if( id === 'container' ) {
            tinyMCE.activeEditor.selection.setContent('[container]...[/container]');
          }

          if( id === 'border-box' ) {
            tinyMCE.activeEditor.selection.setContent('[border_box]...[/border_box]');
          }

          if( id === 'button' ) {
            tinyMCE.activeEditor.selection.setContent('[button link="" (color="white|silver|sky-blue1|yellow|dark-blue1|green|red|light-brown|orange|dull-blue|light-orange|light-purple|sea-blue|sky-blue2|dark-blue2|dark-orange|purple|light-yellow") (type="small|large|medium|mini|extra")]...[/button]');
          }

          if( id === 'alert' ) {
            tinyMCE.activeEditor.selection.setContent('[alert (type="general|error|help|notice|success|info")]...[/alert]');
          }

          if( id === 'blockquote' ) {
            tinyMCE.activeEditor.selection.setContent('[blockquote (style="style1|style2")]...[/blockquote]');
          }

          if( id === 'social-links' ) {
            tinyMCE.activeEditor.selection.setContent('[social_links (style="style1|style2") (linktarget="_blank|_self|_parent|_top|framename") twitter="https://twitter.com/" googleplus="https://plus.google.com" facebook="https://facebook.com" linkedin="https://www.linkedin.com/" vimeo="https://vimeo.com/" dribble="https://dribbble.com/" flickr="https://www.flickr.com" ]');
          }

          if( id === 'person' ) {
            tinyMCE.activeEditor.selection.setContent('[person]...[/person]');
          }

          if( id === 'icon-box' ) {
            tinyMCE.activeEditor.selection.setContent('[icon_box style="{style1-style10}" icon="icon_name"]...[/icon_box]');
          }

          if( id === 'parallax' ) {
            tinyMCE.activeEditor.selection.setContent('[parallax_block ratio="{0-1}" bg_image="image_url"]...[/parallax_block]');
          }

          if( id === 'toggles' ) {
            tinyMCE.activeEditor.selection.setContent('[toggles title="Toggles" (type="accordion") (style="style1|style2") (with_image="false|true" image_animation_type="fadeIn|bounce|flash|pulse|rubberBand|shake|swing|tada|wobble|bounceIn|bounceInDown|bounceInLeft|bounceInRight|bounceInUp|bounceOut|bounceOutDown|bounceOutLeft|bounceOutRight|bounceOutUp|fadeInDown|fadeInDownBig|fadeInLeft|fadeInLeftBig|fadeInRight|fadeInRightBig|fadeInUp|fadeInUpBig|fadeOut|fadeOutDown|fadeOutDownBig|fadeOutLeft|fadeOutLeftBig|fadeOutRight|fadeOutRightBig|fadeOutUp|fadeOutUpBig|flip|flipInX|flipInY|flipOutX|flipOutY|lightSpeedIn|lightSpeedOut|rotateIn|rotateInDownLeft|rotateInDownRight|rotateInUpLeft|rotateInUpRight|rotateOut|rotateOutDownLeft|rotateOutDownRight|rotateOutUpLeft|rotateOutUpRight|slideInDown|slideInLeft|slideInRight|slideOutLeft|slideOutRight|slideOutUp|hinge|rollIn|rollOut" image_animation_duration="{0-}") ][toggle title="Toggle Title" (collapsed="true|false") img_src="" img_alt="" img_width="" img_height=""]...[/toggle][/toggles]');
          }

          if( id === 'toggle' ) {
            tinyMCE.activeEditor.selection.setContent('[toggle title="Toggle Title" (collapsed="true|false") img_src="" img_alt="" img_width="" img_height=""]...[/toggle]');
          }

          if( id === 'tabs' ) {
            tinyMCE.activeEditor.selection.setContent('[tabs title="Tabs" {tab1_id}="tab2_label" {tab2_id}="tab2_label" (style="default|style1|trans-style|full-width-style") (bg_color="blue-bg|yellow-bg|red-bg|green-bg|white-bg|dark-blue-bg|gray-bg|skin-bg") (img_src="") (img_width) (img_height="") (img_alt="") ][tab id=""]...[/tab][/tabs]');
          }

          if( id === 'tab' ) {
            tinyMCE.activeEditor.selection.setContent('[tab id=""]...[/tab]');
          }

          if( id === 'testimonials' ) {
            tinyMCE.activeEditor.selection.setContent('[testimonials (style="style1|style2|style3") title="" (author_img_size="")][testimonial author_name="" author_link="" author_img_url="" ]...[/testimonial][/testimonials]');
          }

          if( id === 'testimonial' ) {
            tinyMCE.activeEditor.selection.setContent('[testimonial author_name="" author_link="" author_img_url="" ]...[/testimonial]');
          }

          if( id === 'slider' ) {
            tinyMCE.activeEditor.selection.setContent('[slider (type="gallery1|gallery2|gallery3|gallery4|carousel1|carousel2") (class="" ul_class="") ][slide]...[/slide][/slider]');
          }

          if( id === 'slide' ) {
            tinyMCE.activeEditor.selection.setContent('[slide]...[/slide]');
          }

          return false;

        }
      });
    }
  
  });

  tinymce.PluginManager.add('trav_shortcode', tinymce.plugins.travShortcode);

})();