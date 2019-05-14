jQuery(document).ready(function ($) {

	$('#aed_submit_ajax').click(function (e) {
		e.preventDefault();
		console.log('Regular Ajax');
		$.get(
			aed_ajax_object.ajax_url,
			{
				_ajax_nonce: aed_ajax_object.nonce,
				action: "aed_action",
			},
			function (response) {
				console.log(response);
			});
	});

	$('#aed_submit_rest').click(function (e) {
		e.preventDefault();
		console.log('REST API');
		$.get(
			aed_ajax_object.site_url + '/wp-json/wp/v2/posts/?per_page=100',
			function (response) {
				console.log(response);
			});
	});

	$('#aed_submit_graph').click(function (e) {
		e.preventDefault();
		console.log('GraphQL');
		$.post({
			url: aed_ajax_object.site_url + '/graphql',
			data: JSON.stringify(
				{
					"query": "query { posts { edges { node { id title date content } } } }"
				}
			),
			contentType: 'application/json',
			variables: {
				"first": 100
			}
		}).done(function (response) {
			console.log(response);
		});
	});

});
