<?php 

/**
 * Class WC_Redq_Rental_Post_Types
 *
 *
 * @author      RedQTeam
 * @category    Admin
 * @package     Userplace\Admin
 * @version     1.0.3
 * @since       1.0.3
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

class WC_Redq_Rental_Post_Types {

	public function __construct(){	
		add_action( 'init', array( $this, 'redq_rental_register_post_types' ));
		add_action( 'save_post', array($this, 'redq_save_inventory_post'));
		add_action( 'add_meta_boxes', array($this, 'redq_register_meta_boxes'));
	}


	/**
	 * Hande Post Type, Taxonomy, Term Meta
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_rental_register_post_types(){
		
		$labels = array(
			'name'               => _x( 'Inventory', 'post type general name', 'redq-rental' ),
			'singular_name'      => _x( 'Inventory', 'post type singular name', 'redq-rental' ),
			'menu_name'          => _x( 'Inventory', 'admin menu', 'redq-rental' ),
			'name_admin_bar'     => _x( 'Inventory', 'add new on admin bar', 'redq-rental' ),
			'add_new'            => _x( 'Add New', 'inventory', 'redq-rental' ),
			'add_new_item'       => __( 'Add New Inventory', 'redq-rental' ),
			'new_item'           => __( 'New Inventory', 'redq-rental' ),
			'edit_item'          => __( 'Edit Inventory', 'redq-rental' ),
			'view_item'          => __( 'View Inventory', 'redq-rental' ),
			'all_items'          => __( 'All inventory', 'redq-rental' ),
			'search_items'       => __( 'Search inventory', 'redq-rental' ),
			'parent_item_colon'  => __( 'Parent inventory:', 'redq-rental' ),
			'not_found'          => __( 'No inventory found.', 'redq-rental' ),
			'not_found_in_trash' => __( 'No inventory found in Trash.', 'redq-rental' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'redq-rental' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'inventory' ),
			'capability_type'    => 'post',
			'menu_icon'			 => 'dashicons-image-filter',	
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 57,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
		);

		register_post_type( 'inventory', $args );

		$this->redq_resgister_inventory_taxonomies('resource','inventory');
		$this->redq_resgister_inventory_taxonomies('person','inventory');
		$this->redq_resgister_inventory_taxonomies('deposite','inventory');
		$this->redq_resgister_inventory_taxonomies('attributes','inventory');
		$this->redq_resgister_inventory_taxonomies('features','inventory');
		$this->redq_resgister_inventory_taxonomies('pickup location','inventory');
		$this->redq_resgister_inventory_taxonomies('dropoff location','inventory');
		$this->redq_resgister_inventory_taxonomies('car company','product');

		$this->redq_create_all_inventroy_term_meta();		

	}



	/**
	 * Create all term meta
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_create_all_inventroy_term_meta(){
		
		//Term meta for resouce taxonomy
		$resource_cost_args = array(
				'title' => __( 'Resource Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_resource_cost_termmeta',
				'column_name' => __( 'R.Cost','redq-rental' ),
				'placeholder' => __( 'Resource Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$resource_cost = $this->redq_register_inventory_text_term_meta('resource',$resource_cost_args);

		$price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),
					
				),
			);
		$price_applicable = $this->redq_register_inventory_select_term_meta('resource',$price_applicable_args);	


		$hourly_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$hourly_cost = $this->redq_register_inventory_text_term_meta('resource',$hourly_cost_args);


		

		//Term meta for person taxonomy
		$payable_person_args = array(
				'title' => __( 'Choose payable or not', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_person_payable_or_not',
				'column_name' => __( 'Payable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => 'Yes'
						),
					'1' => array(
							'key' => 'no',
							'value' => 'No'
						),
					
				),
			);
		$payable_person = $this->redq_register_inventory_select_term_meta('person',$payable_person_args);	

		$person_cost_args = array(
				'title' => __( 'Person Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_person_cost_termmeta',
				'column_name' => __( 'P.Cost', 'redq-rental' ),
				'placeholder' => __( 'Person Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$person_cost = $this->redq_register_inventory_text_term_meta('person',$person_cost_args);

		$person_price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_person_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),
					
				),
			);
		$person_price_applicable = $this->redq_register_inventory_select_term_meta('person',$person_price_applicable_args);	

		$hourly_perosn_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_peroson_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$hourly_person_cost = $this->redq_register_inventory_text_term_meta('person',$hourly_perosn_cost_args);



		//Term meta for securtity deposit taxonomy
		$security_desposite_cost_args = array(
				'title' => __( 'Security Deposite Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_cost_termmeta',
				'column_name' => __( 'S.D.Cost', 'redq-rental' ),
				'placeholder' => __( 'Security Deposite Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('deposite',$security_desposite_cost_args);

		$price_applicable_args = array(
				'title' => __( 'Price Applicable', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_price_applicable_term_meta',
				'column_name' => __( 'Applicable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'one_time',
							'value' => 'One Time'
						),
					'1' => array(
							'key' => 'per_day',
							'value' => 'Per Day'
						),
					
				),
			);
		$price_applicable = $this->redq_register_inventory_select_term_meta('deposite',$price_applicable_args);	

		$hourly_cost_args = array(
				'title' => __( 'Hourly Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_hourly_cost_termmeta',
				'column_name' => __( 'H.Cost', 'redq-rental' ),
				'placeholder' => __( 'Hourly Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$hourly_cost = $this->redq_register_inventory_text_term_meta('deposite',$hourly_cost_args);

		

		$price_clickable_args = array(
				'title' => __( 'Security Deposite Clickable', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_sd_price_clickable_term_meta',
				'column_name' => __( 'Clickable', 'redq-rental' ),
				'options' => array(
					'0' => array(
							'key' => 'yes',
							'value' => 'Yes'
						),
					'1' => array(
							'key' => 'no',
							'value' => 'No'
						),
					
				),
			);
		$price_clickable = $this->redq_register_inventory_select_term_meta('deposite',$price_clickable_args);	



		//Term meta for pickup location taxonomy
		$pickup_locaiton_args = array(
				'title' => __( 'Pickup Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_pickup_cost_termmeta',
				'column_name' => __( 'Pickup Cost', 'redq-rental' ),
				'placeholder' => __( 'Pickup Location Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('pickup_location',$pickup_locaiton_args);

		
		//Term meta for dropoff location taxonomy
		$dropoff_locaiton_args = array(
				'title' => __( 'Dropoff Cost', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_dropoff_cost_termmeta',
				'column_name' => __( 'Dropoff Cost', 'redq-rental' ),
				'placeholder' => __( 'Dropoff Location Cost', 'redq-rental' ),
				'text_type' => 'price',
			);
		$security_desposite_cost = $this->redq_register_inventory_text_term_meta('dropoff_location',$dropoff_locaiton_args);


		//Term meta for attributes taxonomy
		$attributes_name_args = array(
				'title' => __( 'Attribute Name', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_attribute_name',
				'column_name' => __( 'A.Name', 'redq-rental' ),
				'placeholder' => __( 'Attribute Name', 'redq-rental' ),
			);
		$attribute_name = $this->redq_register_inventory_text_term_meta('attributes',$attributes_name_args);

		$attributes_value_args = array(
				'title' => __( 'Attribute Value', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_attribute_value',
				'column_name' => __( 'A.Value', 'redq-rental' ),
				'placeholder' => __( 'Attribute Value', 'redq-rental' ),
			);
		$attribute_value = $this->redq_register_inventory_text_term_meta('attributes',$attributes_value_args);

		$attributes_icon_name = array(
				'title' => __( 'Attribute Icon', 'redq-rental' ),
				'type'  => 'text',
				'id'    => 'inventory_attribute_icon',
				'column_name' => __( 'Icon', 'redq-rental' ),
				'placeholder' => __( 'Font-awesome icon Ex. fa fa-car', 'redq-rental' ),
			);
		$attribute_icon = $this->redq_register_inventory_icon_term_meta('attributes',$attributes_icon_name);
	



	}



	/**
	 * Create all taxonomies
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_resgister_inventory_taxonomies($taxonomy, $post_type){

		$labels = array(
			'name'              => _x( ucwords($taxonomy), 'taxonomy general name' ),
			'singular_name'     => _x( $taxonomy, 'taxonomy singular name' ),
			'search_items'      => __( 'Search '.$taxonomy.'' ),
			'all_items'         => __( 'All '.$taxonomy.'' ),
			'parent_item'       => __( 'Parent '.$taxonomy.'' ),
			'parent_item_colon' => __( 'Parent '.$taxonomy.':' ),
			'edit_item'         => __( 'Edit '.$taxonomy.'' ),
			'update_item'       => __( 'Update '.$taxonomy.'' ),
			'add_new_item'      => __( 'Add New '.$taxonomy.'' ),
			'new_item_name'     => __( 'New '.$taxonomy.' Name' ),
			'menu_name'         => ucwords($taxonomy),
		);
		
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'public'            => true,
			'rewrite'           => array( 'slug' => $taxonomy ),
		);
		
		register_taxonomy( str_replace(' ', '_', $taxonomy), $post_type, $args );

	}



	/**
	 * Call text type term meta
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_register_inventory_text_term_meta($taxonomy, $args){
		$text_term_meta = 'Rnb_Term_Meta_Generator_Text';
		new $text_term_meta($taxonomy, $args);		
	}


	/**
	 * Call icon type term meta
	 *  
	 * @author RedQTeam
	 * @version 2.0.3
	 * @since 2.0.3
	 */
	public function redq_register_inventory_icon_term_meta($taxonomy, $args){
		$icon_term_meta = 'Rnb_Term_Meta_Generator_Icon';
		new $icon_term_meta($taxonomy, $args);		
	}



	/**
	 * Call select type term meta
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_register_inventory_select_term_meta($taxonomy, $args){
		$select_term_meta = 'Rnb_Term_Meta_Generator_Select';
		new $select_term_meta($taxonomy, $args);	
	}


	/**
	 * Handle Save Meta for inventory
	 *  
	 * @author RedQTeam
	 * @version 2.0.0
	 * @since 2.0.0
	 */
	public function redq_save_inventory_post($post_id){
		$user_id = get_current_user_id();
		$intialize_block_dates_and_times = array();

		// Create dynamic inventory child post , resouces terms , deposit terms, location and person
		if(isset($_POST['redq_rental_products_unique_name'])){

			$unique_names = get_post_meta($post_id, 'redq_inventory_products_quique_models', true);

		    $args = array(
		    		'post_type' => 'inventory',
		    		'posts_per_page' => -1
		    	);

		    $data = get_posts($args);
		    $previous_ids = array();
		    $store = array();

		    foreach ($data as $key => $value) {
		    	array_push($previous_ids, $value->ID);
		    }

		    $child_ids = get_post_meta($post_id, 'inventory_child_posts', true);
		    $resource_identifier = array();

		    if(isset($unique_names) && !empty($unique_names)){
			    foreach ($unique_names as $key => $value) {
			    	
			    	$id = '';
			    	
			    	if(isset($child_ids[$key]) && !empty($child_ids[$key])){
			    		$result = array_intersect($previous_ids, array($child_ids[$key]));			    	

				    	$data_availability = array();

				    	foreach ($result as $result_key => $result_value) {
				    		$id = $result_value;
				    	}	
			    	}

			    				    	

			    	$defaults = array(
				    	'ID' => $id,
				        'post_author' => $user_id,
				        'post_content' => $value,
				        'post_content_filtered' => '',
				        'post_title' => $value,
				        'post_excerpt' => '',
				        'post_status' => 'publish',
				        'post_type' => 'inventory',
				        'comment_status' => '',
				        'ping_status' => '',
				        'post_password' => '',
				        'to_ping' =>  '',
				        'pinged' => '',
				        'post_parent' => $post_id,
				        'menu_order' => 0,
				        'guid' => '',
				        'import_id' => 0,
				        'context' => '',
				    );

				    $inventory_id = wp_insert_post( $defaults );
				    $resource_identifier[$inventory_id]['title'] = $value;
				    $resource_identifier[$inventory_id]['inventory_id'] = $inventory_id;

				    array_push($store, $inventory_id);

				    //Set terms for pickup location taxonomy
				    if(isset($_POST['inventory_pickup'])){
				    	if(isset($_POST['inventory_pickup'][$key]) && !empty($_POST['inventory_pickup'][$key])){
				    		$pickup_terms = $_POST['inventory_pickup'][$key];
				    		$pickup_taxonomy_ids = wp_set_object_terms( $inventory_id, $pickup_terms, 'pickup_location' );
				   		}
				    }else{
				    	$pickup_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'pickup_location' );
				    }

				    //Set terms for dropoff location taxonomy
				    if(isset($_POST['inventory_dropoff'])){
				    	if(isset($_POST['inventory_dropoff'][$key]) && !empty($_POST['inventory_dropoff'][$key])){
				    		$dropoff_terms = $_POST['inventory_dropoff'][$key];
				    		$dropoff_taxonomy_ids = wp_set_object_terms( $inventory_id, $dropoff_terms, 'dropoff_location' );
				    	} 				    	
				    }else{
				    	$dropoff_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'dropoff_location' );
				    }

				    //Set terms for resource taxonomy
				    if(isset($_POST['inventory_resources'])){
				    	if(isset($_POST['inventory_resources'][$key]) && !empty($_POST['inventory_resources'][$key])){
				    		$terms = $_POST['inventory_resources'][$key];
				    		$term_taxonomy_ids = wp_set_object_terms( $inventory_id, $terms, 'resource' );
				    	}
				    }else{
				    	$term_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'resource' );
				    }

				    //Set terms for person taxonomy				    
				    if(isset($_POST['inventory_person'])){
				    	if(isset($_POST['inventory_person'][$key]) && !empty($_POST['inventory_person'][$key])){
				    		$person_terms = $_POST['inventory_person'][$key];
				    		$person_term_taxonomy_ids = wp_set_object_terms( $inventory_id, $person_terms, 'person' );	
				    	}				    	
				    }else{
				    	$person_term_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'person' );	
				    }

				    //Set terms for security deposite taxonomy
				    if(isset($_POST['inventory_security_deposite'])){
				    	if(isset($_POST['inventory_security_deposite'][$key]) && !empty($_POST['inventory_security_deposite'][$key])){
				    		$terms = $_POST['inventory_security_deposite'][$key];
				    		$term_taxonomy_ids = wp_set_object_terms( $inventory_id, $terms, 'deposite' );
				    	}				    	
				    }else{
				    	$term_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'deposite' );
				    }


				    //Set terms for attributes taxonomy				    
				    if(isset($_POST['inventory_attributes'])){
				    	if(isset($_POST['inventory_attributes'][$key]) && !empty($_POST['inventory_attributes'][$key])){
				    		$attributes_terms = $_POST['inventory_attributes'][$key];
				    		$attributes_term_taxonomy_ids = wp_set_object_terms( $inventory_id, $attributes_terms, 'attributes' );
				    	}
				    }else{
				    	$attributes_term_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'attributes' );
				    }

				    //Set terms for features taxonomy				    
				    if(isset($_POST['inventory_features'])){
				    	if(isset($_POST['inventory_features'][$key]) && !empty($_POST['inventory_features'][$key])){
				    		$features_terms = $_POST['inventory_features'][$key];
				    		$features_term_taxonomy_ids = wp_set_object_terms( $inventory_id, $features_terms, 'features' );
				    	}
				    }else{
				    	$features_term_taxonomy_ids = wp_set_object_terms( $inventory_id, '', 'features' );
				    }

				    $intialize_rental_availability = array();

				    
				    if(empty($id)){
				    	
				    	$intialize_rental_availability['block_dates'] = array();
				    	$intialize_rental_availability['block_times'] = array();
				    	$intialize_rental_availability['only_block_dates'] = array();	
				    	$intialize_block_dates_and_times[$inventory_id] = $intialize_rental_availability;	

				    	$intialize_block_dates_and_times = get_post_meta($post_id, 'redq_block_dates_and_times', true);
				    	$intialize_block_dates_and_times[$inventory_id] = $intialize_rental_availability;


				    	update_post_meta($post_id, 'redq_block_dates_and_times', $intialize_block_dates_and_times);
				    }
				    

			    }
			}


			update_post_meta($post_id, 'resource_identifier', $resource_identifier);	 
		    update_post_meta($post_id, 'inventory_child_posts', $store);
		}




		// Handle and create date availability array
		$rental_availability = array();
		if(isset($_POST['redq_rental_availability_type']) && isset($_POST['redq_rental_availability_from']) && isset($_POST['redq_rental_availability_to']) && isset($_POST['redq_availability_rentable'])){
			$availability_type = $_POST['redq_rental_availability_type'];
			$availability_from = $_POST['redq_rental_availability_from'];
			$availability_to = $_POST['redq_rental_availability_to'];
			$availability_rentable = $_POST['redq_availability_rentable'];
			for($i=0; $i<sizeof($availability_type); $i++){
				$rental_availability[$i]['type'] = $availability_type[$i];
				$rental_availability[$i]['from'] = $availability_from[$i];
				$rental_availability[$i]['to'] = $availability_to[$i];
				$rental_availability[$i]['rentable'] = $availability_rentable[$i];
				$rental_availability[$i]['post_id'] = get_the_ID();
			}				
		}		


		// Handle and create time availability array
		$rental_time_availability = array();
		if(isset($_POST['redq_rental_time_availability_from_time']) && isset($_POST['redq_rental_time_availability_to_time']) && isset($_POST['redq_time_availability_rentable'])){
			$time_availability_chosen_date = $_POST['redq_rental_time_availability_date'];
			$time_availability_from = $_POST['redq_rental_time_availability_from_time'];
			$time_availability_to = $_POST['redq_rental_time_availability_to_time'];
			$time_availability_rentable = $_POST['redq_time_availability_rentable'];
			for($i=0; $i<sizeof($time_availability_from); $i++){
				$rental_time_availability[$i]['date'] = $time_availability_chosen_date[$i];
				$rental_time_availability[$i]['type'] = 'custom_time';
				$rental_time_availability[$i]['from'] = $time_availability_from[$i];
				$rental_time_availability[$i]['to'] = $time_availability_to[$i];
				$rental_time_availability[$i]['rentable'] = $time_availability_rentable[$i];
				$rental_time_availability[$i]['post_id'] = get_the_ID();
			}				
		}


		$new = new RedQ_Rental_And_Bookings();	
		$booked_dates_aras = array();
		$only_block_dates = array();
		$parent_id = wp_get_post_parent_id( get_the_ID() );
		$output_date_format    = get_post_meta($parent_id,'redq_calendar_date_format',true);        
		$european_date_format  = get_post_meta($parent_id, 'redq_choose_european_date_format', true);		
		$block_dates_and_times = get_post_meta($parent_id, 'redq_block_dates_and_times', true);		
	

		// Handle all block days and will wrok only for inventroy post crate or update
		if(get_post_type($post_id)=== 'inventory' ){
		 	if(isset($rental_availability) && !empty($rental_availability)){
				update_post_meta( $post_id, 'redq_rental_availability', $rental_availability );
				foreach ($rental_availability as $key => $value) {
		        	$booked_dates_aras[] = $new->manage_all_dates($value['from'], $value['to'], $european_date_format, $output_date_format);
		        	
		        } 
		 	}
		}


		$block_times = array();
		$combined_block_times = array();
		$time_flag = 0;
		$block_times_merge = array();

		// Handle all block times and will wrok only for inventroy post crate or update
		if(get_post_type($post_id)=== 'inventory' ){
		 	if(isset($rental_time_availability) && !empty($rental_time_availability)){
				update_post_meta( $post_id, 'redq_rental_time_availability', $rental_time_availability );	
				foreach ($rental_time_availability as $time_key => $time_value) {
            		$block_times = $new->manage_all_times($time_value['date'] , $time_value['from'], $time_value['to']);
            		array_push($combined_block_times, $block_times);                		
            	}			
		 	}
		}  

		if(isset($combined_block_times) && !empty($combined_block_times)){
			foreach ($combined_block_times as $time_key => $time_value) {
				if($time_flag === 0){
					$first_time = $time_value;
					$time_flag = 1;
				}
				$block_times_merge = array_merge_recursive($first_time, $time_value);	
			}
		} 


		foreach ($booked_dates_aras as $index => $booked_dates_aras) {
			foreach ($booked_dates_aras as $key => $value) {
				$only_block_dates[] = $value;
			}
		}

	
		// Udate block dates , times and update availablity control main meta key
		if(isset($block_dates_and_times) && !empty($block_dates_and_times)){
			foreach ($block_dates_and_times as $key => $value) {
				if($key === get_the_ID()){				
					$block_dates_and_times[$key]['block_dates'] = $rental_availability;
					$block_dates_and_times[$key]['block_times'] = $rental_time_availability;
					$block_dates_and_times[$key]['only_block_dates'] = $only_block_dates;
					$block_dates_and_times[$key]['only_block_times'] = $block_times_merge;
				}
			}
		}


		update_post_meta($parent_id, 'redq_block_dates_and_times', $block_dates_and_times);
		

	}




	/**
	* Availability management meta box define
	* @param  callback redq_inventory_availability_control_cb, id redq_inventory_availability_control
	* @author RedQTeam
	* @version 2.0.0
	* @since 2.0.0
	*/
	public function redq_register_meta_boxes(){
		add_meta_box( 
			'redq_inventory_availability_control', 
			'Availability Management', 
			'redq_inventory_availability_control_cb', 
			'inventory', 
			'normal', 
			'high' 
		);
	}



}	



