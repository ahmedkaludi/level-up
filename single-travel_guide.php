<?php
get_header();
global $current_view, $acc_list, $tour_list, $before_article, $after_article, $trav_options;
$current_view = isset( $_REQUEST['view'] )?$_REQUEST['view']:'list';
$acc_page = ( isset( $_REQUEST['acc_page'] ) && ( is_numeric( $_REQUEST['acc_page'] ) ) && ( $_REQUEST['acc_page'] >= 1 ) )?($_REQUEST['acc_page']):1;
$tour_page = ( isset( $_REQUEST['tour_page'] ) && ( is_numeric( $_REQUEST['tour_page'] ) ) && ( $_REQUEST['tour_page'] >= 1 ) )?($_REQUEST['tour_page']):1;
$acc_per_page = ( isset( $trav_options['acc_posts'] ) && is_numeric($trav_options['acc_posts']) )?$trav_options['acc_posts']:12;
$tour_per_page = ( isset( $trav_options['tour_posts'] ) && is_numeric($trav_options['tour_posts']) )?$trav_options['tour_posts']:12;
$acc_offset = ( $acc_page - 1 ) * $acc_per_page;
$tour_offset = ( $tour_page - 1 ) * $tour_per_page;

$order_array = array( 'ASC', 'DESC' );
$order_by_array = array( 'name', 'price', 'rating' );
$order_defaults = array(
		'name' => 'ASC',
		'price' => 'ASC',
		'rating' => 'DESC'
	);
$order_by = ( isset( $_REQUEST['order_by'] ) && in_array( $_REQUEST['order_by'], $order_by_array ) )?$_REQUEST['order_by']:'name';
$order = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $order_array ) )?$_REQUEST['order']:'ASC';

