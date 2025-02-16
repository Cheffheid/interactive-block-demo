<?php
/**
 * OpenLibrary API Connector class.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo\Api;

/**
 * OpenLibrary class definition. Extends ApiConnector.
 */
class OpenLibrary extends ApiConnector {

	/**
	 * API endpoint.
	 *
	 * @var string
	 */
	private $endpoint = 'https://openlibrary.org/search.json';

	/**
	 * Keyword string.
	 *
	 * @var string
	 */
	public $keyword = '';

	/**
	 * API search entry function. Expects a keyword string to search for.
	 *
	 * @param string $keyword Keyword for the search.
	 * @return string
	 */
	public function get_results( $keyword = '' ): string {
		$this->keyword = $keyword;

		$api_results = $this->connect_api();

		return $api_results;
	}

	/**
	 * Make a request of the API. Rewritten to use wp_remote_get (was using curl before).
	 *
	 * @return string
	 */
	protected function connect_api() {
		$http_query = http_build_query(
			array(
				'q'      => rawurlencode( $this->keyword ),
				'limit'  => 10,
				'lang'   => 'en',
				'fields' => 'key,title,author_name,first_publish_year,cover_i,ebook_access',
			)
		);

		$request_url = $this->endpoint . '?' . $http_query;
		$results     = wp_remote_get( $request_url );

		if ( is_wp_error( $results ) ) {
			return 'Error: ' . $results->get_error_message();
		}

		return $results['body'];
	}

	/**
	 * Format the show data.
	 *
	 * @param string $results Array of shows that needs its data formatted.
	 * @return array<array<string, mixed>> Formatted show data.
	 */
	public function format_results( $results ) {
		$formatted_results = array();

		$results_json = json_decode( $results );

		$result_docs = $results_json->docs;

		foreach ( $result_docs as $result ) {

			if ( ! isset( $result->author_name ) ) {
				continue;
			}

			$formatted = array(
				'key'             => str_replace( '/works/', '', $result->key ),
				'title'           => $result->title,
				'author'          => $result->author_name,
				'first_published' => $result->first_publish_year,
				'cover'           => $this->get_book_cover_url_from_result( $result ),
				'has_cover'       => isset( $result->cover_i ),
				'link'            => esc_url( sprintf( '%s/%s', $this->endpoint, $result->key ) ),
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
	private function get_book_cover_url_from_result( $work = null ) {

		if ( ! $work || ! isset( $work->cover_i ) ) {
			return '';
		}

		$cover_id = $work->cover_i;

		return esc_attr( "//covers.openlibrary.org/b/id/{$cover_id}-M.jpg" );
	}
}