/**
* Hande Inventory availability management metabox callback
*  
* @author RedQTeam
* @version 2.0.0
* @since 2.0.0
*/
function redq_inventory_availability_control_cb($post){
?>

	<!-- Date Availability tab -->
	<div id="availability_product_data" class="panel rental_date_availability woocommerce_options_panel">
		<h4 class="redq-headings"><?php _e('Product Date Availabilities','redq-rental') ?></h4>

		<div class="options_group own_availibility">
			<div class="table_grid">
				<table class="widefat">
					<thead style="2px solid #eee;">
						<tr>
							<th class="sort" width="1%">&nbsp;</th>
							<th><?php _e( 'Range type', 'redq-rental' ); ?></th>
							<th><?php _e( 'From', 'redq-rental' ); ?></th>
							<th><?php _e( 'To', 'redq-rental' ); ?></th>
							<th><?php _e( 'Bookable', 'redq-rental' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Please select the date range for which you want the product to be disabled.', 'redq-rental' ); ?>">[?]</a></th>
							<th class="remove" width="1%">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="6">
								<a href="#" class="button button-primary add_redq_row" data-row="<?php
									ob_start();
									include( 'views/html-own-availability.php' );
									$html = ob_get_clean();
									echo esc_attr( $html );
								?>"><?php _e( 'Add Dates', 'redq-rental' ); ?></a>
								<span class="description"><?php _e( 'Please select the date range to be disabled for the product.', 'redq-rental' ); ?></span>
							</th>
						</tr>
					</tfoot>
					<tbody id="availability_rows">
						<?php
							
							$parent_id = wp_get_post_parent_id( get_the_ID() );
							$block_dates_and_times = get_post_meta($parent_id, 'redq_block_dates_and_times', true);


							foreach ($block_dates_and_times as $key => $value) {
								if($key === get_the_ID()){
									$rental_availability = $value['block_dates'];
								}
							}

							if ( ! empty( $rental_availability ) && is_array( $rental_availability ) ) {
								foreach ( $rental_availability as $availability ) {
									include( 'views/html-own-availability.php' );
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>




	<!-- Time Availability tab -->
	<div id="availability_product_data" class="panel rental_time_availability woocommerce_options_panel">
		<h4 class="redq-headings"><?php _e('Product Time Availabilities','redq-rental') ?></h4>

		<div class="options_group own_availibility">
			<div class="table_grid">
				<table class="widefat">
					<thead style="2px solid #eee;">
						<tr>
							<th class="sort" width="1%">&nbsp;</th>
							<th><?php _e( 'Date', 'redq-rental' ); ?></th>
							<th><?php _e( 'From', 'redq-rental' ); ?></th>
							<th><?php _e( 'To', 'redq-rental' ); ?></th>
							<th><?php _e( 'Bookable', 'redq-rental' ); ?>&nbsp;<a class="tips" data-tip="<?php _e( 'Please select the date range for which you want the product to be disabled.', 'redq-rental' ); ?>">[?]</a></th>
							<th class="remove" width="1%">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="6">
								<!-- <a href="#" class="button button-primary add_redq_row" data-row="<?php
									// ob_start();
									// include( 'views/html-own-time-availability.php' );
									// $html = ob_get_clean();
									// echo esc_attr( $html );
								?>"><?php //_e( 'Add Time', 'redq-rental' ); ?></a> -->
							</th>
						</tr>
					</tfoot>
					<tbody id="availability_rows">
						<?php							

							$block_dates_and_times = get_post_meta($parent_id, 'redq_block_dates_and_times', true);

							foreach ($block_dates_and_times as $key => $value) {
								if($key === get_the_ID()){
									$time_availablity = $value['block_times'];
								}
							}

							if ( ! empty( $time_availablity ) && is_array( $time_availablity ) ) {
								foreach ( $time_availablity as $availability ) {
									include( 'views/html-own-time-availability.php' );
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>



<?php
}





new WC_Redq_Rental_Post_Types();