(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	function getAPIdata(ID){
		$.ajax({
			url : "http://leaf.test/wp-json/w1/v1/prithak-nepals/"+ID,
		}).done(function(data){
			$('#mapinner-content .inner-content-box').empty();
		  $('#mapinner-content .inner-content-box').append(convertToTable(data));
		});
	}
	function convertToTable(data){
	   var res= "<div>"+
				"<table>"+
				"<tr><th>Ward No</th><th>Former VDC</th><th>Population</th><th>Area</th></tr>";
  
		$.each(data,function(index,value){
			res += "<tr>"+
				   "<td>" + value.No + "</td>"+
				   "<td>" + value.name + "</td>"+
				   "<td>" + value.population + "</td>"+
				   "<td>" + value.area + "</td>"+
				   "</tr>";
		});
		res  +=  "</table></div>";
		return res;
	}
	$(document).ready(function(){
		


		$('#mapid-content').hide();
		$('#mapinner-content').hide();
		$('#mapinner-content .fire-close').click(function(){
			$(this).parent().hide();
		});
		// $('#VIEWwards').click(function(){
		// 	let ID = $(this).attr('data-id');
		// 	getAPIdata(ID);
		// 	$('#mapinner-content').show();
		// });
	});

	$(document).on('click','#VIEWwards',function(){
		let ID = $(this).attr('data-id');
		getAPIdata(ID);
		$('#mapinner-content').show();
	});
})( jQuery );
