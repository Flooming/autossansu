<?php 

	if(isset($unique_model) && !empty($unique_model)){
		$unique_model_name = $unique_model;
	}else{
		$unique_model_name = '';
	}

?>


<div class="rental_inventory redq-remove-rows sort ui-state-default postbox" style="background: none; border: none;">

	<div class="inventory-bar redq-show-bar">
		<h4 class="redq-headings"><?php echo esc_attr($unique_model_name); ?>
			<button style="float: right" type="button" class="remove_row button"><i class="fa fa-trash-o"></i><?php _e('Remove','redq-rental'); ?></button>
			<a type="button" class="handlediv button-link" aria-expanded="true">
				<span class="screen-reader-text">Toggle panel: Product Image</span>
				<span class="handlediv toggle-indicator show-or-hide" title="Click to toggle"></span>
			</a>
		</h4>		
	</div>
	
	<div class="rental-inventory redq-hide-row" style="margin: 15px;">
	<?php
		
		woocommerce_wp_text_input( 
			array( 
				'id' => 'rental_products_unique_name', 
				'name' => 'redq_rental_products_unique_name[]',
				'label' => __( 'Unique product model', 'redq-rental' ), 
				'desc_tip' => 'true', 
				'description' => sprintf( __( 'Hourly price will be applicabe if booking or rental days min 1day', 'redq-rental' ) ),
				'placeholder' => __( 'Unique product model', 'redq-rental' ), 
				'type' => 'text',
				'value' => $unique_model_name				
			) 
		);


		
	?>


	<?php
		if(isset($key) && $key >= 0){
			$resource_tax_key = $key; 
			$person_post_key = $key; 
			$attribute_tax_key = $key;
			$feature_tax_key = $key; 
			$sd_tax_key = $key;
			$pickup_tax_key = $key;
			$dropoff_tax_key = $key;
		} 			
	?>	



	<!-- Select Pickup location for inventory models start -->
	<p  class="form-field">
		<?php if(isset($pickup_tax_key) && $pickup_tax_key >= 0): ?>		
			<label for="inventory-person">Select Pickup Locations</label>
			<?php 

				$pickup_terms = get_terms( 'pickup_location', array(
				    'hide_empty' => false,
				) );

				$pickup_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_pickup_terms = array();

				foreach ($pickup_identifiers as $pickup_key => $pickup_value) {				
					if($pickup_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('pickup_location')){
							$pickup_terms_per_post = wp_get_post_terms( $pickup_value['inventory_id'], 'pickup_location', $args );
						}

						if(isset($pickup_terms_per_post) && is_array($pickup_terms_per_post)){
							foreach ($pickup_terms_per_post as $term_key => $term_value) {
								$selected_pickup_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_pickup[<?php if(isset($pickup_tax_key)){ echo $pickup_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set pickup locations', 'rental' ); ?>" title="<?php esc_attr_e( 'Pickup Locations', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($pickup_terms) && !empty($pickup_terms)): ?>	
					<?php foreach($pickup_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_pickup_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>


	<!-- Select Dropoff location for inventory models start -->
	<p  class="form-field">
		<?php if(isset($dropoff_tax_key) && $dropoff_tax_key >= 0): ?>		
			<label for="inventory-dropoff-location">Select Drop-off Locations</label>
			<?php 

				$dropoff_terms = get_terms( 'dropoff_location', array(
				    'hide_empty' => false,
				) );

				$dropoff_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_dropoff_terms = array();

				foreach ($dropoff_identifiers as $dropoff_key => $dropoff_value) {				
					if($dropoff_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('dropoff_location')){
							$dropoff_terms_per_post = wp_get_post_terms( $dropoff_value['inventory_id'], 'dropoff_location', $args );
						}

						if(isset($dropoff_terms_per_post) && is_array($dropoff_terms_per_post)){
							foreach ($dropoff_terms_per_post as $term_key => $term_value) {
								$selected_dropoff_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_dropoff[<?php if(isset($dropoff_tax_key)){ echo $dropoff_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set drop-off locations', 'rental' ); ?>" title="<?php esc_attr_e( 'Dropoff Locations', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($dropoff_terms) && !empty($dropoff_terms)): ?>	
					<?php foreach($dropoff_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_dropoff_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>


	<!-- Select resource for inventory models start -->
	<p  class="form-field">
		
		<?php if(isset($resource_tax_key) && $resource_tax_key >= 0): ?>		
			<label for="inventory-resources">Select Resources</label>
			<?php 

				$resoruce_terms = get_terms( 'resource', array(
				    'hide_empty' => false,
				) );

				$resource_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_terms = array();

				foreach ($resource_identifiers as $resource_key => $resource_value) {				
					if($resource_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('resource')){
							$terms = wp_get_post_terms( $resource_value['inventory_id'], 'resource', $args );
						}

						if(isset($terms) && is_array($terms)){
							foreach ($terms as $term_key => $term_value) {
								$selected_terms[] = $term_value->slug;
							}
						}
					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_resources[<?php if(isset($resource_tax_key)){ echo $resource_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set payable  resources', 'rental' ); ?>" title="<?php esc_attr_e( 'Resources', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($resoruce_terms) && !empty($resoruce_terms)): ?>	
					<?php foreach($resoruce_terms as $akey => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>

	
	
	<!-- Select person for inventory models start -->
	<p  class="form-field">
		<?php if(isset($person_post_key) && $person_post_key >= 0): ?>		
			<label for="inventory-person">Select Person</label>
			<?php 

				$person_terms = get_terms( 'person', array(
				    'hide_empty' => false,
				) );

				$person_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_person_terms = array();

				foreach ($person_identifiers as $person_key => $person_value) {				
					if($person_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('person')){
							$person_terms_per_post = wp_get_post_terms( $person_value['inventory_id'], 'person', $args );
						}

						if(isset($person_terms_per_post) && is_array($person_terms_per_post)){
							foreach ($person_terms_per_post as $term_key => $term_value) {
								$selected_person_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_person[<?php if(isset($person_post_key)){ echo $person_post_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set Person', 'rental' ); ?>" title="<?php esc_attr_e( 'Person', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($person_terms) && !empty($person_terms)): ?>	
					<?php foreach($person_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_person_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>



	<!-- Select security deposite for inventory models start -->
	<p  class="form-field">
		<?php if(isset($sd_tax_key) && $sd_tax_key >= 0): ?>		
			<label for="inventory-security-deposite">Select Security Deposite</label>
			<?php 

				$sd_terms = get_terms( 'deposite', array(
				    'hide_empty' => false,
				) );

				$sd_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_sd_terms = array();

				foreach ($sd_identifiers as $sd_key => $sd_value) {				
					if($sd_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('deposite')){
							$sd_terms_per_post = wp_get_post_terms( $sd_value['inventory_id'], 'deposite', $args );
						}

						if(isset($sd_terms_per_post) && is_array($sd_terms_per_post)){
							foreach ($sd_terms_per_post as $term_key => $term_value) {
								$selected_sd_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_security_deposite[<?php if(isset($sd_tax_key)){ echo $sd_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set security deposites', 'rental' ); ?>" title="<?php esc_attr_e( 'Deposite', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($sd_terms) && !empty($sd_terms)): ?>	
					<?php foreach($sd_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_sd_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>


	<!-- Select attribute for inventory models start -->
	<p  class="form-field">
		<?php if(isset($attribute_tax_key) && $attribute_tax_key >= 0): ?>		
			<label for="inventory-attribute">Select Attributes</label>
			<?php 

				$attributes_terms = get_terms( 'attributes', array(
				    'hide_empty' => false,
				) );

				$attribute_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_attributes_terms = array();

				foreach ($attribute_identifiers as $attribute_key => $attribute_value) {				
					if($attribute_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('attributes')){
							$attributes_terms_per_post = wp_get_post_terms( $attribute_value['inventory_id'], 'attributes', $args );
						}

						if(isset($attributes_terms_per_post) && is_array($attributes_terms_per_post)){
							foreach ($attributes_terms_per_post as $term_key => $term_value) {
								$selected_attributes_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_attributes[<?php if(isset($attribute_tax_key)){ echo $attribute_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set attributes', 'rental' ); ?>" title="<?php esc_attr_e( 'Attributes', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($attributes_terms) && !empty($attributes_terms)): ?>	
					<?php foreach($attributes_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_attributes_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>


	<!-- Select features for inventory models start -->
	<p  class="form-field">
		<?php if(isset($feature_tax_key) && $feature_tax_key >= 0): ?>		
			<label for="inventory-feature">Select Features</label>
			<?php 

				$features_terms = get_terms( 'features', array(
				    'hide_empty' => false,
				) );

				$feature_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
				$selected_features_terms = array();

				foreach ($feature_identifiers as $feature_key => $feature_value) {				
					if($feature_value['title'] === $unique_model_name){
						$args = array(
									'orderby'           => 'name', 
									'order'             => 'ASC',
									'fields'      => 'all',       
								); 
						if(taxonomy_exists('features')){
							$features_terms_per_post = wp_get_post_terms( $feature_value['inventory_id'], 'features', $args );
						}

						if(isset($features_terms_per_post) && is_array($features_terms_per_post)){
							foreach ($features_terms_per_post as $term_key => $term_value) {
								$selected_features_terms[] = $term_value->slug;
							}
						}

					}
				}
			?>			
			<select multiple="multiple" class="inventory-resources"  style="width:350px" name="inventory_features[<?php if(isset($feature_tax_key)){ echo $feature_tax_key; } ?>][]" data-placeholder="<?php esc_attr_e( 'Set features', 'rental' ); ?>" title="<?php esc_attr_e( 'Features', 'rental' ) ?>" class="wc-enhanced-select">
				<?php if(is_array($features_terms) && !empty($features_terms)): ?>	
					<?php foreach($features_terms as $key => $value){ ?>
						<option value="<?php echo esc_attr($value->slug); ?>" <?php if(in_array($value->slug, $selected_features_terms)){ ?> selected <?php } ?> ><?php echo esc_attr($value->name); ?></option>
					<?php } ?>
				<?php endif; ?>
			</select>
		<?php endif; ?>		
	</p>


	


	</div>
</div>