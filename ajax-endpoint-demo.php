<?php
/**
 * Plugin Name:     Ajax Endpoint Demo
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     ajax-endpoint-demo
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ajax_Endpoint_Demo
 */

// Your code starts here.

add_shortcode( 'aed_form_shortcode', 'aed_form_shortcode' );
function aed_form_shortcode() {
	?>
	<form>
		Item Id <input type="text" id="aed_item_id"><br>
		<input type="submit" id="aed_submit" value="Submit">
	</form>
	<?php
}

add_action( 'wp_enqueue_scripts', 'aed_enqueue' );
function aed_enqueue() {
	wp_enqueue_script(
		'aed-ajax-script',
		plugins_url( '/js/ajax.js', __FILE__ ),
		array( 'jquery' ),
		'1.0.0',
		true
	);
	$ajax_nonce = wp_create_nonce( 'aed_test' );
	wp_localize_script(
		'aed-ajax-script',
		'aed_ajax_object',
		array(
			'site_url' => site_url(),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => $ajax_nonce,
		)
	);
}

add_action( 'wp_ajax_aed_action', 'aed_ajax_handler' );
add_action( 'wp_ajax_nopriv_aed_action', 'aed_ajax_handler' );
function aed_ajax_handler() {
	if ( ! check_ajax_referer( 'aed_test' ) ) {
		wp_send_json(
			array(
				'status'  => 'error',
				'message' => 'Ajax referer verification failed',
			)
		);
	}

	global $wp_query;
	$item_id = $wp_query->get( 'item_id' );
	$post    = get_post( $item_id );
	if ( is_wp_error( $post ) ) {
		wp_send_json(
			array(
				'status'  => 'error',
				'message' => 'Invalid Post ID',
			)
		);
	}
	wp_send_json(
		array(
			'status' => 'success',
			'post'   => $post,
		)
	);
	wp_die(); // All ajax handlers die when finished
}

add_action( 'init', 'aed_add_api_endpoints' );
function aed_add_api_endpoints() {
	add_rewrite_tag( '%api_item_id%', '([0-9]+)' );
	add_rewrite_rule( 'api/items/([0-9]+)/?', 'index.php?api_item_id=$matches[1]', 'top' );
	//flush_rewrite_rules(); //uncomment this if the api end point doesnt work, but then recomment it once you've refreshed the page once
}

/**
 * Handle data (maybe) passed to the API endpoint.
 */
add_action( 'template_redirect', 'aed_do_api' );
function aed_do_api() {
	global $wp_query;
	$item_id = $wp_query->get( 'api_item_id' );
	if ( empty( $item_id ) ) {
		return;
	}

	if ( empty( $item_id ) ) {
		wp_send_json(
			array(
				'status'  => 'error',
				'message' => 'Invalid Item ID',
			)
		);
	}

	$post = get_post( $item_id );
	if ( is_wp_error( $post ) ) {
		wp_send_json(
			array(
				'status'  => 'error',
				'message' => 'Invalid Post ID',
			)
		);
	}
	wp_send_json(
		array(
			'status' => 'success',
			'post'   => $post,
		)
	);
}
