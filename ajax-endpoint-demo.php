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
		<div>
			Ajax Test<br>
			<input type="submit" id="aed_submit_ajax" value="Submit">
		</div>
		<div>
			REST API Test<br>
			<input type="submit" id="aed_submit_rest" value="Submit">
		</div>
		<div>
			GraphQL Test<br>
			<input type="submit" id="aed_submit_graph" value="Submit">
		</div>
	</form>
	<?php
}

add_action( 'wp_enqueue_scripts', 'aed_enqueue' );
function aed_enqueue() {
	wp_enqueue_script(
		'aed-ajax-script',
		plugins_url( '/js/ajax.js', __FILE__ ),
		array( 'jquery' ),
		'1.0.1',
		true
	);
	wp_localize_script(
		'aed-ajax-script',
		'aed_ajax_object',
		array(
			'site_url' => site_url(),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);
}

add_action( 'wp_ajax_aed_action', 'aed_ajax_handler' );
add_action( 'wp_ajax_nopriv_aed_action', 'aed_ajax_handler' );
function aed_ajax_handler() {
	$posts = get_posts( array( 'numberposts' => 100 ) );
	if ( is_wp_error( $posts ) ) {
		wp_send_json(
			array(
				'status'  => 'error',
				'message' => 'Invalid Post ID',
			)
		);
	}
	wp_send_json(
		$posts
	);
	wp_die(); // All ajax handlers die when finished
}