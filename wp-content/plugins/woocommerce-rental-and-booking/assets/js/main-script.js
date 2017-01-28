jQuery(document).ready(function($) {
	
	/**
	 * Configuratin weekend
	 *
	 * @since 2.0.0
	 * @return null
	 */	
	var offDays = new Array();  
	if(BOOKING_DATA.all_data.local_settings_data.rental_off_days != undefined){
		var offDaysLength = BOOKING_DATA.all_data.local_settings_data.rental_off_days.length;
		for(var i=0; i<offDaysLength; i++){
			offDays.push(parseInt(BOOKING_DATA.all_data.local_settings_data.rental_off_days[i]));	
		}	
	} 

	var domain = '';
		months = '';
		weekdays = '';

	if(BOOKING_DATA.localize_info.domain !== false && BOOKING_DATA.localize_info.months !== false && BOOKING_DATA.localize_info.weekdays !== false){
		domain   = BOOKING_DATA.localize_info.domain,
		months   = BOOKING_DATA.localize_info.months.split(','),
		weekdays = BOOKING_DATA.localize_info.weekdays.split(',');
	}


	$.datetimepicker.setLocale(domain);


	/**
	 * Configuratin of date picker for pickupdate
	 *
	 * @since 1.0.0
	 * @return null
	 */	
	$('#pickup-date').datetimepicker({
	  	timepicker:false,
	  	scrollMonth: false,
	  	format:BOOKING_DATA.all_data.choose_date_format,	  	
	  	minDate: 0,
	  	disabledDates: BOOKING_DATA.block_dates,
	  	formatDate: BOOKING_DATA.all_data.choose_date_format ,	  	
	  	onShow:function( ct ){
			this.setOptions({
		    	maxDate:jQuery('#dropoff-date').val()?jQuery('#dropoff-date').val():false,		    	
		   	})
		},	
		disabledWeekDays : offDays, 
		i18n:
		{
			domain:{
		   		months: months,
		   		dayOfWeek: weekdays
		  	}
		}, 
		scrollInput: false
	});


	/**
	 * Configuratin of time picker for pickuptime
	 *
	 * @since 1.0.0
	 * @return null
	 */	
	$('#pickup-time').datetimepicker({
	  	datepicker:false,
	  	format:'H:i',	  	 
	  	step:5,
	  	scrollInput: false
	 	// onGenerate:function(ct,$i){
		// 	$('.xdsoft_time_variant .xdsoft_time').each(function(index){		 	
		// 	 	var hour = $(this).data('hour'),
		// 	 		min  = $(this).data('minute');
		// 	 		if(parseInt(hour) === 15 && parseInt(min) === 45 ){
		// 	 			$(this).addClass('xdsoft_disabled');
		// 	 			$(this).prop('xdsoft_disabled',true);	
		// 	 		}
			 		
		// 	});
		// }
	});


	/**
	 * Configuratin of time picker for dropoffdate
	 *
	 * @since 1.0.0
	 * @return null
	 */	
	$('#dropoff-date').datetimepicker({
	  	timepicker:false,
	  	scrollMonth: false,
	  	format:BOOKING_DATA.all_data.choose_date_format,	  	
	  	minDate: 0,
	  	disabledDates: BOOKING_DATA.block_dates,
	  	formatDate: BOOKING_DATA.all_data.choose_date_format,
	  	formatTime : 'H:i',
	  	onShow:function( ct ){
			this.setOptions({
		    	minDate:jQuery('#pickup-date').val()?jQuery('#pickup-date').val():false,		    	
		   	})
		},	
		disabledWeekDays : offDays,  
		i18n:
		{
			domain:{
		   		months: months,
		   		dayOfWeek: weekdays
		  	}
		}, 
		scrollInput: false	 
	});


	/**
	 * Configuratin of time picker for dropofftime
	 *
	 * @since 1.0.0
	 * @return null
	 */	
	$('#dropoff-time').datetimepicker({
	  	datepicker:false,
	  	format:'H:i',
	  	onShow:function( ct ){
			this.setOptions({
		    	minTime:jQuery('#pickup-time').val()?jQuery('#pickup-time').val():false,		    	
		   	})
		},
	  	step:5,
	  	scrollInput: false
	});


	/**
	 * Configuratin others pluins
	 *
	 * @since 1.0.0
	 * @return null
	 */	
	$('.redq-select-boxes').chosen();
	$('.price-showing').flip();
	


});