jQuery(document).ready(function ($) {
	$('#aed_submit').click(function (e) {
		e.preventDefault();
		let item_id = $('#aed_item_id').val();

		//regular admin-ajax request
		/*
		$.get(
			aed_ajax_object.ajax_url,
			{
				_ajax_nonce: aed_ajax_object.nonce,
				action: "aed_action",
				item_id: item_id
			},
			function (response) {
				console.log(response);
			});
		 */

		// custom endpoint
		/*
		$.get(
			aed_ajax_object.site_url + '/api/items/' + item_id,
			function (response) {
				console.log(response);
			});
		*/
		// wp rest api endpoint
		$.get(
			aed_ajax_object.site_url + '/wp-json/wp/v2/posts/' + item_id,
			function (response) {
				console.log(response);
			});
	});
});