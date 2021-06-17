/*
*
* Backpack Crud
*
*/

jQuery(function($){

    'use strict';

	// AJAX Buttons
	$("#crudTable").on("click", "div[ajax-toggle]", function(){
		let $url = $(this).attr("ajax-toggle");

		$.ajax({
			type: "POST",
			url: $url
		})
		.done(function(data) {
			let json = JSON.parse(data),
				on = $("#crudTable [ajax-toggle-id=" + json.id + "].on" ),
				off= $("#crudTable [ajax-toggle-id=" + json.id + "].off");

			json.active == 1 ? 
				$(on).removeClass("hide") && $(off).addClass("hide"):
				$(off).removeClass("hide") && $(on).addClass("hide");
		});
	});

});
