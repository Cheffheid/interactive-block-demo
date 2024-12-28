<?php
/**
 * This file contains functions for API interactions and data formatting.
 * I am using the Open Library API for demonstration purposes.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo;

/**
 * This is the entry point for the search that is called from render.php to create the context but is also called from the AJAX handler.
 * It will return stored results if they are less than 15 minutes old, otherwise it will connect to the API to get fresh results.
 * And then it will use the provided keyword to filter the shows from the API and return them.
 *
 * @param string $keyword Keyword to use as the search query.
 * @return array
 */
function get_ibd_results( $keyword ) {
	if ( empty( $keyword ) ) {
		return array();
	}

	$api_results = get_transient( 'ibd_ol_search_results_' . $keyword );

	if ( ! $api_results ) {
		$api_results = connect_api( $keyword );
	} else {
		$api_results = base64_decode( $api_results ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
	}

	return format_results( $api_results );
}

/**
 * Make a request of the API. Rewritten to use wp_remote_get (was using curl before).
 *
 * @param string $query Keyword to query for.
 * @return array
 */
function connect_api( $query ) {
	$http_query = http_build_query(
		array(
			'q'      => rawurlencode( $query ),
			'limit'  => 10,
			'lang'   => 'en',
			'fields' => 'key,title,author_name,first_publish_year,cover_i,ebook_access',
		)
	);

	$request_url = 'https://openlibrary.org/search.json?' . $http_query;
	$results     = wp_remote_get( $request_url );

	if ( is_wp_error( $results ) ) {
		echo 'Error: ' . $results->get_error_message(); // phpcs:ignore
	}

	set_transient( 'ibd_ol_search_results_' . $query, base64_encode( $results['body'] ), 15 * MINUTE_IN_SECONDS ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode

	return $results['body'];
}

/**
 * Format the show data.
 *
 * @param string $results Array of shows that needs its data formatted.
 * @return array Formatted show data.
 */
function format_results( $results ) {
	$formatted_results = array();

	$results_json = json_decode( $results );
	$result_docs  = $results_json->docs;

	foreach ( $result_docs as $result ) {

		if ( ! isset( $result->author_name ) ) {
			continue;
		}

		$formatted = array(
			'key'             => str_replace( '/works/', '', $result->key ),
			'title'           => $result->title,
			'author'          => $result->author_name,
			'first_published' => $result->first_publish_year,
			'cover'           => get_book_cover_url_from_result( $result ),
			'has_cover'       => isset( $result->cover_i ),
			'link'            => esc_url( sprintf( 'https://openlibrary.org/%s', $result->key ) ),
			'has_ebook'       => 'no_ebook' !== $result->ebook_access && 'printdisabled' !== $result->ebook_access,
			'ebook'           => $result->ebook_access,
		);

		$formatted_results[] = $formatted;
	}

	return $formatted_results;
}

/**
 * Get the openlibrary cover image URL. Use responsibly. It won't check if it exists, and hotlinking is not great either.
 *
 * @param object $work The work as a decoded JSON object.
 * @return string
 */
function get_book_cover_url_from_result( $work = null ) {

	if ( ! $work || ! isset( $work->cover_i ) ) {
		return '';
	}

	$cover_id = $work->cover_i;

	return esc_attr( "//covers.openlibrary.org/b/id/${cover_id}-M.jpg" );
}

/**
 * Query the FOL API with a keyword and return formatted data for the block to display search results.
 *
 * @author Jeffrey de Wit
 * @since October 17, 2024
 */
function ajax_ibd_search_handler() {
	if ( ! check_ajax_referer( 'ajax_nonce_ibd', 'nonce', false ) ) {
		wp_send_json( '{}', 403 );
		die;
	}

	$results = get_ibd_results( $_POST['keyword'] );

	wp_send_json( $results, 200 );

	wp_die();
}
add_action( 'wp_ajax_ibd_ajax_search_handler', __NAMESPACE__ . '\ajax_ibd_search_handler' );
add_action( 'wp_ajax_nopriv_ibd_ajax_search_handler', __NAMESPACE__ . '\ajax_ibd_search_handler' );

/**
 * Interestingly, wp-a11y is not enqueued or recognized as a requirement of our view.js script module and needs to be enqueued manually.
 * It would be more ideal if this is done when the block is actually on the page,
 * but this is a tiny script that likely won't harm much in the vast majority of cases.
 *
 * @return void
 */
function enqueue_wpa11y_manually() {
	wp_enqueue_script( 'wp-a11y' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_wpa11y_manually' );
