<?php
/**
 * The template for displaying Search form
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package levelup
 */
 ?>

    <form role="search" method="get" class="levelup-fullscreen-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <label>
          <input type="search" class="search-field"
              placeholder="<?php echo esc_attr_x( 'Search...', 'label', 'level-up' ) ?>"
              value="<?php echo esc_attr( get_search_query() ); ?>" name="s"
              title="<?php echo esc_attr_x( 'Search for:', 'label', 'level-up' ) ?>" id="levelup-fullscreen-search-input"/>
      </label>
      <label class="search-button search-overlay">
          <i aria-hidden="true"></i>
      <input type="submit" class="search-submit"
          value="<?php echo esc_attr_x( '', 'label', 'level-up' ) ?>" />
      </label>
      <!-- <div class="overlay-search"></div> -->
    </form>
    <a class="lb-x" href="#"></a>