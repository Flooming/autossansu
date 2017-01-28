<?php

	if ( ! isset( $availability['type'] ) )
		$availability['type'] = 'custom_date';
?>

<tr>
	<td class="sort">&nbsp;</td>
	<td>
		<div class="select rental_availability_type">
			<select name="redq_rental_availability_type[]">
				<option value="custom_date" selected="selected"><?php _e( 'Custom date range', 'redq-rental' ); ?></option>
			</select>
		</div>
	</td>
	<td>
		<div class="from_date inventory-form-to-input">
			<input type="text" style="border: 1px solid #ddd;" class="date-picker" name="redq_rental_availability_from[]" value="<?php if ( !empty( $availability['from'] ) ) echo $availability['from'] ?>"/>
		</div>
	</td>
	<td>
		<div class="to_date inventory-form-to-input">
			<input type="text" style="border: 1px solid #ddd;" class="date-picker" name="redq_rental_availability_to[]" value="<?php if ( !empty( $availability['to'] ) ) echo $availability['to'] ?>" />
		</div>
	</td>
	<td>
		<div class="select">
			<select name="redq_availability_rentable[]">
				<option value="no" <?php selected( isset( $availability['rentable'] ) && $availability['rentable'] == 'no', true ) ?>><?php _e( 'Not', 'redq-rental' ) ;?></option>
				<!-- <option value="yes" <?php selected( isset( $availability['bookable'] ) && $availability['bookable'] == 'yes', true ) ?>><?php _e( 'Yes', 'redq-rental' ) ;?></option> -->
			</select>
		</div>
	</td>
	<td class="remove"><button type="btn" class=""><?php _e('delete','redq-rental'); ?></button></td>
</tr>