<?php
/**
 * The template for displaying Search form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package designblocks
 */
 ?>

    <form role="search" method="get" class="designblocks-fullscreen-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label>
          <input type="search" class="search-field"
              placeholder="<?php echo esc_attr_x( 'Search...', 'label', 'designblocks' ) ?>"
              value="<?php echo esc_attr( get_search_query() ); ?>" name="s"
              title="<?php echo esc_attr_x( 'Search for:', 'label', 'designblocks' ) ?>" id="designblocks-fullscreen-search-input"/>
      </label>
      <label class="search-button search-overlay">
          <i aria-hidden="true"></i>
      <input type="submit" class="search-submit"
          value="<?php echo esc_attr_x( '', 'label', 'designblocks' ) ?>" />
      </label>
      <!-- <div class="overlay-search"></div> -->
    </form>
    <a class="lb-x" href="#"></a>