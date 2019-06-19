/**
* v4.X TinyMCE specific functions. (from wordpress 3.9)
*/

(function() {

  tinymce.PluginManager.add('trav_shortcode', function(editor, url) {

    editor.addButton('trav_shortcode_button', {

      type  : 'menubutton',
      title  : 'Trav Shortcode',
      style : 'background-image: url("' + url + '/trav.png' + '"); background-repeat: no-repeat; background-position: 2px 2px;"',
      icon  : true,
      menu  : [
        { text: 'Layouts',
          menu : [
             { text : 'Row', onclick: function() {editor.insertContent('[row]...[/row]');} },
             { text : 'One Half', onclick: function() {editor.insertContent('[one_half (offset="{0-6}") ]...[/one_half]');} },
             { text : 'One Third', onclick: function() {editor.insertContent('[one_third (offset="{0-8}") ]...[/one_third]');} },
             { text : 'One Fourth', onclick: function() {editor.insertContent('[one_fourth (offset="{0-9}") ]...[/one_fourth]');} },
             { text : 'Two Third', onclick: function() {editor.insertContent('[two_third (offset="{0-4}") ]...[/two_third]');} },
             { text : 'Three Fourth', onclick: function() {editor.insertContent('[three_fourth (offset="{0-3}") ]...[/three_fourth]');} },
             { text : 'Column', onclick: function() {editor.insertContent('[column (lg = "{1-12}") (md = "{1-12}") (sm = "{1-12}") (xs = "{1-12}") (lgoff = "{0-12}") (mdoff = "{0-12}") (smoff = "{0-12}") (xsoff = "{0-12}") (lghide = "yes|no") (mdhide = "yes|no") (smhide = "yes|no") (xshide = "yes|no") (lgclear = "yes|no") (mdclear = "yes|no") (smclear = "yes|no") (xsclear = "yes|no") ]...[/column]');} },
             { text : 'Five Columns', onclick: function() {editor.insertContent('[five_column (no_margin="true|false")]...[/five_column]');} },
             { text : 'Section', onclick: function() {editor.insertContent('[section]...[/section]');} },
             { text : 'Block', onclick: function() {editor.insertContent('[block (type="small|medium|large|section|whitebox") ]...[/block]');} },
             { text : 'Folded Corner Block', onclick: function() {editor.insertContent('[folded_corner_block]...[/folded_corner_block]');} },
             { text : 'Container', onclick: function() {editor.insertContent('[container]...[/container]');} },
             { text : 'Border Box', onclick: function() {editor.insertContent('[border_box]...[/border_box]');} },
          ]
        },
        { text: 'Main Elements',
          menu : [
             { text : 'Button', onclick: function() {editor.insertContent('[button link="" (color="white|silver|sky-blue1|yellow|dark-blue1|green|red|light-brown|orange|dull-blue|light-orange|light-purple|sea-blue|sky-blue2|dark-blue2|dark-orange|purple|light-yellow") (type="small|large|medium|mini|extra")]...[/button]');} },
             { text : 'Alert', onclick: function() {editor.insertContent('[alert (type="general|error|help|notice|success|info")]...[/alert]');} },
             { text : 'Blockquote', onclick: function() {editor.insertContent('[blockquote (style="style1|style2")]...[/blockquote]');} },
             { text : 'Dropcap', onclick: function() {editor.insertContent('[dropcap]...[/dropcap]');} },
             { text : 'Checklist', onclick: function() {editor.insertContent('[checklist]...[/checklist]');} },
             { text : 'Social Links', onclick: function() {editor.insertContent('[social_links (style="style1|style2") (linktarget="_blank|_self|_parent|_top|framename") twitter="https://twitter.com/" googleplus="https://plus.google.com" facebook="https://facebook.com" linkedin="https://www.linkedin.com/" vimeo="https://vimeo.com/" dribble="https://dribbble.com/" flickr="https://www.flickr.com" ]');} },
             { text : 'Person', onclick: function() {editor.insertContent('[person]...[/person]');} },
             { text : 'Icon Box', onclick: function() {editor.insertContent('[icon_box style="{style1-style10}" icon="icon_name"]...[/icon_box]');} },
             { text : 'Parallax Block', onclick: function() {editor.insertContent('[parallax_block ratio="{0-1}" bg_image="image_url"]...[/parallax_block]');} },
             { text : 'Animation', onclick: function() {editor.insertContent('[animation]...[/animation]');} },
             { text : 'Rating', onclick: function() {editor.insertContent('[rating]...[/rating]');} },

          ]
        },
        { text: 'Group',
          menu : [
             { text : 'Toggles/Accordions', onclick: function() {editor.insertContent('[toggles title="Toggles Title" (type="accordion") (style="style1|style2") (with_image="false|true" image_animation_type="fadeIn|bounce|flash|pulse|rubberBand|shake|swing|tada|wobble|bounceIn|bounceInDown|bounceInLeft|bounceInRight|bounceInUp|bounceOut|bounceOutDown|bounceOutLeft|bounceOutRight|bounceOutUp|fadeInDown|fadeInDownBig|fadeInLeft|fadeInLeftBig|fadeInRight|fadeInRightBig|fadeInUp|fadeInUpBig|fadeOut|fadeOutDown|fadeOutDownBig|fadeOutLeft|fadeOutLeftBig|fadeOutRight|fadeOutRightBig|fadeOutUp|fadeOutUpBig|flip|flipInX|flipInY|flipOutX|flipOutY|lightSpeedIn|lightSpeedOut|rotateIn|rotateInDownLeft|rotateInDownRight|rotateInUpLeft|rotateInUpRight|rotateOut|rotateOutDownLeft|rotateOutDownRight|rotateOutUpLeft|rotateOutUpRight|slideInDown|slideInLeft|slideInRight|slideOutLeft|slideOutRight|slideOutUp|hinge|rollIn|rollOut" image_animation_duration="{0-}") ][toggle title="Toggle Title" (collapsed="true|false") img_src="" img_alt="" img_width="" img_height=""]...[/toggle][/toggles]');} },
             { text : 'Toggle', onclick: function() {editor.insertContent('[toggle title="Toggle Title" (collapsed="true|false") img_src="" img_alt="" img_width="" img_height=""]...[/toggle]');} },
             { text : 'Tabs', onclick: function() {editor.insertContent('[tabs title="Tabs" {tab1_id}="tab2_label" {tab2_id}="tab2_label" (style="default|style1|trans-style|full-width-style") (bg_color="blue-bg|yellow-bg|red-bg|green-bg|white-bg|dark-blue-bg|gray-bg|skin-bg") (img_src="") (img_width) (img_height="") (img_alt="") ]...[/tabs]');} },
             { text : 'Tab', onclick: function() {editor.insertContent('[tab id=""]...[/tab]');} },
             { text : 'Testimonials', onclick: function() {editor.insertContent('[testimonials (style="style1|style2|style3") title="" (author_img_size="")][testimonial author_name="" author_link="" author_img_url="" ]...[/testimonial][/testimonials]');} },
             { text : 'Testimonial', onclick: function() {editor.insertContent('[testimonial author_name="" author_link="" author_img_url="" ]...[/testimonial]');} },
             { text : 'Slider', onclick: function() {editor.insertContent('[slider (type="gallery1|gallery2|gallery3|gallery4|carousel1|carousel2") (class="" ul_class="") ][slide]...[/slide][/slider]');} },
             { text : 'Slide', onclick: function() {editor.insertContent('[slide]...[/slide]');} },
             { text : 'Background Slider', onclick: function() {editor.insertContent('[bgslide img_urls="http://soaptheme.net/html/travelo/images/tour/home/slider/1.jpg,http://soaptheme.net/html/travelo/images/tour/home/slider/2.jpg"]...[/bgslide]');} },
             { text : 'Images', onclick: function() {editor.insertContent('[images]...[/images]');} },
             { text : 'imageframe', onclick: function() {editor.insertContent('[imageframe]');} },
          ]
        },
        { text: 'Special Blocks',
          menu : [
             { text : 'Content Boxes', onclick: function() {editor.insertContent('[content_boxes]...[/content_boxes]');} },
             { text : 'Content Box', onclick: function() {editor.insertContent('[content_box]...[/content_box]');} },
             { text : 'Content Box Detail', onclick: function() {editor.insertContent('[content_box_detail]...[/content_box_detail]');} },
             { text : 'Content Box Detail Row', onclick: function() {editor.insertContent('[content_box_detail_row]...[/content_box_detail_row]');} },
             { text : 'Content Box Action', onclick: function() {editor.insertContent('[content_box_action]...[/content_box_action]');} },
             { text : 'Promo Box', onclick: function() {editor.insertContent('[promo_box]...[/promo_box]');} },
             { text : 'Promo Box Left', onclick: function() {editor.insertContent('[promo_box_left]...[/promo_box_left]');} },
             { text : 'Promo Box Right', onclick: function() {editor.insertContent('[promo_box_right]...[/promo_box_right]');} },
             { text : 'Search Group', onclick: function() {editor.insertContent('[search_group]...[/search_group]');} },
             { text : 'Search Form', onclick: function() {editor.insertContent('[search_form]...[/search_form]');} },
             { text : 'Search Text Field', onclick: function() {editor.insertContent('[search_form_textfield]...[/search_form_textfield]');} },
             { text : 'Pricing Table', onclick: function() {editor.insertContent('[pricing_table]...[/pricing_table]');} },
             { text : 'Pricing Table Head', onclick: function() {editor.insertContent('[pricing_table_head]...[/pricing_table_head]');} },
             { text : 'Pricing Table Content', onclick: function() {editor.insertContent('[pricing_table_content]...[/pricing_table_content]');} },
             { text : 'Pricing Table Features', onclick: function() {editor.insertContent('[pricing_table_features]...[/pricing_table_features]');} },
          ]
        },
        { text: 'Pages & Lists',
          menu : [
             { text : 'Blog', onclick: function() {editor.insertContent('[blog]');} },
             { text : 'Dashboard', onclick: function() {editor.insertContent('[dashboard]');} },
             { text : 'Accommodation Booking Page', onclick: function() {editor.insertContent('[accommodation_booking]');} },
             { text : 'Accommodation Booking Confirmation Page', onclick: function() {editor.insertContent('[accommodation_booking_confirmation]');} },
             { text : 'Accommodations', onclick: function() {editor.insertContent('[accommodations title="", (type="latest|featured|popular|hot|selected") (style="style1|style2|style3|style4") (count="10") (city="" country="") (post_ids="") ]');} },
             { text : 'Similar Accommodations', onclick: function() {editor.insertContent('[similar_accommodations (count="3") (thumb_width="64") (thumb_height="64")]');} },
             { text : 'Tour Booking Page', onclick: function() {editor.insertContent('[tour_booking]');} },
             { text : 'Tour Booking Confirmation Page', onclick: function() {editor.insertContent('[tour_booking_confirmation]');} },
             { text : 'Tours', onclick: function() {editor.insertContent('[tours title="", (type="latest|featured|popular|hot|selected") (style="style1|style2|style3") (count="10") (city="" country="") (post_ids="") ]');} },
             { text : 'Latest Tours', onclick: function() {editor.insertContent('[latest_tours (count="3") (thumb_width="64") (thumb_height="64")]');} },
          ]
        }
      ]

    });

  });

})();