<?php

/**
* 
*/
class WC_Full_Calendar_Api {
  
  	function __construct() {
    	add_action('admin_enqueue_scripts', array( $this, 'full_calendar_styles_and_scripts' ) );
  	}

  	/**
	 * Plugin enqueues admin stylesheet and scripts
	 *
	 * @since 1.0.0
	 * @return null
	 */
	public function full_calendar_styles_and_scripts($hook){

		global $post, $woocommerce, $wp_scripts;		

		$this->redq_show_rental_data_on_full_calendar($hook);
	}


	/**
	 * Show all booking data on full calendar
	 *
	 * @since 2.4.0
	 * @return null
	 */
    public function redq_show_rental_data_on_full_calendar($hook){

    	if( 'toplevel_page_rnb_admin' === $hook ) {
	        wp_register_script( 'moment', REDQ_ROOT_URL. '/assets/js/moment.js', array('jquery'), $ver = true, true);
	        wp_enqueue_script( 'moment' );

	        wp_register_style('qtip2', '//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css', array(), $ver = false, $media = 'all');
	        wp_enqueue_style('qtip2');

	        wp_register_script( 'qtip2', '//cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.js', array('jquery', 'moment'), $ver = true, true);
	        wp_enqueue_script( 'qtip2' );

	        wp_register_style('fullcalendar', REDQ_ROOT_URL. '/assets/css/fullcalendar.css', array(), $ver = false, $media = 'all');
	        wp_enqueue_style('fullcalendar');

	        wp_register_script( 'fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.7.2/fullcalendar.min.js', array('jquery', 'moment', 'qtip2'), $ver = true, true);
	        wp_enqueue_script( 'fullcalendar' );

	        wp_register_style('magnific-popup', REDQ_ROOT_URL. '/assets/css/magnific-popup.css', array(), $ver = false, $media = 'all');
	        wp_enqueue_style('magnific-popup');

	        wp_register_script( 'magnific-popup', REDQ_ROOT_URL. '/assets/js/jquery.magnific-popup.min.js', array('jquery'), $ver = false, true);
	        wp_enqueue_script( 'magnific-popup' );

	        wp_register_script( 'redq-admin-page', REDQ_ROOT_URL. '/assets/js/admin-page.js', array('jquery'), $ver = false, true);
	        wp_enqueue_script( 'redq-admin-page' );

	        $args = array(
				'post_type' => 'shop_order',
				'post_status' => 'any',
				'posts_per_page' => -1,
	        );
	         
	        $orders = get_posts($args);
	        
	        $fullcalendar = array();

	        if(isset($orders) && !empty($orders)){
		        foreach($orders as $o) {
		          
					$order_id = $o->ID;
					$order = new WC_Order($order_id);

					foreach( $order->get_items() as $item ) {

						$item_meta_array = $item['item_meta_array'];
						foreach ($item_meta_array as $item_meta) {

						  	if( $item_meta->key === '_product_id' ) {

							    $pro = get_product($item_meta->value);	
						  		$product_type = '';

						  		if(isset($pro) && !empty($pro)){
						  			$product_type = $pro->product_type;
						  		}

							    if( $product_type === 'redq_rental' ) {
									$fullcalendar[$order_id]['post_status'] = $o->post_status;
									$fullcalendar[$order_id]['title'] = $item['name'];
									$fullcalendar[$order_id]['link'] = get_the_permalink($item_meta->value);
									$fullcalendar[$order_id]['id'] = $item_meta->value;
									$fullcalendar[$order_id]['description'] = '<table cellspacing="0" class="redq-rental-display-meta"><tbody><tr><th>Order ID:</th><td>#'.$order_id.'</td></tr>';

									foreach ($item_meta_array as $v) {
										if( $v->key !== '_qty'
											&& $v->key !== '_tax_class'
											&& $v->key !== '_product_id'
											&& $v->key !== '_variation_id'
											&& $v->key !== '_line_subtotal'
											&& $v->key !== '_line_total'
											&& $v->key !== '_line_subtotal_tax'
											&& $v->key !== '_line_tax'
											&& $v->key !== '_line_tax_data'
										){
											$fullcalendar[$order_id]['description'] .= '<tr><th>'.$v->key.'</th><td>'.$v->value.'</td></tr>';  
										}

										if( $v->key === 'Pickup Date') {
										  	$fullcalendar[$order_id]['start'] = DateTime::createFromFormat('m/d/Y', $v->value)->format('Y-m-d');
										}
										if( $v->key === 'Pickup Time') {
										  	$fullcalendar[$order_id]['start'] .= 'T'.$v->value;
										}
										if( $v->key === 'Drop-off Date') {
										  	$fullcalendar[$order_id]['end'] = DateTime::createFromFormat('m/d/Y', $v->value)->format('Y-m-d');
										}
										if( $v->key === 'Drop-off Time') {
										  	$fullcalendar[$order_id]['end'] .= 'T'.$v->value;
										}
										$fullcalendar[$order_id]['url'] = admin_url( 'post.php?post=' . absint( $order->id ) . '&action=edit' );
									}

									$order_total = $order->get_formatted_order_total();
									$fullcalendar[$order_id]['description'] .= '<tr><th>Order Total</th><td>'.$order_total.'</td>';
									$fullcalendar[$order_id]['description'] .= '</tbody></table>';
								}
						  	}
						}
					}
				}
			}

			wp_localize_script( 'redq-admin-page', 'REDQRENTALFULLCALENDER', $fullcalendar );
		}

    }



}