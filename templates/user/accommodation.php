<?php $user_id = get_current_user_id(); ?>
<h2><?php echo __( 'Your Accommodations', 'trav' ); ?></h2>
<div class="row image-box listing-style2 add-clearfix">
	<?php
	global $trav_options, $acc_list, $before_article, $after_article, $current_view;
	$acc_list = array();
	$acc_per_page = ( isset( $trav_options['acc_posts'] ) && is_numeric($trav_options['acc_posts']) )?$trav_options['acc_posts']:12;
	$acc_page = ( isset( $_REQUEST['acc_page'] ) && ( is_numeric( $_REQUEST['acc_page'] ) ) && ( $_REQUEST['acc_page'] >= 1 ) )?($_REQUEST['acc_page']):1;
	$args = array(
		'post_type'        => 'accommodation',
		'suppress_filters' => 0,
		'post_status' => 'publish',
	);
	$accs = get_posts( $args );
	$count = count( $accs );
	$acc_offset = ( $acc_page - 1 ) * $acc_per_page;
	for ( $i = $acc_offset; $i < $acc_page * $acc_per_page; $i++ ) {
		if ( ! isset( $accs[$i] ) ) break;
		$acc = $accs[$i];
		$acc_list[] = $acc->ID;
	}
	if ( ! empty( $acc_list ) ) {
		$current_view = 'block';
		$before_article = '<div class="col-sm-6 col-md-4">';
		$after_article = '</div>';
		trav_get_template( 'accommodation-list.php', '/templates/accommodation/');
	} else {
		echo __( 'Your wishlist is empty.', 'trav' );
	}
	?>
</div>