if ( have_posts() ) {
	while ( have_posts() ) : the_post();
		$post_id = get_the_ID();
		$g_info = get_post_meta( $post_id, 'trav_tg_g_info', true );
		$sports = get_post_meta( $post_id, 'trav_tg_sports', true );
		$culture = get_post_meta( $post_id, 'trav_tg_culture', true );
		$nightlife = get_post_meta( $post_id, 'trav_tg_nightlife', true );

		$acc_list = array();
		$tour_list = array();
		if ( empty( $trav_options['disable_acc'] ) ) {
			$acc_list = trav_acc_get_accs_by_tg_id( $post_id, $order_by, $order, $acc_offset, $acc_per_page );
			$acc_count = trav_acc_count_accs_by_tg_id( $post_id );
		}
		if ( empty( $trav_options['disable_tour'] ) ) {
			$tour_list = trav_tour_get_tours_by_tg_id( $post_id, $order_by, $order, $tour_offset, $tour_per_page );
			$tour_count = trav_tour_count_tours_by_tg_id( $post_id );
		}
		?>

		<section id="content">
			<div class="container">
				<div class="row">
					<div id="main" class="col-md-9">
						<div class="tab-container style1" id="travel-guide">
							<ul class="tabs full-width">
							<?php
								$active_class = ' class="active"';
								if ( ! empty( $g_info ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-info" data-toggle="tab">' . __( 'General Information', 'trav' ) . '</a></li>';
									$active_class = '';
								}
								if ( ! empty( $sports ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-sports" data-toggle="tab">' . __( 'Sports', 'trav' ) . '</a></li>';
									$active_class = '';
								}
								if ( ! empty( $culture ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-culture-history" data-toggle="tab">' . __( 'Culture and History', 'trav' ) . '</a></li>';
									$active_class = '';
								}
								if ( ! empty( $nightlife ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-nightlife" data-toggle="tab">' . __( 'Nightlife', 'trav' ) . '</a></li>';
									$active_class = '';
								}
								if ( ! empty( $acc_list ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-hotels" data-toggle="tab">' . __( 'Accommodations', 'trav' ) . '</a></li>';
									$active_class = '';
								}
								if ( ! empty( $tour_list ) ) {
									echo '<li' . $active_class . '><a href="#travel-guide-tours" data-toggle="tab">' . __( 'Tours', 'trav' ) . '</a></li>';
									$active_class = '';
								}
							?>
							</ul>
							<div class="tab-content">
								<?php $active_class = ' active in'; ?>
								<?php if ( ! empty( $g_info ) ) { ?>
									<div class="tab-pane fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-info">
										<?php echo balanceTags( do_shortcode( $g_info ) ); ?>
									</div>
								<?php } ?>
								<?php if ( ! empty( $sports ) ) { ?>
									<div class="tab-pane fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-sports">
										<?php echo balanceTags( do_shortcode( $sports ) ); ?>
									</div>
								<?php } ?>
								<?php if ( ! empty( $culture ) ) { ?>
									<div class="tab-pane fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-culture-history">
										<?php echo balanceTags( do_shortcode( $culture ) ); ?>
									</div>
								<?php } ?>
								<?php if ( ! empty( $nightlife ) ) { ?>
									<div class="tab-pane fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-nightlife">
										<?php echo balanceTags( do_shortcode( $nightlife ) ); ?>
									</div>
								<?php } ?>
								<?php if ( ! empty( $acc_list ) ) { ?>
									<div class="tab-pane gray-bg fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-hotels">
										<div class="sort-by-section clearfix box">
											<h4 class="sort-by-title block-sm"><?php _e( 'Sort results by:', 'trav' ); ?></h4>
											<ul class="sort-bar clearfix block-sm">
												<?php
													foreach( $order_by_array as $key ) {
														$active = '';
														$def_order = $order_defaults[ $key ];
														if ( $key == $order_by ) {
															$active = ' active';
															$def_order = ( $order == 'ASC' )?'DESC':'ASC';
														}
														echo '<li class="sort-by-' . esc_attr( $key . $active ) . '"><a class="sort-by-container" href="' . esc_url( add_query_arg( array( 'order_by' => $key, 'order' => $def_order, 'acc_page' => 1 ) ) ) . '#travel-guide-hotels"><span>' . ( __( $key, 'trav' ) ) . '</span></a></li>';
													}
												?>
											</ul>
											<ul class="swap-tiles clearfix block-sm">
												<?php
													$views = array( 'list' => __( 'List View', 'trav' ),
																	'grid' => __( 'Grid View', 'trav' ),
																	'block' => __( 'Block View', 'trav' )
																);
													$params = $_GET;
													foreach( $views as $view => $label ) {
														$active = ( $view == $current_view )?' active':'';
														echo '<li class="swap-' . esc_attr( $view ) . $active . '">';
														echo '<a href="' . esc_url( add_query_arg( array( 'view' => $view ) ) ) . '#travel-guide-hotels" title="' . ( $label ) . '"><i class="soap-icon-' . esc_attr( $view ) . '"></i></a>';
														echo '</li>';
													}
												?>
											</ul>
										</div>
										<div class="hotel-list list-wrapper">
											<?php
												if ( $current_view == 'block' ) {
													echo '<div class="row image-box listing-style2 add-clearfix">';
													$before_article = '<div class="col-sms-6 col-sm-6 col-md-4">';
													$after_article = '</div>';
												} elseif ( $current_view == 'grid' ) {
													echo '<div class="row image-box hotel listing-style1 add-clearfix">';
													$before_article = '<div class="col-sm-6 col-md-4">';
													$after_article = '</div>';
												} else {
													echo '<div class="listing-style3 image-box hotel">';
													$before_article = '';
													$after_article = '';
												}
												trav_get_template( 'accommodation-list.php', '/templates/accommodation/');
											?>
											</div>
											<?php if ( ! empty( $trav_options['ajax_pagination'] ) ) { ?>
												<?php if ( count( $acc_list ) >= $acc_per_page ) { ?>
													<a href="<?php echo esc_url( add_query_arg( array( 'acc_page' => ( $acc_page + 1 ) ) ) ); ?>" class="uppercase full-width button btn-large btn-load-more-accs" data-view="<?php echo esc_attr( $current_view ); ?>" data-search-params="<?php echo wp_kses_post( http_build_query($_GET, '', '&amp;') );?>"><?php echo __( 'load more listing', 'trav' ) ?></a>
												<?php } ?>
											<?php } else {
												unset( $_GET['acc_page'] );
												$pagenum_link = strtok( $_SERVER["REQUEST_URI"], '?' ) . '%_%';
												$total = ceil( $acc_count / $acc_per_page );
												$args = array(
													'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
													'total' => $total,
													'format' => '?acc_page=%#%',
													'current' => $acc_page,
													'show_all' => false,
													'prev_next' => true,
													'prev_text' => __('Previous', 'trav'),
													'next_text' => __('Next', 'trav'),
													'end_size' => 1,
													'mid_size' => 2,
													'type' => 'list',
													'add_args' => $_GET,
												);
												$add_sharp = create_function('$link', 'return $link . "#travel-guide-hotels";');  
												add_filter( 'paginate_links', $add_sharp );
												echo paginate_links( $args );
											} ?>
										</div>
									</div>
								<?php } ?>
								<?php if ( ! empty( $tour_list ) ) { ?>
									<div class="tab-pane gray-bg fade<?php echo esc_attr( $active_class ); $active_class=''; ?>" id="travel-guide-tours">
										<div class="sort-by-section clearfix box">
											<h4 class="sort-by-title block-sm"><?php _e( 'Sort results by:', 'trav' ); ?></h4>
											<ul class="sort-bar clearfix block-sm">
												<?php
													$order_by_array = array( 'name', 'price' );
													foreach( $order_by_array as $key ) {
														$active = '';
														$def_order = $order_defaults[ $key ];
														if ( $key == $order_by ) {
															$active = ' active';
															$def_order = ( $order == 'ASC' )?'DESC':'ASC';
														}
														echo '<li class="sort-by-' . esc_attr( $key . $active ) . '"><a class="sort-by-container" href="' . esc_url( add_query_arg( array( 'order_by' => $key, 'order' => $def_order, 'tour_page' => 1 ) ) ) . '#travel-guide-tours"><span>' . ( __( $key, 'trav' ) ) . '</span></a></li>';
													}
												?>
											</ul>
											<ul class="swap-tiles clearfix block-sm">
												<?php
													$views = array( 'list' => __( 'List View', 'trav' ),
																	'grid' => __( 'Grid View', 'trav' ),
																	// 'block' => __( 'Block View', 'trav' )
																);
													$params = $_GET;
													foreach( $views as $view => $label ) {
														$active = ( $view == $current_view )?' active':'';
														echo '<li class="swap-' . esc_attr( $view ) . $active . '">';
														echo '<a href="' . esc_url( add_query_arg( array( 'view' => $view ) ) ) . '#travel-guide-tours" title="' . ( $label ) . '"><i class="soap-icon-' . esc_attr( $view ) . '"></i></a>';
														echo '</li>';
													}
												?>
											</ul>
										</div>
										<div class="tour-list list-wrapper">
											<?php
												if ( $current_view == 'block' ) {
													echo '<div class="tour-packages listing-style2 row add-clearfix image-box">';
													$before_article = '<div class="col-sm-6 col-md-4">';
													$after_article = '</div>';
												} elseif ( $current_view == 'grid' ) {
													echo '<div class="tour-packages listing-style1 row add-clearfix image-box">';
													$before_article = '<div class="col-sm-6 col-md-4">';
													$after_article = '</div>';
												} else {
													echo '<div class="tour-packages image-box listing-style3">';
													$before_article = '';
													$after_article = '';
												}
												trav_get_template( 'tour-list.php', '/templates/tour/');
											?>
											</div>
											<?php if ( ! empty( $trav_options['ajax_pagination'] ) ) { ?>
												<?php if ( count( $tour_list ) >= $tour_per_page ) { ?>
													<a href="<?php echo esc_url( add_query_arg( array( 'tour_page' => ( $tour_page + 1 ) ) ) ); ?>" class="uppercase full-width button btn-large btn-load-more-accs" data-view="<?php echo esc_attr( $current_view ); ?>" data-search-params="<?php echo wp_kses_post( http_build_query($_GET, '', '&amp;') );?>"><?php echo __( 'load more listing', 'trav' ) ?></a>
												<?php } ?>
											<?php } else {
												unset( $_GET['tour_page'] );
												$pagenum_link = strtok( $_SERVER["REQUEST_URI"], '?' ) . '%_%';
												$total = ceil( $tour_count / $tour_per_page );
												$args = array(
													'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
													'total' => $total,
													'format' => '?tour_page=%#%',
													'current' => $tour_page,
													'show_all' => false,
													'prev_next' => true,
													'prev_text' => __('Previous', 'trav'),
													'next_text' => __('Next', 'trav'),
													'end_size' => 1,
													'mid_size' => 2,
													'type' => 'list',
													'add_args' => $_GET,
												);
												$add_sharp = create_function('$link', 'return $link . "#travel-guide-tours";');  
												add_filter( 'paginate_links', $add_sharp );
												echo paginate_links( $args );
											} ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="sidebar col-md-3">
						<?php generated_dynamic_sidebar(); ?>
					</div>
				</div>
			</div>
		</section>
<?php endwhile;
}
get_footer();