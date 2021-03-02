(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).on('click','.jsonupdate',function(){

		var table_name = $(this).attr('table');
		console.log(table_name);
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "your_json_action","table_name": table_name},
			success: function (data) {
				alert("sucessfully json");
				console.log(data);
				// location.reload();
			}
		});
	});
	$(document).on('click', '.delete', function () {
		var id = this.id;
		var table_name = $(this).attr('table');
		var con = confirm("Are you sure you want to delete thiss?");
		if(con){
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "your_delete_action", "element_id": id,"table_name": table_name},
			success: function (data) {
				alert("sucessfully deleted");
				location.reload();
			}
		});
		}
	});

	$(document).ready(function(){
		$('#ward_single_municipality_id').val($('#wardDrops option:selected').attr('data-id'));
		$('#municipality_single_district_id').val($('#muniDrops option:selected').attr('data-id'));
		$('#district_single_province_id').val($('#proDrops option:selected').attr('data-id'));
		let proID = $('#provdropinMuni option:selected').attr('data-id');
		let disID = $('#muniDrops option:selected').attr('data-id');

		console.log(disID);

		$('#muniDrops option').each(function(){
			if($(this).attr('pro-id') != proID)
				$(this).hide();
		});

		$('#wardDrops option').each(function(){
			if($(this).attr('dis-id') != disID)
				$(this).hide();
		});


	});
	//ward js
	$(document).on('change','#provdropinMuni',function(){
		let ID = $('option:selected', this).attr('data-id');
		console.log(ID);
		$('#muniDrops option').each(function(){
			$(this).show();
			if($(this).attr('pro-id') != ID)
				$(this).hide();
		});
	});

	$(document).on('change','#wardDrops',function(){
		let option = $('option:selected', this).attr('data-id');
		$('#ward_single_municipality_id').val(option);
	});

	$(document).on('change','#muniDrops',function(){
		let option = $('option:selected', this).attr('data-id');
		$('#wardDrops option').each(function(){
			$(this).show();
			if($(this).attr('dis-id') != option)
				$(this).hide();
		});
		$('#municipality_single_district_id').val(option);
	});

	$(document).on('change','#proDrops',function(){
		let option = $('option:selected', this).attr('data-id');
		$('#district_single_province_id').val(option);
	});


	//display pages

	$(document).on('change','#displayDistrict',function(){
		let option = $('option:selected', this).val();
		$('.fire-full table tbody tr').each(function(){
			$(this).show();
			if(option != "all" )
               {
				   if(option != $(this).find('td:nth-child(7)')[0].innerHTML)
						$(this).hide();
			   }
		});
	});
})( jQuery );
