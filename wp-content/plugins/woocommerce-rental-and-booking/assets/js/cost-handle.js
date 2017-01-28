jQuery(document).ready(function($) {
	"use strict";
	$('.show_if_time').hide();
	$('.redq_add_to_cart_button').attr('disabled','disabled');

	var translated_strings = BOOKING_DATA.translated_strings;


	$('form.cart').on('change',function(){

		var formData = $(this).serializeArray(),
			dataObj = {};

		$(formData).each(function(i, field){
		  	dataObj[field.name] = field.value;
		});


		/**
		 * Configuring data
		 *
		 * @since 1.0.3
		 * @return null
		 */
		var date_format; 

		if(BOOKING_DATA.all_data.choose_date_format.toLowerCase() === 'd/m/y'){
        	date_format = 'd/M/yyyy'; 
        }

        if(BOOKING_DATA.all_data.choose_date_format.toLowerCase() === 'm/d/y'){
            date_format = 'MM/d/yyyy'; 
        }

        if(BOOKING_DATA.all_data.choose_date_format.toLowerCase() === 'y/m/d'){
            date_format = 'yyyy/MM/d'; 
        }

		if(dataObj.dropoff_date == undefined){			
			if(dataObj.pickup_time == undefined || dataObj.dropoff_time == undefined){
				dataObj.dropoff_date = dataObj.pickup_date;
			}else{
				dataObj.dropoff_date = dataObj.pickup_date;
			}
		}  

		if(dataObj.pickup_date == undefined){			
			if(dataObj.pickup_time == undefined || dataObj.dropoff_time == undefined){
				dataObj.pickup_date = dataObj.dropoff_date;
			}else{
				dataObj.pickup_date = dataObj.dropoff_date;
			}
		} 

		if(dataObj.pickup_time == undefined){			
			if(dataObj.pickup_time == undefined && dataObj.dropoff_time){
				dataObj.pickup_time = dataObj.dropoff_time;
			}else{
				dataObj.pickup_time = '';	
			}
		} 

		if(dataObj.dropoff_time == undefined){			
			if(dataObj.dropoff_time == undefined && dataObj.pickup_time){
				dataObj.dropoff_time = dataObj.pickup_time;
			}else{
				dataObj.dropoff_time = '';
			}
		} 


		// calcute days and prices
		if(dataObj.pickup_date != undefined && dataObj.pickup_date != '' && dataObj.dropoff_date != undefined && dataObj.dropoff_date != '' ){

			$('.booking-pricing-info').show();
			$('.single_add_to_cart_button').removeAttr('disabled','disabled');		



			/**
			 * Handling days and hours
			 *
			 * @since 1.0.0
			 * @return null
			 */
			if(BOOKING_DATA.all_data.choose_date_format == 'd/m/Y'){
				var splitPickupDate  = dataObj.pickup_date.split('/'),
					splitDropoffDate = dataObj.dropoff_date.split('/');

				if(parseInt(splitPickupDate[0]) < 13){
					var pickupDate = splitPickupDate[1]+'/'+splitPickupDate[0]+'/'+splitPickupDate[2];
				}else{
					var pickupDate  = Date.parse(dataObj.pickup_date).toString('M/d/yyyy');
				}

				if(parseInt(splitDropoffDate[0]) < 13){
					var dropoffDate = splitDropoffDate[1]+'/'+splitDropoffDate[0]+'/'+splitDropoffDate[2];
				}else{
					var	dropoffDate = Date.parse(dataObj.dropoff_date).toString('M/d/yyyy');
				}

			}else{
				var pickupDate  = Date.parse(dataObj.pickup_date).toString('M/d/yyyy'),
					dropoffDate = Date.parse(dataObj.dropoff_date).toString('M/d/yyyy');
			}

			if(dataObj.pickup_time != '' && dataObj.dropoff_time == ''){
				var	pickupTime  = dataObj.pickup_time,
					dropoffTime = pickupTime;
			}else{
				var	pickupTime  = dataObj.pickup_time,
					dropoffTime = dataObj.dropoff_time;
			}

		


			var pickupDateTime = pickupDate + ' ' +pickupTime,
				dropoffDateTime = dropoffDate + ' ' +dropoffTime;

			var start = new Date(pickupDateTime),
				end   = new Date(dropoffDateTime),
				diff  = end.getTime() - start.getTime(),
				hours = diff/3600000,
				days,total_hours;

			var enableSingleDayTimeBooking = BOOKING_DATA.all_data.local_settings_data.enable_single_day_time_booking;	


			if(hours < 24){
				if(enableSingleDayTimeBooking == 'open'){
					days = 1;
				}else{
					days = 0;	
				}			  	
			  	total_hours = Math.ceil(hours);

			  	$('.additional_person_info').trigger("chosen:updated");

			  	$('.show_person_cost_if_day').hide();
			  	$('.show_person_cost_if_time').show();

			  	// $('.show_if_day').children('.amount').css({'visibility': 'hidden'});
			  	$('.show_if_day').children('span').hide();
			  	$('.show_if_time').show();
			  	$('.single_add_to_cart_button').removeAttr('disabled','disabled');

			}else{
			  	days = parseInt(hours/24);
			  	var extra_hours = hours%24;
			  	if(enableSingleDayTimeBooking == 'open'){
			  		if(extra_hours >= parseFloat(BOOKING_DATA.all_data.max_time_late) ){
				  		days = days + 1;
				  	}
			  	}else{
			  		if(extra_hours > parseFloat(BOOKING_DATA.all_data.max_time_late) ){
				  		days = days + 1;
				  	}
			  	}			  	

			  	$('.show_person_cost_if_day').show();
			  	$('.show_person_cost_if_time').hide();

			  	$('.additional_person_info').trigger("chosen:updated");
			  	$('.show_if_day').children('span').show();
			  	$('.show_if_time').hide();
			}


			console.log(days);


			/**
			 * Handling book now button on/off
			 *
			 * @since 1.0.0
			 * @return null
			 */
			var selected_days = new Array(),
				flag = 0,
				format;

			if(BOOKING_DATA.all_data.choose_date_format === 'Y/m/d'){
				format = 'yyyy/MM/dd';
			}

			if(BOOKING_DATA.all_data.choose_date_format === 'm/d/Y'){
				format = 'MM/dd/yyyy';
			}

			if(BOOKING_DATA.all_data.choose_date_format === 'd/m/Y'){
				format = 'dd/MM/yyyy';
			}

			for(var i = 0; i<parseInt(days) ; i++){
				if(i == 0){
					selected_days.push(Date.parse(pickupDate).toString(format));
				}else{
					selected_days.push(Date.parse(pickupDate).add(i).day().toString(format));
				}
			}


			var error = new Array(),
				max_rental_days = BOOKING_DATA.all_data.local_settings_data.max_rental_days,
				min_rental_days = BOOKING_DATA.all_data.local_settings_data.min_rental_days;

			for (var i = 0; i < selected_days.length; i++) {
			    for (var j = 0; j < BOOKING_DATA.block_dates.length; j++) {
			    	if(flag==0){
				        if (selected_days[i] == BOOKING_DATA.block_dates[j]) {				        	
				            error.push(translated_strings.max_booking_days);
				            flag = 1;
			          	}else{
			        		//$('.single_add_to_cart_button').removeAttr('disabled','disabled');
			        	}
			        }
			    }
			}			
			

			if(parseInt(days) > parseInt(max_rental_days) )	{
				error.push(translated_strings.min_booking_days + ' ' + max_rental_days+' exceed');				
			}

			if(parseInt(days) < parseInt(min_rental_days) )	{
				error.push(translated_strings.min_booking_days + ' ' + min_rental_days);				
			}

			if(error.length > 0){
				for(var i=0; i<error.length; i++){
					sweetAlert(translated_strings.opps,error[i],"error");	
				}
				$('.redq_add_to_cart_button').attr('disabled','disabled');
			}else{
				$('.redq_add_to_cart_button').removeAttr('disabled','disabled');
			}



			/**
			 * Handling resources
			 *
			 * @since 1.0.0
			 * @return null
			 */
			var extras_pricing_plan = {};
		    extras_pricing_plan.extras = $("input[name='extras[]']:checked").map(function(){

		    	var extras = {
		    		'name'  : $(this).data('name'),
		    		'cost'  : $(this).data('value'),
		    		'hourly_cost' : $(this).data('hourly-rate'),
		    		'applicable' : $(this).data('applicable'),
		    	}
		        return  extras;
		    }).get();


		    /**
			 * Handling persons
			 *
			 * @since 1.0.0
			 * @return null
			 */
		    var person_cost      = $('.additional_person_info').find(':selected').data('person_cost'),
		    	person_count     = $('.additional_person_info').find(':selected').data('person_count'),
		    	pcost_applicable = $('.additional_person_info').find(':selected').data('applicable');


		    /**
			 * Handling location cost
			 *
			 * @since 1.0.0
			 * @return null
			 */
		    var pickup_cost  = $('.pickup_location').find(':selected').data('pickup-location-cost'),
		    	dropoff_cost = $('.dropoff_location').find(':selected').data('dropoff-location-cost');    	
		    

		    /**
			 * Handling security_deposites
			 *
			 * @since 1.0.0
			 * @return null
			 */
			var security_deposites_pricing_plan = {};
		    security_deposites_pricing_plan.security_deposites = $("input[name='security_deposites[]']:checked").map(function(){
		    	var security_deposites = {
		    		'name'  : $(this).data('name'),
		    		'cost'  : $(this).data('value'),
		    		'hourly_cost' : $(this).data('hourly-rate'),
		    		'applicable' : $(this).data('applicable'),
		    	}
		        return  security_deposites;
		    }).get();


		    /**
			 * Calculate price discount
			 *
			 * @since 1.0.0
			 * @return number
			 */
			function calculate_price_discount(cost, price_discount){
				
				var flag = 0,
					discount_amount,
					discount_type;

				$.each(price_discount, function(index , value){
					if(flag == 0){
						if(parseInt(value.min_days) <= parseInt(days) && parseInt(value.max_days) >= parseInt(days)){
							discount_type = value.discount_type;
							discount_amount = value.discount_amount;
							flag = 1;
						}
					}
				});

				if(discount_type != undefined && discount_type && discount_amount != undefined && discount_amount){						
					if(discount_type === 'percentage'){
						cost = cost - (cost*discount_amount)/100;
						$('p.discount-rate span').html(discount_amount + '%');
					}else{
						cost = cost - discount_amount;
						var currency = $('.currency-symbol').val();
						$('p.discount-rate span').html(accounting.formatMoney(discount_amount,currency));
					}
				}
				return cost;
			} 



			/**
			 * Calculate resources and person cost
			 *
			 * @since 1.0.0
			 * @return number
			 */
			function calculate_third_party_cost(cost){

				if(pickup_cost != null && pickup_cost != undefined && pickup_cost){					
					cost = parseFloat(cost) + parseFloat(pickup_cost);					
				}

				if(dropoff_cost != null && dropoff_cost != undefined && dropoff_cost){					
					cost = parseFloat(cost) + parseFloat(dropoff_cost);					
				}

				if(extras_pricing_plan.extras.length != 0){
					$.each(extras_pricing_plan.extras , function(index, value){
						if(value.applicable == 'per_day'){
							cost = parseFloat(cost) + parseInt(days)*parseFloat(value.cost);
						}else{
							cost = parseFloat(cost) + parseFloat(value.cost);
						}
					});
				}

				if(person_cost != null && person_cost != undefined && person_cost){
					if(pcost_applicable == 'per_day'){
						cost = parseFloat(cost) + parseInt(days)*parseFloat(person_cost);
					}else{
						cost = parseFloat(cost) + parseFloat(person_cost);
					}
				}

				if(security_deposites_pricing_plan.security_deposites.length != 0){
					$.each(security_deposites_pricing_plan.security_deposites , function(index, value){
						if(value.applicable == 'per_day'){
							cost = parseFloat(cost) + parseInt(days)*parseFloat(value.cost);
						}else{
							cost = parseFloat(cost) + parseFloat(value.cost);
						}
					});
				}

				return cost;
			}


			/**
			 * Calculate hourly resources and person cost
			 *
			 * @since 1.0.0
			 * @return number
			 */
			function calculate_hourly_third_party_cost(cost){

				if(pickup_cost != null && pickup_cost != undefined && pickup_cost){					
					cost = parseFloat(cost) + parseFloat(pickup_cost);					
				}

				if(dropoff_cost != null && dropoff_cost != undefined && dropoff_cost){					
					cost = parseFloat(cost) + parseFloat(dropoff_cost);					
				}

				if(extras_pricing_plan.extras.length != 0){
					$.each(extras_pricing_plan.extras , function(index, value){
						if(value.applicable == 'per_day'){
							cost = parseFloat(cost) + parseInt(total_hours)*parseFloat(value.hourly_cost);
						}else{
							cost = parseFloat(cost) + parseFloat(value.cost);
						}
					});
				}

				if(person_cost != null && person_cost != undefined && person_cost){
					if(pcost_applicable == 'per_day'){
						cost = parseFloat(cost) + parseInt(total_hours)*parseFloat(person_cost);
					}else{
						cost = parseFloat(cost) + parseFloat(person_cost);
					}
				}

				if(security_deposites_pricing_plan.security_deposites.length != 0){
					$.each(security_deposites_pricing_plan.security_deposites , function(index, value){
						if(value.applicable == 'per_day'){
							cost = parseFloat(cost) + parseInt(total_hours)*parseFloat(value.hourly_cost);
						}else{
							cost = parseFloat(cost) + parseFloat(value.cost);
						}
					});
				}

				return cost;
			}


		    /**
			 * Calculate day ranges pricing
			 *
			 * @since 1.0.0
			 * @return null
			 */
			if(BOOKING_DATA.all_data.pricing_type === 'days_range'){

				if(days > 0){
					var days_range = BOOKING_DATA.all_data.days_range_cost,
						flag = 0,
						cost,						
						max_days_check = new Array();

					$.each(days_range, function(index , value){
						max_days_check.push(parseInt(value.max_days));
					});

					if(days > Math.max.apply(Math,max_days_check)){
						$('.single_add_to_cart_button').attr('disabled','disabled');
				        sweetAlert(translated_strings.opps, translated_strings.max_booking_days_exceed, "error");
					}else{
						$('.single_add_to_cart_button').removeAttr('disabled','disabled');
					}

					$.each(days_range, function(index , value){
						if(flag == 0){
							if(value.cost_applicable === 'per_day'){
								if(parseInt(value.min_days) <= parseInt(days) && parseInt(value.max_days) >= parseInt(days)){
									cost = parseFloat(value.range_cost) * parseInt(days);								
									flag = 1;
								}
							}else{
								if(parseInt(value.min_days) <= parseInt(days) && parseInt(value.max_days) >= parseInt(days)){
									cost = parseFloat(value.range_cost);								
									flag = 1;
								}	
							}							
						}
					});					

					var price_discount = BOOKING_DATA.all_data.price_discount;
					cost = calculate_price_discount(cost, price_discount);

					cost = calculate_third_party_cost(cost);
				}else{
					var cost = parseInt(total_hours)*parseFloat(BOOKING_DATA.all_data.hourly_pricing);
					cost = calculate_hourly_third_party_cost(cost);
				}

			}


			/**
			 * Calculate general pricing
			 *
			 * @since 1.0.0
			 * @return null
			 */
			if(BOOKING_DATA.all_data.pricing_type ==="general_pricing"){
				if(days > 0){
					var cost = parseInt(days)*parseFloat(BOOKING_DATA.all_data.general_pricing);
					
					var price_discount = BOOKING_DATA.all_data.price_discount;
					cost = calculate_price_discount(cost, price_discount);

					cost = calculate_third_party_cost(cost);
				}else{
					var cost = parseInt(total_hours)*parseFloat(BOOKING_DATA.all_data.hourly_pricing);

					cost = calculate_hourly_third_party_cost(cost);
				}
			}


			/**
			 * Calculate daily pricing
			 *
			 * @since 1.0.0
			 * @return null
			 */
			if(BOOKING_DATA.all_data.pricing_type ==="daily_pricing"){
				if(days > 0){
					var daily_pricing_plan = BOOKING_DATA.all_data.daily_pricing,
						cost = 0;

					for(var i=0; i<parseInt(days); i++){
						if(i == 0){
							var day = Date.parse(pickupDate).getDay();
							switch(day){
								case 0:
									if(daily_pricing_plan.sunday != ''){
										cost = cost + parseFloat(daily_pricing_plan.sunday);
									}else{
										cost = cost + 0;
									}
									break;
								case 1:
									if(daily_pricing_plan.monday != ''){
										cost = cost + parseFloat(daily_pricing_plan.monday);
									}else{
										cost = cost + 0;
									}
									break;
								case 2:
									if(daily_pricing_plan.tuesday != ''){
										cost = cost + parseFloat(daily_pricing_plan.tuesday);
									}else{
										cost = cost + 0;
									}
									break;
								case 3:
									if(daily_pricing_plan.wednesday != ''){
										cost = cost + parseFloat(daily_pricing_plan.wednesday);
									}else{
										cost = cost + 0;
									}
									break;
								case 4:
									if(daily_pricing_plan.thursday != ''){
										cost = cost + parseFloat(daily_pricing_plan.thursday);
									}else{
										cost = cost + 0;
									}
									break;
								case 5:
									if(daily_pricing_plan.friday != ''){
										cost = cost + parseFloat(daily_pricing_plan.friday);
									}else{
										cost = cost + 0;
									}
									break;
								case 6:
									if(daily_pricing_plan.saturday != ''){
										cost = cost + parseFloat(daily_pricing_plan.saturday);
									}else{
										cost = cost + 0;
									}
									break;
							}
						}else{
							var day = Date.parse(pickupDate).add(i).day().getDay();
							switch(day){
								case 0:
									if(daily_pricing_plan.sunday != ''){
										cost = cost + parseFloat(daily_pricing_plan.sunday);
									}else{
										cost = cost + 0;
									}
									break;
								case 1:
									if(daily_pricing_plan.monday != ''){
										cost = cost + parseFloat(daily_pricing_plan.monday);
									}else{
										cost = cost + 0;
									}
									break;
								case 2:
									if(daily_pricing_plan.tuesday != ''){
										cost = cost + parseFloat(daily_pricing_plan.tuesday);
									}else{
										cost = cost + 0;
									}
									break;
								case 3:
									if(daily_pricing_plan.wednesday != ''){
										cost = cost + parseFloat(daily_pricing_plan.wednesday);
									}else{
										cost = cost + 0;
									}
									break;
								case 4:
									if(daily_pricing_plan.thursday != ''){
										cost = cost + parseFloat(daily_pricing_plan.thursday);
									}else{
										cost = cost + 0;
									}
									break;
								case 5:
									if(daily_pricing_plan.friday != ''){
										cost = cost + parseFloat(daily_pricing_plan.friday);
									}else{
										cost = cost + 0;
									}
									break;
								case 6:
									if(daily_pricing_plan.saturday != ''){
										cost = cost + parseFloat(daily_pricing_plan.saturday);
									}else{
										cost = cost + 0;
									}
									break;
							}
						}
					}

					var price_discount = BOOKING_DATA.all_data.price_discount;
					cost = calculate_price_discount(cost, price_discount);

					cost = calculate_third_party_cost(cost);
				}else{
					var cost = parseInt(total_hours)*parseFloat(BOOKING_DATA.all_data.hourly_pricing);
					cost = calculate_hourly_third_party_cost(cost);
				}

			}


			/**
			 * Calculate monthly pricing
			 *
			 * @since 1.0.0
			 * @return null
			 */
			if(BOOKING_DATA.all_data.pricing_type === "monthly_pricing"){
				if(days > 0){
					var monthly_pricing_plan = BOOKING_DATA.all_data.monthly_pricing,
						cost = 0;

					for(var i = 0; i<parseInt(days) ; i++){

						if(i == 0){
							var month = Date.parse(pickupDate).getMonth();
							switch(month){
								case 0:
									if(monthly_pricing_plan.january != ''){
										cost = cost + parseFloat(monthly_pricing_plan.january);
									}else{
										cost = cost + 0;
									}
									break;
								case 1:
									if(monthly_pricing_plan.february != ''){
										cost = cost + parseFloat(monthly_pricing_plan.february);
									}else{
										cost = cost + 0;
									}
									break;
								case 2:
									if(monthly_pricing_plan.march != ''){
										cost = cost + parseFloat(monthly_pricing_plan.march);
									}else{
										cost = cost + 0;
									}
									break;
								case 3:
									if(monthly_pricing_plan.april != ''){
										cost = cost + parseFloat(monthly_pricing_plan.april);
									}else{
										cost = cost + 0;
									}
									break;
								case 4:
									if(monthly_pricing_plan.may != ''){
										cost = cost + parseFloat(monthly_pricing_plan.may);
									}else{
										cost = cost + 0;
									}
									break;
								case 5:
									if(monthly_pricing_plan.june != ''){
										cost = cost + parseFloat(monthly_pricing_plan.june);
									}else{
										cost = cost + 0;
									}
									break;
								case 6:
									if(monthly_pricing_plan.july != ''){
										cost = cost + parseFloat(monthly_pricing_plan.july);
									}else{
										cost = cost + 0;
									}
									break;
								case 7:
									if(monthly_pricing_plan.august != ''){
										cost = cost + parseFloat(monthly_pricing_plan.august);
									}else{
										cost = cost + 0;
									}
									break;
								case 8:
									if(monthly_pricing_plan.september != ''){
										cost = cost + parseFloat(monthly_pricing_plan.september);
									}else{
										cost = cost + 0;
									}
									break;
								case 9:
									if(monthly_pricing_plan.october != ''){
										cost = cost + parseFloat(monthly_pricing_plan.october);
									}else{
										cost = cost + 0;
									}
									break;
								case 10:
									if(monthly_pricing_plan.november != ''){
										cost = cost + parseFloat(monthly_pricing_plan.november);
									}else{
										cost = cost + 0;
									}
									break;
								case 11:
									if(monthly_pricing_plan.december != ''){
										cost = cost + parseFloat(monthly_pricing_plan.december);
									}else{
										cost = cost + 0;
									}
									break;
							}
						}else{
							var month = Date.parse(pickupDate).add(i).day().getMonth();

							switch(month){
								case 0:
									if(monthly_pricing_plan.january != ''){
										cost = cost + parseFloat(monthly_pricing_plan.january);
									}else{
										cost = cost + 0;
									}
									break;
								case 1:
									if(monthly_pricing_plan.february != ''){
										cost = cost + parseFloat(monthly_pricing_plan.february);
									}else{
										cost = cost + 0;
									}
									break;
								case 2:
									if(monthly_pricing_plan.march != ''){
										cost = cost + parseFloat(monthly_pricing_plan.march);
									}else{
										cost = cost + 0;
									}
									break;
								case 3:
									if(monthly_pricing_plan.april != ''){
										cost = cost + parseFloat(monthly_pricing_plan.april);
									}else{
										cost = cost + 0;
									}
									break;
								case 4:
									if(monthly_pricing_plan.may != ''){
										cost = cost + parseFloat(monthly_pricing_plan.may);
									}else{
										cost = cost + 0;
									}
									break;
								case 5:
									if(monthly_pricing_plan.june != ''){
										cost = cost + parseFloat(monthly_pricing_plan.june);
									}else{
										cost = cost + 0;
									}
									break;
								case 6:
									if(monthly_pricing_plan.july != ''){
										cost = cost + parseFloat(monthly_pricing_plan.july);
									}else{
										cost = cost + 0;
									}
									break;
								case 7:
									if(monthly_pricing_plan.august != ''){
										cost = cost + parseFloat(monthly_pricing_plan.august);
									}else{
										cost = cost + 0;
									}
									break;
								case 8:
									if(monthly_pricing_plan.september != ''){
										cost = cost + parseFloat(monthly_pricing_plan.september);
									}else{
										cost = cost + 0;
									}
									break;
								case 9:
									if(monthly_pricing_plan.october != ''){
										cost = cost + parseFloat(monthly_pricing_plan.october);
									}else{
										cost = cost + 0;
									}
									break;
								case 10:
									if(monthly_pricing_plan.november != ''){
										cost = cost + parseFloat(monthly_pricing_plan.november);
									}else{
										cost = cost + 0;
									}
									break;
								case 11:
									if(monthly_pricing_plan.december != ''){
										cost = cost + parseFloat(monthly_pricing_plan.december);
									}else{
										cost = cost + 0;
									}
									break;
							}
						}  //End if-else statement
					}
					//End for loop

					var price_discount = BOOKING_DATA.all_data.price_discount;
					cost = calculate_price_discount(cost, price_discount);
					cost = calculate_third_party_cost(cost);

				}else{
					var cost = parseInt(total_hours)*parseFloat(BOOKING_DATA.all_data.hourly_pricing);
					cost = calculate_hourly_third_party_cost(cost);

				}

			}


			var woocommerce_config = BOOKING_DATA.woocommerce_info;
			if(woocommerce_config.position === 'left'){
				var currencyFormat = "%s%v";
			}else{
				var currencyFormat = "%v%s";
			}

			var currencyOptions = {
				symbol : woocommerce_config.symbol,
				decimal : woocommerce_config.decimal,
				thousand: woocommerce_config.thousand,
				precision : woocommerce_config.number,
				format: currencyFormat,
			};

			$('h3.booking_cost span').html(accounting.formatMoney(cost,currencyOptions));

		}else{


		}
	});

});