<?php $user_id = get_current_user_id(); ?>
<h2><?php echo __( 'Trips You have Booked!', 'trav' ) ?></h2>
<div class="filter-section gray-area clearfix">
	<form class="booking-status-filter">
		<input type="hidden" name="action" value="update_booking_list">
		<label class="radio radio-inline">
			<input type="radio" name="status" checked="checked" value="-1" />
			<?php echo __( 'All Types', 'trav' ) ?>
		</label>
		<label class="radio radio-inline">
			<input type="radio" name="status" value="1" />
			<?php echo __( 'UPCOMING', 'trav' ) ?>
		</label>
		<label class="radio radio-inline">
			<input type="radio" name="status" value="0" />
			<?php echo __( 'CANCELLED', 'trav' ) ?>
		</label>
		<label class="radio radio-inline">
			<input type="radio" name="status"  value="2"/>
			<?php echo __( 'COMPLETED', 'trav' ) ?>
		</label>
		<div class="pull-right col-md-6 action">
			<h5 class="pull-left no-margin col-md-4"><?php echo __( 'Sort results by' ,'trav' ) ?>:</h5>
			<input type="hidden" name="sort_by" value="created">
			<input type="hidden" name="order" value="desc">
			<button class="btn-small white gray-color" value="created"><?php echo __( 'DATE', 'trav' ) ?></button>
			<button class="btn-small white gray-color" value="total_price"><?php echo __( 'PRICE', 'trav' ) ?></button>
		</div>
	</form>
</div>
<div class="booking-history">
	<?php echo trav_get_user_booking_list( $user_id, -1, 'created', 'desc' ); ?>
</div>
<script>
tjq = jQuery;
tjq(document).ready(function(){

	tjq('.booking-status-filter input[name="status"]').change(function(){
		update_booking_list();
	});

	tjq('.booking-status-filter button').click(function(e){
		e.preventDefault();
		if ( tjq(this).siblings('input[name="sort_by"]').val() == tjq(this).val() ) {
			if ( tjq(this).siblings('input[name="order"]').val() == 'desc' ) {
				tjq(this).siblings('input[name="order"]').val('asc');
			} else {
				tjq(this).siblings('input[name="order"]').val('desc');
			}
		} else {
			tjq(this).siblings('input[name="sort_by"]').val(tjq(this).val());
			tjq(this).siblings('input[name="order"]').val('desc');
		}
		update_booking_list();
		return false;
	});

	function update_booking_list(){
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: tjq('.booking-status-filter').serialize(),
			success: function(response){
				if ( response.success == 1 ) {
					tjq('.booking-history').html(response.result);
				} else {
					tjq('.booking-history').html(response.result);
				}
			}
		});
	}
});
</script>